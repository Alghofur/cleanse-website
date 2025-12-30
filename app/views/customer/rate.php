<?php
$title = 'Rate Service - Cleanse';
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
                <h1 class="mb-4">Rate Your Service</h1>

                <form method="POST" class="card p-4 shadow-sm">
                    <div class="mb-4">
                        <h5>How was our service?</h5>
                        <div class="rating-stars mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                    <label class="form-check-label" for="rating<?php echo $i; ?>">
                                        <?php for ($j = 1; $j <= $i; $j++): ?>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        <?php endfor; ?>
                                        <?php for ($j = $i; $j < 5; $j++): ?>
                                            <i class="bi bi-star text-warning"></i>
                                        <?php endfor; ?>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="review" class="form-label">Review (Optional)</label>
                        <textarea name="review" id="review" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                    </div>

                    <div class="mb-4">
                        <h5>Rate the Staff</h5>
                        <div class="rating-stars mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="staff_rating" id="staff_rating<?php echo $i; ?>" value="<?php echo $i; ?>">
                                    <label class="form-check-label" for="staff_rating<?php echo $i; ?>">
                                        <?php for ($j = 1; $j <= $i; $j++): ?>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        <?php endfor; ?>
                                        <?php for ($j = $i; $j < 5; $j++): ?>
                                            <i class="bi bi-star text-warning"></i>
                                        <?php endfor; ?>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="staff_review" class="form-label">Staff Review (Optional)</label>
                        <textarea name="staff_review" id="staff_review" class="form-control" rows="3" placeholder="Comment about the staff..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Review</button>
                        <a href="<?php echo APP_URL; ?>/customer/orders" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
