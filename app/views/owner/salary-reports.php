<?php
$title = 'Salary Reports - Owner';
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
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="bi bi-file-earmark-pdf"></i> Salary Reports</h1>
            <p>View staff and admin salary reports</p>
        </div>

        <!-- Filter Form -->
        <div class="filter-form mb-4">
            <form method="GET" class="row align-items-end">
                <div class="col-md-5">
                    <label for="month" class="form-label">Month</label>
                    <select name="month" id="month" class="form-select" required>
                        <option value="">Select Month</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" 
                                <?php echo $month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" id="year" class="form-select" required>
                        <?php for ($y = 2000; $y <= 2099; $y++): ?>
                            <option value="<?php echo $y; ?>" 
                                <?php echo $year == $y ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Staff Salary Report -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-people"></i> Staff Salary Report</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($staffReport)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Base Salary</th>
                                    <th>Bonus</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($staffReport as $report): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($report['full_name'] ?? 'N/A'); ?></strong></td>
                                        <td>$<?php echo number_format($report['base_salary'] ?? 0, 2); ?></td>
                                        <td><span class="badge bg-success">+$<?php echo number_format($report['bonus'] ?? 0, 2); ?></span></td>
                                        <td><span class="badge bg-danger">-$<?php echo number_format($report['deductions'] ?? 0, 2); ?></span></td>
                                        <td><strong>$<?php echo number_format($report['net_salary'] ?? 0, 2); ?></strong></td>
                                        <td><?php echo $report['payment_date'] ? date('M d, Y', strtotime($report['payment_date'])) : '-'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($report['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($report['payment_status'] ?? 'pending'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (($report['payment_status'] ?? 'pending') === 'pending'): ?>
                                                <form method="POST" action="<?php echo APP_URL; ?>/owner/salary-reports/staff/<?php echo $report['id']; ?>/mark-paid" style="display:inline;">
                                                    <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">✓ Paid</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No staff salary records for this period.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin Salary Report -->
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-person-check"></i> Admin Salary Report</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($adminReport)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Admin Name</th>
                                    <th>Base Salary</th>
                                    <th>Bonus</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($adminReport as $report): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($report['full_name'] ?? 'N/A'); ?></strong></td>
                                        <td>$<?php echo number_format($report['base_salary'] ?? 0, 2); ?></td>
                                        <td><span class="badge bg-success">+$<?php echo number_format($report['bonus'] ?? 0, 2); ?></span></td>
                                        <td><span class="badge bg-danger">-$<?php echo number_format($report['deductions'] ?? 0, 2); ?></span></td>
                                        <td><strong>$<?php echo number_format($report['net_salary'] ?? 0, 2); ?></strong></td>
                                        <td><?php echo $report['payment_date'] ? date('M d, Y', strtotime($report['payment_date'])) : '-'; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($report['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($report['payment_status'] ?? 'pending'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (($report['payment_status'] ?? 'pending') === 'pending'): ?>
                                                <form method="POST" action="<?php echo APP_URL; ?>/owner/salary-reports/admin/<?php echo $report['id']; ?>/mark-paid" style="display:inline;">
                                                    <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">✓ Paid</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No admin salary records for this period.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
