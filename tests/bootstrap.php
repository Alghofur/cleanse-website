<?php
// Define APP_ROOT for tests if not already defined
if (!defined('APP_ROOT')) {
    define('APP_ROOT', __DIR__ . '/..');
}

// Load environment configuration for tests
if (!defined('APP_NAME')) {
    require_once APP_ROOT . '/config/constant.php';
}

// Mock Database class for testing (dapat di-override per test)
if (!class_exists('Database')) {
    require_once APP_ROOT . '/app/core/Database.php';
}

// Load core classes
require_once APP_ROOT . '/app/core/Auth.php';
require_once APP_ROOT . '/app/core/BaseController.php';
require_once APP_ROOT . '/app/core/Router.php';

// Load models
require_once APP_ROOT . '/app/models/User.php';
require_once APP_ROOT . '/app/models/Order.php';
require_once APP_ROOT . '/app/models/Service.php';
require_once APP_ROOT . '/app/models/Payment.php';
require_once APP_ROOT . '/app/models/Rating.php';
require_once APP_ROOT . '/app/models/Staff.php';

// Start session for tests (only if not already started)
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
