<?php
class OwnerController extends BaseController {
    
    public function dashboard() {
        Auth::requireRole(ROLE_OWNER);
        
        // Get all admin dashboard data
        $stats = Order::getStatistics();
        $pendingOrders = Order::findByStatus('pending');
        $staff = User::all('admin');
        $recentOrders = array_slice(Order::all(), 0, 5);
        
        // Add owner-specific data
        $data = [
            'stats' => $stats,
            'pendingOrders' => $pendingOrders,
            'staff' => $staff,
            'recentOrders' => $recentOrders,
            'totalRevenue' => Payment::getTotalRevenue(),
            'isOwner' => true
        ];
        
        echo $this->view('admin/dashboard', $data);
    }
    
    public function payroll() {
        Auth::requireRole(ROLE_OWNER);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type']; // staff or admin
            
            if ($type === 'staff') {
                $this->processStaffSalary();
            } else {
                $this->processAdminSalary();
            }
            
            $_SESSION['success'] = 'Salary processed successfully!';
            Router::back();
        }
        
        $staff = User::all('admin');
        $pendingSalaries = $this->getPendingSalaries();
        
        echo $this->view('owner/payroll', [
            'staff' => $staff,
            'pendingSalaries' => $pendingSalaries
        ]);
    }
    
    private function processStaffSalary() {
        $staffId = $_POST['staff_id'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        
        // Calculate salary based on completed orders
        $salarySql = "SELECT 
                      COUNT(*) as completed_orders,
                      SUM(o.total_price) as total_revenue
                      FROM orders o
                      WHERE o.staff_id = ?
                      AND o.status = 'completed'
                      AND MONTH(o.completed_at) = ?
                      AND YEAR(o.completed_at) = ?";
        
        $performance = Database::fetch($salarySql, [$staffId, $month, $year]);
        
        $baseSalary = 2000; // Example base salary
        $bonus = $performance['completed_orders'] * 50; // $50 per completed order
        
        $salaryData = [
            'base_salary' => $baseSalary,
            'bonus' => $bonus,
            'deductions' => 0,
            'payment_date' => date('Y-m-d'),
            'payment_method' => 'bank_transfer'
        ];
        
        Staff::processSalary($staffId, $salaryData);
    }
    
    private function processAdminSalary() {
        $adminId = $_POST['admin_id'];
        
        $salaryData = [
            'base_salary' => $_POST['base_salary'],
            'bonus' => $_POST['bonus'] ?? 0,
            'deductions' => $_POST['deductions'] ?? 0,
            'payment_date' => date('Y-m-d'),
            'payment_method' => 'bank_transfer'
        ];
        
        $sql = "INSERT INTO admin_salary 
                (admin_id, base_salary, bonus, deductions, net_salary, payment_date, paid_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $netSalary = $salaryData['base_salary'] + $salaryData['bonus'] - $salaryData['deductions'];
        
        Database::query($sql, [
            $adminId,
            $salaryData['base_salary'],
            $salaryData['bonus'],
            $salaryData['deductions'],
            $netSalary,
            $salaryData['payment_date'],
            Auth::user()->id
        ]);
    }
    
    private function getStaffSalaryReport() {
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
                ORDER BY ss.payment_date DESC 
                LIMIT 10";
        
        return Database::fetchAll($sql);
    }
    
    private function getPendingSalaries() {
        $sql = "SELECT 
                u.id,
                u.full_name as name,
                'staff' as type,
                2000 as amount
                FROM users u
                WHERE u.role_id IN (2, 3)
                LIMIT 5";
        
        return Database::fetchAll($sql);
    }
    
    private function getAdminSalaryReport() {
        $sql = "SELECT 
                u.full_name,
                asal.base_salary,
                asal.bonus,
                asal.deductions,
                asal.net_salary,
                asal.payment_date,
                asal.payment_status
                FROM admin_salary asal
                JOIN users u ON asal.admin_id = u.id
                ORDER BY asal.payment_date DESC 
                LIMIT 10";
        
        return Database::fetchAll($sql);
    }
    
    private function calculateProfitLoss() {
        $income = Payment::getTotalRevenue();
        
        $expenseSql = "SELECT 
                       SUM(net_salary) as total_salary_expense 
                       FROM staff_salary 
                       WHERE payment_status = 'paid'
                       UNION ALL
                       SELECT 
                       SUM(net_salary) as total_salary_expense 
                       FROM admin_salary 
                       WHERE payment_status = 'paid'";
        
        $expenses = Database::fetchAll($expenseSql);
        $totalExpenses = array_sum(array_column($expenses, 'total_salary_expense'));
        
        return [
            'income' => $income,
            'expenses' => $totalExpenses,
            'profit' => $income - $totalExpenses
        ];
    }
    
    public function salaryReports() {
        Auth::requireRole(ROLE_OWNER);
        
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        
        $staffReport = Staff::getMonthlySalaryReport($month, $year);
        
        $adminReportSql = "SELECT 
                           u.full_name,
                           asal.base_salary,
                           asal.bonus,
                           asal.deductions,
                           asal.net_salary,
                           asal.payment_date,
                           asal.payment_status
                           FROM admin_salary asal
                           JOIN users u ON asal.admin_id = u.id
                           WHERE MONTH(asal.payment_date) = ? 
                           AND YEAR(asal.payment_date) = ?
                           ORDER BY u.full_name";
        
        $adminReport = Database::fetchAll($adminReportSql, [$month, $year]);
        
        echo $this->view('owner/salary-reports', [
            'staffReport' => $staffReport,
            'adminReport' => $adminReport,
            'month' => $month,
            'year' => $year
        ]);
    }
}
?>