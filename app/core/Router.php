<?php
class Router {
    private $routes = [];
    private $basePath = '';
    
    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }
    
    public function add($route, $callback) {
        $this->routes[$route] = $callback;
    }
    
    public function dispatch($requestUri = null) {
        // If requestUri not provided, get from REQUEST_URI
        if ($requestUri === null) {
            $requestUri = $_SERVER['REQUEST_URI'];
            
            // Remove base path
            if ($this->basePath) {
                $requestUri = str_replace($this->basePath, '', $requestUri);
            }
            
            // Remove query string
            $requestUri = strtok($requestUri, '?');
        }
        
        // Remove query string if present
        $requestUri = strtok($requestUri, '?');
        
        // Find matching route
        foreach ($this->routes as $route => $callback) {
            // Convert route to regex pattern
            $pattern = '#^' . preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route) . '$#';
            
            if (preg_match($pattern, $requestUri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                if (is_callable($callback)) {
                    return call_user_func_array($callback, $params);
                }
                
                // If callback is Controller@method format
                if (is_string($callback) && strpos($callback, '@') !== false) {
                    list($controller, $method) = explode('@', $callback);
                    
                    $controllerFile = APP_ROOT . "/app/controllers/{$controller}.php";
                    if (file_exists($controllerFile)) {
                        require_once $controllerFile;
                        $controllerInstance = new $controller();
                        
                        if (method_exists($controllerInstance, $method)) {
                            return call_user_func_array([$controllerInstance, $method], $params);
                        }
                    }
                }
            }
        }
        
        // 404 - Page not found
        http_response_code(404);
        require_once APP_ROOT . '/app/views/errors/404.php';
        exit();
    }
    
    public static function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    public static function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL;
        self::redirect($referer);
    }
}
?>