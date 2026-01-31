@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    
    <div class="page-header-admin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Edit User</h2>
                <p class="text-muted mb-0">Edit data user/pelanggan</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="avatar-preview">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Customer</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Akun</label>
                                <div class="form-control-plaintext">
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Email Terverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock"></i> Belum Verifikasi
                                        </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        Bergabung: {{ $user->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Perhatian:</strong> Mengubah role user ke "Admin" akan memberikan akses penuh ke panel admin.
                </div>
                
                @if($user->id === Auth::id())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Anda sedang mengedit akun sendiri. Hati-hati saat mengubah role.
                </div>
                @endif
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-admin-outline px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-admin-primary px-4">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection