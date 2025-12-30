<?php
$title = 'Payment - Cleanse';
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
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mb-4">Order Payment</h1>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Order Details</h5>
                        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order->order_number ?? 'N/A'); ?></p>
                        <p><strong>Amount:</strong> <span class="h4 text-primary">$<?php echo number_format($order->total_price, 2); ?></span></p>
                    </div>
                </div>

                <form method="POST" class="card p-4 shadow-sm">
                    <h5 class="mb-4">Payment Method</h5>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                            <label class="form-check-label" for="credit_card">
                                <i class="bi bi-credit-card"></i> Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="debit_card" value="debit_card">
                            <label class="form-check-label" for="debit_card">
                                <i class="bi bi-credit-card"></i> Debit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                            <label class="form-check-label" for="bank_transfer">
                                <i class="bi bi-bank"></i> Bank Transfer
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="digital_wallet" value="digital_wallet">
                            <label class="form-check-label" for="digital_wallet">
                                <i class="bi bi-wallet2"></i> Digital Wallet
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID (Optional)</label>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Enter transaction ID if available">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Confirm Payment
                        </button>
                        <a href="<?php echo APP_URL; ?>/customer/dashboard" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
