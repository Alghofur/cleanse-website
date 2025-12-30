-- Database: cleanse_db
CREATE DATABASE IF NOT EXISTS cleanse_db;
USE cleanse_db;

-- Roles table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (role_name) VALUES ('customer'), ('admin'), ('owner');

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    profile_image VARCHAR(255) DEFAULT 'default.jpg',
    role_id INT DEFAULT 1,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Services table
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price_per_hour DECIMAL(10,2) NOT NULL,
    duration_hours INT DEFAULT 2,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample services
INSERT INTO services (name, description, price_per_hour, duration_hours) VALUES
('Regular Home Cleaning', 'Complete home cleaning including living room, bedrooms, kitchen, and bathrooms', 25.00, 4),
('Deep Cleaning', 'Intensive cleaning with attention to details, corners, and hard-to-reach areas', 35.00, 6),
('Office Cleaning', 'Commercial cleaning for offices and workspaces', 30.00, 5),
('Carpet Cleaning', 'Specialized carpet and upholstery cleaning', 40.00, 3),
('Window Cleaning', 'Interior and exterior window cleaning', 20.00, 2);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    service_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    schedule_date DATE NOT NULL,
    schedule_time TIME NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10),
    special_instructions TEXT,
    status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(10,2) NOT NULL,
    staff_id INT,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (staff_id) REFERENCES users(id)
);

-- Payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'e_wallet') DEFAULT 'bank_transfer',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    transaction_id VARCHAR(100),
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Ratings table
CREATE TABLE ratings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review TEXT,
    staff_rating INT CHECK (staff_rating BETWEEN 1 AND 5),
    staff_review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (customer_id) REFERENCES users(id),
    UNIQUE KEY unique_order_rating (order_id)
);

-- Staff availability table
CREATE TABLE staff_availability (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    available_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    assigned_order_id INT,
    FOREIGN KEY (staff_id) REFERENCES users(id),
    FOREIGN KEY (assigned_order_id) REFERENCES orders(id)
);

-- Staff salary table
CREATE TABLE staff_salary (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    base_salary DECIMAL(10,2) NOT NULL,
    bonus DECIMAL(10,2) DEFAULT 0.00,
    deductions DECIMAL(10,2) DEFAULT 0.00,
    net_salary DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    payment_method VARCHAR(50),
    notes TEXT,
    paid_by INT,
    FOREIGN KEY (staff_id) REFERENCES users(id),
    FOREIGN KEY (paid_by) REFERENCES users(id)
);

-- Admin salary table
CREATE TABLE admin_salary (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    base_salary DECIMAL(10,2) NOT NULL,
    bonus DECIMAL(10,2) DEFAULT 0.00,
    deductions DECIMAL(10,2) DEFAULT 0.00,
    net_salary DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    paid_by INT,
    FOREIGN KEY (admin_id) REFERENCES users(id),
    FOREIGN KEY (paid_by) REFERENCES users(id)
);

-- Financial records
CREATE TABLE financial_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    record_date DATE NOT NULL,
    income DECIMAL(10,2) DEFAULT 0.00,
    expense DECIMAL(10,2) DEFAULT 0.00,
    description VARCHAR(255),
    category ENUM('service_income', 'salary_expense', 'equipment_expense', 'other'),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Create indexes for better performance
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_payments_order ON payments(order_id);
CREATE INDEX idx_ratings_order ON ratings(order_id);
CREATE INDEX idx_staff_availability ON staff_availability(staff_id, available_date);

-- Insert sample admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role_id) VALUES
('admin', 'admin@cleanse.com', '$2y$10$YourHashedPasswordHere', 'Admin Cleanse', 2);

-- Insert sample owner user (password: owner123)
INSERT INTO users (username, email, password, full_name, role_id) VALUES
('owner', 'owner@cleanse.com', '$2y$10$YourHashedPasswordHere', 'Owner Cleanse', 3);