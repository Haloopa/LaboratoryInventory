@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="page-header-admin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Kelola Kategori</h2>
                <p class="text-muted mb-0">Daftar semua kategori produk</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
                <i class="bi bi-plus-circle me-2"></i> Tambah Kategori
            </a>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Produk</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <p class="text-muted mb-0 small">{{ Str::limit($category->description, 50) }}</p>
                                @endif
                            </td>
                            <td>
                                <code>{{ $category->slug }}</code>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $category->products_count }}</span> produk
                            </td>
                            <td>{{ $category->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-admin-outline px-3">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-tags display-6 text-muted"></i>
                                <h5 class="mt-3">Belum ada kategori</h5>
                                <p class="text-muted">Mulai dengan menambahkan kategori pertama Anda</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Kategori Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection