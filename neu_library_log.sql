-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2026 at 08:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neu_library_log`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `rfid` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `college` varchar(100) DEFAULT NULL,
  `role` enum('visitor','admin') NOT NULL DEFAULT 'visitor',
  `password` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `visitor_type` varchar(50) NOT NULL DEFAULT 'Student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `rfid`, `email`, `full_name`, `college`, `role`, `password`, `is_blocked`, `created_at`, `visitor_type`) VALUES
(12, NULL, 'harry.potter@neu.edu.ph', 'Harry Potter', 'BSIT', 'visitor', NULL, 0, '2026-03-13 18:28:39', 'Student'),
(13, NULL, 'ron.weasley@neu.edu.ph', 'Ron Weasley', 'COC', 'visitor', NULL, 0, '2026-03-13 18:29:08', 'Student'),
(14, NULL, 'hermione.granger@neu.edu.ph', 'Hermione Granger', 'BSIT', 'visitor', NULL, 0, '2026-03-13 18:29:37', 'Student'),
(15, NULL, 'draco.malfoy@neu.edu.ph', 'Draco Malfoy', 'COA', 'visitor', NULL, 0, '2026-03-13 18:30:14', 'Student'),
(16, NULL, 'linda.walker@neu.edu.ph', 'Linda Walker', 'CICS', 'visitor', NULL, 0, '2026-03-13 18:31:22', 'Student'),
(19, NULL, 'jcesperanza@neu.edu.ph', 'Jeremias C. Esperanza', 'Admin Office', 'admin', '7488e331b8b64e5794da3fa4eb10ad5d', 0, '2026-03-16 10:22:40', 'Professor'),
(20, NULL, 'neville.longbottom@neu.edu.ph', 'Neville Longbottom', 'CCR', 'visitor', NULL, 0, '2026-03-16 10:40:43', 'Student'),
(21, NULL, 'thorin.oakinshield@neu.edu.ph', 'Thorin Oakinshield', 'CEA', 'visitor', NULL, 0, '2026-03-16 10:58:30', 'Professor'),
(22, NULL, 'timo.gray@neu.edu.ph', 'Timo Gray', 'CAS', 'visitor', NULL, 0, '2026-03-17 17:22:16', 'Student'),
(23, NULL, 'knox.archer@neu.edu.ph', 'Knox Archer', 'CEA', 'visitor', NULL, 0, '2026-03-17 18:53:30', 'Student'),
(24, NULL, 'levi.ackerman@neu.edu.ph', 'Levi Ackerman', 'CCR', 'visitor', NULL, 0, '2026-03-17 19:23:01', 'Professor'),
(25, NULL, 'eren.yeager@neu.edu.ph', 'Eren Yeager', 'CMT', 'visitor', NULL, 0, '2026-03-17 19:24:40', 'Student'),
(26, NULL, 'mikasa.ackerman@neu.edu.ph', 'Mikasa Ackerman', 'CMT', 'visitor', NULL, 0, '2026-03-17 19:26:23', 'Student'),
(27, NULL, 'sasha.braul@neu.edu.ph', 'Sasha Braul', 'CICS', 'visitor', NULL, 0, '2026-03-17 20:42:42', 'Student'),
(28, NULL, 'raiza.visaya@neu.edu.ph', 'Raiza Visaya', 'CICS', 'admin', NULL, 0, '2026-03-17 20:46:42', 'Student'),
(29, NULL, 'she.may@neu.edu.ph', 'She May', 'CICS', 'visitor', NULL, 0, '2026-03-17 22:10:12', 'Student'),
(36, NULL, 'rai.newadmin@neu.edu.ph', 'Rai', NULL, 'admin', 'f5b480afec38e90861cff0df8a52d685', 0, '2026-03-21 14:46:13', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `visit_logs`
--

CREATE TABLE `visit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit_logs`
--

INSERT INTO `visit_logs` (`id`, `user_id`, `purpose`, `visit_date`, `visit_time`, `created_at`) VALUES
(18, 12, 'Researching', '2026-03-14', '02:28:39', '2026-03-13 18:28:39'),
(19, 13, 'Researching', '2026-03-14', '02:29:08', '2026-03-13 18:29:08'),
(20, 14, 'Studying', '2026-03-14', '02:29:37', '2026-03-13 18:29:37'),
(21, 15, 'Others', '2026-03-14', '02:30:14', '2026-03-13 18:30:14'),
(22, 16, 'Studying', '2026-03-14', '02:31:22', '2026-03-13 18:31:22'),
(23, 20, 'Reading', '2026-03-16', '18:40:43', '2026-03-16 10:40:43'),
(24, 21, 'Others', '2026-03-16', '18:58:30', '2026-03-16 10:58:30'),
(27, 21, 'Others', '2026-03-16', '19:44:10', '2026-03-16 11:44:10'),
(28, 22, 'Use of Computer', '2026-03-18', '01:22:16', '2026-03-17 17:22:16'),
(29, 23, 'Reading', '2026-03-18', '02:53:30', '2026-03-17 18:53:30'),
(30, 21, 'Meeting', '2026-03-18', '03:20:04', '2026-03-17 19:20:04'),
(31, 13, 'Quiet Space', '2026-03-18', '03:21:33', '2026-03-17 19:21:33'),
(32, 24, 'Lecture Preparation', '2026-03-18', '03:23:01', '2026-03-17 19:23:01'),
(33, 25, 'Printing', '2026-03-18', '03:24:40', '2026-03-17 19:24:40'),
(34, 26, 'Studying', '2026-03-18', '03:26:23', '2026-03-17 19:26:23'),
(35, 16, 'Studying', '2026-03-18', '03:27:20', '2026-03-17 19:27:20'),
(36, 27, 'Quiet Space', '2026-03-18', '04:42:42', '2026-03-17 20:42:42'),
(38, 13, 'Group Study', '2026-03-18', '04:47:18', '2026-03-17 20:47:18'),
(39, 23, 'Returning Books', '2026-03-18', '04:47:53', '2026-03-17 20:47:53'),
(40, 29, 'Printing', '2026-03-18', '06:10:12', '2026-03-17 22:10:12'),
(41, 28, 'Returning Books', '2026-03-21', '23:00:05', '2026-03-21 15:00:05'),
(42, 16, 'Researching', '2026-03-21', '23:01:27', '2026-03-21 15:01:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid` (`rfid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `visit_logs`
--
ALTER TABLE `visit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `visit_logs`
--
ALTER TABLE `visit_logs`
  ADD CONSTRAINT `visit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
