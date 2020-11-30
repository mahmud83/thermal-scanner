-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2020 at 03:48 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thermal_scanner`
--
CREATE DATABASE IF NOT EXISTS `thermal_scanner` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `thermal_scanner`;

-- --------------------------------------------------------

--
-- Table structure for table `detection_history`
--

CREATE TABLE `detection_history` (
  `id` int(11) NOT NULL,
  `ip` char(20) NOT NULL,
  `temperature` float NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detection_history`
--

INSERT INTO `detection_history` (`id`, `ip`, `temperature`, `created_on`) VALUES
(1, '192.168.10.3', 30.36, '2020-11-28 05:04:40'),
(2, '192.168.10.3', 31.65, '2020-11-28 05:05:24'),
(3, '192.168.10.3', 31.72, '2020-11-28 05:05:39'),
(4, '192.168.10.3', 31.99, '2020-11-28 05:06:04'),
(5, '192.168.10.3', 32.38, '2020-11-28 05:07:20'),
(6, '192.168.10.3', 32.05, '2020-11-28 05:09:27'),
(7, '192.168.10.3', 31.82, '2020-11-28 05:11:08'),
(8, '192.168.10.3', 30.64, '2020-11-28 05:11:19'),
(9, '192.168.10.3', 32.19, '2020-11-28 05:11:36'),
(10, '192.168.10.3', 32.88, '2020-11-28 05:11:47'),
(11, '192.168.10.3', 32.02, '2020-11-28 05:12:00'),
(12, '192.168.10.3', 32.63, '2020-11-28 05:12:10'),
(13, '192.168.10.3', 32.06, '2020-11-28 05:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `system_ack_log`
--

CREATE TABLE `system_ack_log` (
  `id` int(11) NOT NULL,
  `ip` char(20) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `system_log`
--

CREATE TABLE `system_log` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `ip` char(20) NOT NULL,
  `event` text NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `system_log_type`
--

CREATE TABLE `system_log_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` char(60) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `type`, `email`, `password`, `name`, `created_on`) VALUES
(1, 1, 'administrator@email.com', '$2y$12$l41HYZ8DgEHe2xUIGqfNKOCTwt.YKS/8Ls1DH4a969EsuFiRLgp2m', 'Administrator', '2020-11-25 23:42:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `created_on`) VALUES
(1, 'Administrator', '2020-11-25 23:40:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detection_history`
--
ALTER TABLE `detection_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_ack_log`
--
ALTER TABLE `system_ack_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_log`
--
ALTER TABLE `system_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `system_log_type`
--
ALTER TABLE `system_log_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detection_history`
--
ALTER TABLE `detection_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `system_ack_log`
--
ALTER TABLE `system_ack_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_log`
--
ALTER TABLE `system_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_log_type`
--
ALTER TABLE `system_log_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `system_log`
--
ALTER TABLE `system_log`
  ADD CONSTRAINT `system_log_ibfk_1` FOREIGN KEY (`type`) REFERENCES `system_log_type` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`type`) REFERENCES `user_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
