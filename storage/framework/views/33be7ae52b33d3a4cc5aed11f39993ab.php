

<?php $__env->startSection('title', 'Detail Pesanan #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pesanan
                </a>
            </div>

            <!-- Order Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3 rounded-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="bi bi-receipt me-2"></i> Detail Pesanan
                            </h4>
                            <p class="mb-0 small opacity-75">No. <?php echo e($order->order_number); ?></p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo e($order->status_badge); ?> fs-5 px-3 py-2">
                                <?php echo e($order->status_text); ?>

                            </span>
                            <div class="mt-2">
                                <small class="opacity-75">
                                    <i class="bi bi-calendar me-1"></i>
                                    <?php echo e($order->created_at->format('d F Y, H:i')); ?>

                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="info-box p-3 rounded bg-light">
                                <label class="form-label text-muted small mb-2">No. Pesanan</label>
                                <p class="fw-bold mb-0 fs-5"><?php echo e($order->order_number); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-box p-3 rounded bg-light">
                                <label class="form-label text-muted small mb-2">Tanggal Pesanan</label>
                                <p class="mb-0">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <?php echo e($order->created_at->format('d F Y')); ?>

                                    <span class="ms-2 text-muted">(<?php echo e($order->created_at->format('H:i')); ?>)</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-cart3 me-2"></i> Produk yang Dipesan
                            </h5>
                            <span class="badge bg-secondary"><?php echo e($order->items->count()); ?> item</span>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Produk</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-muted"><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($item->product->image): ?>
                                                    <img src="<?php echo e(asset($item->product->image)); ?>" 
                                                         class="rounded me-3" 
                                                         alt="<?php echo e($item->product->name); ?>"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-1"><?php echo e($item->product->name); ?></h6>
                                                    <small class="text-muted"><?php echo e($item->product->category->name); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-semibold">
                                                Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?php echo e($item->quantity); ?></span>
                                        </td>
                                        <td class="text-end fw-bold">
                                            Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total Pesanan</td>
                                        <td class="text-end">
                                            <span class="fw-bold text-primary fs-4">
                                                Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?>

                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="border-top pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Status terakhir diperbarui: <?php echo e($order->updated_at->format('d/m/Y H:i')); ?>

                                </small>
                            </div>
                            
                            <div class="btn-group">
                                <?php if($order->status === 'pending'): ?>
                                <a href="https://api.whatsapp.com/send/?phone=621235678000&text&type=phone_number&app_absent=0" 
                                   target="_blank" 
                                   class="btn btn-success">
                                    <i class="bi bi-whatsapp me-2"></i> Konfirmasi Pembayaran
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-list-ul me-2"></i> Semua Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i> Status Pesanan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <?php
                            $steps = [
                                ['status' => 'pending', 'icon' => 'bi-cart', 'label' => 'Pesanan Dibuat', 'time' => $order->created_at],
                                ['status' => 'processing', 'icon' => 'bi-gear', 'label' => 'Diproses', 'time' => $order->status === 'processing' || $order->status === 'completed' ? $order->updated_at : null],
                                ['status' => 'completed', 'icon' => 'bi-check-circle', 'label' => 'Selesai', 'time' => $order->status === 'completed' ? $order->updated_at : null],
                            ];
                        ?>
                        
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="timeline-step <?php echo e(in_array($order->status, array_slice(['pending', 'processing', 'completed'], 0, array_search($step['status'], ['pending', 'processing', 'completed']) + 1)) ? 'active' : ''); ?>">
                            <div class="timeline-icon">
                                <i class="<?php echo e($step['icon']); ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1 fw-semibold"><?php echo e($step['label']); ?></h6>
                                <?php if($step['time']): ?>
                                    <small class="text-muted"><?php echo e($step['time']->format('d F Y, H:i')); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Menunggu...</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding: 20px 0;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 0;
        right: 0;
        height: 3px;
        background: #dee2e6;
        z-index: 1;
    }
    
    .timeline-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 2;
    }
    
    .timeline-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #dee2e6;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 15px;
        border: 3px solid white;
    }
    
    .timeline-step.active .timeline-icon {
        background: #0d6efd;
        color: white;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }
    
    .timeline-content h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .info-box {
        border-left: 4px solid #0d6efd;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }
    
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header.rounded-top {
        border-radius: 10px 10px 0 0 !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/orders/show.blade.php ENDPATH**/ ?>