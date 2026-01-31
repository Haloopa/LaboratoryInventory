<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanUserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $sedangDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        $pending = Peminjaman::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $totalRiwayat = Peminjaman::where('user_id', $user->id)
            ->count();

        $recentActivities = Peminjaman::with('details.inventory')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('borrowing.dashboard-user', compact(
            'sedangDipinjam',
            'pending',
            'totalRiwayat',
            'recentActivities'
        ));
    }

    public function index(Request $request)
    {
        $query = Inventory::where('jumlah', '>', 0);

        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $inventories = $query->paginate(10);

        $cart = session()->get('borrowing_cart', []);
        $cartCount = count($cart);

        return view('borrowing.pinjam', compact('inventories', 'cartCount'));
    }

    // Tambahkan barang ke keranjang
    public function addToCart(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Cek stok
        if ($request->jumlah > $inventory->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $inventory->jumlah
            ]);
        }

        $cart = session()->get('borrowing_cart', []);

        // Cek apakah barang sudah ada di cart
        $existingIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['inventory_id'] == $request->inventory_id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Update jumlah jika sudah ada
            $newJumlah = $cart[$existingIndex]['jumlah'] + $request->jumlah;

            // Cek lagi stok total
            if ($newJumlah > $inventory->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total jumlah melebihi stok. Stok tersedia: ' . $inventory->jumlah
                ]);
            }

            $cart[$existingIndex]['jumlah'] = $newJumlah;
        } else {
            // Tambah baru ke cart
            $cart[] = [
                'inventory_id' => $inventory->id,
                'nama_barang' => $inventory->nama_barang,
                'kode_barang' => $inventory->kode_barang ?? 'BAR-' . $inventory->id,
                'jumlah' => $request->jumlah,
                'max_stok' => $inventory->jumlah,
                'added_at' => now()
            ];
        }

        session(['borrowing_cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang!',
            'cart_count' => count($cart)
        ]);
    }

    //Menampilkan isi keranjang
    public function viewCart()
    {
        $cart = session()->get('borrowing_cart', []);

        $cartItems = [];
        foreach ($cart as $item) {
            $inventory = Inventory::find($item['inventory_id']);
            if ($inventory) {
                $cartItems[] = [
                    'id' => $item['inventory_id'],
                    'nama_barang' => $inventory->nama_barang,
                    'kode_barang' => $item['kode_barang'],
                    'jumlah' => $item['jumlah'],
                    'max_stok' => $inventory->jumlah
                ];
            }
        }

        return view('borrowing.cart', compact('cartItems'));
    }

    // Update isi keranjang
    public function updateCart(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'action' => 'required|in:increment,decrement'
        ]);

        $cart = session()->get('borrowing_cart', []);
        $inventory = Inventory::find($request->inventory_id);

        foreach ($cart as $index => $item) {
            if ($item['inventory_id'] == $request->inventory_id) {
                if ($request->action == 'increment') {
                    if (($item['jumlah'] + 1) > $inventory->jumlah) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $inventory->jumlah
                        ]);
                    }
                    $cart[$index]['jumlah'] += 1;
                } elseif ($request->action == 'decrement' && $cart[$index]['jumlah'] > 1) {
                    $cart[$index]['jumlah'] -= 1;
                }
                break;
            }
        }

        session(['borrowing_cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
    }

    // Hapus item dari keranjang
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id'
        ]);

        $cart = session()->get('borrowing_cart', []);

        $cart = array_filter($cart, function ($item) use ($request) {
            return $item['inventory_id'] != $request->inventory_id;
        });

        $cart = array_values($cart);

        session(['borrowing_cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus dari keranjang',
            'cart_count' => count($cart)
        ]);
    }

    // Mengosongkan keranjang
    public function clearCart()
    {
        session()->forget('borrowing_cart');

        return redirect()->route('borrowing.cart')
            ->with('success', 'Keranjang berhasil dikosongkan');
    }

    // Submit peminjaman
    public function submitPeminjaman(Request $request)
    {
        $user = Auth::user();
        $cart = session()->get('borrowing_cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }

        // Validasi stok sebelum submit
        foreach ($cart as $item) {
            $inventory = Inventory::find($item['inventory_id']);
            if (!$inventory || $item['jumlah'] > $inventory->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok ' . ($inventory->nama_barang ?? 'barang') . ' tidak mencukupi'
                ], 400);
            }
        }

        try {
            // Buat peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => null
            ]);

            // Simpan detail peminjaman
            foreach ($cart as $item) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'inventory_id' => $item['inventory_id'],
                    'jumlah' => $item['jumlah']
                ]);
            }

            // Kosongkan keranjang
            session()->forget('borrowing_cart');

            return response()->json([
                'success' => true,
                'message' => 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan admin.',
                'peminjaman_id' => $peminjaman->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $riwayat = Peminjaman::with(['details.inventory'])
            ->where('user_id', $user->id)
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('borrowing.riwayat', compact('riwayat'));
    }





    public function approvalIndex()
    {
        $ajuan = DB::table('peminjamans')
            ->join('users', 'users.id', '=', 'peminjamans.user_id')
            ->join('peminjaman_details', 'peminjaman_details.peminjaman_id', '=', 'peminjamans.id')
            ->join('inventories', 'inventories.id', '=', 'peminjaman_details.inventory_id')
            ->select(
                'peminjamans.id as peminjaman_id',
                'users.name as peminjam',
                'users.email',
                'inventories.nama_barang',
                'peminjaman_details.jumlah',
                'peminjamans.status',
                'peminjamans.tanggal_pinjam as tanggal_pinjam',
                'peminjamans.tanggal_kembali'
            )
            ->get();

        return view('admin.approval-peminjaman', ['ajuan' => $ajuan]);
    }

    public function approve($id)
    {
        DB::table('peminjamans')->where('id', $id)->update([
            'status' => 'dipinjam',
            'tanggal_kembali' => now()
        ]);

        return back()->with('success', 'Ajuan peminjaman berhasil di ACC oleh admin.');
    }

    public function reject($id)
    {
        DB::table('peminjamans')->where('id', $id)->update([
            'status' => 'kembali',
            'tanggal_kembali' => now()
        ]);

        return back()->with('error', 'Ajuan peminjaman tidak disetujui dan status diubah menjadi kembali.');
    }


}
