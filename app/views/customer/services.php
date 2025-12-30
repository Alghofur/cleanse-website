<?php
$title = 'Services - Cleanse';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/style.css">
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
                    <li class="nav-item"><a class="nav-link active" href="<?php echo APP_URL; ?>/customer/services">Services</a></li>
                    <?php if (Auth::check()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/customer/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/auth/logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo APP_URL; ?>/auth/login">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Our Services</h1>
        <div class="row">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if ($service->image_url): ?>
                                <img src="<?php echo $service->image_url; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service->name); ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image text-white" style="font-size: 60px;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($service->name); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($service->description); ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0">$<?php echo number_format($service->price_per_hour, 2); ?>/hour</span>
                                    <span class="badge bg-info"><?php echo $service->duration_hours; ?> hrs</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <?php if (Auth::check()): ?>
                                    <a href="<?php echo APP_URL; ?>/customer/book" class="btn btn-primary btn-sm w-100">Book Now</a>
                                <?php else: ?>
                                    <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-primary btn-sm w-100">Login to Book</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No services available at the moment.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
