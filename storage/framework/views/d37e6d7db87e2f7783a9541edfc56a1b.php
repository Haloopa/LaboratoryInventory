<?php $__env->startSection('title', 'Stationery Shop - Toko Alat Tulis Terlengkap'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Selamat Datang di StationeryShop</h1>
            <p class="lead">Temukan alat tulis berkualitas untuk kebutuhan belajar dan kerja Anda</p>
            <div class="mt-4">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-light btn-lg">
                    Belanja Sekarang <i class="bi bi-arrow-right"></i>
                </a>
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-light btn-lg ms-2">
                        <i class="bi bi-person-plus"></i> Daftar Gratis
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="container mb-5">
        <h2 class="text-center mb-4">Kategori Produk</h2>
        <div class="row justify-content-center g-3">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-auto">
                    <a href="<?php echo e(route('products.index', ['category' => $category->slug])); ?>" 
                       class="category-badge">
                        <?php echo e($category->name); ?> (<?php echo e($category->products_count); ?>)
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Why Register Section -->
    <?php if(auth()->guard()->guest()): ?>
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
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg mt-4">
                            <i class="bi bi-person-plus"></i> Daftar Sekarang Gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Featured Products -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Produk Unggulan</h2>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100">
                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset($product->image)); ?>" class="card-img-top product-image" alt="<?php echo e($product->name); ?>">
                        <?php else: ?>
                            <div class="card-img-top product-image bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <?php if($product->category): ?>
                                <span class="badge bg-primary mb-2"><?php echo e($product->category->name); ?></span>
                            <?php endif; ?>
                            <h5 class="card-title"><?php echo e($product->name); ?></h5>
                            <p class="card-text text-muted flex-grow-1" style="font-size: 0.9rem;">
                                <?php echo e(Str::limit($product->description, 50)); ?>

                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold text-primary">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                                    <span class="badge bg-<?php echo e($product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger')); ?>">
                                        Stok: <?php echo e($product->stock); ?>

                                    </span>
                                </div>
                                <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam" style="font-size: 4rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Belum ada produk</h4>
                    <p>Produk akan segera tersedia</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/store.blade.php ENDPATH**/ ?>