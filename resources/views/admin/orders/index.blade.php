@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="page-header-admin mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">Kelola Pesanan</h2>
            <p class="text-muted mb-0">Daftar semua pesanan dari customer</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-outline">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Stats cards-->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2">{{ number_format($stats['total']) }}</h3>
            <p class="stat-label mb-0">Total Pesanan</p>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2">{{ number_format($stats['pending']) }}</h3>
            <p class="stat-label mb-0">Pending</p>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2">{{ number_format($stats['processing']) }}</h3>
            <p class="stat-label mb-0">Diproses</p>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2">{{ number_format($stats['completed']) }}</h3>
            <p class="stat-label mb-0">Selesai</p>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2">{{ number_format($stats['cancelled']) }}</h3>
            <p class="stat-label mb-0">Dibatalkan</p>
        </div>
    </div>
    
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="stat-card-admin h-100 text-center">
            <h3 class="stat-number mb-2 text-truncate">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
            <p class="stat-label mb-0">Revenue</p>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="admin-card">
    <div class="card-body">

        <!-- Filter & Search -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0" 
                               placeholder="Cari no. order atau nama customer..."
                               value="{{ request('search') }}">
                    </div>
                    
                    <select name="status" class="form-select" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    
                    <button type="submit" class="btn btn-admin-primary px-4">
                        Filter
                    </button>
                    
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Table order -->
        <div class="table-responsive">
            <table class="table table-hover admin-table">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Customer</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ];
                        
                        $statusTexts = [
                            'pending' => 'Menunggu',
                            'processing' => 'Diproses',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ];
                    @endphp
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $order->order_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $order->items->count() ?? 0 }}</span>
                        </td>
                        <td class="text-end fw-semibold">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ $statusTexts[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{ $order->created_at->format('d/m/Y') }}
                            <br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="btn btn-sm btn-admin-outline px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                
                                <form action="{{ route('admin.orders.destroy', $order) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger px-3">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-bag display-6 text-muted"></i>
                            <h5 class="mt-3">Belum ada pesanan</h5>
                            @if(request()->hasAny(['search', 'status']))
                                <p class="text-muted">Coba ubah filter pencarian Anda</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <p class="mb-0 text-muted">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan
                </p>
            </div>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Stat Cards*/
    .stat-card-admin {
        background: white;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-admin:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card-admin .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }
    
    .stat-card-admin .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.2;
        margin-bottom: 0;
    }
    
    /* Border color */
    .stat-card-admin:nth-child(1) { border-left-color: #0d6efd; }
    .stat-card-admin:nth-child(2) { border-left-color: #ffc107; }
    .stat-card-admin:nth-child(3) { border-left-color: #0dcaf0; }
    .stat-card-admin:nth-child(4) { border-left-color: #20c997; }
    .stat-card-admin:nth-child(5) { border-left-color: #dc3545; }
    .stat-card-admin:nth-child(6) { border-left-color: #6f42c1; }
    
    /* Background hover */
    .stat-card-admin:nth-child(1):hover { background-color: rgba(13, 110, 253, 0.03); }
    .stat-card-admin:nth-child(2):hover { background-color: rgba(255, 193, 7, 0.03); }
    .stat-card-admin:nth-child(3):hover { background-color: rgba(13, 202, 240, 0.03); }
    .stat-card-admin:nth-child(4):hover { background-color: rgba(32, 201, 151, 0.03); }
    .stat-card-admin:nth-child(5):hover { background-color: rgba(220, 53, 69, 0.03); }
    .stat-card-admin:nth-child(6):hover { background-color: rgba(111, 66, 193, 0.03); }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .stat-card-admin {
            padding: 1.25rem 0.875rem;
        }
        
        .stat-card-admin .stat-number {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .stat-card-admin {
            padding: 1rem 0.75rem;
        }
        
        .stat-card-admin .stat-number {
            font-size: 1.35rem;
        }
        
        .stat-card-admin .stat-label {
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .stat-card-admin {
            padding: 0.875rem 0.625rem;
        }
        
        .stat-card-admin .stat-number {
            font-size: 1.25rem;
        }
        
        .stat-card-admin .stat-label {
            font-size: 0.8rem;
        }
    }
    
    /* Table */
    .admin-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    .admin-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }
    
    .admin-table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 4px;
    }
</style>
@endpush