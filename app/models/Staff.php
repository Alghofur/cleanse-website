<?php
class Staff {
    public $id;
    public $user_id;
    public $position;
    public $base_salary;
    public $bonus;
    public $deductions;
    public $net_salary;
    public $payment_date;
    public $payment_status;
    
    public static function getAvailableStaff($date, $time) {
        $sql = "SELECT u.* FROM users u
                WHERE u.role_id IN (2, 3) 
                AND u.id NOT IN (
                    SELECT staff_id FROM staff_availability 
                    WHERE available_date = ? 
                    AND ? BETWEEN start_time AND end_time
                    AND is_available = 0
                )";
        
        $stmt = Database::query($sql, [$date, $time]);
        $staff = [];
        
        while ($data = $stmt->fetch()) {
            $staff[] = User::createFromData($data);
        }
        
        return $staff;
    }
    
    public static function updateAvailability($staffId, $date, $startTime, $endTime, $available, $orderId = null) {
        $sql = "INSERT INTO staff_availability (staff_id, available_date, start_time, end_time, is_available, assigned_order_id)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                is_available = VALUES(is_available),
                assigned_order_id = VALUES(assigned_order_id)";
        
        return Database::query($sql, [
            $staffId, $date, $startTime, $endTime, $available, $orderId
        ]);
    }
    
    public static function processSalary($staffId, $data) {
        $sql = "INSERT INTO staff_salary 
                (staff_id, base_salary, bonus, deductions, net_salary, payment_date, payment_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $netSalary = $data['base_salary'] + $data['bonus'] - $data['deductions'];
        
        Database::query($sql, [
            $staffId,
            $data['base_salary'],
            $data['bonus'],
            $data['deductions'],
            $netSalary,
            $data['payment_date'],
            $data['payment_method']
        ]);
        
        return Database::lastInsertId();
    }
    
    public static function getSalaryHistory($staffId) {
        $sql = "SELECT * FROM staff_salary 
                WHERE staff_id = ? 
                ORDER BY payment_date DESC";
        
        $stmt = Database::query($sql, [$staffId]);
        return $stmt->fetchAll();
    }
    
    public static function getMonthlySalaryReport($month, $year) {
        $sql = "SELECT 
                u.full_name,
                ss.base_salary,
                ss.bonus,
                ss.deductions,
                ss.net_salary,
                ss.payment_date,
                ss.payment_status
                FROM staff_salary ss
                JOIN users u ON ss.staff_id = u.id
                WHERE MONTH(ss.payment_date) = ? 
                AND YEAR(ss.payment_date) = ?
                ORDER BY u.full_name";
        
        return Database::fetchAll($sql, [$month, $year]);
    }
}
?>