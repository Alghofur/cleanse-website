<?php
class Payment {
    public $id;
    public $order_id;
    public $amount;
    public $payment_method;
    public $payment_status;
    public $payment_date;
    public $transaction_id;
    public $notes;
    
    public static function create($data) {
        $sql = "INSERT INTO payments (order_id, amount, payment_method, payment_status, payment_date, transaction_id) 
                VALUES (?, ?, ?, ?, NOW(), ?)";
        
        Database::query($sql, [
            $data['order_id'],
            $data['amount'],
            $data['payment_method'],
            'paid',  // Set status langsung ke paid saat customer bayar
            $data['transaction_id'] ?? uniqid('TRX-')
        ]);
        
        $paymentId = Database::lastInsertId();
        
        // Update order payment status
        $updateSql = "UPDATE orders SET status = 'confirmed' WHERE id = ?";
        Database::query($updateSql, [$data['order_id']]);
        
        return self::find($paymentId);
    }
    
    public static function find($id) {
        $sql = "SELECT * FROM payments WHERE id = ?";
        $data = Database::fetch($sql, [$id]);
        
        if ($data) {
            return self::createFromData($data);
        }
        
        return null;
    }
    
    public function updateStatus($status) {
        $sql = "UPDATE payments SET payment_status = ?, payment_date = NOW() WHERE id = ?";
        Database::query($sql, [$status, $this->id]);
        
        return self::find($this->id);
    }
    
    public static function getTotalRevenue() {
        $sql = "SELECT SUM(amount) as total_revenue FROM payments WHERE payment_status = 'paid'";
        $result = Database::fetch($sql);
        return $result['total_revenue'] ?? 0;
    }
    
    private static function createFromData($data) {
        $payment = new self();
        foreach ($data as $key => $value) {
            if (property_exists($payment, $key)) {
                $payment->$key = $value;
            }
        }
        return $payment;
    }
}
?>