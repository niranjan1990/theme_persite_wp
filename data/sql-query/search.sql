-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2017 at 10:09 AM
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
-- Table structure for table `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `productid` int(255) NOT NULL,
  `channelid` int(255) NOT NULL,
  `node_level` int(255) NOT NULL,
  `manufacturer_name` varchar(255) NOT NULL,
  `category_path` varchar(255) NOT NULL,
  `search_term` varchar(255) NOT NULL,
  `display_term` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `search`
--
ALTER TABLE `search`
 ADD KEY `search_term` (`search_term`,`display_term`);

INSERT INTO search(channelid,node_level,manufacturer_name,category_path,search_term,display_term,url) SELECT distinct p.channelid,cc.node_level,m.url_safe_manufacturer_name,'',m.manufacturer_name,m.manufacturer_name,concat('brand/',m.url_safe_manufacturer_name,'.html') FROM products p, manufacturers m,channel_categories cc where p.manufacturerid=m.manufacturerid and  p.categoryid=cc.categoryid and p.channelid=7;
	
INSERT INTO search(productid,channelid,node_level,manufacturer_name,category_path,search_term,display_term,url) SELECT p.productid,p.channelid,cc.node_level,m.url_safe_manufacturer_name,cc.category_path,p.product_name,concat(m.manufacturer_name,' ',p.product_name),concat(m.url_safe_manufacturer_name,'/',c.url_safe_category_name,'/',p.url_safe_product_name,'.html') FROM products p, manufacturers m,categories c,channel_categories cc where p.manufacturerid=m.manufacturerid and  p.categoryid=c.categoryid and p.categoryid=cc.categoryid and p.channelid <> 82;
	
INSERT INTO search(productid,channelid,node_level,manufacturer_name,category_path,search_term,display_term,url) SELECT p.productid,p.channelid,cc.node_level,m.url_safe_manufacturer_name,cc.category_path,concat(m.manufacturer_name,' ',p.product_name),concat(m.manufacturer_name,' ',p.product_name),concat(m.url_safe_manufacturer_name,'/',c.url_safe_category_name,'/',p.url_safe_product_name,'.html') FROM products p, manufacturers m,categories c,channel_categories cc where p.manufacturerid=m.manufacturerid and  p.categoryid=c.categoryid and p.categoryid=cc.categoryid and p.channelid <> 82;
	
INSERT INTO search (productid,channelid,node_level,manufacturer_name,category_path,search_term,display_term,url) SELECT p.productid,p.channelid,cc.node_level,m.url_safe_manufacturer_name,cc.category_path,concat(p.product_name,',',(select attribute_value from attribute_values where attributeid = 69 and productid = p.productid),',',c.category_name),concat(p.product_name,',',(select attribute_value from attribute_values where attributeid = 69 and productid = p.productid),',',(select attribute_value from attribute_values where attributeid = 70 and productid = p.productid)),concat(REPLACE(c.category_name, ' ','-' ),'/',REPLACE((select attribute_value from attribute_values where attributeid = 69 and productid = p.productid), ' ','-' ),'/',p.url_safe_product_name,'.html') FROM products p, manufacturers m,categories c,channel_categories cc where p.manufacturerid=m.manufacturerid and p.categoryid=c.categoryid and p.categoryid=cc.categoryid and p.channelid = 82;
	
delete from search where channelid = 82 and productid not in (select p.productid from products p, attribute_values a where p.productid = a.productid and a.attributeid = 69);
	
delete from search where channelid = 82 and productid not in (select p.productid from products p, attribute_values a where p.productid = a.productid and a.attributeid = 70);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
