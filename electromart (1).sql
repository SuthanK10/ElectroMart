-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 05:15 PM
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
-- Database: `electromart`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 2, 1, 1, '2025-09-19 09:54:16'),
(2, 2, 1, 1, '2025-09-19 09:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `subtotal`, `shipping`, `tax`, `total`, `created_at`) VALUES
(1, 2, 4499.97, 0.00, 0.00, 4499.97, '2025-09-19 16:27:34'),
(2, 4, 349.99, 0.00, 0.00, 349.99, '2025-09-19 16:28:37'),
(3, 3, 2599.98, 0.00, 0.00, 2599.98, '2025-09-19 18:03:39'),
(4, 2, 499.99, 0.00, 0.00, 499.99, '2025-09-19 18:05:16'),
(5, 2, 1499.99, 0.00, 0.00, 1499.99, '2025-09-19 18:27:01'),
(6, 2, 1499.99, 0.00, 0.00, 1499.99, '2025-09-19 22:06:59'),
(7, 3, 2099.89, 0.00, 0.00, 2099.89, '2025-09-19 22:10:39'),
(8, 3, 5099.94, 0.00, 0.00, 5099.94, '2025-09-19 22:12:56'),
(9, 3, 74999.50, 0.00, 0.00, 74999.50, '2025-09-19 22:14:14'),
(10, 5, 17959.87, 0.00, 0.00, 17959.87, '2025-09-20 04:45:41'),
(11, 2, 1499.99, 0.00, 0.00, 1499.99, '2025-09-20 19:13:10'),
(12, 2, 1699.98, 0.00, 0.00, 1699.98, '2025-09-21 01:21:31'),
(13, 2, 1399.99, 0.00, 0.00, 1399.99, '2025-09-21 02:00:09'),
(14, 4, 3899.99, 0.00, 0.00, 3899.99, '2025-09-21 02:09:41'),
(15, 3, 1299.99, 0.00, 0.00, 1299.99, '2025-09-21 02:10:07'),
(16, 6, 1099.99, 0.00, 0.00, 1099.99, '2025-09-21 02:18:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `ordered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `user_id`, `product_id`, `quantity`, `price`, `ordered_at`) VALUES
(1, 1, 2, 1, 3, 1499.99, '2025-09-19 10:57:34'),
(2, 2, 4, 4, 1, 349.99, '2025-09-19 10:58:37'),
(3, 3, 3, 11, 2, 1299.99, '2025-09-19 12:33:39'),
(4, 4, 2, 7, 1, 499.99, '2025-09-19 12:35:16'),
(5, 5, 2, 1, 1, 1499.99, '2025-09-19 12:57:01'),
(6, 6, 2, 1, 1, 1499.99, '2025-09-19 16:36:59'),
(7, 7, 3, 1, 1, 1499.99, '2025-09-19 16:40:39'),
(8, 7, 3, 9, 10, 59.99, '2025-09-19 16:40:39'),
(9, 8, 3, 2, 1, 1299.99, '2025-09-19 16:42:56'),
(10, 8, 3, 4, 1, 349.99, '2025-09-19 16:42:56'),
(11, 8, 3, 7, 1, 499.99, '2025-09-19 16:42:56'),
(12, 8, 3, 11, 1, 1299.99, '2025-09-19 16:42:56'),
(13, 8, 3, 12, 1, 1399.99, '2025-09-19 16:42:56'),
(14, 8, 3, 13, 1, 249.99, '2025-09-19 16:42:56'),
(15, 9, 3, 1, 50, 1499.99, '2025-09-19 16:44:14'),
(16, 10, 5, 1, 1, 1499.99, '2025-09-19 23:15:41'),
(17, 10, 5, 2, 1, 1299.99, '2025-09-19 23:15:41'),
(18, 10, 5, 3, 1, 1199.99, '2025-09-19 23:15:41'),
(19, 10, 5, 4, 1, 349.99, '2025-09-19 23:15:41'),
(20, 10, 5, 5, 1, 399.99, '2025-09-19 23:15:41'),
(21, 10, 5, 6, 1, 1099.99, '2025-09-19 23:15:41'),
(22, 10, 5, 7, 1, 499.99, '2025-09-19 23:15:41'),
(23, 10, 5, 8, 1, 3899.99, '2025-09-19 23:15:41'),
(24, 10, 5, 9, 1, 59.99, '2025-09-19 23:15:41'),
(25, 10, 5, 10, 1, 4999.99, '2025-09-19 23:15:41'),
(26, 10, 5, 11, 1, 1299.99, '2025-09-19 23:15:41'),
(27, 10, 5, 13, 1, 249.99, '2025-09-19 23:15:41'),
(28, 10, 5, 14, 1, 1099.99, '2025-09-19 23:15:41'),
(29, 11, 2, 1, 1, 1499.99, '2025-09-20 13:43:10'),
(30, 12, 2, 3, 1, 1199.99, '2025-09-20 19:51:31'),
(31, 12, 2, 7, 1, 499.99, '2025-09-20 19:51:31'),
(32, 13, 2, 12, 1, 1399.99, '2025-09-20 20:30:09'),
(33, 14, 4, 8, 1, 3899.99, '2025-09-20 20:39:41'),
(34, 15, 3, 2, 1, 1299.99, '2025-09-20 20:40:07'),
(35, 16, 6, 14, 1, 1099.99, '2025-09-20 20:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `features`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(1, 'iPhone 17 Pro Max', 'Apple flagship smartphone with A19 Bionic chip, 6.9-inch display, and advanced camera system.', 'Premium build quality\r\nFast A19 Bionic chip\r\n6.9-inch Super Retina display\r\nAdvanced camera system\r\nLong battery life', 1499.99, 50, 'Smartphone', '../images/iphone17pm.png', '2025-09-16 17:06:28'),
(2, 'MacBook Air', 'Lightweight Apple laptop with M3 chip, 13-inch Retina display, and long battery life.', 'Lightweight aluminum design\r\n13-inch Retina display\r\nM2 chip for fast performance\r\nUp to 18 hours battery\r\nBacklit Magic Keyboard', 1299.99, 30, 'Laptop', '../images/macbookair2.jpeg', '2025-09-16 17:06:28'),
(3, 'Dell XPS 13', 'Premium ultrabook with Intel Core i7, 16GB RAM, 512GB SSD, and InfinityEdge display.', '13-inch InfinityEdge display\r\nIntel Core i7 processor\r\n16GB RAM, 512GB SSD\r\nUltra-portable design\r\nLong-lasting battery', 1199.99, 20, 'Laptop', '../images/dellxps3.png', '2025-09-16 17:06:28'),
(4, 'Samsung Galaxy Watch 7', 'Smartwatch with health tracking, GPS, and AMOLED display.', 'AMOLED touch display\r\nAdvanced fitness tracking\r\nGPS + heart rate monitoring\r\nWater resistant\r\nLong battery life', 349.99, 40, 'Wearable', '../images/samsungwatch72.png', '2025-09-16 17:06:28'),
(5, 'Sony WH-1000XM5', 'Industry-leading noise-canceling headphones with 30-hour battery life.', 'Industry-leading noise cancellation\r\nHigh-resolution audio\r\nAdaptive sound control\r\nComfortable fit\r\nLong battery life', 399.99, 25, 'Audio', '../images/sonyhs.png', '2025-09-16 17:06:28'),
(6, 'iPad Pro', '12.9-inch tablet with M2 chip, ProMotion display, and Apple Pencil support.', '12.9-inch Liquid Retina display\r\nM2 chip for extreme performance\r\nApple Pencil support\r\nProMotion technology\r\nLong-lasting battery', 1099.99, 35, 'Tablet', '../images/ipadpro2.jpg', '2025-09-16 17:06:28'),
(7, 'PlayStation 5', 'Next-gen gaming console with ultra-fast SSD and 4K gaming support.', 'Ultra-fast SSD storage\r\n4K gaming capability\r\nDualSense wireless controller\r\nExclusive game titles\r\nImmersive 3D audio', 499.99, 15, 'Gaming Console', '../images/ps5.jpg', '2025-09-16 17:06:28'),
(8, 'Canon EOS R5', 'Professional mirrorless camera with 45MP sensor and 8K video recording.', '45MP full-frame sensor\r\n8K video recording\r\nDual memory card slots\r\nHigh-speed autofocus\r\nWeather-sealed body', 3899.99, 10, 'Camera', '../images/canoneos.jpg', '2025-09-16 17:06:28'),
(9, 'Amazon Echo Dot (5th Gen)', 'Smart speaker with Alexa voice assistant, improved sound, and smart home control.', 'Smart home integration\r\nVoice assistant support\r\nHigh-quality sound\r\nCompact design\r\nWi-Fi and Bluetooth connectivity', 59.99, 60, 'Smart Home', '../images/echodot.jpg', '2025-09-16 17:06:28'),
(10, 'Samsung Neo QLED 8K', '75-inch 8K TV with Quantum Matrix Technology, HDR10+, and smart features.', '75-inch 8K resolution\r\nQuantum Matrix Technology\r\nHDR10+ support\r\nSmart TV features\r\nImmersive surround sound', 4999.99, 8, 'TV', '../images/samsungneo.png', '2025-09-16 17:06:28'),
(11, 'Samsung Galaxy S25 Ultra', 'Flagship smartphone with cutting-edge performance, pro-level cameras, and AI-enhanced features.', '6.9-inch Dynamic AMOLED 2X display\r\nSnapdragon 8 Gen 4 chip\r\nQuad-camera system with 200MP main\r\n1TB max storage option\r\nAI photo and productivity tools', 1299.99, 15, 'Smartphone', '../images/samsungs25u.jpg', '2025-09-17 16:37:12'),
(12, 'ASUS TUF A15 Gaming Laptop', 'Durable gaming laptop with powerful hardware, high refresh display, and military-grade build quality.', '15.6-inch FHD 144Hz display\r\nAMD Ryzen 9 processor\r\nNVIDIA GeForce RTX 4070 GPU\r\n1TB SSD + 16GB RAM\r\nTUF military-grade durability', 1399.99, 20, 'Laptop', '../images/tuff.png', '2025-09-17 16:37:12'),
(13, 'Airpods Pro 3', 'The AirPods Pro 3 are Apple’s latest premium earbuds with improved Active Noise Cancellation, Adaptive Audio, Spatial Audio, a built-in heart rate sensor, and Live Translation. They feature foam-infused tips in five sizes for a secure fit, IP57 dust and water resistance, and deliver up to 8 hours of listening with ANC on. Designed for workouts, travel, and everyday use, they combine powerful sound with intelligent features.', 'Active Noise Cancellation with improved seal\r\n\r\nTransparency mode and Adaptive Audio\r\n\r\nFive ear tip sizes including XXS for better fit\r\n\r\nBuilt-in heart rate sensor for fitness tracking\r\n\r\nLive Translation support\r\n\r\nIP57 dust, sweat, and water resistance\r\n\r\nPersonalized Volume and Conversation Awareness\r\n\r\nSpatial Audio with dynamic head tracking', 249.99, 10, 'Audio', '../images/airpods3.png', '2025-09-19 14:01:31'),
(14, 'Samsung Tab S11 Ultra', 'The Samsung Tab S11 Ultra is a flagship productivity tablet that combines a stunning 14.6-inch AMOLED display with an ultra-slim design, powerful performance, and advanced S Pen features. It’s perfect for creatives, students, and professionals who want top-tier hardware in a sleek form factor. With quad speakers, 5G connectivity (optional), and long battery life, the Tab S11 Ultra delivers an immersive experience whether you\'re drawing, watching, or multitasking.', '14.6-inch AMOLED display with 120Hz refresh rate\r\n\r\nUltra Slim Bezels & lightweight design\r\n\r\nSnapdragon flagship processor with large RAM (12-16GB)\r\n\r\nS Pen included with Air Actions and low latency responsiveness\r\n\r\nStorage options up to 512GB plus microSD expansion\r\n\r\nQuad speakers tuned by AKG with Dolby Atmos\r\n\r\nOptional 5G connectivity and WiFi 6E\r\n\r\nLong battery life (up to 12-14 hours video playback)\r\n\r\nPremium build: aluminum frame and Gorilla Glass\r\n\r\nFast charging support', 1099.99, 5, 'Tablet', '../images/samsungtabs11.jpg', '2025-09-19 14:35:03');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `name`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 'Vidwa', 5, 'Insanely fast and battery lasts all day.', '2025-09-17 21:32:35'),
(2, 1, 'Sara', 4, 'Camera is top tier, a bit pricey though.', '2025-09-17 21:32:35'),
(3, 1, 'Ravi', 5, 'Best iPhone I’ve used so far.', '2025-09-17 21:32:35'),
(4, 2, 'Fiona', 5, 'Light and powerful—perfect for uni.', '2025-09-17 21:32:35'),
(5, 2, 'Kavindu', 4, 'Great battery, wish there were more ports.', '2025-09-17 21:32:35'),
(6, 2, 'Sajini', 5, 'M2 flies and the display is gorgeous.', '2025-09-17 21:32:35'),
(7, 3, 'Santhosh', 5, 'Premium build and super portable.', '2025-09-17 21:32:35'),
(8, 3, 'Ishara', 4, 'Runs a bit warm under heavy loads.', '2025-09-17 21:32:35'),
(9, 3, 'Malsha', 5, 'Screen and keyboard feel excellent.', '2025-09-17 21:32:35'),
(10, 4, 'Abu', 5, 'Accurate tracking and smooth UI.', '2025-09-17 21:32:35'),
(11, 4, 'Madhavi', 4, 'Stylish, GPS is very precise.', '2025-09-17 21:32:35'),
(12, 4, 'Charith', 4, 'Great watch; app ecosystem could grow.', '2025-09-17 21:32:35'),
(13, 5, 'Nethmi', 5, 'ANC is next level, sound is rich.', '2025-09-17 21:32:35'),
(14, 5, 'Dinuka', 5, 'Super comfy for long sessions.', '2025-09-17 21:32:35'),
(15, 5, 'Harsha', 4, 'Amazing audio, a bit expensive.', '2025-09-17 21:32:35'),
(16, 6, 'Greenish', 5, 'Perfect for drawing and editing.', '2025-09-17 21:32:35'),
(17, 6, 'Ruwini', 4, 'Brilliant screen; accessories add up.', '2025-09-17 21:32:35'),
(18, 6, 'Pasindu', 5, 'M2 performance is crazy smooth.', '2025-09-17 21:32:35'),
(19, 7, 'Raghav', 5, 'Loads fast and 4K gaming is stunning.', '2025-09-17 21:32:35'),
(20, 7, 'Nipun', 4, 'Great exclusives; storage fills quickly.', '2025-09-17 21:32:35'),
(21, 7, 'Irum', 5, 'DualSense haptics are a game changer.', '2025-09-17 21:32:35'),
(22, 8, 'Milni', 5, 'Image quality is unbelievable.', '2025-09-17 21:32:35'),
(23, 8, 'Chathuri', 4, '8K is amazing; watch the temps.', '2025-09-17 21:32:35'),
(24, 8, 'Sahan', 5, 'AF tracking is rock solid.', '2025-09-17 21:32:35'),
(25, 9, 'Devmi', 5, 'Great sound for the size.', '2025-09-17 21:32:35'),
(26, 9, 'Roshan', 4, 'Smart home works flawlessly.', '2025-09-17 21:32:35'),
(27, 9, 'Dilshan', 4, 'Handy for routines and timers.', '2025-09-17 21:32:35'),
(28, 10, 'Hasini', 5, 'Picture is insanely sharp.', '2025-09-17 21:32:35'),
(29, 10, 'Chamara', 4, 'HDR pops; speakers are okay.', '2025-09-17 21:32:35'),
(30, 10, 'Nadeesha', 5, 'Gaming at 120Hz is butter-smooth.', '2025-09-17 21:32:35'),
(31, 11, 'Kasun', 5, 'The 200MP camera is insane, photos look pro.', '2025-09-17 22:10:51'),
(32, 11, 'Amaya', 4, 'Battery is strong but charging brick not included.', '2025-09-17 22:10:51'),
(33, 11, 'Sahan', 5, 'Display and performance are unmatched.', '2025-09-17 22:10:51'),
(34, 12, 'Raghav', 5, 'This laptop is so tuff bro🤓☝️', '2025-09-17 22:10:51'),
(35, 12, 'Nadeesha', 4, 'Great performance, but a bit heavy.', '2025-09-17 22:10:51'),
(36, 12, 'Thilina', 5, 'Solid laptop, military-grade feel is real.', '2025-09-17 22:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_specs`
--

CREATE TABLE `product_specs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_key` varchar(255) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_specs`
--

INSERT INTO `product_specs` (`id`, `product_id`, `spec_key`, `spec_value`, `sort_order`) VALUES
(1, 1, 'Display', '6.9-inch Super Retina XDR', 1),
(2, 1, 'Processor', 'A19 Bionic', 2),
(3, 1, 'Main Camera', '48MP + Ultra Wide + Telephoto', 3),
(4, 1, 'Battery Life', 'Up to 23 hours video playback', 4),
(5, 1, 'Connectivity', '5G, Wi-Fi 6E, Bluetooth 5.x', 5),
(6, 2, 'Display', '13.6-inch Liquid Retina', 1),
(7, 2, 'Processor', 'Apple M2', 2),
(8, 2, 'Memory', '8GB Unified Memory', 3),
(9, 2, 'Storage', '256GB SSD', 4),
(10, 2, 'Battery Life', 'Up to 18 hours', 5),
(11, 3, 'Display', '13.4-inch InfinityEdge FHD+', 1),
(12, 3, 'Processor', 'Intel Core i7 (12th Gen)', 2),
(13, 3, 'Memory', '16GB LPDDR5', 3),
(14, 3, 'Storage', '512GB SSD', 4),
(15, 3, 'OS', 'Windows 11', 5),
(16, 4, 'Display', '1.4-inch AMOLED', 1),
(17, 4, 'Battery Life', 'Up to 40 hours', 2),
(18, 4, 'Sensors', 'Heart Rate, SpO2, ECG', 3),
(19, 4, 'Water Resistance', '5 ATM + IP68', 4),
(20, 4, 'Connectivity', 'GPS, Bluetooth, Wi-Fi', 5),
(21, 5, 'Type', 'Over-Ear Wireless Headphones', 1),
(22, 5, 'ANC', 'Industry-leading noise cancellation', 2),
(23, 5, 'Drivers', '30mm', 3),
(24, 5, 'Battery Life', 'Up to 30 hours', 4),
(25, 5, 'Charging', 'USB-C Fast Charge', 5),
(26, 6, 'Display', '12.9-inch Liquid Retina XDR', 1),
(27, 6, 'Processor', 'Apple M2', 2),
(28, 6, 'Apple Pencil', '2nd Gen support', 3),
(29, 6, 'ProMotion', '120Hz', 4),
(30, 6, 'Battery Life', 'Up to 10 hours', 5),
(31, 7, 'CPU', 'AMD Zen 2, 8-core', 1),
(32, 7, 'GPU', 'AMD RDNA 2 (~10.28 TFLOPS)', 2),
(33, 7, 'Memory', '16GB GDDR6', 3),
(34, 7, 'Storage', '1TB NVMe SSD', 4),
(35, 7, 'Output', 'Up to 4K @ 120Hz, HDR', 5),
(36, 8, 'Sensor', '45MP Full-Frame CMOS', 1),
(37, 8, 'Video', '8K RAW / 4K HQ', 2),
(38, 8, 'IBIS', 'Up to 8 stops', 3),
(39, 8, 'AF', 'Dual Pixel CMOS AF II', 4),
(40, 8, 'Burst', 'Up to 20 fps (electronic)', 5),
(41, 9, 'Speaker', '1.73-inch front-firing', 1),
(42, 9, 'Assistant', 'Alexa', 2),
(43, 9, 'Connectivity', 'Wi-Fi, Bluetooth', 3),
(44, 9, 'Smart Home', 'Matter/Thread (where available)', 4),
(45, 9, 'Mics', 'Far-field voice recognition', 5),
(46, 10, 'Display', '75-inch 8K Neo QLED', 1),
(47, 10, 'Processor', 'Neo Quantum 8K', 2),
(48, 10, 'HDR', 'HDR10+ support', 3),
(49, 10, 'Refresh Rate', '120Hz', 4),
(50, 10, 'Ports', 'HDMI 2.1 x4', 5),
(51, 11, 'Display', '6.9-inch Dynamic AMOLED 2X, 120Hz', 1),
(52, 11, 'Processor', 'Qualcomm Snapdragon 8 Gen 4', 2),
(53, 11, 'Camera', '200MP + 12MP Ultra-Wide + 10MP Telephoto + 10MP Periscope', 3),
(54, 11, 'Storage', '256GB / 512GB / 1TB', 4),
(55, 11, 'Battery', '5000 mAh with 65W fast charging', 5),
(56, 12, 'Display', '15.6-inch FHD IPS, 144Hz', 1),
(57, 12, 'Processor', 'AMD Ryzen 9 7940HS', 2),
(58, 12, 'GPU', 'NVIDIA GeForce RTX 4070 8GB', 3),
(59, 12, 'RAM', '16GB DDR5', 4),
(60, 12, 'Storage', '1TB NVMe SSD', 5),
(61, 12, 'Weight', '2.2 kg', 6),
(62, 13, 'Driver', 'Custom high-excursion Apple driver', 0),
(63, 13, 'Amplifier', 'High-dynamic range amplifier', 1),
(64, 13, 'Chip', 'Apple H2 chip', 2),
(65, 13, 'Controls', 'ANC, Transparency, Adaptive Audio, Voice Isolation', 3),
(66, 13, 'Ear Tips', '5 sizes including XXS, foam-infused', 4),
(67, 13, 'Battery Life', 'Up to 8 hours (ANC), 10 hours (Transparency)', 5),
(68, 13, 'Water Resistance', 'IP57 dust and water resistant', 6),
(69, 13, 'Sensors', 'Heart rate sensor, accelerometer, gyroscope', 7),
(70, 13, 'Charging', 'USB-C charging case with extra charges', 8),
(71, 13, 'Special Features', 'Live Translation, Personalized Volume, Spatial Audio', 9),
(72, 14, 'Display', '14.6-inch AMOLED, 120Hz', 0),
(73, 14, 'Processor', 'Snapdragon 8 Gen 2 for Galaxy', 1),
(74, 14, 'RAM', '12-16GB', 2),
(75, 14, 'Storage Options', '128/256/512GB + microSD support', 3),
(76, 14, 'Battery', '11200mAh, supports fast charging', 4),
(77, 14, 'Speakers', 'Quad AKG speakers, Dolby Atmos', 5),
(78, 14, 'Connectivity', 'WiFi 6E, Bluetooth 5.3, optional 5G', 6),
(79, 14, 'Stylus', 'S Pen included with Air Actions', 7),
(80, 14, 'Cameras', '13MP Ultra Wide front, 13MP + 50MP + 12MP rear triple', 8),
(81, 14, 'Build', 'Aluminum frame, Gorilla Glass Victus', 9),
(82, 14, 'Charging Port', 'USB-C 3.2', 10),
(83, 14, 'Wireless Charging', 'Supports reverse wireless charging', 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Site Admin', 'admin@gmail.com', '$2y$10$RLrd9RzUhMNAx2CMhH4PU.9VaLyRgEQk2yPDGRwnwpGrkF9VZ4ijG', 'admin', '2025-09-18 16:15:50'),
(2, 'Suthan', 'suthan@gmail.com', '$2y$10$kuWjahRoheA31NV0veoJRe.jL4gtfAqeegToTBwF6kpyJFQtQZiA2', 'user', '2025-09-18 16:16:31'),
(3, 'Abu', 'abu@gmail.com', '$2y$10$NUYdhX79JAmlAUCTBCKtPuyTAzdgaUDsRjj7YDu0xsvD7eOgllqrW', 'user', '2025-09-19 00:03:22'),
(4, 'Yassa', 'yassa@gmail.com', '$2y$10$mvf0pXPm7G0q3cKjrolkCu0H4AxSjGiY.kpdg4Bqg4Em9E8kfJf56', 'user', '2025-09-19 16:13:34'),
(5, 'Bubs', 'bubs@gmail.com', '$2y$10$RbXOhE320qfxaXKY.v9pPuwwy951aXYxHBuuF1VOXdmO2S4qMGQNa', 'user', '2025-09-20 04:43:30'),
(6, 'Greenish', 'greenish@gmail.com', '$2y$10$8QHQx6IyVl0meOk9c9uJ0eG/r4ZSPa8wHNicbV6txa0WjwcDw/Dxq', 'user', '2025-09-21 02:18:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_specs`
--
ALTER TABLE `product_specs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `product_specs`
--
ALTER TABLE `product_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specs`
--
ALTER TABLE `product_specs`
  ADD CONSTRAINT `product_specs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
