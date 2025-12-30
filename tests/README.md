# Unit Tests - Cleanease Platform

Unit tests untuk mengverifikasi functionality dari core classes dan models.

## Setup

### 1. Install Dependencies

Jalankan command untuk install PHPUnit:

```bash
composer install
```

### 2. Run All Tests

```bash
vendor/bin/phpunit
```

### 3. Run Tests dari Folder Spesifik

```bash
vendor/bin/phpunit tests/Unit/AuthTest.php
vendor/bin/phpunit tests/Unit/UserTest.php
```

### 4. Run Tests dengan Verbose Output

```bash
vendor/bin/phpunit --verbose
```

### 5. Generate Code Coverage Report

```bash
vendor/bin/phpunit --coverage-html=coverage/
```

Coverage report akan tersimpan di folder `coverage/`.

## Test Structure

```
tests/
├── bootstrap.php          # Bootstrap file yang load semua dependencies
├── TestCase.php           # Base test class
└── Unit/
    ├── AuthTest.php       # Tests untuk Auth class
    └── UserTest.php       # Tests untuk User model
```

## Test Classes

### AuthTest.php

Tests untuk `App\Auth` class:

- `testCheckReturnsFalseWhenNotLoggedIn` - Memverifikasi bahwa check() mengembalikan false ketika user belum login
- `testUserReturnsNullWhenNotLoggedIn` - Memverifikasi bahwa user() mengembalikan null saat belum login
- `testIsCustomerReturnsFalseWhenNotLoggedIn` - Memverifikasi isCustomer() validation
- `testIsAdminReturnsFalseWhenNotLoggedIn` - Memverifikasi isAdmin() validation
- `testIsOwnerReturnsFalseWhenNotLoggedIn` - Memverifikasi isOwner() validation
- `testCheckSessionTimeout` - Memverifikasi session timeout functionality
- `testRoleConstantsAreDefined` - Memverifikasi role constants
- `testAuthHasRequiredMethods` - Memverifikasi Auth memiliki semua method yang diperlukan
- `testPasswordVerification` - Memverifikasi password hashing dan verification

### UserTest.php

Tests untuk `User` model:

- `testUserCanBeCreatedFromData` - Memverifikasi user object creation
- `testIsStaffReturnsTrueForAdminAndOwner` - Memverifikasi isStaff() untuk admin/owner
- `testIsStaffReturnsFalseForCustomer` - Memverifikasi isStaff() untuk customer
- `testUserHasRequiredProperties` - Memverifikasi user memiliki semua properties
- `testUserHasRequiredMethods` - Memverifikasi user memiliki semua methods
- `testEmailValidation` - Memverifikasi email validation
- `testInvalidEmailValidation` - Memverifikasi invalid email detection
- `testPasswordHashing` - Memverifikasi password hashing
- `testUserDataInitialization` - Memverifikasi user data initialization

## Menambah Tests Baru

Untuk menambah tests baru untuk class lain, ikuti template ini:

```php
<?php
namespace Tests\Unit;

use Tests\TestCase;

class YourClassTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        // Setup code sebelum setiap test
    }

    /**
     * Test description
     */
    public function testSomething(): void {
        // Arrange
        $input = 'example';
        
        // Act
        $result = yourFunction($input);
        
        // Assert
        $this->assertEquals('expected', $result);
    }
}
```

## Assertion Methods

PHPUnit menyediakan berbagai assertion methods:

```php
$this->assertEquals($expected, $actual);          // Sama dengan
$this->assertNotEquals($expected, $actual);       // Tidak sama
$this->assertTrue($condition);                     // True
$this->assertFalse($condition);                    // False
$this->assertNull($value);                         // Null
$this->assertNotNull($value);                      // Bukan null
$this->assertEmpty($value);                        // Empty
$this->assertNotEmpty($value);                     // Tidak empty
$this->assertArrayHasKey($key, $array);           // Array memiliki key
$this->assertInstanceOf(ClassName::class, $obj);  // Instance of class
$this->assertContains($needle, $haystack);        // Contain value
```

## Tips & Best Practices

1. **Gunakan Descriptive Test Names** - Nama test harus menjelaskan apa yang di-test
2. **One Assertion Per Test** - Idealnya satu assertion per test method
3. **AAA Pattern** - Arrange, Act, Assert
4. **Setup/Teardown** - Gunakan setUp() dan tearDown() untuk initialization dan cleanup
5. **Mock External Dependencies** - Mock database calls dan external services
6. **Test Edge Cases** - Test boundary conditions dan error cases

## Troubleshooting

### Command not found: vendor/bin/phpunit
Make sure you've run `composer install` first.

### Tests failing due to database connection
Tests tidak perlu database connection untuk tests sederhana. Untuk database-dependent tests, gunakan mocks atau test database terpisah.

### Session-related errors
Session sudah di-initialize di bootstrap.php, tapi pastikan tes tidak conflict dengan session state.

## Next Steps

Untuk coverage yang lebih komprehensif, tambahkan tests untuk:

- Model classes lainnya (Order, Service, Payment, etc.)
- Controller classes
- Database utility functions
- Router functionality
- Input validation

Gunakan mocking untuk database dan external dependencies agar tests menjadi unit tests yang sebenarnya.
