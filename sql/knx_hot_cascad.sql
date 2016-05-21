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
-- Database: `knx_hot_cascad`
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

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`pincode`, `address`, `city`, `state`) VALUES
(641032, 'Othakkalmandapam', 'Coimbatore', 'Tamil Nadu');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `category_1`
--

INSERT INTO `category_1` (`category_id`, `name`, `desc`, `priority`) VALUES
(1, 'Veg', 'pure vegeterian', 10),
(2, 'Non veg', 'pure non veg', 10),
(3, 'starters', 'start with the better one', 9),
(4, 'cool drinks', 'ice cold', 9);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `customer_1`
--

INSERT INTO `customer_1` (`customer_id`, `name`, `email`, `phone`, `address`, `pincode`, `rand_id`) VALUES
(1, 'sam', '', '9685744858', '', '', '1WV81Yzo4h'),
(2, 'sam', '', '8574558696', '', '', 'AsIQvF00r2'),
(3, 'king', '', '8569684775', '', '', '110V58BGkp'),
(4, 'kabil', '', '8668574586', 'cbe', '641032', '06nICqCALT');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `driver_1`
--

INSERT INTO `driver_1` (`driver_id`, `name`, `phone`) VALUES
(1, 'sam', '7598149699'),
(2, 'boss', '7598149696'),
(3, 'jack', '9865465498');

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
(1, 'pongal', 'divine', 7, 0, 30, 1, 0, 'break_fast', 'veg', 0, 1),
(2, 'Idly(set of 3)', 'delisious', 10, 0, 20, 1, 0, 'break_fast, dinner', 'veg', 0, 1),
(3, 'chicken Biriyani', 'tasty', 9, 0, 80, 2, 0, 'lunch, dinner', 'non-veg', 0, 1),
(4, 'mutton biriyani', 'tasty', 8, 0, 180, 2, 0, 'lunch, dinner', 'non-veg', 0, 1),
(5, 'sweet corn soup', 'start with the better one', 7, 0, 35, 3, 0, 'break_fast, lunch, snacks, dinner', 'veg', 0, 1),
(6, 'appy fish(200ml)', 'enjoy the apple flavour', 7, 0, 25, 4, 0, 'lunch, snacks, dinner', 'veg', 0, 1),
(7, 'maaza(200ml)', 'enjoy the taste of mango', 7, 0, 25, 4, 0, 'lunch, snacks, dinner', 'veg', 0, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `sale_order_1`
--

INSERT INTO `sale_order_1` (`sale_order_id`, `customer_id`, `status`, `order_date`, `is_driver`, `portal`, `driver_id`, `bill_paid`, `special_notes`, `table_id`, `order_type`, `rand_id`, `server_id`) VALUES
(1, 1, 'confirm', '2016-03-08 07:51:26', 0, 'Own', 0, 0, '', 1, 'dine_in', 'Q41RkkhUaF', '1'),
(2, 1, 'm(3)', '2016-03-08 07:55:30', 0, 'Own', 0, 0, '', 1, 'dine_in', '0oC4gXieRN', '1'),
(3, 1, 'confirm', '2016-03-08 07:55:49', 0, 'Own', 0, 0, '', 2, 'dine_in', 'AqMCBngY9F', '1'),
(4, 1, 'confirm', '2016-03-08 08:02:27', 0, 'Own', 0, 1, '', 1, 'dine_in', '0YVVIAmUFG', '1'),
(5, 1, 'm(4)', '2016-03-08 08:02:49', 0, 'Own', 0, 0, '', 3, 'dine_in', 'd7eabhehzT', '1'),
(6, 2, 'preperation', '2016-03-08 08:11:14', 0, 'Own', 0, 0, '', 0, 'collection', 'kPcWKlvjtc', '1'),
(7, 3, 'preperation', '2016-03-08 08:13:00', 0, 'Own', 0, 0, '', 0, 'collection', '12lUChGCOC', '1'),
(8, 1, 'confirm', '2016-03-08 08:53:18', 0, 'Own', 0, 0, '', 1, 'dine_in', '1gsGbbYAZw', '1'),
(9, 4, 'delivered', '2016-03-08 09:12:10', 1, 'Own', 3, 0, '', 0, 'delivery', 'Sd9v7glIP7', '1'),
(10, 1, 'cancel', '2016-03-08 09:15:29', 0, 'Own', 0, 0, '', 1, 'dine_in', 'WD8oNdDI8K', '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sale_order_line_1`
--

INSERT INTO `sale_order_line_1` (`order_line_id`, `order_id`, `product_id`, `product_name`, `product_template_id`, `qty`, `uom`, `price`, `discount`, `special_notes`, `extra_ids`, `TEST`) VALUES
(1, 1, 1, 'pongal', 1, 1, 1, 30, 0, '', '', ''),
(2, 1, 2, 'Idly(set of 3)', 2, 1, 1, 20, 0, '', '', ''),
(3, 3, 1, 'pongal', 1, 1, 1, 30, 0, '', '', ''),
(4, 3, 2, 'Idly(set of 3)', 2, 1, 1, 20, 0, '', '', ''),
(5, 3, 3, 'chicken Biriyani', 3, 1, 1, 80, 0, '', '', ''),
(6, 3, 4, 'mutton biriyani', 4, 1, 1, 180, 0, '', '', ''),
(7, 4, 1, 'pongal', 1, 1, 1, 30, 0, '', '', ''),
(8, 4, 2, 'Idly(set of 3)', 2, 1, 1, 20, 0, '', '', ''),
(9, 4, 3, 'chicken Biriyani', 3, 1, 1, 80, 0, '', '', ''),
(10, 4, 5, 'sweet corn soup', 5, 1, 1, 35, 0, '', '', ''),
(11, 4, 6, 'appy fish(200ml)', 6, 1, 1, 25, 0, '', '', ''),
(12, 6, 1, 'pongal', 1, 3, 1, 90, 0, '', '', ''),
(13, 7, 1, 'pongal', 1, 2, 1, 60, 0, '', '', ''),
(14, 7, 2, 'Idly(set of 3)', 2, 2, 1, 40, 0, '', '', ''),
(15, 8, 1, 'pongal', 1, 1, 1, 30, 0, '', '', ''),
(16, 9, 3, 'chicken Biriyani', 3, 1, 1, 80, 0, '', '', ''),
(17, 9, 4, 'mutton biriyani', 4, 1, 1, 180, 0, '', '', ''),
(18, 10, 1, 'pongal', 1, 1, 1, 30, 0, '', '', ''),
(19, 10, 2, 'Idly(set of 3)', 2, 1, 1, 20, 0, '', '', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `table_category_1`
--

INSERT INTO `table_category_1` (`table_category_id`, `table_category_name`, `no_of_tables`) VALUES
(1, 'first floor', 20),
(2, 'second floor', 20);

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
  KEY `table_category_id_fk` (`table_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `table_details_1`
--

INSERT INTO `table_details_1` (`table_id`, `table_category_id`, `table_name`, `capacity`) VALUES
(1, 1, 'T1', 10),
(2, 1, 'T2', 10),
(3, 2, 'T1', 5),
(4, 2, 'T2', 5),
(5, 2, 'T3', 5),
(6, 2, 'T4', 5);

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
