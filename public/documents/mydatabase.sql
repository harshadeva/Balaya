-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2020 at 07:58 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_privilage`
--

CREATE TABLE `access_privilage` (
  `idaccess_privilage` int(11) NOT NULL,
  `privilage_list_idprivilage_list` int(11) NOT NULL,
  `iduser_role` int(11) NOT NULL,
  `save` int(11) DEFAULT NULL,
  `update` int(11) DEFAULT NULL,
  `delete` int(11) DEFAULT NULL,
  `view` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `idagent` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `idvillage` int(11) NOT NULL,
  `idcareer` int(11) NOT NULL,
  `idethnicity` int(11) NOT NULL,
  `idreligion` int(11) NOT NULL,
  `ideducational_qualification` int(11) NOT NULL,
  `idnature_of_income` int(11) NOT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `is_government` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`idagent`, `idUser`, `iddistrict`, `idelection_division`, `idpolling_booth`, `idgramasewa_division`, `idvillage`, `idcareer`, `idethnicity`, `idreligion`, `ideducational_qualification`, `idnature_of_income`, `referral_code`, `is_government`, `status`, `created_at`, `updated_at`) VALUES
(1, 111, 5, 2, 1, 1, 1, 1, 1, 1, 1, 1, '9XFLYwe', 1, 1, '2020-05-24 11:34:07', '2020-05-24 11:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `agent_event`
--

CREATE TABLE `agent_event` (
  `idagent_event` int(11) NOT NULL,
  `long` text DEFAULT NULL,
  `lat` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `event_idevent` int(11) NOT NULL,
  `usermaster_idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agent_event_member`
--

CREATE TABLE `agent_event_member` (
  `idagent_event_member` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `usermaster_idUser` int(11) NOT NULL,
  `agent_event_idagent_event` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `analysis`
--

CREATE TABLE `analysis` (
  `idpost_category` int(11) NOT NULL,
  `base` int(11) DEFAULT NULL,
  `referrence_id` int(11) DEFAULT NULL,
  `idmain_category` int(11) NOT NULL,
  `idsub_category` int(11) NOT NULL,
  `idcategory` int(11) NOT NULL,
  `commenter` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) DEFAULT NULL,
  `idpolling_booth` int(11) DEFAULT NULL,
  `idgramasewa_division` int(11) DEFAULT NULL,
  `idvillage` int(11) DEFAULT NULL,
  `idUser` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `analysis`
--

INSERT INTO `analysis` (`idpost_category`, `base`, `referrence_id`, `idmain_category`, `idsub_category`, `idcategory`, `commenter`, `iddistrict`, `idelection_division`, `idpolling_booth`, `idgramasewa_division`, `idvillage`, `idUser`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, 4, 111, 5, 2, 1, 1, 1, 110, 1, '2020-05-24 11:37:51', '2020-05-24 11:37:51'),
(2, 1, 3, 2, 3, 4, 111, 5, 2, 1, 1, 1, 110, 1, '2020-05-24 11:39:25', '2020-05-24 11:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `benefical_district`
--

CREATE TABLE `benefical_district` (
  `idbenefical_district` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `benefical_district`
--

INSERT INTO `benefical_district` (`idbenefical_district`, `idPost`, `iddistrict`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 0, 1, '2020-05-24 11:29:41', '2020-05-24 11:29:41'),
(2, 2, 5, 1, 1, '2020-05-24 11:30:26', '2020-05-24 11:30:26'),
(3, 3, 5, 0, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `benefical_election_division`
--

CREATE TABLE `benefical_election_division` (
  `idbenefical_election_division` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `benefical_election_division`
--

INSERT INTO `benefical_election_division` (`idbenefical_election_division`, `idPost`, `idelection_division`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 1, '2020-05-24 11:29:41', '2020-05-24 11:29:41'),
(2, 3, 2, 0, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `benefical_gramasewa_division`
--

CREATE TABLE `benefical_gramasewa_division` (
  `idbenefical_gramasewa_division` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `benefical_gramasewa_division`
--

INSERT INTO `benefical_gramasewa_division` (`idbenefical_gramasewa_division`, `idPost`, `idgramasewa_division`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 0, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `benefical_polling_booth`
--

CREATE TABLE `benefical_polling_booth` (
  `idbenefical_polling_booth` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `benefical_polling_booth`
--

INSERT INTO `benefical_polling_booth` (`idbenefical_polling_booth`, `idPost`, `idpolling_booth`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 0, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `benefical_village`
--

CREATE TABLE `benefical_village` (
  `idbenefical_village` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idvillage` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `benefical_village`
--

INSERT INTO `benefical_village` (`idbenefical_village`, `idPost`, `idvillage`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, 1, '2020-05-24 11:31:26', '2020-05-24 11:31:26');

-- --------------------------------------------------------

--
-- Table structure for table `beneficial_cat`
--

CREATE TABLE `beneficial_cat` (
  `idbeneficial_cat` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idcategory` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `beneficial_cat`
--

INSERT INTO `beneficial_cat` (`idbeneficial_cat`, `idPost`, `idcategory`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2020-05-24 11:29:41', '2020-05-24 11:29:41'),
(2, 2, 4, 1, '2020-05-24 11:30:26', '2020-05-24 11:30:26'),
(3, 3, 4, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `career`
--

CREATE TABLE `career` (
  `idcareer` int(11) NOT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `name_si` varchar(100) DEFAULT NULL,
  `name_ta` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `career`
--

INSERT INTO `career` (`idcareer`, `name_en`, `name_si`, `name_ta`, `status`, `updated_at`, `created_at`) VALUES
(1, 'Agriculture', 'රැකියාව', 'தொழில்', 1, '2019-11-05 18:30:00', '2019-09-10 04:37:38'),
(2, 'Plantation and Livestock', 'රැකියාව', 'தொழில்', 1, '2019-11-19 18:30:00', '2020-01-31 03:48:20'),
(3, 'Art Design and Media(Visual and Performing)', 'රැකියාව', 'தொழில்', 1, '2020-01-31 03:48:46', '2020-01-31 03:48:20'),
(4, 'Automobile Repair and Maintenance', 'රැකියාව', 'தொழில்', 1, '2020-04-15 18:30:00', '2020-04-15 18:30:00'),
(5, 'Building and Construction', 'රැකියාව', 'தொழில்', 1, '2020-05-12 18:30:00', '2020-05-12 18:30:00'),
(6, 'Electrical, Electronics and Telecommunication', 'රැකියාව', 'தொழில்', 1, '2020-05-04 18:30:00', '2020-05-13 18:30:00'),
(7, 'Finance Banking and Management', 'රැකියාව', 'தொழில்', 1, '2020-05-19 18:30:00', '2020-05-26 18:30:00'),
(8, 'Food Technology', 'රැකියාව', 'தொழில்', 1, '2020-05-27 18:30:00', '2020-05-19 18:30:00'),
(9, 'Gem and Jewellery', 'රැකියාව', 'தொழில்', 1, '2020-05-20 18:30:00', '2020-05-20 18:30:00'),
(10, 'Heavy Vehicles Operations', 'රැකියාව', 'தொழில்', 1, '2020-05-26 18:30:00', '2020-05-12 18:30:00'),
(11, 'Hotel and Tourism', 'රැකියාව', 'தொழில்', 1, '2020-05-25 18:30:00', '2020-05-18 18:30:00'),
(12, 'Human Resource Management', 'රැකියාව', 'தொழில்', 1, '2020-05-27 18:30:00', '2020-05-27 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `idcategory` int(11) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`idcategory`, `category`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FOOD', 0, '2019-09-10 04:37:38', '2020-05-21 05:09:46'),
(2, 'ELECTICITY', 1, '2020-01-31 03:48:20', '2020-05-18 12:17:50'),
(3, 'PLAYGRPOUDS', 1, '2020-05-09 16:32:43', '2020-05-18 12:17:43'),
(4, 'HOUSE', 1, '2020-05-09 16:34:56', '2020-05-09 16:34:56'),
(5, 'PADDY FIELD', 0, '2020-05-09 16:35:04', '2020-05-21 05:10:06'),
(6, 'NEUTRILIZER', 1, '2020-05-09 23:58:26', '2020-05-09 23:58:26'),
(7, 'GYM', 1, '2020-05-11 11:01:28', '2020-05-11 11:01:28'),
(8, 'SCHOOL', 0, '2020-05-11 11:01:35', '2020-05-21 05:10:25'),
(9, 'TEMPLE', 1, '2020-05-18 10:32:12', '2020-05-18 10:32:12'),
(11, 'HOSPITAL', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(12, 'LIBRARY', 1, '2020-01-31 03:48:20', '2019-11-19 18:30:00'),
(17, 'WATER', 1, '2020-01-31 03:48:20', '2019-11-19 18:30:00'),
(23, 'GROUND', 1, '2020-01-31 03:48:20', '2019-11-19 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `direct_message`
--

CREATE TABLE `direct_message` (
  `iddirect_message` int(11) NOT NULL,
  `from_idUser` int(11) NOT NULL,
  `to_idUser` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `message_type` int(11) DEFAULT NULL,
  `size` double DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `categorized` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `iddistrict` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `name_en` varchar(45) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`iddistrict`, `idUser`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ampara', 'අම්පාර', 'அம்பாறை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(2, 1, 'Anuradhapura', 'අනුරාධපුරය', 'அனுராதபுரம்', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(3, 1, 'Badulla', 'බදුල්ල', 'பதுளை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(4, 1, 'Batticaloa', 'මඩකලපුව', 'மட்டக்களப்பு', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(5, 1, 'Colombo', 'කොළඹ', 'கொழும்பு', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(6, 1, 'Galle', 'ගාල්ල', 'காலி', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(7, 1, 'Gampaha', 'ගම්පහ', 'கம்பஹா', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(8, 1, 'Hambantota', 'හම්බන්තොට', 'அம்பாந்தோட்டை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(9, 1, 'Jaffna', 'යාපනය', 'யாழ்ப்பாணம்', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(10, 1, 'Kalutara', 'කළුතර', 'களுத்துறை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(11, 1, 'Kandy', 'මහනුවර', 'கண்டி', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(12, 1, 'Kegalle', 'කෑගල්ල', 'கேகாலை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(13, 1, 'Kilinochchi', 'කිලිනොච්චිය', 'கிளிநொச்சி', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(14, 1, 'Kurunegala', 'කුරුණෑගල', 'குருணாகல்', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(15, 1, 'Mannar', 'මන්නාරම', 'மன்னார்', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(16, 1, 'Matale', 'මාතලේ', 'மாத்தளை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(17, 1, 'Matara', 'මාතර', 'மாத்தறை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(18, 1, 'Monaragala', 'මොණරාගල', 'மொணராகலை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(19, 1, 'Mullaitivu', 'මුලතිව්', 'முல்லைத்தீவு', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(20, 1, 'Nuwara Eliya', 'නුවර එළිය', 'நுவரேலியா', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(21, 1, 'Polonnaruwa', 'පොළොන්නරුව', 'பொலன்னறுவை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(22, 1, 'Puttalam', 'පුත්තලම', 'புத்தளம்', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(23, 1, 'Ratnapura', 'රත්නපුර', 'இரத்தினபுரி', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(24, 1, 'Trincomalee', 'ත්‍රිකුණාමලය', 'திருகோணமலை', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00'),
(25, 1, 'Vavuniya', 'වව්නියාව', 'வவுனியா', 1, '2020-05-05 18:30:00', '2020-05-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `educational_qualification`
--

CREATE TABLE `educational_qualification` (
  `ideducational_qualification` int(11) NOT NULL,
  `name_en` varchar(65) DEFAULT NULL,
  `name_si` varchar(65) DEFAULT NULL,
  `name_ta` varchar(65) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `creatd_at` timestamp NULL DEFAULT NULL,
  `updatd_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `educational_qualification`
--

INSERT INTO `educational_qualification` (`ideducational_qualification`, `name_en`, `name_si`, `name_ta`, `status`, `creatd_at`, `updatd_at`) VALUES
(1, 'GRADE - 5', 'අධ්‍යාපන සුදුසුකම්', '\r\nகல்வி தகுதி', 1, '2020-05-07 18:30:00', '2020-05-13 18:30:00'),
(2, 'GRADE - 8 ', 'අධ්‍යාපන සුදුසුකම්', '\r\nகல்வி தகுதி', 1, '2020-05-12 18:30:00', '2020-05-12 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `election_division`
--

CREATE TABLE `election_division` (
  `idelection_division` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `name_en` varchar(45) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `election_division`
--

INSERT INTO `election_division` (`idelection_division`, `idUser`, `iddistrict`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 58, 5, 'COLOMBO - EAST', 'කොලොම්බො - ඊස්ට්', 'கொழும்பு - கிழக்கு', 1, '2020-05-12 06:34:08', '2020-05-16 09:47:25'),
(2, 58, 5, 'COLOMBO - WEST', 'කොලොම්බො - වෙස්ටර්න්', 'கொழும்பு - மேற்கு', 1, '2020-05-12 06:34:52', '2020-05-16 10:13:06'),
(3, 58, 5, 'COLOMBO - SOUTH', 'කොලොම්බො - සවුත්', 'கொழும்பு - தெற்கு', 1, '2020-05-12 06:35:40', '2020-05-16 10:13:06'),
(4, 58, 5, 'COLOMBO - NORTH', 'කොලොම්බො - නොර්ත්', 'கொழும்பு - வடக்கு', 1, '2020-05-12 06:36:18', '2020-05-16 09:35:42'),
(10, 77, 5, 'PANADURA', 'පානදුර', 'பனதுரா', 1, '2020-05-21 22:49:49', '2020-05-21 22:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `ethnicity`
--

CREATE TABLE `ethnicity` (
  `idethnicity` int(11) NOT NULL,
  `name_en` varchar(45) DEFAULT NULL,
  `name_si` varchar(65) DEFAULT NULL,
  `name_ta` varchar(65) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ethnicity`
--

INSERT INTO `ethnicity` (`idethnicity`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 'sinhala', 'ජාතිය', 'இனம்', 1, '2019-11-07 18:30:00', '2019-11-08 18:30:00'),
(2, 'Tamil', 'ජාතිය', 'இனம்', 1, '2020-01-31 03:48:20', '2019-12-17 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `idevent` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `idvillage` int(11) NOT NULL,
  `event_name` varchar(65) DEFAULT NULL,
  `event_location` varchar(65) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gramasewa_division`
--

CREATE TABLE `gramasewa_division` (
  `idgramasewa_division` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `name_en` varchar(55) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gramasewa_division`
--

INSERT INTO `gramasewa_division` (`idgramasewa_division`, `idUser`, `iddistrict`, `idelection_division`, `idpolling_booth`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 58, 5, 2, 1, 'SUWARAPOLA', 'සුවාරපොල', 'ஆரோக்கியம்', 1, '2020-05-12 06:47:44', '2020-05-16 09:54:04'),
(2, 58, 5, 2, 1, 'HADIGAMA', 'හැඩිගම', 'ஹடிகம', 1, '2020-05-12 06:48:14', '2020-05-16 10:11:08'),
(3, 58, 5, 4, 4, 'fgdsfgdf', 'dfgdfg', 'dfgdf', 1, '2020-05-18 10:14:20', '2020-05-18 10:14:20'),
(4, 58, 5, 3, 3, 'DSFS', 'FSDFS', 'FSD', 1, '2020-05-18 10:14:46', '2020-05-18 10:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `main_category`
--

CREATE TABLE `main_category` (
  `idmain_category` int(11) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`idmain_category`, `category`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PRIVATE', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(2, 'PUBLIC', 1, '2020-01-31 03:48:20', '2019-12-17 18:30:00'),
(3, 'GROUP', 1, '2020-01-31 03:48:20', '2020-01-31 03:48:46');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `idmember` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `idvillage` int(11) NOT NULL,
  `idethnicity` int(11) NOT NULL,
  `idreligion` int(11) NOT NULL,
  `ideducational_qualification` int(11) NOT NULL,
  `idnature_of_income` int(11) NOT NULL,
  `idcareer` int(11) NOT NULL,
  `is_government` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `member_agent`
--

CREATE TABLE `member_agent` (
  `idmember_agent` int(11) NOT NULL,
  `idmember` int(11) NOT NULL,
  `idagent` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nature_of_income`
--

CREATE TABLE `nature_of_income` (
  `idnature_of_income` int(11) NOT NULL,
  `name_si` varchar(65) DEFAULT NULL,
  `name_ta` varchar(65) DEFAULT NULL,
  `name_en` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nature_of_income`
--

INSERT INTO `nature_of_income` (`idnature_of_income`, `name_si`, `name_ta`, `name_en`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'POOR', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(2, NULL, NULL, 'MIDDLE', 1, '2020-01-31 03:48:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('1', 90, 1, NULL, '[]', 0, '2020-05-22 21:07:52', '2020-05-22 21:07:52', '2021-05-23 02:37:52'),
('1335755d50d10acb6975b12370d2a92d8c1d651710875740a7caeae90bd0d04f5a23f45c4c6a3b43', 106, 1, 'authToken', '[]', 0, '2020-05-23 23:46:17', '2020-05-23 23:46:17', '2021-05-24 05:16:17'),
('1afb0dffd0481442396256701d597cc852333c807d58ba887d3a0d276ac085206272a3753cb39bd9', 107, 1, 'authToken', '[]', 0, '2020-05-24 00:33:24', '2020-05-24 00:33:24', '2021-05-24 06:03:24'),
('2147483647', 101, 1, NULL, '[]', 0, '2020-05-23 23:39:14', '2020-05-23 23:39:14', '2021-05-24 05:09:14'),
('248769cc6b9044a121980354845826ef613ec5b52d825cb5fb7079fab0c4648e267b97b8fb29b27e', 111, 1, 'authToken', '[]', 0, '2020-05-24 11:34:10', '2020-05-24 11:34:10', '2021-05-24 17:04:10'),
('25807', 96, 1, 'authToken', '[]', 0, '2020-05-22 21:26:36', '2020-05-22 21:26:36', '2021-05-23 02:56:36'),
('25808', 97, 1, NULL, '[]', 0, '2020-05-22 21:54:25', '2020-05-22 21:54:25', '2021-05-23 03:24:25'),
('25809', 98, 1, NULL, '[]', 0, '2020-05-23 23:24:07', '2020-05-23 23:24:07', '2021-05-24 04:54:07'),
('25810', 99, 1, NULL, '[]', 0, '2020-05-23 23:32:55', '2020-05-23 23:32:55', '2021-05-24 05:02:55'),
('25811', 100, 1, NULL, '[]', 0, '2020-05-23 23:37:09', '2020-05-23 23:37:09', '2021-05-24 05:07:09'),
('3', 94, 1, 'authToken', '[]', 0, '2020-05-22 21:13:04', '2020-05-22 21:13:04', '2021-05-23 02:43:04'),
('49689363564dd410dc0de75640604190c2e9520bc46eb9b1625ad9c8a0e40a6f546e65c11b808dbd', 107, 1, 'authToken', '[]', 0, '2020-05-23 23:49:00', '2020-05-23 23:49:00', '2021-05-24 05:19:00'),
('6', 85, 1, 'authToken', '[]', 0, '2020-05-23 06:31:43', '2020-05-23 06:31:43', '2021-05-23 12:01:43'),
('6f40d7d9b854e34fdd34d961edd3760008319cd0e4323e45f717b973cc5e20a7588234745f3dd3cc', 107, 1, 'authToken', '[]', 0, '2020-05-24 00:33:06', '2020-05-24 00:33:06', '2021-05-24 06:03:06'),
('718', 103, 1, 'authToken', '[]', 0, '2020-05-23 23:40:57', '2020-05-23 23:40:57', '2021-05-24 05:10:57'),
('8', 95, 1, 'authToken', '[]', 0, '2020-05-22 21:13:35', '2020-05-22 21:13:35', '2021-05-23 02:43:35'),
('870a2dea8f03721cf4eaa81e916599cae6bf17b3dfff6d0f172978a4e0791dd580518d175ee4b4c0', 105, 1, 'authToken', '[]', 0, '2020-05-23 23:46:07', '2020-05-23 23:46:07', '2021-05-24 05:16:07'),
('ab8dcbb1213f143fa679eadb464dd4deed0e859555d0d5947972dddff4290c760bf1edaafeb7541a', 107, 1, 'authToken', '[]', 0, '2020-05-24 00:34:53', '2020-05-24 00:34:53', '2021-05-24 06:04:53'),
('c68bd57dbcd9ecff37987bd8f1550283aa0dd538a6d5399ce4332f64b2f38195c19d4fc190b471f8', 107, 1, 'authToken', '[]', 0, '2020-05-24 00:35:11', '2020-05-24 00:35:11', '2021-05-24 06:05:11'),
('dd7de23deeb5d0beac49a335389e754fcfb6af4a9d115160986b52167cd0d307ef30b097c4be496c', 107, 1, 'authToken', '[]', 0, '2020-05-23 23:48:11', '2020-05-23 23:48:11', '2021-05-24 05:18:11'),
('e46cbd9ba9f1256fa0121f00207b7d52018086ee6a6e38d656a96540dc5722f4d659cf1d4ebd4119', 107, 1, 'authToken', '[]', 0, '2020-05-24 00:35:43', '2020-05-24 00:35:43', '2021-05-24 06:05:43');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` int(11) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `redirect` text DEFAULT NULL,
  `personal_access_client` tinyint(1) DEFAULT NULL,
  `password_client` tinyint(1) DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Laravel Personal Access Client', 'ZxALVokvjQ7osjKv8ZiEreD3ShwUVjbumWG6S9h5', 'http://localhost', 1, 0, 0, '2020-05-22 21:07:14', '2020-05-22 21:07:14', NULL),
(2, 'Laravel Password Grant Client', 'zJAy5Em2Yz1JmOJxA2vUyLaRPjhZiqmAe68fShGD', 'http://localhost', 0, 1, 0, '2020-05-22 21:07:14', '2020-05-22 21:07:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `created_at`, `updated_at`, `client_id`) VALUES
(0, '2020-05-22 21:07:14', '2020-05-22 21:07:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) DEFAULT NULL,
  `revoked` tinyint(1) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `idoffice` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `office_name` varchar(45) DEFAULT NULL,
  `random` varchar(10) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `monthly_payment` double DEFAULT NULL,
  `analysis_available` tinyint(1) DEFAULT NULL,
  `attendence_available` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`idoffice`, `iddistrict`, `office_name`, `random`, `payment_date`, `sub_total`, `discount`, `monthly_payment`, `analysis_available`, `attendence_available`, `created_at`, `updated_at`, `status`) VALUES
(1, 5, 'woffice', 'PpWwnjc', '2020-05-14', 15000, 0, 15000, 1, 1, '2020-05-24 11:26:02', '2020-05-24 11:26:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `office_admin`
--

CREATE TABLE `office_admin` (
  `idoffice_admin` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `office_admin`
--

INSERT INTO `office_admin` (`idoffice_admin`, `idUser`, `referral_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 108, '8Rot0wf', 1, '2020-05-24 11:26:40', '2020-05-24 11:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `office_staff`
--

CREATE TABLE `office_staff` (
  `idoffice_staff` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `office_staff`
--

INSERT INTO `office_staff` (`idoffice_staff`, `idUser`, `status`, `created_at`, `updated_at`) VALUES
(1, 110, 1, '2020-05-24 11:28:08', '2020-05-24 11:28:08');

-- --------------------------------------------------------

--
-- Table structure for table ` password_resets`
--

CREATE TABLE ` password_resets` (
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(225) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `idpayment` int(11) NOT NULL,
  `idoffice` int(11) NOT NULL,
  `discount` double DEFAULT NULL,
  `payment` double DEFAULT NULL,
  `total_with_discount` double DEFAULT NULL,
  `for_month` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `polling_booth`
--

CREATE TABLE `polling_booth` (
  `idpolling_booth` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `name_en` varchar(55) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `polling_booth`
--

INSERT INTO `polling_booth` (`idpolling_booth`, `idUser`, `iddistrict`, `idelection_division`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 58, 5, 2, 'KESBEWA CW', 'කැස්බැව', 'கெஸ்பேவா', 1, '2020-05-12 06:37:26', '2020-05-16 10:11:38'),
(2, 58, 5, 2, 'MAHARAGAMA CW', 'මහරගම', 'மகாரகமத்தில் வீடுகள்', 1, '2020-05-12 06:38:02', '2020-05-16 09:48:52'),
(3, 1, 5, 3, 'AWISSAWELLA CS', 'අවිස්සාවෙල්ල', 'அவிசாவெல்லா', 1, '2020-05-12 07:09:30', '2020-05-13 22:35:20'),
(4, 58, 5, 4, 'CN', 'cn sinhala', 'cn tamil', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(5, 58, 5, 9, 'sdfs', 'sdf', 'ssd', 1, '2020-05-18 10:13:08', '2020-05-18 10:13:08'),
(6, 58, 5, 3, 'CFVBCVB', 'BCVBC', 'CVBC', 1, '2020-05-18 10:13:46', '2020-05-18 10:13:57'),
(7, 58, 5, 4, 'dfsd', 'fsdf', 'sdfsd', 1, '2020-05-18 10:14:05', '2020-05-18 10:14:11');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `idPost` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idoffice` int(11) NOT NULL,
  `post_no` varchar(45) DEFAULT NULL,
  `title_en` varchar(100) DEFAULT NULL,
  `title_si` varchar(150) DEFAULT NULL,
  `title_ta` varchar(150) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_si` text DEFAULT NULL,
  `text_ta` text DEFAULT NULL,
  `isOnce` int(11) DEFAULT NULL,
  `job_sector` int(11) DEFAULT NULL,
  `preferred_gender` int(11) DEFAULT NULL,
  `minAge` int(11) DEFAULT NULL,
  `maxAge` int(11) DEFAULT NULL,
  `careers` tinyint(1) DEFAULT NULL,
  `religions` tinyint(1) DEFAULT NULL,
  `ethnicities` tinyint(1) DEFAULT NULL,
  `educations` tinyint(1) DEFAULT NULL,
  `incomes` tinyint(1) DEFAULT NULL,
  `response_panel` int(11) DEFAULT NULL,
  `categorized` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `expire_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`idPost`, `idUser`, `idoffice`, `post_no`, `title_en`, `title_si`, `title_ta`, `text_en`, `text_si`, `text_ta`, `isOnce`, `job_sector`, `preferred_gender`, `minAge`, `maxAge`, `careers`, `religions`, `ethnicities`, `educations`, `incomes`, `response_panel`, `categorized`, `status`, `expire_date`, `created_at`, `updated_at`) VALUES
(1, 108, 1, '1', 'What is Lorem Ipsum?', NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', NULL, NULL, 0, NULL, NULL, 0, 120, 0, 0, 0, 0, 0, 1, 0, 1, '2020-05-27 18:30:00', '2020-05-24 11:29:40', '2020-05-24 11:29:40'),
(2, 108, 1, '2', 'Where can I get some?', NULL, NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', NULL, NULL, 0, NULL, NULL, 60, 60, 0, 0, 0, 0, 0, 1, 0, 1, '2020-05-14 18:30:00', '2020-05-24 11:30:25', '2020-05-24 11:30:25'),
(3, 108, 1, '3', 'Why do we use it?', NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', NULL, NULL, 0, NULL, NULL, 60, 60, 0, 0, 0, 0, 0, 1, 0, 1, '2020-05-28 18:30:00', '2020-05-24 11:31:24', '2020-05-24 11:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `post_attachment`
--

CREATE TABLE `post_attachment` (
  `idpost_attachment` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `attachment` text DEFAULT NULL,
  `file_type` int(11) DEFAULT NULL,
  `size` double DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_attachment`
--

INSERT INTO `post_attachment` (`idpost_attachment`, `idPost`, `attachment`, `file_type`, `size`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'image5ecaa7fc8b48f13.jpg', 1, 78983, 1, '2020-05-24 11:29:41', '2020-05-24 11:29:41'),
(2, 1, 'image5ecaa7fd9a7c954.jpg', 1, 5648, 1, '2020-05-24 11:29:41', '2020-05-24 11:29:41'),
(3, 2, 'audio5ecaa82a134e991.mp3', 3, 3736576, 1, '2020-05-24 11:30:26', '2020-05-24 11:30:26'),
(4, 2, 'audio5ecaa82a391e199.mp3', 3, 5367808, 1, '2020-05-24 11:30:26', '2020-05-24 11:30:26'),
(5, 3, 'video5ecaa8655955323.mp4', 2, 2446184, 1, '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `post_career`
--

CREATE TABLE `post_career` (
  `idpost_career` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idcareer` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_district`
--

CREATE TABLE `post_district` (
  `idpost_district` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `allChild` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_district`
--

INSERT INTO `post_district` (`idpost_district`, `idPost`, `iddistrict`, `allChild`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 0, 1, '2020-05-24 11:29:40', '2020-05-24 11:29:40'),
(2, 2, 5, 1, 1, '2020-05-24 11:30:25', '2020-05-24 11:30:25'),
(3, 3, 5, 0, 1, '2020-05-24 11:31:24', '2020-05-24 11:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `post_education`
--

CREATE TABLE `post_education` (
  `idpost_education` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `ideducational_qualification` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_election_division`
--

CREATE TABLE `post_election_division` (
  `idpost_election_division` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `allChild` int(11) DEFAULT NULL,
  `status` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_election_division`
--

INSERT INTO `post_election_division` (`idpost_election_division`, `idPost`, `idelection_division`, `allChild`, `status`, `updated_at`, `created_at`) VALUES
(1, 1, 2, 1, '0000-00-00 00:00:00', '2020-05-24 11:29:40', '2020-05-24 11:29:40'),
(2, 3, 2, 0, '0000-00-00 00:00:00', '2020-05-24 11:31:24', '2020-05-24 11:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `post_ethnicity`
--

CREATE TABLE `post_ethnicity` (
  `idpost_ethnicity` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idethnicity` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_gramasewa_division`
--

CREATE TABLE `post_gramasewa_division` (
  `idpost_gramasewa_division` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `allChild` int(11) DEFAULT NULL,
  `status` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_gramasewa_division`
--

INSERT INTO `post_gramasewa_division` (`idpost_gramasewa_division`, `idPost`, `idgramasewa_division`, `allChild`, `status`, `updated_at`, `created_at`) VALUES
(1, 3, 1, 0, '0000-00-00 00:00:00', '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `post_income`
--

CREATE TABLE `post_income` (
  `idpost_income` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idnature_of_income` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_polling_booth`
--

CREATE TABLE `post_polling_booth` (
  `idpost_polling_booth` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `allChild` int(11) DEFAULT NULL,
  `status` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_polling_booth`
--

INSERT INTO `post_polling_booth` (`idpost_polling_booth`, `idPost`, `idpolling_booth`, `allChild`, `status`, `updated_at`, `created_at`) VALUES
(1, 3, 1, 0, '0000-00-00 00:00:00', '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `post_religion`
--

CREATE TABLE `post_religion` (
  `idpost_religion` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idreligion` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_response`
--

CREATE TABLE `post_response` (
  `idpost_response` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `response` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `size` double DEFAULT NULL,
  `response_type` int(11) DEFAULT NULL,
  `categorized` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_response`
--

INSERT INTO `post_response` (`idpost_response`, `idPost`, `idUser`, `response`, `attachment`, `size`, `response_type`, `categorized`, `is_admin`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 111, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', '', 0, 1, 1, 0, 1, '2020-05-24 11:35:21', '2020-05-24 11:37:51'),
(2, 3, 111, 'Ok we will consider it', '', 0, 1, 0, 1, 1, '2020-05-24 11:38:31', '2020-05-24 11:38:31'),
(3, 3, 111, 'English versions from the 1914 translation by H. Rackham', '', 0, 1, 1, 0, 1, '2020-05-24 11:38:37', '2020-05-24 11:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `post_view`
--

CREATE TABLE `post_view` (
  `idpost_view` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `count` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_village`
--

CREATE TABLE `post_village` (
  `idpost_village` int(11) NOT NULL,
  `idPost` int(11) NOT NULL,
  `idvillage` int(11) NOT NULL,
  `status` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_village`
--

INSERT INTO `post_village` (`idpost_village`, `idPost`, `idvillage`, `status`, `updated_at`, `created_at`) VALUES
(1, 3, 1, '0000-00-00 00:00:00', '2020-05-24 11:31:25', '2020-05-24 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `privilage_list`
--

CREATE TABLE `privilage_list` (
  `idprivilage_list` int(11) NOT NULL,
  `privilage_name` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

CREATE TABLE `religion` (
  `idreligion` int(11) NOT NULL,
  `name_en` varchar(45) DEFAULT NULL,
  `name_si` varchar(65) DEFAULT NULL,
  `name_ta` varchar(65) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `religion`
--

INSERT INTO `religion` (`idreligion`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BUDDHIST', NULL, NULL, 1, '2019-11-07 18:30:00', '2019-11-05 18:30:00'),
(2, 'CATHELIC', NULL, NULL, 1, '2020-01-31 03:48:20', '2019-12-17 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff_gramasewa_devision`
--

CREATE TABLE `staff_gramasewa_devision` (
  `idstaff_gramasewa_devision` int(11) NOT NULL,
  `idoffice_staff` int(11) NOT NULL,
  `idoffice` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_gramasewa_devision`
--

INSERT INTO `staff_gramasewa_devision` (`idstaff_gramasewa_devision`, `idoffice_staff`, `idoffice`, `idgramasewa_division`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, '2020-05-24 11:36:52', '2020-05-24 11:36:52'),
(2, 1, 1, 1, 1, '2020-05-24 11:36:52', '2020-05-24 11:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `idsub_category` int(11) NOT NULL,
  `categroy` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category` (`idsub_category`, `categroy`, `status`, `created_at`, `updated_at`) VALUES
(1, 'QUESTION', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(2, 'PERPOSAL', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(3, 'REQUEST', 1, '2020-01-31 03:48:20', '2019-11-19 18:30:00'),
(4, 'RESPONSE', 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `idtask` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `task_no` int(11) DEFAULT NULL,
  `target` int(11) DEFAULT NULL,
  `task_gender` int(11) DEFAULT NULL,
  `task_job_sector` int(11) DEFAULT NULL,
  `completed_amount` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_age`
--

CREATE TABLE `task_age` (
  `idtask_age` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `comparison` int(11) DEFAULT NULL,
  `minAge` int(11) DEFAULT NULL,
  `maxAge` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_career`
--

CREATE TABLE `task_career` (
  `idtask_career` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `idcareer` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_education`
--

CREATE TABLE `task_education` (
  `idtask_education` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `ideducational_qualification` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_ethnicity`
--

CREATE TABLE `task_ethnicity` (
  `idtask_ethnicity` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `idethnicity` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_income`
--

CREATE TABLE `task_income` (
  `idtask_income` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `idnature_of_income` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `task_religion`
--

CREATE TABLE `task_religion` (
  `idtask_religion` int(11) NOT NULL,
  `idtask` int(11) NOT NULL,
  `idreligion` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usermaster`
--

CREATE TABLE `usermaster` (
  `idUser` int(11) NOT NULL,
  `idoffice` int(11) NOT NULL,
  `iduser_role` int(11) NOT NULL,
  `iduser_title` int(11) NOT NULL,
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `nic` varchar(15) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `contact_no1` varchar(25) DEFAULT NULL,
  `contact_no2` varchar(25) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `system_language` varchar(3) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `api_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usermaster`
--

INSERT INTO `usermaster` (`idUser`, `idoffice`, `iduser_role`, `iduser_title`, `fName`, `lName`, `nic`, `gender`, `contact_no1`, `contact_no2`, `address`, `email`, `username`, `password`, `bday`, `system_language`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `status`, `api_token`) VALUES
(1, 1, 2, 1, 'SUPER', 'ADMIN', '111111111V', 0, NULL, NULL, NULL, 'admin@gmail.com', 'superAdmin', '$2y$10$Ip2pgpLQehq1JtVFHUnRQeLGd8.x2IkVgKZeCm9bE9EhBIEhB5ovy', NULL, '1', NULL, '08BuvKkX1EwqiV78cpjjkS8Z2MoXO9okpNdYTUoOqfIRw4Cowq63S3En4BqJ', '2020-05-03 10:31:04', '2020-05-18 09:15:28', 1, NULL),
(108, 1, 3, 1, 'office', 'admin', '950184019V', 0, NULL, NULL, NULL, NULL, 'office', '$2y$10$EmjjXXRYvl0tz3u9Wrh9AO5.4XlNOa.r98x9K0lz7pwQCn1izwtl6', '2020-05-19', '1', NULL, 'Fp2iPjpSlLDXJUiFnuEYX51AZGsUT7mWanwH4k4q3azC5YU5lc1rooApmcSz', '2020-05-24 11:26:40', '2020-05-24 11:26:40', 1, NULL),
(109, 1, 4, 1, 'managemetn', 'managemetn', NULL, 0, NULL, NULL, NULL, NULL, 'managment', '$2y$10$8az2csIEdd0a1csswdnYTO4H7DxP5ZrakXEAawUyOWfJ9NX/eWNEK', '1970-01-01', '1', NULL, 'aeKnJPufBaLnqkCVO4Fde8kj8mPH1y0uP1DyyR2BJdZv0ChMwaPtyq4bzlS9', '2020-05-24 11:27:35', '2020-05-24 11:27:35', 1, NULL),
(110, 1, 5, 1, 'staff', 'office', '07412345', 0, NULL, NULL, NULL, NULL, 'staff', '$2y$10$.O0rcuM4UTjgT0uLi6bsf.AhUWG6b9ECLUSNttSXwJUMDsj.IbyIK', '2020-05-13', '1', NULL, NULL, '2020-05-24 11:28:08', '2020-05-24 11:28:08', 1, NULL),
(111, 1, 6, 1, 'First', 'Agent', '45878787V', 0, '0717275536', NULL, 'No;32/1 buddaloka', 'agent@gmail.cojm', 'agent', '$2y$10$Z5VP6El5Od8SyE1bpV.o7emfI/jdtOpJ6OQHSUXzZwKAeltV8hIrW', '2020-01-01', NULL, NULL, NULL, '2020-05-24 11:34:07', '2020-05-24 11:37:11', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `iduser_role` int(11) NOT NULL,
  `role` varchar(25) DEFAULT NULL,
  `allow_to_manage_by` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`iduser_role`, `role`, `allow_to_manage_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MASTER ADMIN', 1, 0, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(2, 'SUPER ADMIN', 1, 0, '2019-11-07 18:30:00', '2019-11-21 19:13:15'),
(3, 'OFFICE ADMIN', 2, 1, '2019-11-07 18:30:00', '2019-11-21 19:13:15'),
(4, 'MANAGEMENT', 3, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(5, 'OFFICE STAFF', 3, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(6, 'AGENT', 6, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(7, 'MEMBER', 7, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_title`
--

CREATE TABLE `user_title` (
  `iduser_title` int(11) NOT NULL,
  `name_en` varchar(10) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_title`
--

INSERT INTO `user_title` (`iduser_title`, `name_en`, `name_si`, `name_ta`, `gender`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Mr.', 'මයා', NULL, 0, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(2, 'Ms.', 'මෙනෙවිය', NULL, 1, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00'),
(3, 'Rev.', 'පූජ්‍ය', 'ரெவ்', NULL, 1, '2020-01-31 03:48:20', '2019-11-19 18:30:00'),
(4, 'Mrs.', 'මිය', NULL, 1, 1, '2019-09-10 04:37:38', '2019-11-05 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `village`
--

CREATE TABLE `village` (
  `idvillage` int(11) NOT NULL,
  `iddistrict` int(11) NOT NULL,
  `idelection_division` int(11) NOT NULL,
  `idpolling_booth` int(11) NOT NULL,
  `idgramasewa_division` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `name_en` varchar(55) DEFAULT NULL,
  `name_si` varchar(45) DEFAULT NULL,
  `name_ta` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `village`
--

INSERT INTO `village` (`idvillage`, `iddistrict`, `idelection_division`, `idpolling_booth`, `idgramasewa_division`, `idUser`, `name_en`, `name_si`, `name_ta`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 1, 1, 77, 'Suwarapola-west', 'සුවාරපොල-බටහිර', 'பனதுரா - பனதுரா', 1, '2020-05-21 23:20:54', '2020-05-21 23:20:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_privilage`
--
ALTER TABLE `access_privilage`
  ADD PRIMARY KEY (`idaccess_privilage`),
  ADD KEY `fk_access_privilage_privilage_list1_idx` (`privilage_list_idprivilage_list`),
  ADD KEY `fk_access_privilage_user_role1_idx` (`iduser_role`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`idagent`),
  ADD KEY `fk_agent_usermaster1_idx` (`idUser`),
  ADD KEY `fk_agent_district1_idx` (`iddistrict`),
  ADD KEY `fk_agent_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_agent_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_agent_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_agent_village1_idx` (`idvillage`),
  ADD KEY `fk_agent_career1_idx` (`idcareer`),
  ADD KEY `fk_agent_ethnicity1_idx` (`idethnicity`),
  ADD KEY `fk_agent_religion1_idx` (`idreligion`),
  ADD KEY `fk_agent_educational_qualification1_idx` (`ideducational_qualification`),
  ADD KEY `fk_agent_nature_of_income1_idx` (`idnature_of_income`);

--
-- Indexes for table `agent_event`
--
ALTER TABLE `agent_event`
  ADD PRIMARY KEY (`idagent_event`),
  ADD KEY `fk_agent_event_event1_idx` (`event_idevent`),
  ADD KEY `fk_agent_event_usermaster1_idx` (`usermaster_idUser`);

--
-- Indexes for table `agent_event_member`
--
ALTER TABLE `agent_event_member`
  ADD PRIMARY KEY (`idagent_event_member`),
  ADD KEY `fk_agent_event_member_usermaster1_idx` (`usermaster_idUser`),
  ADD KEY `fk_agent_event_member_agent_event1_idx` (`agent_event_idagent_event`);

--
-- Indexes for table `analysis`
--
ALTER TABLE `analysis`
  ADD PRIMARY KEY (`idpost_category`),
  ADD KEY `fk_post_category_main_category1_idx` (`idmain_category`),
  ADD KEY `fk_post_category_sub_category1_idx` (`idsub_category`),
  ADD KEY `fk_post_category_category1_idx` (`idcategory`),
  ADD KEY `fk_post_category_usermaster1_idx` (`commenter`),
  ADD KEY `fk_post_category_district1_idx` (`iddistrict`),
  ADD KEY `fk_post_category_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_post_category_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_post_category_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_post_category_village1_idx` (`idvillage`),
  ADD KEY `fk_analysis_usermaster1_idx` (`idUser`);

--
-- Indexes for table `benefical_district`
--
ALTER TABLE `benefical_district`
  ADD PRIMARY KEY (`idbenefical_district`),
  ADD KEY `fk_benefical_district_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_district_district1_idx` (`iddistrict`);

--
-- Indexes for table `benefical_election_division`
--
ALTER TABLE `benefical_election_division`
  ADD PRIMARY KEY (`idbenefical_election_division`),
  ADD KEY `fk_benefical_election_division_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_election_division_election_division1_idx` (`idelection_division`);

--
-- Indexes for table `benefical_gramasewa_division`
--
ALTER TABLE `benefical_gramasewa_division`
  ADD PRIMARY KEY (`idbenefical_gramasewa_division`),
  ADD KEY `fk_benefical_gramasewa_division_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_gramasewa_division_gramasewa_division1_idx` (`idgramasewa_division`);

--
-- Indexes for table `benefical_polling_booth`
--
ALTER TABLE `benefical_polling_booth`
  ADD PRIMARY KEY (`idbenefical_polling_booth`),
  ADD KEY `fk_benefical_polling_booth_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_polling_booth_polling_booth1_idx` (`idpolling_booth`);

--
-- Indexes for table `benefical_village`
--
ALTER TABLE `benefical_village`
  ADD PRIMARY KEY (`idbenefical_village`),
  ADD KEY `fk_benefical_village_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_village_village1_idx` (`idvillage`);

--
-- Indexes for table `beneficial_cat`
--
ALTER TABLE `beneficial_cat`
  ADD PRIMARY KEY (`idbeneficial_cat`),
  ADD KEY `fk_benefical_cat_post1_idx` (`idPost`),
  ADD KEY `fk_benefical_cat_category1_idx` (`idcategory`);

--
-- Indexes for table `career`
--
ALTER TABLE `career`
  ADD PRIMARY KEY (`idcareer`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`idcategory`);

--
-- Indexes for table `direct_message`
--
ALTER TABLE `direct_message`
  ADD PRIMARY KEY (`iddirect_message`),
  ADD KEY `fk_direct_message_usermaster1_idx` (`from_idUser`),
  ADD KEY `fk_direct_message_usermaster2_idx` (`to_idUser`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`iddistrict`),
  ADD KEY `fk_district_usermaster1_idx` (`idUser`);

--
-- Indexes for table `educational_qualification`
--
ALTER TABLE `educational_qualification`
  ADD PRIMARY KEY (`ideducational_qualification`);

--
-- Indexes for table `election_division`
--
ALTER TABLE `election_division`
  ADD PRIMARY KEY (`idelection_division`),
  ADD KEY `fk_election_division_district1_idx` (`iddistrict`),
  ADD KEY `fk_election_division_usermaster1_idx` (`idUser`);

--
-- Indexes for table `ethnicity`
--
ALTER TABLE `ethnicity`
  ADD PRIMARY KEY (`idethnicity`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`),
  ADD KEY `fk_event_usermaster1_idx` (`idUser`),
  ADD KEY `fk_event_district1_idx` (`iddistrict`),
  ADD KEY `fk_event_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_event_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_event_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_event_village1_idx` (`idvillage`);

--
-- Indexes for table `gramasewa_division`
--
ALTER TABLE `gramasewa_division`
  ADD PRIMARY KEY (`idgramasewa_division`),
  ADD KEY `fk_gramasewa_division_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_gramasewa_division_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_gramasewa_division_district1_idx` (`iddistrict`),
  ADD KEY `fk_gramasewa_division_usermaster1_idx` (`idUser`);

--
-- Indexes for table `main_category`
--
ALTER TABLE `main_category`
  ADD PRIMARY KEY (`idmain_category`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`idmember`),
  ADD KEY `fk_member_ethnicity1_idx` (`idethnicity`),
  ADD KEY `fk_member_religion1_idx` (`idreligion`),
  ADD KEY `fk_member_educational_qualification1_idx` (`ideducational_qualification`),
  ADD KEY `fk_member_nature_of_income1_idx` (`idnature_of_income`),
  ADD KEY `fk_member_career1_idx` (`idcareer`),
  ADD KEY `fk_member_usermaster2_idx` (`idUser`),
  ADD KEY `fk_member_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_member_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_member_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_member_village1_idx` (`idvillage`),
  ADD KEY `fk_member_district1_idx` (`iddistrict`);

--
-- Indexes for table `member_agent`
--
ALTER TABLE `member_agent`
  ADD PRIMARY KEY (`idmember_agent`),
  ADD KEY `fk_member_agent_member1_idx` (`idmember`),
  ADD KEY `fk_member_agent_agent1_idx` (`idagent`);

--
-- Indexes for table `nature_of_income`
--
ALTER TABLE `nature_of_income`
  ADD PRIMARY KEY (`idnature_of_income`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oauth_access_tokens_usermaster1_idx` (`user_id`),
  ADD KEY `fk_oauth_access_tokens_oauth_clients1_idx` (`client_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD KEY `fk_oauth_auth_codes_usermaster1_idx` (`user_id`),
  ADD KEY `fk_oauth_auth_codes_oauth_clients1_idx` (`client_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oauth_clients_usermaster1_idx` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oauth_personal_access_clients_oauth_clients1_idx` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`idoffice`),
  ADD KEY `fk_company_district1_idx` (`iddistrict`);

--
-- Indexes for table `office_admin`
--
ALTER TABLE `office_admin`
  ADD PRIMARY KEY (`idoffice_admin`),
  ADD KEY `fk_office_admin_usermaster1_idx` (`idUser`);

--
-- Indexes for table `office_staff`
--
ALTER TABLE `office_staff`
  ADD PRIMARY KEY (`idoffice_staff`),
  ADD KEY `fk_office_staff_usermaster1_idx` (`idUser`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`idpayment`),
  ADD KEY `fk_payment_office1_idx` (`idoffice`);

--
-- Indexes for table `polling_booth`
--
ALTER TABLE `polling_booth`
  ADD PRIMARY KEY (`idpolling_booth`),
  ADD KEY `fk_polling_booth_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_polling_booth_district1_idx` (`iddistrict`),
  ADD KEY `fk_polling_booth_usermaster1_idx` (`idUser`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`idPost`),
  ADD KEY `fk_postmaster_usermaster1_idx` (`idUser`),
  ADD KEY `fk_post_office1_idx` (`idoffice`);

--
-- Indexes for table `post_attachment`
--
ALTER TABLE `post_attachment`
  ADD PRIMARY KEY (`idpost_attachment`),
  ADD KEY `fk_post_attachment_postmaster1_idx` (`idPost`);

--
-- Indexes for table `post_career`
--
ALTER TABLE `post_career`
  ADD PRIMARY KEY (`idpost_career`),
  ADD KEY `fk_post_career_post1_idx` (`idPost`),
  ADD KEY `fk_post_career_career1_idx` (`idcareer`);

--
-- Indexes for table `post_district`
--
ALTER TABLE `post_district`
  ADD PRIMARY KEY (`idpost_district`),
  ADD KEY `fk_post_district_post1_idx` (`idPost`),
  ADD KEY `fk_post_district_district1_idx` (`iddistrict`);

--
-- Indexes for table `post_education`
--
ALTER TABLE `post_education`
  ADD PRIMARY KEY (`idpost_education`),
  ADD KEY `fk_post_education_post1_idx` (`idPost`),
  ADD KEY `fk_post_education_educational_qualification1_idx` (`ideducational_qualification`);

--
-- Indexes for table `post_election_division`
--
ALTER TABLE `post_election_division`
  ADD PRIMARY KEY (`idpost_election_division`),
  ADD KEY `fk_post_election_division_post1_idx` (`idPost`),
  ADD KEY `fk_post_election_division_election_division1_idx` (`idelection_division`);

--
-- Indexes for table `post_ethnicity`
--
ALTER TABLE `post_ethnicity`
  ADD PRIMARY KEY (`idpost_ethnicity`),
  ADD KEY `fk_post_ethicity_post1_idx` (`idPost`),
  ADD KEY `fk_post_ethicity_ethnicity1_idx` (`idethnicity`);

--
-- Indexes for table `post_gramasewa_division`
--
ALTER TABLE `post_gramasewa_division`
  ADD PRIMARY KEY (`idpost_gramasewa_division`),
  ADD KEY `fk_post_gramasewa_division_post1_idx` (`idPost`),
  ADD KEY `fk_post_gramasewa_division_gramasewa_division1_idx` (`idgramasewa_division`);

--
-- Indexes for table `post_income`
--
ALTER TABLE `post_income`
  ADD PRIMARY KEY (`idpost_income`),
  ADD KEY `fk_post_income_post1_idx` (`idPost`),
  ADD KEY `fk_post_income_nature_of_income1_idx` (`idnature_of_income`);

--
-- Indexes for table `post_polling_booth`
--
ALTER TABLE `post_polling_booth`
  ADD PRIMARY KEY (`idpost_polling_booth`),
  ADD KEY `fk_post_polling_booth_post1_idx` (`idPost`),
  ADD KEY `fk_post_polling_booth_polling_booth1_idx` (`idpolling_booth`);

--
-- Indexes for table `post_religion`
--
ALTER TABLE `post_religion`
  ADD PRIMARY KEY (`idpost_religion`),
  ADD KEY `fk_post_commiunity_post1_idx` (`idPost`),
  ADD KEY `fk_post_religion_religion1_idx` (`idreligion`);

--
-- Indexes for table `post_response`
--
ALTER TABLE `post_response`
  ADD PRIMARY KEY (`idpost_response`),
  ADD KEY `fk_post_response_post1_idx` (`idPost`),
  ADD KEY `fk_post_response_usermaster1_idx` (`idUser`);

--
-- Indexes for table `post_view`
--
ALTER TABLE `post_view`
  ADD PRIMARY KEY (`idpost_view`),
  ADD KEY `fk_post_view_post1_idx` (`idPost`),
  ADD KEY `fk_post_view_usermaster1_idx` (`idUser`);

--
-- Indexes for table `post_village`
--
ALTER TABLE `post_village`
  ADD PRIMARY KEY (`idpost_village`),
  ADD KEY `fk_post_village_post1_idx` (`idPost`),
  ADD KEY `fk_post_village_village1_idx` (`idvillage`);

--
-- Indexes for table `privilage_list`
--
ALTER TABLE `privilage_list`
  ADD PRIMARY KEY (`idprivilage_list`);

--
-- Indexes for table `religion`
--
ALTER TABLE `religion`
  ADD PRIMARY KEY (`idreligion`);

--
-- Indexes for table `staff_gramasewa_devision`
--
ALTER TABLE `staff_gramasewa_devision`
  ADD PRIMARY KEY (`idstaff_gramasewa_devision`),
  ADD KEY `fk_staff_gramasewa_devision_office_staff1_idx` (`idoffice_staff`),
  ADD KEY `fk_staff_gramasewa_devision_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_staff_gramasewa_devision_office1_idx` (`idoffice`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`idsub_category`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`idtask`),
  ADD KEY `fk_task_usermaster1_idx` (`idUser`),
  ADD KEY `fk_task_usermaster2_idx` (`assigned_by`);

--
-- Indexes for table `task_age`
--
ALTER TABLE `task_age`
  ADD PRIMARY KEY (`idtask_age`),
  ADD KEY `fk_task_age_task1_idx` (`idtask`);

--
-- Indexes for table `task_career`
--
ALTER TABLE `task_career`
  ADD PRIMARY KEY (`idtask_career`),
  ADD KEY `fk_task_career_task1_idx` (`idtask`),
  ADD KEY `fk_task_career_career1_idx` (`idcareer`);

--
-- Indexes for table `task_education`
--
ALTER TABLE `task_education`
  ADD PRIMARY KEY (`idtask_education`),
  ADD KEY `fk_task_education_task1_idx` (`idtask`),
  ADD KEY `fk_task_education_educational_qualification1_idx` (`ideducational_qualification`);

--
-- Indexes for table `task_ethnicity`
--
ALTER TABLE `task_ethnicity`
  ADD PRIMARY KEY (`idtask_ethnicity`),
  ADD KEY `fk_task_ethnicity_task1_idx` (`idtask`),
  ADD KEY `fk_task_ethnicity_ethnicity1_idx` (`idethnicity`);

--
-- Indexes for table `task_income`
--
ALTER TABLE `task_income`
  ADD PRIMARY KEY (`idtask_income`),
  ADD KEY `fk_task_income_task1_idx` (`idtask`),
  ADD KEY `fk_task_income_nature_of_income1_idx` (`idnature_of_income`);

--
-- Indexes for table `task_religion`
--
ALTER TABLE `task_religion`
  ADD PRIMARY KEY (`idtask_religion`),
  ADD KEY `fk_task_religion_task1_idx` (`idtask`),
  ADD KEY `fk_task_religion_religion1_idx` (`idreligion`);

--
-- Indexes for table `usermaster`
--
ALTER TABLE `usermaster`
  ADD PRIMARY KEY (`idUser`),
  ADD KEY `fk_usermaster_user_role1_idx` (`iduser_role`),
  ADD KEY `fk_usermaster_user_title1_idx` (`iduser_title`),
  ADD KEY `fk_usermaster_company1_idx` (`idoffice`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`iduser_role`),
  ADD KEY `fk_user_role_user_role1_idx` (`allow_to_manage_by`);

--
-- Indexes for table `user_title`
--
ALTER TABLE `user_title`
  ADD PRIMARY KEY (`iduser_title`);

--
-- Indexes for table `village`
--
ALTER TABLE `village`
  ADD PRIMARY KEY (`idvillage`),
  ADD KEY `fk_village_gramasewa_division1_idx` (`idgramasewa_division`),
  ADD KEY `fk_village_district1_idx` (`iddistrict`),
  ADD KEY `fk_village_election_division1_idx` (`idelection_division`),
  ADD KEY `fk_village_polling_booth1_idx` (`idpolling_booth`),
  ADD KEY `fk_village_usermaster1_idx` (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_privilage`
--
ALTER TABLE `access_privilage`
  MODIFY `idaccess_privilage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `idagent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_event`
--
ALTER TABLE `agent_event`
  MODIFY `idagent_event` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_event_member`
--
ALTER TABLE `agent_event_member`
  MODIFY `idagent_event_member` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `analysis`
--
ALTER TABLE `analysis`
  MODIFY `idpost_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `benefical_district`
--
ALTER TABLE `benefical_district`
  MODIFY `idbenefical_district` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `benefical_election_division`
--
ALTER TABLE `benefical_election_division`
  MODIFY `idbenefical_election_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `benefical_gramasewa_division`
--
ALTER TABLE `benefical_gramasewa_division`
  MODIFY `idbenefical_gramasewa_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `benefical_polling_booth`
--
ALTER TABLE `benefical_polling_booth`
  MODIFY `idbenefical_polling_booth` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `benefical_village`
--
ALTER TABLE `benefical_village`
  MODIFY `idbenefical_village` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beneficial_cat`
--
ALTER TABLE `beneficial_cat`
  MODIFY `idbeneficial_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `career`
--
ALTER TABLE `career`
  MODIFY `idcareer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `idcategory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `direct_message`
--
ALTER TABLE `direct_message`
  MODIFY `iddirect_message` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `iddistrict` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `educational_qualification`
--
ALTER TABLE `educational_qualification`
  MODIFY `ideducational_qualification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `election_division`
--
ALTER TABLE `election_division`
  MODIFY `idelection_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ethnicity`
--
ALTER TABLE `ethnicity`
  MODIFY `idethnicity` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gramasewa_division`
--
ALTER TABLE `gramasewa_division`
  MODIFY `idgramasewa_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `main_category`
--
ALTER TABLE `main_category`
  MODIFY `idmain_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `idmember` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_agent`
--
ALTER TABLE `member_agent`
  MODIFY `idmember_agent` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nature_of_income`
--
ALTER TABLE `nature_of_income`
  MODIFY `idnature_of_income` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `idoffice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `office_admin`
--
ALTER TABLE `office_admin`
  MODIFY `idoffice_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `office_staff`
--
ALTER TABLE `office_staff`
  MODIFY `idoffice_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `idpayment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `polling_booth`
--
ALTER TABLE `polling_booth`
  MODIFY `idpolling_booth` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_attachment`
--
ALTER TABLE `post_attachment`
  MODIFY `idpost_attachment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `post_career`
--
ALTER TABLE `post_career`
  MODIFY `idpost_career` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_district`
--
ALTER TABLE `post_district`
  MODIFY `idpost_district` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_education`
--
ALTER TABLE `post_education`
  MODIFY `idpost_education` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_election_division`
--
ALTER TABLE `post_election_division`
  MODIFY `idpost_election_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_ethnicity`
--
ALTER TABLE `post_ethnicity`
  MODIFY `idpost_ethnicity` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_gramasewa_division`
--
ALTER TABLE `post_gramasewa_division`
  MODIFY `idpost_gramasewa_division` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_income`
--
ALTER TABLE `post_income`
  MODIFY `idpost_income` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_polling_booth`
--
ALTER TABLE `post_polling_booth`
  MODIFY `idpost_polling_booth` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_religion`
--
ALTER TABLE `post_religion`
  MODIFY `idpost_religion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_response`
--
ALTER TABLE `post_response`
  MODIFY `idpost_response` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_view`
--
ALTER TABLE `post_view`
  MODIFY `idpost_view` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_village`
--
ALTER TABLE `post_village`
  MODIFY `idpost_village` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `privilage_list`
--
ALTER TABLE `privilage_list`
  MODIFY `idprivilage_list` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `religion`
--
ALTER TABLE `religion`
  MODIFY `idreligion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff_gramasewa_devision`
--
ALTER TABLE `staff_gramasewa_devision`
  MODIFY `idstaff_gramasewa_devision` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `idsub_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `idtask` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_age`
--
ALTER TABLE `task_age`
  MODIFY `idtask_age` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_career`
--
ALTER TABLE `task_career`
  MODIFY `idtask_career` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_education`
--
ALTER TABLE `task_education`
  MODIFY `idtask_education` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_ethnicity`
--
ALTER TABLE `task_ethnicity`
  MODIFY `idtask_ethnicity` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_income`
--
ALTER TABLE `task_income`
  MODIFY `idtask_income` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_religion`
--
ALTER TABLE `task_religion`
  MODIFY `idtask_religion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usermaster`
--
ALTER TABLE `usermaster`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `iduser_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_title`
--
ALTER TABLE `user_title`
  MODIFY `iduser_title` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `village`
--
ALTER TABLE `village`
  MODIFY `idvillage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_privilage`
--
ALTER TABLE `access_privilage`
  ADD CONSTRAINT `fk_access_privilage_privilage_list1` FOREIGN KEY (`privilage_list_idprivilage_list`) REFERENCES `privilage_list` (`idprivilage_list`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_access_privilage_user_role1` FOREIGN KEY (`iduser_role`) REFERENCES `user_role` (`iduser_role`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `fk_agent_career1` FOREIGN KEY (`idcareer`) REFERENCES `career` (`idcareer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_educational_qualification1` FOREIGN KEY (`ideducational_qualification`) REFERENCES `educational_qualification` (`ideducational_qualification`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_ethnicity1` FOREIGN KEY (`idethnicity`) REFERENCES `ethnicity` (`idethnicity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_nature_of_income1` FOREIGN KEY (`idnature_of_income`) REFERENCES `nature_of_income` (`idnature_of_income`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_religion1` FOREIGN KEY (`idreligion`) REFERENCES `religion` (`idreligion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `agent_event`
--
ALTER TABLE `agent_event`
  ADD CONSTRAINT `fk_agent_event_event1` FOREIGN KEY (`event_idevent`) REFERENCES `event` (`idevent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_event_usermaster1` FOREIGN KEY (`usermaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `agent_event_member`
--
ALTER TABLE `agent_event_member`
  ADD CONSTRAINT `fk_agent_event_member_agent_event1` FOREIGN KEY (`agent_event_idagent_event`) REFERENCES `agent_event` (`idagent_event`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_agent_event_member_usermaster1` FOREIGN KEY (`usermaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `analysis`
--
ALTER TABLE `analysis`
  ADD CONSTRAINT `fk_analysis_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_category1` FOREIGN KEY (`idcategory`) REFERENCES `category` (`idcategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_main_category1` FOREIGN KEY (`idmain_category`) REFERENCES `main_category` (`idmain_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_sub_category1` FOREIGN KEY (`idsub_category`) REFERENCES `sub_category` (`idsub_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_usermaster1` FOREIGN KEY (`commenter`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_category_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `benefical_district`
--
ALTER TABLE `benefical_district`
  ADD CONSTRAINT `fk_benefical_district_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_district_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `benefical_election_division`
--
ALTER TABLE `benefical_election_division`
  ADD CONSTRAINT `fk_benefical_election_division_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_election_division_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `benefical_gramasewa_division`
--
ALTER TABLE `benefical_gramasewa_division`
  ADD CONSTRAINT `fk_benefical_gramasewa_division_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_gramasewa_division_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `benefical_polling_booth`
--
ALTER TABLE `benefical_polling_booth`
  ADD CONSTRAINT `fk_benefical_polling_booth_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_polling_booth_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `benefical_village`
--
ALTER TABLE `benefical_village`
  ADD CONSTRAINT `fk_benefical_village_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_village_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `beneficial_cat`
--
ALTER TABLE `beneficial_cat`
  ADD CONSTRAINT `fk_benefical_cat_category1` FOREIGN KEY (`idcategory`) REFERENCES `category` (`idcategory`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_benefical_cat_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `direct_message`
--
ALTER TABLE `direct_message`
  ADD CONSTRAINT `fk_direct_message_usermaster1` FOREIGN KEY (`from_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_direct_message_usermaster2` FOREIGN KEY (`to_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
  ADD CONSTRAINT `fk_district_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `election_division`
--
ALTER TABLE `election_division`
  ADD CONSTRAINT `fk_election_division_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_election_division_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_event_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `gramasewa_division`
--
ALTER TABLE `gramasewa_division`
  ADD CONSTRAINT `fk_gramasewa_division_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gramasewa_division_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gramasewa_division_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_gramasewa_division_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `fk_member_career1` FOREIGN KEY (`idcareer`) REFERENCES `career` (`idcareer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_educational_qualification1` FOREIGN KEY (`ideducational_qualification`) REFERENCES `educational_qualification` (`ideducational_qualification`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_ethnicity1` FOREIGN KEY (`idethnicity`) REFERENCES `ethnicity` (`idethnicity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_nature_of_income1` FOREIGN KEY (`idnature_of_income`) REFERENCES `nature_of_income` (`idnature_of_income`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_religion1` FOREIGN KEY (`idreligion`) REFERENCES `religion` (`idreligion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_usermaster2` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `member_agent`
--
ALTER TABLE `member_agent`
  ADD CONSTRAINT `fk_member_agent_agent1` FOREIGN KEY (`idagent`) REFERENCES `agent` (`idagent`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_member_agent_member1` FOREIGN KEY (`idmember`) REFERENCES `member` (`idmember`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD CONSTRAINT `fk_oauth_access_tokens_oauth_clients1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_oauth_access_tokens_usermaster1` FOREIGN KEY (`user_id`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD CONSTRAINT `fk_oauth_auth_codes_oauth_clients1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_oauth_auth_codes_usermaster1` FOREIGN KEY (`user_id`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD CONSTRAINT `fk_oauth_clients_usermaster1` FOREIGN KEY (`user_id`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD CONSTRAINT `fk_oauth_personal_access_clients_oauth_clients1` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `office`
--
ALTER TABLE `office`
  ADD CONSTRAINT `fk_company_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `office_admin`
--
ALTER TABLE `office_admin`
  ADD CONSTRAINT `fk_office_admin_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `office_staff`
--
ALTER TABLE `office_staff`
  ADD CONSTRAINT `fk_office_staff_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_office1` FOREIGN KEY (`idoffice`) REFERENCES `office` (`idoffice`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `polling_booth`
--
ALTER TABLE `polling_booth`
  ADD CONSTRAINT `fk_polling_booth_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_polling_booth_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_polling_booth_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_office1` FOREIGN KEY (`idoffice`) REFERENCES `office` (`idoffice`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_postmaster_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_attachment`
--
ALTER TABLE `post_attachment`
  ADD CONSTRAINT `fk_post_attachment_postmaster1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_career`
--
ALTER TABLE `post_career`
  ADD CONSTRAINT `fk_post_career_career1` FOREIGN KEY (`idcareer`) REFERENCES `career` (`idcareer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_career_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_district`
--
ALTER TABLE `post_district`
  ADD CONSTRAINT `fk_post_district_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_district_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_education`
--
ALTER TABLE `post_education`
  ADD CONSTRAINT `fk_post_education_educational_qualification1` FOREIGN KEY (`ideducational_qualification`) REFERENCES `educational_qualification` (`ideducational_qualification`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_education_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_election_division`
--
ALTER TABLE `post_election_division`
  ADD CONSTRAINT `fk_post_election_division_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_election_division_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_ethnicity`
--
ALTER TABLE `post_ethnicity`
  ADD CONSTRAINT `fk_post_ethicity_ethnicity1` FOREIGN KEY (`idethnicity`) REFERENCES `ethnicity` (`idethnicity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_ethicity_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_gramasewa_division`
--
ALTER TABLE `post_gramasewa_division`
  ADD CONSTRAINT `fk_post_gramasewa_division_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_gramasewa_division_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_income`
--
ALTER TABLE `post_income`
  ADD CONSTRAINT `fk_post_income_nature_of_income1` FOREIGN KEY (`idnature_of_income`) REFERENCES `nature_of_income` (`idnature_of_income`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_income_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_polling_booth`
--
ALTER TABLE `post_polling_booth`
  ADD CONSTRAINT `fk_post_polling_booth_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_polling_booth_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_religion`
--
ALTER TABLE `post_religion`
  ADD CONSTRAINT `fk_post_commiunity_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_religion_religion1` FOREIGN KEY (`idreligion`) REFERENCES `religion` (`idreligion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_response`
--
ALTER TABLE `post_response`
  ADD CONSTRAINT `fk_post_response_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_response_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_view`
--
ALTER TABLE `post_view`
  ADD CONSTRAINT `fk_post_view_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_view_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `post_village`
--
ALTER TABLE `post_village`
  ADD CONSTRAINT `fk_post_village_post1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_village_village1` FOREIGN KEY (`idvillage`) REFERENCES `village` (`idvillage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `staff_gramasewa_devision`
--
ALTER TABLE `staff_gramasewa_devision`
  ADD CONSTRAINT `fk_staff_gramasewa_devision_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_staff_gramasewa_devision_office1` FOREIGN KEY (`idoffice`) REFERENCES `office` (`idoffice`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_staff_gramasewa_devision_office_staff1` FOREIGN KEY (`idoffice_staff`) REFERENCES `office_staff` (`idoffice_staff`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_usermaster2` FOREIGN KEY (`assigned_by`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_age`
--
ALTER TABLE `task_age`
  ADD CONSTRAINT `fk_task_age_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_career`
--
ALTER TABLE `task_career`
  ADD CONSTRAINT `fk_task_career_career1` FOREIGN KEY (`idcareer`) REFERENCES `career` (`idcareer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_career_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_education`
--
ALTER TABLE `task_education`
  ADD CONSTRAINT `fk_task_education_educational_qualification1` FOREIGN KEY (`ideducational_qualification`) REFERENCES `educational_qualification` (`ideducational_qualification`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_education_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_ethnicity`
--
ALTER TABLE `task_ethnicity`
  ADD CONSTRAINT `fk_task_ethnicity_ethnicity1` FOREIGN KEY (`idethnicity`) REFERENCES `ethnicity` (`idethnicity`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_ethnicity_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_income`
--
ALTER TABLE `task_income`
  ADD CONSTRAINT `fk_task_income_nature_of_income1` FOREIGN KEY (`idnature_of_income`) REFERENCES `nature_of_income` (`idnature_of_income`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_income_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `task_religion`
--
ALTER TABLE `task_religion`
  ADD CONSTRAINT `fk_task_religion_religion1` FOREIGN KEY (`idreligion`) REFERENCES `religion` (`idreligion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_task_religion_task1` FOREIGN KEY (`idtask`) REFERENCES `task` (`idtask`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usermaster`
--
ALTER TABLE `usermaster`
  ADD CONSTRAINT `fk_usermaster_company1` FOREIGN KEY (`idoffice`) REFERENCES `office` (`idoffice`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usermaster_user_role1` FOREIGN KEY (`iduser_role`) REFERENCES `user_role` (`iduser_role`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usermaster_user_title1` FOREIGN KEY (`iduser_title`) REFERENCES `user_title` (`iduser_title`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_user_role1` FOREIGN KEY (`allow_to_manage_by`) REFERENCES `user_role` (`iduser_role`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `village`
--
ALTER TABLE `village`
  ADD CONSTRAINT `fk_village_district1` FOREIGN KEY (`iddistrict`) REFERENCES `district` (`iddistrict`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_village_election_division1` FOREIGN KEY (`idelection_division`) REFERENCES `election_division` (`idelection_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_village_gramasewa_division1` FOREIGN KEY (`idgramasewa_division`) REFERENCES `gramasewa_division` (`idgramasewa_division`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_village_polling_booth1` FOREIGN KEY (`idpolling_booth`) REFERENCES `polling_booth` (`idpolling_booth`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_village_usermaster1` FOREIGN KEY (`idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
