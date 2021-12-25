-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2021 at 12:35 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs319`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `academic_staff`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `academic_staff`;
CREATE TABLE `academic_staff` (
`id` int(11)
,`registration_date` timestamp
,`index_id` int(11)
,`name` varchar(255)
,`lastname` varchar(255)
,`email` varchar(255)
,`password_hash` varchar(255)
,`hescode` char(10)
,`hescode_status` tinyint(1)
,`profile_picture` blob
);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `main_user_id` int(11) NOT NULL,
  `contacted_user_id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `contact`:
--   `main_user_id`
--       `user` -> `id`
--   `event_id`
--       `event` -> `event_id`
--   `contacted_user_id`
--       `user` -> `id`
--

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`main_user_id`, `contacted_user_id`, `event_id`) VALUES
(1, 2, 2),
(1, 3, 2),
(1, 123, 1),
(1, 22101569, 1),
(1, 22102778, 1),
(1, 22102843, 1),
(1, 22102973, 1),
(1, 22103090, 1),
(1, 22103285, 1),
(1, 22103480, 1),
(2, 1, 1),
(2, 3, 4),
(123, 3, 1),
(123, 22101907, 1),
(123, 22102843, 1),
(123, 22102973, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `course`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `course`;
CREATE TABLE `course` (
`event_id` int(11)
,`year` year(4)
,`semester` enum('FALL','SPRING','SUMMER')
,`event_name` varchar(255)
,`place` varchar(255)
,`max_no_of_participant` int(11)
,`can_people_join` tinyint(1)
);

-- --------------------------------------------------------

--
-- Table structure for table `covid_test`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `covid_test`;
CREATE TABLE `covid_test` (
  `test_id` int(11) NOT NULL,
  `test_date` datetime NOT NULL,
  `result` enum('POSITIVE','NEGATIVE','UNKNOWN','PENDING') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `document` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `covid_test`:
--   `user_id`
--       `user` -> `id`
--

--
-- Dumping data for table `covid_test`
--

INSERT INTO `covid_test` (`test_id`, `test_date`, `result`, `user_id`, `document`) VALUES
(1, '2021-12-18 13:36:42', 'NEGATIVE', 1, NULL),
(2, '2021-12-18 13:37:57', 'POSITIVE', 1, NULL),
(3, '2021-12-18 13:38:38', 'NEGATIVE', 1, NULL),
(7, '2021-12-18 13:38:55', 'PENDING', 2, NULL),
(8, '2021-12-18 13:39:28', 'NEGATIVE', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `diagnosis`;
CREATE TABLE `diagnosis` (
  `diagnosis_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `result` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `diagnosis`:
--   `user_id`
--       `user` -> `id`
--

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`diagnosis_id`, `type`, `result`, `date`, `user_id`) VALUES
(1, 'tomography', 0, '2021-12-24 21:07:34', 1),
(4, 'Ultrasound', 1, '2021-12-24 21:14:41', 1),
(5, 'Blood test', 1, '2021-12-24 21:16:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'Bilkent University',
  `max_no_of_participant` int(11) NOT NULL DEFAULT 99999,
  `can_people_join` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event`:
--

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `event_name`, `place`, `max_no_of_participant`, `can_people_join`) VALUES
(1, 'CS101', 'Bilkent University', 75, 1),
(2, 'Fitness', 'Dormitory Sports Hall', 40, 1),
(3, 'CS319', 'B204', 60, 1),
(4, 'Fitness', 'Dormitory Sports Hall', 60, 1),
(5, 'PCR Test', 'Bilkent University', 500, 1),
(6, 'Diagnovir Test', 'Bilkent University', 4, 1),
(7, 'Algebraic Number Theory', 'SB-Z04', 99999, 1),
(8, 'Algebraic Number Theory', 'SB-Z04', 99999, 1),
(9, 'Automata Theory and Formal Languages', 'B-204', 99999, 1),
(10, 'Probability and Statistics for Engineers', 'EA-Z01', 99999, 1),
(11, 'Great Discoveries from the Ancient World ', 'SA-Z18', 99999, 1),
(12, 'Algebraic Number Theory', 'SB-Z04', 99999, 1),
(13, 'Graphic Design for Non-Majors', 'SA-Z18', 99999, 1),
(14, 'Automata Theory and Formal Languages', 'B-Z02', 99999, 1),
(15, 'Graphic Design for Non-Majors', 'SB-Z04', 99999, 1),
(16, 'Computer Organization', 'SA-Z18', 99999, 1),
(17, 'Great Discoveries from the Ancient World ', 'SA-Z18', 99999, 1),
(18, 'Basics of Signals and Systems', 'SA-Z18', 99999, 1),
(19, 'Algebraic Number Theory', 'B-Z05', 99999, 1),
(20, 'Algebraic Number Theory', 'SB-Z04', 99999, 1),
(21, 'Cultures Civilizations and Ideas I', 'EA-Z01', 99999, 1),
(22, 'Object-Oriented Software Engineering', 'EA-Z01', 99999, 1),
(23, 'Basics of Signals and Systems', 'B-Z05', 99999, 1),
(24, 'English and Composition I', 'B-Z01', 99999, 1),
(25, 'Great Discoveries from the Ancient World ', 'EA-Z01', 99999, 1),
(26, 'Great Discoveries from the Ancient World ', 'EA-Z01', 99999, 1),
(27, 'Algorithms and Programming I', 'B-Z02', 99999, 1),
(28, 'Cultures Civilizations and Ideas II', 'B-Z02', 99999, 1),
(29, 'Cultures Civilizations and Ideas II', 'B-Z02', 99999, 1),
(30, 'Cultures Civilizations and Ideas II', 'B-Z05', 99999, 1),
(31, 'English and Composition I', 'EA-Z01', 99999, 1),
(32, 'Graphic Design for Non-Majors', 'B-Z02', 99999, 1),
(33, 'English and Composition I', 'B-204', 99999, 1),
(34, 'Computer Organization', 'SA-Z18', 99999, 1),
(35, 'Cultures Civilizations and Ideas II', 'B-Z02', 99999, 1),
(36, 'Automata Theory and Formal Languages', 'B-Z05', 99999, 1),
(37, 'Algebraic Number Theory', 'B-Z01', 99999, 1),
(38, 'Automata Theory and Formal Languages', 'B-Z02', 99999, 1),
(39, 'Algorithms and Programming I', 'B-Z05', 99999, 1),
(40, 'Introduction to Modern Biology', 'EA-Z01', 99999, 1),
(41, 'Cultures Civilizations and Ideas II', 'B-204', 99999, 1),
(42, 'Algorithms and Programming I', 'B-Z01', 99999, 1),
(43, 'Introduction to Modern Biology', 'SA-Z18', 99999, 1),
(44, 'Automata Theory and Formal Languages', 'SB-Z04', 99999, 1),
(45, 'Great Discoveries from the Ancient World ', 'B-Z05', 99999, 1),
(46, 'Graphic Design for Non-Majors', 'SB-Z04', 99999, 1),
(47, 'Cultures Civilizations and Ideas I', 'SA-Z18', 99999, 1),
(48, 'Great Discoveries from the Ancient World ', 'B-Z01', 99999, 1),
(49, 'Algorithms and Programming I', 'EA-Z01', 99999, 1),
(50, 'Cultures Civilizations and Ideas I', 'B-Z02', 99999, 1),
(51, 'Cultures Civilizations and Ideas II', 'SB-Z04', 99999, 1),
(52, 'Cultures Civilizations and Ideas II', 'SB-Z04', 99999, 1),
(53, 'Cultures Civilizations and Ideas II', 'B-Z02', 99999, 1),
(54, 'Algebraic Number Theory', 'B-Z01', 99999, 1),
(55, 'Great Discoveries from the Ancient World ', 'B-Z02', 99999, 1),
(56, 'Basics of Signals and Systems', 'SA-Z18', 99999, 1),
(57, 'Volleyyball', 'Yurtlar Spor Salonu', 47, 1),
(58, 'Volleyyball', 'Doğu Spor Salonu', 60, 1),
(59, 'Squash ', 'Merkez Spor Salonu', 20, 1),
(60, 'Basketball', 'Doğu Spor Salonu', 39, 1),
(61, 'Fitness', 'Yurtlar Spor Salonu', 63, 1),
(62, 'Yürüme/Koşu Parkuru', 'Doğu Spor Salonu', 48, 1),
(63, 'Yürüme/Koşu Parkuru', 'Yurtlar Spor Salonu', 52, 1),
(64, 'Football', 'Merkez Spor Salonu', 55, 1),
(65, 'Football', 'Doğu Spor Salonu', 88, 1),
(66, 'Fitness', 'Doğu Spor Salonu', 30, 1),
(67, 'Squash ', 'Doğu Spor Salonu', 16, 1),
(68, 'Volleyyball', 'Yurtlar Spor Salonu', 69, 1),
(69, 'Basketball', 'Merkez Spor Salonu', 38, 1),
(70, 'Basketball', 'Yurtlar Spor Salonu', 41, 1),
(71, 'Volleyyball', 'Doğu Spor Salonu', 70, 1),
(72, 'Fitness', 'Doğu Spor Salonu', 9, 1),
(73, 'Volleyyball', 'Merkez Spor Salonu', 53, 1),
(74, 'Squash ', 'Merkez Spor Salonu', 38, 1),
(75, 'Volleyyball', 'Yurtlar Spor Salonu', 0, 1),
(76, 'Basketball', 'Merkez Spor Salonu', 35, 1),
(77, 'Volleyyball', 'Yurtlar Spor Salonu', 34, 1),
(78, 'Squash ', 'Yurtlar Spor Salonu', 40, 1),
(79, 'Fitness', 'Yurtlar Spor Salonu', 66, 1),
(80, 'Yürüme/Koşu Parkuru', 'Doğu Spor Salonu', 66, 1),
(81, 'Basketball', 'Yurtlar Spor Salonu', 51, 1),
(82, 'Squash ', 'Merkez Spor Salonu', 30, 1),
(83, 'Fitness', 'Yurtlar Spor Salonu', 88, 1),
(84, 'Fitness', 'Merkez Spor Salonu', 67, 1),
(85, 'Fitness', 'Merkez Spor Salonu', 84, 1),
(86, 'Squash ', 'Doğu Spor Salonu', 68, 1),
(87, 'Football', 'Merkez Spor Salonu', 36, 1),
(88, 'Volleyyball', 'Doğu Spor Salonu', 54, 1),
(89, 'Squash ', 'Yurtlar Spor Salonu', 34, 1),
(90, 'Squash ', 'Doğu Spor Salonu', 28, 1),
(91, 'Fitness', 'Doğu Spor Salonu', 63, 1),
(92, 'Fitness', 'Doğu Spor Salonu', 58, 1),
(93, 'Squash ', 'Yurtlar Spor Salonu', 70, 1),
(94, 'Squash ', 'Merkez Spor Salonu', 56, 1),
(95, 'Yürüme/Koşu Parkuru', 'Merkez Spor Salonu', 61, 1),
(96, 'Football', 'Doğu Spor Salonu', 44, 1),
(97, 'Fitness', 'Yurtlar Spor Salonu', 48, 1),
(98, 'Squash ', 'Yurtlar Spor Salonu', 66, 1),
(99, 'Yürüme/Koşu Parkuru', 'Doğu Spor Salonu', 80, 1),
(100, 'Volleyyball', 'Yurtlar Spor Salonu', 64, 1),
(101, 'Volleyyball', 'Doğu Spor Salonu', 80, 1),
(102, 'Volleyyball', 'Merkez Spor Salonu', 56, 1),
(103, 'Volleyyball', 'Merkez Spor Salonu', 48, 1),
(104, 'Volleyyball', 'Merkez Spor Salonu', 64, 1),
(105, 'Football', 'Doğu Spor Salonu', 36, 1),
(106, 'Football', 'Doğu Spor Salonu', 25, 1);

-- --------------------------------------------------------

--
-- Table structure for table `event_control`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `event_control`;
CREATE TABLE `event_control` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_control`:
--   `event_id`
--       `event` -> `event_id`
--   `user_id`
--       `user` -> `index_id`
--

--
-- Dumping data for table `event_control`
--

INSERT INTO `event_control` (`event_id`, `user_id`) VALUES
(1, 2),
(1, 123),
(2, 123),
(3, 1),
(3, 123),
(4, 123),
(5, 123),
(6, 123),
(7, 123),
(8, 123),
(9, 123),
(10, 123),
(11, 123),
(12, 123),
(13, 123),
(14, 123),
(15, 123),
(16, 123),
(17, 123),
(18, 123),
(19, 123),
(20, 123),
(21, 123),
(22, 123),
(23, 123),
(24, 123),
(25, 123),
(26, 123),
(27, 123),
(28, 123),
(29, 123),
(30, 123),
(31, 123),
(32, 123),
(33, 123),
(34, 123),
(35, 123),
(36, 123),
(37, 123),
(38, 123),
(39, 123),
(40, 123),
(41, 123),
(42, 123),
(43, 123),
(44, 123),
(45, 123),
(46, 123),
(47, 123),
(48, 123),
(49, 123),
(50, 123),
(51, 123),
(52, 123),
(53, 123),
(54, 123),
(55, 123),
(56, 123),
(57, 123),
(58, 123),
(59, 123),
(60, 123);

-- --------------------------------------------------------

--
-- Table structure for table `event_course`
--
-- Creation: Dec 26, 2021 at 12:29 AM
--

DROP TABLE IF EXISTS `event_course`;
CREATE TABLE `event_course` (
  `event_id` int(11) NOT NULL,
  `year` year(4) NOT NULL DEFAULT 2021,
  `semester` enum('FALL','SPRING','SUMMER') COLLATE utf8mb4_turkish_ci NOT NULL DEFAULT 'FALL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_course`:
--   `event_id`
--       `event` -> `event_id`
--

--
-- Dumping data for table `event_course`
--

INSERT INTO `event_course` (`event_id`, `year`, `semester`) VALUES
(1, 2021, 'FALL'),
(3, 2021, 'FALL'),
(7, 2021, 'SPRING'),
(8, 2022, 'FALL'),
(9, 2021, 'FALL'),
(10, 2022, 'SPRING'),
(11, 2021, 'FALL'),
(12, 2022, 'SPRING'),
(13, 2020, 'SPRING'),
(14, 2020, 'SUMMER'),
(15, 2020, 'FALL'),
(16, 2020, 'FALL'),
(17, 2021, 'SUMMER'),
(18, 2020, 'SUMMER'),
(19, 2020, 'SPRING'),
(20, 2022, 'SUMMER'),
(21, 2021, 'SPRING'),
(22, 2022, 'SUMMER'),
(23, 2020, 'SPRING'),
(24, 2022, 'SUMMER'),
(25, 2020, 'SUMMER'),
(26, 2021, 'SPRING'),
(27, 2020, 'SPRING'),
(28, 2021, 'SPRING'),
(29, 2020, 'SUMMER'),
(30, 2020, 'SPRING'),
(31, 2022, 'FALL'),
(32, 2022, 'SUMMER'),
(33, 2021, 'SPRING'),
(34, 2022, 'SPRING'),
(35, 2021, 'FALL'),
(36, 2021, 'SPRING'),
(37, 2020, 'FALL'),
(38, 2020, 'SPRING'),
(39, 2021, 'FALL'),
(40, 2020, 'SUMMER'),
(41, 2021, 'SUMMER'),
(42, 2021, 'FALL'),
(43, 2020, 'SPRING'),
(44, 2021, 'SUMMER'),
(45, 2020, 'SUMMER'),
(46, 2022, 'FALL'),
(47, 2021, 'SUMMER'),
(48, 2021, 'SPRING'),
(49, 2020, 'FALL'),
(50, 2021, 'SPRING'),
(51, 2022, 'SPRING'),
(52, 2020, 'FALL'),
(53, 2021, 'SPRING'),
(54, 2021, 'SPRING'),
(55, 2021, 'FALL'),
(56, 2021, 'SUMMER');

-- --------------------------------------------------------

--
-- Table structure for table `event_participation`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `event_participation`;
CREATE TABLE `event_participation` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_participation`:
--   `event_id`
--       `event` -> `event_id`
--   `user_id`
--       `user` -> `id`
--

--
-- Dumping data for table `event_participation`
--

INSERT INTO `event_participation` (`event_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 22101127),
(1, 22101218),
(1, 22101387),
(1, 22102167),
(1, 22102232),
(1, 22103779),
(2, 3),
(2, 5),
(2, 123),
(2, 22101309),
(2, 22101764),
(2, 22102843),
(2, 22103311),
(2, 22103740),
(3, 1),
(3, 2),
(3, 3),
(3, 5),
(3, 22101101),
(3, 22101504),
(3, 22102661),
(3, 22102700),
(3, 22102726),
(3, 22103116),
(3, 22104260),
(4, 1),
(4, 3),
(4, 5),
(4, 22101569),
(4, 22102778),
(4, 22102973),
(4, 22103285),
(4, 22103480),
(5, 1),
(5, 3),
(5, 5),
(5, 22101153),
(5, 22101361),
(5, 22101621),
(5, 22102180),
(5, 22102596),
(5, 22102765),
(5, 22103038),
(5, 22103935),
(5, 22104013),
(6, 3),
(6, 22101036),
(6, 22101062),
(6, 22101231),
(6, 22101361),
(6, 22101530),
(6, 22102232),
(6, 22102674),
(6, 22102830),
(6, 22102895),
(6, 22103233),
(7, 3),
(7, 5),
(7, 22102492),
(7, 22103363),
(7, 22104065),
(8, 5),
(8, 22101400),
(8, 22101738),
(8, 22101868),
(8, 22102063),
(8, 22102089),
(8, 22104078),
(9, 1),
(9, 3),
(9, 22101413),
(9, 22101491),
(9, 22101582),
(9, 22102297),
(9, 22102505),
(9, 22102895),
(9, 22103844),
(9, 22104091),
(10, 1),
(10, 3),
(10, 5),
(10, 22101205),
(10, 22101777),
(10, 22102245),
(10, 22102310),
(10, 22102830),
(10, 22103467),
(10, 22103597),
(11, 1),
(11, 5),
(11, 22101348),
(11, 22101569),
(11, 22104117),
(12, 1),
(12, 5),
(12, 22101322),
(12, 22101725),
(12, 22102193),
(12, 22102622),
(12, 22103597),
(12, 22103896),
(12, 22104065),
(13, 1),
(13, 3),
(13, 5),
(13, 22101478),
(13, 22102518),
(13, 22103857),
(13, 22103948),
(14, 1),
(14, 3),
(14, 5),
(14, 22101348),
(14, 22101634),
(14, 22102102),
(14, 22102206),
(14, 22102544),
(14, 22102934),
(14, 22103532),
(14, 22104195),
(14, 22104247),
(15, 1),
(15, 3),
(15, 5),
(15, 22101114),
(15, 22101140),
(15, 22101725),
(15, 22101920),
(15, 22101985),
(15, 22102154),
(15, 22102297),
(15, 22102349),
(15, 22102908),
(15, 22103844),
(16, 1),
(16, 3),
(16, 5),
(16, 22101855),
(16, 22101933),
(16, 22103129),
(16, 22103246),
(16, 22104247),
(17, 3),
(17, 5),
(17, 22101244),
(17, 22101556),
(17, 22101673),
(17, 22101803),
(17, 22101842),
(17, 22102037),
(17, 22102440),
(17, 22102921),
(17, 22103818),
(17, 22104195),
(18, 1),
(18, 3),
(18, 5),
(18, 22101283),
(18, 22101361),
(18, 22102219),
(18, 22102726),
(18, 22103506),
(19, 22101660),
(19, 22101803),
(19, 22101998),
(19, 22102609),
(19, 22102882),
(20, 1),
(20, 3),
(20, 5),
(20, 22101049),
(20, 22101218),
(20, 22101647),
(20, 22101699),
(20, 22102063),
(20, 22102180),
(20, 22102908),
(20, 22103090),
(20, 22103987),
(21, 1),
(21, 5),
(21, 22101699),
(21, 22101751),
(21, 22102362),
(22, 1),
(22, 5),
(22, 22101946),
(22, 22102024),
(22, 22102258),
(22, 22103025),
(22, 22103558),
(23, 1),
(23, 3),
(23, 22101439),
(23, 22102414),
(23, 22102544),
(23, 22102817),
(23, 22103298),
(23, 22103636),
(24, 1),
(24, 22101179),
(24, 22101283),
(24, 22101517),
(24, 22101777),
(24, 22102440),
(24, 22103688),
(24, 22103701),
(24, 22104234),
(25, 1),
(25, 3),
(25, 5),
(25, 22101270),
(25, 22101699),
(25, 22101751),
(25, 22102050),
(25, 22103233),
(25, 22103818),
(26, 3),
(26, 22101179),
(26, 22101686),
(26, 22102050),
(26, 22102401),
(26, 22102570),
(26, 22102791),
(26, 22104104),
(27, 1),
(27, 3),
(27, 5),
(27, 22101426),
(27, 22101790),
(27, 22101959),
(27, 22102349),
(27, 22102739),
(27, 22102947),
(27, 22103077),
(27, 22103246),
(27, 22103285),
(27, 22103532),
(28, 1),
(28, 3),
(28, 5),
(28, 22101374),
(28, 22102323),
(28, 22102752),
(28, 22102804),
(28, 22103168),
(28, 22103324),
(28, 22103636),
(29, 1),
(29, 3),
(29, 5),
(29, 22101153),
(30, 1),
(30, 3),
(30, 5),
(30, 22101608),
(30, 22101946),
(30, 22102193),
(30, 22102219),
(30, 22102752),
(30, 22103467),
(30, 22103974),
(30, 22104039),
(30, 22104130),
(31, 1),
(31, 3),
(31, 5),
(31, 22101127),
(31, 22101608),
(31, 22102024),
(31, 22104208),
(32, 5),
(32, 22101439),
(32, 22101543),
(32, 22101764),
(32, 22101985),
(32, 22102882),
(32, 22103129),
(33, 1),
(33, 3),
(33, 22101049),
(33, 22101075),
(33, 22101283),
(33, 22101309),
(33, 22101335),
(33, 22102141),
(33, 22102284),
(33, 22102427),
(33, 22103337),
(33, 22104234),
(34, 1),
(34, 3),
(34, 22101062),
(34, 22101101),
(34, 22101647),
(34, 22102076),
(34, 22103792),
(35, 3),
(35, 5),
(35, 22101413),
(35, 22102531),
(35, 22102869),
(35, 22103298),
(35, 22103402),
(35, 22103883),
(35, 22104039),
(36, 3),
(36, 5),
(36, 22101855),
(36, 22102791),
(36, 22102856),
(36, 22103051),
(36, 22103675),
(37, 1),
(37, 3),
(37, 5),
(37, 22101296),
(37, 22101426),
(37, 22101621),
(37, 22101829),
(37, 22102258),
(37, 22102284),
(37, 22103519),
(37, 22103662),
(37, 22104000),
(38, 1),
(38, 3),
(38, 5),
(38, 22101387),
(38, 22101400),
(38, 22101660),
(38, 22101829),
(38, 22102141),
(38, 22102557),
(38, 22102713),
(38, 22102947),
(38, 22103038),
(38, 22103116),
(38, 22104143),
(39, 3),
(39, 5),
(39, 22101205),
(39, 22101257),
(39, 22101452),
(39, 22101582),
(39, 22101959),
(39, 22102804),
(39, 22102869),
(39, 22103142),
(39, 22103155),
(39, 22103376),
(39, 22103610),
(40, 3),
(40, 5),
(40, 22103155),
(41, 5),
(41, 22101400),
(41, 22102648),
(42, 1),
(42, 3),
(42, 5),
(42, 22101140),
(42, 22101842),
(42, 22102583),
(42, 22102687),
(42, 22103922),
(43, 1),
(43, 3),
(43, 5),
(43, 22101686),
(43, 22103064),
(43, 22103207),
(43, 22103441),
(43, 22103558),
(43, 22103688),
(43, 22103766),
(44, 1),
(44, 3),
(44, 5),
(44, 22101595),
(44, 22101894),
(44, 22101972),
(44, 22103623),
(44, 22103961),
(44, 22104091),
(44, 22104104),
(44, 22104182),
(45, 1),
(45, 3),
(45, 5),
(45, 22101114),
(45, 22101491),
(45, 22102310),
(45, 22102479),
(45, 22103324),
(45, 22103493),
(46, 1),
(46, 3),
(46, 5),
(46, 22101166),
(46, 22101218),
(46, 22101478),
(46, 22101803),
(46, 22103389),
(46, 22103428),
(46, 22103883),
(46, 22103948),
(46, 22104078),
(47, 1),
(47, 5),
(47, 22101062),
(47, 22101517),
(47, 22102037),
(47, 22102388),
(47, 22102583),
(47, 22103675),
(48, 1),
(48, 3),
(48, 22101088),
(48, 22101738),
(48, 22101946),
(48, 22102427),
(48, 22103025),
(48, 22103311),
(49, 1),
(49, 3),
(49, 5),
(49, 22101049),
(49, 22101270),
(49, 22101842),
(49, 22102414),
(49, 22103194),
(49, 22103571),
(49, 22103662),
(50, 1),
(50, 3),
(50, 5),
(50, 22101296),
(50, 22101322),
(50, 22101504),
(50, 22102050),
(50, 22102089),
(50, 22102999),
(50, 22103350),
(51, 3),
(51, 5),
(51, 22101114),
(51, 22101127),
(51, 22101530),
(51, 22102037),
(51, 22102128),
(51, 22102284),
(51, 22103909),
(51, 22104013),
(51, 22104143),
(52, 1),
(52, 3),
(52, 22101790),
(52, 22102115),
(52, 22102219),
(52, 22102921),
(52, 22103831),
(52, 22103922),
(52, 22104156),
(53, 1),
(53, 3),
(53, 5),
(53, 22101374),
(53, 22102648),
(53, 22103259),
(53, 22103870),
(54, 1),
(54, 3),
(54, 22101088),
(54, 22101387),
(54, 22101465),
(54, 22103727),
(54, 22103805),
(54, 22104130),
(55, 5),
(55, 22101634),
(55, 22101933),
(55, 22102076),
(55, 22102193),
(55, 22103259),
(55, 22103272),
(56, 1),
(56, 3),
(56, 5),
(56, 22101244),
(56, 22102453),
(57, 1),
(57, 3),
(57, 123),
(57, 22101790),
(57, 22101816),
(57, 22101881),
(57, 22103090),
(58, 3),
(58, 123),
(58, 22101452),
(58, 22101920),
(58, 22103454),
(58, 22103987),
(59, 1),
(59, 5),
(59, 22101751),
(59, 22102206),
(59, 22102635),
(59, 22102843),
(59, 22103649),
(60, 1),
(60, 3),
(60, 5),
(60, 22101374),
(61, 5),
(61, 22102115),
(61, 22102323),
(61, 22102596),
(61, 22103935),
(62, 3),
(62, 22101465),
(62, 22101673),
(62, 22101907),
(62, 22102557),
(62, 22103415),
(62, 22103727),
(62, 22104156),
(63, 1),
(63, 5),
(63, 22101192),
(63, 22101829),
(63, 22102466),
(63, 22102960),
(64, 1),
(64, 3),
(64, 5),
(64, 22102102),
(64, 22102115),
(64, 22102609),
(64, 22102700),
(64, 22104169),
(64, 22104182),
(65, 1),
(65, 22101413),
(65, 22101894),
(65, 22102102),
(65, 22102687),
(65, 22102934),
(65, 22103571),
(65, 22103753),
(66, 1),
(66, 3),
(66, 22101959),
(66, 22101998),
(66, 22102492),
(66, 22102765),
(66, 22103220),
(66, 22104169),
(67, 1),
(67, 3),
(67, 123),
(67, 22101075),
(67, 22101712),
(67, 22101907),
(67, 22101985),
(67, 22102310),
(67, 22102401),
(67, 22102973),
(67, 22103961),
(68, 3),
(68, 5),
(68, 22101647),
(68, 22101712),
(68, 22101816),
(68, 22101920),
(68, 22102167),
(68, 22102180),
(68, 22102336),
(68, 22103194),
(68, 22103623),
(68, 22103766),
(69, 1),
(69, 3),
(69, 5),
(69, 22101231),
(69, 22101543),
(69, 22101972),
(69, 22102960),
(69, 22103168),
(69, 22104000),
(69, 22104208),
(70, 1),
(70, 3),
(70, 5),
(70, 22101504),
(70, 22101673),
(70, 22102128),
(70, 22102297),
(70, 22102622),
(70, 22102739),
(71, 1),
(71, 22101023),
(71, 22101036),
(71, 22101192),
(71, 22101764),
(71, 22102271),
(71, 22103272),
(71, 22103857),
(72, 1),
(72, 3),
(72, 22101309),
(72, 22102128),
(72, 22102856),
(72, 22103012),
(72, 22103181),
(72, 22103350),
(72, 22103649),
(72, 22103896),
(73, 3),
(73, 5),
(73, 22101166),
(73, 22101270),
(73, 22102154),
(73, 22102375),
(73, 22103545),
(73, 22104052),
(74, 1),
(74, 3),
(74, 5),
(74, 22102011),
(74, 22103428),
(74, 22103493),
(74, 22103779),
(74, 22103909),
(75, 1),
(75, 3),
(75, 5),
(75, 22101335),
(75, 22101868),
(75, 22102024),
(75, 22103415),
(76, 1),
(76, 3),
(76, 5),
(76, 22101595),
(76, 22101907),
(76, 22103714),
(77, 1),
(77, 3),
(77, 5),
(77, 22101231),
(77, 22101322),
(78, 5),
(78, 22101140),
(78, 22101179),
(78, 22101933),
(78, 22103740),
(79, 1),
(79, 3),
(79, 5),
(79, 22101686),
(79, 22103376),
(79, 22103402),
(79, 22103506),
(79, 22103610),
(80, 1),
(80, 3),
(80, 5),
(80, 22102076),
(80, 22102089),
(80, 22102258),
(80, 22103103),
(81, 3),
(81, 5),
(81, 22101452),
(81, 22101621),
(81, 22102063),
(81, 22102245),
(81, 22103753),
(81, 22104052),
(82, 1),
(82, 3),
(82, 5),
(82, 22101478),
(82, 22102531),
(82, 22103181),
(82, 22103220),
(83, 1),
(83, 3),
(83, 5),
(83, 22101582),
(83, 22101712),
(83, 22102999),
(83, 22103012),
(83, 22104026),
(84, 1),
(84, 3),
(84, 5),
(84, 22101075),
(84, 22101166),
(84, 22101543),
(84, 22101608),
(84, 22101725),
(84, 22102141),
(84, 22103077),
(84, 22103103),
(84, 22103337),
(85, 1),
(85, 3),
(85, 5),
(85, 22102336),
(85, 22102479),
(85, 22102505),
(85, 22103207),
(85, 22103714),
(86, 3),
(86, 5),
(86, 22101465),
(86, 22101491),
(86, 22101556),
(86, 22101868),
(86, 22102362),
(86, 22102986),
(86, 22103064),
(86, 22103363),
(86, 22103870),
(86, 22104026),
(87, 1),
(87, 3),
(87, 5),
(87, 22101101),
(87, 22101998),
(87, 22102232),
(87, 22102570),
(87, 22103389),
(87, 22104117),
(88, 1),
(88, 3),
(88, 5),
(88, 22101257),
(89, 1),
(89, 3),
(89, 5),
(89, 22101023),
(89, 22101335),
(89, 22101894),
(89, 22102518),
(89, 22103454),
(89, 22103792),
(90, 3),
(90, 5),
(90, 22101036),
(90, 22101088),
(90, 22101205),
(90, 22101595),
(90, 22101777),
(90, 22103519),
(90, 22103545),
(90, 22103584),
(91, 1),
(91, 3),
(91, 5),
(91, 22101530),
(91, 22102271),
(91, 22102466),
(91, 22102778),
(92, 1),
(92, 3),
(92, 5),
(92, 22101023),
(92, 22101296),
(92, 22101634),
(92, 22101816),
(92, 22102713),
(92, 22102986),
(92, 22104260),
(93, 1),
(93, 3),
(93, 5),
(93, 22101556),
(93, 22101972),
(93, 22102154),
(93, 22103480),
(94, 1),
(94, 5),
(94, 22101881),
(94, 22102206),
(94, 22102375),
(94, 22102817),
(94, 22104221),
(95, 3),
(95, 5),
(95, 22101426),
(95, 22101569),
(95, 22102011),
(95, 22103701),
(95, 22103805),
(96, 1),
(96, 3),
(96, 5),
(96, 22102167),
(96, 22102245),
(97, 1),
(97, 3),
(97, 22101855),
(97, 22101881),
(97, 22103051),
(97, 22103142),
(98, 1),
(98, 3),
(98, 5),
(98, 22101192),
(98, 22101244),
(98, 22101439),
(98, 22102453),
(98, 22102661),
(98, 22103584),
(98, 22103831),
(99, 1),
(99, 3),
(99, 22101153),
(99, 22101348),
(99, 22101517),
(99, 22101660),
(99, 22102011),
(99, 22102271),
(99, 22103441),
(99, 22103974),
(99, 22104221),
(100, 22102388),
(100, 22102635);

-- --------------------------------------------------------

--
-- Table structure for table `event_sport`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `event_sport`;
CREATE TABLE `event_sport` (
  `event_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_sport`:
--   `event_id`
--       `event` -> `event_id`
--

--
-- Dumping data for table `event_sport`
--

INSERT INTO `event_sport` (`event_id`, `start_date`, `end_date`) VALUES
(2, '2021-12-16 08:40:00', '2021-12-16 09:40:00'),
(4, '2021-12-17 08:40:00', '2021-12-17 09:40:00'),
(56, '2020-12-25 19:00:00', '2020-12-25 19:59:59'),
(57, '2020-12-23 15:00:00', '2020-12-23 15:59:59'),
(58, '2020-12-27 01:00:00', '2020-12-27 01:59:59'),
(59, '2020-12-28 11:00:00', '2020-12-28 11:59:59'),
(60, '2020-12-24 01:00:00', '2020-12-24 01:59:59'),
(61, '2020-12-24 12:00:00', '2020-12-24 12:59:59'),
(62, '2020-12-23 01:00:00', '2020-12-23 01:59:59'),
(63, '2020-12-22 20:00:00', '2020-12-22 20:59:59'),
(64, '2020-12-23 14:00:00', '2020-12-23 14:59:59'),
(65, '2020-12-24 20:00:00', '2020-12-24 20:59:59'),
(66, '2020-12-28 05:00:00', '2020-12-28 05:59:59'),
(67, '2020-12-26 20:00:00', '2020-12-26 20:59:59'),
(68, '2020-12-28 11:00:00', '2020-12-28 11:59:59'),
(69, '2020-12-23 21:00:00', '2020-12-23 21:59:59'),
(70, '2020-12-26 09:00:00', '2020-12-26 09:59:59'),
(71, '2020-12-23 01:00:00', '2020-12-23 01:59:59'),
(72, '2020-12-28 13:00:00', '2020-12-28 13:59:59'),
(73, '2020-12-25 07:00:00', '2020-12-25 07:59:59'),
(74, '2020-12-27 23:00:00', '2020-12-27 23:59:59'),
(75, '2020-12-25 05:00:00', '2020-12-25 05:59:59'),
(76, '2020-12-25 09:00:00', '2020-12-25 09:59:59'),
(77, '2020-12-28 17:00:00', '2020-12-28 17:59:59'),
(78, '2020-12-26 14:00:00', '2020-12-26 14:59:59'),
(79, '2020-12-26 00:00:00', '2020-12-26 00:59:59'),
(80, '2020-12-28 10:00:00', '2020-12-28 10:59:59'),
(81, '2020-12-23 19:00:00', '2020-12-23 19:59:59'),
(82, '2020-12-25 03:00:00', '2020-12-25 03:59:59'),
(83, '2020-12-28 07:00:00', '2020-12-28 07:59:59'),
(84, '2020-12-28 12:00:00', '2020-12-28 12:59:59'),
(85, '2020-12-23 15:00:00', '2020-12-23 15:59:59'),
(86, '2020-12-24 06:00:00', '2020-12-24 06:59:59'),
(87, '2020-12-25 23:00:00', '2020-12-25 23:59:59'),
(88, '2020-12-23 16:00:00', '2020-12-23 16:59:59'),
(89, '2020-12-23 01:00:00', '2020-12-23 01:59:59'),
(90, '2020-12-28 02:00:00', '2020-12-28 02:59:59'),
(91, '2020-12-24 04:00:00', '2020-12-24 04:59:59'),
(92, '2020-12-26 09:00:00', '2020-12-26 09:59:59'),
(93, '2020-12-28 11:00:00', '2020-12-28 11:59:59'),
(94, '2020-12-25 00:00:00', '2020-12-25 00:59:59'),
(95, '2020-12-23 01:00:00', '2020-12-23 01:59:59'),
(96, '2020-12-28 08:00:00', '2020-12-28 08:59:59'),
(97, '2020-12-26 06:00:00', '2020-12-26 06:59:59'),
(98, '2020-12-27 07:00:00', '2020-12-27 07:59:59'),
(99, '2020-12-23 04:00:00', '2020-12-23 04:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `event_test_appointment`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `event_test_appointment`;
CREATE TABLE `event_test_appointment` (
  `event_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `event_test_appointment`:
--   `event_id`
--       `event` -> `event_id`
--

--
-- Dumping data for table `event_test_appointment`
--

INSERT INTO `event_test_appointment` (`event_id`, `start_date`, `end_date`) VALUES
(5, '2021-12-16 08:40:00', '2021-12-16 09:40:00'),
(6, '2021-12-17 08:40:00', '2021-12-17 09:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'user may prefer to be anonymous',
  `type` enum('UNIVERSITY_REGULATIONS','UNIVERSITY_FEEDBACK','WEB_APP_SUGGESTION','WEB_APP_PROBLEMS') COLLATE utf8mb4_turkish_ci NOT NULL,
  `comment` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `document` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `feedback`:
--

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_token`
--
-- Creation: Dec 25, 2021 at 10:19 PM
-- Last update: Dec 26, 2021 at 01:52 AM
--

DROP TABLE IF EXISTS `password_reset_token`;
CREATE TABLE `password_reset_token` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `generation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `password_reset_token`:
--

--
-- Dumping data for table `password_reset_token`
--

INSERT INTO `password_reset_token` (`id`, `user_id`, `token`, `generation_date`) VALUES
(37, 1, 'fcaee1faca19fda1ddfd81085146bb0658a552704fda5417eb9edc3fe21b78c4', '2021-12-26 01:52:29');

-- --------------------------------------------------------

--
-- Stand-in structure for view `sport`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `sport`;
CREATE TABLE `sport` (
`event_id` int(11)
,`start_date` datetime
,`end_date` datetime
,`event_name` varchar(255)
,`place` varchar(255)
,`max_no_of_participant` int(11)
,`can_people_join` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `sports_center_staff`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `sports_center_staff`;
CREATE TABLE `sports_center_staff` (
`id` int(11)
,`index_id` int(11)
,`name` varchar(255)
,`lastname` varchar(255)
,`email` varchar(255)
,`password_hash` varchar(255)
,`hescode` char(10)
,`hescode_status` tinyint(1)
,`profile_picture` blob
);

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
,`hescode_status` tinyint(1)
,`profile_picture` blob
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `test_appointment`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `test_appointment`;
CREATE TABLE `test_appointment` (
`event_id` int(11)
,`event_name` varchar(255)
,`place` varchar(255)
,`max_no_of_participant` int(11)
,`can_people_join` tinyint(1)
,`start_date` datetime
,`end_date` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `university_administration`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `university_administration`;
CREATE TABLE `university_administration` (
`id` int(11)
,`index_id` int(11)
,`name` varchar(255)
,`lastname` varchar(255)
,`email` varchar(255)
,`password_hash` varchar(255)
,`hescode` char(10)
,`hescode_status` tinyint(1)
,`profile_picture` blob
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
-- Creation: Dec 25, 2021 at 10:02 PM
-- Last update: Dec 26, 2021 at 01:55 AM
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
  `hescode_status` tinyint(1) NOT NULL DEFAULT 1,
  `profile_picture` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `user`:
--

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`index_id`, `id`, `name`, `lastname`, `email`, `password_hash`, `hescode`, `hescode_status`, `profile_picture`) VALUES
(1, 1, 'Muhammed Can', 'Küçükaslan', 'muhammedcankucukaslan@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ZEhWcWZBTDFCQWJqUDYveg$XUE+jeVrAURW002U/lTvUWs4tym+5944DwlIWeTOEts', 'HESCODEHES', 1, NULL),
(2, 2, 'Mustafa', 'K', 'a@b', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', 'random', 1, NULL),
(3, 3, 'Mustafa', 'K', 'a@c', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', '122', 1, NULL),
(5, 5, 'Manmoon', 'Fisher', 'a@e', '$2a$10$j7fjSm.dNIIo7ovzBEIU7udL.IHKWl2X2ydCVm/cJHhyE50np9kw2', '1234567890', 1, NULL),
(6, 123, 'Tanrıkulu', 'Ecdad', '123@123', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'feqfqf13', 1, NULL),
(9, 11, 'Register', 'Tester', 'q@w', '$argon2i$v=19$m=65536,t=4,p=1$T0FxL05xcGlac2p0cnNEYQ$i3nCwT70kdtT4wlZJCLfU06fBu0rK8m78Yzj3cAxTO8', '1f31ff', 1, NULL),
(10, 907, 'utku', 'jjjj', 'gg@ggsdjsıg.vom', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', '1f31f31f1', 1, NULL),
(11, 22101023, 'Vladimir', 'Carroll', 'cursus@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'WE15DBP7OT', 1, NULL),
(12, 22101036, 'Blake', 'Haley', 'adipiscing@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'WD82UTS3RX', 1, NULL),
(13, 22101049, 'Bianca', 'Lopez', 'molestie@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'FW15ULO4YI', 1, NULL),
(14, 22101062, 'Ryan', 'Holland', 'ullamcorper.velit@hotmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'LB16QSD2HN', 1, NULL),
(15, 22101075, 'Josiah', 'O\'Neill', 'vulputate.posuere@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', 'OX59NIU4SQ', 1, NULL),
(16, 22101088, 'Rosalyn', 'Hooper', 'massa@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(17, 22101101, 'Brandon', 'Neal', 'lobortis.mauris.suspendisse@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(18, 22101114, 'Jocelyn', 'Sykes', 'duis.a.mi@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(19, 22101127, 'Georgia', 'Rich', 'cum.sociis@hotmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(20, 22101140, 'Brennan', 'Prince', 'orci.adipiscing.non@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(26, 22101153, 'Quon', 'Gilmore', 'vivamus.nisi.mauris@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(27, 22101166, 'Craig', 'Mcfadden', 'aliquam@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(28, 22101179, 'Teegan', 'Caldwell', 'pede.cum@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(29, 22101192, 'Barry', 'Bradley', 'ligula@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(30, 22101205, 'Kimberly', 'Hubbard', 'nibh.sit.amet@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(31, 22101218, 'Tanek', 'Rowland', 'aliquam.rutrum.lorem@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(32, 22101231, 'Kevin', 'Sandoval', 'rutrum.justo.praesent@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(33, 22101244, 'Devin', 'Raymond', 'sodales.nisi@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(34, 22101257, 'Isadora', 'Wilkins', 'duis.ac@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(35, 22101270, 'Zephr', 'Boyd', 'vulputate.dui.nec@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(36, 22101283, 'Walter', 'Blevins', 'elit@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(37, 22101296, 'Keefe', 'Richards', 'cursus@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(38, 22101309, 'Jeremy', 'O\'connor', 'adipiscing.non@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(39, 22101322, 'Eaton', 'Burch', 'nulla.tempor@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(40, 22101335, 'Ralph', 'Scott', 'justo.eu.arcu@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(41, 22101348, 'Nita', 'Roman', 'nullam.vitae.diam@hotmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(42, 22101361, 'Julie', 'Holloway', 'sit@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(43, 22101374, 'Wendy', 'Jordan', 'pede.cum.sociis@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(44, 22101387, 'Carolyn', 'Kramer', 'aliquet.odio@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(45, 22101400, 'Driscoll', 'Robbins', 'laoreet.libero@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(46, 22101413, 'Travis', 'Noble', 'urna.nec@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(47, 22101426, 'Troy', 'Jacobson', 'lobortis.nisi@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(48, 22101439, 'Denton', 'Callahan', 'etiam@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(49, 22101452, 'Cadman', 'Crane', 'dolor.elit@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(50, 22101465, 'Liberty', 'Sargent', 'adipiscing.elit@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(51, 22101478, 'Odysseus', 'Hood', 'sit.amet.orci@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(52, 22101491, 'Patricia', 'Ellison', 'non.dapibus@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(53, 22101504, 'Neve', 'Bentley', 'mauris.suspendisse@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(54, 22101517, 'Colin', 'Key', 'gravida.molestie@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(55, 22101530, 'TaShya', 'Carey', 'enim.etiam@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(56, 22101543, 'Cullen', 'Frazier', 'ipsum.phasellus@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(57, 22101556, 'Cailin', 'Thornton', 'lorem.ac@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(58, 22101569, 'Zahir', 'Mcintyre', 'ipsum.porta@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(59, 22101582, 'Courtney', 'Flores', 'et.netus.et@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(60, 22101595, 'Abigail', 'Benjamin', 'sem@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(61, 22101608, 'Basia', 'Daugherty', 'lobortis@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(62, 22101621, 'Cailin', 'Saunders', 'odio.tristique@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(63, 22101634, 'Megan', 'Owens', 'ullamcorper@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(64, 22101647, 'Salvador', 'Knight', 'turpis.aliquam.adipiscing@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(65, 22101660, 'Reed', 'Baker', 'eu.metus@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(66, 22101673, 'Zorita', 'Baker', 'fringilla.euismod@google.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(67, 22101686, 'Arsenio', 'Whitehead', 'id.mollis@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(68, 22101699, 'Wesley', 'Merritt', 'lectus.pede@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(69, 22101712, 'Stacey', 'Norman', 'at.fringilla.purus@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(70, 22101725, 'Madaline', 'Petty', 'egestas.aliquam@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(71, 22101738, 'Ifeoma', 'Fitzpatrick', 'vitae.sodales.at@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(72, 22101751, 'Lacota', 'Vazquez', 'augue.id@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(73, 22101764, 'Herman', 'Hess', 'mattis.ornare@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(74, 22101777, 'Wallace', 'Bullock', 'placerat.eget.venenatis@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(75, 22101790, 'Dacey', 'Humphrey', 'sit.amet@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(76, 22101803, 'Mary', 'Hunt', 'sapien.aenean@protonmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(77, 22101816, 'Alexandra', 'Silva', 'mauris@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(78, 22101829, 'Melinda', 'Bruce', 'pharetra.sed@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(79, 22101842, 'Erica', 'Hahn', 'sit.amet@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(80, 22101855, 'Abel', 'Newton', 'eget.lacus.mauris@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(81, 22101868, 'Kerry', 'Fox', 'in.nec.orci@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(82, 22101881, 'Cadman', 'Delaney', 'pellentesque.ut.ipsum@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(83, 22101894, 'Daryl', 'Hart', 'nisl.quisque.fringilla@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(84, 22101907, 'Yoko', 'Sanford', 'lectus.quis.massa@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(85, 22101920, 'Eve', 'Spencer', 'nullam@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(86, 22101933, 'Mona', 'Martin', 'nisi@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(87, 22101946, 'Burton', 'Gilmore', 'quisque@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(88, 22101959, 'Remedios', 'Munoz', 'molestie@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(89, 22101972, 'Nehru', 'Sargent', 'cum.sociis.natoque@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(90, 22101985, 'Drew', 'Love', 'quis.pede@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(91, 22101998, 'Whoopi', 'Franco', 'sapien.gravida@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(92, 22102011, 'Cade', 'Richardson', 'lorem.vehicula.et@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(93, 22102024, 'Holmes', 'Kaufman', 'enim.consequat@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(94, 22102037, 'Perry', 'Mccray', 'vel.arcu@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(95, 22102050, 'Odette', 'Whitney', 'duis.at.lacus@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(96, 22102063, 'Uma', 'Duncan', 'aliquam.erat@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(97, 22102076, 'Doris', 'Griffin', 'in.mi@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(98, 22102089, 'Prescott', 'Vaughn', 'magna.cras@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(99, 22102102, 'Vladimir', 'Gilmore', 'adipiscing.ligula@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(100, 22102115, 'Isaac', 'Crane', 'ornare.lectus@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(101, 22102128, 'Allen', 'Medina', 'at@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(102, 22102141, 'Shellie', 'Pena', 'cursus.vestibulum@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(103, 22102154, 'Amanda', 'Bates', 'tempor.diam@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(104, 22102167, 'Marah', 'Lang', 'vulputate.lacus@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(105, 22102180, 'Montana', 'Henderson', 'sed.id@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(106, 22102193, 'Signe', 'Gordon', 'penatibus@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(107, 22102206, 'Zia', 'Huffman', 'eu@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(108, 22102219, 'Carla', 'Bruce', 'et.ultrices.posuere@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(109, 22102232, 'Garrett', 'Rhodes', 'nec.quam@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(110, 22102245, 'Jeremy', 'Hunter', 'magna@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(111, 22102258, 'Evan', 'Lawson', 'ligula@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(112, 22102271, 'Kuame', 'Freeman', 'magna@protonmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(113, 22102284, 'Aidan', 'Albert', 'sed.libero@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(114, 22102297, 'Daquan', 'Sellers', 'magna.praesent.interdum@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(115, 22102310, 'Jacob', 'O\'donnell', 'auctor.ullamcorper@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(116, 22102323, 'Josephine', 'Tate', 'nulla.dignissim@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(117, 22102336, 'Elmo', 'Valenzuela', 'consequat.auctor@yahoo.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(118, 22102349, 'Jescie', 'Hartman', 'lorem.donec@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(119, 22102362, 'Yasir', 'Frederick', 'lectus.rutrum.urna@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(120, 22102375, 'Matthew', 'Richardson', 'est.mollis.non@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(121, 22102388, 'Kennedy', 'Workman', 'arcu.vel@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(122, 22102401, 'Hyacinth', 'Delgado', 'diam@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(123, 22102414, 'Lee', 'Russell', 'nec.eleifend@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(124, 22102427, 'Anjolie', 'Case', 'nisl.elementum@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(125, 22102440, 'Sonya', 'Sparks', 'enim@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(126, 22102453, 'Kiara', 'Vasquez', 'enim.curabitur.massa@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(127, 22102466, 'Ifeoma', 'Fulton', 'ullamcorper.duis@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(128, 22102479, 'Colleen', 'Bush', 'et.magna@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(129, 22102492, 'Bevis', 'Vazquez', 'sed.neque.sed@protonmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(130, 22102505, 'Dominic', 'Dunlap', 'velit.aliquam@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(131, 22102518, 'Amal', 'Peterson', 'enim@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(132, 22102531, 'Margaret', 'Joyner', 'eget.mollis.lectus@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(133, 22102544, 'Roanna', 'Parrish', 'cursus.et@yahoo.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(134, 22102557, 'Carly', 'Singleton', 'ante@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(135, 22102570, 'Rachel', 'Douglas', 'diam.sed@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(136, 22102583, 'Joseph', 'Fields', 'penatibus.et@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(137, 22102596, 'Kadeem', 'Park', 'ultrices.mauris.ipsum@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(138, 22102609, 'Robert', 'Herrera', 'arcu.aliquam@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(139, 22102622, 'Martha', 'Chan', 'curabitur@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(140, 22102635, 'Griffith', 'Jacobs', 'eu.metus@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(141, 22102648, 'Colt', 'Simpson', 'vulputate@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(142, 22102661, 'Sandra', 'Pratt', 'ac.nulla.in@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(143, 22102674, 'Rhona', 'Sweet', 'ornare.in@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(144, 22102687, 'Ezra', 'Giles', 'nibh@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(145, 22102700, 'Fulton', 'Foster', 'orci.ut.semper@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(146, 22102713, 'Moses', 'Castillo', 'dolor.tempus@yahoo.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(147, 22102726, 'Brendan', 'Mccullough', 'libero.est@hotmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(148, 22102739, 'Tanner', 'Sargent', 'erat.nonummy@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(149, 22102752, 'Noel', 'Mccray', 'erat.neque@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(150, 22102765, 'Belle', 'Carey', 'ut@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(151, 22102778, 'Zenaida', 'Duffy', 'parturient.montes.nascetur@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(152, 22102791, 'Camden', 'Gay', 'vestibulum.mauris.magna@yahoo.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(153, 22102804, 'Clinton', 'Craft', 'mi@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(154, 22102817, 'Gary', 'Daniels', 'aliquam.iaculis@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(155, 22102830, 'Freya', 'Bowers', 'blandit.mattis.cras@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(156, 22102843, 'Lydia', 'Patterson', 'a.nunc.in@hotmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(157, 22102856, 'Dieter', 'Cash', 'integer.eu.lacus@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(158, 22102869, 'Kathleen', 'Keith', 'sollicitudin@icloud.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(159, 22102882, 'Risa', 'Warner', 'nibh.aliquam.ornare@hotmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(160, 22102895, 'Ivor', 'French', 'nec.eleifend.non@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(161, 22102908, 'Flynn', 'Carney', 'gravida@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(162, 22102921, 'Bethany', 'Oneal', 'nonummy.ut.molestie@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(163, 22102934, 'Jerome', 'Clements', 'quisque.varius@hotmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(164, 22102947, 'Marvin', 'Mckinney', 'mauris.eu@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(165, 22102960, 'Lamar', 'Yang', 'mollis.phasellus.libero@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(166, 22102973, 'Stacy', 'Gross', 'id.ante@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(167, 22102986, 'Sarah', 'Rasmussen', 'feugiat.tellus.lorem@icloud.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(168, 22102999, 'Jada', 'Stark', 'ut.odio@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(169, 22103012, 'Kylie', 'Fitzgerald', 'ante@hotmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(170, 22103025, 'Harrison', 'Mendoza', 'hendrerit.neque@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(171, 22103038, 'Joseph', 'Curtis', 'fringilla.ornare.placerat@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(172, 22103051, 'Jaden', 'Goff', 'sed.orci.lobortis@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(173, 22103064, 'Mason', 'Atkinson', 'semper@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(174, 22103077, 'Elaine', 'Lowe', 'dignissim.tempor@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(175, 22103090, 'Arden', 'Berry', 'quis.pede.suspendisse@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(176, 22103103, 'Ryder', 'Curtis', 'eu.euismod.ac@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(177, 22103116, 'Rinah', 'Berry', 'sem.consequat@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(178, 22103129, 'Seth', 'Owen', 'orci.adipiscing.non@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(179, 22103142, 'Clinton', 'Miller', 'purus.ac.tellus@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(180, 22103155, 'Quinn', 'Glover', 'sit.amet@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(181, 22103168, 'Tanisha', 'Quinn', 'rhoncus@yahoo.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(182, 22103181, 'Imani', 'Reyes', 'ornare@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(183, 22103194, 'Levi', 'Burgess', 'sollicitudin@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(184, 22103207, 'Brett', 'Horn', 'suspendisse@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(185, 22103220, 'Jaquelyn', 'Shelton', 'ornare.tortor.at@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(186, 22103233, 'Nissim', 'Underwood', 'congue@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(187, 22103246, 'Ferdinand', 'Nicholson', 'pede.nec.ante@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(188, 22103259, 'Frances', 'Sawyer', 'lacus.quisque@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(189, 22103272, 'Gabriel', 'Maldonado', 'ullamcorper.nisl.arcu@hotmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(190, 22103285, 'Jada', 'Mays', 'nec.luctus.felis@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(191, 22103298, 'Kathleen', 'Hinton', 'litora.torquent@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(192, 22103311, 'Brent', 'Shaffer', 'fringilla.euismod@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(193, 22103324, 'Eleanor', 'Bauer', 'nunc.risus.varius@yahoo.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(194, 22103337, 'Merritt', 'Mcfarland', 'quis.arcu@icloud.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(195, 22103350, 'Meredith', 'Coffey', 'quam.curabitur.vel@hotmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(196, 22103363, 'Nichole', 'Larsen', 'magna.et.ipsum@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(197, 22103376, 'Riley', 'Sullivan', 'sed.dictum.eleifend@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(198, 22103389, 'Ivor', 'Pickett', 'nonummy.ultricies@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(199, 22103402, 'Knox', 'Mclean', 'duis.ac.arcu@hotmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(200, 22103415, 'Nyssa', 'Bass', 'fringilla.purus@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(201, 22103428, 'Ella', 'Luna', 'aliquam.ornare@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(202, 22103441, 'Ramona', 'Miller', 'consequat.nec@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(203, 22103454, 'Ryder', 'Case', 'morbi.vehicula@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(204, 22103467, 'Zachery', 'Booth', 'lectus.quis.massa@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(205, 22103480, 'Zeus', 'Wood', 'dui.lectus.rutrum@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(206, 22103493, 'Aquila', 'Acosta', 'massa@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(207, 22103506, 'Kelly', 'Holloway', 'cursus.integer@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(208, 22103519, 'Renee', 'Pate', 'mauris@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(209, 22103532, 'Keegan', 'Haney', 'mi.duis.risus@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(210, 22103545, 'India', 'Jackson', 'erat@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(211, 22103558, 'Summer', 'Waters', 'ornare.libero.at@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(212, 22103571, 'Carter', 'Flynn', 'venenatis.vel@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(213, 22103584, 'Emerald', 'Neal', 'augue@google.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(214, 22103597, 'Austin', 'Russo', 'euismod.in.dolor@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(215, 22103610, 'Rinah', 'Lancaster', 'a@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(216, 22103623, 'Hayes', 'Wiggins', 'in.mi@hotmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(217, 22103636, 'Jasmine', 'Berg', 'mollis.lectus@hotmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(218, 22103649, 'Timothy', 'Ramos', 'ante.ipsum@icloud.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(219, 22103662, 'Steel', 'Hobbs', 'euismod.ac.fermentum@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(220, 22103675, 'Galvin', 'Carrillo', 'ut.tincidunt@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(221, 22103688, 'Ezekiel', 'Ortega', 'neque.morbi@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(222, 22103701, 'Orson', 'Cain', 'velit.dui@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(223, 22103714, 'Oscar', 'Cummings', 'sit.amet.luctus@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(224, 22103727, 'Neve', 'Figueroa', 'eu@hotmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(225, 22103740, 'Willow', 'Bryan', 'et.nunc@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(226, 22103753, 'Malik', 'Hatfield', 'arcu.vestibulum.ut@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(227, 22103766, 'Aristotle', 'Mcleod', 'sapien.nunc.pulvinar@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(228, 22103779, 'Harlan', 'Vaughan', 'eleifend.nec.malesuada@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(229, 22103792, 'Nadine', 'Daniels', 'quisque@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(230, 22103805, 'Garrett', 'Acosta', 'consequat.purus.maecenas@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(231, 22103818, 'Kennedy', 'Howe', 'commodo.at.libero@hotmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(232, 22103831, 'Acton', 'Dunn', 'scelerisque.neque@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(233, 22103844, 'Quentin', 'Hendricks', 'fusce.aliquam@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(234, 22103857, 'Nero', 'Castillo', 'ac.turpis.egestas@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(235, 22103870, 'Wallace', 'Parsons', 'vehicula.pellentesque@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(236, 22103883, 'Igor', 'Patrick', 'morbi.tristique@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(237, 22103896, 'Wylie', 'Giles', 'dapibus.rutrum@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(238, 22103909, 'Erasmus', 'Sawyer', 'proin.ultrices@protonmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(239, 22103922, 'Wylie', 'Robertson', 'aliquam@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(240, 22103935, 'Brenden', 'Wells', 'ut@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(241, 22103948, 'Thor', 'Fuller', 'eget.ipsum@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(242, 22103961, 'John', 'Mays', 'luctus@hotmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(243, 22103974, 'Nissim', 'Dorsey', 'sociosqu.ad@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(244, 22103987, 'Honorato', 'Lowery', 'risus@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(245, 22104000, 'Armand', 'Herring', 'neque.venenatis@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(246, 22104013, 'Salvador', 'Moon', 'augue@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(247, 22104026, 'Darrel', 'Casey', 'at.egestas.a@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(248, 22104039, 'Ross', 'Deleon', 'a.enim@protonmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(249, 22104052, 'Karly', 'Schultz', 'interdum.nunc@google.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(250, 22104065, 'Anthony', 'Bryant', 'feugiat.placerat.velit@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(251, 22104078, 'Preston', 'Diaz', 'vel.turpis@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(252, 22104091, 'Elizabeth', 'Evans', 'etiam@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(253, 22104104, 'Mechelle', 'Bridges', 'nec.leo.morbi@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(254, 22104117, 'Henry', 'Paul', 'nullam@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(255, 22104130, 'Uta', 'Vaughan', 'libero.at@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(256, 22104143, 'Clio', 'Potter', 'in@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(257, 22104156, 'Katell', 'Velasquez', 'eget.magna@outlook.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(258, 22104169, 'Autumn', 'Davidson', 'dolor@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(259, 22104182, 'Judah', 'Craig', 'cras.eget@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(260, 22104195, 'Kellie', 'Keller', 'pede.ultrices@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(261, 22104208, 'Alyssa', 'Davenport', 'tristique@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(262, 22104221, 'Ava', 'Fitzpatrick', 'ornare@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(263, 22104234, 'Claire', 'Cardenas', 'ridiculus.mus@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(264, 22104247, 'Nissim', 'Noble', 'massa.rutrum@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(265, 22104260, 'Mechelle', 'Strong', 'nec.tempus.scelerisque@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(266, 22104273, 'Venus', 'Hodges', 'rutrum.eu@aol.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(267, 22104286, 'Chloe', 'Bond', 'aenean@icloud.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(268, 22104299, 'Hyacinth', 'Collins', 'non.arcu@aol.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(269, 22104312, 'Cade', 'Hooper', 'erat.nonummy@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(270, 22104325, 'Clio', 'Freeman', 'dolor.sit@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(271, 22104338, 'Leonard', 'Mooney', 'in.condimentum@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(272, 22104351, 'Constance', 'Cote', 'dui.nec@hotmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(273, 22104364, 'Omar', 'Cunningham', 'nam@protonmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(274, 22104377, 'Christopher', 'Bradley', 'consectetuer.adipiscing@hotmail.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(275, 22104390, 'Leilani', 'Bird', 'curae.donec.tincidunt@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(276, 22104403, 'Roth', 'Valentine', 'nam.porttitor@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(277, 22104416, 'Georgia', 'Ellison', 'enim.sit@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(278, 22104429, 'Savannah', 'Levine', 'ut.eros@icloud.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(279, 22104442, 'Derek', 'Howe', 'euismod.enim@outlook.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(280, 22104455, 'Colette', 'Herring', 'et@yahoo.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(281, 22104468, 'Amelia', 'Cleveland', 'natoque.penatibus@outlook.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(282, 22104481, 'Jael', 'Gilbert', 'nibh.lacinia@protonmail.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(283, 22104494, 'Ocean', 'Chavez', 'eros.non@outlook.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(284, 22104507, 'Ralph', 'Grant', 'dis.parturient.montes@hotmail.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(285, 22104520, 'Sara', 'James', 'lacus.cras.interdum@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(286, 22104533, 'Susan', 'Dalton', 'dictum.eleifend@outlook.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(287, 22104546, 'Remedios', 'Holmes', 'libero.lacus.varius@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(288, 22104559, 'Chanda', 'Moody', 'blandit.congue.in@yahoo.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(289, 22104572, 'Shana', 'Carlson', 'a.sollicitudin.orci@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(290, 22104585, 'Kieran', 'Alexander', 'libero.proin@google.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(291, 22104598, 'Maryam', 'Sellers', 'nec.leo@yahoo.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(292, 22104611, 'Kamal', 'Baxter', 'feugiat.lorem@icloud.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(293, 22104624, 'Allegra', 'Kent', 'ut.tincidunt@google.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(294, 22104637, 'Olivia', 'Waters', 'nec@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(295, 22104650, 'Jaden', 'Dillon', 'arcu.vel@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(296, 22104663, 'Ursula', 'Bernard', 'cras.pellentesque.sed@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(297, 22104676, 'Finn', 'Oneil', 'phasellus.dolor@protonmail.org', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(298, 22104702, 'Halla', 'Christensen', 'ipsum@google.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(299, 22104715, 'Iola', 'Bishop', 'diam.eu@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(300, 22104728, 'Vivien', 'Jacobson', 'varius.nam@aol.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(301, 22104741, 'Jasmine', 'Foster', 'non.enim@hotmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(302, 22104754, 'Reuben', 'Reese', 'diam.nunc@yahoo.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(303, 22104767, 'Sasha', 'Travis', 'amet@outlook.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(304, 22104780, 'Darryl', 'Carney', 'eu.placerat.eget@icloud.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(306, 22104793, 'Ella', 'Zamora', 'luctus@aol.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(307, 22104806, 'Jayme', 'Sawyer', 'dolor.tempus.non@icloud.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(308, 22104819, 'Uma', 'Owen', 'quam.dignissim@aol.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(309, 22104832, 'Hayden', 'Poole', 'egestas@icloud.net', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(310, 22104845, 'Ann', 'Allen', 'molestie@google.com', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(311, 22104858, 'Brenna', 'Mathis', 'ut.lacus@yahoo.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(312, 22104871, 'Tana', 'Fitzpatrick', 'ac.turpis@aol.couk', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(313, 22104884, 'Ahmed', 'Moore', 'vulputate.velit.eu@protonmail.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(314, 22104897, 'Oprah', 'Cervantes', 'tempor@protonmail.ca', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL),
(315, 22104910, 'Colette', 'Ballard', 'cursus@google.edu', '$argon2id$v=19$m=16,t=2,p=1$bmtsc2thYTEydg$nZyzDsRW+HAiuyuMGNV+jg', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_academic_staff`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `user_academic_staff`;
CREATE TABLE `user_academic_staff` (
  `id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `user_academic_staff`:
--   `id`
--       `user` -> `id`
--

--
-- Dumping data for table `user_academic_staff`
--

INSERT INTO `user_academic_staff` (`id`, `registration_date`) VALUES
(1, '2021-12-09 17:13:40'),
(2, '2021-12-09 17:28:10'),
(5, '2021-12-09 17:28:10'),
(123, '2021-12-09 17:28:10'),
(22103623, '2021-12-23 16:35:36'),
(22103636, '2021-12-23 16:35:36'),
(22103649, '2021-12-23 16:35:36'),
(22103662, '2021-12-23 16:35:36'),
(22103675, '2021-12-23 16:35:36'),
(22103688, '2021-12-23 16:35:36'),
(22103701, '2021-12-23 16:35:36'),
(22103714, '2021-12-23 16:35:36'),
(22103727, '2021-12-23 16:35:36'),
(22103740, '2021-12-23 16:35:36'),
(22103753, '2021-12-23 16:35:36'),
(22103766, '2021-12-23 16:35:36'),
(22103779, '2021-12-23 16:35:36'),
(22103792, '2021-12-23 16:35:36'),
(22103805, '2021-12-23 16:35:36'),
(22103818, '2021-12-23 16:35:36'),
(22103831, '2021-12-23 16:35:36'),
(22103844, '2021-12-23 16:35:36'),
(22103857, '2021-12-23 16:35:36'),
(22103870, '2021-12-23 16:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_sports_center_staff`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `user_sports_center_staff`;
CREATE TABLE `user_sports_center_staff` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `user_sports_center_staff`:
--   `id`
--       `user` -> `id`
--

--
-- Dumping data for table `user_sports_center_staff`
--

INSERT INTO `user_sports_center_staff` (`id`) VALUES
(1),
(2),
(3),
(22103883),
(22103896),
(22103909),
(22103922),
(22103935),
(22103948),
(22103961),
(22103974),
(22103987),
(22104000),
(22104013),
(22104026),
(22104039),
(22104052),
(22104065),
(22104078),
(22104091),
(22104104),
(22104117),
(22104130);

-- --------------------------------------------------------

--
-- Table structure for table `user_student`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `user_student`;
CREATE TABLE `user_student` (
  `id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `user_student`:
--   `id`
--       `user` -> `id`
--

--
-- Dumping data for table `user_student`
--

INSERT INTO `user_student` (`id`, `registration_date`) VALUES
(1, '2021-12-09 17:13:40'),
(2, '2021-12-09 17:28:10'),
(3, '2021-12-09 17:28:10'),
(5, '2021-12-09 17:28:10'),
(123, '2021-12-23 16:17:01'),
(22101023, '2021-12-23 16:33:04'),
(22101036, '2021-12-23 16:33:04'),
(22101049, '2021-12-23 16:33:04'),
(22101062, '2021-12-23 16:33:04'),
(22101075, '2021-12-23 16:33:04'),
(22101088, '2021-12-23 16:33:04'),
(22101101, '2021-12-23 16:33:04'),
(22101114, '2021-12-23 16:33:04'),
(22101127, '2021-12-23 16:33:04'),
(22101140, '2021-12-23 16:33:04'),
(22101153, '2021-12-23 16:33:04'),
(22101166, '2021-12-23 16:33:04'),
(22101179, '2021-12-23 16:33:04'),
(22101192, '2021-12-23 16:33:04'),
(22101205, '2021-12-23 16:33:04'),
(22101218, '2021-12-23 16:33:04'),
(22101231, '2021-12-23 16:33:04'),
(22101244, '2021-12-23 16:33:04'),
(22101257, '2021-12-23 16:33:04'),
(22101270, '2021-12-23 16:33:04'),
(22101283, '2021-12-23 16:33:04'),
(22101296, '2021-12-23 16:33:04'),
(22101309, '2021-12-23 16:33:04'),
(22101322, '2021-12-23 16:33:04'),
(22101335, '2021-12-23 16:33:04'),
(22101348, '2021-12-23 16:33:04'),
(22101361, '2021-12-23 16:33:04'),
(22101374, '2021-12-23 16:33:04'),
(22101387, '2021-12-23 16:33:04'),
(22101400, '2021-12-23 16:33:04'),
(22101413, '2021-12-23 16:33:04'),
(22101426, '2021-12-23 16:33:04'),
(22101439, '2021-12-23 16:33:04'),
(22101452, '2021-12-23 16:33:04'),
(22101465, '2021-12-23 16:33:04'),
(22101478, '2021-12-23 16:33:04'),
(22101491, '2021-12-23 16:33:04'),
(22101504, '2021-12-23 16:33:04'),
(22101517, '2021-12-23 16:33:04'),
(22101530, '2021-12-23 16:33:04'),
(22101543, '2021-12-23 16:33:04'),
(22101556, '2021-12-23 16:33:04'),
(22101569, '2021-12-23 16:33:04'),
(22101582, '2021-12-23 16:33:04'),
(22101595, '2021-12-23 16:33:04'),
(22101608, '2021-12-23 16:33:04'),
(22101621, '2021-12-23 16:33:04'),
(22101634, '2021-12-23 16:33:04'),
(22101647, '2021-12-23 16:33:04'),
(22101660, '2021-12-23 16:33:04'),
(22101673, '2021-12-23 16:33:04'),
(22101686, '2021-12-23 16:33:04'),
(22101699, '2021-12-23 16:33:04'),
(22101712, '2021-12-23 16:33:04'),
(22101725, '2021-12-23 16:33:04'),
(22101738, '2021-12-23 16:33:04'),
(22101751, '2021-12-23 16:33:04'),
(22101764, '2021-12-23 16:33:04'),
(22101777, '2021-12-23 16:33:04'),
(22101790, '2021-12-23 16:33:04'),
(22101803, '2021-12-23 16:33:04'),
(22101816, '2021-12-23 16:33:04'),
(22101829, '2021-12-23 16:33:04'),
(22101842, '2021-12-23 16:33:04'),
(22101855, '2021-12-23 16:33:04'),
(22101868, '2021-12-23 16:33:04'),
(22101881, '2021-12-23 16:33:04'),
(22101894, '2021-12-23 16:33:04'),
(22101907, '2021-12-23 16:33:04'),
(22101920, '2021-12-23 16:33:04'),
(22101933, '2021-12-23 16:33:04'),
(22101946, '2021-12-23 16:33:04'),
(22101959, '2021-12-23 16:33:04'),
(22101972, '2021-12-23 16:33:04'),
(22101985, '2021-12-23 16:33:04'),
(22101998, '2021-12-23 16:33:04'),
(22102011, '2021-12-23 16:33:04'),
(22102024, '2021-12-23 16:33:04'),
(22102037, '2021-12-23 16:33:04'),
(22102050, '2021-12-23 16:33:04'),
(22102063, '2021-12-23 16:33:04'),
(22102076, '2021-12-23 16:33:04'),
(22102089, '2021-12-23 16:33:04'),
(22102102, '2021-12-23 16:33:04'),
(22102115, '2021-12-23 16:33:04'),
(22102128, '2021-12-23 16:33:04'),
(22102141, '2021-12-23 16:33:04'),
(22102154, '2021-12-23 16:33:04'),
(22102167, '2021-12-23 16:33:04'),
(22102180, '2021-12-23 16:33:04'),
(22102193, '2021-12-23 16:33:04'),
(22102206, '2021-12-23 16:33:04'),
(22102219, '2021-12-23 16:33:04'),
(22102232, '2021-12-23 16:33:04'),
(22102245, '2021-12-23 16:33:04'),
(22102258, '2021-12-23 16:33:04'),
(22102271, '2021-12-23 16:33:04'),
(22102284, '2021-12-23 16:33:04'),
(22102297, '2021-12-23 16:33:04'),
(22102310, '2021-12-23 16:33:04'),
(22102323, '2021-12-23 16:33:04'),
(22102336, '2021-12-23 16:33:04'),
(22102349, '2021-12-23 16:33:04'),
(22102362, '2021-12-23 16:33:04'),
(22102375, '2021-12-23 16:33:04'),
(22102388, '2021-12-23 16:33:04'),
(22102401, '2021-12-23 16:33:04'),
(22102414, '2021-12-23 16:33:04'),
(22102427, '2021-12-23 16:33:04'),
(22102440, '2021-12-23 16:33:04'),
(22102453, '2021-12-23 16:33:04'),
(22102466, '2021-12-23 16:33:04'),
(22102479, '2021-12-23 16:33:04'),
(22102492, '2021-12-23 16:33:04'),
(22102505, '2021-12-23 16:33:04'),
(22102518, '2021-12-23 16:33:04'),
(22102531, '2021-12-23 16:33:04'),
(22102544, '2021-12-23 16:33:04'),
(22102557, '2021-12-23 16:33:04'),
(22102570, '2021-12-23 16:33:04'),
(22102583, '2021-12-23 16:33:04'),
(22102596, '2021-12-23 16:33:04'),
(22102609, '2021-12-23 16:33:04'),
(22102622, '2021-12-23 16:33:04'),
(22102635, '2021-12-23 16:33:04'),
(22102648, '2021-12-23 16:33:04'),
(22102661, '2021-12-23 16:33:04'),
(22102674, '2021-12-23 16:33:04'),
(22102687, '2021-12-23 16:33:04'),
(22102700, '2021-12-23 16:33:04'),
(22102713, '2021-12-23 16:33:04'),
(22102726, '2021-12-23 16:33:04'),
(22102739, '2021-12-23 16:33:04'),
(22102752, '2021-12-23 16:33:04'),
(22102765, '2021-12-23 16:33:04'),
(22102778, '2021-12-23 16:33:04'),
(22102791, '2021-12-23 16:33:04'),
(22102804, '2021-12-23 16:33:04'),
(22102817, '2021-12-23 16:33:04'),
(22102830, '2021-12-23 16:33:04'),
(22102843, '2021-12-23 16:33:04'),
(22102856, '2021-12-23 16:33:04'),
(22102869, '2021-12-23 16:33:04'),
(22102882, '2021-12-23 16:33:04'),
(22102895, '2021-12-23 16:33:04'),
(22102908, '2021-12-23 16:33:04'),
(22102921, '2021-12-23 16:33:04'),
(22102934, '2021-12-23 16:33:04'),
(22102947, '2021-12-23 16:33:04'),
(22102960, '2021-12-23 16:33:04'),
(22102973, '2021-12-23 16:33:04'),
(22102986, '2021-12-23 16:33:04'),
(22102999, '2021-12-23 16:33:04'),
(22103012, '2021-12-23 16:33:04'),
(22103025, '2021-12-23 16:33:04'),
(22103038, '2021-12-23 16:33:04'),
(22103051, '2021-12-23 16:33:04'),
(22103064, '2021-12-23 16:33:04'),
(22103077, '2021-12-23 16:33:04'),
(22103090, '2021-12-23 16:33:04'),
(22103103, '2021-12-23 16:33:04'),
(22103116, '2021-12-23 16:33:04'),
(22103129, '2021-12-23 16:33:04'),
(22103142, '2021-12-23 16:33:04'),
(22103155, '2021-12-23 16:33:04'),
(22103168, '2021-12-23 16:33:04'),
(22103181, '2021-12-23 16:33:04'),
(22103194, '2021-12-23 16:33:04'),
(22103207, '2021-12-23 16:33:04'),
(22103220, '2021-12-23 16:33:04'),
(22103233, '2021-12-23 16:33:04'),
(22103246, '2021-12-23 16:33:04'),
(22103259, '2021-12-23 16:33:04'),
(22103272, '2021-12-23 16:33:04'),
(22103285, '2021-12-23 16:33:04'),
(22103298, '2021-12-23 16:33:04'),
(22103311, '2021-12-23 16:33:04'),
(22103324, '2021-12-23 16:33:04'),
(22103337, '2021-12-23 16:33:04'),
(22103350, '2021-12-23 16:33:04'),
(22103363, '2021-12-23 16:33:04'),
(22103376, '2021-12-23 16:33:04'),
(22103389, '2021-12-23 16:33:04'),
(22103402, '2021-12-23 16:33:04'),
(22103415, '2021-12-23 16:33:04'),
(22103428, '2021-12-23 16:33:04'),
(22103441, '2021-12-23 16:33:04'),
(22103454, '2021-12-23 16:33:04'),
(22103467, '2021-12-23 16:33:04'),
(22103480, '2021-12-23 16:33:04'),
(22103493, '2021-12-23 16:33:04'),
(22103506, '2021-12-23 16:33:04'),
(22103519, '2021-12-23 16:33:04'),
(22103532, '2021-12-23 16:33:04'),
(22103545, '2021-12-23 16:33:04'),
(22103558, '2021-12-23 16:33:04'),
(22103571, '2021-12-23 16:33:04'),
(22103584, '2021-12-23 16:33:04'),
(22103597, '2021-12-23 16:33:04'),
(22103610, '2021-12-23 16:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_university_administration`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `user_university_administration`;
CREATE TABLE `user_university_administration` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `user_university_administration`:
--   `id`
--       `user` -> `id`
--

--
-- Dumping data for table `user_university_administration`
--

INSERT INTO `user_university_administration` (`id`) VALUES
(1),
(2),
(123),
(22104117),
(22104130),
(22104143),
(22104156),
(22104169),
(22104182),
(22104195),
(22104208),
(22104221),
(22104234),
(22104247),
(22104260),
(22104273),
(22104286),
(22104299),
(22104312),
(22104325),
(22104338),
(22104351),
(22104364);

-- --------------------------------------------------------

--
-- Table structure for table `vaccine`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `vaccine`;
CREATE TABLE `vaccine` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_type` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `vaccine_name` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `manufacturer` varchar(255) COLLATE utf8mb4_turkish_ci NOT NULL,
  `cvx_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `vaccine`:
--

--
-- Dumping data for table `vaccine`
--

INSERT INTO `vaccine` (`vaccine_id`, `vaccine_type`, `vaccine_name`, `manufacturer`, `cvx_code`) VALUES
(1, 'mRNA', 'Pfizer-BioNTech COVID-19 Vaccine', 'Pfizer-BioNTech', 208);

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_administration`
--
-- Creation: Dec 25, 2021 at 10:02 PM
--

DROP TABLE IF EXISTS `vaccine_administration`;
CREATE TABLE `vaccine_administration` (
  `vaccination_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `administration_date` datetime NOT NULL,
  `document` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- RELATIONSHIPS FOR TABLE `vaccine_administration`:
--   `user_id`
--       `user` -> `id`
--   `vaccine_id`
--       `vaccine` -> `vaccine_id`
--

--
-- Dumping data for table `vaccine_administration`
--

INSERT INTO `vaccine_administration` (`vaccination_id`, `vaccine_id`, `user_id`, `administration_date`, `document`) VALUES
(1, 1, 1, '2021-12-13 01:04:00', NULL),
(2, 1, 1, '2021-12-14 23:55:56', NULL),
(3, 1, 1, '2021-12-15 12:16:05', NULL),
(4, 1, 1, '2021-12-19 12:10:34', NULL),
(7, 1, 1, '2021-12-20 23:12:09', NULL);

-- --------------------------------------------------------

--
-- Structure for view `academic_staff`
--
DROP TABLE IF EXISTS `academic_staff`;

DROP VIEW IF EXISTS `academic_staff`;
CREATE VIEW `academic_staff`  AS SELECT `user_academic_staff`.`id` AS `id`, `user_academic_staff`.`registration_date` AS `registration_date`, `user`.`index_id` AS `index_id`, `user`.`name` AS `name`, `user`.`lastname` AS `lastname`, `user`.`email` AS `email`, `user`.`password_hash` AS `password_hash`, `user`.`hescode` AS `hescode`, `user`.`hescode_status` AS `hescode_status`, `user`.`profile_picture` AS `profile_picture` FROM (`user_academic_staff` join `user` on(`user_academic_staff`.`id` = `user`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `course`
--
DROP TABLE IF EXISTS `course`;

DROP VIEW IF EXISTS `course`;
CREATE VIEW `course`  AS SELECT `event_course`.`event_id` AS `event_id`, `event_course`.`year` AS `year`, `event_course`.`semester` AS `semester`, `event`.`event_name` AS `event_name`, `event`.`place` AS `place`, `event`.`max_no_of_participant` AS `max_no_of_participant`, `event`.`can_people_join` AS `can_people_join` FROM (`event_course` join `event` on(`event_course`.`event_id` = `event`.`event_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `sport`
--
DROP TABLE IF EXISTS `sport`;

DROP VIEW IF EXISTS `sport`;
CREATE VIEW `sport`  AS SELECT `event_sport`.`event_id` AS `event_id`, `event_sport`.`start_date` AS `start_date`, `event_sport`.`end_date` AS `end_date`, `event`.`event_name` AS `event_name`, `event`.`place` AS `place`, `event`.`max_no_of_participant` AS `max_no_of_participant`, `event`.`can_people_join` AS `can_people_join` FROM (`event_sport` join `event` on(`event_sport`.`event_id` = `event`.`event_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `sports_center_staff`
--
DROP TABLE IF EXISTS `sports_center_staff`;

DROP VIEW IF EXISTS `sports_center_staff`;
CREATE VIEW `sports_center_staff`  AS SELECT `user_sports_center_staff`.`id` AS `id`, `user`.`index_id` AS `index_id`, `user`.`name` AS `name`, `user`.`lastname` AS `lastname`, `user`.`email` AS `email`, `user`.`password_hash` AS `password_hash`, `user`.`hescode` AS `hescode`, `user`.`hescode_status` AS `hescode_status`, `user`.`profile_picture` AS `profile_picture` FROM (`user_sports_center_staff` join `user` on(`user_sports_center_staff`.`id` = `user`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `student`
--
DROP TABLE IF EXISTS `student`;

DROP VIEW IF EXISTS `student`;
CREATE VIEW `student`  AS SELECT `user_student`.`id` AS `id`, `user_student`.`registration_date` AS `registration_date`, `user`.`index_id` AS `index_id`, `user`.`name` AS `name`, `user`.`lastname` AS `lastname`, `user`.`email` AS `email`, `user`.`password_hash` AS `password_hash`, `user`.`hescode` AS `hescode`, `user`.`hescode_status` AS `hescode_status`, `user`.`profile_picture` AS `profile_picture` FROM (`user_student` join `user` on(`user_student`.`id` = `user`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `test_appointment`
--
DROP TABLE IF EXISTS `test_appointment`;

DROP VIEW IF EXISTS `test_appointment`;
CREATE VIEW `test_appointment`  AS SELECT `event`.`event_id` AS `event_id`, `event`.`event_name` AS `event_name`, `event`.`place` AS `place`, `event`.`max_no_of_participant` AS `max_no_of_participant`, `event`.`can_people_join` AS `can_people_join`, `event_test_appointment`.`start_date` AS `start_date`, `event_test_appointment`.`end_date` AS `end_date` FROM (`event` join `event_test_appointment` on(`event`.`event_id` = `event_test_appointment`.`event_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `university_administration`
--
DROP TABLE IF EXISTS `university_administration`;

DROP VIEW IF EXISTS `university_administration`;
CREATE VIEW `university_administration`  AS SELECT `user_university_administration`.`id` AS `id`, `user`.`index_id` AS `index_id`, `user`.`name` AS `name`, `user`.`lastname` AS `lastname`, `user`.`email` AS `email`, `user`.`password_hash` AS `password_hash`, `user`.`hescode` AS `hescode`, `user`.`hescode_status` AS `hescode_status`, `user`.`profile_picture` AS `profile_picture` FROM (`user_university_administration` join `user` on(`user_university_administration`.`id` = `user`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD UNIQUE KEY `main_user_id` (`main_user_id`,`contacted_user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `contacted_user_id` (`contacted_user_id`);

--
-- Indexes for table `covid_test`
--
ALTER TABLE `covid_test`
  ADD PRIMARY KEY (`test_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`diagnosis_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_control`
--
ALTER TABLE `event_control`
  ADD UNIQUE KEY `event_id_2` (`event_id`,`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `event_course`
--
ALTER TABLE `event_course`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_participation`
--
ALTER TABLE `event_participation`
  ADD UNIQUE KEY `event_id` (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `event_sport`
--
ALTER TABLE `event_sport`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_test_appointment`
--
ALTER TABLE `event_test_appointment`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `password_reset_token`
--
ALTER TABLE `password_reset_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `token` (`token`);

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
-- Indexes for table `user_academic_staff`
--
ALTER TABLE `user_academic_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id_uid` (`id`) USING BTREE;

--
-- Indexes for table `user_sports_center_staff`
--
ALTER TABLE `user_sports_center_staff`
  ADD UNIQUE KEY `sport_staff_id_uindex` (`id`);

--
-- Indexes for table `user_student`
--
ALTER TABLE `user_student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id_uid` (`id`) USING BTREE;

--
-- Indexes for table `user_university_administration`
--
ALTER TABLE `user_university_administration`
  ADD UNIQUE KEY `instructor_id_uindex` (`id`);

--
-- Indexes for table `vaccine`
--
ALTER TABLE `vaccine`
  ADD PRIMARY KEY (`vaccine_id`);

--
-- Indexes for table `vaccine_administration`
--
ALTER TABLE `vaccine_administration`
  ADD PRIMARY KEY (`vaccination_id`),
  ADD KEY `fk_va_user` (`user_id`),
  ADD KEY `fk_va_vaccine` (`vaccine_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `covid_test`
--
ALTER TABLE `covid_test`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `diagnosis`
--
ALTER TABLE `diagnosis`
  MODIFY `diagnosis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_token`
--
ALTER TABLE `password_reset_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `index_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=557;

--
-- AUTO_INCREMENT for table `vaccine`
--
ALTER TABLE `vaccine`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vaccine_administration`
--
ALTER TABLE `vaccine_administration`
  MODIFY `vaccination_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`main_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`),
  ADD CONSTRAINT `contact_ibfk_3` FOREIGN KEY (`contacted_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `covid_test`
--
ALTER TABLE `covid_test`
  ADD CONSTRAINT `covid_test_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_control`
--
ALTER TABLE `event_control`
  ADD CONSTRAINT `event_control_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_control_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`index_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_course`
--
ALTER TABLE `event_course`
  ADD CONSTRAINT `event_course_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_participation`
--
ALTER TABLE `event_participation`
  ADD CONSTRAINT `event_participation_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_participation_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `event_sport`
--
ALTER TABLE `event_sport`
  ADD CONSTRAINT `event_sport_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_test_appointment`
--
ALTER TABLE `event_test_appointment`
  ADD CONSTRAINT `event_test_appointment_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_academic_staff`
--
ALTER TABLE `user_academic_staff`
  ADD CONSTRAINT `fk_uas_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_sports_center_staff`
--
ALTER TABLE `user_sports_center_staff`
  ADD CONSTRAINT `fk_scs_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_student`
--
ALTER TABLE `user_student`
  ADD CONSTRAINT `fk_us_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_university_administration`
--
ALTER TABLE `user_university_administration`
  ADD CONSTRAINT `fk_uua_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vaccine_administration`
--
ALTER TABLE `vaccine_administration`
  ADD CONSTRAINT `fk_va_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_va_vaccine` FOREIGN KEY (`vaccine_id`) REFERENCES `vaccine` (`vaccine_id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
