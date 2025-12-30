<?php
class User {
    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $role_id;
    public $role;
    public $profile_image;
    public $is_active;
    public $created_at;
    public $updated_at;
    
    public static function find($id) {
        $sql = "SELECT u.*, r.role_name as role FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.id = ?";
        $stmt = Database::query($sql, [$id]);
        $data = $stmt->fetch();
        
        if ($data) {
            return self::createFromData($data);
        }
        
        return null;
    }
    
    public static function findByEmail($email) {
        $sql = "SELECT u.*, r.role_name as role FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = ?";
        $stmt = Database::query($sql, [$email]);
        $data = $stmt->fetch();
        
        if ($data) {
            return self::createFromData($data);
        }
        
        return null;
    }
    
    public static function create($data) {
        $sql = "INSERT INTO users (username, email, password, full_name, phone, role_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        Database::query($sql, [
            $data['username'],
            $data['email'],
            $hashedPassword,
            $data['full_name'],
            $data['phone'] ?? null,
            $data['role_id'] ?? 1
        ]);
        
        return self::find(Database::lastInsertId());
    }
    
    public function update($data) {
        $updates = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            $updates[] = "{$key} = ?";
            $params[] = $value;
        }
        
        $params[] = $this->id;
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        Database::query($sql, $params);
        
        return self::find($this->id);
    }
    
    public function delete() {
        $sql = "DELETE FROM users WHERE id = ?";
        return Database::query($sql, [$this->id]);
    }
    
    public static function all($role = null) {
        $sql = "SELECT u.*, r.role_name as role FROM users u 
                JOIN roles r ON u.role_id = r.id";
        
        if ($role) {
            $sql .= " WHERE r.role_name = ?";
            $stmt = Database::query($sql, [$role]);
        } else {
            $stmt = Database::query($sql);
        }
        
        $users = [];
        while ($data = $stmt->fetch()) {
            $users[] = self::createFromData($data);
        }
        
        return $users;
    }
    
    public static function countByRole($role) {
        $sql = "SELECT COUNT(*) as count FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE r.role_name = ?";
        $result = Database::fetch($sql, [$role]);
        return $result['count'];
    }
    
    public static function createFromData($data) {
        $user = new self();
        foreach ($data as $key => $value) {
            if (property_exists($user, $key)) {
                $user->$key = $value;
            }
        }
        return $user;
    }
    
    public function getOrders() {
        return Order::findByCustomer($this->id);
    }
    
    public function isStaff() {
        return in_array($this->role, ['admin', 'owner']);
    }
}
?>