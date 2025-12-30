<?php
namespace Tests\Unit;

use Tests\TestCase;

/**
 * Unit tests untuk Auth class
 */
class AuthTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        
        // Clear any existing session data
        $_SESSION = [];
    }

    /**
     * Test bahwa Auth::check() mengembalikan false ketika user belum login
     */
    public function testCheckReturnsFalseWhenNotLoggedIn(): void {
        $this->assertFalse(\Auth::check());
    }

    /**
     * Test bahwa Auth::user() mengembalikan null ketika belum login
     */
    public function testUserReturnsNullWhenNotLoggedIn(): void {
        $this->assertNull(\Auth::user());
    }

    /**
     * Test isCustomer mengembalikan false ketika user tidak ada
     */
    public function testIsCustomerReturnsFalseWhenNotLoggedIn(): void {
        $this->assertFalse(\Auth::isCustomer());
    }

    /**
     * Test isAdmin mengembalikan false ketika user tidak ada
     */
    public function testIsAdminReturnsFalseWhenNotLoggedIn(): void {
        $this->assertFalse(\Auth::isAdmin());
    }

    /**
     * Test isOwner mengembalikan false ketika user tidak ada
     */
    public function testIsOwnerReturnsFalseWhenNotLoggedIn(): void {
        $this->assertFalse(\Auth::isOwner());
    }

    /**
     * Test session timeout checking exists
     */
    public function testCheckSessionTimeoutMethodExists(): void {
        $this->assertTrue(method_exists('Auth', 'checkSessionTimeout'));
    }

    /**
     * Test bahwa role constants terdefinisi
     */
    public function testRoleConstantsAreDefined(): void {
        $this->assertTrue(defined('ROLE_CUSTOMER'));
        $this->assertTrue(defined('ROLE_ADMIN'));
        $this->assertTrue(defined('ROLE_OWNER'));
        
        $this->assertEquals('customer', ROLE_CUSTOMER);
        $this->assertEquals('admin', ROLE_ADMIN);
        $this->assertEquals('owner', ROLE_OWNER);
    }

    /**
     * Test bahwa Auth class memiliki method yang diperlukan
     */
    public function testAuthHasRequiredMethods(): void {
        $this->assertTrue(method_exists('Auth', 'init'));
        $this->assertTrue(method_exists('Auth', 'login'));
        $this->assertTrue(method_exists('Auth', 'logout'));
        $this->assertTrue(method_exists('Auth', 'user'));
        $this->assertTrue(method_exists('Auth', 'check'));
        $this->assertTrue(method_exists('Auth', 'isAdmin'));
        $this->assertTrue(method_exists('Auth', 'isOwner'));
        $this->assertTrue(method_exists('Auth', 'isCustomer'));
        $this->assertTrue(method_exists('Auth', 'requireAuth'));
        $this->assertTrue(method_exists('Auth', 'requireRole'));
    }

    /**
     * Test bahwa password_verify dapat memverifikasi password
     */
    public function testPasswordVerification(): void {
        $password = 'testpassword123';
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hashed));
        $this->assertFalse(password_verify('wrongpassword', $hashed));
    }

    /**
     * Test session constants are defined
     */
    public function testSessionConstantsDefined(): void {
        $this->assertTrue(defined('SESSION_TIMEOUT'));
        $this->assertIsInt(SESSION_TIMEOUT);
    }
}
