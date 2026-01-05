<?php
class Order {
    public $id;
    public $order_number;
    public $customer_id;
    public $service_id;
    public $order_date;
    public $schedule_date;
    public $schedule_time;
    public $address;
    public $city;
    public $postal_code;
    public $special_instructions;
    public $status;
    public $total_price;
    public $staff_id;
    public $completed_at;
    
    // Derived fields from joins
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $service_name;
    public $service_price;
    public $staff_name;
    
    // Relationships
    public $customer;
    public $service;
    public $staff;
    public $payment;
    public $rating;
    
    public static function find($id) {
        $sql = "SELECT o.*, 
                u.full_name as customer_name,
                u.email as customer_email,
                u.phone as customer_phone,
                s.name as service_name,
                s.price_per_hour as service_price,
                st.full_name as staff_name
                FROM orders o
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN services s ON o.service_id = s.id
                LEFT JOIN users st ON o.staff_id = st.id
                WHERE o.id = ?";
        
        $stmt = Database::query($sql, [$id]);
        $data = $stmt->fetch();
        
        if ($data) {
            $order = self::createFromData($data);
            $order->loadRelations();
            return $order;
        }
        
        return null;
    }
    
    public static function all() {
        $sql = "SELECT o.*, 
                u.full_name as customer_name,
                s.name as service_name
                FROM orders o
                JOIN users u ON o.customer_id = u.id
                JOIN services s ON o.service_id = s.id
                ORDER BY o.order_date DESC";
        
        $stmt = Database::query($sql);
        $orders = [];
        
        while ($data = $stmt->fetch()) {
            $order = self::createFromData($data);
            $orders[] = $order;
        }
        
        return $orders;
    }
    
    public static function findByCustomer($customerId) {
        $sql = "SELECT o.*, s.name as service_name 
                FROM orders o
                JOIN services s ON o.service_id = s.id
                WHERE o.customer_id = ?
                ORDER BY o.order_date DESC";
        
        $stmt = Database::query($sql, [$customerId]);
        $orders = [];
        
        while ($data = $stmt->fetch()) {
            $order = self::createFromData($data);
            $orders[] = $order;
        }
        
        return $orders;
    }
    
    public static function findByStatus($status) {
        $sql = "SELECT o.*, 
                u.full_name as customer_name,
                s.name as service_name
                FROM orders o
                JOIN users u ON o.customer_id = u.id
                JOIN services s ON o.service_id = s.id
                WHERE o.status = ?
                ORDER BY o.schedule_date, o.schedule_time";
        
        $stmt = Database::query($sql, [$status]);
        $orders = [];
        
        while ($data = $stmt->fetch()) {
            $order = self::createFromData($data);
            $orders[] = $order;
        }
        
        return $orders;
    }
    
    public static function create($data) {
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        $sql = "INSERT INTO orders 
                (order_number, customer_id, service_id, schedule_date, schedule_time, 
                 address, city, postal_code, special_instructions, total_price) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        Database::query($sql, [
            $orderNumber,
            $data['customer_id'],
            $data['service_id'],
            $data['schedule_date'],
            $data['schedule_time'],
            $data['address'],
            $data['city'],
            $data['postal_code'],
            $data['special_instructions'] ?? null,
            $data['total_price']
        ]);
        
        return self::find(Database::lastInsertId());
    }
    
    public function updateStatus($status, $staffId = null) {
        $sql = "UPDATE orders SET status = ?, staff_id = ? WHERE id = ?";
        Database::query($sql, [$status, $staffId, $this->id]);
        
        if ($status === 'completed') {
            $this->markAsCompleted();
        }
        
        return self::find($this->id);
    }
    
    public function markAsCompleted() {
        $sql = "UPDATE orders SET completed_at = NOW() WHERE id = ?";
        Database::query($sql, [$this->id]);
    }
    
    public function getPayment() {
        $sql = "SELECT * FROM payments WHERE order_id = ?";
        $data = Database::fetch($sql, [$this->id]);
        
        if ($data) {
            $payment = new Payment();
            foreach ($data as $key => $value) {
                if (property_exists($payment, $key)) {
                    $payment->$key = $value;
                }
            }
            return $payment;
        }
        
        return null;
    }
    
    public function getRating() {
        $sql = "SELECT * FROM ratings WHERE order_id = ?";
        $data = Database::fetch($sql, [$this->id]);
        
        if ($data) {
            $rating = new Rating();
            foreach ($data as $key => $value) {
                if (property_exists($rating, $key)) {
                    $rating->$key = $value;
                }
            }
            return $rating;
        }
        
        return null;
    }
    
    public static function getStatistics() {
        $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_orders,
                SUM(total_price) as total_revenue
                FROM orders";
        
        return Database::fetch($sql);
    }
    
    private function loadRelations() {
        $this->customer = User::find($this->customer_id);
        $this->service = Service::find($this->service_id);
        $this->payment = $this->getPayment();
        $this->rating = $this->getRating();
        
        if ($this->staff_id) {
            $this->staff = User::find($this->staff_id);
        }
    }
    
    private static function createFromData($data) {
        $order = new self();
        foreach ($data as $key => $value) {
            if (property_exists($order, $key)) {
                $order->$key = $value;
            }
        }
        return $order;
    }
}
?>