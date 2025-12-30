<?php
namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Base test class untuk semua unit tests
 */
abstract class TestCase extends PHPUnitTestCase {
    
    /**
     * Setup yang dijalankan sebelum setiap test
     */
    protected function setUp(): void {
        parent::setUp();
        
        // Clear session
        if (isset($_SESSION)) {
            session_unset();
        }
        
        // Reset auth state
        if (class_exists('Auth')) {
            // Auth::logout() will be called if needed in individual tests
        }
    }
    
    /**
     * Teardown yang dijalankan setelah setiap test
     */
    protected function tearDown(): void {
        parent::tearDown();
        
        // Clear session
        if (isset($_SESSION)) {
            session_unset();
        }
    }
    
    /**
     * Helper function untuk membuat mock database
     */
    protected function createMockDatabase() {
        return $this->createMock('Database');
    }
}
