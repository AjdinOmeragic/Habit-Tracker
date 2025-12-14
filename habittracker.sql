-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 10:35 PM
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
-- Database: `habittracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `parent_comment_id`, `content`, `created_at`) VALUES
(2, 1, 2, NULL, 'Thanks! How is your journey?', '2025-10-27 19:25:10'),
(3, 2, 2, NULL, 'What do you do for self care?', '2025-10-27 19:25:10'),
(5, 4, 2, NULL, 'Water goals! I need to improve', '2025-10-27 19:25:10'),
(6, 8, 21, NULL, 'Mid', '2025-12-13 15:34:41'),
(7, 1, 21, NULL, 'I like your progress bro!', '2025-12-13 16:18:52'),
(8, 1, 21, NULL, 'hello', '2025-12-13 16:49:38'),
(9, 1, 21, NULL, 'these', '2025-12-13 16:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habits`
--

INSERT INTO `habits` (`id`, `user_id`, `name`, `category`, `created_at`) VALUES
(2, 2, 'Read 30 minutes', 'learning', '2025-10-27 19:25:10'),
(3, 3, 'Meditation', 'mindfulness', '2025-10-27 19:25:10'),
(4, 3, 'Drink Water', 'health', '2025-10-27 19:25:10'),
(5, 2, 'Code Practice', 'learning', '2025-10-27 19:25:10'),
(9, 6, 'dada', 'learning', '2025-11-13 19:11:23'),
(10, 7, 'Exercise Morning', 'health', '2025-11-13 20:33:45'),
(11, 7, 'Another Habit', 'productivity', '2025-11-13 20:33:56'),
(12, 17, 'Push Ups', 'health', '2025-12-11 18:17:10'),
(13, 17, 'Diplomiranje?', 'learning', '2025-12-11 18:18:57'),
(14, 17, 'noodletemp1', 'health', '2025-12-11 18:26:20'),
(15, 18, 'Studying For Web', 'learning', '2025-12-11 18:29:29'),
(16, 18, 'noodletemp1', 'health', '2025-12-11 18:29:48'),
(21, 19, '10 Push Ups', 'health', '2025-12-11 19:58:31'),
(23, 21, 'Gym Day 1H', 'health', '2025-12-13 15:35:38'),
(24, 21, 'Studying Project 6PM', 'learning', '2025-12-13 15:35:56'),
(25, 21, 'Wash Clothes ', 'productivity', '2025-12-13 15:36:19'),
(26, 21, 'Kokok', 'mindfulness', '2025-12-13 15:36:24'),
(40, 26, 'Exercise 30 Push Ups', 'health', '2025-12-14 19:28:03'),
(41, 24, 'KEnanDoesStuff', 'learning', '2025-12-14 20:18:04'),
(42, 26, '!BOSNIA NUMBAR!!1111!!!', 'learning', '2025-12-14 20:28:21'),
(44, 24, 'Kokok', 'learning', '2025-12-14 21:25:11');

-- --------------------------------------------------------

--
-- Table structure for table `habit_completions`
--

CREATE TABLE `habit_completions` (
  `id` int(11) NOT NULL,
  `habit_id` int(11) NOT NULL,
  `completion_date` date NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habit_completions`
--

INSERT INTO `habit_completions` (`id`, `habit_id`, `completion_date`, `completed_at`) VALUES
(6, 2, '2024-10-01', '2025-10-27 19:25:10'),
(7, 2, '2024-10-03', '2025-10-27 19:25:10'),
(8, 2, '2024-10-04', '2025-10-27 19:25:10'),
(9, 2, '2024-10-06', '2025-10-27 19:25:10'),
(10, 2, '2024-10-07', '2025-10-27 19:25:10'),
(11, 3, '2024-10-02', '2025-10-27 19:25:10'),
(12, 3, '2024-10-03', '2025-10-27 19:25:10'),
(13, 3, '2024-10-04', '2025-10-27 19:25:10'),
(14, 3, '2024-10-05', '2025-10-27 19:25:10'),
(15, 3, '2024-10-07', '2025-10-27 19:25:10'),
(16, 4, '2024-10-01', '2025-10-27 19:25:10'),
(17, 4, '2024-10-02', '2025-10-27 19:25:10'),
(18, 4, '2024-10-03', '2025-10-27 19:25:10'),
(19, 4, '2024-10-05', '2025-10-27 19:25:10'),
(20, 4, '2024-10-06', '2025-10-27 19:25:10'),
(21, 5, '2024-10-02', '2025-10-27 19:25:10'),
(22, 5, '2024-10-03', '2025-10-27 19:25:10'),
(23, 5, '2024-10-04', '2025-10-27 19:25:10'),
(24, 5, '2024-10-06', '2025-10-27 19:25:10'),
(25, 5, '2024-10-07', '2025-10-27 19:25:10'),
(27, 9, '2025-11-13', '2025-11-13 19:11:26'),
(30, 23, '2025-12-13', '2025-12-13 15:36:37'),
(31, 24, '2025-12-13', '2025-12-13 15:36:55'),
(32, 25, '2025-12-13', '2025-12-13 15:56:12'),
(36, 40, '2025-12-14', '2025-12-14 19:28:09'),
(37, 42, '2025-12-14', '2025-12-14 20:31:47');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, '4 days of meditation completed!', 'Meditation is really helpful for personal growth!', '2025-10-27 19:25:10', '2025-10-27 19:25:10'),
(2, 3, 'Self care day!', 'Self care involves growth for yourself', '2025-10-27 19:25:10', '2025-10-27 19:25:10'),
(4, 3, 'Water drinking challenge', '10/10 days of drinking 2L water daily', '2025-10-27 19:25:10', '2025-10-27 19:25:10'),
(8, 19, 'Does anyone else think Gojo VS Sukuna was the PEEK?', 'Personally it slapped ', '2025-12-11 20:18:55', '2025-12-11 20:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 1, 3, '2025-10-27 19:25:10'),
(2, 1, 2, '2025-10-27 19:25:10'),
(3, 2, 2, '2025-10-27 19:25:10'),
(5, 4, 2, '2025-10-27 19:25:10'),
(12, 4, 21, '2025-12-13 16:01:33'),
(16, 8, 21, '2025-12-13 16:01:38'),
(18, 2, 21, '2025-12-13 16:20:02'),
(20, 1, 21, '2025-12-13 16:38:21'),
(21, 8, 22, '2025-12-13 16:51:19'),
(25, 4, 22, '2025-12-13 16:51:23'),
(29, 1, 22, '2025-12-13 16:51:38'),
(30, 2, 22, '2025-12-13 17:34:08'),
(31, 1, 13, '2025-12-13 20:04:52'),
(33, 1, 24, '2025-12-14 17:07:27'),
(36, 1, 26, '2025-12-14 19:29:34'),
(37, 8, 24, '2025-12-14 21:10:06'),
(38, 4, 24, '2025-12-14 21:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@habittracker.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-27 19:25:10'),
(2, 'john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-27 19:25:10'),
(3, 'jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-10-27 19:25:10'),
(6, 'noodlesajhjua', 'test3@example.com', '$2y$10$iubTEd7Uhjgrvb15049K9.Kkt8IiJ4ZGv7JOq1a2rY2fxEIGFT.16', 'user', '2025-11-13 18:50:25'),
(7, 'tester', 'tester@gmail.com', '$2y$10$/ZFZIbILaUM5CwhysRKsx..zTJPQCFwlILp94eCj2TcotPRftz1la', 'user', '2025-11-13 20:32:53'),
(8, 'testuser', 'test@test.com', '$2y$10$SlA5ciqDAhFEjYrBxEKGuesSMuDzxrbkDeV1ldCbsBtL8rbmQBayW', 'user', '2025-12-06 23:24:21'),
(9, 'postmantest', 'postman@test.com', '$2y$10$mu7mtDSMA9993vMHp1SIsekpBr.EWySWK2P0FlSt8OY.hq1GHTsmW', 'user', '2025-12-06 23:25:24'),
(10, 'testuser1', 'test@test1.com', '$2y$10$1qIQ/cpwmCOenRYbxXaX8e23Pt9PRbjWTzFWi70CkROASQTznpzu6', 'user', '2025-12-06 23:32:17'),
(11, 'adminAjdin', 'admin@habit.com', '$2y$10$X2R6L0XH1.lpoEhTUc3aaOhbU7bwl/HcdxGmBNSf6m9666E06.1y.', 'user', '2025-12-08 20:24:39'),
(12, 'adminAjdinomeragic', 'admin@habitos.com', '$2y$10$wycKoiUAOUcOJc752mYo8Ol9XqkDMn1I/8yTIIWLjxP0VB4P5lXgu', 'user', '2025-12-08 22:15:32'),
(13, 'adminuser', 'admin@example.com', '$2y$10$IUhW2qW1.J.N7MzN3aqc8ujn78VWH5y/rSlmffQRTf6UG5PBeEa7.', 'user', '2025-12-08 22:17:03'),
(14, 'testuser99', 'test@example.com', '$2y$10$HqaRCIf/EcnO58ZbipWl7ONQJd.u/k0HayzmD7fHb/XMS8gAPe/QC', 'user', '2025-12-09 21:47:39'),
(15, 'Ajdinos', 'regular@example.com', '$2y$10$BE.bCZrDTMGEu8hBjY7VqeDACbAt6xR14e7dwK8X81cM402iFqlDG', 'user', '2025-12-10 00:37:37'),
(16, 'phpunit_user_1765395214', 'phpunit_1765395214@example.com', '$2y$10$8luW6WZVndfdyOdmNFwhFO0R7yGKpzFUsXUU5a0sTO/TqA.EgEV0i', 'admin', '2025-12-10 19:33:34'),
(17, 'solaris_byte_73', 'solaris.byte.73@testexample.dev', '$2y$10$YxKx7HkEM3R3dZ8o7ne.jeGTVj5rE0LKT6/9ZWxkRzxu7Jzd/OYIG', 'user', '2025-12-11 18:15:47'),
(18, 'ember_circuit_42', 'ember.circuit.42@testexample.dev', '$2y$10$9n31KRvLgGRBvKydR1Lio.7kQBBEZofIa3bXq9.z8oM.iBmj1QXT.', 'user', '2025-12-11 18:28:51'),
(19, 'nova_flux_88', 'nova.flux.88@testexample.dev', '$2y$10$ikoLF/BKvB.W.eaMuSDf5uFOs203pYxGqPN4Mwz43TGIzqyFFSXGO', 'user', '2025-12-11 18:39:46'),
(20, 'BillyBob', 'BillyBob@gmail.com', '$2y$10$vRB/nbWPaFxWC5WdJhSBTei6G1WOcNU8C.E/qY9wmy8SaiImwI5EO', 'user', '2025-12-11 20:30:09'),
(21, 'EchoVertex92', 'echo.vertex92@protonmail.com', '$2y$10$EWWZVsTE821TSUwhNA.OqesmPifnHRI9IOmdqb1pvUPJX6JBOicrG', 'user', '2025-12-13 15:33:24'),
(22, 'ajd1n_x9', 'ajd1n.vertex9@protonmail.com', '$2y$10$R2lHd0tusv6xU/khuXKiYuQT1JJYM6f5bvM.DoiWJSkN26qL5ep5y', 'user', '2025-12-13 16:51:00'),
(23, 'ajdinTester909', 'ajdintester@gmail.com', '$2y$10$.lB3/EbX8wADGngOwuF15OJT2Fj3xwxL4jO/mCcWW3xA7nNJ7DFYS', 'admin', '2025-12-13 16:53:49'),
(24, 'ajdinomerkadics', 'ajdin.omeragic.pladada@gmail.com', '$2y$10$rzUJXyUy3iuCZc2PMJSe0eW4p4aJHKfABQS05IQoUQyA//7WGOlmK', 'admin', '2025-12-14 00:32:54'),
(25, 'NullAxiom47', 'null.axiom47@outlook.com', '$2y$10$4SMjMkoILWlVqwv8IW4mIOJ.FxcoreZsMARcJN3kGlmH2lWWROjMq', 'user', '2025-12-14 17:57:50'),
(26, 'AxiomDrift56', 'axiom.drift56@gmail.com', '$2y$10$zESV8DYZaiDUCHTX6yZv3OwWusFWsyN2b1pt8hLQvWZPppuWb3K3q', 'user', '2025-12-14 19:23:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `habit_completions`
--
ALTER TABLE `habit_completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_habit_date` (`habit_id`,`completion_date`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_post_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `habit_completions`
--
ALTER TABLE `habit_completions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habits`
--
ALTER TABLE `habits`
  ADD CONSTRAINT `habits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habit_completions`
--
ALTER TABLE `habit_completions`
  ADD CONSTRAINT `habit_completions_ibfk_1` FOREIGN KEY (`habit_id`) REFERENCES `habits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
