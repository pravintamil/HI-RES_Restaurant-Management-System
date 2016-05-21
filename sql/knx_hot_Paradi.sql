-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2016 at 01:44 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `knx_hot_Paradi`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `pincode` int(6) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  PRIMARY KEY (`pincode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category_1`
--

CREATE TABLE IF NOT EXISTS `category_1` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `desc` varchar(300) NOT NULL,
  `priority` int(20) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `category_1`
--

INSERT INTO `category_1` (`category_id`, `name`, `desc`, `priority`) VALUES
(1, 'Beer', 'Ice cold', 10),
(2, 'Rum', 'enjoy the drowsy', 7),
(3, 'Cool Drinks', 'chill', 7),
(4, 'Non-Alcoholic Drinks', 'Baby drinks', 8),
(5, 'Cigar', 'Smooky', 8),
(6, 'Wine collections', 'good for health if it is limited', 10);

-- --------------------------------------------------------

--
-- Table structure for table `customer_1`
--

CREATE TABLE IF NOT EXISTS `customer_1` (
  `customer_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `rand_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `rand_id` (`rand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `driver_1`
--

CREATE TABLE IF NOT EXISTS `driver_1` (
  `driver_id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  PRIMARY KEY (`driver_id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_1`
--

CREATE TABLE IF NOT EXISTS `product_1` (
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `item_desc` varchar(300) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `max_order` int(11) NOT NULL,
  `price` double NOT NULL,
  `category_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `meal_type` varchar(100) NOT NULL,
  `food_type` varchar(100) NOT NULL,
  `is_option_available` int(11) NOT NULL DEFAULT '0',
  `availability` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  KEY `categoryID` (`category_id`),
  KEY `tax_id` (`tax_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `product_1`
--

INSERT INTO `product_1` (`item_id`, `item_name`, `item_desc`, `priority`, `max_order`, `price`, `category_id`, `tax_id`, `meal_type`, `food_type`, `is_option_available`, `availability`) VALUES
(1, 'kingfisher premium beer (650ml)', 'ice cold', 9, 0, 260, 1, 0, 'lunch', 'veg', 0, 1),
(2, 'kingfisher Super Strong (650ml)', 'ice cold', 1, 0, 260, 1, 0, 'break_fast', 'veg', 0, 1),
(3, 'British Empior Superior(650ml)', 'ice cold', 7, 0, 255, 1, 0, 'break_fast', 'veg', 0, 1),
(4, 'kingfisher lehar(650ml)', 'chill', 7, 0, 260, 1, 0, 'break_fast', 'veg', 0, 1),
(5, 'Bacardi Apple original Rum(large-60ml)', 'Enjoy the hangover', 9, 0, 140, 2, 0, 'break_fast', 'veg', 0, 1),
(6, 'Bacardi Apple original Rum( small-30ml)', 'enjoy the hangover', 8, 0, 70, 2, 0, 'break_fast', 'veg', 0, 1),
(7, 'kingfisher Super Strong (650ml)', 'ice cold', 1, 0, 260, 1, 0, 'break_fast', 'veg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_1`
--

CREATE TABLE IF NOT EXISTS `sale_order_1` (
  `sale_order_id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'preparation',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_driver` tinyint(1) NOT NULL,
  `portal` varchar(100) DEFAULT 'own',
  `driver_id` int(10) DEFAULT NULL,
  `bill_paid` tinyint(1) DEFAULT '0',
  `special_notes` varchar(100) DEFAULT NULL,
  `table_id` int(11) DEFAULT NULL,
  `order_type` varchar(20) NOT NULL,
  `rand_id` varchar(20) DEFAULT NULL,
  `server_id` varchar(4) DEFAULT '1',
  PRIMARY KEY (`sale_order_id`),
  UNIQUE KEY `rand_id` (`rand_id`),
  KEY `customer_id` (`customer_id`),
  KEY `customer_id_2` (`customer_id`),
  KEY `customer_id_3` (`customer_id`),
  KEY `driver_id` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_line_1`
--

CREATE TABLE IF NOT EXISTS `sale_order_line_1` (
  `order_line_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_template_id` int(10) NOT NULL,
  `qty` int(10) NOT NULL,
  `uom` int(10) NOT NULL,
  `price` float NOT NULL,
  `discount` float DEFAULT NULL,
  `special_notes` varchar(100) DEFAULT NULL,
  `extra_ids` varchar(100) DEFAULT NULL,
  `TEST` varchar(255) NOT NULL,
  PRIMARY KEY (`order_line_id`),
  KEY `sale_order_line_ibfk_1` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `table_category_1`
--

CREATE TABLE IF NOT EXISTS `table_category_1` (
  `table_category_id` int(10) NOT NULL AUTO_INCREMENT,
  `table_category_name` varchar(100) NOT NULL,
  `no_of_tables` int(10) NOT NULL,
  PRIMARY KEY (`table_category_id`),
  UNIQUE KEY `table_category_name` (`table_category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `table_details_1`
--

CREATE TABLE IF NOT EXISTS `table_details_1` (
  `table_id` int(10) NOT NULL AUTO_INCREMENT,
  `table_category_id` int(10) DEFAULT NULL,
  `table_name` varchar(100) NOT NULL,
  `capacity` int(100) NOT NULL,
  PRIMARY KEY (`table_id`),
  UNIQUE KEY `table_name` (`table_name`),
  KEY `table_category_id_fk` (`table_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_1`
--
ALTER TABLE `product_1`
  ADD CONSTRAINT `category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `category_1` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_order_1`
--
ALTER TABLE `sale_order_1`
  ADD CONSTRAINT `customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer_1` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `driver_id_fk` FOREIGN KEY (`driver_id`) REFERENCES `driver_1` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sale_order_line_1`
--
ALTER TABLE `sale_order_line_1`
  ADD CONSTRAINT `sale_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `sale_order_1` (`sale_order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `table_details_1`
--
ALTER TABLE `table_details_1`
  ADD CONSTRAINT `table_category_id_fk` FOREIGN KEY (`table_category_id`) REFERENCES `table_category_1` (`table_category_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
