-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql102.infinityfree.com
-- Thời gian đã tạo: Th10 16, 2025 lúc 10:05 AM
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
(25, 'Adidas', '500000.00', 'Adidas/adidas (1).jpg', NULL, 'Adidas'),
(26, 'Adidas', '500000.00', 'Adidas/adidas (2).jpg', NULL, 'Adidas'),
(27, 'Adidas', '500000.00', 'Adidas/adidas (3).jpg', NULL, 'Adidas'),
(28, 'Adidas', '500000.00', 'Adidas/adidas (4).jpg', NULL, 'Adidas'),
(29, 'Adidas', '500000.00', 'Adidas/adidas (5).jpg', NULL, 'Adidas'),
(30, 'Adidas', '500000.00', 'Adidas/adidas (6).jpg', NULL, 'Adidas'),
(31, 'Adidas', '500000.00', 'Adidas/adidas (7).jpg', NULL, 'Adidas'),
(33, 'Adidas', '500000.00', 'Adidas/adidas (8).jpg', NULL, 'Adidas'),
(34, 'Adidas', '500000.00', 'Adidas/adidas (9).jpg', NULL, 'Adidas'),
(35, 'Adidas', '500000.00', 'Adidas/adidas (10).jpg', NULL, 'Adidas'),
(36, '4T', '1000000.00', '4T/4T (1).jpg', NULL, '4T'),
(37, '4T', '1000000.00', '4T/4T (2).jpg', NULL, '4T'),
(38, '4T', '1000000.00', '4T/4T (4).jpg', NULL, '4T'),
(39, '4T', '1000000.00', '4T/4T (3).jpg', NULL, '4T'),
(40, '4T', '1000000.00', '4T/4T (5).jpg', NULL, '4T'),
(41, '4T Pure', '1000000.00', '4T/4T (6).jpg', NULL, '4T'),
(42, '4T Urban High', '1000000.00', '4T/4T (7).jpg', NULL, '4T'),
(43, '4T Blackout', '1000000.00', '4T/4T Blackout.png', NULL, '4T'),
(44, 'Puma', '1500000.00', 'Puma/puma (1).jpg', NULL, 'Puma'),
(45, 'Nike', '250000.00', 'Nike/nike (1).jpg', NULL, 'Nike'),
(46, '4T', '1000000.00', '4T/4T (9).jpg', NULL, '4T'),
(47, '4T', '1000000.00', '4T/4T (10).jpg', NULL, '4T'),
(48, '4T', '1000000.00', '4T/4T (11).jpg', NULL, '4T'),
(50, 'Puma', '1500000.00', 'Puma/puma (3).jpg', NULL, 'Puma'),
(51, 'Puma', '1500000.00', 'Puma/puma (4).jpg', NULL, 'Puma'),
(52, 'Puma', '1500000.00', 'Puma/puma (5).jpg', NULL, 'Puma'),
(53, 'Puma', '1500000.00', 'Puma/puma (2).jpg', NULL, 'Puma'),
(54, 'Puma', '1500000.00', 'Puma/puma (6).jpg', NULL, 'Puma'),
(55, 'Puma', '1500000.00', 'Puma/puma (7).jpg', NULL, 'Puma'),
(56, 'Puma', '1500000.00', 'Puma/puma (8).jpg', NULL, 'Puma'),
(57, 'Puma', '1500000.00', 'Puma/puma (9).jpg', NULL, 'Puma'),
(58, 'Puma', '1500000.00', 'Puma/puma (10).jpg', NULL, 'Puma');

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
(13, 'user', '$2y$10$H.Qn2Owk3cll6uciN53AMeABAnkyhAGoA2fXojB4XqgqIrstM0G9O', 'user'),
(14, 'user1', '$2y$10$qg.W8yEgd3EruBZ2TGWEfO0fAf2DsjZAgUskcQXc3adL.jEQVj8vi', 'user');

--
-- Chỉ mục cho các bảng đã đổ
--

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
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
