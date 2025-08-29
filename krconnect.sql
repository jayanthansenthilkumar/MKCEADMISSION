-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 03:49 PM
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
-- Database: `krconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission`
--

CREATE TABLE `admission` (
  `admission_id` int(11) NOT NULL,
  `sid` varchar(30) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `programme` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `doadmission` date DEFAULT NULL,
  `admcate` varchar(50) DEFAULT NULL,
  `admtype` varchar(50) DEFAULT NULL,
  `initial_payment` decimal(10,2) DEFAULT 0.00,
  `status` enum('ADMITTED','PENDING','CONFIRMED','REJECTED') DEFAULT 'ADMITTED',
  `ayear_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admitted_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `admission`
--
DELIMITER $$
CREATE TRIGGER `after_admission_confirm` AFTER UPDATE ON `admission` FOR EACH ROW BEGIN
    IF NEW.status = 'CONFIRMED' THEN
        -- Generate final SID by removing MKCE from admission sid (example logic)
        INSERT INTO sbasic (sid, fname, lname, gender, programme, department, batch, doadmission, admcate, admtype, admission_id, ayear_id, status)
        VALUES (
            REPLACE(NEW.sid, 'MKCE', ''),   -- Convert 26MKCEAL001 -> 26AL001
            NEW.fname,
            NEW.lname,
            NEW.gender,
            NEW.programme,
            NEW.department,
            NEW.batch,
            NEW.doadmission,
            NEW.admcate,
            NEW.admtype,
            NEW.admission_id,
            NEW.ayear_id,
            0
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ayear`
--

CREATE TABLE `ayear` (
  `id` int(11) NOT NULL,
  `ayear` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ayear`
--

INSERT INTO `ayear` (`id`, `ayear`) VALUES
(1, '2021-2022'),
(2, '2022-2023'),
(3, '2023-2024'),
(4, '2024-2025'),
(5, '2025-2026'),
(6, '2026-2027');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `uid` int(100) NOT NULL,
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `ddept` varchar(200) NOT NULL,
  `design` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `manager` varchar(50) NOT NULL DEFAULT 'HOD',
  `doj` varchar(30) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `cert` varchar(100) NOT NULL,
  `bc` int(200) NOT NULL,
  `ac` int(200) NOT NULL,
  `cl` float NOT NULL DEFAULT 0,
  `col` float NOT NULL DEFAULT 0,
  `odb` float NOT NULL DEFAULT 0,
  `odr` float NOT NULL DEFAULT 0,
  `odp` float NOT NULL DEFAULT 0,
  `odo` float NOT NULL DEFAULT 0,
  `vl` float NOT NULL DEFAULT 0,
  `lop` float NOT NULL DEFAULT 0,
  `ml` float NOT NULL DEFAULT 0,
  `mal` float NOT NULL DEFAULT 0,
  `mtl` float NOT NULL DEFAULT 0,
  `ptl` float NOT NULL DEFAULT 0,
  `sl` float NOT NULL DEFAULT 0,
  `spl` float NOT NULL DEFAULT 0,
  `pm` float NOT NULL DEFAULT 0,
  `tenpm` float NOT NULL DEFAULT 0,
  `status` int(20) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`uid`, `id`, `name`, `dept`, `ddept`, `design`, `role`, `manager`, `doj`, `pass`, `cert`, `bc`, `ac`, `cl`, `col`, `odb`, `odr`, `odp`, `odo`, `vl`, `lop`, `ml`, `mal`, `mtl`, `ptl`, `sl`, `spl`, `pm`, `tenpm`, `status`) VALUES
(305, '1113006', 'AMBIKA S', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', 'HOD', 'HOD', '2018-03-01', '1113006', 'images/profile/AMBIKA S1113006.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(306, '1114030', 'GOVINDHARAJ G', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-06-10', '1114030', 'images/profile/GOVINDHARAJ G1114030.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(307, '1112003', 'GURU PRASAD L', 'Freshman Engineering', 'Freshman Engineering', 'Professor', 'Faculty', 'HOD', '2012-06-29', '1112003', 'images/profile/GURU PRASAD L1112003.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(308, '1111004', 'SUBHA S', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2012-06-18', '1111004', 'images/profile/SUBHA S1111004.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(309, '1153002', 'JEYA GANESH KUMAR K', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2021-02-15', 'Sivesh@1209', 'images/profile/JEYA GANESH KUMAR K1153002.JPG', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(311, '1153003', 'NITHYASRI A', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2021-07-15', '1153003', 'images/profile/NITHYASRI A1153003.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(312, '1153004', 'SURESH P', '', '', '', '', 'HOD', '0000-00-00', '1153004', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(313, '1154003', 'VIDHYA P', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2021-09-01', '1154003', 'images/profile/VIDHYA P1154003.jpg', 75, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(314, '1153005', 'JAGANATH M', '', '', '', '', 'HOD', '0000-00-00', '1153005', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(315, '1153006', 'AMSA M', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2022-05-02', '1153006', 'images/profile/AMSA M1153006.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(316, '1153009', 'LAVANYA S', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', 'Faculty', 'HOD', '2022-06-10', '1153009', 'images/profile/LAVANYA S1153009.jpg', 75, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(317, '1114043', 'RANGANATHAN R', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2021-10-20', '1114043', 'images/profile/RANGANATHAN R1114043.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(318, '1155001', 'STALINBABU R', 'Artificial Intelligence and Machine Learning', 'Artificial Intelligence and Machine Learning', 'Assistant Professor', '', 'HOD', '2022-07-20', 'StalinBabu151989', 'images/profile/STALINBABU R1155001.jpeg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(319, '1155002', 'BHARANI NAYAGI S', 'Artificial Intelligence and Machine Learning', 'Artificial Intelligence and Machine Learning', 'Assistant Professor', '', 'HOD', '2022-08-01', '1155002', 'images/profile/BHARANI NAYAGI S1155002.jpeg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(320, '1155003', 'VIJAYAGANTH R', 'Artificial Intelligence and Machine Learning', 'Artificial Intelligence and Machine Learning', 'Assistant Professor', '', 'HOD', '2022-08-04', 'sumithra143', 'images/profile/VIJAYAGANTH R1155003.jpeg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(321, '1155005', 'GOMATHI P', '', '', '', '', 'HOD', '0000-00-00', '1155005', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(322, '1121016', 'DINESHKUMAR R', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2017-06-19', '1121016', 'images/profile/DINESHKUMAR R1121016.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(323, '1121001', 'LOGESHKUMARAN A', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2014-06-02', '1121001', 'images/profile/LOGESHKUMARAN A1121001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(324, '1121008', 'MUKESH P', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2015-12-14', '1121008', 'images/profile/MUKESH P1121008.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(325, '1121012', 'SRINIVASAN N P', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'Principal', '2016-06-13', '1121012', 'images/profile/SRINIVASAN N P1121012.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(326, '1121010', 'RAMKUMAR S', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2016-06-13', '1121010', 'images/profile/RAMKUMAR S1121010.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(327, '1121009', 'BALAJI G', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HR', '2016-06-13', '1121009', 'images/profile/BALAJI G1121009.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(328, '1121013', 'VETTURAYASUDHARSANAN R', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2016-06-13', '1121013', 'images/profile/VETTURAYASUDHARSANAN R1121013.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(329, '1114027', 'YASOTHA S', '', '', '', '', 'HOD', '0000-00-00', '1114027', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(330, '1111013', 'MADHU BHARATHI M', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-06-24', 'butterfly', 'images/profile/MADHU BHARATHI M1111013.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(331, '1121033', 'SENTHIL KUMAR V', 'Civil Engineering', 'Civil Engineering', 'Professor', 'HOD', 'HOD', '2021-08-02', 'Civil@23', 'images/profile/SENTHIL KUMAR V1121033.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(332, '1114042', 'SUBHASRI D', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '1992-06-21', '1114042', 'images/profile/SUBHASRI D1114042.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(333, '1121036', 'SETHURAMAN S', 'Civil Engineering', 'Civil Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-03-20', '1121036', 'images/profile/SETHURAMAN S1121036.png', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(334, '1154001', 'BANUPRIYA V', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'HOD', 'HOD', '2021-02-07', '1154001', 'images/profile/BANUPRIYA V1154001.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(335, '1154002', 'DHIVYA P', '', '', '', '', 'HOD', '0000-00-00', '1154002', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(336, '1154004', 'RAJA GURU R', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Associate Professor', 'HOD', 'HOD', '2021-09-01', '1154004', 'images/profile/RAJA GURU R1154004.pdf', 75, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(337, '1111021', 'SARAVANAN V S', 'Freshman Engineering', 'Freshman Engineering', 'Associate Professor', '', 'HOD', '2021-06-10', '1111021', 'images/profile/SARAVANAN V S1111021.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(338, '1111023', 'PRABHAKARAN S', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2022-04-04', '1111023', 'images/profile/PRABHAKARAN S1111023.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(339, '1154007', 'DHARANI R', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2022-05-02', '1154007', 'images/profile/DHARANI R1154007.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(340, '1154010', 'SEENIVASAN D', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2022-07-13', '1154010', 'images/profile/SEENIVASAN D1154010.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(341, '1154011', 'ISWARYA J', '', '', '', '', 'HOD', '0000-00-00', '1154011', '', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(342, '1114047', 'KARTHICK B', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2022-07-18', '1114047', 'images/profile/KARTHICK B1114047.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(343, '1154012', 'DURAI MURUGAN A', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2023-03-01', '1154012', 'images/profile/DURAI MURUGAN A1154012.jpeg', 75, 25, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(344, '1151001', 'THILAGAMANI S', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Professor', 'Faculty', 'HOD', '2007-06-15', '1151001', 'images/profile/THILAGAMANI S1151001.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(345, '1151017', 'PANDIARAJA P', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Associate Professor', 'Faculty', 'HOD', '2018-06-18', '1151017', 'images/profile/PANDIARAJA P1151017.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(346, '1151018', 'JOSE TRINY K', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2018-06-18', '1151018', 'images/profile/JOSE TRINY K1151018.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(347, '1151004', 'PADMINI DEVI B', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Associate Professor', 'Faculty', 'HOD', '2006-06-23', '1151004', 'images/profile/PADMINI DEVI B1151004.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(348, '1151006', 'MANI V', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '1984-04-20', '1151006', 'images/profile/MANI V1151006.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(349, '1151003', 'SELVARATHI C', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2006-06-19', '1151003', 'images/profile/SELVARATHI C1151003.JPG', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(350, '1151033', 'KARTHIK K', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-01-20', '1151033', 'images/profile/KARTHIK K1151033.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(351, '1113002', 'GEETHA S', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2011-10-28', '1113002', 'images/profile/GEETHA S1113002.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(352, '1114002', 'CHITIRAKALA K', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2006-07-05', '1114002', 'images/profile/CHITIRAKALA K1114002.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(353, '1151002', 'SELVI A', 'Computer Science and Engineering', 'Computer Science and Engineering', '', 'Faculty', 'HOD', '2014-06-16', '1151002', 'images/profile/SELVI A1151002.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(354, '1151023', 'KARTHIKA I', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2021-06-07', '1151023', 'images/profile/KARTHIKA I1151023.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(355, '1151024', 'SANTHIYA S', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '0000-00-00', 'neshiya@123', 'images/profile/SANTHIYA S1151024.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(356, '1151036', 'MAKANYADEVI K', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-04', '1151036', 'images/profile/MAKANYADEVI K1151036.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(357, '1151037', 'PRIYA P', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-07-15', '1151037', 'images/profile/PRIYA P1151037.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(358, '1151038', 'PRADEEP D', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Associate Professor', 'Faculty', 'HOD', '2021-03-03', '1151038', 'images/profile/PRADEEP D1151038.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(359, '1151039', 'RAJESHRAM V', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2021-03-10', '1151039', 'images/profile/RAJESHRAM V1151039.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(360, '1114041', 'AMSAVENI P', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2021-03-08', 'nikar2019', 'images/profile/AMSAVENI P1114041.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(361, '1111019', 'HEMASHREE A', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2021-01-20', '1111019', 'images/profile/HEMASHREE A1111019.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(362, '1151042', 'SUJANTHI S', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-02-14', '1151042', 'images/profile/SUJANTHI S1151042.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(363, '1151043', 'NANDHAKUMAR C', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-12-12', '1151043', 'images/profile/NANDHAKUMAR C1151043.png', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(364, '1151044', 'PRIYANKA T', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-02-03', '1151044', 'images/profile/PRIYANKA T1151044.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(365, '1151045', 'GEETHA M', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-02-03', '1151045', 'images/profile/GEETHA M1151045.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(366, '1141001', 'SUNDARARAJU K', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Professor', '', 'HOD', '2002-05-14', '1141001', 'images/profile/SUNDARARAJU K1141001.jpg', 75, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(367, '1141041', 'SUBRAMANIAM G', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2019-06-10', 'hainelson41', 'images/profile/SUBRAMANIAM G1141041.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(368, '1141002', 'KARTHIKEYAN R', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Professor', 'Faculty', 'HOD', '2011-06-08', '1141002', 'images/profile/KARTHIKEYAN R1141002.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(369, '1141042', 'UDHAYA KUMAR A', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2019-06-10', '1141042', 'images/profile/UDHAYA KUMAR A1141042.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(370, '1143001', 'UMA J', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Professor', 'HOD', 'HOD', '2006-01-18', '1143001', 'images/profile/UMA J1143001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(371, '1141005', 'RAMESH M', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2010-06-07', 'san1MURU', 'images/profile/RAMESH M1141005.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(372, '1141003', 'RAJESH KUMAR B', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2011-06-01', '1141003', 'images/profile/RAJESH KUMAR B1141003.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(373, '1141006', 'ISHWARYA S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', '', 'Faculty', 'HOD', '2014-06-16', '1141006', 'images/profile/ISHWARYA S1141006.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(374, '1141007', 'SOMASUNDARAM PL', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-04', '1141007', 'images/profile/SOMASUNDARAM PL1141007.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(375, '1141004', 'MANIRAJ P', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2013-06-12', '1141004', 'images/profile/MANIRAJ P1141004.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(376, '1141010', 'DINESHKUMAR S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2014-06-16', '1141010', 'images/profile/DINESHKUMAR S1141010.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(377, '1141015', 'BANUMATHI S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Professor', '', 'HOD', '2016-06-13', '1141015', 'images/profile/BANUMATHI S1141015.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(378, '1141014', 'JAYAKUMAR V', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'EO', 'HOD', '2016-06-13', '1141014', 'images/profile/JAYAKUMAR V1141014.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(379, '1141016', 'CHOCKALINGAM AL', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2016-06-15', '1141016', 'images/profile/CHOCKALINGAM AL1141016.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(380, '1141018', 'SELVAM N', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2017-06-19', '1141018', 'images/profile/SELVAM N1141018.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(381, '1141019', 'HARIPRABHU M', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2017-06-19', '1141019', 'images/profile/HARIPRABHU M1141019.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(382, '1141047', 'SASIREKHA P', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2019-08-14', '1141047', 'images/profile/SASIREKHA P1141047.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(383, '1141048', 'SIVAKUMAR A', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-01-20', '1141048', 'images/profile/SIVAKUMAR A1141048.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(384, '1141049', 'UMAPATHI K', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Associate Professor', 'Faculty', 'HOD', '2019-06-10', '1141049', 'images/profile/UMAPATHI K1141049.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(385, '1143005', 'KIRUTHIKA S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2015-06-04', '1143005', 'images/profile/KIRUTHIKA S1143005.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(386, '1143003', 'SARAVANAN V', '', '', '', '', 'HOD', '0000-00-00', '1143003', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(387, '1113003', 'PADMAVATHI R', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2017-06-19', '1113003', 'images/profile/PADMAVATHI R1113003.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(388, '1112006', 'NATCHIMUTHU V', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2018-01-03', '1112006', 'images/profile/NATCHIMUTHU V1112006.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(389, '1114028', 'NIVETHITHA K', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-06-10', '1114028', 'images/profile/NIVETHITHA K1114028.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(390, '1141020', 'RAMESHBABU N', '', '', '', '', 'HOD', '0000-00-00', '1141020', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(391, '1366134', 'PRABHU A C', '', '', '', '', 'HOD', '0000-00-00', '1366134', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(392, '1141052', 'KUMAR C', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Professor', 'Faculty', 'HOD', '2020-06-03', '1141052', 'images/profile/KUMAR C1141052.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(393, '1141050', 'SATHISH KUMAR S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Associate Professor', 'Faculty', 'HOD', '2019-06-10', '1141050', 'images/profile/SATHISH KUMAR S1141050.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(394, '1111017', 'PRAMILA M', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2007-09-10', '1111017', 'images/profile/PRAMILA M1111017.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(395, '1141053', 'NALINI N', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', '', 'Faculty', 'HOD', '2021-01-04', '1141053', 'images/profile/NALINI N1141053.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(396, '1141058', 'BHARANI G', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2021-06-02', '1141058', 'images/profile/BHARANI G1141058.jpeg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(397, '1141059', 'SENTHILKUMARAN C', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2021-09-01', '1141059', 'images/profile/SENTHILKUMARAN C1141059.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(398, '1111024', 'SHANTHA S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', '', 'Faculty', 'HOD', '2022-05-11', '1111024', 'images/profile/SHANTHA S1111024.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(399, '1141060', 'SUVETHA P S', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', '', 'HOD', '2022-07-11', '1141060', 'images/profile/SUVETHA P S1141060.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(400, '1114046', 'MADHANKUMAR M', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-07-11', '1114046', 'images/profile/MADHANKUMAR M1114046.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(401, '1141061', 'JAISIVA S', '', '', '', '', 'HOD', '0000-00-00', '1141061', '', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(402, '1141062', 'LAKSHMANAN M', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Associate Professor', 'Faculty', 'HOD', '2022-07-27', '1141062', 'images/profile/LAKSHMANAN M1141062.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(403, '1141021', 'SHANAVASH A', '', '', '', '', 'HOD', '0000-00-00', '1141021', '', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(404, '1142026', 'JEGADEESAN S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Professor', '', 'HOD', '2017-06-19', '1142026', 'images/profile/JEGADEESAN S1142026.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(405, '1142027', 'DHAMODARAN M', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Professor', 'Faculty', 'HOD', '2017-06-19', '1142027', 'images/profile/DHAMODARAN M1142027.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(406, '1142038', 'MEIVEL S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2018-06-18', 'Embsysmeivel7@', 'images/profile/MEIVEL S1142038.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(407, '1142035', 'ARUN PRATHAP S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', '', 'Faculty', 'HOD', '2018-06-18', 'mkce6', 'images/profile/ARUN PRATHAP S1142035.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(408, '1142032', 'SUGANYA A', '', '', '', '', 'HOD', '0000-00-00', '1142032', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(409, '1142030', 'MARISELVAM V', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2018-06-18', '1142030', 'images/profile/MARISELVAM V1142030.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(410, '1142037', 'JOTHIMANI S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2018-06-18', '1142037', 'images/profile/JOTHIMANI S1142037.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(411, '1142040', 'SUDHAKAR K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2018-07-09', 'Digital', 'images/profile/SUDHAKAR K1142040.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(412, '1142041', 'MURUGAN A', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Professor', 'Faculty', 'HOD', '2018-07-09', '1142041', 'images/profile/MURUGAN A1142041.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(413, '1142061', 'KALAIARASAN R', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2019-06-18', '1142061', 'images/profile/KALAIARASAN R1142061.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(414, '1142002', 'SHANMUGAVADIVEL G', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', '', 'Faculty', 'HOD', '2008-08-04', '1142002', 'images/profile/SHANMUGAVADIVEL G1142002.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(415, '1142003', 'RAMAKRISHNAN P', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2014-06-02', '1142003', 'images/profile/RAMAKRISHNAN P1142003.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(416, '1142004', 'ABIRAMI T', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', '', 'Faculty', 'HOD', '2014-06-02', '1142004', 'images/profile/ABIRAMI T1142004.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(417, '1142005', 'SIVAGURUNATHAN  P T', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2011-06-01', '07121986', 'images/profile/SIVAGURUNATHAN  P T1142005.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(418, '1142006', 'MOHANRAJ S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '1988-07-13', '1142006', 'images/profile/MOHANRAJ S1142006.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(419, '1142007', 'MAHENDRAN N', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2011-06-01', '1142007', 'images/profile/MAHENDRAN N1142007.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(420, '1142009', 'JAMUNA V', '', '', '', '', 'HOD', '0000-00-00', '1142009', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(421, '1142010', 'SIVARANJANI S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-14', '1142010', 'images/profile/SIVARANJANI S1142010.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(422, '1142011', 'NANDAGOPAL C', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2012-06-19', 'micmkce', 'images/profile/NANDAGOPAL C1142011.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(423, '1142008', 'DINESH E', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-04', '839208958@Aa', 'images/profile/DINESH E1142008.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(424, '1142013', 'PALANIVEL RAJAN S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Professor', 'HOD', 'HOD', '2013-12-09', 'velpalani', 'images/profile/PALANIVEL RAJAN S1142013.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(425, '1142015', 'SHEIK DAVOOD K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', '', 'HOD', '2014-06-16', 'pearl8520', 'images/profile/SHEIK DAVOOD K1142015.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(426, '1142014', 'SRIDEVI A', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', '', 'Faculty', 'HOD', '2014-04-16', 'Sridevi1142014', 'images/profile/SRIDEVI A1142014.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(427, '1142022', 'KAARTHIK K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2016-06-13', 'Kumar@123', 'images/profile/KAARTHIK K1142022.png', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(428, '1142023', 'RAMESH L', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2016-06-13', 'Harshita@123', 'images/profile/RAMESH L1142023.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(429, '1143004', 'YUVARANI P', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-04', '1143004', 'images/profile/YUVARANI P1143004.JPG', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(430, '1113005', 'JEYALAKSHMI K', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2017-08-02', '1113005', 'images/profile/JEYALAKSHMI K1113005.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(431, '1111010', 'SUBA T', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-06-10', '1111010', 'images/profile/SUBA T1111010.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(432, '1112005', 'VIDHYA J', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2008-08-13', '1112005', 'images/profile/VIDHYA J1112005.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(433, '1114008', 'VEERAMALAI G', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2015-08-28', '1114008', 'images/profile/VEERAMALAI G1114008.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(434, '1114033', 'HELLAN PRIYA J', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-08-05', '1114033', 'images/profile/HELLAN PRIYA J1114033.jpg', 1, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(435, '1114034', 'RAMYA M', '', '', '', '', 'HOD', '0000-00-00', '1114034', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(436, '1142019', 'NEETHTHI AADITHIYA B', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2015-06-04', '1142019', 'images/profile/NEETHTHI AADITHIYA B1142019.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(437, '1142074', 'KAVITHA S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-11-02', '1142074', 'images/profile/KAVITHA S1142074.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(438, '1142075', 'SENTHILKUMAR N', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', '', 'Faculty', 'HOD', '2020-07-15', '1142075', 'images/profile/SENTHILKUMAR N1142075.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(439, '1142076', 'KARTHIKEYAN K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-07-22', '1142076', 'images/profile/KARTHIKEYAN K1142076.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(440, '1142073', 'KAVITHA A', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Professor', 'HOD', 'HOD', '2020-08-10', '1142073', 'images/profile/KAVITHA A1142073.pdf', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(441, '1142078', 'VEDHASHREE K S', '', '', '', '', 'HOD', '0000-00-00', '1142078', '', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(442, '1142079', 'VIJAY N', '', '', '', '', 'HOD', '0000-00-00', '1142079', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(443, '1142080', 'SIVANANDAM K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2021-06-10', '1142080', 'images/profile/SIVANANDAM K1142080.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(444, '1113014', 'MANIKANDAN V', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2021-10-04', '1113014', 'images/profile/MANIKANDAN V1113014.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(445, '1142084', 'JEYAKUMAR P', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2021-11-15', '1142084', 'images/profile/JEYAKUMAR P1142084.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(446, '1142085', 'VASUKI S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2021-01-07', '1142085', 'images/profile/VASUKI S1142085.jpg', 75, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(447, '1142029', 'LOGAMBAL R', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-03-04', '1142029', 'images/profile/LOGAMBAL R1142029.jpeg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(448, '1142052', 'SUGANTHI N', '', '', '', '', 'HOD', '0000-00-00', '1142052', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(449, '1142043', 'NIRMALA V', '', '', '', '', 'HOD', '0000-00-00', '1142043', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(450, '1142054', 'GOWRI S', '', '', '', '', 'HOD', '0000-00-00', '1142054', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(451, '1142077', 'GAYATHRI M', '', '', '', '', 'HOD', '0000-00-00', '1142077', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(452, '1142086', 'RAJESHKANNA R', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-03-02', '1142086', 'images/profile/RAJESHKANNA R1142086.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(453, '1111022', 'SRIJA V', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2022-04-04', '1111022', 'images/profile/SRIJA V1111022.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(454, '1142088', 'VIMALNATH S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Associate Professor', 'Faculty', 'HOD', '2022-07-11', '1142088', 'images/profile/VIMALNATH S1142088.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(455, '1142089', 'SUBASELVI S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-07-11', '1142089', 'images/profile/SUBASELVI S1142089.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(456, '1142091', 'SENTAMILSELVI M', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-08-10', '1142091', 'images/profile/SENTAMILSELVI M1142091.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(457, '1142093', 'MAHESHWARI A', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-08-30', 'Krishika@21', 'images/profile/MAHESHWARI A1142093.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(458, '1142094', 'SIVA KUMAR T', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-08-30', '1142094', 'images/profile/SIVA KUMAR T1142094.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(459, '1142095', 'RAJENDRAKUMAR M G', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-08-30', '1142095', 'images/profile/RAJENDRAKUMAR M G1142095.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(460, '1142096', 'DHARANIPRIYA K', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-08-30', '1142096', 'images/profile/DHARANIPRIYA K1142096.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(461, '1142069', 'SANMUGAVALLI P', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2020-03-03', '1142069', 'images/profile/SANMUGAVALLI P1142069.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(462, '1142097', 'BALAMANI T', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-06-13', '1142097', 'images/profile/BALAMANI T1142097.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(463, '1142098', 'NIVISHNA S', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-06-13', '1142098', 'images/profile/NIVISHNA S1142098.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(464, '1142099', 'VADIVUKARASI B L', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-06-13', '1142099', 'images/profile/VADIVUKARASI B L1142099.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(465, '1143002', 'KARTHIKEYAN S', '', '', '', '', 'HOD', '0000-00-00', '1143002', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(466, '1141008', 'YUVARAJ M', 'Electrical and Electronics Engineering', 'Electrical and Electronics Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-06-04', '1141008', 'images/profile/YUVARAJ M1141008.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(467, '1143008', 'SAKTHI P', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2012-12-26', '123indirani', 'images/profile/SAKTHI P1143008.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(468, '1163004', 'SHARMILA S', '', '', '', '', 'HOD', '0000-00-00', '1163004', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(469, '1152030', 'PUNITHAVATHI R', 'Information Technology', 'Information Technology', 'Professor', 'HOD', 'HOD', '2019-06-10', 'India2023#', 'images/profile/PUNITHAVATHI R1152030.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(470, '1152011', 'KANIMOZHI S', 'Information Technology', 'Information Technology', '', 'Faculty', 'HOD', '2017-12-11', '1152011', 'images/profile/KANIMOZHI S1152011.jpg', 100, 100, 6, 0, 0.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1),
(471, '1152013', 'VALARMATHI N', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2018-01-08', '1152013', 'images/profile/VALARMATHI N1152013.jpg', 100, 100, 6, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 1),
(472, '1152016', 'THILAGAVATHI C', 'Information Technology', 'Information Technology', '', 'Faculty', 'HOD', '2018-06-18', 'Abhinav@04', 'images/profile/THILAGAVATHI C1152016.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(474, '1152003', 'SUJATHA R', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2009-06-10', '1152003', 'images/profile/SUJATHA R1152003.jpg', 100, 100, 6, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(476, '1152018', 'KALAIARASAN K', 'Information Technology', 'Information Technology', 'Assistant Professor', 'HOD', 'Principal', '2019-01-21', 'kalai@92', 'images/profile/KALAIARASAN K1152018.png', 100, 100, 5, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(478, '1113004', 'VIJAYAKUMAR K', 'Freshman Engineering', 'Freshman Engineering', 'Professor', '', 'HOD', '2017-06-19', '1113004', 'images/profile/VIJAYAKUMAR K1113004.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(479, '1114031', 'AROCKIA RANJITHKUMAR M', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2019-06-24', '8883249648', 'images/profile/AROCKIA RANJITHKUMAR M1114031.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(480, '1111001', 'POOMALAR K', 'Freshman Engineering', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '1985-07-24', '1111001', 'images/profile/POOMALAR K1111001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(481, '1112001', 'KANNAN K', '', '', '', '', 'HOD', '0000-00-00', '1112001', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(482, '1114009', 'BALAMURUGAN P', '', '', '', '', 'HOD', '0000-00-00', '1114009', '', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(484, '1152047', 'SYED MUSTHAFA A', 'Information Technology', 'Information Technology', 'Professor', 'Faculty', 'HOD', '2020-07-27', '1152047', 'images/profile/SYED MUSTHAFA A1152047.jpeg', 100, 100, 6, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 1),
(485, '1152050', 'PRIYADHARSHINI K V', 'Information Technology', 'Information Technology', '', 'Faculty', 'HOD', '1994-07-26', '1152050', 'images/profile/PRIYADHARSHINI K V1152050.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(486, '1152051', 'SRIJA N', 'Information Technology', 'Information Technology', 'Assistant Professor', '', 'HOD', '2021-03-01', '1152051', 'images/profile/SRIJA N1152051.jpg', 100, 51, 6, 0, 1.5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 1),
(488, '1152054', 'VIJAY A', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2021-07-12', '1152054', 'images/profile/VIJAY A1152054.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(489, '1152055', 'GEEITHA S', 'Information Technology', 'Information Technology', 'Associate Professor', 'Faculty', 'HOD', '2021-07-23', '1152055', 'images/profile/GEEITHA S1152055.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(490, '1112015', 'Dr .SIVAKUMAR V', 'Freshman Engineering', 'Freshman Engineering', 'Associate Professor', '', 'HOD', '2022-04-20', '1112015', 'images/profile/Dr .SIVAKUMAR V1112015.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(491, '1152058', 'MEKALA R', 'Information Technology', 'Information Technology', '', 'Faculty', 'HOD', '2022-05-13', 'AbikA2023', 'images/profile/MEKALA R1152058.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(492, '1152059', 'ANITHA K', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2022-05-09', 'Arun@123', 'images/profile/ANITHA K1152059.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(493, '1152061', 'BHARATHI R', 'Information Technology', 'Information Technology', 'Assistant Professor', '', 'HOD', '2022-07-20', '1152061', 'images/profile/BHARATHI R1152061.JPG', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(494, '1152062', 'SATHYANATHAN S', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2022-05-20', '1152062', 'images/profile/SATHYANATHAN S1152062.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(495, '1152063', 'NITHIYA A', 'Information Technology', 'Information Technology', '', 'Faculty', 'HOD', '2022-05-04', '1152063', 'images/profile/NITHIYA A1152063.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(496, '1162002', 'LAKSHMI S', '', '', '', '', 'HOD', '0000-00-00', '1162002', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(497, '1162001', 'SAKTHIVEL R', '', '', '', '', 'HOD', '0000-00-00', '1162001', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(498, '1115001', 'MALARKODI K', 'Master of Business Administration', 'Master of Business Administration', '', 'Faculty', 'HOD', '2015-05-04', '1115001', 'images/profile/MALARKODI K1115001.jpeg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(499, '1115007', 'VANITHA P', 'Master of Business Administration', 'Master of Business Administration', 'Associate Professor', 'Faculty', 'HOD', '2009-08-17', '1115007', 'images/profile/VANITHA P1115007.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(500, '1115010', 'RAMPRATHAP K', 'Master of Business Administration', 'Master of Business Administration', 'Assistant Professor', 'Faculty', 'HOD', '2019-06-26', '1115010', 'images/profile/RAMPRATHAP K1115010.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(501, '1114039', 'NITHYA M', 'Master of Business Administration', 'Master of Business Administration', 'Assistant Professor', 'Faculty', 'HOD', '2020-07-27', '1114039', 'images/profile/NITHYA M1114039.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(502, '1115019', 'RAJESH KUMAR K', 'Master of Business Administration', 'Master of Business Administration', 'Associate Professor', 'HOD', 'HOD', '2022-06-13', '1115019', 'images/profile/RAJESH KUMAR K1115019.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(503, '1115020', 'SUGANYA C', 'Master of Business Administration', 'Master of Business Administration', 'Assistant Professor', 'Faculty', 'HOD', '2022-07-11', '1115020', 'images/profile/SUGANYA C1115020.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(504, '1115021', 'MANIKANDAN M', 'Master of Business Administration', 'Master of Business Administration', 'Assistant Professor', '', 'HOD', '2022-09-25', '1115021', 'images/profile/MANIKANDAN M1115021.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(505, '1115022', 'SANTHIYA A', 'Master of Business Administration', 'Master of Business Administration', '', 'Faculty', 'HOD', '1998-06-19', '1115022', 'images/profile/SANTHIYA A1115022.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(506, '1115025', 'SOWMIYA B', '', '', '', '', 'HOD', '0000-00-00', '1115025', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(507, '1115023', 'SANTHOSHKUMAR P', '', '', '', '', 'HOD', '0000-00-00', '1115023', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(508, '1115026', 'PARTHIBAN S', '', '', '', '', 'HOD', '0000-00-00', '1115026', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(509, '1115027', 'PRABHAKARAN V', '', '', '', '', 'HOD', '0000-00-00', '1115027', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(510, '1115030', 'VENKATESH P', '', '', '', '', 'HOD', '0000-00-00', '1115030', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);
INSERT INTO `faculty` (`uid`, `id`, `name`, `dept`, `ddept`, `design`, `role`, `manager`, `doj`, `pass`, `cert`, `bc`, `ac`, `cl`, `col`, `odb`, `odr`, `odp`, `odo`, `vl`, `lop`, `ml`, `mal`, `mtl`, `ptl`, `sl`, `spl`, `pm`, `tenpm`, `status`) VALUES
(511, '1116001', 'VANITHAMANI S', 'Master of Computer Applications', 'Master of Computer Applications', 'Associate Professor', 'HOD', 'HOD', '2006-06-21', '1116001', 'images/profile/VANITHAMANI S1116001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(512, '1116006', 'KAYATHRI S', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2018-02-22', '1116006', 'images/profile/KAYATHRI S1116006.jpeg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(513, '1151005', 'MURUGESAN M', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'HOD', 'Principal', '2009-08-24', '1151005', 'images/profile/MURUGESAN M1151005.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(514, '1116013', 'RAMYA S', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2019-09-05', '1116013', 'images/profile/RAMYA S1116013.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(515, '1116017', 'RAMESH R', '', '', '', '', 'HOD', '0000-00-00', '1116017', '', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(516, '1116019', 'SARATHA M', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2021-10-11', '1116019', 'images/profile/SARATHA M1116019.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(517, '1116020', 'SENTHIL S', '', '', '', '', 'HOD', '0000-00-00', '1116020', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(518, '1116014', 'NEETHIMATHI R', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2020-02-10', '1116014', 'images/profile/NEETHIMATHI R1116014.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(519, '1116021', 'SRIPRIYA R K', '', '', '', '', 'HOD', '0000-00-00', '1116021', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(520, '1114044', 'SHANTHA LAKSHMI K', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2022-08-01', '1114044', 'images/profile/SHANTHA LAKSHMI K1114044.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(521, '1131001', 'RAMESH C', 'Mechanical Engineering', 'Mechanical Engineering', 'Professor', 'DSA', 'HOD', '2000-09-04', 'Kumarasamy2000', 'images/profile/RAMESH C1131001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(522, '1131056', 'SARAVANAKUMAR S', '', '', '', '', 'HOD', '0000-00-00', '1131056', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(523, '1131057', 'SARAVANAKUMAR S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2019-07-15', '1131057', 'images/profile/SARAVANAKUMAR S1131057.jpeg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(524, '1131026', 'DHANABALAN S', 'Mechanical Engineering', 'Mechanical Engineering', 'Professor', '', 'HOD', '2017-01-04', 'dharsanya@22', 'images/profile/DHANABALAN S1131026.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(525, '1131032', 'RAJA NARAYANAN S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2017-07-03', '1131032', 'images/profile/RAJA NARAYANAN S1131032.jpg', 51, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(526, '1131031', 'KARTHICK R', '', '', '', '', 'HOD', '0000-00-00', '1131031', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(527, '1131033', 'KAMALAKANNAN R', 'Mechanical Engineering', 'Mechanical Engineering', 'Professor', '', 'HOD', '2018-06-18', '1131033', 'images/profile/KAMALAKANNAN R1131033.PNG', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(528, '1131035', 'VIJAY S', '', '', '', '', 'HOD', '0000-00-00', '1131035', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(529, '1131050', 'VINOTH KUMAR H', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2019-06-10', '1131050', 'images/profile/VINOTH KUMAR H1131050.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(530, '1131052', 'MOHAN PRASAD M', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', 'HOD', 'HOD', '2019-06-10', '1131052', 'images/profile/MOHAN PRASAD M1131052.jpg', 26, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(531, '1131051', 'MOHANRAJ C', '', '', '', '', 'HOD', '0000-00-00', '1131051', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(532, '1131006', 'LOGANATHAN M', 'Mechanical Engineering', 'Mechanical Engineering', '', 'Faculty', 'HOD', '2011-12-01', 'ML@28912891', 'images/profile/LOGANATHAN M1131006.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(533, '1131003', 'NITHYANANDAM T', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2010-06-02', '1131003', 'images/profile/NITHYANANDAM T1131003.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(534, '1131004', 'PADMAVATHY S', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', 'Faculty', 'HOD', '2010-06-09', '1131004', 'images/profile/PADMAVATHY S1131004.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(535, '1131005', 'SETHUSUNDARAM P P', '', '', '', '', 'HOD', '0000-00-00', '1131005', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(536, '1131008', 'PARTHIPAN N', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', 'Faculty', 'HOD', '2012-06-04', '1131008', 'images/profile/PARTHIPAN N1131008.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(537, '1131009', 'BALAMURUGAN R', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', '', 'HOD', '2012-06-25', '1131009', 'images/profile/BALAMURUGAN R1131009.jpg', 51, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(538, '1131010', 'KIRUBAGHARAN R', '', '', '', '', 'HOD', '0000-00-00', '1131010', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(539, '1131037', 'BALAKRISHANAN M', 'Mechanical Engineering', 'Mechanical Engineering', 'Professor', '', 'HOD', '2013-06-12', '1131037', 'images/profile/BALAKRISHANAN M1131037.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(540, '1131013', 'KESAVAN V', '', '', '', '', 'HOD', '0000-00-00', '1131013', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(541, '1131014', 'VIJAYAKUMAR R', '', '', '', '', 'HOD', '0000-00-00', '1131014', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(542, '1131017', 'KARTHE M', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2014-06-16', '1131017', 'images/profile/KARTHE M1131017.JPG', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(543, '1131015', 'NANDHAKUMAR S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2014-06-16', '1131015', 'images/profile/NANDHAKUMAR S1131015.jpg', 26, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(544, '1131019', 'MANIVEL R', '', '', '', '', 'HOD', '0000-00-00', '1131019', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(545, '1131020', 'RAJU K', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', '', 'HOD', '2015-06-04', '1131020', 'images/profile/RAJU K1131020.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(546, '1131024', 'EMMANUAL L', 'Mechanical Engineering', 'Mechanical Engineering', '', 'Faculty', 'HOD', '2016-06-13', 'Emman0511', 'images/profile/EMMANUAL L1131024.JPG', 75, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(547, '1111008', 'SINTHU S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2018-10-29', '1111008', 'images/profile/SINTHU S1111008.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(548, '1114024', 'KARTHIGA K', '', '', '', '', 'HOD', '0000-00-00', '1114024', '', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(549, '1131012', 'PRASANNA S C', '', '', '', '', 'HOD', '0000-00-00', '1131012', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(550, '1131002', 'MANICKAM C', '', '', '', '', 'HOD', '0000-00-00', '1131002', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(551, '1114040', 'ILAVARASAN B', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2021-02-01', '1114040', 'images/profile/ILAVARASAN B1114040.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(552, '1113013', ' KALAISELVAN S', 'Mechanical Engineering', 'Mechanical Engineering', 'Associate Professor', 'Faculty', 'HOD', '2021-02-22', '1113013', 'images/profile/ KALAISELVAN S1113013.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(553, '1131066', 'MANIKANDAN R', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2021-06-15', '1131066', 'images/profile/MANIKANDAN R1131066.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(554, '1131021', 'MANIKANDAN A', '', '', '', '', 'HOD', '0000-00-00', '1131021', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(555, '1131028', 'TAMILSELVAN T', '', '', '', '', 'HOD', '0000-00-00', '1131028', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(556, '1131038', 'SHOBA PRIYADHARSINI L', '', '', '', '', 'HOD', '0000-00-00', '1131038', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(557, '1131067', 'DHIVYANATHAN M', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2022-04-06', '1131067', 'images/profile/DHIVYANATHAN M1131067.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(558, '1131068', 'PREMKUMAR R', '', '', '', '', 'HOD', '0000-00-00', '1131068', '', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(559, '1131069', 'UMAMAHESWARI D', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2022-08-29', '1131069', 'images/profile/UMAMAHESWARI D1131069.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(560, '1131070', 'SATHEESH KUMAR S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2022-10-31', 'Harshi@2016', 'images/profile/SATHEESH KUMAR S1131070.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(561, '1131071', 'VIGNESH S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2022-06-15', '1131071', 'images/profile/VIGNESH S1131071.jpg', 100, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(562, '1131072', 'KEERTHIVASAN K C', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2023-03-23', '1131072', 'images/profile/KEERTHIVASAN K C1131072.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(563, '1131073', 'GOPINATH G R ', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', '', 'HOD', '2023-03-13', '1131073', 'images/profile/GOPINATH G R 1131073.jpg', 75, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(564, '1366170', 'RAJA SUBRAMANIAN', '', '', '', '', 'HOD', '0000-00-00', '1366170', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(565, '1165000', 'ELANGOVAN P', '', '', '', '', 'HOD', '0000-00-00', '1165000', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(566, '1165001', 'DOMINIC A', '', '', '', '', 'HOD', '0000-00-00', '1165001', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(567, '1165002', 'KAMARAJ P', '', '', '', '', 'HOD', '0000-00-00', '1165002', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(568, '1112013', 'ENIYA S', 'Mechanical Engineering', 'Mechanical Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-11-17', '1112013', 'images/profile/ENIYA S1112013.jpg', 75, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(569, '1112016', 'THANGAMANI C', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', '', 'HOD', '2022-04-20', '1112016', 'images/profile/THANGAMANI C1112016.jpg', 26, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(570, '1114026', 'MAHESWARI S', '', '', '', '', 'HOD', '0000-00-00', '1114026', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(571, '2151042', 'SIVAJAYAPRAKASH A', '', '', '', '', 'HOD', '0000-00-00', '2151042', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(572, '1261001', 'BASKAR S', '', '', '', '', 'HOD', '0000-00-00', '1261001', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(573, '1261025', 'AJEESHA K', '', '', '', '', 'HOD', '0000-00-00', '1261025', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(574, '1261026', 'MONISHA M', '', '', '', '', 'HOD', '0000-00-00', '1261026', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(575, '1261028', 'NIRANJANA DEVI M', '', '', '', '', 'HOD', '0000-00-00', '1261028', '', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(576, '1261027', 'PREETHI R', '', '', '', '', 'HOD', '0000-00-00', '1261027', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(577, '1261029', 'SNEGALATHA R', '', '', '', '', 'HOD', '0000-00-00', '1261029', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(578, '1261030', 'ESWARAN E', '', '', '', '', 'HOD', '0000-00-00', '1261030', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(579, '1261031', 'MANJURIYA S', '', '', '', '', 'HOD', '0000-00-00', '1261031', '', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(580, '1115028', 'NARMADA DEVI M', 'Master of Business Administration', 'Master of Business Administration', 'Assistant Professor', 'Faculty', 'HOD', '2023-01-12', '1115028', 'images/profile/NARMADA DEVI M1115028.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(581, '1116022', 'Sindhu K', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-03', '1116022', 'images/profile/Sindhu K1116022.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(582, '1116023', 'Manoj S', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-03', '1116023', 'images/profile/Manoj S1116023.jpg', 51, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(583, '1116024', 'Vanisri S', 'Master of Computer Applications', 'Master of Computer Applications', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-10', '1116024', 'images/profile/Vanisri S1116024.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(584, '1117001', 'Dr.S.K.Azhakambigai', 'Freshman Engineering', 'Freshman Engineering', 'Professor', '', 'HOD', '2023-06-26', '1117001', 'images/profile/Dr.S.K.Azhakambigai1117001.jpg', 100, 100, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(585, '1114023', 'T.Jayapriya', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-05-22', '1114023', 'images/profile/T.Jayapriya1114023.jpg', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(586, '1152067', 'DHIVYA R', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-12', '1152067', 'images/profile/DHIVYA R1152067.jpg', 100, 75, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(587, '1154015', 'SHOBANA M', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2023-06-09', '1154015', 'images/profile/SHOBANA M1154015.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(588, '1152066', 'RANI K', 'Information Technology', 'Information Technology', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-03', '1152066', 'images/profile/RANI K1152066.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(589, '1151050', 'Kayalvizhi.P', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-09-20', '1151050', 'images/profile/Kayalvizhi.P1151050.jpg', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(590, '1154016', 'KIRTHIKA R', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-03', '1154016', 'images/profile/KIRTHIKA R1154016.jpg', 100, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 2, 0),
(593, '1152065', 'MYTHILI M', 'Information Technology', 'Information Technology', 'Assistant Professor', '', 'HOD', '2023-07-03', '1152065', 'images/profile/MYTHILI M1152065.jpg', 100, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(594, '1151046', 'RAMYASHRI', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-06-02', '1151046', 'images/profile/RAMYASHRI1151046.jpg', 26, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(595, '1151049', 'ANANTHI S', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-07-12', '1151049', 'images/profile/ANANTHI S1151049.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(596, '1151009', 'SELVANAYAGI A', 'Computer Science and Engineering', 'Computer Science and Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-06-26', '1151009', 'images/profile/SELVANAYAGI A1151009.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(597, '1142100', 'RAMYA.N', 'Electronics and Communication Engineering', 'Electronics and Communication Engineering', 'Assistant Professor', 'Faculty', 'HOD', '0023-09-25', '1142100', 'images/profile/RAMYA.N1142100.jpg', 1, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(598, '1114048', 'R.JAYA', 'Freshman Engineering', 'Freshman Engineering', 'Assistant Professor', 'Faculty', 'HOD', '2023-10-04', '1114048', 'images/profile/R.JAYA1114048.jpg', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(599, '1154017', 'DEVAYANI G', 'Computer Science and Business Systems', 'Computer Science and Business Systems', 'Assistant Professor', 'Faculty', 'HOD', '2023-01-11', '1154017', 'images/profile/DEVAYANI G1154017.jpg', 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(600, '1155010', 'Dr.T.Saravanan', 'Artificial Intelligence and Machine Learning', 'Artificial Intelligence and Machine Learning', 'Assistant Professor', 'Faculty', 'HOD', '2023-12-06', '1155010', 'images/profile/Dr.T.Saravanan1155010.jpg', 75, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(601, '1153013', 'S.M.Hemalatha', 'Artificial Intelligence and Data Science', 'Artificial Intelligence and Data Science', 'Assistant Professor', '', 'HOD', '2023-06-14', '1153013', 'images/profile/S.M.Hemalatha1153013.jpg', 26, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sbasic`
--

CREATE TABLE `sbasic` (
  `sid` varchar(30) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `programme` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `blood` varchar(10) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `pmobile` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `offemail` varchar(100) DEFAULT NULL,
  `languages` varchar(100) DEFAULT NULL,
  `aadhar` varchar(20) DEFAULT NULL,
  `saadhar` varchar(20) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `span` varchar(20) DEFAULT NULL,
  `hosday` enum('Hosteller','Dayscholar') DEFAULT NULL,
  `hosname` varchar(50) DEFAULT NULL,
  `room` varchar(30) DEFAULT NULL,
  `stay` varchar(100) DEFAULT NULL,
  `busno` int(11) DEFAULT NULL,
  `paddress` varchar(200) DEFAULT NULL,
  `taddress` varchar(200) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `doadmission` date DEFAULT NULL,
  `admcate` varchar(50) DEFAULT NULL,
  `admtype` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `socstrata` varchar(50) DEFAULT NULL,
  `caste` varchar(50) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `firstgra` enum('YES','NO') DEFAULT NULL,
  `cutoff` int(11) DEFAULT NULL,
  `exam_status` varchar(50) DEFAULT NULL,
  `exam_mark` varchar(50) DEFAULT NULL,
  `Strengths` text DEFAULT NULL,
  `Weaknesses` text DEFAULT NULL,
  `Opportunities` text DEFAULT NULL,
  `Threats` text DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `guarname` varchar(100) DEFAULT NULL,
  `guarmobile` varchar(15) DEFAULT NULL,
  `guaraddress` varchar(200) DEFAULT NULL,
  `pphoto` varchar(200) DEFAULT NULL,
  `fphoto` varchar(200) DEFAULT NULL,
  `mphoto` varchar(200) DEFAULT NULL,
  `gphoto` varchar(200) DEFAULT NULL,
  `phyident1` varchar(200) DEFAULT NULL,
  `phyident2` varchar(200) DEFAULT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `ayear_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission`
--
ALTER TABLE `admission`
  ADD PRIMARY KEY (`admission_id`),
  ADD UNIQUE KEY `sid` (`sid`),
  ADD KEY `fk_admission_ayear` (`ayear_id`),
  ADD KEY `idx_admission_admitted_by` (`admitted_by`);

--
-- Indexes for table `ayear`
--
ALTER TABLE `ayear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sbasic`
--
ALTER TABLE `sbasic`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `fk_sbasic_admission` (`admission_id`),
  ADD KEY `fk_sbasic_ayear` (`ayear_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission`
--
ALTER TABLE `admission`
  MODIFY `admission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ayear`
--
ALTER TABLE `ayear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `uid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=607;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admission`
--
ALTER TABLE `admission`
  ADD CONSTRAINT `fk_admission_ayear` FOREIGN KEY (`ayear_id`) REFERENCES `ayear` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admission_faculty` FOREIGN KEY (`admitted_by`) REFERENCES `mic`.`faculty` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sbasic`
--
ALTER TABLE `sbasic`
  ADD CONSTRAINT `fk_sbasic_admission` FOREIGN KEY (`admission_id`) REFERENCES `admission` (`admission_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sbasic_ayear` FOREIGN KEY (`ayear_id`) REFERENCES `ayear` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
