<?php
namespace Tests\Unit;

use Tests\TestCase;

/**
 * Unit tests untuk User model
 */
class UserTest extends TestCase {
    
    /**
     * Test bahwa User object dapat dibuat dengan data
     */
    public function testUserCanBeCreatedFromData(): void {
        $data = [
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'full_name' => 'Test User',
            'phone' => '081234567890',
            'role' => ROLE_CUSTOMER,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $user = \User::createFromData($data);
        
        $this->assertInstanceOf(\User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('Test User', $user->full_name);
        $this->assertEquals(ROLE_CUSTOMER, $user->role);
    }

    /**
     * Test bahwa User::isStaff() mengembalikan true untuk admin dan owner
     */
    public function testIsStaffReturnsTrueForAdminAndOwner(): void {
        $adminData = [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => ROLE_ADMIN
        ];
        
        $ownerData = [
            'id' => 2,
            'username' => 'owner',
            'email' => 'owner@example.com',
            'role' => ROLE_OWNER
        ];
        
        $admin = \User::createFromData($adminData);
        $owner = \User::createFromData($ownerData);
        
        $this->assertTrue($admin->isStaff());
        $this->assertTrue($owner->isStaff());
    }

    /**
     * Test bahwa User::isStaff() mengembalikan false untuk customer
     */
    public function testIsStaffReturnsFalseForCustomer(): void {
        $customerData = [
            'id' => 3,
            'username' => 'customer',
            'email' => 'customer@example.com',
            'role' => ROLE_CUSTOMER
        ];
        
        $customer = \User::createFromData($customerData);
        
        $this->assertFalse($customer->isStaff());
    }

    /**
     * Test bahwa User object memiliki properti yang diperlukan
     */
    public function testUserHasRequiredProperties(): void {
        $user = new \User();
        
        $this->assertTrue(property_exists($user, 'id'));
        $this->assertTrue(property_exists($user, 'username'));
        $this->assertTrue(property_exists($user, 'email'));
        $this->assertTrue(property_exists($user, 'password'));
        $this->assertTrue(property_exists($user, 'full_name'));
        $this->assertTrue(property_exists($user, 'phone'));
        $this->assertTrue(property_exists($user, 'role'));
        $this->assertTrue(property_exists($user, 'is_active'));
        $this->assertTrue(property_exists($user, 'created_at'));
        $this->assertTrue(property_exists($user, 'updated_at'));
    }

    /**
     * Test bahwa User class memiliki method yang diperlukan
     */
    public function testUserHasRequiredMethods(): void {
        $this->assertTrue(method_exists('User', 'find'));
        $this->assertTrue(method_exists('User', 'findByEmail'));
        $this->assertTrue(method_exists('User', 'create'));
        $this->assertTrue(method_exists('User', 'update'));
        $this->assertTrue(method_exists('User', 'delete'));
        $this->assertTrue(method_exists('User', 'all'));
        $this->assertTrue(method_exists('User', 'countByRole'));
        $this->assertTrue(method_exists('User', 'createFromData'));
        $this->assertTrue(method_exists('User', 'getOrders'));
        $this->assertTrue(method_exists('User', 'isStaff'));
    }

    /**
     * Test email validation
     */
    public function testEmailValidation(): void {
        $validEmails = [
            'test@example.com',
            'user.name@example.co.uk',
            'test+tag@example.com'
        ];
        
        foreach ($validEmails as $email) {
            $this->assertTrue(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
        }
    }

    /**
     * Test invalid email validation
     */
    public function testInvalidEmailValidation(): void {
        $invalidEmails = [
            'notanemail',
            'test@',
            '@example.com',
            'test@.com'
        ];
        
        foreach ($invalidEmails as $email) {
            $this->assertFalse(filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
        }
    }

    /**
     * Test password hashing
     */
    public function testPasswordHashing(): void {
        $password = 'MySecurePassword123!';
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertNotEquals($password, $hashed);
        $this->assertTrue(password_verify($password, $hashed));
        $this->assertFalse(password_verify('WrongPassword', $hashed));
    }

    /**
     * Test user data initialization
     */
    public function testUserDataInitialization(): void {
        $userData = [
            'id' => 123,
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'full_name' => 'John Doe',
            'phone' => '081234567890',
            'role' => ROLE_CUSTOMER,
            'is_active' => 1
        ];
        
        $user = \User::createFromData($userData);
        
        $this->assertEquals($userData['id'], $user->id);
        $this->assertEquals($userData['username'], $user->username);
        $this->assertEquals($userData['email'], $user->email);
        $this->assertEquals($userData['full_name'], $user->full_name);
        $this->assertEquals($userData['phone'], $user->phone);
        $this->assertEquals($userData['role'], $user->role);
        $this->assertEquals($userData['is_active'], $user->is_active);
    }

    /**
     * Test user object instantiation
     */
    public function testUserInstantiation(): void {
        $user = new \User();
        
        $this->assertIsObject($user);
        $this->assertInstanceOf(\User::class, $user);
    }

    /**
     * Test setting user properties
     */
    public function testSettingUserProperties(): void {
        $user = new \User();
        
        $user->id = 5;
        $user->username = 'testuser';
        $user->email = 'test@example.com';
        
        $this->assertEquals(5, $user->id);
        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('test@example.com', $user->email);
    }
}
