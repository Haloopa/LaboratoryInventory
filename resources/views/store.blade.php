@extends('layouts.app')

@section('title', 'Stationery Shop - Toko Alat Tulis Terlengkap')

@section('content')
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Selamat Datang di StationeryShop</h1>
            <p class="lead">Temukan alat tulis berkualitas untuk kebutuhan belajar dan kerja Anda</p>
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                    Belanja Sekarang <i class="bi bi-arrow-right"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg ms-2">
                        <i class="bi bi-person-plus"></i> Daftar Gratis
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Kategori produk -->
    <div class="container mb-5">
        <h2 class="text-center mb-4">Kategori Produk</h2>
        <div class="row justify-content-center g-3">
            @foreach($categories as $category)
                <div class="col-auto">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="category-badge">
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Iklan pendaftaran -->
    @guest
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow">
                    <div class="card-body p-5 text-center">
                        <h2 class="mb-4">Mengapa Harus Daftar?</h2>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="p-3">
                                    <i class="bi bi-truck display-6 text-primary"></i>
                                    <h5 class="mt-3">Gratis Ongkir</h5>
                                    <p class="text-muted">Untuk member baru</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3">
                                    <i class="bi bi-percent display-6 text-primary"></i>
                                    <h5 class="mt-3">Diskon Spesial</h5>
                                    <p class="text-muted">Harga khusus member</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3">
                                    <i class="bi bi-clock-history display-6 text-primary"></i>
                                    <h5 class="mt-3">Riwayat Belanja</h5>
                                    <p class="text-muted">Lacak pesanan Anda</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-3">
                                    <i class="bi bi-star display-6 text-primary"></i>
                                    <h5 class="mt-3">Poin Reward</h5>
                                    <p class="text-muted">Tukar dengan hadiah</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-4">
                            <i class="bi bi-person-plus"></i> Daftar Sekarang Gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Produk Unggulan -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Produk Unggulan</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        @else
                            <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            @if($product->category)
                                <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                            @endif
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted flex-grow-1" style="font-size: 0.9rem;">
                                {{ Str::limit($product->description, 50) }}
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                        Stok: {{ $product->stock }}
                                    </span>
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary w-100">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Belum ada produk</h4>
                    <p>Produk akan segera tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection