-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 08:58 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `labour_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('full','half','custom') DEFAULT 'full',
  `custom_amount` decimal(10,2) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `confirmed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('full','half','custom') DEFAULT 'full',
  `attendance_type` enum('full','half','custom') DEFAULT 'full',
  `is_removed` tinyint(1) DEFAULT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `labour_id`, `date`, `status`, `custom_amount`, `group_id`, `contractor_id`, `confirmed_at`, `type`, `attendance_type`, `is_removed`, `bonus`) VALUES
(1, 5, '2025-04-07', '', NULL, NULL, 4, '2025-04-07 14:16:44', 'full', 'full', NULL, '0.00'),
(2, 6, '2025-04-07', '', NULL, NULL, 4, '2025-04-07 14:16:48', 'full', 'full', NULL, '0.00'),
(3, 7, '2025-04-07', '', '566.00', NULL, 4, '2025-04-07 14:21:53', 'custom', 'full', NULL, '0.00'),
(4, 1, '2025-04-07', '', '200.00', NULL, 4, '2025-04-07 14:22:00', 'half', 'full', NULL, '0.00'),
(5, 4, '2025-04-07', '', NULL, NULL, 4, '2025-04-07 14:22:00', 'custom', 'full', NULL, '0.00'),
(10, 4, '2025-04-08', '', NULL, NULL, 4, '2025-04-07 19:32:56', 'full', 'full', 1, '0.00'),
(11, 5, '2025-04-08', '', '169.50', NULL, 4, '2025-04-07 19:33:04', 'half', 'full', NULL, '-34.00'),
(12, 8, '2025-04-08', 'full', NULL, NULL, 4, '2025-04-08 11:34:16', 'full', 'full', NULL, '0.00'),
(13, 1, '2025-04-11', 'full', NULL, NULL, 4, '2025-04-11 16:48:00', 'full', 'full', NULL, '0.00'),
(15, 11, '2025-04-11', 'full', NULL, NULL, 4, '2025-04-11 16:48:00', 'full', 'full', NULL, '0.00'),
(18, 8, '2025-04-12', 'full', NULL, NULL, 4, '2025-04-11 18:47:21', 'full', 'full', NULL, '-13.00'),
(19, 1, '2025-04-12', 'full', NULL, NULL, 4, '2025-04-11 19:19:26', 'full', 'full', NULL, '-200.00'),
(20, 4, '2025-04-12', 'full', NULL, NULL, 4, '2025-04-11 19:20:39', 'full', 'full', NULL, '0.00'),
(21, 5, '2025-04-12', 'full', NULL, NULL, 4, '2025-04-11 19:20:39', 'full', 'full', NULL, '12.00'),
(23, 12, '2025-04-12', 'full', NULL, NULL, 4, '2025-04-12 14:42:18', 'full', 'full', NULL, '0.00'),
(24, 1, '2025-06-07', 'full', NULL, NULL, 4, '2025-06-07 15:09:49', 'full', 'full', NULL, '0.00'),
(25, 5, '2025-06-07', 'full', NULL, NULL, 4, '2025-06-07 15:09:49', 'full', 'full', NULL, '-10.00'),
(26, 11, '2025-06-09', 'full', NULL, NULL, 4, '2025-06-09 08:01:54', 'full', 'full', NULL, '0.00'),
(27, 12, '2025-06-09', 'full', NULL, NULL, 4, '2025-06-09 08:01:54', 'full', 'full', NULL, '0.00'),
(28, 14, '2025-06-09', 'full', NULL, NULL, 4, '2025-06-09 08:01:54', 'full', 'full', NULL, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_groups`
--

CREATE TABLE `attendance_groups` (
  `id` int(11) NOT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance_groups`
--

INSERT INTO `attendance_groups` (`id`, `contractor_id`, `group_name`, `created_at`) VALUES
(1, 4, 'raja ka group', '2025-04-07 08:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_temp`
--

CREATE TABLE `attendance_temp` (
  `id` int(11) NOT NULL,
  `labour_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('full','half','custom') DEFAULT 'full',
  `custom_amount` decimal(10,2) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bonus`
--

CREATE TABLE `bonus` (
  `id` int(11) NOT NULL,
  `labour_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contractor`
--

CREATE TABLE `contractor` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contractor`
--

INSERT INTO `contractor` (`id`, `name`, `mobile`, `password`, `photo`) VALUES
(1, 'pratik sarkate', '9359558166', '$2y$10$PqTZMOjkHJnAbt5e4FXqsemrItYFTZ0hjLqfd90dauCNYeat4IH1S', NULL),
(2, 'p', '9359558163', '$2y$10$oxOifxNbKONM5MeiW9GK3.5OI66UCCcG3tynpRxopTwV5gxoJFh.S', NULL),
(3, 's', '9359558167', '$2y$10$RLe8yyNViSFpwXaG/pUULegE6VGmWY2737S7m7ujt.l93cFBXJn/6', NULL),
(4, 'PR@T!K S', 'admin', '$2y$10$ys2skBcams2zJlNuYAdMp.1JzPR0viquwi7jw6FGd9aRd56BziSia', ''),
(5, 'pratik s', '9359553454', '$2y$10$ZFsG/DXykJ4P3r2HuT3JSeEpqTfxxIsAYkRys4otKin5D7tb1Jd7G', NULL),
(6, 'raja', '9359550000', '$2y$10$jY7iU9Uzv2x4nSAIPtIT4.E56ypzcPFDnaUgrnLB9ZmlEuJka8ytm', NULL),
(7, 'vasanta', '9359558778', '$2y$10$lb27CcUPcz3SNLyPMocRpux.8p6Vjr3.QniK.yDPrP.fff2HT/9jW', NULL),
(8, 'pratik s', '9359558888', '$2y$10$W6U/TUfegkqd/jCv6o.khO3PvEj9/lixzS8j4VfsBlf/aPN9AECqe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `labours`
--

CREATE TABLE `labours` (
  `id` int(11) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `salary_type` varchar(20) DEFAULT NULL,
  `salary_amount` decimal(10,2) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `timestamp` datetime DEFAULT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labours`
--

INSERT INTO `labours` (`id`, `contractor_id`, `name`, `mobile`, `password`, `role`, `salary_type`, `salary_amount`, `join_date`, `profile_picture`, `is_deleted`, `timestamp`, `bonus`) VALUES
(1, 4, 'harshad1', '0000000001', '$2y$10$I50npP.0QtOBLTsWkywtL.aRUby2bsduumvchapF9v2PW0xc3dwdK', 'superviser', 'प्रतिदिन', '418.00', '2025-04-07', NULL, 0, NULL, '0.00'),
(4, 4, 'pappu', '2233333333', '$2y$10$AIAhqjwoYej5ulJKOuf5yON8HRVF4ADipNdNNGYUaaZ5aMQu7FwDu', 'helper', 'प्रतिदिन', '400.00', NULL, NULL, 0, '2025-04-06 23:12:22', '0.00'),
(5, 4, 'rajuuu', '1211212222', '$2y$10$NftHFLy/IGfkpM1kB3zgSOHsk5mWXCSlkdkeDumDXcVHLWOq7zdu6', 'majdur', 'Daily', '339.00', NULL, NULL, 0, '2025-04-06 23:19:47', '0.00'),
(6, 4, 'rajuuu', '1211212222', '$2y$10$6kWZIEfQLXlftIYLMWqrzeL8/lCvwH7gBqPkXzhVwo79OJTgCq1nC', 'majdur', 'Daily', '339.00', NULL, NULL, 1, '2025-04-06 23:22:39', '0.00'),
(7, 4, 'rajuuu', '1211212222', '$2y$10$Q2vQoP5wwXaQvLGSGCZEAu0Dn7nlFUIrbspyZCTKt8FyhSWmn1iq.', 'majdur', 'Daily', '339.00', NULL, NULL, 1, '2025-04-06 23:23:54', '0.00'),
(8, 4, 'pratikk s', '8392929292', '$2y$10$1zMhPH4L.WaVa4aQs2nIOeMkcNuZYHaNBl0c1q0rLEiII.LkLLi9a', 'labour', 'प्रतिदिन', '400.00', NULL, NULL, 0, '2025-04-08 11:19:29', '0.00'),
(9, 4, 'pratik dev', '0000000000', '$2y$10$M3ECGoAI0ApcPUPoFTG0/OZufpcBEHdWEh/AkEeF5h6TPyB6Ae5l2', 'ooo', 'प्रतिदिन', '400.00', NULL, NULL, 0, '2025-04-08 11:37:57', '0.00'),
(10, 4, 'pratik', '0000000000', '$2y$10$jCff1NPNyOqf8HqrobsfPOlP2RVkaBBGIFGZNBP5aDPRjSwioe9Yu', '000', 'Daily', '999.00', NULL, NULL, 0, '2025-04-08 11:38:21', '0.00'),
(11, 4, 'raj', '0000000000', '$2y$10$A8H.h0RIaRJYXDzD9xxuCeUZ3YuvmBTZZP4tLySfKgvACTvJ/jm0e', '0000', 'Daily', '222.00', NULL, NULL, 1, '2025-04-08 12:32:09', '0.00'),
(12, 4, '0000000000', '0000000009', '$2y$10$O4H1Pg2Vdlf93UkGeEPE9Op/99ME3oZcv74/nPmDmpWbTKFZ7sd1C', '0000', 'Daily', '-1.00', NULL, NULL, 1, '2025-04-08 12:37:05', '0.00'),
(13, 7, 'golya', '3456789098', '$2y$10$JSGw5zCI4bouc9gi5kjTi.mAWugv54xEhbbcp9iUITO/Qj9A8BDue', 'mistri', 'प्रतिदिन', '820.00', NULL, NULL, 0, '2025-04-12 16:10:02', '0.00'),
(14, 4, 'vikas', '2345678903', '$2y$10$JmZl7otQEWJAPynFSnCBTuOC1XX/GEX.63Fxr1qZ16bnWlmuRBOoG', 'mistru', 'Daily', '800.00', NULL, NULL, 0, '2025-04-12 16:40:20', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `labour_groups`
--

CREATE TABLE `labour_groups` (
  `id` int(11) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labour_groups`
--

INSERT INTO `labour_groups` (`id`, `contractor_id`, `group_name`, `created_at`) VALUES
(4, 4, 'raja ka group', '2025-04-07 13:24:52'),
(5, 4, 'jiwndwnid', '2025-04-07 13:25:05'),
(6, 4, 'ok', '2025-04-07 13:25:53'),
(7, 4, '123', '2025-04-07 13:36:26'),
(8, 7, 'avhale pahunyach ghar', '2025-04-12 14:10:35'),
(9, 4, 'gautam home', '2025-04-12 14:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `labour_group_members`
--

CREATE TABLE `labour_group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `labour_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `labour_group_members`
--

INSERT INTO `labour_group_members` (`id`, `group_id`, `labour_id`) VALUES
(21, 4, 1),
(22, 4, 4),
(23, 5, 5),
(24, 5, 6),
(25, 7, 1),
(26, 4, 5),
(27, 8, 13),
(28, 9, 1),
(29, 9, 6),
(30, 9, 8),
(31, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `labour_id` int(11) NOT NULL,
  `total_amount` float NOT NULL,
  `payed_amount` float NOT NULL,
  `remaining_amount` float NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `labour_id`, `total_amount`, `payed_amount`, `remaining_amount`, `from_date`, `to_date`, `created_at`, `note`) VALUES
(1, 1, 1047, 500, 547, '2024-01-01', '2025-04-12', '2025-04-12 09:54:11', 'Test payment'),
(2, 1, 547, 500, 47, '2025-04-13', '2025-04-12', '2025-04-12 09:55:17', 'Test payment'),
(3, 1, 547, 500, 47, '2025-04-13', '2025-04-12', '2025-04-12 09:55:18', 'Test payment'),
(4, 1, 47, 500, -453, '2025-04-13', '2025-04-12', '2025-04-12 09:55:19', 'Test payment'),
(5, 1, -453, 500, -953, '2025-04-13', '2025-04-12', '2025-04-12 09:55:21', 'Test payment'),
(6, 1, -953, 500, -1453, '2025-04-13', '2025-04-12', '2025-04-12 09:55:23', 'Test payment'),
(8, 4, 800, 50, 750, '2024-01-01', '2025-04-12', '2025-04-12 10:02:47', 'Test payment'),
(9, 4, 750, 50, 700, '2025-04-13', '2025-04-12', '2025-04-12 10:03:04', 'Test payment'),
(10, 4, 750, 50, 700, '2025-04-13', '2025-04-12', '2025-04-12 10:03:12', 'Test payment'),
(11, 4, 700, 50, 650, '2025-04-13', '2025-04-12', '2025-04-12 10:03:14', 'Test payment'),
(12, 4, 650, 50, 600, '2025-04-13', '2025-04-12', '2025-04-12 10:03:17', 'Test payment'),
(13, 8, 787, 0, 787, '2024-01-01', '2025-04-12', '2025-04-12 11:09:26', 'salary'),
(14, 8, 787, 0, 787, '2025-04-13', '2025-04-12', '2025-04-12 11:09:40', 'salary'),
(15, 8, 787, 0, 787, '2025-04-13', '2025-04-12', '2025-04-12 11:10:10', '50 diyA'),
(16, 8, 787, 50, 737, '2025-04-13', '2025-04-12', '2025-04-12 11:14:29', 'dele re bho'),
(17, 8, 737, 50, 687, '2025-04-13', '2025-04-12', '2025-04-12 11:16:31', 'done'),
(18, 8, 687, 55, 632, '2025-04-13', '2025-06-07', '2025-06-07 22:25:29', 'dele'),
(19, 8, 632, 111, 521, '2025-06-08', '2025-06-09', '2025-06-09 13:11:48', ''),
(20, 8, 521, 111, 410, '2025-06-10', '2025-06-09', '2025-06-09 13:15:57', ''),
(21, 14, 0, 100, -100, '2024-01-01', '2025-06-09', '2025-06-09 13:28:46', ''),
(22, 5, 815.5, 110, 705.5, '2024-01-01', '2025-06-11', '2025-06-11 09:02:19', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_groups`
--
ALTER TABLE `attendance_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_temp`
--
ALTER TABLE `attendance_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonus`
--
ALTER TABLE `bonus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contractor`
--
ALTER TABLE `contractor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`);

--
-- Indexes for table `labours`
--
ALTER TABLE `labours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractor_id` (`contractor_id`);

--
-- Indexes for table `labour_groups`
--
ALTER TABLE `labour_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labour_group_members`
--
ALTER TABLE `labour_group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labour_id` (`labour_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `attendance_groups`
--
ALTER TABLE `attendance_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance_temp`
--
ALTER TABLE `attendance_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bonus`
--
ALTER TABLE `bonus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contractor`
--
ALTER TABLE `contractor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `labours`
--
ALTER TABLE `labours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `labour_groups`
--
ALTER TABLE `labour_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `labour_group_members`
--
ALTER TABLE `labour_group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `labours`
--
ALTER TABLE `labours`
  ADD CONSTRAINT `labours_ibfk_1` FOREIGN KEY (`contractor_id`) REFERENCES `contractor` (`id`),
  ADD CONSTRAINT `labours_ibfk_2` FOREIGN KEY (`contractor_id`) REFERENCES `contractor` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`labour_id`) REFERENCES `labours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- DEMO USER DATA INSERTED

INSERT INTO `contractor` (`id`, `name`, `mobile`, `password`, `photo`) VALUES
(9, 'Pratik', '9876543210', 'pratik', NULL);

INSERT INTO `labour` (`id`, `contractor_id`, `name`, `mobile`, `daily_salary`) VALUES
(1, 9, 'Raju', '9852569443', 800),
(2, 9, 'Akash', '9845229077', 700),
(3, 9, 'Vijay', '9844486701', 600),
(4, 9, 'Suresh', '9874719404', 700),
(5, 9, 'Mahesh', '9825130366', 600),
(6, 9, 'Ganesh', '9810424193', 800),
(7, 9, 'Rohit', '9895565266', 500),
(8, 9, 'Karan', '9854726599', 500),
(9, 9, 'Amit', '9833049005', 800),
(10, 9, 'Pintu', '9848156237', 600);

INSERT INTO `attendance`
(`id`, `labour_id`, `date`, `status`, `custom_amount`, `group_id`, `contractor_id`, `confirmed_at`, `type`, `attendance_type`, `is_removed`, `bonus`) VALUES
(29, 1, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(30, 2, '2026-04-01', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(31, 3, '2026-04-01', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(32, 4, '2026-04-01', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(33, 5, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(34, 6, '2026-04-01', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(35, 7, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(36, 8, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(37, 9, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(38, 10, '2026-04-01', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(39, 1, '2026-04-02', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(40, 2, '2026-04-02', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(41, 3, '2026-04-02', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(42, 4, '2026-04-02', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(43, 5, '2026-04-02', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(44, 6, '2026-04-02', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(45, 7, '2026-04-02', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(46, 8, '2026-04-02', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(47, 9, '2026-04-02', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(48, 10, '2026-04-02', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(49, 1, '2026-04-03', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(50, 2, '2026-04-03', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(51, 3, '2026-04-03', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(52, 4, '2026-04-03', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(53, 5, '2026-04-03', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(54, 6, '2026-04-03', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(55, 7, '2026-04-03', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(56, 8, '2026-04-03', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(57, 9, '2026-04-03', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(58, 10, '2026-04-03', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(59, 1, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(60, 2, '2026-04-04', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(61, 3, '2026-04-04', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(62, 4, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(63, 5, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(64, 6, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(65, 7, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(66, 8, '2026-04-04', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(67, 9, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(68, 10, '2026-04-04', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(69, 1, '2026-04-05', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(70, 2, '2026-04-05', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(71, 3, '2026-04-05', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(72, 4, '2026-04-05', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(73, 5, '2026-04-05', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(74, 6, '2026-04-05', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(75, 7, '2026-04-05', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(76, 8, '2026-04-05', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(77, 9, '2026-04-05', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(78, 10, '2026-04-05', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(79, 1, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(80, 2, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(81, 3, '2026-04-06', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(82, 4, '2026-04-06', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(83, 5, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(84, 6, '2026-04-06', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(85, 7, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(86, 8, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(87, 9, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(88, 10, '2026-04-06', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(89, 1, '2026-04-07', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(90, 2, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(91, 3, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(92, 4, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(93, 5, '2026-04-07', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(94, 6, '2026-04-07', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(95, 7, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(96, 8, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(97, 9, '2026-04-07', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(98, 10, '2026-04-07', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(99, 1, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(100, 2, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(101, 3, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(102, 4, '2026-04-08', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(103, 5, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(104, 6, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(105, 7, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(106, 8, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(107, 9, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(108, 10, '2026-04-08', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(109, 1, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(110, 2, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(111, 3, '2026-04-09', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(112, 4, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(113, 5, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(114, 6, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(115, 7, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(116, 8, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(117, 9, '2026-04-09', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(118, 10, '2026-04-09', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(119, 1, '2026-04-10', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(120, 2, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(121, 3, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(122, 4, '2026-04-10', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(123, 5, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(124, 6, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(125, 7, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(126, 8, '2026-04-10', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(127, 9, '2026-04-10', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(128, 10, '2026-04-10', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(129, 1, '2026-04-11', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(130, 2, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(131, 3, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(132, 4, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(133, 5, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(134, 6, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(135, 7, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(136, 8, '2026-04-11', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(137, 9, '2026-04-11', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(138, 10, '2026-04-11', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(139, 1, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(140, 2, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(141, 3, '2026-04-12', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(142, 4, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(143, 5, '2026-04-12', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(144, 6, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(145, 7, '2026-04-12', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(146, 8, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(147, 9, '2026-04-12', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(148, 10, '2026-04-12', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(149, 1, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(150, 2, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(151, 3, '2026-04-13', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(152, 4, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(153, 5, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(154, 6, '2026-04-13', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(155, 7, '2026-04-13', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(156, 8, '2026-04-13', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(157, 9, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(158, 10, '2026-04-13', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(159, 1, '2026-04-14', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(160, 2, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(161, 3, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(162, 4, '2026-04-14', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(163, 5, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(164, 6, '2026-04-14', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(165, 7, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(166, 8, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(167, 9, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(168, 10, '2026-04-14', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(169, 1, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(170, 2, '2026-04-15', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(171, 3, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(172, 4, '2026-04-15', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(173, 5, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(174, 6, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(175, 7, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(176, 8, '2026-04-15', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(177, 9, '2026-04-15', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(178, 10, '2026-04-15', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(179, 1, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(180, 2, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(181, 3, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(182, 4, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(183, 5, '2026-04-16', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(184, 6, '2026-04-16', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(185, 7, '2026-04-16', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(186, 8, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(187, 9, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(188, 10, '2026-04-16', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(189, 1, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(190, 2, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(191, 3, '2026-04-17', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(192, 4, '2026-04-17', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(193, 5, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(194, 6, '2026-04-17', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(195, 7, '2026-04-17', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(196, 8, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(197, 9, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(198, 10, '2026-04-17', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(199, 1, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(200, 2, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(201, 3, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(202, 4, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(203, 5, '2026-04-18', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(204, 6, '2026-04-18', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(205, 7, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(206, 8, '2026-04-18', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(207, 9, '2026-04-18', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(208, 10, '2026-04-18', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(209, 1, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(210, 2, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(211, 3, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(212, 4, '2026-04-19', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(213, 5, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(214, 6, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(215, 7, '2026-04-19', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(216, 8, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(217, 9, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(218, 10, '2026-04-19', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(219, 1, '2026-04-20', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(220, 2, '2026-04-20', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(221, 3, '2026-04-20', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(222, 4, '2026-04-20', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(223, 5, '2026-04-20', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(224, 6, '2026-04-20', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(225, 7, '2026-04-20', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(226, 8, '2026-04-20', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(227, 9, '2026-04-20', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(228, 10, '2026-04-20', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(229, 1, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(230, 2, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(231, 3, '2026-04-21', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(232, 4, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(233, 5, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(234, 6, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(235, 7, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(236, 8, '2026-04-21', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(237, 9, '2026-04-21', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(238, 10, '2026-04-21', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(239, 1, '2026-04-22', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(240, 2, '2026-04-22', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(241, 3, '2026-04-22', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(242, 4, '2026-04-22', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(243, 5, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(244, 6, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(245, 7, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(246, 8, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(247, 9, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(248, 10, '2026-04-22', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(249, 1, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(250, 2, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(251, 3, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(252, 4, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(253, 5, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(254, 6, '2026-04-23', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(255, 7, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(256, 8, '2026-04-23', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(257, 9, '2026-04-23', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(258, 10, '2026-04-23', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(259, 1, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(260, 2, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(261, 3, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(262, 4, '2026-04-24', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(263, 5, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(264, 6, '2026-04-24', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(265, 7, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(266, 8, '2026-04-24', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(267, 9, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(268, 10, '2026-04-24', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(269, 1, '2026-04-25', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(270, 2, '2026-04-25', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(271, 3, '2026-04-25', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(272, 4, '2026-04-25', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(273, 5, '2026-04-25', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(274, 6, '2026-04-25', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(275, 7, '2026-04-25', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(276, 8, '2026-04-25', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(277, 9, '2026-04-25', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(278, 10, '2026-04-25', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(279, 1, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(280, 2, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(281, 3, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(282, 4, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(283, 5, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(284, 6, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(285, 7, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(286, 8, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(287, 9, '2026-04-26', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(288, 10, '2026-04-26', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(289, 1, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(290, 2, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(291, 3, '2026-04-27', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(292, 4, '2026-04-27', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '20.00'),
(293, 5, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(294, 6, '2026-04-27', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(295, 7, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(296, 8, '2026-04-27', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(297, 9, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(298, 10, '2026-04-27', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(299, 1, '2026-04-28', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(300, 2, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(301, 3, '2026-04-28', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(302, 4, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(303, 5, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(304, 6, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(305, 7, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(306, 8, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(307, 9, '2026-04-28', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(308, 10, '2026-04-28', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(309, 1, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(310, 2, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(311, 3, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(312, 4, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '0.00'),
(313, 5, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(314, 6, '2026-04-29', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(315, 7, '2026-04-29', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(316, 8, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(317, 9, '2026-04-29', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(318, 10, '2026-04-29', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '50.00'),
(319, 1, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(320, 2, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(321, 3, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(322, 4, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(323, 5, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '50.00'),
(324, 6, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00'),
(325, 7, '2026-04-30', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '-10.00'),
(326, 8, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '-10.00'),
(327, 9, '2026-04-30', 'half', NULL, NULL, 9, NOW(), 'half', 'half', NULL, '20.00'),
(328, 10, '2026-04-30', 'full', NULL, NULL, 9, NOW(), 'full', 'full', NULL, '0.00');

INSERT INTO `payments`
(`id`, `labour_id`, `total_amount`, `payed_amount`, `remaining_amount`, `from_date`, `to_date`, `created_at`, `note`) VALUES
(23, 1, 20800, 20218, 582, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(24, 2, 18200, 17357, 843, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(25, 3, 15600, 12830, 2770, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(26, 4, 18200, 16795, 1405, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(27, 5, 15600, 13650, 1950, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(28, 6, 20800, 19864, 936, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(29, 7, 13000, 12041, 959, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(30, 8, 13000, 10432, 2568, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(31, 9, 20800, 17949, 2851, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment'),
(32, 10, 15600, 14960, 640, '2026-04-01', '2026-04-30', NOW(), 'Monthly payment');
