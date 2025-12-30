# Creating Test Admin User

## Option 1: Direct Database Insert

Open phpMyAdmin and run this SQL query:

```sql
-- Hash password "admin123" using password_hash()
-- You can use PHP to generate it or use an online tool

-- Default hash for "admin123":
-- $2y$10$YIjlrHxYlBpyVCHNSe.UKuX3aJ8aE7K8WTf9qZ7mK8xJ5s4dK8Yte

INSERT INTO users (username, email, password, full_name, phone, role_id, is_active) 
VALUES ('admin', 'admin@cleanse.com', '$2y$10$YIjlrHxYlBpyVCHNSe.UKuX3aJ8aE7K8WTf9qZ7mK8xJ5s4dK8Yte', 'Administrator', '123-456-7890', 2, TRUE);
```

## Option 2: Using PHP Script

Create a temporary file `create_admin.php` in `public/` folder:

```php
<?php
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hashed_password;
?>
```

Then run it at `http://localhost/cleanse-website/public/create_admin.php`

Copy the hashed password and use it in the SQL query above.

## Option 3: Register & Promote to Admin

1. Register a normal customer account
2. Find the user ID in the database
3. Run this SQL:

```sql
UPDATE users SET role_id = 2 WHERE id = YOUR_USER_ID;
```

## Test Login Credentials

After creating admin user:

```
Email: admin@cleanse.com
Password: admin123
```

## Test Customer Account

Register via the register page at: `http://localhost/cleanse-website/auth/register`

## Owner Account

```sql
-- Create owner user (role_id = 3)
INSERT INTO users (username, email, password, full_name, phone, role_id, is_active) 
VALUES ('owner', 'owner@cleanse.com', '$2y$10$...hash...', 'Owner', '987-654-3210', 3, TRUE);
```

Then login with owner credentials to access owner features.

---

**Note**: Always change default passwords in production!
