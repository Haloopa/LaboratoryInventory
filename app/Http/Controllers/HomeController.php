<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek role
        if ($user->hasAnyRole(['admin', 'super admin'])) {
            // redirect ke admin dashboard
            return redirect()->route('dashboard.admin');
        } else {
            // redirect ke user dashboard
            return redirect()->route('borrowing.dashboard');
        }
    }

    // Opsional: halaman dashboard admin
    public function adminDashboard()
    {
        $stats = [];

        $stats['total_items'] = Inventory::sum('jumlah');

        $stats['borrowed_items'] = DB::table('peminjaman_details as d')
            ->join('peminjamans as p', 'p.id', '=', 'd.peminjaman_id')
            ->where('p.status', 'dipinjam')
            ->sum('d.jumlah');

        $stats['overdue_items'] = DB::table('peminjaman_details as d')
            ->join('peminjamans as p', 'p.id', '=', 'd.peminjaman_id')
            ->where('p.status', 'dipinjam')
            ->where('p.tanggal_kembali', '<', Carbon::now())
            ->count();

        $stats['total_users'] = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'super admin']);
        })->count();

        $low_stock_items = Inventory::where('jumlah', '<=', 5)->get();

        $recent_activities = DB::table('peminjaman_details as d')
            ->join('peminjamans as p', 'p.id', '=', 'd.peminjaman_id')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->join('inventories as i', 'i.id', '=', 'd.inventory_id')
            ->select(
                'p.id as peminjaman_id',
                'u.name as peminjam',
                'i.nama_barang',
                'd.jumlah',
                'p.status',
                'p.created_at'
            )
            ->orderByDesc('p.created_at')
            ->limit(5) //
            ->get();

        return view('dashboard', compact('stats', 'low_stock_items', 'recent_activities'));
    }
}
