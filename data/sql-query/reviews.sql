-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2017 at 10:44 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `golf_venice_new_feb_ver1`
--

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--
ALTER TABLE reviews
 ADD `pros` varchar(2500) DEFAULT NULL,
 ADD `cons` varchar(2500) DEFAULT NULL,
 ADD `year_purchased` varchar(25) DEFAULT NULL,
 ADD `price` varchar(25) DEFAULT NULL,
 ADD `product_type` varchar(25) DEFAULT NULL COMMENT 'New or Old';
