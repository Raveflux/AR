-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 08:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_rewards`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `earned_badges`
--

CREATE TABLE `earned_badges` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_codes`
--

CREATE TABLE `reward_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `is_redeemed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward_codes`
--

INSERT INTO `reward_codes` (`id`, `code`, `is_redeemed`, `created_at`) VALUES
(1, 'KXsrbn', 1, '2024-10-08 05:22:44'),
(2, 'xjPEgu', 1, '2024-10-08 05:22:44'),
(3, 'IYIXWm', 1, '2024-10-08 05:22:44'),
(4, 'RlOffI', 1, '2024-10-08 05:22:44'),
(5, 'HIzhWQ', 1, '2024-10-08 05:22:44'),
(6, 'eJeIaH', 1, '2024-10-08 05:22:44'),
(7, 'ToAXlL', 1, '2024-10-08 05:22:44'),
(8, '7jhFGG', 1, '2024-10-08 05:22:44'),
(9, 'Wc6fmg', 1, '2024-10-08 05:22:44'),
(10, 'pQn0zW', 1, '2024-10-08 05:22:44'),
(11, 'ZQC5xJ', 1, '2024-10-08 05:22:44'),
(12, 'fwZsvD', 1, '2024-10-08 05:22:44'),
(13, 'zH3cqZ', 0, '2024-10-08 05:22:44'),
(14, 'CwJFDb', 1, '2024-10-08 05:22:44'),
(15, '2JKCtf', 1, '2024-10-08 05:22:44'),
(16, 'l6jXlA', 1, '2024-10-08 05:22:44'),
(17, 'VmWbZf', 0, '2024-10-08 05:22:44'),
(18, 'iVRoQw', 0, '2024-10-08 05:22:44'),
(19, 'YkMGaV', 0, '2024-10-08 05:22:44'),
(20, '1Ao8Yj', 0, '2024-10-08 05:22:44'),
(21, '6CeWO6', 0, '2024-10-08 05:22:44'),
(22, 'HR5gK6', 0, '2024-10-08 05:22:44'),
(23, 'oKKA1s', 0, '2024-10-08 05:22:44'),
(24, 'T6UONw', 0, '2024-10-08 05:22:44'),
(25, 'ETDeyo', 1, '2024-10-08 05:22:44'),
(26, '3GBIVv', 0, '2024-10-08 05:22:44'),
(27, '3sANAP', 0, '2024-10-08 05:22:44'),
(28, 'M3nzzg', 0, '2024-10-08 05:22:44'),
(29, 'yphawe', 0, '2024-10-08 05:22:44'),
(30, 'YzfCRT', 0, '2024-10-08 05:22:44'),
(31, 'NGJ6P', 0, '2024-10-08 05:22:44'),
(32, 'x9yK5K', 0, '2024-10-08 05:22:44'),
(33, 'BUWxAy', 0, '2024-10-08 05:22:44'),
(34, 'PuUz9L', 0, '2024-10-08 05:22:44'),
(35, 'VSJz1P', 0, '2024-10-08 05:22:44'),
(36, 'EkaMUr', 0, '2024-10-08 05:22:44'),
(37, 'ySFHMt', 0, '2024-10-08 05:22:44'),
(38, 'hfY0oJ', 0, '2024-10-08 05:22:44'),
(39, 'd9qse5', 0, '2024-10-08 05:22:44'),
(40, 'ZHLbSm', 0, '2024-10-08 05:22:44'),
(41, 'pKLGAD', 0, '2024-10-08 05:22:44'),
(42, 'UX8dfU', 0, '2024-10-08 05:22:44'),
(43, 'iu1Dyh', 0, '2024-10-08 05:22:44'),
(44, 'T03GdW', 0, '2024-10-08 05:22:44'),
(45, 'ehe7P1', 0, '2024-10-08 05:22:44'),
(46, '59s108', 0, '2024-10-08 05:22:44'),
(47, 't4SASi', 0, '2024-10-08 05:22:44'),
(48, 'B9T8q4', 0, '2024-10-08 05:22:44'),
(49, '6ruzA5', 0, '2024-10-08 05:22:44'),
(50, 'qrqFjS', 0, '2024-10-08 05:22:44'),
(51, 'LS6j5p', 0, '2024-10-08 05:22:44'),
(52, 'E6Ssvw', 0, '2024-10-08 05:22:44'),
(53, 'wD95Aw', 0, '2024-10-08 05:22:44'),
(54, 'WKJUlU', 0, '2024-10-08 05:22:44'),
(55, 'IHnV3K', 0, '2024-10-08 05:22:44'),
(56, 'mxdrVR', 0, '2024-10-08 05:22:44'),
(57, 'usZIBr', 0, '2024-10-08 05:22:44'),
(58, 'pA0GHN', 0, '2024-10-08 05:22:44'),
(59, 'EG3tW6', 0, '2024-10-08 05:22:44'),
(60, 'bsPTBp', 0, '2024-10-08 05:22:44'),
(61, 'FiXYYA', 0, '2024-10-08 05:22:44'),
(62, 'Een3WJ', 0, '2024-10-08 05:22:44'),
(63, 'BxyZLh', 0, '2024-10-08 05:22:44'),
(64, 'l0SLmS', 0, '2024-10-08 05:22:44'),
(65, 'rrqt2x', 0, '2024-10-08 05:22:44'),
(66, 'W2D898', 0, '2024-10-08 05:22:44'),
(67, 'PFrd5B', 0, '2024-10-08 05:22:44'),
(68, 'ucmEkD', 0, '2024-10-08 05:22:44'),
(69, '2JYPAZ', 0, '2024-10-08 05:22:44'),
(70, 'JtCUjE', 0, '2024-10-08 05:22:44'),
(71, 'iTLIfE', 0, '2024-10-08 05:22:44'),
(72, 'S6qfxF', 0, '2024-10-08 05:22:44'),
(73, 'p8fI1d', 0, '2024-10-08 05:22:44'),
(74, '1ZDxLa', 0, '2024-10-08 05:22:44'),
(75, 'copgdJ', 0, '2024-10-08 05:22:44'),
(76, 'h9rs8I', 0, '2024-10-08 05:22:44'),
(77, '8Le5Q5', 0, '2024-10-08 05:22:44'),
(78, 'S0il6M', 0, '2024-10-08 05:22:44'),
(79, 'eQSSjb', 0, '2024-10-08 05:22:44'),
(80, 'MWep7d', 0, '2024-10-08 05:22:44'),
(81, 'PFEgNV', 0, '2024-10-08 05:22:44'),
(82, 'pzc1Ae', 0, '2024-10-08 05:22:44'),
(83, 'GSAp0S', 0, '2024-10-08 05:22:44'),
(84, 'gRFo83', 0, '2024-10-08 05:22:44'),
(85, 'MkLQ0m', 0, '2024-10-08 05:22:44'),
(86, 'nMq5j6', 0, '2024-10-08 05:22:44'),
(87, 'qegx85', 0, '2024-10-08 05:22:44'),
(88, 'hKEIIL', 0, '2024-10-08 05:22:44'),
(89, 'lnBQkD', 0, '2024-10-08 05:22:44'),
(90, 'LMYAo0', 0, '2024-10-08 05:22:44'),
(91, 'ui8O7E', 0, '2024-10-08 05:22:44'),
(92, 'IqnoMP', 0, '2024-10-08 05:22:44'),
(93, 'SrWUbC', 0, '2024-10-08 05:22:44'),
(94, '1iMT0D', 0, '2024-10-08 05:22:44'),
(95, 'LfhCbV', 0, '2024-10-08 05:22:44'),
(96, 'dvU7Mk', 0, '2024-10-08 05:22:44'),
(97, 'SW3iWm', 0, '2024-10-08 05:22:44'),
(98, '6Il4t8', 0, '2024-10-08 05:22:44'),
(99, 'af0yya', 0, '2024-10-08 05:22:44'),
(100, '1DNmj4', 0, '2024-10-08 05:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `school_id_number` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0,
  `user_type` enum('student','teacher') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `birthdate`, `school_id_number`, `username`, `password`, `points`, `user_type`, `profile_picture`) VALUES
(1, 'francis', '2024-09-06', 'SCC-19-000234245', 'francis', '$2y$10$e7OcR3h0ZNEmYo8/HDutzOBk2vIQ.Za8qmvLYRo.JTCz9Dg1LwZ8i', 5, 'student', 'uploads/ew.jpg'),
(2, 'leo', '2024-09-06', 'SCC-19-0003234245', 'leo', '$2y$10$UGn25WAONnlOJo0I5w.EZ.f8c1rvzGWKg08feVW8bQWRa2YRFQmMi', 4, 'student', 'uploads/me.jpg'),
(3, 'mike', '2024-09-06', 'SCC-19-00032234245', 'mike', '$2y$10$7CrqzuLJMXXtN9ejzwWCN.l2um1/jgE61Er4sqWfCdWzc6sEP2Fse', 7, 'student', 'uploads/repu.PNG'),
(4, 'cantos', '2024-09-06', 'SCC-19-000322334245', 'cantos', '$2y$10$C1Ldsr1806G2eJ7RUeAtZOjLgcNnOeYjEPZE2G4NwMsOa6i2btQHS', 0, 'student', 'uploads/test.PNG'),
(5, 'arthur', '2024-09-06', 'SCC-19-0003322334245', 'arthur', '$2y$10$C3OchIIO2lCjMV5kTKv1c.wK3PSpuEJiBQ96Q9bo0R6az.0QUjppe', 0, 'student', 'uploads/ss.PNG'),
(6, 'rafayla', '2024-09-06', 'SCC-19-0002342452', 'rafayla', '$2y$10$xwYHAq6dxBCKdarehxJWX.SBlrjLy2N3OreKXFKhlGwCw/Iv68K1C', 0, 'student', 'uploads/8-4-2024 9;39;27 PM.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `student_actions`
--

CREATE TABLE `student_actions` (
  `student_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `points_used` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `earned_badges`
--
ALTER TABLE `earned_badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_badge` (`student_id`,`badge_name`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `reward_codes`
--
ALTER TABLE `reward_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reward_codes`
--
ALTER TABLE `reward_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
