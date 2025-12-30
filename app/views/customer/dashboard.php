<?php
$title = 'Dashboard - Cleanse';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo APP_URL; ?>">Cleanse</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($user->full_name ?? 'User'); ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/customer/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/auth/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="<?php echo APP_URL; ?>/customer/dashboard" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="<?php echo APP_URL; ?>/customer/services" class="list-group-item list-group-item-action">Services</a>
                    <a href="<?php echo APP_URL; ?>/customer/book" class="list-group-item list-group-item-action">Book Service</a>
                    <a href="<?php echo APP_URL; ?>/customer/orders" class="list-group-item list-group-item-action">My Orders</a>
                    <a href="<?php echo APP_URL; ?>/customer/profile" class="list-group-item list-group-item-action">Profile</a>
                </div>
            </div>

            <div class="col-md-9">
                <h1 class="mb-4">Customer Dashboard</h1>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="text-primary"><?php echo $totalOrders ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Pending</h5>
                                <h2 class="text-warning"><?php echo $pendingOrders ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Completed</h5>
                                <h2 class="text-success"><?php echo $completedOrders ?? 0; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="mb-3">Recent Orders</h3>
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order->service_name ?? 'N/A'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order->schedule_date)); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>$<?php echo number_format($order->total_price, 2); ?></td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/customer/orders" class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo APP_URL; ?>/customer/orders" class="btn btn-primary">View All Orders</a>
                <?php else: ?>
                    <div class="alert alert-info">No orders yet. <a href="<?php echo APP_URL; ?>/customer/book">Book a service now!</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
