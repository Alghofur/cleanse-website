<?php
$title = 'Cleanse - Professional Cleaning Services';
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
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo APP_URL; ?>">
                <i class="bi bi-bucket-fill"></i> Cleanse
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <?php if (Auth::check()): ?>
                            <a class="nav-link" href="<?php echo APP_URL; ?>/customer/services">Services</a>
                        <?php else: ?>
                            <a class="nav-link" href="<?php echo APP_URL; ?>/auth/login">Services</a>
                        <?php endif; ?>
                    </li>
                    <?php if (Auth::check()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/customer/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/auth/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/auth/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/auth/register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Professional Cleaning Services</h1>
                    <p class="lead mb-4">We provide top-quality cleaning services for your home and office. Book now and get 20% discount on your first order!</p>
                    <?php if (Auth::check()): ?>
                        <a href="<?php echo APP_URL; ?>/customer/book" class="btn btn-light btn-lg">
                            <i class="bi bi-calendar3"></i> Book a Service
                        </a>
                    <?php else: ?>
                        <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-light btn-lg">
                            <i class="bi bi-calendar3"></i> Book a Service
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-bucket-fill" style="font-size: 150px;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Services</h2>
            <div class="row">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php 
                                    // Map service names to image files
                                    $imageMap = [
                                        'Regular Home Cleaning' => 'reguler_home_cleaning.jpg',
                                        'Deep Cleaning' => 'deep_cleaning.jpg',
                                        'Office Cleaning' => 'office_cleaning.jpg',
                                        'Carpet Cleaning' => 'carpet_cleaning.jpg',
                                        'Window Cleaning' => 'window_cleaning.jpg'
                                    ];
                                    $imageName = isset($imageMap[$service->name]) ? $imageMap[$service->name] : null;
                                    $imagePath = $imageName ? ASSETS_PATH . 'images/' . $imageName : null;
                                ?>
                                <?php if ($imagePath): ?>
                                    <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($service->name); ?>" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-white" style="font-size: 60px;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($service->name); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($service->description, 0, 100)); ?>...</p>
                                    <p class="fw-bold">$<?php echo number_format($service->price_per_hour, 2); ?>/hour</p>
                                </div>
                                <div class="card-footer bg-white">
                                    <?php if (Auth::check()): ?>
                                        <a href="<?php echo APP_URL; ?>/customer/book" class="btn btn-primary btn-sm w-100">Book Now</a>
                                    <?php else: ?>
                                        <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-primary btn-sm w-100">Book Now</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="reviews bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Customer Reviews</h2>
            <div class="row">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <?php for ($i = 0; $i < $review->rating; $i++): ?>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        <?php endfor; ?>
                                        <?php for ($i = $review->rating; $i < 5; $i++): ?>
                                            <i class="bi bi-star text-warning"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="card-text">"<?php echo htmlspecialchars($review->review); ?>"</p>
                                    <p class="text-muted">- <?php echo htmlspecialchars($review->customer_name); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>About Us</h5>
                    <p>Cleanse is a professional cleaning service provider with years of experience.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo APP_URL; ?>" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="<?php echo APP_URL; ?>/customer/services" class="text-white text-decoration-none">Services</a></li>
                        <li><a href="<?php echo APP_URL; ?>/auth/login" class="text-white text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Contact</h5>
                    <p>Email: info@cleanse.com<br>Phone: +1-800-CLEANSE</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Cleanse. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/script.js"></script>
</body>
</html>
