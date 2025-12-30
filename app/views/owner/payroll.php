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
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/dashboard"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/orders"><i class="bi bi-bag-check"></i>Orders</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/staff"><i class="bi bi-people"></i>Staff</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/payments"><i class="bi bi-credit-card"></i>Payments</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/admin/financial-report"><i class="bi bi-graph-up"></i>Financial Report</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/owner/payroll"><i class="bi bi-calculator"></i>Payroll</a></li>
                            <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/owner/salary-reports"><i class="bi bi-file-earmark-pdf"></i>Salary Reports</a></li>
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
                        <h5>Pending Salaries</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pendingSalaries)): ?>
                            <ul class="list-unstyled">
                                <?php foreach ($pendingSalaries as $salary): ?>
                                    <li class="mb-2">
                                        <span class="badge bg-warning">Pending</span>
                                        <?php echo htmlspecialchars($salary['name'] ?? 'N/A'); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No pending salaries</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
