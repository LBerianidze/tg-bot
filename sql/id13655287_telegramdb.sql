-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2020 at 09:01 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id13655287_telegramdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `sarafan_reviews`
--

CREATE TABLE `sarafan_reviews` (
  `id` int(11) NOT NULL,
  `telegram_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `stage` int(11) NOT NULL,
  `content` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `post_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sarafan_users`
--

CREATE TABLE `sarafan_users` (
  `id` int(11) NOT NULL,
  `telegram_id` int(11) NOT NULL,
  `telegram_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `telegram_username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stage` int(11) NOT NULL DEFAULT 0,
  `has_access_next_stage` tinyint(4) NOT NULL DEFAULT 1,
  `last_notify_date` datetime DEFAULT NULL,
  `notify_count` int(11) NOT NULL DEFAULT 0,
  `register_date` datetime DEFAULT NULL,
  `step` int(11) NOT NULL DEFAULT 0,
  `first_take_date` datetime DEFAULT NULL,
  `first_done_date` datetime DEFAULT NULL,
  `second_take_date` datetime DEFAULT NULL,
  `second_done_date` datetime DEFAULT NULL,
  `third_take_date` datetime DEFAULT NULL,
  `third_done_date` datetime DEFAULT NULL,
  `last_action_date` datetime DEFAULT NULL,
  `last_action_type` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sarafan_reviews`
--
ALTER TABLE `sarafan_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sarafan_users`
--
ALTER TABLE `sarafan_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stage` (`stage`),
  ADD KEY `notify_count` (`notify_count`),
  ADD KEY `register_date` (`register_date`),
  ADD KEY `first_take_date` (`first_take_date`),
  ADD KEY `second_take_date` (`second_take_date`),
  ADD KEY `third_take_date` (`third_take_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sarafan_reviews`
--
ALTER TABLE `sarafan_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sarafan_users`
--
ALTER TABLE `sarafan_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
