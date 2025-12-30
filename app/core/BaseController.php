<?php
class BaseController {
    protected $db;
    protected $auth;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->auth = Auth::class;
    }
    
    public function view($view, $data = []) {
        extract($data);
        
        $viewFile = APP_ROOT . "/app/views/{$view}.php";
        
        if (file_exists($viewFile)) {
            ob_start();
            require_once $viewFile;
            return ob_get_clean();
        } else {
            throw new Exception("View {$view} not found");
        }
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            $ruleParts = explode('|', $rule);
            
            foreach ($ruleParts as $part) {
                if ($part === 'required' && empty(trim($value))) {
                    $errors[$field][] = "The {$field} field is required.";
                }
                
                if ($part === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "The {$field} must be a valid email address.";
                }
                
                if (strpos($part, 'min:') === 0) {
                    $min = (int) str_replace('min:', '', $part);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "The {$field} must be at least {$min} characters.";
                    }
                }
                
                if (strpos($part, 'max:') === 0) {
                    $max = (int) str_replace('max:', '', $part);
                    if (strlen($value) > $max) {
                        $errors[$field][] = "The {$field} may not be greater than {$max} characters.";
                    }
                }
            }
        }
        
        return $errors;
    }
    
    protected function generateOrderNumber() {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
    
    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}
?>