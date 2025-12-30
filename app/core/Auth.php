<?php
// Session is started in public/index.php

class Auth {
    private static $user = null;
    
    public static function init() {
        if (isset($_SESSION['user_id'])) {
            self::$user = User::find($_SESSION['user_id']);
        }
    }
    
    public static function login($email, $password) {
        $user = User::findByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;
            $_SESSION['username'] = $user->username;
            self::$user = $user;
            
            // Set session timeout
            $_SESSION['last_activity'] = time();
            
            return $user;
        }
        
        return false;
    }
    
    public static function logout() {
        session_destroy();
        self::$user = null;
        header('Location: ' . APP_URL . '/auth/login');
        exit();
    }
    
    public static function user() {
        return self::$user;
    }
    
    public static function check() {
        return self::$user !== null;
    }
    
    public static function isAdmin() {
        return self::check() && (self::$user->role === ROLE_ADMIN || self::$user->role === ROLE_OWNER);
    }
    
    public static function isOwner() {
        return self::check() && self::$user->role === ROLE_OWNER;
    }
    
    public static function isCustomer() {
        return self::check() && self::$user->role === ROLE_CUSTOMER;
    }
    
    public static function requireAuth() {
        if (!self::check()) {
            header('Location: ' . APP_URL . '/auth/login.php');
            exit();
        }
    }
    
    public static function requireRole($role) {
        self::requireAuth();
        
        if (self::$user->role !== $role) {
            http_response_code(403);
            echo "Access Denied: You don't have permission to access this page.";
            exit();
        }
    }
    
    public static function checkSessionTimeout() {
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            if ($inactive > SESSION_TIMEOUT) {
                self::logout();
            }
        }
        $_SESSION['last_activity'] = time();
    }
}
?>