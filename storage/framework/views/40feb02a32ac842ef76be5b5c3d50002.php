

<?php $__env->startSection('title', 'Detail Pesanan #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header-admin">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">Detail Pesanan</h2>
            <p class="text-muted mb-0">No. <?php echo e($order->order_number); ?></p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-admin-outline me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Order Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box p-3 rounded bg-light mb-3">
                    <label class="form-label text-muted small mb-2">Informasi Customer</label>
                    <p class="mb-1">
                        <i class="bi bi-person me-2"></i>
                        <strong><?php echo e($order->user->name); ?></strong>
                    </p>
                    <p class="mb-1">
                        <i class="bi bi-envelope me-2"></i>
                        <?php echo e($order->user->email); ?>

                    </p>
                    <p class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        <span class="badge bg-<?php echo e($order->user->role === 'admin' ? 'danger' : 'primary'); ?>">
                            <?php echo e($order->user->role === 'admin' ? 'Admin' : 'Customer'); ?>

                        </span>
                    </p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-box p-3 rounded bg-light mb-3">
                    <label class="form-label text-muted small mb-2">Status Pesanan</label>
                    <form action="<?php echo e(route('admin.orders.update-status', $order)); ?>" method="POST" class="d-flex gap-2">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="pending" <?php echo e($order->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Diproses</option>
                            <option value="completed" <?php echo e($order->status == 'completed' ? 'selected' : ''); ?>>Selesai</option>
                            <option value="cancelled" <?php echo e($order->status == 'cancelled' ? 'selected' : ''); ?>>Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn btn-admin-primary">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                    <div class="mt-2">
                        <span class="badge bg-<?php echo e($order->status_badge); ?> fs-6">
                            Status saat ini: <?php echo e($order->status_text); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-cart3 me-2"></i> Items Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($item->product->image): ?>
                                                    <img src="<?php echo e(asset($item->product->image)); ?>" 
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($item->product->name); ?></h6>
                                                    <small class="text-muted"><?php echo e($item->product->category->name); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo e($item->quantity); ?></span>
                                        </td>
                                        <td class="text-end fw-semibold">
                                            Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold text-primary fs-5">
                                            Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle me-2"></i> Informasi Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">No. Pesanan</label>
                            <p class="fw-bold mb-0"><?php echo e($order->order_number); ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Tanggal Pesanan</label>
                            <p class="mb-0"><?php echo e($order->created_at->format('d F Y, H:i')); ?></p>
                            <small class="text-muted"><?php echo e($order->created_at->diffForHumans()); ?></small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Terakhir Diupdate</label>
                            <p class="mb-0"><?php echo e($order->updated_at->format('d F Y, H:i')); ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small mb-1">Jumlah Item</label>
                            <p class="mb-0"><?php echo e($order->items->count()); ?> produk</p>
                        </div>
                        
                        <hr>
                        
                        <div class="d-grid gap-2">
                            <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                               target="_blank" 
                               class="btn btn-success">
                                <i class="bi bi-whatsapp me-2"></i> Hubungi Customer
                            </a>
                            
                            <form action="<?php echo e(route('admin.orders.destroy', $order)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                                    <i class="bi bi-trash me-2"></i> Hapus Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>