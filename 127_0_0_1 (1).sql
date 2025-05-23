-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 12:28 PM
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
-- Database: `home_marketplace`
--
CREATE DATABASE IF NOT EXISTS `home_marketplace` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `home_marketplace`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `role` enum('buyer','seller') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `expected_delivery` date DEFAULT NULL,
  `payment_method` enum('Razorpay','COD') NOT NULL DEFAULT 'COD',
  `payment_id` varchar(255) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `seller_id`, `buyer_id`, `quantity`, `total_amount`, `address`, `order_status`, `order_date`, `expected_delivery`, `payment_method`, `payment_id`, `contact_number`) VALUES
(29, 28, 16, 15, 1, 632.70, 'Ram Mandir jamkhandi, jamkhandi - 587301', 'Pending', '2025-05-23 10:18:36', '2025-05-28', 'Razorpay', 'pay_QYImfgBrO61vX9', '4567894312'),
(30, 55, 19, 15, 1, 380.00, 'buvi galli, jamakhandi - 587301', 'Shipped', '2025-05-23 12:23:08', '2025-05-24', 'Razorpay', 'pay_QYKtqBaAFX9GRx', '8088192805');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discount` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `description`, `price`, `category`, `image`, `created_at`, `discount`) VALUES
(19, 16, 'sweat shirt', 'winter wear | sweat shirt for unisex', 549.00, 'Clothing', 'sweat shirt.avif', '2025-05-23 07:52:16', 10),
(20, 16, 'gym wear', 'gym wear  for mens', 299.00, 'Clothing', 'gym wear.webp', '2025-05-23 07:53:35', 5),
(21, 16, 'men\'s kurtas', 'traditional kurtas for men', 999.00, 'Clothing', 'mens kurtas.webp', '2025-05-23 07:54:36', 10),
(22, 16, 'collared t shirt', 'collared t shirt for men line pattern', 499.00, 'Clothing', 'line t shirt.webp', '2025-05-23 07:56:10', 0),
(23, 16, 'trendy gym vests', 'trending gym wear', 299.00, 'Clothing', 'trendy gym vests.webp', '2025-05-23 07:57:09', 5),
(24, 16, 'classic vests', 'classic vests for men', 399.00, 'Clothing', 'classic vests.webp', '2025-05-23 07:57:51', 5),
(25, 16, 'mens t shirts', 'men t shirts', 999.00, 'Clothing', 'men tshirts.webp', '2025-05-23 07:59:25', 5),
(26, 16, 'women frocks', 'one piece for womens', 299.00, 'Clothing', 'women frocks.webp', '2025-05-23 08:00:34', 2),
(27, 16, 'one piece  dresses for womens', 'one piece', 799.00, 'Clothing', 'trendy 1 piece.webp', '2025-05-23 08:01:33', 5),
(28, 16, 'stylish frocks', 'stylish frock for womens', 666.00, 'Clothing', 'stylish frock.webp', '2025-05-23 08:02:43', 5),
(29, 16, 'women  skirts', 'women skirts', 800.00, 'Clothing', 'fashon skirts.webp', '2025-05-23 08:03:50', 5),
(30, 16, 'jeans skirts', 'jeans skirts', 799.00, 'Clothing', 'jeans skirts.webp', '2025-05-23 08:04:43', 5),
(31, 16, 'designer kurties', 'designer kurties for women', 1000.00, 'Clothing', 'designer kurtis.webp', '2025-05-23 08:05:57', 10),
(32, 17, 'Silver Plated Necklace Set With White American Diamond', 'Silver Plated Necklace Set With White American Diamond', 450.00, 'Jewelry', 'Silver Plated Necklace Set With White American Diamond.webp', '2025-05-23 08:12:41', 5),
(33, 17, 'Gold Plated Choker Set with Lct Austrian diamond', 'Gold Plated Choker Set with Lct Austrian diamond', 350.00, 'Jewelry', 'Gold Plated Choker Set with Lct Austrian diamond.webp', '2025-05-23 08:14:06', 7),
(34, 17, 'Diva Fancy Diamond Gold-Plated Finish Jewellery Set', 'Diva Fancy Diamond Gold-Plated Finish Jewellery Set', 250.00, 'Jewelry', 'Diva Fancy Diamond Gold-Plated Finish Jewellery Set.webp', '2025-05-23 08:37:51', 5),
(35, 17, 'Diva Fusion Jewellery Sets', 'Diva Fusion Jewellery Sets', 300.00, 'Jewelry', 'Diva Fusion Jewellery Sets.webp', '2025-05-23 08:40:38', 10),
(36, 17, 'Chunky Silver American Diamond Free Size Rings', 'Chunky Silver American Diamond Free Size Rings', 220.00, 'Jewelry', 'Chunky Silver American Diamond Free Size Rings.webp', '2025-05-23 08:43:06', 7),
(37, 17, 'Vassaley Rotating Cubic Zirconia Studded Adjustable Silver-Plated ring', 'Vassaley Rotating Cubic Zirconia Studded Adjustable Silver-Plated Flower Ring For Women And Girls', 150.00, 'Jewelry', 'Vassaley Rotating Cubic Zirconia Studded.webp', '2025-05-23 08:45:19', 5),
(38, 17, 'R. Silver Adjustable Ring', 'R. Silver Adjustable Ring', 200.00, 'Jewelry', 'R. Silver Adjustable Ring.webp', '2025-05-23 08:46:17', 10),
(39, 17, 'Jewellery for woman', 'Jewellery for woman', 320.00, 'Jewelry', 'Jewellery for woman.webp', '2025-05-23 08:47:33', 10),
(40, 17, 'Stylish Chooda Bangles Set with Jhumki in Black Color', 'Stylish Chooda Bangles Set with Jhumki in Black Color', 400.00, 'Jewelry', 'Stylish Chooda Bangles Set with Jhumki in Black Color.webp', '2025-05-23 08:50:48', 10),
(41, 17, 'Stylish Bangles Set', 'Stylish Bangles Set', 400.00, 'Jewelry', 'Stylish Bangles Set.webp', '2025-05-23 08:51:58', 10),
(42, 17, 'Stylus Bangles Set-Yellow-10', 'Stylus Bangles Set-Yellow-10', 400.00, 'Jewelry', 'Stylus Bangles Set-Yellow-10.webp', '2025-05-23 08:53:44', 10),
(43, 18, 'Chocolate Truffle Delicious Cake Half Kg', 'Chocolate Truffle Delicious Cake Half Kg', 450.00, 'Cakes', 'Chocolate Truffle Delicious Cake Half Kg.webp', '2025-05-23 08:59:40', 5),
(44, 18, 'Fruit Overload Cake Half Kg', 'Fruit Overload Cake Half Kg', 500.00, 'Cakes', 'Fruit Overload Cake Half Kg.webp', '2025-05-23 09:01:05', 5),
(45, 18, 'Butterscotch Bento Cake 250gm', 'Butterscotch Bento Cake 250gm', 450.00, 'Cakes', 'Butterscotch Bento Cake 250gm.webp', '2025-05-23 09:02:53', 10),
(46, 18, 'Rose Paradise Chocolate Cake Half Kg', 'Rose Paradise Chocolate Cake Half Kg', 550.00, 'Cakes', 'rose-paradise-chocolate-cake-half-kg_2.webp', '2025-05-23 09:04:40', 10),
(47, 18, 'Dates & Walnuts Mixed Dry Cake 500gms', 'Dates & Walnuts Mixed Dry Cake 500gms', 650.00, 'Cakes', 'dates-walnuts-mixed-dry-cake-500gms.webp', '2025-05-23 09:05:48', 5),
(48, 18, 'Golden Delight Rasmalai Cake', 'Golden Delight Rasmalai Cake', 500.00, 'Cakes', 'golden-delight-rasmalai-cake.webp', '2025-05-23 09:09:05', 5),
(49, 18, 'Creamy Mango Delight Cake', 'Creamy Mango Delight Cake', 500.00, 'Cakes', 'mango-mania-cream-cake-eggless_.webp', '2025-05-23 09:10:16', 5),
(50, 18, 'Caramel Lotus Dreamscape', 'Caramel Lotus Dreamscape', 600.00, 'Cakes', 'caramel-lotus-dreamscape_1.webp', '2025-05-23 09:11:12', 10),
(51, 18, 'Chocolate Rouge Reverie', 'Chocolate Rouge Reverie', 650.00, 'Cakes', 'chocolate-rouge-reverie_1.webp', '2025-05-23 09:12:19', 5),
(52, 18, 'Black Forest Bento Cake 250 Gram', 'Black Forest Bento Cake 250 Gram', 600.00, 'Cakes', 'black-forest-bento-cake_1.webp', '2025-05-23 09:13:59', 10),
(53, 18, 'Nutella Royale Cheesecake', 'Nutella Royale Cheesecake', 500.00, 'Cakes', 'nutella-royale-cheesecake_1.webp', '2025-05-23 09:15:39', 5),
(54, 18, 'Chocolate Trio Cream Cake- Half Kg', 'Chocolate Trio Cream Cake- Half Kg', 600.00, 'Cakes', 'chocolate-trio-cream-cake-half-kg_1.webp', '2025-05-23 09:18:15', 5),
(55, 19, 'Analog Square Dial Watch', 'Analog Square Dial Watch', 400.00, 'Accessories', 'Analog Square Dial Watch.webp', '2025-05-23 09:25:15', 5),
(56, 19, 'Midnight Blue Dial & Silicon Mesh Strap with Water Resistant', 'Midnight Blue Dial & Silicon Mesh Strap with Water Resistant', 600.00, 'Accessories', 'Midnight Blue Dial & Silicon Mesh Strap with Water Resistant.webp', '2025-05-23 09:27:44', 10),
(57, 19, 'NEW LOOK-SUN \\PACK OF 1', 'NEW LOOK-SUN \\PACK OF 1', 999.00, 'Accessories', 'PACK OF 1.webp', '2025-05-23 09:30:14', 10),
(58, 19, 'Unisex Silver Metal Oval Sunglasses', 'Unisex Silver Metal Oval Sunglasses', 600.00, 'Accessories', 'Unisex Silver Metal Oval Sunglasses.webp', '2025-05-23 09:31:30', 10),
(59, 19, 'Shehriz Eyewear Men Gold Metal Rectangular', 'Shehriz Eyewear Men Gold Metal Rectangular', 600.00, 'Accessories', 'Shehriz Eyewear Men Gold Metal Rectangular.webp', '2025-05-23 09:32:45', 5),
(60, 19, 'Women Brown Carbon fiber Square , UV Protected Sunglasses', 'Women Brown Carbon fiber Square , UV Protected Sunglasses', 700.00, 'Accessories', 'Women Brown Carbon fiber Square , UV Protected Sunglasses.webp', '2025-05-23 09:34:51', 10),
(61, 19, 'New Trending Imported Premium Men & Women Sunglasses', 'New Trending Imported Premium Men & Women Sunglasses', 700.00, 'Accessories', 'New Trending Imported Premium Men & Women Sunglasses.webp', '2025-05-23 09:37:07', 10),
(62, 19, 'Boys and Men\'s Exclusive Brown black Boys watch', 'Boys and Men\'s Exclusive Brown black Boys watch', 500.00, 'Accessories', 'Boys and Men\'s Exclusive Brown black Boys watch.webp', '2025-05-23 09:38:17', 10),
(63, 19, 'Trendy Analog Men\'s Watch', 'Trendy Analog Men\'s Watch', 700.00, 'Accessories', 'Trendy Analog Men\'s Watch.webp', '2025-05-23 09:39:18', 5),
(64, 19, 'Men Multicolor Leather Formal Watches', 'Men Multicolor Leather Formal Watches', 700.00, 'Accessories', 'Men Multicolor Leather Formal Watches.webp', '2025-05-23 09:40:24', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(14, 'Harish Patil', 'harish@gmail.com', '$2y$10$nAraEaCMKHKdFZfMuznP/.SNIaYTpwAtzyBo8wjVXfU7g3rT2kExi', 'admin', '2025-05-23 07:24:57'),
(15, 'Daneshwari Savadi', 'danu@gmail.com', '$2y$10$Uo8Mh86VSHat7FmEKHhpeu4r9JDOEit8ouJpeSXWWThnCLIT426me', 'buyer', '2025-05-23 07:27:47'),
(16, 'Tarukh Jagiradar', 'tarukh@gmail.com', '$2y$10$xDOOWd60s3Zb75gYHK/vSu7GQfB3V4ISfKdcrfvV7LNHXvm2u55X2', 'seller', '2025-05-23 07:32:10'),
(17, 'laxmi more', 'laxmi@gmail.com', '$2y$10$ACGGuLT6/gPqUGxxjEeJ.uFUyZcDqpfcreAD6HDrQyP5gbWJO8YgK', 'seller', '2025-05-23 08:07:44'),
(18, 'Kavana badiger', 'kavana@gmail.com', '$2y$10$FpkqCNGotcsc8bUT25r4c.GLXk8cwy33gaD4QK2iFnTrikvUYy/la', 'seller', '2025-05-23 08:56:34'),
(19, 'Somesh', 'somesh@gmail.com', '$2y$10$VpoMv3TpgHapC3UO8S/G7OLZVEzz2xYbl9OcKRe7vqJPoK.eYV0KW', 'seller', '2025-05-23 09:19:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"home_marketplace\",\"table\":\"users\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-04-26 07:45:25', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
