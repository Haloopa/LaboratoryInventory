@extends('layouts.app')

@section('title', 'Produk - Stationery Shop')

@section('content')
    <div class="container py-4">
        <div class="row">
            <!-- Sidebar kategori -->
            <div class="col-md-3">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-tags me-2"></i> Kategori
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.index') }}" 
                           class="list-group-item list-group-item-action border-0 py-3 {{ !request('category') || request('category') == 'all' ? 'active bg-primary text-white' : '' }}">
                            <i class="bi bi-grid me-2"></i> Semua Kategori
                            <span class="badge bg-secondary float-end">{{ $categories->sum('products_count') }}</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                               class="list-group-item list-group-item-action border-0 py-3 {{ request('category') == $category->slug ? 'active bg-primary text-white' : '' }}">
                                <i class="bi bi-tag me-2"></i> {{ $category->name }}
                                <span class="badge bg-secondary float-end">{{ $category->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Filter stok -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-filter me-2"></i> Filter Stok
                        </h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('products.index', array_merge(request()->except('stock'), ['stock' => 'available'])) }}" 
                           class="list-group-item list-group-item-action border-0 py-2 {{ request('stock') == 'available' ? 'active' : '' }}">
                            <i class="bi bi-check-circle me-2"></i> Stok Tersedia
                        </a>
                        <a href="{{ route('products.index', array_merge(request()->except('stock'), ['stock' => 'low'])) }}" 
                           class="list-group-item list-group-item-action border-0 py-2 {{ request('stock') == 'low' ? 'active' : '' }}">
                            <i class="bi bi-exclamation-triangle me-2"></i> Stok Rendah (<10)
                        </a>
                        <a href="{{ route('products.index', request()->except('stock')) }}" 
                           class="list-group-item list-group-item-action border-0 py-2 text-primary">
                            <i class="bi bi-x-circle me-2"></i> Hapus Filter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daftar produk -->
            <div class="col-md-9">
                <!-- Header dengan search -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="h4 fw-bold">Semua Produk</h2>
                        <p class="text-muted mb-0 small">
                            @if(request('search'))
                                Hasil pencarian untuk "{{ request('search') }}"
                            @endif
                            @if(request('category') && isset($currentCategory))
                                Kategori: {{ $currentCategory->name }}
                            @endif
                        </p>
                    </div>
                    
                    <div class="w-100 w-md-auto">
                        <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0" 
                                       name="search" 
                                       placeholder="Cari produk..." 
                                       value="{{ request('search') }}">
                                @if(request()->hasAny(['search', 'category', 'stock']))
                                    <a href="{{ route('products.index') }}" class="input-group-text bg-white text-danger">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-search me-1 d-none d-md-inline"></i> Cari
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info hasil filter -->
                @if(request()->hasAny(['search', 'category', 'stock']))
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                Menampilkan 
                                @if(request('search'))
                                    hasil pencarian "{{ request('search') }}"
                                @endif
                                @if(request('category') && isset($currentCategory))
                                    dalam kategori {{ $currentCategory->name }}
                                @endif
                                @if(request('stock') == 'available')
                                    dengan stok tersedia
                                @elseif(request('stock') == 'low')
                                    dengan stok rendah
                                @endif
                                <span class="badge bg-primary ms-2">{{ $products->total() }} produk</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-search display-6 text-muted mb-3"></i>
                        <h4 class="mb-3">Produk tidak ditemukan</h4>
                        <p class="text-muted mb-4">Coba gunakan kata kunci lain atau lihat kategori lainnya</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Lihat Semua Produk
                        </a>
                    </div>
                @else
                    <!-- Grid produk -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($products as $product)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <!-- Badge stok -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                            {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Gambar produk -->
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" 
                                             class="card-img-top product-image" 
                                             alt="{{ $product->name }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge kategori -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                                    </div>

                                    <!-- Konten card -->
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="card-title fw-semibold mb-2">{{ Str::limit($product->name, 40) }}</h5>
                                        <p class="card-text text-muted small mb-3 flex-grow-1">
                                            {{ Str::limit($product->description, 70) }}
                                        </p>
                                        
                                        <!-- Harga -->
                                        <div class="mb-3">
                                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                        </div>

                                        <!-- Tombol aksi -->
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('products.show', $product->slug) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye me-1"></i> Lihat Detail
                                            </a>
                                            <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                                               target="_blank" 
                                               class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-whatsapp me-1"></i> Beli via WhatsApp
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <p class="text-muted small mb-0">
                                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                                    dari {{ $products->total() }} produk
                                </p>
                            </div>
                            <div>
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

<style>
    .product-image {
        transition: transform 0.3s ease;
    }
    
    .card:hover .product-image {
        transform: scale(1.03);
    }
    
    .list-group-item.active {
        border-left: 4px solid #0d6efd;
    }
    
    .list-group-item {
        border-radius: 0;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>