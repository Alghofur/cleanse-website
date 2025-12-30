<?php
$title = 'Financial Report - Admin';
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
        <div class="page-header">
            <h1><i class="bi bi-graph-up"></i> Financial Report</h1>
            <p>Monitor your business income and expenses</p>
        </div>

        <!-- Filter Form -->
        <div class="filter-form">
            <form method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label for="month" class="form-label">Month</label>
                    <input type="month" name="month" id="month" class="form-control" value="<?php echo htmlspecialchars($month); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" name="year" id="year" class="form-control" value="<?php echo htmlspecialchars($year); ?>" min="2000" max="2099" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-box income">
                    <h3><i class="bi bi-cash-coin"></i> Total Income</h3>
                    <h2>$<?php 
                        $totalIncome = 0;
                        if (!empty($income)):
                            foreach ($income as $item):
                                $totalIncome += $item['total_income'] ?? 0;
                            endforeach;
                        endif;
                        echo number_format($totalIncome, 2);
                    ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box expense">
                    <h3><i class="bi bi-credit-card"></i> Total Expenses</h3>
                    <h2>$<?php echo number_format($expenses['total_expense'] ?? 0, 2); ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box profit">
                    <h3><i class="bi bi-graph-up-arrow"></i> Net Profit</h3>
                    <h2>$<?php echo number_format(($totalIncome - ($expenses['total_expense'] ?? 0)), 2); ?></h2>
                </div>
            </div>
        </div>

        <!-- Income Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-arrow-up-circle"></i> Income by Payment Method</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($income)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th>Total Income</th>
                                    <th>Transactions</th>
                                    <th>Avg Per Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($income as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $item['payment_method'])); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">$<?php echo number_format($item['total_income'], 2); ?></span>
                                        </td>
                                        <td><?php echo $item['transaction_count']; ?></td>
                                        <td>$<?php echo number_format(($item['total_income'] / ($item['transaction_count'] ?? 1)), 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No income data for this period.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Expense Summary -->
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-arrow-down-circle"></i> Expense Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Staff Salaries</h6>
                        <h3>$<?php echo number_format($expenses['total_expense'] ?? 0, 2); ?></h3>
                        <small class="text-muted">
                            <?php echo ($expenses['salary_count'] ?? 0); ?> salary records
                        </small>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Expense Details</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success"></i>
                                Salaries Processed: <?php echo ($expenses['salary_count'] ?? 0); ?>
                            </li>
                            <li>
                                <i class="bi bi-check-circle text-success"></i>
                                Period: <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
