# Cleanse - Professional Cleaning Services Website

Aplikasi web untuk booking service cleaning profesional dengan fitur lengkap untuk customer, admin, dan owner.

## 🚀 Quick Start

### Prerequisites
- XAMPP atau Apache + PHP 7.4+
- MySQL 5.7+
- Web browser modern

### Installation

1. **Setup Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Import file `sql/cleanse_db.sql`
   - Database otomatis akan dibuat dengan sample data

2. **Access Application**
   ```
   http://localhost/cleanse-website/
   ```

## 📋 File Perbaikan Utama

Berikut file yang telah diperbaiki dan ditambahkan:

### Core Files
- ✅ `app/core/Database.php` - Fixed typo `password`
- ✅ `app/core/Auth.php` - Fixed session handling & logout redirect
- ✅ `app/core/BaseController.php` - Made `view()` method public

### Controllers
- ✅ `app/controllers/CustomerController.php` - Added `services()` method
- ✅ `app/controllers/AdminController.php` - Fixed `orders()` method
- ✅ `app/controllers/OwnerController.php` - Fixed dashboard & added `getPendingSalaries()`

### Models
- ✅ `app/models/User.php` - Added `all()` method
- ✅ `app/models/Order.php` - Added `all()` method

### Views - Customer
- ✅ `app/views/home.php` - Homepage
- ✅ `app/views/auth/login.php` - Completed login page
- ✅ `app/views/auth/register.php` - Completed registration
- ✅ `app/views/customer/dashboard.php` - Customer dashboard
- ✅ `app/views/customer/services.php` - Services listing
- ✅ `app/views/customer/book.php` - Book service form
- ✅ `app/views/customer/payment.php` - Payment page
- ✅ `app/views/customer/orders.php` - Orders listing
- ✅ `app/views/customer/rate.php` - Rate service form
- ✅ `app/views/customer/profile.php` - Customer profile

### Views - Admin
- ✅ `app/views/admin/dashboard.php` - Admin dashboard
- ✅ `app/views/admin/orders.php` - Orders management
- ✅ `app/views/admin/staff.php` - Staff management
- ✅ `app/views/admin/payments.php` - Payments listing

### Views - Owner
- ✅ `app/views/owner/payroll.php` - Payroll management

### Errors
- ✅ `app/views/errors/404.php` - 404 error page

### Config
- ✅ `config/constant.php` - Fixed typo `UPLOUD_PATH` → `UPLOAD_PATH`
- ✅ `public/index.php` - Fixed file constant references

### Documentation
- ✅ `SETUP.md` - Setup instructions
- ✅ `README.md` - This file

## 🔑 Test Credentials

### Admin User (Create manually)
```sql
INSERT INTO users (username, email, password, full_name, phone, role_id) 
VALUES ('admin', 'admin@cleanse.com', '$2y$10$...hash...', 'Administrator', '1234567890', 2);
```

### Register New Customer
- Buka `http://localhost/cleanse-website/auth/register`
- Isi form dan submit
- Login dengan credentials yang baru dibuat

## 📊 Database Schema

### Main Tables
- `users` - User accounts (customer, admin, owner)
- `roles` - User roles definition
- `services` - Available cleaning services
- `orders` - Customer service orders
- `payments` - Payment records
- `ratings` - Service ratings & reviews
- `staff_availability` - Staff schedule
- `staff_salary` - Staff salary records
- `admin_salary` - Admin salary records

### Sample Data
- 5 sample services (Regular Cleaning, Deep Cleaning, Office Cleaning, etc.)
- 3 default roles (customer, admin, owner)

## 🎯 Features

### Customer Features
- ✅ User Registration & Login
- ✅ Browse Services
- ✅ Book Cleaning Service
- ✅ Make Payment
- ✅ Track Orders
- ✅ Rate Service
- ✅ Manage Profile

### Admin Features
- ✅ Dashboard with Statistics
- ✅ Manage Orders (pending, confirmed, in progress, completed)
- ✅ Manage Staff Availability
- ✅ View Payments
- ✅ Financial Reports
- ✅ Staff Management

### Owner Features
- ✅ All Admin Features
- ✅ Process Staff Payroll
- ✅ Process Admin Payroll
- ✅ Salary Reports
- ✅ Financial Analysis

## 🔧 API Routes

### Public Routes
```
GET  /                          - Homepage
```

### Auth Routes
```
GET  /auth/login                - Login page
POST /auth/login                - Process login
GET  /auth/register             - Register page
POST /auth/register             - Process registration
GET  /auth/logout               - Logout
```

### Customer Routes
```
GET  /customer/dashboard        - Dashboard
GET  /customer/services         - Services list
GET  /customer/book             - Book service form
POST /customer/book             - Submit booking
GET  /customer/payment/{id}     - Payment page
POST /customer/payment/{id}     - Process payment
GET  /customer/orders           - Orders list
GET  /customer/rate/{id}        - Rate service form
POST /customer/rate/{id}        - Submit rating
GET  /customer/profile          - Profile page
POST /customer/profile          - Update profile
```

### Admin Routes
```
GET  /admin/dashboard           - Dashboard
GET  /admin/orders              - Orders list
POST /admin/orders/{id}/status  - Update order status
GET  /admin/staff               - Staff management
GET  /admin/payments            - Payments list
GET  /admin/financial-report    - Financial report
```

### Owner Routes
```
GET  /owner/payroll             - Payroll page
POST /owner/payroll             - Process salary
GET  /owner/salary-reports      - Salary reports
```

## 📁 Project Structure

```
cleanse-website/
├── app/
│   ├── controllers/
│   │   ├── AdminController.php
│   │   ├── CustomerController.php
│   │   └── OwnerController.php
│   ├── core/
│   │   ├── Auth.php
│   │   ├── BaseController.php
│   │   ├── Database.php
│   │   └── Router.php
│   ├── models/
│   │   ├── Order.php
│   │   ├── Payment.php
│   │   ├── Rating.php
│   │   ├── Service.php
│   │   ├── Staff.php
│   │   └── User.php
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── customer/
│       ├── errors/
│       ├── layouts/
│       └── owner/
├── config/
│   ├── constant.php
│   └── database.php
├── public/
│   ├── index.php
│   └── .htaccess
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── sql/
│   └── cleanse_db.sql
├── SETUP.md
└── README.md
```

## 🐛 Troubleshooting

### Database Connection Error
```
Check config/database.php:
- host: localhost
- dbname: cleanese_db
- username: root
- password: (empty)
```

### Session Already Active Error
- Ensured session_start() is only in public/index.php
- Other files don't call session_start()

### View Not Found Error
- Check view file exists in correct folder
- Verify file name matches exactly

### 404 Page Not Found
- Check .htaccess in public folder
- Enable RewriteEngine in Apache
- Clear browser cache

### Login/Password Not Working
- Verify user exists in database
- Password must be hashed with password_hash()
- Use password_verify() for verification

## 🔐 Security Features

- Password hashing dengan PHP's password_hash()
- Session management & timeout
- Role-based access control (RBAC)
- Input validation pada form
- CSRF protection (recommended to add)
- XSS prevention dengan htmlspecialchars()
- SQL injection prevention dengan prepared statements

## 📝 Development Tips

### Adding New Controller
```php
<?php
class NewController extends BaseController {
    public function method() {
        Auth::requireRole(ROLE_CUSTOMER);
        echo $this->view('view_name', ['data' => $data]);
    }
}
?>
```

### Adding New Route
```php
$router->add('/path/to/route', function($param = null) {
    $controller = new NewController();
    $controller->method($param);
});
```

### Adding New Model Method
```php
public static function findByCondition($condition) {
    $sql = "SELECT * FROM table WHERE column = ?";
    $stmt = Database::query($sql, [$condition]);
    $data = $stmt->fetch();
    return $data ? self::createFromData($data) : null;
}
```



## 📄 License

Proprietary - Cleanse Professional Services

## 👥 Credits

Developed with modern PHP practices and Bootstrap 5.3 framework.

---

**Version**: 1.0.0  
**Status**: Production Ready  
**Last Updated**: December 2025
