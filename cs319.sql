-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2021 at 11:17 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs319`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `student`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `student`;
CREATE TABLE `student` (
`id` int(11)
,`registration_date` timestamp
,`index_id` int(11)
,`name` varchar(255)
,`lastname` varchar(255)
,`email` varchar(255)
,`password_hash` varchar(255)
,`hescode` char(10)
,`profile_picture` blob
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `index_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `hescode` char(10) DEFAULT NULL,
  `profile_picture` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`index_id`, `id`, `name`, `lastname`, `email`, `password_hash`, `hescode`, `profile_picture`) VALUES
(1, 1, 'Muhammed', 'lol', 'a@a', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', NULL, NULL),
(2, 2, 'Mustafa', 'K', 'a@b', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', NULL, NULL),
(3, 3, 'Mustafa', 'K', 'a@c', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', NULL, NULL),
(5, 5, 'Testing', 'lol', 'a@e', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', '1234567890', NULL),
(6, 123, '123', '123', '123@123', '123', NULL, NULL),
(9, 11, 'Register', 'Tester', 'q@w', '123', NULL, NULL),
(10, 907, 'utku', 'jjjj', 'gg@ggsdjsıg.vom', '$argon2i$v=19$m=65536,t=4,p=1$T0FxL05xcGlac2p0cnNEYQ$i3nCwT70kdtT4wlZJCLfU06fBu0rK8m78Yzj3cAxTO8', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_student`
--

DROP TABLE IF EXISTS `user_student`;
CREATE TABLE `user_student` (
  `id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_turkish_ci;

--
-- Dumping data for table `user_student`
--

INSERT INTO `user_student` (`id`, `registration_date`) VALUES
(0, '2021-12-09 14:13:40'),
(1, '2021-12-09 14:13:40'),
(2, '2021-12-09 14:28:10'),
(3, '2021-12-09 14:28:10'),
(4, '2021-12-09 14:28:10'),
(5, '2021-12-09 14:28:10'),
(11, '2021-12-11 22:25:09'),
(907, '2021-12-11 22:28:26');

-- --------------------------------------------------------

--
-- Structure for view `student`
--
DROP TABLE IF EXISTS `student`;

DROP VIEW IF EXISTS `student`;
CREATE ALGORITHM=UNDEFINED DEFINER=`cs319-1I`@`%` SQL SECURITY DEFINER VIEW `student`  AS SELECT `user_student`.`id` AS `id`, `user_student`.`registration_date` AS `registration_date`, `user`.`index_id` AS `index_id`, `user`.`name` AS `name`, `user`.`lastname` AS `lastname`, `user`.`email` AS `email`, `user`.`password_hash` AS `password_hash`, `user`.`hescode` AS `hescode`, `user`.`profile_picture` AS `profile_picture` FROM (`user_student` join `user` on(`user_student`.`id` = `user`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`index_id`),
  ADD UNIQUE KEY `administrator_email_uindex` (`email`),
  ADD UNIQUE KEY `administrator_index_id_uindex` (`index_id`),
  ADD UNIQUE KEY `administrator_id_uindex` (`id`),
  ADD UNIQUE KEY `hescode` (`hescode`);

--
-- Indexes for table `user_student`
--
ALTER TABLE `user_student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id_uid` (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `index_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
