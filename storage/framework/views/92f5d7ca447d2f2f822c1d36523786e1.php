

<?php $__env->startSection('title', 'Produk - Stationery Shop'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row">
            <!-- Sidebar Kategori -->
            <div class="col-md-3">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-tags me-2"></i> Kategori
                        </h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('products.index')); ?>" 
                           class="list-group-item list-group-item-action border-0 py-3 <?php echo e(!request('category') || request('category') == 'all' ? 'active bg-primary text-white' : ''); ?>">
                            <i class="bi bi-grid me-2"></i> Semua Kategori
                            <span class="badge bg-secondary float-end"><?php echo e($categories->sum('products_count')); ?></span>
                        </a>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('products.index', ['category' => $category->slug])); ?>" 
                               class="list-group-item list-group-item-action border-0 py-3 <?php echo e(request('category') == $category->slug ? 'active bg-primary text-white' : ''); ?>">
                                <i class="bi bi-tag me-2"></i> <?php echo e($category->name); ?>

                                <span class="badge bg-secondary float-end"><?php echo e($category->products_count); ?></span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Filter Stok -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-filter me-2"></i> Filter Stok
                        </h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('products.index', array_merge(request()->except('stock'), ['stock' => 'available']))); ?>" 
                           class="list-group-item list-group-item-action border-0 py-2 <?php echo e(request('stock') == 'available' ? 'active' : ''); ?>">
                            <i class="bi bi-check-circle me-2"></i> Stok Tersedia
                        </a>
                        <a href="<?php echo e(route('products.index', array_merge(request()->except('stock'), ['stock' => 'low']))); ?>" 
                           class="list-group-item list-group-item-action border-0 py-2 <?php echo e(request('stock') == 'low' ? 'active' : ''); ?>">
                            <i class="bi bi-exclamation-triangle me-2"></i> Stok Rendah (<10)
                        </a>
                        <a href="<?php echo e(route('products.index', request()->except('stock'))); ?>" 
                           class="list-group-item list-group-item-action border-0 py-2 text-primary">
                            <i class="bi bi-x-circle me-2"></i> Hapus Filter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="col-md-9">
                <!-- Header dengan Search -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                    <div class="mb-3 mb-md-0">
                        <h2 class="h4 fw-bold">Semua Produk</h2>
                        <p class="text-muted mb-0 small">
                            <?php if(request('search')): ?>
                                Hasil pencarian untuk "<?php echo e(request('search')); ?>"
                            <?php endif; ?>
                            <?php if(request('category') && isset($currentCategory)): ?>
                                Kategori: <?php echo e($currentCategory->name); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="w-100 w-md-auto">
                        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0" 
                                       name="search" 
                                       placeholder="Cari produk..." 
                                       value="<?php echo e(request('search')); ?>">
                                <?php if(request()->hasAny(['search', 'category', 'stock'])): ?>
                                    <a href="<?php echo e(route('products.index')); ?>" class="input-group-text bg-white text-danger">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="bi bi-search me-1 d-none d-md-inline"></i> Cari
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Hasil Filter -->
                <?php if(request()->hasAny(['search', 'category', 'stock'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                Menampilkan 
                                <?php if(request('search')): ?>
                                    hasil pencarian "<?php echo e(request('search')); ?>"
                                <?php endif; ?>
                                <?php if(request('category') && isset($currentCategory)): ?>
                                    dalam kategori <?php echo e($currentCategory->name); ?>

                                <?php endif; ?>
                                <?php if(request('stock') == 'available'): ?>
                                    dengan stok tersedia
                                <?php elseif(request('stock') == 'low'): ?>
                                    dengan stok rendah
                                <?php endif; ?>
                                <span class="badge bg-primary ms-2"><?php echo e($products->total()); ?> produk</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($products->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-search display-6 text-muted mb-3"></i>
                        <h4 class="mb-3">Produk tidak ditemukan</h4>
                        <p class="text-muted mb-4">Coba gunakan kata kunci lain atau lihat kategori lainnya</p>
                        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Lihat Semua Produk
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Grid Produk -->
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <!-- Badge Stok -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-<?php echo e($product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger')); ?>">
                                            <?php echo e($product->stock > 0 ? $product->stock . ' tersedia' : 'Habis'); ?>

                                        </span>
                                    </div>
                                    
                                    <!-- Gambar Produk -->
                                    <?php if($product->image): ?>
                                        <img src="<?php echo e(asset($product->image)); ?>" 
                                             class="card-img-top product-image" 
                                             alt="<?php echo e($product->name); ?>"
                                             style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Badge Kategori -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge bg-primary"><?php echo e($product->category->name); ?></span>
                                    </div>

                                    <!-- Konten Card -->
                                    <div class="card-body d-flex flex-column p-3">
                                        <h5 class="card-title fw-semibold mb-2"><?php echo e(Str::limit($product->name, 40)); ?></h5>
                                        <p class="card-text text-muted small mb-3 flex-grow-1">
                                            <?php echo e(Str::limit($product->description, 70)); ?>

                                        </p>
                                        
                                        <!-- Harga -->
                                        <div class="mb-3">
                                            <span class="fw-bold text-primary fs-5">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                                        </div>

                                        <!-- Tombol Aksi -->
                                        <div class="d-grid gap-2">
                                            <a href="<?php echo e(route('products.show', $product->slug)); ?>" 
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Pagination -->
                    <?php if($products->hasPages()): ?>
                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <div>
                                <p class="text-muted small mb-0">
                                    Menampilkan <?php echo e($products->firstItem() ?? 0); ?> - <?php echo e($products->lastItem() ?? 0); ?> 
                                    dari <?php echo e($products->total()); ?> produk
                                </p>
                            </div>
                            <div>
                                <?php echo e($products->links()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/products/index.blade.php ENDPATH**/ ?>