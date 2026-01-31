

<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-admin">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0"><?php echo e(App\Models\Product::count()); ?></h3>
                        <p class="stat-label mb-0">Total Produk</p>
                    </div>
                </div>
                <div class="stat-info mt-2">
                    <span class="badge bg-success"><?php echo e(App\Models\Product::where('is_active', true)->count()); ?> aktif</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-admin">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #8a2be2;">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0"><?php echo e(App\Models\Category::count()); ?></h3>
                        <p class="stat-label mb-0">Total Kategori</p>
                    </div>
                </div>
                <div class="stat-info mt-2">
                    <span class="badge bg-info"><?php echo e(App\Models\Category::has('products')->count()); ?> terpakai</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-admin">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #0dcaf0;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0"><?php echo e(App\Models\User::count()); ?></h3>
                        <p class="stat-label mb-0">Total User</p>
                    </div>
                </div>
                <div class="stat-info mt-2">
                    <span class="badge bg-primary"><?php echo e(App\Models\User::where('role', 'user')->count()); ?> customer</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card-admin">
                <div class="d-flex align-items-center">
                    <div class="stat-icon me-3" style="background: #20c997;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <h3 class="stat-number mb-0"><?php echo e(App\Models\Order::count()); ?></h3>
                        <p class="stat-label mb-0">Total Pesanan</p>
                    </div>
                </div>
                <div class="stat-info mt-2">
                    <span class="badge bg-warning"><?php echo e(App\Models\Order::where('status', 'pending')->count()); ?> pending</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - TAMBAH CLASS mb-5 -->
    <div class="row g-3 mb-5">
        <!-- Recent Products -->
        <div class="col-lg-8">
            <div class="admin-card rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center py-3 rounded-top-3">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="bi bi-clock me-2"></i> Produk Terbaru
                    </h5>
                    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-sm btn-admin-outline rounded-pill">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover admin-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = App\Models\Product::latest()->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?php echo e(Str::limit($product->name, 25)); ?></td>
                                    <td>
                                        <?php if($product->category): ?>
                                            <span class="badge bg-info">
                                                <?php echo e($product->category->name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-primary fw-semibold">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger')); ?>">
                                            <?php echo e($product->stock); ?>

                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge bg-<?php echo e($product->is_active ? 'primary' : 'secondary'); ?>">
                                            <?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-box-seam display-6 text-muted"></i>
                                        <p class="mt-3">Belum ada produk</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="admin-card rounded-3 mt-4">
                <div class="card-header d-flex justify-content-between align-items-center py-3 rounded-top-3">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="bi bi-receipt me-2"></i> Pesanan Terbaru
                    </h5>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm btn-admin-outline rounded-pill">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover admin-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Invoice</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = App\Models\Order::latest()->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 fw-semibold">#<?php echo e($order->invoice); ?></td>
                                    <td>
                                        <?php if($order->user): ?>
                                            <?php echo e(Str::limit($order->user->name, 20)); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-primary fw-semibold">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></td>
                                    <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                                    <td class="pe-4">
                                        <?php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$order->status] ?? 'secondary'); ?>">
                                            <?php echo e(ucfirst($order->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="bi bi-receipt display-6 text-muted"></i>
                                        <p class="mt-3">Belum ada pesanan</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Orders -->
        <div class="col-lg-4">
            <div class="admin-card h-100 rounded-3">
                <div class="card-header py-3 rounded-top-3">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="bi bi-lightning me-2"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.products.create')); ?>" 
                           class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-plus-circle me-3"></i>
                                <div>
                                    <div class="fw-semibold">Tambah Produk</div>
                                </div>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <a href="<?php echo e(route('admin.categories.create')); ?>" 
                           class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-tag me-3"></i>
                                <div>
                                    <div class="fw-semibold">Tambah Kategori</div>
                                </div>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <a href="<?php echo e(route('admin.products.index')); ?>" 
                           class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-list-ul me-3"></i>
                                <div>
                                    <div class="fw-semibold">Kelola Produk</div>
                                </div>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <a href="<?php echo e(route('admin.orders.index')); ?>" 
                           class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-receipt me-3"></i>
                                <div>
                                    <div class="fw-semibold">Kelola Pesanan</div>
                                </div>
                            </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <a href="<?php echo e(route('admin.users.index')); ?>" 
                            class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                                <span class="d-flex align-items-center">
                                    <i class="bi bi-people me-3"></i>
                                    <div>
                                        <div class="fw-semibold">Kelola Pengguna</div>
                                    </div>
                                </span>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <a href="<?php echo e(route('store')); ?>" target="_blank" 
                           class="btn btn-admin-primary d-flex align-items-center justify-content-between py-2 px-3 rounded-2">
                            <span class="d-flex align-items-center">
                                <i class="bi bi-eye me-3"></i>
                                <div>
                                    <div class="fw-semibold">Lihat Toko</div>
                                </div>
                            </span>
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Spacer untuk footer -->
    <div class="footer-spacer" style="height: 2rem;"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Tambah spacing untuk dashboard */
    .dashboard-content {
        padding-bottom: 3rem;
    }

    /* Stat Card */
    .stat-card-admin {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border-left: 4px solid #0d6efd;
        transition: all 0.3s;
        height: 100%;
        margin-bottom: 1rem;
    }

    .stat-card-admin:nth-child(2) {
        border-left-color: #8a2be2;
    }

    .stat-card-admin:nth-child(3) {
        border-left-color: #0dcaf0;
    }

    .stat-card-admin:nth-child(4) {
        border-left-color: #20c997;
    }

    .stat-card-admin:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-card-admin .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
        background: #0d6efd;
    }

    .stat-card-admin .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1.2;
    }

    .stat-card-admin .stat-label {
        color: #6c757d;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .stat-card-admin .stat-info {
        margin-top: 0.5rem;
    }

    .stat-card-admin .stat-info .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        font-weight: 500;
    }

    /* Cards dengan spacing */
    .admin-card {
        background: white;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: none;
        overflow: hidden;
        height: 100%;
        margin-bottom: 1.5rem;
    }

    .admin-card .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }

    .admin-card .card-body {
        padding: 1.25rem;
    }

    .rounded-top-3 {
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
    }

    /* Button styles - SEMUA BTN BIRU */
    .btn-admin-primary, .btn-purple, .btn-admin-outline {
        border-radius: 8px;
        transition: all 0.3s;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
    }

    .btn-admin-primary:last-child,
    .btn-purple:last-child,
    .btn-admin-outline:last-child {
        margin-bottom: 0;
    }

    .btn-admin-primary {
        background: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-admin-primary:hover {
        background: #0b5ed7;
        border-color: #0a58ca;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    
    .btn-admin-outline {
        background: transparent;
        border: 1px solid #0d6efd;
        color: #0d6efd;
    }
    
    .btn-admin-outline:hover {
        background: #0d6efd;
        color: white;
        transform: translateY(-2px);
    }

    /* Table */
    .admin-table th {
        background: #0d6efd;
        color: white;
        font-weight: 500;
        border: none;
        padding: 0.75rem 1rem;
        white-space: nowrap;
    }

    .admin-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }

    .admin-table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }

    /* Badge rounding */
    .badge {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    /* Extra spacing untuk row terakhir */
    .mb-5 {
        margin-bottom: 3rem !important;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>