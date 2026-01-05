<?php
// Load configuration
require_once '../config/constant.php';

// Autoload classes
spl_autoload_register(function($className) {
    $paths = [
        APP_ROOT . '/app/core/',
        APP_ROOT . '/app/models/',
        APP_ROOT . '/app/controllers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize session and auth
session_start();
Auth::init();
Auth::checkSessionTimeout();

// Create router
$router = new Router(APP_URL);

// Public routes
$router->add('/', function() {
    try {
        $services = Service::all(true);
        $reviews = Rating::getRecent(3);
    } catch (Exception $e) {
        $services = [];
        $reviews = [];
    }
    
    $data = [
        'services' => $services,
        'reviews' => $reviews,
        'title' => 'Cleanse - Professional Cleaning Services'
    ];
    
    $controller = new BaseController();
    echo $controller->view('home', $data);
});

// Auth routes
$router->add('/auth/login', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = Auth::login($email, $password);
        
        if ($user) {
            if ($user->role === 'customer') {
                Router::redirect(APP_URL . '/customer/dashboard');
            } elseif ($user->role === 'owner') {
                Router::redirect(APP_URL . '/owner/payroll');
            } else {
                Router::redirect(APP_URL . '/admin/dashboard');
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            Router::redirect(APP_URL . '/auth/login');
        }
    } else {
        $controller = new BaseController();
        echo $controller->view('auth/login');
    }
});

$router->add('/auth/register', function() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone']
        ];
        
        $user = User::create($userData);
        
        if ($user) {
            Auth::login($_POST['email'], $_POST['password']);
            Router::redirect(APP_URL . '/customer/dashboard');
        } else {
            $_SESSION['error'] = 'Registration failed';
            Router::back();
        }
    } else {
        $controller = new BaseController();
        echo $controller->view('auth/register');
    }
});

$router->add('/auth/logout', function() {
    Auth::logout();
});

// Customer routes
$router->add('/customer/dashboard', function() {
    $controller = new CustomerController();
    $controller->dashboard();
});

$router->add('/customer/services', function() {
    $controller = new CustomerController();
    $controller->services();
});

$router->add('/customer/book', function() {
    $controller = new CustomerController();
    $controller->bookService();
});

$router->add('/customer/payment/{id}', function($id) {
    $controller = new CustomerController();
    $controller->payment($id);
});

$router->add('/customer/rate/{id}', function($id) {
    $controller = new CustomerController();
    $controller->rateService($id);
});

$router->add('/customer/orders', function() {
    $controller = new CustomerController();
    $controller->orders();
});

$router->add('/customer/profile', function() {
    $controller = new CustomerController();
    $controller->profile();
});

// Admin routes
$router->add('/admin/dashboard', function() {
    if (Auth::isOwner()) {
        $controller = new OwnerController();
        $controller->dashboard();
    } else {
        $controller = new AdminController();
        $controller->dashboard();
    }
});

$router->add('/admin/orders', function() {
    $controller = new AdminController();
    $controller->orders();
});

$router->add('/admin/orders/{id}/status', function($id) {
    $controller = new AdminController();
    $controller->updateOrderStatus($id);
});

$router->add('/admin/staff', function() {
    $controller = new AdminController();
    $controller->manageStaff();
});

$router->add('/admin/payments', function() {
    $controller = new AdminController();
    $controller->payments();
});

$router->add('/admin/financial-report', function() {
    $controller = new AdminController();
    $controller->financialReport();
});

// Owner routes
$router->add('/owner/payroll', function() {
    Auth::requireRole(ROLE_OWNER);
    $controller = new OwnerController();
    $controller->payroll();
});

$router->add('/owner/salary-reports', function() {
    Auth::requireRole(ROLE_OWNER);
    $controller = new OwnerController();
    $controller->salaryReports();
});

$router->add('/owner/financial-report', function() {
    Auth::requireRole(ROLE_OWNER);
    $controller = new OwnerController();
    $controller->financialReport();
});

$router->add('/owner/salary-reports/{type}/{id}/mark-paid', function($type, $id) {
    Auth::requireRole(ROLE_OWNER);
    $controller = new OwnerController();
    $controller->markSalaryPaid($type, $id);
});

// Catch-all for 404
$router->add('/404', function() {
    http_response_code(404);
    $controller = new BaseController();
    echo $controller->view('errors/404');
});

// Dispatch the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extract the path after /cleanse-website/ or /cleanse-website/public/
$requestUri = preg_replace('#^/cleanse-website(/?public)?#', '', $requestUri);
$requestUri = rtrim($requestUri, '/');

if ($requestUri === '') {
    $requestUri = '/';
}

// Debug: log the request URI
error_log('REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . ' - Parsed: ' . $requestUri);
echo "<!-- DEBUG: Parsed URI = " . htmlspecialchars($requestUri) . " -->\n";

$router->dispatch($requestUri);
?>