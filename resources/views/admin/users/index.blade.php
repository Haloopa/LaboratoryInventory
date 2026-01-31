@extends('layouts.admin')

@section('title', 'Kelola Pengguna/Admin')

@section('content')
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    
    <div class="page-header-admin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Kelola Pengguna/Admin</h2>
                <p class="text-muted mb-0">Daftar semua user/admin</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-admin-primary me-2">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Admin
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-admin-outline">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

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
                                   placeholder="Cari nama atau email..."
                                   value="{{ request('search') }}">
                        </div>
                        
                        <select name="role" class="form-select" style="width: auto;">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                        
                        <button type="submit" class="btn btn-admin-primary px-4">
                            Filter
                        </button>
                        
                        @if(request()->hasAny(['search', 'role']))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
                
                <div class="col-md-4 text-end">
                    <div class="h-100 d-flex align-items-center justify-content-end">
                        <span class="badge bg-info me-2">{{ $users->total() }} total user</span>
                        <span class="badge bg-primary me-2">{{ App\Models\User::where('role', 'admin')->count() }} admin</span>
                        <span class="badge bg-secondary">{{ App\Models\User::where('role', 'user')->count() }} customer</span>
                    </div>
                </div>
            </div>

            <!-- Table user -->
            <div class="table-responsive">
                <table class="table table-hover admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Bergabung</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === Auth::id())
                                            <span class="badge bg-warning ms-1">Anda</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $user->email }}
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">✓ Terverifikasi</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Customer' }}
                                </span>
                            </td>
                            <td>
                                {{ $user->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm btn-admin-outline px-3">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-3">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-outline-secondary px-3" disabled>
                                        <i class="bi bi-shield-lock"></i> Diri Sendiri
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-people display-6 text-muted"></i>
                                <h5 class="mt-3">Tidak ada user ditemukan</h5>
                                @if(request()->hasAny(['search', 'role']))
                                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="mb-0 text-muted">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                    </p>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection