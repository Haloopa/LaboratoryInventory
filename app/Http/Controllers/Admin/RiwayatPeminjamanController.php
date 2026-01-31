<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatPeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua data peminjaman (semua status)
        $data = DB::table('peminjamans as p')
            ->select([
                'p.id as peminjaman_id',
                'p.user_id as user_id',
                'u.name as peminjam',
                DB::raw('COALESCE(mhs.nim, dsn.nip) as identitas'),
                'u.email as kontak',
                'p.status',
                'p.tanggal_pinjam',
                'p.tanggal_kembali'
            ])
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->leftJoin('profiles_mahasiswa as mhs', 'mhs.user_id', '=', 'u.id')
            ->leftJoin('profiles_dosen as dsn', 'dsn.user_id', '=', 'u.id')
            ->orderByDesc('p.tanggal_pinjam')
            ->get();

        // 2. Ambil detail barang untuk semua peminjaman sekaligus
        $peminjamanIds = $data->pluck('peminjaman_id')->toArray();
        
        $details = DB::table('peminjaman_details as d')
            ->select([
                'd.peminjaman_id',
                'i.nama_barang',
                'd.jumlah'
            ])
            ->join('inventories as i', 'i.id', '=', 'd.inventory_id')
            ->whereIn('d.peminjaman_id', $peminjamanIds)
            ->get()
            ->groupBy('peminjaman_id');

        // 3. Gabungkan data dan format items
        $data->transform(function ($item) use ($details) {
            $item->items = [];
            
            if (isset($details[$item->peminjaman_id])) {
                $item->items = $details[$item->peminjaman_id]->map(function ($detail) {
                    return [
                        'nama' => $detail->nama_barang,
                        'jumlah' => $detail->jumlah
                    ];
                })->toArray();
            }
            
            return $item;
        });

        // 4. Ambil role untuk semua user sekaligus
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

        // 6. Filter berdasarkan status jika ada parameter
        if ($request->has('status') && !empty($request->status)) {
            $data = $data->filter(function($item) use ($request) {
                return $item->status === $request->status;
            })->values();
        }

        // 7. Manual Pagination
        $page = $request->get('page', 1);
        $perPage = 10;

        $paginator = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.riwayat.index', ['data' => $paginator]);
    }
}