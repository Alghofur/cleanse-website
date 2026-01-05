<?php
$title = 'Payroll - Owner';
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
                <i class="bi bi-bucket-fill"></i>Cleanse Owner
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar"><?php echo strtoupper(substr($user->username ?? 'O', 0, 1)); ?></div>
                            <span class="text-white ms-2"><?php echo htmlspecialchars($user->full_name ?? 'Owner'); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/owner/payroll"><i class="bi bi-calculator"></i>Payroll</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/owner/salary-reports"><i class="bi bi-file-earmark-pdf"></i>Salary Reports</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/owner/financial-report"><i class="bi bi-graph-up"></i>Financial Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo APP_URL; ?>/auth/logout"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-5">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="bi bi-calculator"></i> Payroll Management</h1>
            <p>Manage staff and admin salaries for your cleaning service business</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Process Salary</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row mb-3">
                                <label for="type" class="col-sm-3 col-form-label">Salary Type</label>
                                <div class="col-sm-9">
                                    <select name="type" class="form-select" required>
                                        <option value="staff">Staff Salary</option>
                                        <option value="admin">Admin Salary</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="staff_id" class="col-sm-3 col-form-label">Select Staff/Admin</label>
                                <div class="col-sm-9">
                                    <select name="staff_id" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        <?php if (!empty($staff)): ?>
                                            <?php foreach ($staff as $member): ?>
                                                <option value="<?php echo $member->id; ?>">
                                                    <?php echo htmlspecialchars($member->full_name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="base_salary" class="col-sm-3 col-form-label">Base Salary</label>
                                <div class="col-sm-9">
                                    <input type="number" name="base_salary" class="form-control" step="0.01" min="0" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="bonus" class="col-sm-3 col-form-label">Bonus</label>
                                <div class="col-sm-9">
                                    <input type="number" name="bonus" class="form-control" step="0.01" min="0" value="0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="deductions" class="col-sm-3 col-form-label">Deductions</label>
                                <div class="col-sm-9">
                                    <input type="number" name="deductions" class="form-control" step="0.01" min="0" value="0">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="month" class="col-sm-3 col-form-label">Month</label>
                                <div class="col-sm-9">
                                    <input type="month" name="month" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-calculator"></i> Process Salary
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Pending Salaries (This Month)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pendingSalaries)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pendingSalaries as $salary): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            <span class="ms-2"><?php echo htmlspecialchars($salary['name'] ?? 'N/A'); ?></span>
                                            <br>
                                            <small class="text-muted">$<?php echo number_format($salary['amount'] ?? 0, 2); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3">
                                <p class="text-muted mb-2">
                                    <i class="bi bi-info-circle"></i> 
                                    <small>Showing unpaid salaries for the current month</small>
                                </p>
                                <a href="<?php echo APP_URL; ?>/owner/salary-reports" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-file-earmark-pdf"></i> View All Salary Records
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle"></i>
                                <strong>All Salaries Paid!</strong><br>
                                <small>No pending salary payments for this month</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Salary Processing -->
        <div class="card mt-4">
            <div class="card-header">
                <h5><i class="bi bi-history"></i> Recent Salary Transactions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Recent Staff Salary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Staff</th>
                                        <th>Base</th>
                                        <th>Net</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $staffSql = "SELECT ss.*, u.full_name FROM staff_salary ss 
                                                 JOIN users u ON ss.staff_id = u.id 
                                                 ORDER BY ss.payment_date DESC LIMIT 5";
                                    $staffTransactions = Database::fetchAll($staffSql);
                                    if (!empty($staffTransactions)):
                                        foreach ($staffTransactions as $trans): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trans['full_name'] ?? 'N/A'); ?></td>
                                            <td>$<?php echo number_format($trans['base_salary'], 2); ?></td>
                                            <td>$<?php echo number_format($trans['net_salary'], 2); ?></td>
                                            <td><?php echo date('M d', strtotime($trans['payment_date'])); ?></td>
                                        </tr>
                                    <?php endforeach;
                                    else: ?>
                                        <tr><td colspan="4" class="text-muted">No transactions</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Recent Admin Salary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Admin</th>
                                        <th>Base</th>
                                        <th>Net</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $adminSql = "SELECT asal.*, u.full_name FROM admin_salary asal 
                                                 JOIN users u ON asal.admin_id = u.id 
                                                 ORDER BY asal.payment_date DESC LIMIT 5";
                                    $adminTransactions = Database::fetchAll($adminSql);
                                    if (!empty($adminTransactions)):
                                        foreach ($adminTransactions as $trans): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($trans['full_name'] ?? 'N/A'); ?></td>
                                            <td>$<?php echo number_format($trans['base_salary'], 2); ?></td>
                                            <td>$<?php echo number_format($trans['net_salary'], 2); ?></td>
                                            <td><?php echo date('M d', strtotime($trans['payment_date'])); ?></td>
                                        </tr>
                                    <?php endforeach;
                                    else: ?>
                                        <tr><td colspan="4" class="text-muted">No transactions</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
