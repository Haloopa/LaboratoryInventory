@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3 rounded-top">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3">
                            <i class="bi bi-bag-check text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Pesanan Saya</h4>
                            <p class="mb-0 small opacity-75">Riwayat semua pemesanan Anda</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="text-center py-5 px-4">
                            <div class="empty-state">
                                <i class="bi bi-bag display-1 text-muted mb-4"></i>
                                <h4 class="mb-3">Belum ada pesanan</h4>
                                <p class="text-muted mb-4">Mulai belanja dan buat pesanan pertama Anda</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary px-4">
                                    <i class="bi bi-bag me-2"></i> Lihat Produk
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Order stats -->
                        <div class="row g-3 px-4 pt-4">
                            <div class="col-md-3">
                                <div class="stat-box p-3 rounded text-center">
                                    <div class="stat-number text-primary fw-bold fs-4">{{ $orders->total() }}</div>
                                    <div class="stat-label text-muted small">Total Pesanan</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box p-3 rounded text-center">
                                    <div class="stat-number text-success fw-bold fs-4">
                                        {{ $orders->where('status', 'completed')->count() }}
                                    </div>
                                    <div class="stat-label text-muted small">Selesai</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box p-3 rounded text-center">
                                    <div class="stat-number text-warning fw-bold fs-4">
                                        {{ $orders->whereIn('status', ['pending', 'processing'])->count() }}
                                    </div>
                                    <div class="stat-label text-muted small">Diproses</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box p-3 rounded text-center">
                                    <div class="stat-number text-info fw-bold fs-4">
                                        Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}
                                    </div>
                                    <div class="stat-label text-muted small">Total Belanja</div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders list -->
                        <div class="px-4 py-3">
                            <h6 class="fw-semibold mb-3 text-muted">
                                <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan
                            </h6>
                            
                            <div class="orders-list">
                                @foreach($orders as $order)
                                <div class="order-card mb-3 border rounded">
                                    <div class="order-header p-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-primary">{{ $order->order_number }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $order->created_at->format('d F Y, H:i') }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-{{ $order->status_badge }} fs-6 px-3 py-2">
                                                    {{ $order->status_text }}
                                                </span>
                                                <div class="mt-2">
                                                    <small class="text-muted">{{ $order->items_count }} item</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="order-body p-3">
                                        <!-- Order items -->
                                        <div class="mb-3">
                                            @foreach($order->items->take(2) as $item)
                                            <div class="d-flex align-items-center mb-2">
                                                @if($item->product->image)
                                                    <img src="{{ asset($item->product->image) }}" 
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between">
                                                        <span>{{ $item->product->name }}</span>
                                                        <span class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </small>
                                                </div>
                                            </div>
                                            @endforeach
                                            
                                            @if($order->items->count() > 2)
                                                <div class="text-center mt-2">
                                                    <small class="text-muted">
                                                        +{{ $order->items->count() - 2 }} item lainnya
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Order summary -->
                                        <div class="border-top pt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="fw-bold text-primary fs-5">
                                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                <div class="btn-group">
                                                    <a href="{{ route('orders.show', $order) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-eye me-1"></i> Detail
                                                    </a>
                                                    @if($order->status === 'pending')
                                                    <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                                                       target="_blank" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="bi bi-whatsapp me-1"></i> Konfirmasi
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-0">
                                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 
                                    dari {{ $orders->total() }} pesanan
                                </p>
                            </div>
                            <div>
                                {{ $orders->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .order-card {
        transition: all 0.3s ease;
        background: white;
    }
    
    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .stat-box {
        background: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .stat-box:hover {
        background: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    
    .empty-state {
        max-width: 400px;
        margin: 0 auto;
    }
    
    .orders-list {
        max-height: 600px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .orders-list::-webkit-scrollbar {
        width: 5px;
    }
    
    .orders-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .orders-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .orders-list::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header.rounded-top {
        border-radius: 10px 10px 0 0 !important;
    }
</style>
@endsection