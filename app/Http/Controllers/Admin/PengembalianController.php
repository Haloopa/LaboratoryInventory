<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PengembalianController extends Controller
{
    public function index()
    {
        // 1. Ambil data peminjaman yang sedang dipinjam
        $data = DB::table('peminjamans as p')
            ->select([
                'p.id as peminjaman_id',
                'p.user_id as user_id',
                'u.name as peminjam',
                DB::raw("CASE 
                    WHEN m.nim IS NOT NULL THEN m.nim 
                    ELSE ds.nip 
                END as identitas"),
                'p.status',
                'p.tanggal_pinjam',
                'p.tanggal_kembali'
            ])
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->leftJoin('profiles_mahasiswa as m', 'm.user_id', '=', 'u.id')
            ->leftJoin('profiles_dosen as ds', 'ds.user_id', '=', 'u.id')
            ->where('p.status', 'dipinjam')
            ->orderBy('p.tanggal_pinjam', 'asc')
            ->get();

        // 2. Ambil detail barang untuk semua peminjaman sekaligus
        $peminjamanIds = $data->pluck('peminjaman_id')->toArray();
        
        $details = DB::table('peminjaman_details as d')
            ->select([
                'd.peminjaman_id',
                'i.nama_barang as barang',
                'd.jumlah'
            ])
            ->join('inventories as i', 'i.id', '=', 'd.inventory_id')
            ->whereIn('d.peminjaman_id', $peminjamanIds)
            ->get()
            ->groupBy('peminjaman_id');

        // 3. Gabungkan data dan format detail barang
        $data->transform(function ($item) use ($details) {
            $item->detail_barang = [];
            
            if (isset($details[$item->peminjaman_id])) {
                $item->detail_barang = $details[$item->peminjaman_id]->map(function ($detail) {
                    return [
                        'barang' => $detail->barang,
                        'jumlah' => $detail->jumlah
                    ];
                })->toArray();
            }
            
            return $item;
        });

        // 4. Ambil role untuk semua user sekaligus (lebih efisien)
        $userIds = $data->pluck('user_id')->unique()->toArray();
        $usersWithRoles = User::whereIn('id', $userIds)
            ->with('roles')
            ->get()
            ->keyBy('id');

        // 5. Tambahkan role ke tiap item
        $data->transform(function ($item) use ($usersWithRoles) {
            $user = $usersWithRoles[$item->user_id] ?? null;
            $item->role = $user ? $user->roles->first()->name ?? '-' : '-';
            return $item;
        });

        // 6. Manual Pagination
        $page = request()->get('page', 1);
        $perPage = 10;

        $paginator = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.pengembalian.index', ['ajuan' => $paginator]);
    }

    public function updateTanggalKembali(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => 'nullable|date'
        ]);

        $tanggal = $request->tanggal_kembali;
        
        // Jika tanggal kosong, gunakan tanggal hari ini
        if (empty($tanggal)) {
            $tanggal = now()->toDateString();
        }

        DB::beginTransaction();
        try {
            // Cari peminjaman dengan detailnya
            $peminjaman = Peminjaman::with(['details.inventory'])->findOrFail($id);
            
            // Update status dan tanggal kembali
            $peminjaman->update([
                'tanggal_kembali' => $tanggal,
                'status' => 'kembali'
            ]);

            // Kembalikan stok inventory
            foreach ($peminjaman->details as $detail) {
                if ($detail->inventory) {
                    $detail->inventory->increment('jumlah', $detail->jumlah);
                }
            }

            DB::commit();
            return back()->with('success', 'Pengembalian berhasil diproses dan stok telah dikembalikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pengembalian: ' . $e->getMessage());
        }
    }
}