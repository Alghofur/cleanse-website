<?php
class Service {
    public $id;
    public $name;
    public $description;
    public $price_per_hour;
    public $duration_hours;
    public $image_url;
    public $is_available;
    public $created_at;
    
    public static function find($id) {
        $sql = "SELECT * FROM services WHERE id = ?";
        $stmt = Database::query($sql, [$id]);
        $data = $stmt->fetch();
        
        if ($data) {
            return self::createFromData($data);
        }
        
        return null;
    }
    
    public static function all($availableOnly = false) {
        $sql = "SELECT * FROM services";
        
        if ($availableOnly) {
            $sql .= " WHERE is_available = 1";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = Database::query($sql);
        $services = [];
        
        while ($data = $stmt->fetch()) {
            $services[] = self::createFromData($data);
        }
        
        return $services;
    }
    
    public static function create($data) {
        $sql = "INSERT INTO services (name, description, price_per_hour, duration_hours, image_url) 
                VALUES (?, ?, ?, ?, ?)";
        
        Database::query($sql, [
            $data['name'],
            $data['description'],
            $data['price_per_hour'],
            $data['duration_hours'],
            $data['image_url'] ?? null
        ]);
        
        return self::find(Database::lastInsertId());
    }
    
    public function update($data) {
        $sql = "UPDATE services SET 
                name = ?, 
                description = ?, 
                price_per_hour = ?, 
                duration_hours = ?, 
                image_url = ?, 
                is_available = ? 
                WHERE id = ?";
        
        Database::query($sql, [
            $data['name'] ?? $this->name,
            $data['description'] ?? $this->description,
            $data['price_per_hour'] ?? $this->price_per_hour,
            $data['duration_hours'] ?? $this->duration_hours,
            $data['image_url'] ?? $this->image_url,
            $data['is_available'] ?? $this->is_available,
            $this->id
        ]);
        
        return self::find($this->id);
    }
    
    public function delete() {
        $sql = "DELETE FROM services WHERE id = ?";
        return Database::query($sql, [$this->id]);
    }
    
    public function calculatePrice($hours = null) {
        $hours = $hours ?? $this->duration_hours;
        return $this->price_per_hour * $hours;
    }
    
    public function getOrders() {
        return Order::findByService($this->id);
    }
    
    public function getAverageRating() {
        $sql = "SELECT AVG(r.rating) as avg_rating 
                FROM ratings r 
                JOIN orders o ON r.order_id = o.id 
                WHERE o.service_id = ?";
        
        $result = Database::fetch($sql, [$this->id]);
        return round($result['avg_rating'] ?? 0, 1);
    }
    
    private static function createFromData($data) {
        $service = new self();
        foreach ($data as $key => $value) {
            if (property_exists($service, $key)) {
                $service->$key = $value;
            }
        }
        return $service;
    }
}
?>