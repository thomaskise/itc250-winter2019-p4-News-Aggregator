-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 25, 2019 at 07:30 AM
-- Server version: 5.7.19-log
-- PHP Version: 7.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `NewsAggregator`
--

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`id`, `category_name`) VALUES
(1, 'Uncategorized');

-- --------------------------------------------------------

--
-- Table structure for table `Entries`
--

CREATE TABLE IF NOT EXISTS `Entries` (
  `id` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `url` text NOT NULL,
  `author` text,
  `summary` text,
  `content` text,
  `published` timestamp NOT NULL,
  `updated` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `is_favorite` tinyint(1) NOT NULL DEFAULT '0',
  `image_url` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Feed`
--

CREATE TABLE IF NOT EXISTS `Feed` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `feed_url` varchar(600) NOT NULL,
  `website_url` varchar(600) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `last_modified` timestamp NOT NULL,
  `subscriptions_count` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Feed`
--

INSERT INTO `Feed` (`id`, `category`, `name`, `feed_url`, `website_url`, `description`, `created_at`, `updated_at`, `last_modified`, `subscriptions_count`) VALUES
(1, 1, 'Engadget RSS Feed', 'https://www.engadget.com/rss.xml', 'https://www.engadget.com', 'Engadget is a web magazine with obsessive daily coverage of everything new in gadgets and consumer electronics', '2019-02-24 12:04:08', '2019-02-24 12:04:08', '2019-02-24 12:04:08', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Entries`
--
ALTER TABLE `Entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Feed`
--
ALTER TABLE `Feed`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Entries`
--
ALTER TABLE `Entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Feed`
--
ALTER TABLE `Feed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
