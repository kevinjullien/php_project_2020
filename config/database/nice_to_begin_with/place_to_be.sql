-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 04, 2020 at 07:12 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `place_to_be`
--
CREATE DATABASE IF NOT EXISTS `place_to_be` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `place_to_be`;

-- --------------------------------------------------------

--
-- Table structure for table `adresses`
--

DROP TABLE IF EXISTS `adresses`;
CREATE TABLE `adresses` (
  `id_adress` int(11) NOT NULL,
  `country` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `postal_code` varchar(12) NOT NULL,
  `street` varchar(50) NOT NULL,
  `number` varchar(10) NOT NULL,
  `latitude` decimal(9,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `adresses`
--

INSERT INTO `adresses` (`id_adress`, `country`, `city`, `postal_code`, `street`, `number`, `latitude`, `longitude`) VALUES
(1, 'Belgique', 'Bruxelles', '1000', 'rue de la pinte', '17', '50.5000000', '-31.4500000'),
(2, 'France', 'Paris', '45000', 'avenue du pinard', '65', '31.6000000', '19.1643000'),
(3, 'Japon', 'Osaka', '53-0000', 'Sake machi', '42', NULL, NULL),
(4, 'Russie', 'Moscou', '101000', 'vodochnaya ulitsa', '111', NULL, NULL),
(63, 'Canada', 'Montréal', 'QC H1V 3N7', 'Avenue Pierre-De Coubertin', '4141', NULL, NULL),
(69, 'Belgique', 'Bruxelles', '1200', 'Clos chapelle-aux-champs', '43', '50.8494210', '4.4495590'),
(79, 'Belgique', 'Charleroi', '6000', 'Rue du peket', '20', NULL, NULL),
(80, 'France', 'Auvergne', '63450', 'Rue su sauciflard', '943', NULL, NULL),
(81, 'Portugal', 'Lisbonne', '1100-148 ', 'Praça do Comércio', '0', NULL, NULL),
(82, 'États-Unis', 'Wyoming', '82190', 'Caldeira de Yellowstone', '0', NULL, NULL),
(83, 'Belgique', 'Bruxelles', '1020', 'Square de l\'Atomium', '0', NULL, NULL),
(84, 'Inde', 'Agra', '282001', 'Uttar Pradesh', '0', NULL, NULL),
(85, 'Partout', 'Toutes', '00', 'Milky way', '0', NULL, NULL),
(86, 'Belgique', 'Bruxelles', '1000', 'Grand-Place', '00', NULL, NULL),
(87, 'Bosnie-Herzégovine', 'Mostar', '88000 ', 'Kneza Domagoja bb.', '00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE `keywords` (
  `id_keyword` int(11) NOT NULL,
  `label` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keywords`
--

INSERT INTO `keywords` (`id_keyword`, `label`) VALUES
(7, 'Activités scolaires'),
(6, 'Cinéma'),
(4, 'Loisirs'),
(5, 'Musique'),
(3, 'Santé'),
(8, 'Science & Technologie'),
(2, 'Spiritualité'),
(1, 'Sport');

-- --------------------------------------------------------

--
-- Table structure for table `keywords_of_venues`
--

DROP TABLE IF EXISTS `keywords_of_venues`;
CREATE TABLE `keywords_of_venues` (
  `id_venue` int(11) NOT NULL,
  `id_keyword` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keywords_of_venues`
--

INSERT INTO `keywords_of_venues` (`id_venue`, `id_keyword`) VALUES
(1, 4),
(1, 7),
(1, 8),
(2, 4),
(2, 7),
(2, 8),
(3, 7),
(4, 5),
(6, 2),
(6, 3),
(7, 4),
(8, 4),
(10, 1),
(10, 4),
(11, 4),
(11, 7),
(11, 8),
(12, 2),
(12, 4),
(12, 8),
(13, 2),
(13, 4);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id_member` int(11) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `activate` tinyint(1) NOT NULL DEFAULT 1,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id_member`, `password`, `email`, `is_admin`, `activate`, `lastname`, `firstname`) VALUES
(1, '$2y$10$oD7ake.490RGCjXTwvY3QexYUCNgxAND8rF4YnbWMdaQPUB/9xJxS', 'admin@vinci.be', 1, 1, 'admin', 'admin'),
(27, '$2y$10$tNRhZBwTW9DMJ2L6z3DtMegbUb50HB/wKHbdRtbrSRObWlhtBY3..', 'jean.cerien@vinci.be', 0, 1, 'Cérien', 'Jean'),
(28, '$2y$10$HctZ/IUQ3k2ZhQs6HOKH3emAidzcEcHnn8iHZcaK2WV/re5RyadZi', 'Mickey.M@vinci.be', 0, 1, 'Mouse', 'Mickey'),
(30, '$2y$10$KR8lkX5SLzqbhfF7uy2m/ePg9DyCJRx5mjri00nuOzk0uRGRbFs4e', 'alain.delon@vinci.be', 0, 1, 'Delon', 'Alain'),
(32, '$2y$10$wY2JG8iG.hCzyi7nZMsLJOxIkvfrsV5uY6eJYuqwSh5ZJFZs73ZrK', 'Benny.Mass@vinci.be', 0, 1, 'Massard', 'Bernard'),
(33, '$2y$10$Ym9Y3VUBlul9sZlLF4DrdOzFgKfMw14rLz3cPTfI44m5Du9NnPT3S', 'kevin.jullien@student.vinci.be', 0, 1, 'Jullien', 'Kevin'),
(44, '$2y$10$xa9uAUKQhQOJzlVzkb1w5OPkNN8bnQPIcQUpcrSwaykJI45mCxG2O', 'ochon7.paul@vinci.be', 0, 1, 'Ochon', 'Paul');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
CREATE TABLE `venues` (
  `id_venue` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `photo` varchar(50) NOT NULL,
  `id_adress` int(11) NOT NULL,
  `type` enum('E','P') NOT NULL,
  `submitter` int(11) NOT NULL,
  `start_datetime` timestamp NULL DEFAULT NULL,
  `start_endtime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id_venue`, `title`, `photo`, `id_adress`, `type`, `submitter`, `start_datetime`, `start_endtime`) VALUES
(1, 'Tour Eiffel', 'views/images/tour_eiffel.jpg', 2, 'P', 1, NULL, NULL),
(2, 'Atomium', 'views/images/atomium.jpg', 83, 'P', 1, NULL, NULL),
(3, 'ATARNotes English 3&4 Lecture', 'views/images/event1.jpg', 3, 'E', 1, '2020-07-01 16:00:00', '2020-07-01 18:00:00'),
(4, 'Simply Jesus', 'views/images/event2.jpg', 4, 'E', 1, '2020-04-13 18:00:00', '2020-04-13 20:00:00'),
(5, 'Get Hired', 'views/images/event3.jpg', 87, 'E', 1, '2020-08-12 10:00:00', '2020-08-16 18:00:00'),
(6, 'Ascencion Timeline Meditation', 'views/images/event4.jpg', 79, 'E', 1, '2020-10-17 14:00:00', '2020-10-17 18:00:00'),
(7, 'Grand Place de Bruxelles', 'views/images/grand place.jpg', 86, 'P', 1, NULL, NULL),
(8, 'Place du Commerce', 'views/images/place du commerce.jpg', 81, 'P', 1, NULL, NULL),
(10, 'Yellowstone', 'views/images/Yellowstone.jpg', 82, 'P', 1, NULL, NULL),
(11, 'AndroidMakers', 'views/images/event5.jpg', 80, 'E', 1, '2020-09-19 16:00:00', '2020-09-19 21:30:00'),
(12, 'Voie lactée', 'views/images/great-milky-way-4k.jpg', 85, 'P', 1, NULL, NULL),
(13, 'Taj Mahal', 'views/images/Taj-Mahal.jpg', 84, 'P', 1, NULL, NULL),
(50, 'Stade Olympique', 'views/images/1588583435_0751Le_Stade_Olympique.jpg', 63, 'P', 1, NULL, NULL),
(55, 'zone de confinement 43', 'views/images/1588595110_9088vinci.png', 69, 'P', 44, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `id_member` int(11) NOT NULL,
  `id_favourite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id_member`, `id_favourite`) VALUES
(27, 1),
(27, 2),
(27, 4),
(27, 5),
(27, 6),
(27, 12),
(27, 55),
(33, 1),
(33, 2),
(33, 7),
(33, 8),
(33, 12),
(33, 50),
(33, 55),
(44, 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id_adress`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id_keyword`),
  ADD UNIQUE KEY `label` (`label`);

--
-- Indexes for table `keywords_of_venues`
--
ALTER TABLE `keywords_of_venues`
  ADD PRIMARY KEY (`id_venue`,`id_keyword`),
  ADD KEY `id_keyword` (`id_keyword`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id_member`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id_venue`),
  ADD KEY `fk_foreign_adress` (`id_adress`),
  ADD KEY `fk_foreign_submitter` (`submitter`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id_member`,`id_favourite`),
  ADD KEY `votes_ibfk_1` (`id_favourite`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id_adress` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id_keyword` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id_venue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keywords_of_venues`
--
ALTER TABLE `keywords_of_venues`
  ADD CONSTRAINT `keywords_of_venues_ibfk_1` FOREIGN KEY (`id_keyword`) REFERENCES `keywords` (`id_keyword`),
  ADD CONSTRAINT `keywords_of_venues_ibfk_2` FOREIGN KEY (`id_venue`) REFERENCES `venues` (`id_venue`) ON DELETE CASCADE;

--
-- Constraints for table `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `fk_foreign_adress` FOREIGN KEY (`id_adress`) REFERENCES `adresses` (`id_adress`),
  ADD CONSTRAINT `fk_foreign_submitter` FOREIGN KEY (`submitter`) REFERENCES `members` (`id_member`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`id_favourite`) REFERENCES `venues` (`id_venue`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `members` (`id_member`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
