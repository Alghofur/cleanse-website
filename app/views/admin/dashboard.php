<?php
$title = 'Admin Dashboard - Cleanse';
$user = Auth::user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo APP_URL; ?>">
                <i class="bi bi-bucket-fill"></i>Cleanse Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar"><?php echo strtoupper(substr($user->username ?? 'A', 0, 1)); ?></div>
                            <span class="text-white ms-2"><?php echo htmlspecialchars($user->full_name ?? 'Admin'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/dashboard"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/orders"><i class="bi bi-bag-check"></i>Orders</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/staff"><i class="bi bi-people"></i>Staff</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/payments"><i class="bi bi-credit-card"></i>Payments</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/financial-report"><i class="bi bi-graph-up"></i>Financial Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-5">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <h1><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>
            <p>Welcome back! Here's your business overview.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card primary">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-bag-check"></i> Total Orders</h6>
                        <h2><?php echo $stats['total_orders'] ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card success">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-check-circle"></i> Completed</h6>
                        <h2><?php echo $stats['completed_orders'] ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card warning">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-clock"></i> Pending</h6>
                        <h2><?php echo $stats['pending_orders'] ?? 0; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card info">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-currency-dollar"></i> Revenue</h6>
                        <h2>$<?php echo number_format($stats['total_revenue'] ?? 0, 0); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);">
            <div class="card-header" style="background: white; border-bottom: 2px solid #e9ecef;">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentOrders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order->order_number ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($order->customer_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($order->service_name ?? 'N/A'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order->schedule_date)); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $order->status === 'completed' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>$<?php echo number_format($order->total_price, 2); ?></td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/admin/orders" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No orders yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
