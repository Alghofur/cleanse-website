<?php
$title = 'Orders - Admin';
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
    <nav class="navbar sticky-top navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo APP_URL; ?>">
                <i class="bi bi-broom"></i>Cleanse Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <?php
                            $name = isset($_SESSION['username']) ? htmlspecialchars(substr($_SESSION['username'], 0, 1)) : 'A';
                            echo strtoupper($name);
                            ?>
                        </div>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/orders"><i class="bi bi-list-check"></i> Orders</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/staff"><i class="bi bi-people"></i> Staff</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/payments"><i class="bi bi-credit-card"></i> Payments</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/financial-report"><i class="bi bi-graph-up"></i> Financial Report</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="page-header">
            <h1><i class="bi bi-list-check"></i> Manage Orders</h1>
            <p>View and manage all customer orders</p>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($order->order_number ?? 'N/A'); ?></strong></td>
                                        <td><?php echo htmlspecialchars($order->customer_name ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($order->service_name ?? 'N/A'); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($order->schedule_date . ' ' . $order->schedule_time)); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($order->status); ?>
                                            </span>
                                        </td>
                                        <td>$<?php echo number_format($order->total_price, 2); ?></td>
                                        <td>
                                            <form method="POST" action="<?php echo APP_URL; ?>/admin/orders/<?php echo $order->id; ?>/status" style="display:inline;">
                                                <select name="status" class="form-select form-select-sm" style="width:auto; display:inline-block;">
                                                    <option value="pending" <?php echo $order->status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="confirmed" <?php echo $order->status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="in_progress" <?php echo $order->status === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="completed" <?php echo $order->status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo $order->status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
