

<?php $__env->startSection('title', 'Keranjang Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-cart3 me-2"></i> Keranjang Belanja
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($carts->isEmpty()): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x display-5 text-muted mb-3"></i>
                            <h5 class="mb-3">Keranjang belanja kosong</h5>
                            <p class="text-muted mb-4">Tambahkan produk ke keranjang untuk mulai berbelanja</p>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">
                                <i class="bi bi-bag me-1"></i> Lihat Produk
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $carts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($cart->product->image): ?>
                                                    <img src="<?php echo e(asset($cart->product->image)); ?>" 
                                                         class="me-3 rounded" 
                                                         alt="<?php echo e($cart->product->name); ?>"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-1"><?php echo e($cart->product->name); ?></h6>
                                                    <small class="text-muted"><?php echo e($cart->product->category->name); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            Rp <?php echo e(number_format($cart->product->price, 0, ',', '.')); ?>

                                        </td>
                                        <td class="text-center" style="width: 120px;">
                                            <form action="<?php echo e(route('cart.update', $cart)); ?>" method="POST" class="d-flex align-items-center">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="<?php echo e($cart->quantity); ?>" 
                                                           min="1" 
                                                           max="<?php echo e($cart->product->stock); ?>"
                                                           class="form-control form-control-sm text-center"
                                                           onchange="this.form.submit()">
                                                </div>
                                            </form>
                                            <small class="text-muted">Stok: <?php echo e($cart->product->stock); ?></small>
                                        </td>
                                        <td class="text-center fw-semibold text-primary">
                                            Rp <?php echo e(number_format($cart->product->price * $cart->quantity, 0, ',', '.')); ?>

                                        </td>
                                        <td class="text-center">
                                            <form action="<?php echo e(route('cart.destroy', $cart)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Clear Cart Button -->
                        <div class="text-end mt-3">
                            <form action="<?php echo e(route('cart.clear')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                                    <i class="bi bi-trash me-1"></i> Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="z-index: 1020;"> 
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-receipt me-2"></i> Ringkasan Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pengiriman</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-semibold">Total</span>
                            <span class="fw-bold text-primary fs-5">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                        </div>
                    </div>

                    <?php if(!$carts->isEmpty()): ?>
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-1"></i> Lanjut Belanja
                            </a>
                            
                            <form action="<?php echo e(route('checkout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-whatsapp me-1"></i> Checkout via WhatsApp
                                </button>
                            </form>
                            
                            <div class="alert alert-info small mb-0 mt-2">
                                <i class="bi bi-info-circle me-2"></i>
                                Anda akan diarahkan ke WhatsApp untuk konfirmasi pesanan.
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> Stok produk akan ditahan selama 24 jam.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/cart/index.blade.php ENDPATH**/ ?>