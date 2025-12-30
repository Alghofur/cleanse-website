# Cleanse - Professional Cleaning Services Website

Platform booking service cleaning profesional dengan fitur lengkap untuk customer, admin, dan owner.

## Struktur Folder

```
cleanse-website/
├── app/
│   ├── controllers/        # Logic untuk handling requests
│   ├── core/              # Core classes (Auth, Database, Router, BaseController)
│   ├── models/            # Data models
│   └── views/             # View templates
├── config/                # Configuration files
├── public/                # Public folder (entry point)
├── sql/                   # Database schema
├── assets/                # CSS, JS, images
└── vendor/                # Composer dependencies
```

## Instalasi & Setup

### 1. Database Setup

1. Buka phpMyAdmin di `http://localhost/phpmyadmin`
2. Buat database baru bernama `cleanese_db`
3. Import file `sql/cleanse_db.sql` ke database tersebut

Atau gunakan command line:
```bash
mysql -u root < sql/cleanse_db.sql
```

### 2. Konfigurasi Database

Edit file `config/database.php`:
```php
<?php
return [
    'host' => 'localhost',
    'dbname' => 'cleanese_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

### 3. Akses Website

- **Website**: `http://localhost/cleanse-website/`
- **Login Customer**: Register dulu di halaman register
- **Login Admin**: Setup admin user di database terlebih dahulu

## Fitur Aplikasi

### Customer
- ✅ Register dan Login
- ✅ Lihat services
- ✅ Book service
- ✅ Bayar order
- ✅ Rate service
- ✅ Lihat order history
- ✅ Update profile

### Admin
- ✅ Dashboard dengan statistik
- ✅ Manage orders
- ✅ Manage staff availability
- ✅ Lihat payments
- ✅ Financial reports

### Owner
- ✅ Semua fitur admin
- ✅ Process payroll
- ✅ Salary reports
- ✅ Financial analysis

## User Roles

| Role | Default | Description |
|------|---------|-------------|
| customer | 1 | Pengguna yang membeli service |
| admin | 2 | Admin yang kelola orders & staff |
| owner | 3 | Owner yang kelola bisnis & payroll |

## Database Tables

- `users` - User accounts
- `roles` - User roles
- `services` - Available services
- `orders` - Customer orders
- `payments` - Payment records
- `ratings` - Service ratings
- `staff_availability` - Staff availability
- `staff_salary` - Staff salary records
- `admin_salary` - Admin salary records

## API Routes

### Authentication
- `POST /auth/login` - Login
- `POST /auth/register` - Register
- `GET /auth/logout` - Logout

### Customer Routes
- `GET /customer/dashboard` - Dashboard
- `GET /customer/services` - View services
- `GET /customer/book` - Book service form
- `POST /customer/book` - Submit booking
- `GET /customer/payment/{id}` - Payment page
- `POST /customer/payment/{id}` - Process payment
- `GET /customer/orders` - View orders
- `GET /customer/rate/{id}` - Rate service
- `GET /customer/profile` - User profile
- `POST /customer/profile` - Update profile

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/orders` - View all orders
- `POST /admin/orders/{id}/status` - Update order status
- `GET /admin/staff` - Staff management
- `GET /admin/payments` - View payments
- `GET /admin/financial-report` - Financial report

### Owner Routes
- `GET /owner/payroll` - Payroll management
- `POST /owner/payroll` - Process salary
- `GET /owner/salary-reports` - Salary reports

## File Perbaikan

Berikut file yang sudah diperbaiki:

1. **Database.php** - Fixed typo `p assword` → `password`
2. **Auth.php** - Removed duplicate `session_start()`, fixed logout redirect
3. **BaseController.php** - Changed `view()` method from protected to public
4. **CustomerController.php** - Added missing `services()` method
5. **index.php** - Fixed file name constant `constants.php` → `constant.php`, fixed view() calls
6. **Login & Register Views** - Completed HTML structure
7. **All Customer Views** - Created and styled
8. **All Admin Views** - Created and styled
9. **Owner Views** - Created
10. **404 Error Page** - Created

## Testing

### Create Test Admin User

```sql
-- First, hash a password (use password_hash() in PHP)
-- Or use this simple password: admin123

INSERT INTO users (username, email, password, full_name, phone, role_id) VALUES 
('admin', 'admin@cleanse.com', '$2y$10$...', 'Administrator', '1234567890', 2);
```

### Sample Login Credentials

```
Email: admin@cleanse.com
Password: admin123
```

## Troubleshooting

### "Session already active" Error
- Pastikan `session_start()` hanya dipanggil di `public/index.php`
- File lain tidak boleh memanggil `session_start()`

### "View not found" Error
- Periksa nama file view di folder yang benar
- Pastikan semua view file sudah dibuat

### Database Connection Error
- Periksa koneksi database di `config/database.php`
- Pastikan database dan tabel sudah dibuat
- Verifikasi username dan password

### 404 Page Not Found
- Periksa `.htaccess` file di public folder
- Pastikan RewriteEngine enabled di Apache
- Verify APP_URL di config/constant.php

## Dependencies

- PHP 7.4+
- MySQL 5.7+
- Apache dengan mod_rewrite

## Development

### Adding New Model

```php
<?php
class NewModel {
    public static function find($id) {
        $sql = "SELECT * FROM new_models WHERE id = ?";
        $data = Database::fetch($sql, [$id]);
        if ($data) {
            return self::createFromData($data);
        }
        return null;
    }
    
    private static function createFromData($data) {
        $model = new self();
        foreach ($data as $key => $value) {
            if (property_exists($model, $key)) {
                $model->$key = $value;
            }
        }
        return $model;
    }
}
```

### Adding New Route

```php
// In public/index.php
$router->add('/path/to/route', function($param = null) {
    // Handler code
    $controller = new MyController();
    $controller->method($param);
});
```

## Support

Untuk bantuan lebih lanjut, hubungi:
- Email: support@cleanse.com
- Phone: +1-800-CLEANSE

---

**Version**: 1.0.0  
**Last Updated**: December 2025
