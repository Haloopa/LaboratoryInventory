<?php if(!Auth::user()->isAdmin()): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i> 
        Anda tidak memiliki akses ke halaman admin.
        <a href="<?php echo e(route('store')); ?>" class="alert-link">Kembali ke toko</a>
    </div>
    <?php exit(); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        main {
            flex: 1 0 auto;
            padding-bottom: 2rem; /* Tambah padding bawah */
        }

        .footer {
            background: #2c3e50;
            color: white;
        }

        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8f9fa;
        }
        main {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
            background: #2c3e50;
            color: white;
            margin-top: auto;
        }

        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        
        /* Admin Header */
        .admin-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .admin-header h1 {
            color: white;
            font-weight: bold;
        }
        
        /* Admin Content */
        .admin-content {
            padding: 0 1rem;
        }
        
        /* Card styling for admin */
        .admin-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .admin-card .card-header {
            background: white;
            border-bottom: 2px solid #0d6efd;
            font-weight: 600;
            color: #2c3e50;
            padding: 1rem 1.25rem;
        }
        
        .admin-card .card-body {
            padding: 1.25rem;
        }
        
        /* Table styling */
        .admin-table th {
            background: #0d6efd;
            color: white;
            font-weight: 500;
            border: none;
        }
        
        .admin-table td {
            vertical-align: middle;
        }
        
        /* Button styling */
        .btn-admin-primary {
            background: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .btn-admin-primary:hover {
            background: #0b5ed7;
            border-color: #0a58ca;
            color: white;
        }
        
        .btn-admin-outline {
            border: 2px solid #0d6efd;
            color: #0d6efd;
            background: transparent;
        }
        
        .btn-admin-outline:hover {
            background: #0d6efd;
            color: white;
        }
        
        /* Stats cards */
        .stat-card-admin {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-top: 4px solid #0d6efd;
            transition: transform 0.3s;
        }
        
        .stat-card-admin:hover {
            transform: translateY(-5px);
        }
        
        .stat-card-admin .stat-icon {
            width: 50px;
            height: 50px;
            background: #0d6efd;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card-admin .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .stat-card-admin .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        /* Alert styling */
        .alert-admin-success {
            background: #d1e7dd;
            border: 1px solid #badbcc;
            color: #0f5132;
            border-left: 4px solid #0d6efd;
        }
        
        /* Page header */
        .page-header-admin {
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .page-header-admin h2 {
            color: #2c3e50;
            font-weight: 600;
        }
        
        /* Form styling */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('store')); ?>">
                <i class="bi bi-pencil-square"></i> StationeryShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('store')); ?>">Toko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo e(route('admin.dashboard')); ?>">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('admin.products.index')); ?>">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('admin.categories.index')); ?>">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('admin.users.index')); ?>">Pengguna/Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('admin.orders.index')); ?>">Pesanan</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo e(Auth::user()->name); ?>

                            <span class="badge bg-danger ms-1">Admin</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.products.index')); ?>">
                                <i class="bi bi-box-seam"></i> Produk
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.categories.index')); ?>">
                                <i class="bi bi-tags"></i> Kategori
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.users.index')); ?>">
                                <i class="bi bi-people"></i> Users/Admin
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.orders.index')); ?>">
                                <i class="bi bi-bag-check"></i> Kelola Pesanan
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('store')); ?>" target="_blank">
                                <i class="bi bi-shop"></i> Lihat Toko
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?php echo e(route('logout')); ?>"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><?php echo $__env->yieldContent('title', 'Dashboard Admin'); ?></h1>
                    <p class="mb-0">Kelola toko stationery dengan mudah</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="date-badge">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo e(now()->translatedFormat('l, d F Y')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="admin-content">
        <div class="container">
            <?php if(session('success')): ?>
                <div class="alert alert-admin-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Footer - SEDERHANA -->
    <footer class="footer py-3">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">Admin Side - My Stationery Store - Hann</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/layouts/admin.blade.php ENDPATH**/ ?>