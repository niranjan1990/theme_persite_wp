-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 17, 2017 at 08:19 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `golf_wp_staging_mtbr`
--

-- --------------------------------------------------------

--
-- Table structure for table `profile_answers`
--

CREATE TABLE IF NOT EXISTS `profile_answers` (
`id` int(255) NOT NULL,
  `questionid` int(255) NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `profile_questions`
--

CREATE TABLE IF NOT EXISTS `profile_questions` (
`id` int(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(125) NOT NULL,
  `country` varchar(250) NOT NULL,
  `zip` varchar(11) NOT NULL,
  `gender` varchar(250) NOT NULL,
  `time_zone` varchar(250) NOT NULL,
  `daylight_time` tinyint(1) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_profile_answers`
--

CREATE TABLE IF NOT EXISTS `user_profile_answers` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `answerid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `answer` longtext,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `profile_answers`
--
ALTER TABLE `profile_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_questions`
--
ALTER TABLE `profile_questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile_answers`
--
ALTER TABLE `user_profile_answers`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profile_answers`
--
ALTER TABLE `profile_answers`
MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `profile_questions`
--
ALTER TABLE `profile_questions`
MODIFY `id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_profile_answers`
--
ALTER TABLE `user_profile_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
