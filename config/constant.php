<?php
// Application Constants
if (!defined('APP_NAME')) define('APP_NAME', 'Cleanease');
if (!defined('APP_URL')) define('APP_URL', 'http://localhost/cleanse-website');
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(dirname(__FILE__)));
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');

// path constants
define('UPLOAD_PATH', APP_ROOT . '/public/uploads/');
define('ASSETS_PATH', APP_URL . '/assets/');

// session configurations
define('SESSION_TIMEOUT', 3600); // in 1 hour

// status constants
define('STATUS_PENDING', 'pending');
define('STATUS_CONFIRMED', 'confirmed');
define('STATUS_IN_PROGRESS', 'in_progress');
define('STATUS_COMPLETED', 'completed');
define('STATUS_CANCELLED', 'cancelled');

// Payment status
define('PAYMENT_PENDING', 'pending');
define('PAYMENT_PAID', 'paid');
define('PAYMENT_FAILED', 'failed');

// Roles
define('ROLE_CUSTOMER', 'customer');
define('ROLE_ADMIN', 'admin');
define('ROLE_OWNER', 'owner');