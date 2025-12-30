<?php
$title = 'Orders - Cleanse';
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
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">My Orders</h1>

        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order #</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($order->order_number ?? 'N/A'); ?></strong></td>
                                <td><?php echo htmlspecialchars($order->service_name ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($order->schedule_date)); ?></td>
                                <td><?php echo date('h:i A', strtotime($order->schedule_time)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info'); ?>">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($order->total_price, 2); ?></td>
                                <td>
                                    <?php if ($order->status === 'completed'): ?>
                                        <a href="<?php echo APP_URL; ?>/customer/rate/<?php echo $order->id; ?>" class="btn btn-sm btn-warning">Rate</a>
                                    <?php elseif ($order->status === 'pending'): ?>
                                        <a href="<?php echo APP_URL; ?>/customer/payment/<?php echo $order->id; ?>" class="btn btn-sm btn-primary">Pay</a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                You haven't made any orders yet. <a href="<?php echo APP_URL; ?>/customer/book">Book a service now!</a>
            </div>
        <?php endif; ?>

        <a href="<?php echo APP_URL; ?>/customer/dashboard" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
