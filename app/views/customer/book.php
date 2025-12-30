<?php
$title = 'Book Service - Cleanse';
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
            <div class="col-md-8">
                <h1 class="mb-4">Book a Service</h1>

                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['errors'] as $field => $errors): ?>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <form method="POST" class="card p-4 shadow-sm">
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Select Service</label>
                        <select name="service_id" id="service_id" class="form-control" required>
                            <option value="">-- Select a service --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?php echo $service->id; ?>">
                                    <?php echo htmlspecialchars($service->name); ?> - $<?php echo number_format($service->price_per_hour, 2); ?>/hour
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="schedule_date" class="form-label">Schedule Date</label>
                            <input type="date" name="schedule_date" id="schedule_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="schedule_time" class="form-label">Schedule Time</label>
                            <input type="time" name="schedule_time" id="schedule_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Street address" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="instructions" class="form-label">Special Instructions</label>
                        <textarea name="instructions" id="instructions" class="form-control" rows="3" placeholder="Any special requests?"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-calendar-check"></i> Book Service
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
