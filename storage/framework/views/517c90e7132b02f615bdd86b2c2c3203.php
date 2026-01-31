<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Stationery Shop'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
            min-height: 70vh;
            padding-bottom: 2rem;
        }
        .footer-simple {
            flex-shrink: 0;
            background: #2c3e50;
            color: white;
            padding: 15px 0;
            margin-top: auto;
        }

        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .hero-section {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .card {
            transition: transform 0.3s;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.125);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .category-badge {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            color: #495057;
            transition: all 0.3s;
        }
        category-badge:hover {
            background: #007bff;
            color: white;
        }
        
        /* FIX: Navbar dropdown z-index */
        .navbar {
            z-index: 1030 !important; /* Pastikan navbar di atas konten */
        }
        
        .dropdown-menu {
            min-width: 240px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0,0,0,0.1);
            z-index: 1050 !important; /* Pastikan dropdown di atas sidebar cart */
        }
        
        /* Navbar dropdown styling */
        .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .dropdown-item .badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            min-width: 20px;
            text-align: center;
        }
        
        .dropdown-item-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* FIX: Untuk halaman cart */
        .sticky-top {
            z-index: 1020 !important; /* Sidebar cart di bawah dropdown */
        }
        
        /* Alert styling */
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        /* Button styling */
        .btn {
            border-radius: 6px;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        .btn-outline-primary {
            color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        /* Form styling */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* Badge styling */
        .badge {
            border-radius: 4px;
            font-weight: 500;
        }
        
        /* Cart count in navbar */
        .cart-count-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.6rem;
            padding: 0.15rem 0.35rem;
            min-width: 18px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-nav .dropdown-menu {
                position: static;
                float: none;
                width: auto;
                margin-top: 0;
                background-color: transparent;
                border: 0;
                box-shadow: none;
                z-index: auto !important;
            }
            
            .navbar-nav .dropdown-item {
                padding-left: 2rem;
            }
            
            /* Di mobile, tidak perlu z-index khusus */
            .navbar {
                z-index: auto !important;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar dengan z-index tinggi -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" style="z-index: 1030;">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <i class="bi bi-pencil-square"></i> StationeryShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('store')); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('products.index')); ?>">Produk</a>
                    </li>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->role === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Admin</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <form action="<?php echo e(route('products.index')); ?>" method="GET" class="d-flex me-3">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari produk..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </form>
                <ul class="navbar-nav">
                    <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo e(Auth::user()->name); ?>

                            <?php if(Auth::user()->isAdmin()): ?>
                                <span class="badge bg-danger ms-1">Admin</span>
                            <?php else: ?>
                                <span class="badge bg-primary ms-1">User</span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1050;">
                            <?php if(Auth::user()->isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-speedometer2"></i> 
                                        <span>Dashboard Admin</span>
                                    </div>
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.products.index')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-box-seam"></i> 
                                        <span>Kelola Produk</span>
                                    </div>
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.categories.index')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-tags"></i> 
                                        <span>Kelola Kategori</span>
                                    </div>
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('admin.users.index')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-people"></i> 
                                        <span>Kelola Pengguna</span>
                                    </div>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            
                            <li><a class="dropdown-item" href="<?php echo e(route('store')); ?>">
                                <div class="dropdown-item-content">
                                    <i class="bi bi-shop"></i> 
                                    <span>Lihat Toko</span>
                                </div>
                            </a></li>
                            
                            <?php if(!Auth::user()->isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-person"></i> 
                                        <span>Profil Saya</span>
                                    </div>
                                </a></li>
                                
                                <li>
                                    <a class="dropdown-item position-relative" href="<?php echo e(route('cart.index')); ?>">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="dropdown-item-content">
                                                <i class="bi bi-cart"></i> 
                                                <span>Keranjang Belanja</span>
                                            </div>
                                            <?php
                                                // Gunakan namespace full tanpa 'use'
                                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                                            ?>
                                            <?php if($cartCount > 0): ?>
                                                <span class="badge bg-danger ms-2"><?php echo e($cartCount); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </li>
                                
                                <li><a class="dropdown-item" href="<?php echo e(route('orders.index')); ?>">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-bag-check"></i> 
                                        <span>Pesanan Saya</span>
                                    </div>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            
                            <li>
                                <a class="dropdown-item text-danger" href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <div class="dropdown-item-content">
                                        <i class="bi bi-box-arrow-right"></i> 
                                        <span>Logout</span>
                                    </div>
                                </a>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('register')); ?>">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer Simple - Hanya Copyright -->
    <footer class="footer-simple">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">User Side - My Stationery Store - Hann</p>
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
</html><?php /**PATH D:\KULIAH\SERTIFIKASI\stationery_shop\resources\views/layouts/app.blade.php ENDPATH**/ ?>