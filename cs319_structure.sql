-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2021 at 01:26 AM
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
-- Last update: Dec 26, 2021 at 03:21 AM
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
-- Last update: Dec 26, 2021 at 02:51 AM
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
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diagnosis`
--
ALTER TABLE `diagnosis`
  MODIFY `diagnosis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_token`
--
ALTER TABLE `password_reset_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `index_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine`
--
ALTER TABLE `vaccine`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccine_administration`
--
ALTER TABLE `vaccine_administration`
  MODIFY `vaccination_id` int(11) NOT NULL AUTO_INCREMENT;

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
