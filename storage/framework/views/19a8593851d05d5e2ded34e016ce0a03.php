

<?php $__env->startSection('title', 'Kelola Kategori'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header-admin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">Kelola Kategori</h2>
                <p class="text-muted mb-0">Daftar semua kategori produk</p>
            </div>
            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-admin-primary">
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
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($category->id); ?></td>
                            <td>
                                <strong><?php echo e($category->name); ?></strong>
                                <?php if($category->description): ?>
                                    <p class="text-muted mb-0 small"><?php echo e(Str::limit($category->description, 50)); ?></p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code><?php echo e($category->slug); ?></code>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?php echo e($category->products_count); ?></span> produk
                            </td>
                            <td><?php echo e($category->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" 
                                       class="btn btn-sm btn-admin-outline px-3">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-tags display-6 text-muted"></i>
                                <h5 class="mt-3">Belum ada kategori</h5>
                                <p class="text-muted">Mulai dengan menambahkan kategori pertama Anda</p>
                                <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-admin-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Kategori Pertama
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($categories->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($categories->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>