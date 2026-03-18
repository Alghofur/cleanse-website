-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2026 at 04:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cleanse_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_salary`
--

CREATE TABLE `admin_salary` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `paid_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_salary`
--

INSERT INTO `admin_salary` (`id`, `admin_id`, `base_salary`, `bonus`, `deductions`, `net_salary`, `payment_date`, `payment_status`, `paid_by`) VALUES
(1, 1, 0.00, 0.00, 0.00, 0.00, '2025-12-31', 'paid', 2),
(2, 1, 100.00, 0.00, 0.00, 100.00, '2025-12-31', 'paid', 2),
(3, 1, 200.00, 0.00, 0.00, 200.00, '2025-12-31', 'paid', 2),
(4, 1, 2000.00, 0.00, 0.00, 2000.00, '2026-01-05', 'paid', 2);

-- --------------------------------------------------------

--
-- Table structure for table `financial_records`
--

CREATE TABLE `financial_records` (
  `id` int(11) NOT NULL,
  `record_date` date NOT NULL,
  `income` decimal(10,2) DEFAULT 0.00,
  `expense` decimal(10,2) DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `category` enum('service_income','salary_expense','equipment_expense','other') DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `schedule_date` date NOT NULL,
  `schedule_time` time NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `service_id`, `order_date`, `schedule_date`, `schedule_time`, `address`, `city`, `postal_code`, `special_instructions`, `status`, `total_price`, `staff_id`, `completed_at`) VALUES
(1, 'ORD-20251223-6949F5F', 3, 5, '2025-12-23 01:52:57', '2025-12-24', '09:52:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', 'bersihkan secara menyeluruh', 'completed', 40.00, NULL, '2025-12-23 07:37:55'),
(2, 'ORD-20251223-6949FF6', 3, 1, '2025-12-23 02:33:15', '2025-12-24', '10:32:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', 'bersihkan ac', 'completed', 100.00, NULL, '2025-12-23 07:37:50'),
(3, 'ORD-20251223-694A2D5', 3, 1, '2025-12-23 05:49:09', '2025-12-24', '12:51:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', '--', 'completed', 100.00, NULL, '2025-12-23 05:58:40'),
(4, 'ORD-20251231-6954D7B', 4, 4, '2025-12-31 07:58:42', '2025-12-31', '14:58:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', '-', 'completed', 120.00, NULL, '2025-12-31 08:00:30'),
(5, 'ORD-20251231-6954D7D', 4, 2, '2025-12-31 07:59:15', '2026-01-01', '16:00:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', 'dsa', 'completed', 210.00, NULL, '2025-12-31 08:00:27'),
(6, 'ORD-20251231-695513E', 3, 2, '2025-12-31 12:15:43', '2025-12-31', '23:15:00', 'Jl. K.H Samanhudi No.84-86, Purwosari, Kec. Laweyan', 'Surakarta', '57149', '22', 'completed', 210.00, NULL, '2026-01-05 14:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','credit_card','e_wallet') DEFAULT 'bank_transfer',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount`, `payment_method`, `payment_status`, `payment_date`, `transaction_id`, `notes`) VALUES
(1, 1, 40.00, 'credit_card', 'paid', '2025-12-31 07:25:20', '', NULL),
(2, 2, 100.00, 'credit_card', 'paid', '2025-12-31 07:25:20', '', NULL),
(3, 3, 100.00, 'bank_transfer', 'paid', '2025-12-23 05:49:13', '', NULL),
(4, 4, 120.00, 'credit_card', 'paid', '2025-12-31 07:58:43', '', NULL),
(5, 5, 210.00, '', 'paid', '2025-12-31 07:59:20', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `staff_rating` int(11) DEFAULT NULL CHECK (`staff_rating` between 1 and 5),
  `staff_review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `order_id`, `customer_id`, `rating`, `review`, `staff_rating`, `staff_review`, `created_at`) VALUES
(1, 2, 3, 5, 'nice service', 4, 'kurang bersih sedikit', '2025-12-23 05:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`) VALUES
(1, 'customer', '2025-12-23 00:35:29'),
(2, 'admin', '2025-12-23 00:35:29'),
(3, 'owner', '2025-12-23 00:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `duration_hours` int(11) DEFAULT 2,
  `image_url` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price_per_hour`, `duration_hours`, `image_url`, `is_available`, `created_at`) VALUES
(1, 'Regular Home Cleaning', 'Complete home cleaning including living room, bedrooms, kitchen, and bathrooms', 25.00, 4, NULL, 1, '2025-12-23 00:35:29'),
(2, 'Deep Cleaning', 'Intensive cleaning with attention to details, corners, and hard-to-reach areas', 35.00, 6, NULL, 1, '2025-12-23 00:35:29'),
(3, 'Office Cleaning', 'Commercial cleaning for offices and workspaces', 30.00, 5, NULL, 1, '2025-12-23 00:35:29'),
(4, 'Carpet Cleaning', 'Specialized carpet and upholstery cleaning', 40.00, 3, NULL, 1, '2025-12-23 00:35:29'),
(5, 'Window Cleaning', 'Interior and exterior window cleaning', 20.00, 2, NULL, 1, '2025-12-23 00:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `staff_availability`
--

CREATE TABLE `staff_availability` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `assigned_order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_salary`
--

CREATE TABLE `staff_salary` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `deductions` decimal(10,2) DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_salary`
--

INSERT INTO `staff_salary` (`id`, `staff_id`, `base_salary`, `bonus`, `deductions`, `net_salary`, `payment_date`, `payment_status`, `payment_method`, `notes`, `paid_by`) VALUES
(1, 1, 2000.00, 0.00, 0.00, 2000.00, '2025-12-31', 'paid', 'bank_transfer', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default.jpg',
  `role_id` int(11) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `profile_image`, `role_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@cleanse.com', '$2y$10$jTKzqUg2L30k4oOTN2XVz.9q8cFWNkkLRWdkpfKuZvX3Yx7KVt0wC', 'Admin Cleanse', NULL, 'default.jpg', 2, 1, '2025-12-23 00:35:30', '2025-12-23 00:59:29'),
(2, 'owner', 'owner@cleanse.com', '$2y$10$Et3blrabwp09MrMT9n8lYOdILUHJpmrZ18VoYV5.JnBj5peRccNea', 'Owner Cleanse', NULL, 'default.jpg', 3, 1, '2025-12-23 00:35:30', '2025-12-23 00:59:29'),
(3, 'user', 'user@gmail.com', '$2y$10$xCw20rYLlDTO04cgGT4lm.08JculdbBEReer7Xt30fV09g5QYcQkq', 'user', '+62271716500', 'default.jpg', 1, 1, '2025-12-23 01:51:02', '2025-12-23 01:51:02'),
(4, 'user2', 'user2@gmail.com', '$2y$10$qCZ2f2M/4mvZVspruff8HeREZjWxh.et/dAEGtTM..YHS/Io/nR.6', 'user2', '+62271716500', 'default.jpg', 1, 1, '2025-12-31 07:58:20', '2025-12-31 07:58:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_salary`
--
ALTER TABLE `admin_salary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `paid_by` (`paid_by`);

--
-- Indexes for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payments_order` (`order_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order_rating` (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_ratings_order` (`order_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_availability`
--
ALTER TABLE `staff_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_order_id` (`assigned_order_id`),
  ADD KEY `idx_staff_availability` (`staff_id`,`available_date`);

--
-- Indexes for table `staff_salary`
--
ALTER TABLE `staff_salary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `paid_by` (`paid_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_salary`
--
ALTER TABLE `admin_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_availability`
--
ALTER TABLE `staff_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_salary`
--
ALTER TABLE `staff_salary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_salary`
--
ALTER TABLE `admin_salary`
  ADD CONSTRAINT `admin_salary_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `admin_salary_ibfk_2` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `staff_availability`
--
ALTER TABLE `staff_availability`
  ADD CONSTRAINT `staff_availability_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `staff_availability_ibfk_2` FOREIGN KEY (`assigned_order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `staff_salary`
--
ALTER TABLE `staff_salary`
  ADD CONSTRAINT `staff_salary_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `staff_salary_ibfk_2` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
