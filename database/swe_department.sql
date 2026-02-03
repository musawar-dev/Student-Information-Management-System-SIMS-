-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2026 at 02:34 PM
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
-- Database: `swe_department`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocations`
--

CREATE TABLE `allocations` (
  `allocation_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `course_code` varchar(50) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allocations`
--

INSERT INTO `allocations` (`allocation_id`, `employee_id`, `course_code`, `section`, `batch_id`) VALUES
(14, 105, 'SW201', 'A', 4),
(15, 107, 'SW204', 'A', 4),
(17, 108, 'SW210', 'A', 4),
(18, 110, 'SW210', 'A', 4),
(19, 104, 'SW301', 'A', 3),
(20, 102, 'SW302', 'A', 3),
(21, 111, 'SW311', 'A', 3),
(22, 106, 'SW311', 'A', 3),
(23, 110, 'MTH301', 'A', 3),
(24, 102, 'SW202', 'A', 4),
(32, 106, 'SW102', 'A', 4),
(33, 106, 'SW103', 'A', 4);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `enrollment_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `batch_id` int(11) NOT NULL,
  `batch_name` varchar(50) NOT NULL,
  `passing_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`batch_id`, `batch_name`, `passing_year`) VALUES
(1, '21-Batch', '2025'),
(2, '22-Batch', '2026'),
(3, '23-Batch', '2027'),
(4, '24-Batch', '2028');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_import`
--

CREATE TABLE `bulk_import` (
  `id` int(11) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_code` varchar(20) NOT NULL,
  `course_title` varchar(100) NOT NULL,
  `credit_hours` int(11) NOT NULL,
  `semester` int(11) DEFAULT 1,
  `course_type` enum('Theory','Lab') NOT NULL,
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_code`, `course_title`, `credit_hours`, `semester`, `course_type`, `dept_id`) VALUES
('54', 'Final Year Project-I (FYP-I)', 0, 7, 'Theory', NULL),
('55', 'Final Year Project-II (FYP-II)', 0, 8, 'Theory', NULL),
('EL111', 'Applied Physics', 3, 1, 'Theory', NULL),
('MTH101', 'Applied Calculus', 3, 1, 'Theory', NULL),
('MTH104', 'Pakistan Studies', 2, 2, 'Theory', NULL),
('MTH105', 'Islamic studies', 2, 2, 'Theory', NULL),
('MTH106', 'Functional English', 3, 1, 'Theory', NULL),
('MTH107', 'Communication Skills', 2, 2, 'Theory', NULL),
('MTH204', 'Numerical Analysis with Computer Applications', 3, 3, 'Theory', NULL),
('MTH301', 'Statistics & Probability', 3, 5, 'Theory', NULL),
('MTH303', 'Professional & Social Ethics', 2, 6, 'Theory', NULL),
('SW101', 'Computer Fundamentals', 2, 1, 'Theory', NULL),
('SW102', 'Programming Fundamentals', 3, 1, 'Theory', NULL),
('SW103', 'Object Oriented Programming', 3, 2, 'Theory', NULL),
('SW104', 'Introduction to Software Engineering', 3, 2, 'Theory', NULL),
('SW110', 'Occupational Health & Safety', 1, 1, 'Theory', NULL),
('SW201', 'Data Structures and Algorithms', 3, 3, 'Theory', NULL),
('SW202', 'Database Systems', 3, 3, 'Theory', NULL),
('SW204', 'Software Req. Engineering', 2, 3, 'Theory', NULL),
('SW205', 'Software Design & Architecture', 2, 4, 'Theory', NULL),
('SW206', 'Computer Networks', 3, 4, 'Theory', NULL),
('SW207', 'Web Engineering', 3, 4, 'Theory', NULL),
('SW208', 'Operations Research', 3, 4, 'Theory', NULL),
('SW209', 'Entrepreneurship', 2, 4, 'Theory', NULL),
('SW210', 'Computer Arch. & Logic Design', 3, 3, 'Theory', NULL),
('SW301', 'Software Construction', 2, 5, 'Theory', NULL),
('SW302', 'Elective-I (Mobile Applications Dev.)', 3, 5, 'Theory', NULL),
('SW303', 'Human Computer Interaction', 3, 6, 'Theory', NULL),
('SW304', 'Information Security', 3, 8, 'Theory', NULL),
('SW305', 'Engineering Economics', 2, 5, 'Theory', NULL),
('SW306', 'Software Project Management', 3, 6, 'Theory', NULL),
('SW308', 'Elective-II (Artificial Intelligence)', 3, 6, 'Theory', NULL),
('SW310', 'Digital Marketing', 2, 6, 'Theory', NULL),
('SW311', 'Operating Systems', 3, 5, 'Theory', NULL),
('SW312', 'Community Services', 0, 6, 'Theory', NULL),
('SW401', 'Cloud Computing', 3, 7, 'Theory', NULL),
('SW402', 'Elective-Ill (Data Sciences)', 2, 7, 'Theory', NULL),
('SW403', 'Software Re- engineering', 3, 7, 'Theory', NULL),
('SW404', 'Formal Methods in Software Engg:', 3, 7, 'Theory', NULL),
('SW406', 'Software Quality Engineering', 3, 8, 'Theory', NULL),
('SW407', 'MDEE Elective-IV (Internet of Things)', 3, 8, 'Theory', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `hod_name` varchar(100) DEFAULT NULL,
  `office_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dept_id`, `dept_name`, `hod_name`, `office_location`) VALUES
(1, 'Software Engineering', 'Dr. Arshad', 'Block A');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `roll_no` varchar(20) DEFAULT NULL,
  `course_code` varchar(20) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `semester_no` int(11) DEFAULT NULL,
  `mid_marks` int(11) DEFAULT 0,
  `final_marks` int(11) DEFAULT 0,
  `sessional_marks` int(11) DEFAULT 0,
  `gpa` decimal(3,2) DEFAULT NULL,
  `classes_attended` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `roll_no`, `course_code`, `employee_id`, `semester_no`, `mid_marks`, `final_marks`, `sessional_marks`, `gpa`, `classes_attended`) VALUES
(9, '23SW01', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(10, '23SW02', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(11, '23SW03', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(12, '23SW04', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(13, '23SW05', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(14, '23SW09', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(15, '23SW13', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(16, '23SW15', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(17, '23SW16', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(18, '23SW17', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(19, '23SW18', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(20, '23SW19', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(21, '23SW21', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(22, '23SW22', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(23, '23SW23', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(24, '23SW25', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(25, '23SW26', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(26, '23SW27', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(27, '23SW28', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(28, '23SW29', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(29, '23SW30', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(30, '23SW31', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(31, '23SW32', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(32, '23SW33', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(33, '23SW34', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(34, '23SW35', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(35, '23SW36', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(36, '23SW38', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(37, '23SW40', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(38, '23SW42', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(39, '23SW43', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(40, '23SW44', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(41, '23SW45', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(42, '23SW47', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(43, '23SW49', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(44, '23SW50', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(45, '23SW51', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(46, '23SW52', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(47, '23SW53', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(48, '23SW55', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(49, '23SW56', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(50, '23SW57', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(51, '23SW58', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(52, '23SW59', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(53, '23SW60', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(54, '23SW61', 'SW302', NULL, NULL, 0, 0, 0, NULL, 0),
(55, '24SW01', 'SW202', NULL, NULL, 12, 49, 12, NULL, 1),
(56, '24SW02', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(57, '24SW03', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(58, '24SW04', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(59, '24SW05', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(60, '24SW06', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(61, '24SW07', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(62, '24SW08', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(63, '24SW10', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(64, '24SW14', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(65, '24SW15', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(66, '24SW16', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(67, '24SW18', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(68, '24SW19', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(69, '24SW20', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(70, '24SW23', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(71, '24SW24', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(72, '24SW25', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(73, '24SW30', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(74, '24SW32', 'SW202', NULL, NULL, 0, 0, 0, NULL, 1),
(75, '24SW33', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(76, '24SW34', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(77, '24SW36', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(78, '24SW38', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(79, '24SW39', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(80, '24SW41', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(81, '24SW45', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(82, '24SW46', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(83, '24SW47', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(84, '24SW50', 'SW202', NULL, NULL, 25, 40, 15, NULL, 0),
(85, '24SW51', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(86, '24SW52', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(87, '24SW54', 'SW202', NULL, NULL, 0, 0, 0, NULL, 0),
(88, '24SW01', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(89, '24SW02', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(90, '24SW03', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(91, '24SW04', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(92, '24SW05', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(93, '24SW06', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(94, '24SW07', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(95, '24SW08', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(96, '24SW10', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(97, '24SW14', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(98, '24SW15', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(99, '24SW16', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(100, '24SW18', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(101, '24SW19', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(102, '24SW20', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(103, '24SW23', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(104, '24SW24', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(105, '24SW25', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(106, '24SW30', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(107, '24SW32', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(108, '24SW33', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(109, '24SW34', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(110, '24SW36', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(111, '24SW38', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(112, '24SW39', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(113, '24SW41', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(114, '24SW45', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(115, '24SW46', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(116, '24SW47', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(117, '24SW50', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(118, '24SW51', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(119, '24SW52', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(120, '24SW54', 'SW110', NULL, NULL, 0, 0, 0, NULL, 0),
(121, '24SW01', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(122, '24SW02', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(123, '24SW03', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(124, '24SW04', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(125, '24SW05', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(126, '24SW06', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(127, '24SW07', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(128, '24SW08', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(129, '24SW10', 'SW103', NULL, NULL, 0, 0, 0, NULL, 3),
(130, '24SW14', 'SW103', NULL, NULL, 0, 0, 0, NULL, 4),
(131, '24SW15', 'SW103', NULL, NULL, 28, 46, 19, NULL, 6),
(132, '24SW16', 'SW103', NULL, NULL, 0, 0, 0, NULL, 6),
(133, '24SW18', 'SW103', NULL, NULL, 0, 0, 0, NULL, 6),
(134, '24SW19', 'SW103', NULL, NULL, 0, 0, 0, NULL, 6),
(135, '24SW20', 'SW103', NULL, NULL, 0, 0, 0, NULL, 6),
(136, '24SW23', 'SW103', NULL, NULL, 0, 0, 0, NULL, 6),
(137, '24SW24', 'SW103', NULL, NULL, 0, 0, 0, NULL, 5),
(138, '24SW25', 'SW103', NULL, NULL, 0, 0, 0, NULL, 4),
(139, '24SW30', 'SW103', NULL, NULL, 0, 0, 0, NULL, 2),
(140, '24SW32', 'SW103', NULL, NULL, 0, 0, 0, NULL, 2),
(141, '24SW33', 'SW103', NULL, NULL, 0, 0, 0, NULL, 1),
(142, '24SW34', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(143, '24SW36', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(144, '24SW38', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(145, '24SW39', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(146, '24SW41', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(147, '24SW45', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(148, '24SW46', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(149, '24SW47', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(150, '24SW50', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(151, '24SW51', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(152, '24SW52', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(153, '24SW54', 'SW103', NULL, NULL, 0, 0, 0, NULL, 0),
(154, '24SW01', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(155, '24SW02', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(156, '24SW03', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(157, '24SW04', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(158, '24SW05', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(159, '24SW06', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(160, '24SW07', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(161, '24SW08', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(162, '24SW10', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(163, '24SW14', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(164, '24SW15', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(165, '24SW16', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(166, '24SW17', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(167, '24SW18', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(168, '24SW19', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(169, '24SW20', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(170, '24SW23', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(171, '24SW24', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(172, '24SW25', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(173, '24SW30', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(174, '24SW32', 'SW204', NULL, NULL, 0, 0, 0, NULL, 0),
(175, '24SW33', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(176, '24SW34', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(177, '24SW36', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(178, '24SW38', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(179, '24SW39', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(180, '24SW41', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(181, '24SW45', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(182, '24SW46', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(183, '24SW47', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(184, '24SW50', 'SW204', NULL, NULL, 25, 43, 15, NULL, 9),
(185, '24SW51', 'SW204', NULL, NULL, 0, 0, 0, NULL, 9),
(186, '24SW52', 'SW204', NULL, NULL, 0, 0, 0, NULL, 15),
(187, '24SW54', 'SW204', NULL, NULL, 0, 0, 0, NULL, 15);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `salary` int(11) DEFAULT 0,
  `join_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `full_name`, `designation`, `phone`, `salary`, `join_date`) VALUES
(2, 'Zubair Ali', 'Office Clerk', '0300-1234567', 95700, '2026-01-26');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `roll_no` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `current_semester` int(11) DEFAULT 1,
  `cgpa` decimal(3,2) DEFAULT 0.00,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT 'default_student.png',
  `dept_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`roll_no`, `user_id`, `first_name`, `last_name`, `surname`, `father_name`, `phone_no`, `cnic`, `dob`, `gender`, `admission_date`, `batch_id`, `current_semester`, `cgpa`, `address`, `profile_photo`, `dept_id`) VALUES
('23SW01', 80, 'Basit Baig', NULL, '', 'Mujamil Baig', '3093085380', '4420540842243', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Tharo Khan Laghari, Taluka Sinjhoro', 'default_student.png', 1),
('23SW02', 81, 'Shahzaman ', NULL, '', 'Gul Sher', '3173225648', '4420693148253', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Ranjho Khaskheli Taluka Tando Adam', 'default_student.png', 1),
('23SW03', 82, 'Fateh Mohammad', NULL, '', 'Mukhtiar ali', '3103067592', '4440293500667', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Fateh Mohammad Banglani District Umerkot Taluka Samaro', 'default_student.png', 1),
('23SW04', 83, 'Muhammad Soomer', NULL, '', 'Aijaz Ali', '3433202818', '4540231689861', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Ghazi Khan Hoat Taluka Nawabshah', 'default_student.png', 1),
('23SW05', 84, 'Farhan  Ali', NULL, '', 'Amjad Ali', '3020138967', '4120578926667', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Malook Khoso, P.O, Mehar, Taluka: Mehar, Dist: Dadu. (Union Council Baladai)', 'default_student.png', 1),
('23SW09', 85, 'Muhammad Shaheer', NULL, '', 'Nadeem Ismail', '3320390323', '4130693048433', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Plot No: 306 P.O Diplo Diplo Taluka Diplo Dist: Tharparkar', 'default_student.png', 1),
('23SW13', 86, 'Abdul  Samad', NULL, '', 'Abdul Raheem', '3043429360', '4520213083185', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'House # D-26/1 Street #03 Gol Masjid Hyderabad town qasimabad phase 1', 'default_student.png', 1),
('23SW15', 87, 'Ihsan Ali', NULL, '', 'Khair Muhammad ', '3253030467', '4520277926603', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'VIllage Haji Ismail Shah Taluka Gambat District Khairpur', 'default_student.png', 1),
('23SW16', 88, 'Muhammad Anas Alias Wali Muhammad', NULL, '', 'Aijaz Ali Abbasi', '3123477425', '4520811170379', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Near Allah wari masjid Muhalla Ansari, Ranipur Taluka Sobhodero District Khairpur', 'default_student.png', 1),
('23SW17', 89, 'Abdul Samad Watio', NULL, '', 'Yaseen Ali', '3003667231', '4520826944821', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Watia P/O Hingorja Dist: Khairpur Mir\'s', 'default_student.png', 1),
('23SW18', 90, 'Rumaisa  Abbas ', NULL, '', 'Ghulam Abbas', '3252779687', '4530149081882', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Muhalla Yaseen Colony Near Railway Station Bhiria Road', 'default_student.png', 1),
('23SW19', 91, 'Zahid Ali', NULL, '', 'Muhammad Uris', '3093946832', '4530358865963', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Ward No 02 Ahmed Abad Colony Moro', 'default_student.png', 1),
('23SW21', 92, 'Muhammad Faheem', NULL, '', 'Muhammad Tahir ', '3133611247', '4530325824401', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Latif Colony Moro DIST : NAUSHAHRO FEROZE ', 'default_student.png', 1),
('23SW22', 93, 'Sheeraz Ali Channa', NULL, '', 'Irshad Ali', '3453555893', '4150405221673', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Sadhuja Taluka Moro District Naushahro Feroze', 'default_student.png', 1),
('23SW23', 94, 'Muhammad Khizar', NULL, '', 'Abdul Malik', '3147745465', '4550504503791', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'House No. 319, Muhalla Pathan Colony New Pind, Sukkur, Taluka New Sukkur, District Sukkur', 'default_student.png', 1),
('23SW25', 96, 'Muhammad Saqib Kandhir', NULL, '', 'Muhammad Dawood Kandhir', '3333438440', '4550439779307', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Railway Quarter Station Road, House No A-2, Sukkur', 'default_student.png', 1),
('23SW26', 97, 'Bisma Shaikh', NULL, '', 'Abdul Rouf', '3183314336', '4550131465880', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Muhalla Shaikh, Pano Aqil, District Sukkur', 'default_student.png', 1),
('23SW27', 98, 'Shoaib Ali Shaikh', NULL, '', 'Abdul Rauf', '3093377949', '4550156677031', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'House No. B-896, Shaikh Muhalla, Pano Aqil, District Sukkur', 'default_student.png', 1),
('23SW28', 99, 'Najaf Ali', NULL, '', 'Ali Murad', '3236992987', '4510484552101', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Kauro Mahar, P.O Box Mirpur Mathelo, Taluka Mirpur Mathelo District Ghotki', 'default_student.png', 1),
('23SW29', 100, 'Ghulam Qadir', NULL, '', 'Khalid Hussain', '3042362552', '4250128436313', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Survey No: 205 New Allah Himayati Goth Pirri Bin Qasim District Malir Karachi ', 'default_student.png', 1),
('23SW30', 101, 'Muhammad Zaid', NULL, '', 'Abdul Hafeez', '3153260055', '4510276245401', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Near Qadirpur, Gas Field, P.O Box Qadirpur, Bandh, Taluka & District Ghotki', 'default_student.png', 1),
('23SW31', 102, 'Hassnain Ali', NULL, '', 'Sajjad Ahmed Soomro', '3323593651', '4510167403933', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Mohalla Ameer Bux Soomro, Taluka Daharki District Ghotki', 'default_student.png', 1),
('23SW32', 103, 'Usman Ghani Noonari', NULL, '', 'Gulam Shabir', '3041804991', '4320187670669', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Muhalla Noonari Badah, Taluka Dokri District Larkano', 'default_student.png', 1),
('23SW33', 104, 'Rohit Kumar  Aahuja', NULL, '', 'Haresh Kumar', '3183614899', '4340304281171', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Hindu Muhalla Nasirabad ', 'default_student.png', 1),
('23SW34', 105, 'Abdul Samad', NULL, '', 'Iqbal Ahmed', '3363494053', '4320622195975', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Eid Gah Road H.No:294 Muhalla Kalhora Shahdadkot Taluka Shahdadkot', 'default_student.png', 1),
('23SW35', 106, 'Muhammad  Khalil Ur Rehman', NULL, '', 'Muhammad Latif', '3273440498', '4310482714099', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Mirani Muhalla Town Committee Guddu Taluka Kashmore District Kashmore @ Kandhkot', 'default_student.png', 1),
('23SW36', 107, 'Raheel ', NULL, '', 'Riaz Ahmed', '3150376972', '4330498415495', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'House N.26/47 Muhalla Daya Pir Kamal Shah Shikarpur Taluka Shikarpur', 'default_student.png', 1),
('23SW38', 109, 'Atif Noonari ', NULL, '', 'Abdul Gaffar ', '3142622197', '4310510641311', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Waris Dino Noonari, P/O Thul, UDI, Taluka Thul, Dist: Jacobabad', 'default_student.png', 1),
('23SW40', 110, 'Tazaeen Zahra', NULL, '', 'Yar Muhammad', '3483008696', '4180206284286', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Makhdoom Shahnawaz Colony Hala New', 'default_student.png', 1),
('23SW42', 112, 'Mushahid Hussain', NULL, '', 'Ghulam Hussain', '3492725075', '4110138236355', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Haji Muhammad Soomro, P.O Badin, Pano Baqir, Taluka & Dist: Badin', 'default_student.png', 1),
('23SW43', 113, 'Hasnain Memon', NULL, '', 'Imran Khan Memon', '3153678882', '4150604727643', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Memon Mohalla Bhan', 'default_student.png', 1),
('23SW44', 114, 'Abdul  Bari', NULL, '', 'Oshaque Ali', '3098723006', '4540405020275', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Chhuto Khan Pitafi Taluka Daur District Shaheed Benazirabad', 'default_student.png', 1),
('23SW45', 115, 'Muhammad Qasim', NULL, '', 'Rao Muhammad Aslam', '3268914320', '4140852224923', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Nawabshah Town Taluka Nawabshah', 'default_student.png', 1),
('23SW47', 116, 'Aeiraf Fatima', NULL, '', 'Muhammad Akhtar', '3118944060', '4540269371002', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Nawabshah Town Taluka Nawabshah', 'default_student.png', 1),
('23SW49', 117, 'Syed Ahmed  Ali', NULL, '', 'Syed Danish Ali', '3133279450', '4540201158429', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Nawabshah Town Taluka Nawabshah', 'default_student.png', 1),
('23SW50', 118, 'Kumail Ali', NULL, '', 'Wajid Ali Soomro', '3023365154', '4530199782415', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'R/O Ward No:01 Bhiria City, Taluka Bhiria, District Naushahro Feroze', 'default_student.png', 1),
('23SW51', 119, 'Kumail Abbas', NULL, '', 'Asif Ali', '3000827711', '4540276270513', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Jamsahib Town Taluka Daur District Shaheed Benazirabad', 'default_student.png', 1),
('23SW52', 120, 'Qamar Nisa', NULL, '', 'Muhammad Ali', '3263288462', '4530128405222', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Ward No 08 Abra Colony Tharushah', 'default_student.png', 1),
('23SW53', 121, 'Memoona Chandio', NULL, '', 'Abdul Fattah', '3322839478', '4130408563812', NULL, 'Female', '2023-10-01', 3, 1, 0.00, '', 'default_student.png', 1),
('23SW55', 123, 'Muhammad Azam', NULL, '', 'Amjad Ali ', '3181030223', '4250108940591', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Muhalla Memon Nasarpur Town Committee, Taluka & District Tando Allahyar', 'default_student.png', 1),
('23SW56', 124, 'Sadiq  Ali', NULL, '', 'Mohammad Iqbal ', '3487551331', '4410903759455', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Haji Sajan Rajar Deh 75 Taluka Sindhri District Mirpurkhas', 'default_student.png', 1),
('23SW57', 125, 'Haq Nawaz', NULL, '', 'Alamgir ', '3030848882', '2110609064935', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Tali Johar Qilla Salarzai', 'default_student.png', 1),
('23SW58', 126, 'Hamna ', NULL, '', 'Asghar Ali', '3213202010', '4540259802958', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Nawabshah Town Taluka Nawabshah', 'default_student.png', 1),
('23SW59', 127, 'Kinza ', NULL, '', 'Abdul Ghaffar', '3043233982', '4540253903874', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'Nawabshah Town Taluka Nawabshah', 'default_student.png', 1),
('23SW60', 128, 'Muntazir  Ali', NULL, '', 'Ali Gohar', '3093155855', '4540361154861', NULL, 'Male', '2023-10-01', 3, 1, 0.00, 'Village Muhammad Bachal Chandio Taluka Sakrand', 'default_student.png', 1),
('23SW61', 129, 'Sara ', NULL, '', 'Fazal Rehman', '3110211639', '4130147218554', NULL, 'Female', '2023-10-01', 3, 1, 0.00, 'House No.A/12, Mohalla Anwar Villas, Phase-I, Qasimabad, Hyderabad', 'default_student.png', 1),
('24SW01', 395, 'Sania Batool', NULL, '', 'Afshad Ali Abro', '3091153541', '4520449861196', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'Al-Muntazir Mehdi Mohala Kolabjai Tahsil Kingri Disst: Khairpur Mirs', 'default_student.png', 1),
('24SW02', 396, 'Shahzaib Khan', NULL, '', 'Riaz Hussin', '3053212835', '4520820366283', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Villae Watio P.O Hingorja Taluka Sobhedero Disst: Khairpur Mirs', 'default_student.png', 1),
('24SW03', 397, 'Muhammad Safeer', NULL, '', 'Khair Muhammad', '3153752574', '4340705070181', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Channa Muhalla Nasirabad', 'default_student.png', 1),
('24SW04', 398, 'Alishba Farooq', NULL, '', 'Muhammad Farooq Khan', '3273671168', '4540299753052', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'Gulshan-e-Tayaba Colony 32-33/166 Nawabshah', 'default_student.png', 1),
('24SW05', 399, 'Muhammad Bilal', NULL, '', 'Munwar Hussain', '3178267353', '4520139742751', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Kot Lalo Disst: Khairpur Mirs', 'default_student.png', 1),
('24SW06', 400, 'Dildar Ali', NULL, '', 'Sijawal Mahar', '3022796694', '4510395747377', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Phullo Faqeer Taluka Khan Garh Disst: Ghotki', 'default_student.png', 1),
('24SW07', 401, 'Zulfiqar Ali ', NULL, '', 'Khando alias Noor Hassan', '3009674436', '4510250562003', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Allah Ditto Shar Disst: Ghotki', 'default_student.png', 1),
('24SW08', 402, 'Qurban Ali Zardari', NULL, '', 'Juman Khan', '3075706728', '4530380537863', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Goth Mahi Khan Zardari Disst: Naushehro Feroze', 'default_student.png', 1),
('24SW10', 403, 'Rafia Noor', NULL, '', 'Muhammad Ismail Shaikh', '3126322478', '4550241499330', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'Old Fish Market Rizwani Mohlla Rohri', 'default_student.png', 1),
('24SW14', 406, 'Aqsa Khalid', NULL, '', 'Khalid Hussain Samo', '3299673485', '4530407792442', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'C/O Dr. Muhammad Tariq Samo Al-Munwar Colony Naushehro Feroze', 'default_student.png', 1),
('24SW15', 407, 'Shahriyar Ahmed', NULL, '', 'Naeem Ahmed', '3193491057', '4530504154977', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'P.O Khanwahan Taluka Kandiaro Disst: Naushehro Feroze', 'default_student.png', 1),
('24SW16', 408, 'Fatima Ahmed', NULL, '', 'Irshad Ahmed', '3061535716', '4530453857664', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'Village Abdul Aziz Taluka Bhirya Disst: N Feroze', 'default_student.png', 1),
('24SW17', 409, 'Muhammad Paryal', '', 'Memon', 'Shah Ali', '03183257236', '4530504186637', '0000-00-00', 'Male', '2024-09-30', 4, 3, 0.00, NULL, 'default_student.png', 1),
('24SW18', 410, 'Muhammad Musawar', NULL, '', 'Attique Rehman', '3001700551', '4520178218817', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Abdul Hafeez Pato Taluka Faiz Ganj Disst: Khairpur Mirs', 'default_student.png', 1),
('24SW19', 411, 'Abdul Nafay Anas', NULL, '', 'Abdul Wajid Pirzado', '3288264864', '4550405178051', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'House#679/2 Pirzada Muhalla old Sukkur', 'default_student.png', 1),
('24SW20', 412, 'Awais', NULL, '', 'Muhammad Nawaz', '3462693109', '4320534084947', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Muhla Nader Ali Shah Taluka Rato Dero Disst: Larkano', 'default_student.png', 1),
('24SW23', 414, 'Tabeer Hussain', NULL, '', 'Rafiq Ahmed Samo', '3173652704', '4330463578733', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Siddique Marri Agha Haq Shikarpur', 'default_student.png', 1),
('24SW24', 415, 'Mehdi Hassan', NULL, '', 'Shamsuddin Baloch', '3079863303', '4330431563367', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Haji Bhoora Khan Baloch', 'default_student.png', 1),
('24SW25', 416, 'Mansoor Ali', NULL, '', 'Muhammad Khan', '3123119174', '4170306935229', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Jamal ShahTown Bhitt Shah', 'default_student.png', 1),
('24SW30', 419, 'Prithvee Raj', NULL, '', 'Durga Das', '3009375351', '4130297315727', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, '90/3 Doctors Line Road Saddar', 'default_student.png', 1),
('24SW32', 420, 'Jawad Ali', NULL, '', 'Arshad Ali', '3072558258', '4110366449221', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Matli', 'default_student.png', 1),
('24SW33', 421, 'Kalpna', NULL, '', 'Dongro Mal', '3120367311', '4420223373962', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'P.O Phullahdyom Taluka Sindhri Disst: Mirpurkhas', 'default_student.png', 1),
('24SW34', 422, 'Mahadev', NULL, '', 'Chetan', '3283297001', '4410326258839', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Um-e-Hani Town Mirpurkhas', 'default_student.png', 1),
('24SW36', 423, 'Jashan', NULL, '', 'Gordhan', '3493174393', '4430389540531', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Bhakou Taluka Mithi Disst: Tharparkar', 'default_student.png', 1),
('24SW38', 424, 'Ayaz Babar', NULL, '', 'Ghulam Fareed', '3303251089', '4420379455701', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Muhlla Waheed Colony Sanghar', 'default_student.png', 1),
('24SW39', 425, 'Kartik Kumar', NULL, '', 'Gulab', '3325059074', '4420591913721', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Ward No. 02 Muhlla Hameer Faqeer Khadro Taluka Sinjhoro Disst: Sanghar', 'default_student.png', 1),
('24SW41', 426, 'Areesha ', NULL, '', 'Zahid Rasheed', '3194043747', '4540314482248', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'Khanzada Muhlla 1C-16 Taluka Sakrnd Disst: Shaheed Benazirabad', 'default_student.png', 1),
('24SW45', 429, 'Tanveer Hussain', NULL, '', 'Nishan Ali', '3198424398', '4520684248007', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Dr. Ghulam Qadir Mughal Disst: Khairpur Mirs', 'default_student.png', 1),
('24SW46', 430, 'Mujahid Hussain', NULL, '', 'Fayaz Ali', '3082276248', '4530251909231', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'New Bakhri Taluka Kandiaro Disst: N Feroze', 'default_student.png', 1),
('24SW47', 431, 'Javeria', NULL, '', 'Abdul Rab', '3170294306', '4530322650358', '0000-00-00', 'Female', '2024-09-30', 4, 1, 0.00, 'H.No.IC-116 Jhanda Gali Taluka Sakrand Disst: Shaheed Benazirabad', 'default_student.png', 1),
('24SW50', 433, 'Zafar Ali', NULL, 'Laghari', 'Qurban Ali', '3053928510', '45104-1543325-9', '2006-01-01', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Dodo Khan Laghari', '24SW50_updated.PNG', 1),
('24SW51', 434, 'Hamid Ali', NULL, '', 'Ghulam Asghar Pirzada', '3163976971', '4520399535451', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Village Muhammad Shahladani Disst; Khairpur Mirs', 'default_student.png', 1),
('24SW52', 435, 'Awais Ali', NULL, '', 'Qurban Ali', '3240220546', '4350406531513', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'Mirzapur Mohlla Kandh Kot', 'default_student.png', 1),
('24SW54', 437, 'Abdul Samad', NULL, '', 'Muhammad Hashim Abbasi', '3009202441', '4220189045485', '0000-00-00', 'Male', '2024-09-30', 4, 1, 0.00, 'R-102 Shanti Nagar Gulshan-e-Iqbal block-19 Karachi', 'default_student.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `user_id`, `role`, `action`, `description`, `ip_address`, `timestamp`) VALUES
(1, 458, 'HOD', 'RESET', 'Logs Cleared by Admin', '::1', '2026-01-26 20:12:52'),
(2, 463, 'Teacher', 'LOGOUT', 'User logged out', '::1', '2026-01-26 20:16:26'),
(3, 433, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-01-27 10:27:55'),
(4, 458, 'HOD', 'LOGOUT', 'User logged out', '::1', '2026-01-27 10:43:43'),
(5, 463, 'Teacher', 'LOGOUT', 'User logged out', '::1', '2026-01-27 10:44:39'),
(6, 470, 'Clerk', 'LOGOUT', 'User logged out', '::1', '2026-01-27 10:45:59'),
(7, 409, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-01-27 10:50:09'),
(8, 470, 'Clerk', 'LOGOUT', 'User logged out', '::1', '2026-02-01 15:25:19'),
(9, 470, 'Clerk', 'LOGOUT', 'User logged out', '::1', '2026-02-01 15:30:07'),
(10, 470, 'Clerk', 'LOGOUT', 'User logged out', '::1', '2026-02-01 16:04:25'),
(11, 407, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-02-01 16:05:49'),
(12, 458, 'HOD', 'LOGOUT', 'User logged out', '::1', '2026-02-01 16:23:35'),
(13, 409, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-02-01 16:54:19'),
(14, 409, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-02-01 16:54:35'),
(15, 458, 'HOD', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:26:25'),
(16, 433, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:26:43'),
(17, 458, 'HOD', 'UPDATE', 'Updated Student: 24SW50', '::1', '2026-02-01 18:28:03'),
(18, 458, 'HOD', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:28:08'),
(19, 433, 'Student', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:28:58'),
(20, 464, 'Teacher', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:33:00'),
(21, 458, 'HOD', 'LOGOUT', 'User logged out', '::1', '2026-02-01 18:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT 'default_teacher.png',
  `joining_date` date DEFAULT curdate(),
  `designation` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `leaves_taken` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`employee_id`, `user_id`, `first_name`, `last_name`, `cnic`, `qualification`, `email`, `phone_no`, `address`, `profile_photo`, `joining_date`, `designation`, `salary`, `hire_date`, `dept_id`, `leaves_taken`) VALUES
(101, 458, 'Dr Pardeep', 'Kumar', NULL, '', 'hod@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Prof. & Chairman', 0.00, NULL, NULL, 0),
(102, 459, 'Dr Sajida', 'Parveen', NULL, '', 'sajida@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Professor', 0.00, NULL, NULL, 0),
(103, 460, 'Dr Fayaz Ahmed', 'Memon', NULL, '', 'fayaz@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Professor', 0.00, NULL, NULL, 0),
(104, 461, 'Dr Rafia Naz', 'Memon', NULL, '', 'rafia@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Professor', 0.00, NULL, NULL, 0),
(105, 462, 'Dr Imtiaz Ali', 'Halepoto', NULL, '', 'imtiaz@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Professor', 0.00, NULL, NULL, 0),
(106, 463, 'Dr Ali Raza', 'Bhangwar', '', 'Bachelors', 'ali_raza@quest.edu.pk', '', '', 'default_teacher.png', '2026-01-24', 'Lecturer', 347849.00, NULL, NULL, 0),
(107, 464, 'Engr. M. Aamir', 'Bhutto', NULL, '', 'aamir@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Associate Professor', 0.00, NULL, NULL, 0),
(108, 465, 'Engr. Fozia Noureen', 'Shaikh', NULL, '', 'fozia@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Assistant Professor', 0.00, NULL, NULL, 0),
(109, 466, 'Engr. Mir Mohammad', 'Juno', NULL, '', 'mir@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Lecturer', 0.00, NULL, NULL, 0),
(110, 467, 'Engr. Abdul Qadeer', 'Tunio', NULL, '', 'qadeer@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Lab Engineer', 0.00, NULL, NULL, 0),
(111, 468, 'Ms. Mehwish', 'Siyal', NULL, '', 'mehwish@quest.edu.pk]', '', NULL, 'default_teacher.png', '2026-01-24', 'Junior Lab Engineer', 0.00, NULL, NULL, 0),
(112, 469, 'Mr Zohaib', 'Ahmed', NULL, '', 'zohaib@quest.edu.pk', '', NULL, 'default_teacher.png', '2026-01-24', 'Teaching Assistant', 0.00, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_attendance`
--

CREATE TABLE `teacher_attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL,
  `marked_by` varchar(50) DEFAULT 'Clerk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_attendance`
--

INSERT INTO `teacher_attendance` (`attendance_id`, `employee_id`, `attendance_date`, `status`, `marked_by`) VALUES
(76, 101, '2026-01-26', 'Present', 'Clerk'),
(77, 102, '2026-01-26', 'Absent', 'Clerk'),
(78, 103, '2026-01-26', 'Present', 'Clerk'),
(79, 104, '2026-01-26', 'Absent', 'Clerk'),
(80, 105, '2026-01-26', 'Present', 'Clerk'),
(81, 106, '2026-01-26', 'Present', 'Clerk'),
(82, 107, '2026-01-26', 'Absent', 'Clerk'),
(83, 108, '2026-01-26', 'Absent', 'Clerk'),
(84, 109, '2026-01-26', 'Absent', 'Clerk'),
(85, 110, '2026-01-26', 'Absent', 'Clerk'),
(86, 111, '2026-01-26', 'Absent', 'Clerk'),
(87, 112, '2026-01-26', 'Absent', 'Clerk'),
(88, 101, '2026-01-25', 'Present', 'Clerk'),
(89, 102, '2026-01-25', 'Absent', 'Clerk'),
(90, 103, '2026-01-25', 'Present', 'Clerk'),
(91, 104, '2026-01-25', 'Absent', 'Clerk'),
(92, 105, '2026-01-25', 'Present', 'Clerk'),
(93, 106, '2026-01-25', 'Present', 'Clerk'),
(94, 107, '2026-01-25', 'Absent', 'Clerk'),
(95, 108, '2026-01-25', 'Absent', 'Clerk'),
(96, 109, '2026-01-25', 'Absent', 'Clerk'),
(97, 110, '2026-01-25', 'Absent', 'Clerk'),
(98, 111, '2026-01-25', 'Absent', 'Clerk'),
(99, 112, '2026-01-25', 'Absent', 'Clerk'),
(100, 101, '2026-01-24', 'Absent', 'Clerk'),
(101, 102, '2026-01-24', 'Absent', 'Clerk'),
(102, 103, '2026-01-24', 'Absent', 'Clerk'),
(103, 104, '2026-01-24', 'Absent', 'Clerk'),
(104, 105, '2026-01-24', 'Absent', 'Clerk'),
(105, 106, '2026-01-24', 'Absent', 'Clerk'),
(106, 107, '2026-01-24', 'Absent', 'Clerk'),
(107, 108, '2026-01-24', 'Absent', 'Clerk'),
(108, 109, '2026-01-24', 'Absent', 'Clerk'),
(109, 110, '2026-01-24', 'Absent', 'Clerk'),
(110, 111, '2026-01-24', 'Absent', 'Clerk'),
(111, 112, '2026-01-24', 'Absent', 'Clerk'),
(112, 101, '2026-01-23', 'Absent', 'Clerk'),
(113, 102, '2026-01-23', 'Absent', 'Clerk'),
(114, 103, '2026-01-23', 'Absent', 'Clerk'),
(115, 104, '2026-01-23', 'Absent', 'Clerk'),
(116, 105, '2026-01-23', 'Absent', 'Clerk'),
(117, 106, '2026-01-23', 'Absent', 'Clerk'),
(118, 107, '2026-01-23', 'Absent', 'Clerk'),
(119, 108, '2026-01-23', 'Absent', 'Clerk'),
(120, 109, '2026-01-23', 'Absent', 'Clerk'),
(121, 110, '2026-01-23', 'Absent', 'Clerk'),
(122, 111, '2026-01-23', 'Absent', 'Clerk'),
(123, 112, '2026-01-23', 'Absent', 'Clerk'),
(124, 101, '2026-01-22', 'Absent', 'Clerk'),
(125, 102, '2026-01-22', 'Absent', 'Clerk'),
(126, 103, '2026-01-22', 'Absent', 'Clerk'),
(127, 104, '2026-01-22', 'Absent', 'Clerk'),
(128, 105, '2026-01-22', 'Absent', 'Clerk'),
(129, 106, '2026-01-22', 'Present', 'Clerk'),
(130, 107, '2026-01-22', 'Absent', 'Clerk'),
(131, 108, '2026-01-22', 'Absent', 'Clerk'),
(132, 109, '2026-01-22', 'Absent', 'Clerk'),
(133, 110, '2026-01-22', 'Absent', 'Clerk'),
(134, 111, '2026-01-22', 'Absent', 'Clerk'),
(135, 112, '2026-01-22', 'Absent', 'Clerk'),
(136, 101, '2026-01-21', 'Absent', 'Clerk'),
(137, 102, '2026-01-21', 'Absent', 'Clerk'),
(138, 103, '2026-01-21', 'Absent', 'Clerk'),
(139, 104, '2026-01-21', 'Absent', 'Clerk'),
(140, 105, '2026-01-21', 'Absent', 'Clerk'),
(141, 106, '2026-01-21', 'Absent', 'Clerk'),
(142, 107, '2026-01-21', 'Absent', 'Clerk'),
(143, 108, '2026-01-21', 'Absent', 'Clerk'),
(144, 109, '2026-01-21', 'Absent', 'Clerk'),
(145, 110, '2026-01-21', 'Absent', 'Clerk'),
(146, 111, '2026-01-21', 'Absent', 'Clerk'),
(147, 112, '2026-01-21', 'Absent', 'Clerk'),
(148, 101, '2026-01-20', 'Absent', 'Clerk'),
(149, 102, '2026-01-20', 'Absent', 'Clerk'),
(150, 103, '2026-01-20', 'Absent', 'Clerk'),
(151, 104, '2026-01-20', 'Absent', 'Clerk'),
(152, 105, '2026-01-20', 'Absent', 'Clerk'),
(153, 106, '2026-01-20', 'Absent', 'Clerk'),
(154, 107, '2026-01-20', 'Absent', 'Clerk'),
(155, 108, '2026-01-20', 'Absent', 'Clerk'),
(156, 109, '2026-01-20', 'Absent', 'Clerk'),
(157, 110, '2026-01-20', 'Absent', 'Clerk'),
(158, 111, '2026-01-20', 'Absent', 'Clerk'),
(159, 112, '2026-01-20', 'Absent', 'Clerk'),
(160, 101, '2026-01-19', 'Absent', 'Clerk'),
(161, 102, '2026-01-19', 'Absent', 'Clerk'),
(162, 103, '2026-01-19', 'Absent', 'Clerk'),
(163, 104, '2026-01-19', 'Absent', 'Clerk'),
(164, 105, '2026-01-19', 'Absent', 'Clerk'),
(165, 106, '2026-01-19', 'Absent', 'Clerk'),
(166, 107, '2026-01-19', 'Absent', 'Clerk'),
(167, 108, '2026-01-19', 'Absent', 'Clerk'),
(168, 109, '2026-01-19', 'Absent', 'Clerk'),
(169, 110, '2026-01-19', 'Absent', 'Clerk'),
(170, 111, '2026-01-19', 'Absent', 'Clerk'),
(171, 112, '2026-01-19', 'Absent', 'Clerk'),
(172, 101, '2026-01-18', 'Absent', 'Clerk'),
(173, 102, '2026-01-18', 'Absent', 'Clerk'),
(174, 103, '2026-01-18', 'Absent', 'Clerk'),
(175, 104, '2026-01-18', 'Absent', 'Clerk'),
(176, 105, '2026-01-18', 'Absent', 'Clerk'),
(177, 106, '2026-01-18', 'Absent', 'Clerk'),
(178, 107, '2026-01-18', 'Absent', 'Clerk'),
(179, 108, '2026-01-18', 'Absent', 'Clerk'),
(180, 109, '2026-01-18', 'Absent', 'Clerk'),
(181, 110, '2026-01-18', 'Absent', 'Clerk'),
(182, 111, '2026-01-18', 'Absent', 'Clerk'),
(183, 112, '2026-01-18', 'Absent', 'Clerk'),
(184, 101, '2026-01-17', 'Absent', 'Clerk'),
(185, 102, '2026-01-17', 'Absent', 'Clerk'),
(186, 103, '2026-01-17', 'Absent', 'Clerk'),
(187, 104, '2026-01-17', 'Absent', 'Clerk'),
(188, 105, '2026-01-17', 'Absent', 'Clerk'),
(189, 106, '2026-01-17', 'Absent', 'Clerk'),
(190, 107, '2026-01-17', 'Absent', 'Clerk'),
(191, 108, '2026-01-17', 'Absent', 'Clerk'),
(192, 109, '2026-01-17', 'Absent', 'Clerk'),
(193, 110, '2026-01-17', 'Absent', 'Clerk'),
(194, 111, '2026-01-17', 'Absent', 'Clerk'),
(195, 112, '2026-01-17', 'Absent', 'Clerk'),
(196, 101, '2026-01-16', 'Absent', 'Clerk'),
(197, 102, '2026-01-16', 'Absent', 'Clerk'),
(198, 103, '2026-01-16', 'Absent', 'Clerk'),
(199, 104, '2026-01-16', 'Absent', 'Clerk'),
(200, 105, '2026-01-16', 'Absent', 'Clerk'),
(201, 106, '2026-01-16', 'Absent', 'Clerk'),
(202, 107, '2026-01-16', 'Absent', 'Clerk'),
(203, 108, '2026-01-16', 'Absent', 'Clerk'),
(204, 109, '2026-01-16', 'Absent', 'Clerk'),
(205, 110, '2026-01-16', 'Absent', 'Clerk'),
(206, 111, '2026-01-16', 'Absent', 'Clerk'),
(207, 112, '2026-01-16', 'Absent', 'Clerk'),
(208, 101, '2026-01-15', 'Absent', 'Clerk'),
(209, 102, '2026-01-15', 'Absent', 'Clerk'),
(210, 103, '2026-01-15', 'Absent', 'Clerk'),
(211, 104, '2026-01-15', 'Absent', 'Clerk'),
(212, 105, '2026-01-15', 'Absent', 'Clerk'),
(213, 106, '2026-01-15', 'Absent', 'Clerk'),
(214, 107, '2026-01-15', 'Absent', 'Clerk'),
(215, 108, '2026-01-15', 'Absent', 'Clerk'),
(216, 109, '2026-01-15', 'Absent', 'Clerk'),
(217, 110, '2026-01-15', 'Absent', 'Clerk'),
(218, 111, '2026-01-15', 'Absent', 'Clerk'),
(219, 112, '2026-01-15', 'Absent', 'Clerk'),
(220, 101, '2026-01-01', 'Absent', 'Clerk'),
(221, 102, '2026-01-01', 'Present', 'Clerk'),
(222, 103, '2026-01-01', 'Present', 'Clerk'),
(223, 104, '2026-01-01', 'Present', 'Clerk'),
(224, 105, '2026-01-01', 'Present', 'Clerk'),
(225, 106, '2026-01-01', 'Absent', 'Clerk'),
(226, 107, '2026-01-01', 'Absent', 'Clerk'),
(227, 108, '2026-01-01', 'Present', 'Clerk'),
(228, 109, '2026-01-01', 'Absent', 'Clerk'),
(229, 110, '2026-01-01', 'Present', 'Clerk'),
(230, 111, '2026-01-01', 'Absent', 'Clerk'),
(231, 112, '2026-01-01', 'Absent', 'Clerk'),
(232, 101, '2026-01-02', 'Present', 'Clerk'),
(233, 102, '2026-01-02', 'Present', 'Clerk'),
(234, 103, '2026-01-02', 'Absent', 'Clerk'),
(235, 104, '2026-01-02', 'Present', 'Clerk'),
(236, 105, '2026-01-02', 'Absent', 'Clerk'),
(237, 106, '2026-01-02', 'Absent', 'Clerk'),
(238, 107, '2026-01-02', 'Present', 'Clerk'),
(239, 108, '2026-01-02', 'Present', 'Clerk'),
(240, 109, '2026-01-02', 'Absent', 'Clerk'),
(241, 110, '2026-01-02', 'Present', 'Clerk'),
(242, 111, '2026-01-02', 'Absent', 'Clerk'),
(243, 112, '2026-01-02', 'Absent', 'Clerk'),
(244, 101, '2026-01-03', 'Present', 'Clerk'),
(245, 102, '2026-01-03', 'Present', 'Clerk'),
(246, 103, '2026-01-03', 'Present', 'Clerk'),
(247, 104, '2026-01-03', 'Present', 'Clerk'),
(248, 105, '2026-01-03', 'Absent', 'Clerk'),
(249, 106, '2026-01-03', 'Absent', 'Clerk'),
(250, 107, '2026-01-03', 'Absent', 'Clerk'),
(251, 108, '2026-01-03', 'Present', 'Clerk'),
(252, 109, '2026-01-03', 'Absent', 'Clerk'),
(253, 110, '2026-01-03', 'Present', 'Clerk'),
(254, 111, '2026-01-03', 'Absent', 'Clerk'),
(255, 112, '2026-01-03', 'Absent', 'Clerk'),
(256, 101, '2026-01-04', 'Present', 'Clerk'),
(257, 102, '2026-01-04', 'Present', 'Clerk'),
(258, 103, '2026-01-04', 'Present', 'Clerk'),
(259, 104, '2026-01-04', 'Present', 'Clerk'),
(260, 105, '2026-01-04', 'Absent', 'Clerk'),
(261, 106, '2026-01-04', 'Absent', 'Clerk'),
(262, 107, '2026-01-04', 'Absent', 'Clerk'),
(263, 108, '2026-01-04', 'Present', 'Clerk'),
(264, 109, '2026-01-04', 'Absent', 'Clerk'),
(265, 110, '2026-01-04', 'Present', 'Clerk'),
(266, 111, '2026-01-04', 'Absent', 'Clerk'),
(267, 112, '2026-01-04', 'Absent', 'Clerk');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('HOD','Teacher','Student','Clerk') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `is_active`) VALUES
(80, '23SW01', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw01@quest.edu.pk', 'Student', 1),
(81, '23SW02', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw02@quest.edu.pk', 'Student', 1),
(82, '23SW03', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw03@quest.edu.pk', 'Student', 1),
(83, '23SW04', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw04@quest.edu.pk', 'Student', 1),
(84, '23SW05', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw05@quest.edu.pk', 'Student', 1),
(85, '23SW09', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw09@quest.edu.pk', 'Student', 1),
(86, '23SW13', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw13@quest.edu.pk', 'Student', 1),
(87, '23SW15', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw15@quest.edu.pk', 'Student', 1),
(88, '23SW16', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw16@quest.edu.pk', 'Student', 1),
(89, '23SW17', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw17@quest.edu.pk', 'Student', 1),
(90, '23SW18', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw18@quest.edu.pk', 'Student', 1),
(91, '23SW19', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw19@quest.edu.pk', 'Student', 1),
(92, '23SW21', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw21@quest.edu.pk', 'Student', 1),
(93, '23SW22', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw22@quest.edu.pk', 'Student', 1),
(94, '23SW23', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw23@quest.edu.pk', 'Student', 1),
(95, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw24@quest.edu.pk', 'Student', 1),
(96, '23SW25', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw25@quest.edu.pk', 'Student', 1),
(97, '23SW26', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw26@quest.edu.pk', 'Student', 1),
(98, '23SW27', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw27@quest.edu.pk', 'Student', 1),
(99, '23SW28', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw28@quest.edu.pk', 'Student', 1),
(100, '23SW29', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw29@quest.edu.pk', 'Student', 1),
(101, '23SW30', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw30@quest.edu.pk', 'Student', 1),
(102, '23SW31', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw31@quest.edu.pk', 'Student', 1),
(103, '23SW32', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw32@quest.edu.pk', 'Student', 1),
(104, '23SW33', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw33@quest.edu.pk', 'Student', 1),
(105, '23SW34', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw34@quest.edu.pk', 'Student', 1),
(106, '23SW35', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw35@quest.edu.pk', 'Student', 1),
(107, '23SW36', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw36@quest.edu.pk', 'Student', 1),
(108, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw37@quest.edu.pk', 'Student', 1),
(109, '23SW38', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw38@quest.edu.pk', 'Student', 1),
(110, '23SW40', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw40@quest.edu.pk', 'Student', 1),
(111, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw41@quest.edu.pk', 'Student', 1),
(112, '23SW42', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw42@quest.edu.pk', 'Student', 1),
(113, '23SW43', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw43@quest.edu.pk', 'Student', 1),
(114, '23SW44', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw44@quest.edu.pk', 'Student', 1),
(115, '23SW45', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw45@quest.edu.pk', 'Student', 1),
(116, '23SW47', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw47@quest.edu.pk', 'Student', 1),
(117, '23SW49', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw49@quest.edu.pk', 'Student', 1),
(118, '23SW50', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw50@quest.edu.pk', 'Student', 1),
(119, '23SW51', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw51@quest.edu.pk', 'Student', 1),
(120, '23SW52', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw52@quest.edu.pk', 'Student', 1),
(121, '23SW53', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw53@quest.edu.pk', 'Student', 1),
(122, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw54@quest.edu.pk', 'Student', 1),
(123, '23SW55', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw55@quest.edu.pk', 'Student', 1),
(124, '23SW56', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw56@quest.edu.pk', 'Student', 1),
(125, '23SW57', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw57@quest.edu.pk', 'Student', 1),
(126, '23SW58', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw58@quest.edu.pk', 'Student', 1),
(127, '23SW59', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw59@quest.edu.pk', 'Student', 1),
(128, '23SW60', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw60@quest.edu.pk', 'Student', 1),
(129, '23SW61', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw61@quest.edu.pk', 'Student', 1),
(143, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw01@quest.edu.pk', 'Student', 1),
(144, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw02@quest.edu.pk', 'Student', 1),
(145, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw03@quest.edu.pk', 'Student', 1),
(146, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw04@quest.edu.pk', 'Student', 1),
(147, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw05@quest.edu.pk', 'Student', 1),
(148, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw09@quest.edu.pk', 'Student', 1),
(149, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw13@quest.edu.pk', 'Student', 1),
(150, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw15@quest.edu.pk', 'Student', 1),
(151, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw16@quest.edu.pk', 'Student', 1),
(152, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw17@quest.edu.pk', 'Student', 1),
(153, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw18@quest.edu.pk', 'Student', 1),
(154, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw19@quest.edu.pk', 'Student', 1),
(155, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw21@quest.edu.pk', 'Student', 1),
(156, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw22@quest.edu.pk', 'Student', 1),
(157, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw23@quest.edu.pk', 'Student', 1),
(158, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw24@quest.edu.pk', 'Student', 1),
(159, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw25@quest.edu.pk', 'Student', 1),
(160, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw26@quest.edu.pk', 'Student', 1),
(161, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw27@quest.edu.pk', 'Student', 1),
(162, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw28@quest.edu.pk', 'Student', 1),
(163, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw29@quest.edu.pk', 'Student', 1),
(164, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw30@quest.edu.pk', 'Student', 1),
(165, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw31@quest.edu.pk', 'Student', 1),
(166, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw32@quest.edu.pk', 'Student', 1),
(167, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw33@quest.edu.pk', 'Student', 1),
(168, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw34@quest.edu.pk', 'Student', 1),
(169, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw35@quest.edu.pk', 'Student', 1),
(170, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw36@quest.edu.pk', 'Student', 1),
(171, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw37@quest.edu.pk', 'Student', 1),
(172, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw38@quest.edu.pk', 'Student', 1),
(173, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw40@quest.edu.pk', 'Student', 1),
(174, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw41@quest.edu.pk', 'Student', 1),
(175, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw42@quest.edu.pk', 'Student', 1),
(176, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw43@quest.edu.pk', 'Student', 1),
(177, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw44@quest.edu.pk', 'Student', 1),
(178, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw45@quest.edu.pk', 'Student', 1),
(179, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw47@quest.edu.pk', 'Student', 1),
(180, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw49@quest.edu.pk', 'Student', 1),
(181, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw50@quest.edu.pk', 'Student', 1),
(182, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw51@quest.edu.pk', 'Student', 1),
(183, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw52@quest.edu.pk', 'Student', 1),
(184, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw53@quest.edu.pk', 'Student', 1),
(185, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw54@quest.edu.pk', 'Student', 1),
(186, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw55@quest.edu.pk', 'Student', 1),
(187, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw56@quest.edu.pk', 'Student', 1),
(188, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw57@quest.edu.pk', 'Student', 1),
(189, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw58@quest.edu.pk', 'Student', 1),
(190, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw59@quest.edu.pk', 'Student', 1),
(191, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw60@quest.edu.pk', 'Student', 1),
(192, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw61@quest.edu.pk', 'Student', 1),
(206, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw01@quest.edu.pk', 'Student', 1),
(207, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw02@quest.edu.pk', 'Student', 1),
(208, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw03@quest.edu.pk', 'Student', 1),
(209, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw04@quest.edu.pk', 'Student', 1),
(210, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw05@quest.edu.pk', 'Student', 1),
(211, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw09@quest.edu.pk', 'Student', 1),
(212, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw13@quest.edu.pk', 'Student', 1),
(213, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw15@quest.edu.pk', 'Student', 1),
(214, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw16@quest.edu.pk', 'Student', 1),
(215, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw17@quest.edu.pk', 'Student', 1),
(216, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw18@quest.edu.pk', 'Student', 1),
(217, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw19@quest.edu.pk', 'Student', 1),
(218, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw21@quest.edu.pk', 'Student', 1),
(219, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw22@quest.edu.pk', 'Student', 1),
(220, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw23@quest.edu.pk', 'Student', 1),
(221, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw24@quest.edu.pk', 'Student', 1),
(222, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw25@quest.edu.pk', 'Student', 1),
(223, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw26@quest.edu.pk', 'Student', 1),
(224, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw27@quest.edu.pk', 'Student', 1),
(225, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw28@quest.edu.pk', 'Student', 1),
(226, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw29@quest.edu.pk', 'Student', 1),
(227, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw30@quest.edu.pk', 'Student', 1),
(228, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw31@quest.edu.pk', 'Student', 1),
(229, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw32@quest.edu.pk', 'Student', 1),
(230, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw33@quest.edu.pk', 'Student', 1),
(231, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw34@quest.edu.pk', 'Student', 1),
(232, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw35@quest.edu.pk', 'Student', 1),
(233, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw36@quest.edu.pk', 'Student', 1),
(234, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw37@quest.edu.pk', 'Student', 1),
(235, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw38@quest.edu.pk', 'Student', 1),
(236, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw40@quest.edu.pk', 'Student', 1),
(237, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw41@quest.edu.pk', 'Student', 1),
(238, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw42@quest.edu.pk', 'Student', 1),
(239, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw43@quest.edu.pk', 'Student', 1),
(240, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw44@quest.edu.pk', 'Student', 1),
(241, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw45@quest.edu.pk', 'Student', 1),
(242, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw47@quest.edu.pk', 'Student', 1),
(243, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw49@quest.edu.pk', 'Student', 1),
(244, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw50@quest.edu.pk', 'Student', 1),
(245, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw51@quest.edu.pk', 'Student', 1),
(246, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw52@quest.edu.pk', 'Student', 1),
(247, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw53@quest.edu.pk', 'Student', 1),
(248, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw54@quest.edu.pk', 'Student', 1),
(249, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw55@quest.edu.pk', 'Student', 1),
(250, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw56@quest.edu.pk', 'Student', 1),
(251, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw57@quest.edu.pk', 'Student', 1),
(252, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw58@quest.edu.pk', 'Student', 1),
(253, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw59@quest.edu.pk', 'Student', 1),
(254, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw60@quest.edu.pk', 'Student', 1),
(255, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw61@quest.edu.pk', 'Student', 1),
(269, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw01@quest.edu.pk', 'Student', 1),
(270, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw02@quest.edu.pk', 'Student', 1),
(271, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw03@quest.edu.pk', 'Student', 1),
(272, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw04@quest.edu.pk', 'Student', 1),
(273, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw05@quest.edu.pk', 'Student', 1),
(274, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw09@quest.edu.pk', 'Student', 1),
(275, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw13@quest.edu.pk', 'Student', 1),
(276, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw15@quest.edu.pk', 'Student', 1),
(277, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw16@quest.edu.pk', 'Student', 1),
(278, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw17@quest.edu.pk', 'Student', 1),
(279, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw18@quest.edu.pk', 'Student', 1),
(280, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw19@quest.edu.pk', 'Student', 1),
(281, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw21@quest.edu.pk', 'Student', 1),
(282, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw22@quest.edu.pk', 'Student', 1),
(283, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw23@quest.edu.pk', 'Student', 1),
(284, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw24@quest.edu.pk', 'Student', 1),
(285, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw25@quest.edu.pk', 'Student', 1),
(286, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw26@quest.edu.pk', 'Student', 1),
(287, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw27@quest.edu.pk', 'Student', 1),
(288, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw28@quest.edu.pk', 'Student', 1),
(289, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw29@quest.edu.pk', 'Student', 1),
(290, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw30@quest.edu.pk', 'Student', 1),
(291, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw31@quest.edu.pk', 'Student', 1),
(292, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw32@quest.edu.pk', 'Student', 1),
(293, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw33@quest.edu.pk', 'Student', 1),
(294, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw34@quest.edu.pk', 'Student', 1),
(295, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw35@quest.edu.pk', 'Student', 1),
(296, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw36@quest.edu.pk', 'Student', 1),
(297, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw37@quest.edu.pk', 'Student', 1),
(298, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw38@quest.edu.pk', 'Student', 1),
(299, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw40@quest.edu.pk', 'Student', 1),
(300, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw41@quest.edu.pk', 'Student', 1),
(301, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw42@quest.edu.pk', 'Student', 1),
(302, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw43@quest.edu.pk', 'Student', 1),
(303, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw44@quest.edu.pk', 'Student', 1),
(304, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw45@quest.edu.pk', 'Student', 1),
(305, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw47@quest.edu.pk', 'Student', 1),
(306, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw49@quest.edu.pk', 'Student', 1),
(307, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw50@quest.edu.pk', 'Student', 1),
(308, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw51@quest.edu.pk', 'Student', 1),
(309, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw52@quest.edu.pk', 'Student', 1),
(310, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw53@quest.edu.pk', 'Student', 1),
(311, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw54@quest.edu.pk', 'Student', 1),
(312, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw55@quest.edu.pk', 'Student', 1),
(313, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw56@quest.edu.pk', 'Student', 1),
(314, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw57@quest.edu.pk', 'Student', 1),
(315, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw58@quest.edu.pk', 'Student', 1),
(316, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw59@quest.edu.pk', 'Student', 1),
(317, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw60@quest.edu.pk', 'Student', 1),
(318, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw61@quest.edu.pk', 'Student', 1),
(332, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw01@quest.edu.pk', 'Student', 1),
(333, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw02@quest.edu.pk', 'Student', 1),
(334, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw03@quest.edu.pk', 'Student', 1),
(335, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw04@quest.edu.pk', 'Student', 1),
(336, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw05@quest.edu.pk', 'Student', 1),
(337, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw09@quest.edu.pk', 'Student', 1),
(338, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw13@quest.edu.pk', 'Student', 1),
(339, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw15@quest.edu.pk', 'Student', 1),
(340, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw16@quest.edu.pk', 'Student', 1),
(341, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw17@quest.edu.pk', 'Student', 1),
(342, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw18@quest.edu.pk', 'Student', 1),
(343, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw19@quest.edu.pk', 'Student', 1),
(344, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw21@quest.edu.pk', 'Student', 1),
(345, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw22@quest.edu.pk', 'Student', 1),
(346, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw23@quest.edu.pk', 'Student', 1),
(347, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw24@quest.edu.pk', 'Student', 1),
(348, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw25@quest.edu.pk', 'Student', 1),
(349, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw26@quest.edu.pk', 'Student', 1),
(350, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw27@quest.edu.pk', 'Student', 1),
(351, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw28@quest.edu.pk', 'Student', 1),
(352, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw29@quest.edu.pk', 'Student', 1),
(353, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw30@quest.edu.pk', 'Student', 1),
(354, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw31@quest.edu.pk', 'Student', 1),
(355, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw32@quest.edu.pk', 'Student', 1),
(356, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw33@quest.edu.pk', 'Student', 1),
(357, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw34@quest.edu.pk', 'Student', 1),
(358, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw35@quest.edu.pk', 'Student', 1),
(359, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw36@quest.edu.pk', 'Student', 1),
(360, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw37@quest.edu.pk', 'Student', 1),
(361, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw38@quest.edu.pk', 'Student', 1),
(362, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw40@quest.edu.pk', 'Student', 1),
(363, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw41@quest.edu.pk', 'Student', 1),
(364, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw42@quest.edu.pk', 'Student', 1),
(365, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw43@quest.edu.pk', 'Student', 1),
(366, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw44@quest.edu.pk', 'Student', 1),
(367, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw45@quest.edu.pk', 'Student', 1),
(368, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw47@quest.edu.pk', 'Student', 1),
(369, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw49@quest.edu.pk', 'Student', 1),
(370, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw50@quest.edu.pk', 'Student', 1),
(371, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw51@quest.edu.pk', 'Student', 1),
(372, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw52@quest.edu.pk', 'Student', 1),
(373, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw53@quest.edu.pk', 'Student', 1),
(374, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw54@quest.edu.pk', 'Student', 1),
(375, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw55@quest.edu.pk', 'Student', 1),
(376, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw56@quest.edu.pk', 'Student', 1),
(377, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw57@quest.edu.pk', 'Student', 1),
(378, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw58@quest.edu.pk', 'Student', 1),
(379, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw59@quest.edu.pk', 'Student', 1),
(380, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw60@quest.edu.pk', 'Student', 1),
(381, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '23sw61@quest.edu.pk', 'Student', 1),
(395, '24SW01', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw01@quest.edu.pk', 'Student', 1),
(396, '24SW02', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw02@quest.edu.pk', 'Student', 1),
(397, '24SW03', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw03@quest.edu.pk', 'Student', 1),
(398, '24SW04', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw04@quest.edu.pk', 'Student', 1),
(399, '24SW05', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw05@quest.edu.pk', 'Student', 1),
(400, '24SW06', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw06@quest.edu.pk', 'Student', 1),
(401, '24SW07', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw07@quest.edu.pk', 'Student', 1),
(402, '24SW08', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw08@quest.edu.pk', 'Student', 1),
(403, '24SW10', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw10@quest.edu.pk', 'Student', 1),
(404, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw11@quest.edu.pk', 'Student', 1),
(405, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw13@quest.edu.pk', 'Student', 1),
(406, '24SW14', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw14@quest.edu.pk', 'Student', 1),
(407, '24SW15', '$2y$10$g.8E..O4BnSyKHq3hfCqH.KDpiagIcUvE9jdgdqGxfA3EsICZ33zO', '24sw15@quest.edu.pk', 'Student', 1),
(408, '24SW16', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw16@quest.edu.pk', 'Student', 1),
(409, '24SW17', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw17@quest.edu.pk', 'Student', 1),
(410, '24SW18', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw18@quest.edu.pk', 'Student', 1),
(411, '24SW19', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw19@quest.edu.pk', 'Student', 1),
(412, '24SW20', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw20@quest.edu.pk', 'Student', 1),
(413, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw22@quest.edu.pk', 'Student', 1),
(414, '24SW23', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw23@quest.edu.pk', 'Student', 1),
(415, '24SW24', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw24@quest.edu.pk', 'Student', 1),
(416, '24SW25', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw25@quest.edu.pk', 'Student', 1),
(417, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw26@quest.edu.pk', 'Student', 1),
(418, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw27@quest.edu.pk', 'Student', 1),
(419, '24SW30', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw30@quest.edu.pk', 'Student', 1),
(420, '24SW32', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw32@quest.edu.pk', 'Student', 1),
(421, '24SW33', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw33@quest.edu.pk', 'Student', 1),
(422, '24SW34', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw34@quest.edu.pk', 'Student', 1),
(423, '24SW36', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw36@quest.edu.pk', 'Student', 1),
(424, '24SW38', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw38@quest.edu.pk', 'Student', 1),
(425, '24SW39', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw39@quest.edu.pk', 'Student', 1),
(426, '24SW41', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw41@quest.edu.pk', 'Student', 1),
(427, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw43@quest.edu.pk', 'Student', 1),
(428, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw44@quest.edu.pk', 'Student', 1),
(429, '24SW45', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw45@quest.edu.pk', 'Student', 1),
(430, '24SW46', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw46@quest.edu.pk', 'Student', 1),
(431, '24SW47', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw47@quest.edu.pk', 'Student', 1),
(432, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw49@quest.edu.pk', 'Student', 1),
(433, '24sw50@quest.edu.pk', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw50@quest.edu.pk', 'Student', 1),
(434, '24SW51', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw51@quest.edu.pk', 'Student', 1),
(435, '24SW52', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw52@quest.edu.pk', 'Student', 1),
(436, NULL, '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw53@quest.edu.pk', 'Student', 1),
(437, '24SW54', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', '24sw54@quest.edu.pk', 'Student', 1),
(458, 'admin', '$2y$10$3OPOurKAW2m4OhfJAGNcvu5SeUatzTHytoMn11s1aIGmfg7WdCsXC', 'hod@quest.edu.pk', 'HOD', 1),
(459, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'sajida@quest.edu.pk', 'Teacher', 1),
(460, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'fayaz@quest.edu.pk', 'Teacher', 1),
(461, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'rafia@quest.edu.pk', 'Teacher', 1),
(462, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'imtiaz@quest.edu.pk', 'Teacher', 1),
(463, 'ali_raza@quest.edu.pk', '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'ali_raza@quest.edu.pk', 'Teacher', 1),
(464, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'aamir@quest.edu.pk', 'Teacher', 1),
(465, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'fozia@quest.edu.pk', 'Teacher', 1),
(466, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'mir@quest.edu.pk', 'Teacher', 1),
(467, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'qadeer@quest.edu.pk', 'Teacher', 1),
(468, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'mehwish@quest.edu.pk]', 'Teacher', 1),
(469, NULL, '$2y$10$o.IA9kzL1q47aAQxtI9cTesnVEOEukHaxo1m73olqRYGfc9Qzw6MK', 'zohaib@quest.edu.pk', 'Teacher', 1),
(470, 'clerk-1', '$2y$10$NJFq7b/iJIGOIjuVXwBR6uUJZUMow9MibZTbaX7GtEdhwiA/q7hOK', 'clerk@quest.edu.pk', 'Clerk', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocations`
--
ALTER TABLE `allocations`
  ADD PRIMARY KEY (`allocation_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `course_code` (`course_code`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `enrollment_id` (`enrollment_id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`batch_id`);

--
-- Indexes for table `bulk_import`
--
ALTER TABLE `bulk_import`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `idx_ccode` (`course_code`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `roll_no` (`roll_no`),
  ADD KEY `course_code` (`course_code`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`roll_no`),
  ADD UNIQUE KEY `cnic` (`cnic`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_roll` (`roll_no`),
  ADD KEY `idx_name` (`first_name`,`surname`),
  ADD KEY `idx_batch` (`batch_id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_emp` (`employee_id`),
  ADD KEY `idx_tname` (`first_name`);

--
-- Indexes for table `teacher_attendance`
--
ALTER TABLE `teacher_attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allocations`
--
ALTER TABLE `allocations`
  MODIFY `allocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bulk_import`
--
ALTER TABLE `bulk_import`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `teacher_attendance`
--
ALTER TABLE `teacher_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=471;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocations`
--
ALTER TABLE `allocations`
  ADD CONSTRAINT `allocations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `teachers` (`employee_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allocations_ibfk_2` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`enrollment_id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`roll_no`) REFERENCES `students` (`roll_no`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`),
  ADD CONSTRAINT `enrollments_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `teachers` (`employee_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`batch_id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`),
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `teacher_attendance`
--
ALTER TABLE `teacher_attendance`
  ADD CONSTRAINT `teacher_attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `teachers` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
