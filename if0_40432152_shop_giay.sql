-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql102.infinityfree.com
-- Thời gian đã tạo: Th10 18, 2025 lúc 09:30 PM
-- Phiên bản máy phục vụ: 11.4.7-MariaDB
-- Phiên bản PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `if0_40432152_shop_giay`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `receiver_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `receiver_name`, `phone_number`, `address`, `total_amount`, `payment_method`, `status`, `transaction_id`, `order_date`) VALUES
(12, 13, 'a', '123', '123', '3500000.00', 'cod', 'Pending', 'COD-1763464242', '2025-11-18 03:10:42'),
(13, 16, 'VÃµ ThÃ nh TÃ¢m', '0706236006', 'abc 123', '1280000.00', 'cod', 'Pending', 'COD-1763488901', '2025-11-18 10:01:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_name`, `price`, `quantity`) VALUES
(12, 12, 'Nike Air Force', '500000.00', 7),
(13, 13, '4T VT Cream Horizon', '1280000.00', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `brand`) VALUES
(25, 'Adidas Samba OG White Black', '1800000.00', 'Adidas/adidas (1).jpg', NULL, 'Adidas'),
(26, 'Adidas Neo Gradas White Gray', '1550000.00', 'Adidas/adidas (2).jpg', NULL, 'Adidas'),
(27, 'Adidas EQ21', '1320000.00', 'Adidas/adidas (3).jpg', NULL, 'Adidas'),
(28, 'Adidas DAILY 2.0 CLOUD White', '500000.00', 'Adidas/adidas (4).jpg', NULL, 'Adidas'),
(29, 'Adidas Centennial Green', '500000.00', 'Adidas/adidas (5).jpg', NULL, 'Adidas'),
(30, 'Adidas  Slip On Branco Original', '1999000.00', 'Adidas/adidas (6).jpg', NULL, 'Adidas'),
(31, 'Adidas Galaxy 7', '1090000.00', 'Adidas/adidas (7).jpg', NULL, 'Adidas'),
(33, 'GiÃ y Adidas Corerunner FY9621 White', '500000.00', 'Adidas/adidas (8).jpg', NULL, 'Adidas'),
(34, 'Adidas Breaknet 2.0', '1108000.00', 'Adidas/adidas (9).jpg', NULL, 'Adidas'),
(35, 'Adidas Ultraboost Light', '1500000.00', 'Adidas/adidas (10).jpg', NULL, 'Adidas'),
(36, '4T VT Cream Horizon', '1280000.00', '4T/4T (1).jpg', NULL, '4T'),
(37, '4T VT Orange Premium', '1500000.00', '4T/4T (2).jpg', NULL, '4T'),
(38, '4T Sunrise', '1830000.00', '4T/4T (4).jpg', NULL, '4T'),
(39, '4T QT Black Red', '1116000.00', '4T/4T (3).jpg', NULL, '4T'),
(40, '4T VT Galaxy', '1650000.00', '4T/4T (5).jpg', NULL, '4T'),
(41, '4T Pure', '1732000.00', '4T/4T (6).jpg', NULL, '4T'),
(42, '4T Urban High Black White', '1855000.00', '4T/4T (7).jpg', NULL, '4T'),
(43, '4T Blackout', '1000000.00', '4T/4T Blackout.png', NULL, '4T'),
(44, 'Puma Speedcat OG Black Mauve Mist', '480000.00', 'Puma/puma (1).jpg', NULL, 'Puma'),
(45, 'Nike Dynk Low Retro', '3250000.00', 'Nike/nike (1).jpg', NULL, 'Nike'),
(46, '4T Sneakers Black And Red', '2250000.00', '4T/4T (9).jpg', NULL, '4T'),
(47, '4T Titan White', '1540000.00', '4T/4T (10).jpg', NULL, '4T'),
(48, '4T Blazer Premium Natural', '4250000.00', '4T/4T (11).jpg', NULL, '4T'),
(50, 'Puma Court Classic Vulc FS White', '2150000.00', 'Puma/puma (3).jpg', NULL, 'Puma'),
(51, 'Puma Speedcat OG Black White', '1575000.00', 'Puma/puma (4).jpg', NULL, 'Puma'),
(52, 'Puma Palermo Black White', '1500000.00', 'Puma/puma (5).jpg', NULL, 'Puma'),
(54, 'Puma Speedcat OG Sparco Navy', '1650000.00', 'Puma/puma (6).jpg', NULL, 'Puma'),
(55, 'Puma Sneaker Unisex', '1500000.00', 'Puma/puma (7).jpg', NULL, 'Puma'),
(56, 'Puma Wired Rapid', '1092000.00', 'Puma/puma (8).jpg', NULL, 'Puma'),
(57, 'Puma Caven Sneaker', '1500000.00', 'Puma/puma (9).jpg', NULL, 'Puma'),
(58, 'Puma Cavern 2.0', '1350000.00', 'Puma/puma (10).jpg', NULL, 'Puma'),
(59, 'Nike Air Force', '500000.00', 'Nike/{9DAECA76-B59C-4952-A9AD-CB5733EBFFC4}.png', NULL, 'Nike'),
(60, 'Nike Air Force 1 Low â€™07 LX', '760000.00', 'Nike/{8D25F602-1CB0-41ED-AF83-312D9B8BEF7D}.png', NULL, 'Nike'),
(61, 'Nike Air Force 1 Shadow Sail', '2000000.00', 'Nike/{68FDD9E8-F6CF-4AE6-965E-7516B9EE8B34}.png', NULL, 'Nike'),
(62, 'Nike Blazer', '1200000.00', 'Nike/{479E0218-CAB1-4A8C-8619-DBFB9318EDB3}.png', NULL, 'Nike'),
(63, 'Nike Dunk', '360000.00', 'Nike/{6B321929-AF5F-4D7B-BD37-82BE37698329}.png', NULL, 'Nike'),
(64, 'Nike Court', '600000.00', 'Nike/{DA66B3F8-7950-4AC2-B9DF-8E4F7AF8F39F}.png', NULL, 'Nike'),
(65, 'Nike Air Max 90', '850000.00', 'Nike/{6387A47D-5E01-486F-B347-B0814F3F6552}.png', NULL, 'Nike'),
(66, 'Nike Air Max 1', '1500000.00', 'Nike/{FBDC470D-0DB6-497C-BD3D-FBCB00FAAEC1}.png', NULL, 'Nike'),
(67, 'Nike Air Max 97', '1575000.00', 'Nike/{54007570-4AB3-414E-8C20-6FC71A59EA53}.png', NULL, 'Nike'),
(68, 'Nike React', '102000.00', 'Nike/{3C40616E-DC77-4425-BF24-DEA98A61A7A9}.png', NULL, 'Nike'),
(69, 'Nike Cortez OG', '1400000.00', 'Nike/{88471B53-AD5A-4C5B-A76A-D01C6D0D78EB}.png', NULL, 'Nike'),
(70, 'Nike Black', '900000.00', 'Nike/nike (2).jpg', NULL, 'Nike'),
(71, 'Nike Rainbow', '850000.00', 'Nike/nike (5).jpg', NULL, 'Nike'),
(72, 'Nike Court Vision Mid Smoke Grey', '1490000.00', 'Nike/nike (12).jpg', NULL, 'Nike');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(12, 'admin', '$2y$10$j0h6OIkemuEBg4FPUvLFS.Oc1qp8Y2mekPvUtBFWH1rXgXH4mw1mK', 'admin'),
(13, 'user', '$2y$10$H.Qn2Owk3cll6uciN53AMeABAnkyhAGoA2fXojB4XqgqIrstM0G9O', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
