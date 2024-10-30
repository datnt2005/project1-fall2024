-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 11:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project1-fall2024`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `idAddress` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `idCart` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `idCategory` int(11) NOT NULL,
  `nameCategory` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `idComment` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `commentDate` datetime NOT NULL,
  `evaluate` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `codeCoupon` varchar(250) NOT NULL,
  `nameCoupon` varchar(250) NOT NULL,
  `idCoupon` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `quantityCoupon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailaddress`
--

CREATE TABLE `detailaddress` (
  `idDetailAddress` int(11) NOT NULL,
  `nameAddress` text NOT NULL,
  `phone` varchar(10) NOT NULL,
  `street` text NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `defaultAddress` int(11) NOT NULL,
  `idAddress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailcart`
--

CREATE TABLE `detailcart` (
  `quantityCart` int(11) NOT NULL,
  `size` varchar(250) NOT NULL,
  `idDetailCart` int(11) NOT NULL,
  `idCart` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detailorder`
--

CREATE TABLE `detailorder` (
  `quantityOrder` int(11) NOT NULL,
  `sizeOrder` varchar(250) NOT NULL,
  `idDetailOrder` int(11) NOT NULL,
  `idOrder` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `idDistrict` int(11) NOT NULL,
  `nameDistrict` varchar(250) NOT NULL,
  `idProvince` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `idOrder` int(11) NOT NULL,
  `dateOrder` datetime NOT NULL,
  `statusOrder` varchar(250) NOT NULL,
  `noteOrder` text NOT NULL,
  `totalPrice` int(11) NOT NULL,
  `payment` varchar(250) NOT NULL,
  `idAddress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piccomment`
--

CREATE TABLE `piccomment` (
  `namePicComment` text NOT NULL,
  `idPicComment` int(11) NOT NULL,
  `idComment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `picproduct`
--

CREATE TABLE `picproduct` (
  `idPicProduct` int(11) NOT NULL,
  `namePicProduct` text NOT NULL,
  `idProduct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `idProduct` int(11) NOT NULL,
  `nameProduct` text NOT NULL,
  `description` text NOT NULL,
  `idSubCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_size`
--

CREATE TABLE `product_size` (
  `idProductSize` int(11) NOT NULL,
  `quantityProduct` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `idSize` int(11) NOT NULL,
  `price` int(110) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `province`
--

CREATE TABLE `province` (
  `idProvince` int(11) NOT NULL,
  `nameProvince` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `nameSize` varchar(250) NOT NULL,
  `idSize` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `idSubCategory` int(11) NOT NULL,
  `nameSubCategory` varchar(250) NOT NULL,
  `idCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(250) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(250) NOT NULL,
  `status` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `idUser` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `token` int(11) NOT NULL,
  `otpCreated` int(11) NOT NULL,
  `otp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `idWard` int(11) NOT NULL,
  `nameWard` varchar(250) NOT NULL,
  `idDistrict` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`idAddress`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`idCart`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategory`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`idComment`),
  ADD KEY `idProduct` (`idProduct`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`idCoupon`);

--
-- Indexes for table `detailaddress`
--
ALTER TABLE `detailaddress`
  ADD PRIMARY KEY (`idDetailAddress`),
  ADD KEY `idAddress` (`idAddress`);

--
-- Indexes for table `detailcart`
--
ALTER TABLE `detailcart`
  ADD PRIMARY KEY (`idDetailCart`),
  ADD UNIQUE KEY `idCart` (`idCart`,`idProduct`),
  ADD KEY `idProduct` (`idProduct`);

--
-- Indexes for table `detailorder`
--
ALTER TABLE `detailorder`
  ADD PRIMARY KEY (`idDetailOrder`,`idOrder`,`idProduct`),
  ADD KEY `idOrder` (`idOrder`),
  ADD KEY `idProduct` (`idProduct`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`idDistrict`),
  ADD KEY `idProvince` (`idProvince`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idAddress` (`idAddress`);

--
-- Indexes for table `piccomment`
--
ALTER TABLE `piccomment`
  ADD PRIMARY KEY (`idPicComment`),
  ADD KEY `idComment` (`idComment`);

--
-- Indexes for table `picproduct`
--
ALTER TABLE `picproduct`
  ADD PRIMARY KEY (`idPicProduct`),
  ADD KEY `idProduct` (`idProduct`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`idProduct`),
  ADD KEY `idSubCategory` (`idSubCategory`);

--
-- Indexes for table `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`idProductSize`),
  ADD KEY `idProduct` (`idProduct`),
  ADD KEY `idSize` (`idSize`);

--
-- Indexes for table `province`
--
ALTER TABLE `province`
  ADD PRIMARY KEY (`idProvince`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`idSize`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`idSubCategory`),
  ADD KEY `idCategory` (`idCategory`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`idWard`),
  ADD KEY `idDistrict` (`idDistrict`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `products` (`idProduct`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Constraints for table `detailaddress`
--
ALTER TABLE `detailaddress`
  ADD CONSTRAINT `detailaddress_ibfk_1` FOREIGN KEY (`idAddress`) REFERENCES `address` (`idAddress`);

--
-- Constraints for table `detailcart`
--
ALTER TABLE `detailcart`
  ADD CONSTRAINT `detailcart_ibfk_1` FOREIGN KEY (`idCart`) REFERENCES `carts` (`idCart`),
  ADD CONSTRAINT `detailcart_ibfk_2` FOREIGN KEY (`idProduct`) REFERENCES `products` (`idProduct`);

--
-- Constraints for table `detailorder`
--
ALTER TABLE `detailorder`
  ADD CONSTRAINT `detailorder_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `orders` (`idOrder`),
  ADD CONSTRAINT `detailorder_ibfk_2` FOREIGN KEY (`idProduct`) REFERENCES `products` (`idProduct`);

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `district_ibfk_1` FOREIGN KEY (`idProvince`) REFERENCES `province` (`idProvince`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`idAddress`) REFERENCES `address` (`idAddress`);

--
-- Constraints for table `piccomment`
--
ALTER TABLE `piccomment`
  ADD CONSTRAINT `piccomment_ibfk_1` FOREIGN KEY (`idComment`) REFERENCES `comments` (`idComment`);

--
-- Constraints for table `picproduct`
--
ALTER TABLE `picproduct`
  ADD CONSTRAINT `picproduct_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `products` (`idProduct`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`idSubCategory`) REFERENCES `subcategory` (`idSubCategory`);

--
-- Constraints for table `product_size`
--
ALTER TABLE `product_size`
  ADD CONSTRAINT `product_size_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `products` (`idProduct`),
  ADD CONSTRAINT `product_size_ibfk_2` FOREIGN KEY (`idSize`) REFERENCES `sizes` (`idSize`);

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`idCategory`) REFERENCES `categories` (`idCategory`);

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`idDistrict`) REFERENCES `district` (`idDistrict`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
