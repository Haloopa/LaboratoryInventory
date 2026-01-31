@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-person-circle me-2"></i> Profil Saya
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

                    <!-- Informasi user -->
                    <div class="user-info">
                        <div class="mb-3">
                            <label class="form-label text-muted mb-2 small fw-medium">Nama Lengkap</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person me-2 text-primary"></i>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted mb-2 small fw-medium">Email</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted mb-2 small fw-medium">Bergabung Sejak</label>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar3 me-2 text-primary"></i>
                                <span>{{ $user->created_at->format('d/m/Y') }}</span>
                                <small class="text-muted ms-2">({{ $user->created_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('store') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
    }
    
    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }
    
    hr {
        opacity: 0.1;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .badge.small {
        font-size: 0.75rem;
        padding: 0.25em 0.5em;
    }
    
    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .user-info span:not(.badge) {
        font-size: 1rem;
        color: #212529;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endsection