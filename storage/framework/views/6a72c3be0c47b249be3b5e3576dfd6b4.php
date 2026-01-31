

<?php $__env->startSection('title', 'Kelola Produk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header-admin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Kelola Produk</h2>
                <p class="text-muted mb-0">Daftar semua produk di toko</p>
            </div>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-admin-primary">
                <i class="bi bi-plus-circle me-2"></i> Tambah Produk
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
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($product->id); ?></td>
                            <td>
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(asset($product->image)); ?>" alt="<?php echo e($product->name); ?>" 
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px; border-radius: 5px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($product->name); ?></td>
                            <td>
                                <?php if($product->category): ?>
                                    <span class="badge bg-info"><?php echo e($product->category->name); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                            <td>Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                            <td><?php echo e($product->stock); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($product->is_active ? 'primary' : 'secondary'); ?>">
                                    <?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" 
                                       class="btn btn-sm btn-admin-outline px-3">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger px-3">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-box-seam display-6 text-muted"></i>
                                <h5 class="mt-3">Belum ada produk</h5>
                                <p class="text-muted">Mulai dengan menambahkan produk pertama Anda</p>
                                <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($products->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($products->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/admin/products/index.blade.php ENDPATH**/ ?>