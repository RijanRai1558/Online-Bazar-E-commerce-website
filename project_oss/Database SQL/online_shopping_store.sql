-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 11:22 AM
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
-- Database: `online_shopping_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `total_amount`, `image`) VALUES
(4, 4, 0.00, '', '2025-01-22 09:56:57', 1610.00, NULL),
(70, 6, 0.00, 'successful', '2025-02-20 12:06:22', 7300.00, NULL),
(71, 6, 0.00, 'pending', '2025-02-20 12:06:50', 0.00, NULL),
(73, 6, 0.00, 'pending', '2025-02-20 12:06:57', 4010.00, NULL),
(74, 6, 0.00, 'pending', '2025-02-20 12:07:04', 0.00, NULL),
(75, 6, 0.00, 'pending', '2025-02-20 12:07:06', 0.00, NULL),
(76, 6, 0.00, 'pending', '2025-02-20 12:07:53', 900.00, NULL),
(77, 6, 0.00, 'pending', '2025-02-20 12:07:56', 0.00, NULL),
(78, 6, 0.00, 'pending', '2025-02-20 12:08:17', 4310.00, NULL),
(79, 6, 0.00, 'pending', '2025-02-20 12:08:21', 0.00, NULL),
(80, 6, 0.00, 'pending', '2025-02-20 12:10:01', 4700.00, NULL),
(81, 6, 0.00, 'pending', '2025-02-20 12:11:07', 4310.00, NULL),
(82, 6, 0.00, 'pending', '2025-02-20 12:11:33', 3010.00, NULL),
(83, 1, 0.00, 'pending', '2025-02-21 07:00:01', 1000.00, NULL),
(84, 1, 0.00, 'pending', '2025-02-21 07:06:55', 1000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `image`) VALUES
(6, 4, 1, 1, 1010.00, '2025-01-22 09:56:57', NULL),
(7, 4, 2, 1, 200.00, '2025-01-22 09:56:57', NULL),
(8, 4, 4, 1, 400.00, '2025-01-22 09:56:57', NULL),
(46, 70, 5, 1, 0.00, '2025-02-20 12:06:22', 'combo.jpeg'),
(47, 70, 13, 1, 0.00, '2025-02-20 12:06:22', 'ipad-mini-7-colors.jpg'),
(48, 73, 1, 1, 0.00, '2025-02-20 12:06:57', 'MQTQ3-1000x1000.jpeg'),
(49, 73, 10, 1, 0.00, '2025-02-20 12:06:57', 'f123a3e6c6a165c4bdac69b4e4a576af.jpg'),
(50, 76, 9, 1, 0.00, '2025-02-20 12:07:53', 'ipcover.jpeg'),
(51, 78, 13, 1, 0.00, '2025-02-20 12:08:17', 'ipad-mini-7-colors.jpg'),
(52, 78, 1, 1, 0.00, '2025-02-20 12:08:17', 'MQTQ3-1000x1000.jpeg'),
(53, 80, 4, 1, 0.00, '2025-02-20 12:10:01', 'hoodie.jpeg'),
(54, 80, 7, 1, 0.00, '2025-02-20 12:10:01', '64081ea0-9d46-11ef-bfff-7ba57dd7df36.jpeg'),
(55, 81, 13, 1, 0.00, '2025-02-20 12:11:07', 'ipad-mini-7-colors.jpg'),
(56, 81, 1, 1, 0.00, '2025-02-20 12:11:07', 'MQTQ3-1000x1000.jpeg'),
(57, 82, 1, 1, 0.00, '2025-02-20 12:11:33', 'MQTQ3-1000x1000.jpeg'),
(58, 83, 2, 1, 0.00, '2025-02-21 07:00:01', 'black_forest.jpeg'),
(59, 83, 8, 1, 0.00, '2025-02-21 07:00:01', 'speaker.jpeg'),
(60, 84, 2, 1, 0.00, '2025-02-21 07:06:55', 'black_forest.jpeg'),
(61, 84, 8, 1, 0.00, '2025-02-21 07:06:55', 'speaker.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'Beats Studio Pro Wireless ', 'stock available now in different color', 3010.00, 'MQTQ3-1000x1000.jpeg'),
(2, 'Product 2', 'Description for product 2', 200.00, 'black_forest.jpeg'),
(4, 'Printed Hoodie', 'size M,XL,XXL', 4000.00, 'hoodie.jpeg'),
(5, 'Combo Set', 'High quality product.\r\nSize M,XL<XXL', 6000.00, 'combo.jpeg'),
(6, 'Bluetooth Earpods', 'Rockerz with ASAP Charge and upto 8 Hours Playback Bluetooth Headset (Charcoal Black, In the Ear).', 299.00, 'earpos.jpeg'),
(7, 'Apple 2024 MacBook Pro', 'Description for product 7', 700.00, '64081ea0-9d46-11ef-bfff-7ba57dd7df36.jpeg'),
(8, 'X2 USB Wired Computer Speakers', 'Description for product 8', 800.00, 'speaker.jpeg'),
(9, 'Apple iPhone 15 Pro', 'Description for product 9', 900.00, 'ipcover.jpeg'),
(10, ' Brown Half Sleeves T-Shirt ', 'Description for product 10', 1000.00, 'f123a3e6c6a165c4bdac69b4e4a576af.jpg'),
(11, 'Rucksack Trekking Bag ', 'Description for product 11', 1100.00, 'bag.jpeg'),
(12, 'Table Lamp', 'Description for product 12', 1299.00, 'Striker_Table_Lamp_1.jpeg'),
(13, 'iPad mini', 'Description for product 13', 1300.00, 'ipad-mini-7-colors.jpg'),
(16, 'PU Leather Bags', 'appllee', 1231.00, 'bag.jpg'),
(18, 'Haute Sunglasses', 'HAUTE SAUCE adds a style testament to every woman who wishes to circuit radiance and mirror functionality. Designed – not just for how they look, but how it makes you feel all day long.', 999.00, 'shades.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'Rijan Rai', '$2y$10$TabKsj5AHJ.2wNrWI2oZL.I4uuIifjvyYm6ILeHv7F360yirtm5Xa', 'rijanrai@gmail.com', 'user'),
(6, 'RijanR', 'rijan1234', '', 'admin'),
(7, 'Sabin Silwal', '$2y$10$nTn9Cr.7DyR32t.fpPsxm.22oVyJrIzzc3BFbeWjyGz28OJeyr.FS', 'sabinsilwal123@gmail.com', 'user'),
(8, 'Ranjit Rana', '$2y$10$VmgkM.6MsKb3/yNXtp2vWeMIoRrYVqex4K1CKbPRbxnaIlR7jU1TS', 'ranjir123@gmail.com', 'user'),
(9, '+-123', '$2y$10$2NT7zDAsWOwknDQtZEO6ues4rj8UOFqKv7DObrvlpz9.6tBt/K31W', '123@100.com', 'user'),
(10, 'a', '$2y$10$UcNkoufrupKZTDb9Sc7yT.Rw5jcUalOLZGJGkGX9bgWQi.pxz8fsm', 'rijaan@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
