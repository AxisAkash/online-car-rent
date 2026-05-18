-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 07:59 AM
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
-- Database: `online-car-rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(5, 1, 'Admin Notice About Blog Experience', 'Admin can post a blog and delete any blog post from the system.', '2026-05-15 06:58:28', '2026-05-15 06:58:28'),
(6, 2, 'my experience', 'my first expeience was good', '2026-05-15 06:58:55', '2026-05-15 06:58:55'),
(7, 2, 'Good Service and Clean Car', 'I rented a car for a short trip. The booking process was simple, and the car condition was good.', '2026-05-17 10:46:59', '2026-05-17 10:46:59'),
(8, 2, 'Easy Car Rental Process', 'The website was easy to use. I could view the car details, check the cost, and complete the rental process smoothly.', '2026-05-17 10:55:22', '2026-05-17 10:55:22'),
(9, 2, 'My 3rd time  Rental Experience', 'The car was clean, comfortable, and the rental process was very easy. I had a good experience using this service.', '2026-05-17 10:56:05', '2026-05-17 10:56:05'),
(10, 1, 'Blog Moderation Test', 'Admin can view all blog posts and delete any post from the system when needed.', '2026-05-17 10:57:34', '2026-05-17 10:57:34'),
(11, 1, 'Admin Test Blog', 'This is an admin test post to check blog posting and moderation features.', '2026-05-17 10:57:50', '2026-05-17 10:57:50'),
(12, 2, 'clean car and good experience', 'rent service was good. thanks for give me good service', '2026-05-18 04:44:00', '2026-05-18 04:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','member') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `profile_picture`, `address`, `phone`, `created_at`) VALUES
(1, 'Demo Admin', 'admin@test.com', '$2y$10$eImiTXuWVxfM37uY4JANjQeDfc6Dn36U4VQauQxBhc4xPME8F3i9G', 'admin', NULL, NULL, NULL, '2026-05-15 06:46:54'),
(2, 'Demo Member', 'member@test.com', '$2y$10$eImiTXuWVxfM37uY4JANjQeDfc6Dn36U4VQauQxBhc4xPME8F3i9G', 'member', NULL, NULL, NULL, '2026-05-15 06:46:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
