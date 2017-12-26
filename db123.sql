-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2017 at 11:50 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db123`
--

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `type` enum('single','double','luxury','wedding') NOT NULL,
  `price` int(11) NOT NULL,
  `extraBed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `description`, `type`, `price`, `extraBed`) VALUES
(1, '206', 'Cos', 'single', 1000, 10),
(3, '207', 'Ladny pokoj', 'single', 2, 10),
(4, '300', 'Niezbyt ladny pokoj', 'double', 2, 30),
(5, '802', 'much luxury', 'luxury', 1000000000, 1000000),
(6, '1000A', 'fuck room', 'wedding', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `price` float NOT NULL,
  `name` varchar(255) NOT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `price`, `name`, `availability`) VALUES
(1, 10, 'Basen', 1),
(2, 10, 'Sprzatanie', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services_connection`
--

CREATE TABLE `services_connection` (
  `id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services_connection`
--

INSERT INTO `services_connection` (`id`, `visit_id`, `service_id`, `date`) VALUES
(8, 25, 2, '2017-12-26'),
(9, 25, 1, '2017-12-26');

-- --------------------------------------------------------

--
-- Table structure for table `users1`
--

CREATE TABLE `users1` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('normal','admin') COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users1`
--

INSERT INTO `users1` (`id`, `username`, `password`, `type`, `email`) VALUES
(1, 'mikolaj', '$2y$13$Nr2V7OYN3rkoeU5wQIy.QO.wkDIZFowFsWk8/ix.hP5Gi7GsLrpYi', 'normal', 'abc@onet.pl'),
(3, 'mikolaj1', '$2y$13$I3Enw/p9OuriWQ0ht0bDPezWNZI/6j.9qXEYWcwe4J19PedWW6ZLe', 'normal', 'z@oonet.pl'),
(6, 'k', '$2y$13$0OjXC3s0e7X9ixhrgKBOw.0sRmHhLYe5g3EcK2rHRCsGJhSaAg6La', 'normal', 'k@onet.pl'),
(7, 'admin', '$2y$13$Qe0I5WYKrRWqg7/K0fHgz.254ACyQAqzs7Ai.5ZQKedp554RI1YHi', 'admin', 'admin@admin.admin'),
(8, 'admin2', '$2y$13$OQ9sPrKahF7IDaSy4Sc4T..QXKBz.NWIjISchI5/BrZe.bs7VstXO', 'admin', 'admin@a.pl'),
(9, 'ggggg', '$2y$13$CAeJG4M2vzIACH5TLpnfS.eWuZ0U24Zc.QGmH53iaJkSwYdqlVLbW', 'normal', 'g@g.g');

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `guest` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `Extrabeds` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `visit`
--

INSERT INTO `visit` (`id`, `guest`, `room`, `price`, `Extrabeds`, `startDate`, `endDate`) VALUES
(24, 6, 1, 10000, 0, '2018-01-20', '2018-01-30'),
(25, 6, 3, 12, 0, '2017-12-26', '2017-12-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services_connection`
--
ALTER TABLE `services_connection`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visit_id` (`visit_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `users1`
--
ALTER TABLE `users1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5A1E36CEAA08CB10` (`username`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest` (`guest`),
  ADD KEY `room` (`room`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services_connection`
--
ALTER TABLE `services_connection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users1`
--
ALTER TABLE `users1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `services_connection`
--
ALTER TABLE `services_connection`
  ADD CONSTRAINT `services_connection_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `services_connection_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`guest`) REFERENCES `users1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`room`) REFERENCES `room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
