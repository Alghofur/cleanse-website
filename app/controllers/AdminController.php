<?php
class AdminController extends BaseController {
    
    public function dashboard() {
        Auth::requireRole(ROLE_ADMIN);
        
        $stats = Order::getStatistics();
        $pendingOrders = Order::findByStatus('pending');
        $staff = User::all('admin');
        $recentOrders = array_slice(Order::findByStatus('in_progress'), 0, 5);
        
        $data = [
            'stats' => $stats,
            'pendingOrders' => $pendingOrders,
            'staff' => $staff,
            'recentOrders' => $recentOrders,
            'totalRevenue' => Payment::getTotalRevenue()
        ];
        
        echo $this->view('admin/dashboard', $data);
    }
    
    public function orders() {
        Auth::requireRole(ROLE_ADMIN);
        
        $status = $_GET['status'] ?? 'all';
        $orders = $status === 'all' ? Order::all() : Order::findByStatus($status);
        
        echo $this->view('admin/orders', [
            'orders' => $orders,
            'currentStatus' => $status
        ]);
    }
    
    public function updateOrderStatus($orderId) {
        Auth::requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = Order::find($orderId);
            $newStatus = $_POST['status'];
            $staffId = $_POST['staff_id'] ?? null;
            
            $order->updateStatus($newStatus, $staffId);
            
            $_SESSION['success'] = 'Order status updated successfully!';
            Router::back();
        }
    }
    
    public function manageStaff() {
        Auth::requireRole(ROLE_ADMIN);
        
        $staff = User::all('admin');
        $availableStaff = Staff::getAvailableStaff(date('Y-m-d'), date('H:i:s'));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'];
            
            if ($action === 'update_availability') {
                Staff::updateAvailability(
                    $_POST['staff_id'],
                    $_POST['date'],
                    $_POST['start_time'],
                    $_POST['end_time'],
                    $_POST['available'],
                    $_POST['order_id'] ?? null
                );
                
                $_SESSION['success'] = 'Staff availability updated!';
            }
        }
        
        echo $this->view('admin/staff', [
            'staff' => $staff,
            'availableStaff' => $availableStaff
        ]);
    }
    
    public function payments() {
        Auth::requireRole(ROLE_ADMIN);
        
        $sql = "SELECT p.*, o.order_number, u.full_name as customer_name 
                FROM payments p
                JOIN orders o ON p.order_id = o.id
                JOIN users u ON o.customer_id = u.id
                ORDER BY p.payment_date DESC";
        
        $payments = Database::fetchAll($sql);
        
        echo $this->view('admin/payments', ['payments' => $payments]);
    }
    
    public function verifyPayment($paymentId) {
        Auth::requireRole(ROLE_ADMIN);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payment = Payment::find($paymentId);
            $status = $_POST['status'];
            
            $payment->updateStatus($status);
            
            $_SESSION['success'] = 'Payment status updated!';
            Router::redirect(APP_URL . '/admin/payments');
        }
    }
    
    public function financialReport() {
        Auth::requireRole(ROLE_ADMIN);
        
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        
        // Get income from payments
        $incomeSql = "SELECT 
                      SUM(amount) as total_income,
                      COUNT(*) as transaction_count,
                      payment_method,
                      DATE_FORMAT(payment_date, '%Y-%m') as month
                      FROM payments 
                      WHERE payment_status = 'paid'
                      AND MONTH(payment_date) = ? 
                      AND YEAR(payment_date) = ?
                      GROUP BY payment_method, month";
        
        $income = Database::fetchAll($incomeSql, [$month, $year]);
        
        // Get expenses from salaries
        $expenseSql = "SELECT 
                       SUM(net_salary) as total_expense,
                       COUNT(*) as salary_count
                       FROM staff_salary 
                       WHERE MONTH(payment_date) = ? 
                       AND YEAR(payment_date) = ?";
        
        $expenses = Database::fetch($expenseSql, [$month, $year]);
        
        echo $this->view('admin/financial-report', [
            'income' => $income,
            'expenses' => $expenses,
            'month' => $month,
            'year' => $year
        ]);
    }
}
?>