@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-cart3 me-2"></i> Keranjang Belanja
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Kondisi keranjang kosong -->
                    @if($carts->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x display-5 text-muted mb-3"></i>
                            <h5 class="mb-3">Keranjang belanja kosong</h5>
                            <p class="text-muted mb-4">Tambahkan produk ke keranjang untuk mulai berbelanja</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="bi bi-bag me-1"></i> Lihat Produk
                            </a>
                        </div>
                    @else
                        <!-- Table produk cart -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carts as $index => $cart)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($cart->product->image)
                                                    <img src="{{ asset($cart->product->image) }}" 
                                                         class="me-3 rounded" 
                                                         alt="{{ $cart->product->name }}"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $cart->product->name }}</h6>
                                                    <small class="text-muted">{{ $cart->product->category->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center" style="width: 120px;">
                                            <form action="{{ route('cart.update', $cart) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $cart->quantity }}" 
                                                           min="1" 
                                                           max="{{ $cart->product->stock }}"
                                                           class="form-control form-control-sm text-center"
                                                           onchange="this.form.submit()">
                                                </div>
                                            </form>
                                            <small class="text-muted">Stok: {{ $cart->product->stock }}</small>
                                        </td>
                                        <td class="text-center fw-semibold text-primary">
                                            Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end mt-3">
                            <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                                    <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="z-index: 1020;"> {{-- z-index lebih rendah dari dropdown --}}
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-receipt me-2"></i> Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pengiriman</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-semibold">Total</span>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if(!$carts->isEmpty())
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i> Lanjut Belanja
                            </a>
                            
                            <form action="{{ route('checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-whatsapp me-1"></i> Checkout via WhatsApp
                                </button>
                            </form>
                            
                            <div class="alert alert-info small mb-0 mt-2">
                                <i class="bi bi-info-circle me-2"></i>
                                Anda akan diarahkan ke WhatsApp untuk konfirmasi pesanan.
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> Stok produk akan ditahan selama 24 jam.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection