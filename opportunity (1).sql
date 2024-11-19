-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2024 at 06:29 PM
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
-- Database: `opportunity`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_logs`
--

CREATE TABLE `application_logs` (
  `jobid` int(15) NOT NULL,
  `employer_userid` int(15) NOT NULL,
  `job_position` varchar(255) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `jobsdec` varchar(255) NOT NULL,
  `jobseeker_userid` int(15) NOT NULL,
  `datetime_apply` datetime NOT NULL DEFAULT current_timestamp(),
  `user_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_logs`
--

INSERT INTO `application_logs` (`jobid`, `employer_userid`, `job_position`, `company_name`, `first_name`, `last_name`, `jobsdec`, `jobseeker_userid`, `datetime_apply`, `user_status`) VALUES
(495694599, 226780112, 'Manager', 'Veeny Company', 'armabel', 'ramos', '', 928470641, '2024-11-17 20:05:55', 'waiting List'),
(495694599, 226780112, 'Manager', 'Veeny Company', 'Christian', 'De Guzman', '', 770944389, '2024-11-17 21:42:54', 'waiting List'),
(170570573, 226780112, 'Fry Cook', 'Krusty Krab', 'Leander', 'Ochea', '', 323862607, '2024-11-18 03:37:25', 'Short Listed'),
(170570573, 226780112, 'Fry Cook', 'Krusty Krab', 'armabel', 'ramos', '', 928470641, '2024-11-18 03:38:01', 'Rejected'),
(170570573, 226780112, 'Fry Cook', 'Krusty Krab', 'Christian', 'De Guzman', '', 770944389, '2024-11-18 13:48:38', 'Waiting List'),
(265309059, 927438730, 'Janitor', 'On point', 'Christian', 'De Guzman', '', 770944389, '2024-11-18 14:21:30', 'waiting List'),
(265309059, 927438730, 'Janitor', 'On point', 'armabel', 'ramos', '', 928470641, '2024-11-18 14:21:50', 'waiting List'),
(265309059, 927438730, 'Janitor', 'On point', 'Leander', 'Ochea', '', 323862607, '2024-11-18 14:22:25', 'waiting List'),
(495694599, 226780112, 'Manager', 'Veeny Company', 'Tejedo', 'San Juan', '', 324383166, '2024-11-19 01:18:23', 'waiting List'),
(170570573, 226780112, 'Fry Cook', 'Krusty Krab', 'Tejedo', 'San Juan', '', 324383166, '2024-11-19 20:38:19', 'Waiting List'),
(265309059, 927438730, 'Janitor', 'On point', 'Tejedo', 'San Juan', '', 324383166, '2024-11-19 21:28:08', 'waiting List');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `jobid` int(15) NOT NULL,
  `companyname` varchar(50) NOT NULL,
  `jobname` varchar(50) NOT NULL,
  `jobdesc` varchar(255) NOT NULL,
  `job_location` varchar(255) NOT NULL,
  `salary` int(15) NOT NULL,
  `requirements` varchar(255) NOT NULL,
  `qualities` varchar(255) NOT NULL,
  `expectation` varchar(255) NOT NULL,
  `joblogo` blob NOT NULL,
  `userid` int(50) NOT NULL,
  `datetime_job_created` datetime NOT NULL DEFAULT current_timestamp(),
  `job_status` varchar(50) NOT NULL,
  `job_limit` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`jobid`, `companyname`, `jobname`, `jobdesc`, `job_location`, `salary`, `requirements`, `qualities`, `expectation`, `joblogo`, `userid`, `datetime_job_created`, `job_status`, `job_limit`) VALUES
(305855654, 'Pope Water Corp.', 'Delivery Boy', 'Flexible', 'Limay, Bataan', 12000, 'must have drivers license', 'at least 2years of driving experience', 'know how to handle customers', '', 692307636, '2024-11-17 01:49:45', 'Hiring', 0),
(431194673, 'Gabtopia', 'backend Developer', 'You\'ll need to communicate to your teams in order to get better performance', 'Mariveles, Bataan', 15000, 'must know: Python, Rust, PHP, JavaScript', 'N/A', 'N/A', '', 226780112, '2024-11-17 12:34:49', 'Hiring', 0),
(265309059, 'On point', 'Janitor', 'Dental cleaning', 'Limay, Bataan', 2000, 'PSA, CV', 'Multitasking', 'Lot of patience, Socialize', '', 927438730, '2024-11-17 15:15:23', 'Hiring', 0),
(495694599, 'Veeny Company', 'Manager', 'Manage things', 'Balanga, Bataan', 20000, 'PSA, CV, valid ID', 'College Graduate, Bachelors Degree', 'N/A', '', 226780112, '2024-11-17 20:05:29', 'Hiring', 0),
(170570573, 'Krusty Krab', 'Fry Cook', 'N/A', 'Bikini Bottom', 2000, 'High School Graduate/College Graduate', 'good at cooking', 'N/A', '', 226780112, '2024-11-17 23:00:43', 'Hiring', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_ID` int(15) NOT NULL,
  `user_type` varchar(30) NOT NULL,
  `user_firstname` varchar(50) NOT NULL,
  `user_lastname` varchar(50) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_sex` varchar(25) NOT NULL,
  `profile_photo` blob NOT NULL,
  `datetime_user_created` datetime NOT NULL DEFAULT current_timestamp(),
  `user_notification` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_ID`, `user_type`, `user_firstname`, `user_lastname`, `user_username`, `user_password`, `user_sex`, `profile_photo`, `datetime_user_created`, `user_notification`) VALUES
(323862607, 'employee', 'Leander', 'Ochea', 'lpochea@bpsu.edu.ph', 'macmacpalo', '', '', '2024-11-15 23:37:48', 0),
(226780112, 'employer', 'Marc', 'Parubrub', 'bengbeng@gmail.com', 'milo', '', '', '2024-11-15 23:37:48', 0),
(752150559, 'employee', 'try', 'lang', 'admin@gmail.com', 'admin', '', '', '2024-11-15 23:37:48', 0),
(692307636, 'employer', 'Veeny', 'Bautista', 'strawberry@gmail.com', '123', '', '', '2024-11-15 23:37:48', 0),
(615124876, 'employer', 'Sam', 'Perello', 'sambro@gmail.com', '123', '', '', '2024-11-15 23:40:24', 0),
(928470641, 'employee', 'armabel', 'ramos', 'aramos@gmail.com', 'qwe', '', '', '2024-11-16 12:56:07', 0),
(927438730, 'employer', 'Francis', 'jinis', 'mizu@gmail.com', '123', '', '', '2024-11-17 15:12:58', 0),
(770944389, 'employee', 'Christian', 'De Guzman', 'ian@gmail.com', '123', '', '', '2024-11-17 15:16:28', 0),
(324383166, 'employee', 'Tejedo', 'San Juan', 'tj@gmail.com', '123', '', '', '2024-11-19 01:10:37', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
