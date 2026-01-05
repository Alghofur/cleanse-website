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
        $baseSalary = floatval($_POST['base_salary'] ?? 0);
        $bonus = floatval($_POST['bonus'] ?? 0);
        $deductions = floatval($_POST['deductions'] ?? 0);
        
        $salaryData = [
            'base_salary' => $baseSalary,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'payment_date' => date('Y-m-d'),
            'payment_method' => 'bank_transfer'
        ];
        
        Staff::processSalary($staffId, $salaryData);
    }
    
    private function processAdminSalary() {
        $adminId = $_POST['staff_id'];
        $baseSalary = floatval($_POST['base_salary'] ?? 0);
        $bonus = floatval($_POST['bonus'] ?? 0);
        $deductions = floatval($_POST['deductions'] ?? 0);
        
        $sql = "INSERT INTO admin_salary 
                (admin_id, base_salary, bonus, deductions, net_salary, payment_date, paid_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $netSalary = $baseSalary + $bonus - $deductions;
        
        Database::query($sql, [
            $adminId,
            $baseSalary,
            $bonus,
            $deductions,
            $netSalary,
            date('Y-m-d'),
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
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // Get pending staff salaries from current month
        $staffSql = "SELECT 
                u.full_name as name,
                'staff' as type,
                ss.base_salary as amount,
                ss.id,
                ss.payment_status
                FROM staff_salary ss
                JOIN users u ON ss.staff_id = u.id
                WHERE MONTH(ss.payment_date) = ? 
                AND YEAR(ss.payment_date) = ?
                AND ss.payment_status = 'pending'
                ORDER BY u.full_name";
        
        // Get pending admin salaries from current month
        $adminSql = "SELECT 
                u.full_name as name,
                'admin' as type,
                asal.base_salary as amount,
                asal.id,
                asal.payment_status
                FROM admin_salary asal
                JOIN users u ON asal.admin_id = u.id
                WHERE MONTH(asal.payment_date) = ? 
                AND YEAR(asal.payment_date) = ?
                AND asal.payment_status = 'pending'
                ORDER BY u.full_name";
        
        $staffPending = Database::fetchAll($staffSql, [$currentMonth, $currentYear]);
        $adminPending = Database::fetchAll($adminSql, [$currentMonth, $currentYear]);
        
        return array_merge($staffPending ?? [], $adminPending ?? []);
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
                           asal.id,
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
    
    public function markSalaryPaid($type = 'staff', $id = null) {
        Auth::requireRole(ROLE_OWNER);
        
        if ($type === 'staff') {
            $sql = "UPDATE staff_salary SET payment_status = 'paid' WHERE id = ?";
        } else {
            $sql = "UPDATE admin_salary SET payment_status = 'paid' WHERE id = ?";
        }
        
        Database::query($sql, [$id]);
        
        $_SESSION['success'] = 'Salary marked as paid successfully!';
        Router::back();
    }
    
    public function financialReport() {
        Auth::requireRole(ROLE_OWNER);
        
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
        
        echo $this->view('owner/financial-report', [
            'income' => $income,
            'expenses' => $expenses,
            'month' => $month,
            'year' => $year
        ]);
    }
}
?>