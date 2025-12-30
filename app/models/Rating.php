<?php
class Rating {
    public $id;
    public $order_id;
    public $customer_id;
    public $customer_name;
    public $rating;
    public $review;
    public $staff_rating;
    public $staff_review;
    public $created_at;
    
    public static function create($data) {
        $sql = "INSERT INTO ratings (order_id, customer_id, rating, review, staff_rating, staff_review) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        Database::query($sql, [
            $data['order_id'],
            $data['customer_id'],
            $data['rating'],
            $data['review'] ?? null,
            $data['staff_rating'] ?? null,
            $data['staff_review'] ?? null
        ]);
        
        return self::find(Database::lastInsertId());
    }
    
    public static function find($id) {
        $sql = "SELECT r.*, u.full_name as customer_name 
                FROM ratings r
                JOIN users u ON r.customer_id = u.id
                WHERE r.id = ?";
        
        $data = Database::fetch($sql, [$id]);
        
        if ($data) {
            return self::createFromData($data);
        }
        
        return null;
    }
    
    public static function getAverageRating() {
        $sql = "SELECT AVG(rating) as avg_rating FROM ratings";
        $result = Database::fetch($sql);
        return round($result['avg_rating'] ?? 0, 1);
    }
    
    public static function getRecent($limit = 5) {
        $sql = "SELECT r.*, u.full_name as customer_name 
                FROM ratings r
                JOIN users u ON r.customer_id = u.id
                ORDER BY r.created_at DESC 
                LIMIT ?";
        
        $stmt = Database::query($sql, [$limit]);
        $ratings = [];
        
        while ($data = $stmt->fetch()) {
            $ratings[] = self::createFromData($data);
        }
        
        return $ratings;
    }
    
    private static function createFromData($data) {
        $rating = new self();
        foreach ($data as $key => $value) {
            if (property_exists($rating, $key)) {
                $rating->$key = $value;
            }
        }
        return $rating;
    }
}
?>