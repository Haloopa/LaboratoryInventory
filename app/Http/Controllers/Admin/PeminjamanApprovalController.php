<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PeminjamanApprovalController extends Controller
{
    public function index()
    {
        // 1. Ambil data peminjaman yang pending dengan metode yang kompatibel MariaDB 10.4
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
            ->where('p.status', 'pending')
            ->orderBy('p.tanggal_pinjam', 'asc')
            ->get();

        // 2. Ambil detail barang untuk setiap peminjaman secara terpisah
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
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        // 7. Kirim ke view
        return view('admin.approval.index', ['ajuan' => $paginator]);
    }

    public function approve($id)
    {
        $peminjaman = Peminjaman::with(['details.inventory'])->findOrFail($id);

        // Validasi stok untuk setiap item
        foreach ($peminjaman->details as $detail) {
            $inventory = $detail->inventory;
            if (!$inventory || $detail->jumlah > $inventory->jumlah) {
                return back()->with('error', 'Stok ' . ($inventory->nama_barang ?? 'barang') . ' tidak mencukupi');
            }
        }

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Update status peminjaman
            $peminjaman->update(['status' => 'dipinjam']);

            // Kurangi stok inventory
            foreach ($peminjaman->details as $detail) {
                $inventory = $detail->inventory;
                $inventory->decrement('jumlah', $detail->jumlah);
            }

            DB::commit();
            return back()->with('success', 'Pengajuan peminjaman berhasil di-ACC dan status berubah menjadi dipinjam');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $peminjaman->update(['status' => 'ditolak']);

        return back()->with('success', 'Pengajuan peminjaman berhasil ditolak');
    }

    public function process(Request $request, $id)
    {
        $action = $request->action;

        if ($action === 'approve') {
            $request->validate([
                'signed_pdf' => 'required|mimes:pdf|max:2048'
            ]);

            DB::beginTransaction();
            try {
                // Upload file surat balasan
                $file = $request->file('signed_pdf');
                $filename = 'surat-balasan-' . $id . '-' . time() . '.pdf';
                $path = $file->storeAs('surat-balasan', $filename);

                // Update status peminjaman
                DB::table('peminjamans')->where('id', $id)->update(['status' => 'dipinjam']);

                // Simpan informasi surat balasan
                DB::table('surat_peminjamans')->updateOrInsert(
                    ['peminjaman_id' => $id],
                    [
                        'signed_response_path' => $path,
                        'signed_response_name' => $filename,
                        'signed_at' => now()
                    ]
                );

                DB::commit();
                return back()->with('success', 'Pengajuan di-ACC dan surat balasan berhasil diupload');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan saat memproses: ' . $e->getMessage());
            }
        }

        return back()->with('info', 'Aksi tidak valid');
    }
}