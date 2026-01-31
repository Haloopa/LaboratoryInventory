@extends('layouts.app')

@section('title', $product->name . ' - Stationery Shop')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Gambar produk -->
            <div class="col-md-6">
                <div class="card mb-4">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="max-height: 500px; object-fit: contain;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 500px;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info produk -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <span class="badge bg-primary mb-3">{{ $product->category->name }}</span>
                        <h1 class="card-title h2">{{ $product->name }}</h1>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= 4 ? '-fill' : '' }}"></i>
                                @endfor
                            </span>
                            <span class="text-muted">(4.5 / 5)</span>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                            <p class="text-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }} mb-0">
                                <i class="bi bi-{{ $product->stock > 0 ? 'check-circle' : 'x-circle' }}"></i>
                                {{ $product->stock > 0 ? $product->stock . ' unit tersedia' : 'Stok habis' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h5>Deskripsi Produk</h5>
                            <p class="card-text">{{ $product->description }}</p>
                        </div>

                        <!-- Cart Form -->
                        @if($product->stock > 0)
                        <form action="{{ route('cart.store', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row align-items-center mb-3">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label">Jumlah:</label>
                                </div>
                                <div class="col-auto">
                                    <div class="input-group" style="width: 140px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                                        <input type="number" 
                                               class="form-control text-center" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->stock }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>
                                <div class="col">
                                    <span class="text-muted small">Maks: {{ $product->stock }} unit</span>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                                </button>
                                <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-whatsapp"></i> Beli via WhatsApp
                                </a>
                            </div>
                        </form>
                        @else
                            <div class="d-grid gap-2">
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="bi bi-x-circle"></i> Stok Habis
                                </button>
                                <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-whatsapp"></i> Tanya Stok via WhatsApp
                                </a>
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-truck fs-4 text-primary"></i>
                                <p class="small mb-0">Gratis Ongkir</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-clockwise fs-4 text-primary"></i>
                                <p class="small mb-0">Garansi 7 Hari</p>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-shield-check fs-4 text-primary"></i>
                                <p class="small mb-0">Asli & Berkualitas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk serupa -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-5">
                <h3 class="mb-4">Produk Serupa</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3">
                            <div class="card h-100">
                                @if($relatedProduct->image)
                                    <img src="{{ asset($relatedProduct->image) }}" class="card-img-top product-image" alt="{{ $relatedProduct->name }}">
                                @else
                                    <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($relatedProduct->name, 30) }}</h5>
                                    <p class="card-text text-primary fw-bold">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-outline-primary w-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        document.getElementById('quantity').addEventListener('change', function() {
            const max = parseInt(this.max);
            const min = parseInt(this.min);
            let value = parseInt(this.value);
            
            if (value < min) value = min;
            if (value > max) value = max;
            
            this.value = value;
        });
    </script>
@endsection