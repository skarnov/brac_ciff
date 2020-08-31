-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 31, 2020 at 12:45 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_bracciff`
--

-- --------------------------------------------------------

--
-- Table structure for table `dev_acitivites_categories`
--

CREATE TABLE `dev_acitivites_categories` (
  `pk_activity_cat_id` int(10) UNSIGNED NOT NULL,
  `fk_parent_id` int(11) DEFAULT 0,
  `cat_name` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_acitivites_categories`
--

INSERT INTO `dev_acitivites_categories` (`pk_activity_cat_id`, `fk_parent_id`, `cat_name`) VALUES
(1, 0, 'Community members are sensitized about irregular migration and support to socio-economic reintegration of returnees'),
(2, 1, 'IPT Refreshers wortkshop (National Level)- HO'),
(3, 1, 'IPT Refreshers wortkshop  (Upazilla level)'),
(4, 1, 'Interactive Popular Theatre (IPT)'),
(5, 1, 'Community meeting with migrant’s family members'),
(6, 1, 'Information dissemination through Miking (300 people/Campaign)'),
(7, 1, 'IPC (campaigning for Information dissemination) by Volunteer campaigning'),
(8, 1, 'Volunteer training for campaign delivery'),
(9, 1, 'Courtyard meeting'),
(10, 1, 'Information dissemination by DRSC Staff'),
(11, 1, 'School/College campaign on Socio-economic reintegration of  returnees (100 participant)'),
(12, 1, 'Community people come forward to support returnee migrants for their reintegration'),
(13, 1, 'ICE Materials development workshop - HO'),
(14, 0, ' Men and Women returnee migrants are trained'),
(15, 14, 'Returnee identification'),
(16, 14, 'Profiling of Returnee migrants'),
(17, 14, 'Skill development for wage based economic reintegration'),
(18, 14, 'Skill development for  Enterprise based economic reintegration'),
(19, 14, 'Basic Entrepreneurship training'),
(20, 14, 'Appropriate Product and service development training'),
(21, 14, 'Marketing and sales management training'),
(22, 14, 'Financial management of Enterprises'),
(23, 14, 'Trade specific Training'),
(24, 14, 'Assistance in kinds  to the most vulnerable women returnees for economic self reliance'),
(25, 14, 'Consultant for SME Project profile writing for project financing from Financial Institutions (MFI/Banks)'),
(26, 14, 'Returnee migrants receive financial support/loans from financial institutions'),
(27, 14, 'Recognition of Prior Learning (RPL)'),
(28, 14, 'Referral linkage for Financial support to Returnee Migrants (Successful)'),
(29, 14, 'Referral linkage for Financial support to Returnee Migrants (Successful)'),
(30, 14, 'Other Referral linkage (Rescue, schlorship, Training etc.)-Successful'),
(31, 14, 'Online database development for returnee migrants'),
(32, 0, 'Returnee women migrants receive counselling for reintegration'),
(33, 32, 'On Arrival Emergency Support  (Airport) to Vulnerable Returnees in general and  women in particular'),
(34, 32, 'MOU for long term referral psycho-social counselling of women returnees with  BRAC programs/projects and other organizations'),
(35, 32, 'Psycho-social Counseling support for returnees and/or their family members by counselor'),
(36, 32, 'Psycho-social Counseling support for returnee and their family members by volunteers'),
(37, 32, 'Referral for women returnee Trauma counseling'),
(38, 0, 'Civil Society Organizations (CSOs) advocate to the Government for the welfare of returnee migrants'),
(39, 38, 'Consultation on Returnee Needs Assessment  with Returnee migrants, their families'),
(40, 38, 'Consultations  with CSOs  on Unison development on  Comprehensive returnee reintegration support '),
(41, 38, ' Consultation at National Level'),
(42, 38, 'Involving Community level organizations for Returnee Reintegration Advocacy with duty bearers'),
(43, 38, 'Workshop with govt. duty bearerers and private sector on Service facilitation for Returnee reintegration(1 with govt and 1 with private sector) **Denmark target 60 duty'),
(44, 38, 'Workshop with media professiinals on Returnee reintegration reporting'),
(45, 38, 'Case study research training and capacity building for project people, volunteers and other organization members'),
(46, 38, 'Case study development on Returnee reintegration in general women in particular for women returnee policy focus'),
(47, 38, 'Review and recommend on the Returnee reintegration policy draft development and sharing ( consultations and Desk review of other organizations in Bangladesh and other sending nations) and sharing meeting '),
(48, 0, 'Duty bearers are aware of the support needed for reintegration of returnee migrant workers'),
(49, 48, '04 Capacity building workshops  with public/private sector agencies to support returnees for job placement /loan for enterprise development'),
(50, 48, 'MOU with private sector agencies for returnee reintegration support'),
(51, 0, 'Returnees migrants and/or their families receive financial literacy training'),
(52, 51, 'Innovate/revise Financial literacy curriculum for external shock prevention mechanism'),
(53, 51, 'Financial literacy  training   for Returnees and/or family members is held on remittance utilization (50% on financial literacy and 50% on Remittance utilization)'),
(54, 0, 'Other Activities'),
(55, 54, 'Monthly Staff Meetings at HO Level'),
(56, 54, 'Monthly Staff Meetings at Field Level'),
(57, 54, 'No. of selected Volunteer during the project period'),
(58, 54, 'No. of Dropped out Volunteers'),
(59, 54, 'Volunteer Meeting at Field level'),
(60, 54, 'Quarterly Staff Meeting organized by HO'),
(61, 54, 'Training /Workshops for Staff development by HO'),
(62, 54, 'Event/Day observation'),
(63, 54, 'Project orientation and planning workshop (HO) - 1 Event with 52 participant'),
(64, 1, 'Advertisement and Publicity'),
(65, 1, 'Project Monitoring and Evaluation - HO'),
(66, 0, 'Ensuring Community support for Special reintegration of returnee migrants'),
(67, 66, 'IPT development workshop (national level )'),
(68, 66, 'IPT development workshop (Upazilla Level )'),
(69, 66, 'Interactive Popular Thereat');

-- --------------------------------------------------------

--
-- Table structure for table `dev_activities`
--

CREATE TABLE `dev_activities` (
  `pk_activity_id` bigint(20) NOT NULL,
  `fk_project_id` int(11) NOT NULL,
  `fk_branch_id` int(11) NOT NULL,
  `fk_activity_cat_id` int(11) DEFAULT NULL,
  `entry_month` tinyint(4) DEFAULT NULL,
  `entry_year` int(11) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `activity_target` int(10) NOT NULL DEFAULT 0,
  `activity_achievement` int(10) NOT NULL DEFAULT 0,
  `activity_variance` int(10) NOT NULL DEFAULT 0,
  `male_participant` int(10) NOT NULL DEFAULT 0,
  `female_participant` int(10) NOT NULL DEFAULT 0,
  `boy_participant` int(10) NOT NULL DEFAULT 0,
  `girl_participant` int(10) NOT NULL DEFAULT 0,
  `total_participant` int(10) NOT NULL DEFAULT 0,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_activities`
--

INSERT INTO `dev_activities` (`pk_activity_id`, `fk_project_id`, `fk_branch_id`, `fk_activity_cat_id`, `entry_month`, `entry_year`, `entry_date`, `activity_target`, `activity_achievement`, `activity_variance`, `male_participant`, `female_participant`, `boy_participant`, `girl_participant`, `total_participant`, `create_date`, `create_time`, `create_by`, `update_date`, `update_time`, `update_by`) VALUES
(1, 2, 1, 2, 6, 2019, '2019-07-08', 0, 5, 5, 0, 0, 0, 0, 0, '2019-07-08', '15:13:30', 1, '2019-07-23', '12:39:17', 1),
(2, 2, 1, 3, 6, 2019, '2019-07-08', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-08', '15:16:26', 1, '2019-07-23', '12:39:17', 1),
(3, 2, 1, 4, 6, 2019, '2019-07-09', 0, 0, 0, 60, 0, 0, 0, 60, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(4, 2, 1, 5, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(5, 2, 1, 6, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(6, 2, 1, 7, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(7, 2, 1, 8, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(8, 2, 1, 9, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(9, 2, 1, 10, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(10, 2, 1, 11, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(11, 2, 1, 12, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(12, 2, 1, 13, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:20:09', 1, '2019-07-23', '12:39:17', 1),
(13, 2, 1, 15, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(14, 2, 1, 16, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(15, 2, 1, 17, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(16, 2, 1, 18, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(17, 2, 1, 19, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(18, 2, 1, 20, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(19, 2, 1, 21, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(20, 2, 1, 22, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(21, 2, 1, 23, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(22, 2, 1, 24, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(23, 2, 1, 25, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(24, 2, 1, 26, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(25, 2, 1, 27, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(26, 2, 1, 28, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(27, 2, 1, 29, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(28, 2, 1, 30, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(29, 2, 1, 31, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:36:40', 1, '2019-07-23', '12:39:17', 1),
(30, 2, 1, 33, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:43:09', 1, '2019-07-23', '12:39:17', 1),
(31, 2, 1, 34, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:43:09', 1, '2019-07-23', '12:39:17', 1),
(32, 2, 1, 35, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:43:09', 1, '2019-07-23', '12:39:17', 1),
(33, 2, 1, 36, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:43:09', 1, '2019-07-23', '12:39:17', 1),
(34, 2, 1, 37, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:43:09', 1, '2019-07-23', '12:39:17', 1),
(35, 2, 1, 39, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(36, 2, 1, 40, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(37, 2, 1, 41, 6, 2019, '2019-07-21', 20, 0, -20, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(38, 2, 1, 42, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(39, 2, 1, 43, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(40, 2, 1, 44, 6, 2019, '2019-07-21', 10, 0, -10, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(41, 2, 1, 45, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(42, 2, 1, 46, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(43, 2, 1, 47, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:51:14', 1, '2019-07-23', '12:39:17', 1),
(44, 2, 1, 49, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:52:53', 1, '2019-07-23', '12:39:17', 1),
(45, 2, 1, 50, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:52:53', 1, '2019-07-23', '12:39:17', 1),
(46, 2, 1, 52, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:54:02', 1, '2019-07-23', '12:39:17', 1),
(47, 2, 1, 53, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:54:02', 1, '2019-07-23', '12:39:17', 1),
(48, 2, 1, 55, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(49, 2, 1, 56, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(50, 2, 1, 57, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(51, 2, 1, 58, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(52, 2, 1, 59, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(53, 2, 1, 60, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(54, 2, 1, 61, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(55, 2, 1, 62, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(56, 2, 1, 63, 6, 2019, '2019-07-09', 0, 0, 0, 0, 0, 0, 0, 0, '2019-07-09', '13:56:42', 1, '2019-07-23', '12:39:17', 1),
(57, 1, 7, 29, 7, 2019, '2019-07-19', 20, 0, 0, 0, 0, 0, 0, 0, '2019-07-19', '09:15:37', 1, '2019-07-19', '09:15:37', 1),
(58, 1, 1, 15, 7, 2019, '2019-07-22', 5, 4, -1, 3, 6, 0, 0, 9, '2019-07-22', '15:46:03', 1, '2019-07-22', '16:38:59', 1),
(59, 1, 1, 16, 7, 2019, '2019-07-22', 5, 3, -2, 6, 8, 0, 0, 14, '2019-07-22', '15:46:03', 1, '2019-07-22', '16:38:59', 1),
(60, 1, 1, 12, 7, 2019, '2019-07-23', 6, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:24:43', 1, '2019-07-23', '12:37:57', 1),
(61, 1, 5, 12, 7, 2019, '2019-07-23', 6, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:24:43', 1, '2019-07-23', '12:37:57', 1),
(62, 1, 1, 50, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:25:54', 1, '2019-07-22', '17:25:54', 1),
(63, 1, 4, 50, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:25:54', 1, '2019-07-22', '17:25:54', 1),
(64, 1, 1, 11, 1, 2019, '2019-07-22', 2, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:26:29', 1, '2019-07-22', '17:26:29', 1),
(65, 1, 4, 11, 1, 2019, '2019-07-22', 2, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:26:29', 1, '2019-07-22', '17:26:29', 1),
(66, 1, 1, 22, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(67, 1, 1, 23, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(68, 1, 1, 26, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(69, 1, 4, 22, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(70, 1, 4, 23, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(71, 1, 4, 26, 1, 2019, '2019-07-22', 3, 0, 0, 0, 0, 0, 0, 0, '2019-07-22', '17:27:17', 1, '2019-07-22', '17:27:17', 1),
(72, 1, 1, 11, 7, 2019, '2019-07-23', 5, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(73, 1, 1, 9, 7, 2019, '2019-07-23', 4, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(74, 1, 5, 11, 7, 2019, '2019-07-23', 5, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(75, 1, 5, 9, 7, 2019, '2019-07-23', 4, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(76, 1, 8, 11, 7, 2019, '2019-07-23', 5, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(77, 1, 8, 12, 7, 2019, '2019-07-23', 6, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(78, 1, 8, 9, 7, 2019, '2019-07-23', 4, 0, 0, 0, 0, 0, 0, 0, '2019-07-23', '12:37:57', 1, '2019-07-23', '12:37:57', 1),
(79, 1, 1, 2, 2, 2018, '2019-09-02', 4, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(80, 1, 1, 3, 2, 2018, '2019-09-02', 4, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(81, 1, 1, 4, 2, 2018, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(82, 1, 1, 5, 2, 2018, '2019-09-02', 4, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(83, 1, 1, 6, 2, 2018, '2019-09-02', 3, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(84, 1, 1, 64, 2, 2018, '2019-09-02', 2, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(85, 1, 1, 7, 2, 2018, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(86, 1, 1, 8, 2, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(87, 1, 1, 9, 2, 2018, '2019-09-02', 7, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(88, 1, 1, 10, 2, 2018, '2019-09-02', 9, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(89, 1, 1, 11, 2, 2018, '2019-09-02', 4, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(90, 1, 1, 12, 2, 2018, '2019-09-02', 8, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(91, 1, 1, 13, 2, 2018, '2019-09-02', 9, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(92, 1, 1, 65, 2, 2018, '2019-09-02', 4, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '10:55:39', 1, '2019-09-02', '10:55:39', 1),
(93, 2, 7, 2, 3, 2019, '2019-09-02', 5, 1, -4, 0, 0, 0, 0, 0, '2019-09-02', '11:13:22', 1, '2019-09-02', '11:17:10', 1),
(94, 2, 7, 3, 3, 2019, '2019-09-02', 7, 2, -5, 0, 0, 0, 0, 0, '2019-09-02', '11:13:22', 1, '2019-09-02', '11:17:10', 1),
(95, 2, 7, 15, 3, 2019, '2019-09-02', 8, 2, -6, 0, 0, 0, 0, 0, '2019-09-02', '11:14:43', 1, '2019-09-02', '11:17:10', 1),
(96, 2, 7, 16, 3, 2019, '2019-09-02', 6, 4, -2, 0, 0, 0, 0, 0, '2019-09-02', '11:14:43', 1, '2019-09-02', '11:17:10', 1),
(97, 2, 7, 23, 3, 2019, '2019-09-02', 4, 3, -1, 0, 0, 0, 0, 0, '2019-09-02', '11:14:43', 1, '2019-09-02', '11:17:10', 1),
(98, 2, 7, 3, 7, 2019, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:18:18', 1, '2019-09-02', '12:18:18', 1),
(99, 2, 31, 3, 7, 2019, '2019-09-02', 1, 1, 0, 7, 3, 0, 0, 10, '2019-09-02', '12:32:55', 1, '2019-09-02', '13:08:47', 1),
(100, 2, 31, 4, 7, 2019, '2019-09-02', 52, 0, -52, 452, 1250, 0, 0, 1702, '2019-09-02', '12:32:55', 1, '2019-09-02', '13:08:47', 1),
(101, 2, 26, 3, 7, 2019, '2019-09-02', 1, 1, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:33:03', 1, '2019-09-02', '12:55:35', 1),
(102, 2, 10, 2, 7, 2019, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:35:22', 1, '2019-09-02', '12:35:22', 1),
(103, 2, 10, 3, 7, 2019, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:35:22', 1, '2019-09-02', '12:35:22', 1),
(104, 2, 10, 4, 7, 2019, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:35:22', 1, '2019-09-02', '12:35:22', 1),
(105, 2, 10, 5, 7, 2019, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:35:22', 1, '2019-09-02', '12:35:22', 1),
(106, 2, 8, 3, 7, 2019, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:36:03', 1, '2019-09-02', '12:36:03', 1),
(107, 2, 8, 4, 7, 2019, '2019-09-02', 52, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:36:03', 1, '2019-09-02', '12:36:03', 1),
(108, 2, 1, 4, 7, 2019, '2019-09-02', 66, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:36:37', 1, '2019-09-02', '12:36:37', 1),
(109, 2, 45, 2, 7, 2019, '2019-09-02', 5, 5, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:37:03', 1, '2019-09-02', '12:51:21', 1),
(110, 2, 45, 3, 7, 2019, '2019-09-02', 5, 4, -1, 0, 0, 0, 0, 0, '2019-09-02', '12:37:03', 1, '2019-09-02', '12:51:21', 1),
(111, 2, 45, 4, 7, 2019, '2019-09-02', 5, 3, -2, 0, 0, 0, 0, 0, '2019-09-02', '12:37:03', 1, '2019-09-02', '12:51:21', 1),
(112, 2, 6, 3, 11, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:37:46', 1, '2019-09-02', '12:37:46', 1),
(113, 2, 6, 4, 11, 2018, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:37:46', 1, '2019-09-02', '12:37:46', 1),
(114, 2, 5, 3, 7, 2019, '2019-09-02', 54, 50, -4, 3000, 5000, 0, 0, 8000, '2019-09-02', '12:38:30', 1, '2019-09-03', '11:12:32', 1),
(115, 2, 5, 9, 7, 2019, '2019-09-02', 60, 60, 0, 1100, 1000, 0, 0, 2100, '2019-09-02', '12:38:30', 1, '2019-09-03', '11:12:32', 1),
(116, 2, 35, 3, 7, 2019, '2019-09-02', 1, 1, 0, 7, 3, 0, 0, 10, '2019-09-02', '12:38:45', 1, '2019-09-02', '12:51:42', 1),
(117, 2, 35, 4, 7, 2019, '2019-09-02', 52, 4, -48, 670, 500, 0, 0, 1170, '2019-09-02', '12:38:45', 1, '2019-09-02', '12:51:42', 1),
(118, 2, 45, 15, 7, 2019, '2019-09-02', 370, 7, -363, 0, 0, 0, 0, 0, '2019-09-02', '12:41:59', 1, '2019-09-02', '12:51:21', 1),
(119, 2, 45, 16, 7, 2019, '2019-09-02', 18, 3, -15, 0, 0, 0, 0, 0, '2019-09-02', '12:41:59', 1, '2019-09-02', '12:51:21', 1),
(120, 2, 26, 7, 7, 2019, '2019-09-02', 100, 1, -99, 0, 0, 0, 0, 0, '2019-09-02', '12:45:07', 1, '2019-09-02', '12:55:35', 1),
(121, 2, 26, 8, 7, 2019, '2019-09-02', 50, 5, -45, 0, 0, 0, 0, 0, '2019-09-02', '12:45:07', 1, '2019-09-02', '12:55:35', 1),
(122, 2, 24, 2, 11, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:47:33', 1, '2019-09-02', '12:47:33', 1),
(123, 2, 24, 3, 11, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:47:33', 1, '2019-09-02', '12:47:33', 1),
(124, 2, 24, 4, 11, 2018, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:47:33', 1, '2019-09-02', '12:47:33', 1),
(125, 2, 28, 3, 7, 2019, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:58:52', 1, '2019-09-02', '12:58:52', 1),
(126, 2, 28, 7, 7, 2019, '2019-09-02', 120, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:58:52', 1, '2019-09-02', '12:58:52', 1),
(127, 2, 28, 9, 7, 2019, '2019-09-02', 20, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '12:58:52', 1, '2019-09-02', '12:58:52', 1),
(128, 2, 22, 2, 11, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '13:06:15', 1, '2019-09-02', '13:06:15', 1),
(129, 2, 22, 3, 11, 2018, '2019-09-02', 1, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '13:06:15', 1, '2019-09-02', '13:06:15', 1),
(130, 2, 22, 4, 11, 2018, '2019-09-02', 5, 0, 0, 0, 0, 0, 0, 0, '2019-09-02', '13:06:15', 1, '2019-09-02', '13:06:15', 1),
(131, 1, 1, 2, 9, 2019, '2019-09-15', 120, 0, 0, 0, 0, 0, 0, 0, '2019-09-15', '13:38:45', 1, '2019-09-15', '13:38:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_branches`
--

CREATE TABLE `dev_branches` (
  `pk_branch_id` int(10) UNSIGNED NOT NULL,
  `fk_branch_id` int(10) UNSIGNED DEFAULT NULL,
  `fk_branch_type` int(11) UNSIGNED DEFAULT 0,
  `fk_project_id` int(10) UNSIGNED DEFAULT NULL,
  `branch_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_division` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_sub_district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_contact_person` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_contact_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dev_branches`
--

INSERT INTO `dev_branches` (`pk_branch_id`, `fk_branch_id`, `fk_branch_type`, `fk_project_id`, `branch_name`, `branch_division`, `branch_district`, `branch_sub_district`, `branch_address`, `branch_contact_person`, `branch_contact_number`, `create_time`, `create_date`, `create_by`, `update_time`, `update_date`, `update_by`) VALUES
(1, 0, 1, 1, 'Tangail DRSC', 'Dhaka', 'Tangail', '', '', NULL, NULL, '13:28:46', '2019-07-08', 1, '13:28:46', '2019-07-08', 1),
(2, 1, 2, 2, 'Tangail Sadar', 'Dhaka', 'Tangail', 'Tangail Sadar', '', NULL, NULL, '13:29:47', '2019-07-08', 1, '13:29:47', '2019-07-08', 1),
(3, 1, 2, 2, 'Ghatail', 'Dhaka', 'Tangail', 'Ghatail', '', NULL, NULL, '13:30:19', '2019-07-08', 1, '13:30:19', '2019-07-08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_branch_types`
--

CREATE TABLE `dev_branch_types` (
  `pk_item_id` int(10) UNSIGNED NOT NULL,
  `fk_item_id` int(10) UNSIGNED DEFAULT NULL,
  `item_sort_order` smallint(5) UNSIGNED DEFAULT NULL,
  `item_title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_short_title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `_branch_type_slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_branch_types`
--

INSERT INTO `dev_branch_types` (`pk_item_id`, `fk_item_id`, `item_sort_order`, `item_title`, `item_short_title`, `_branch_type_slug`, `create_date`, `create_time`, `create_by`, `update_date`, `update_time`, `update_by`) VALUES
(1, 3, 2, 'District Branch', 'DRSC', NULL, '2019-06-17', '14:19:36', 1, '2019-06-17', '14:19:36', 1),
(2, 1, 3, 'Upazila Branch', 'URSC', NULL, '2019-06-17', '14:22:38', 1, '2019-09-02', '12:34:59', 1),
(3, 0, 1, 'Head Office', 'HO', NULL, '2019-07-09', '18:20:29', 1, '2019-07-09', '18:20:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_complains`
--

CREATE TABLE `dev_complains` (
  `pk_complain_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `name` varchar(160) DEFAULT NULL,
  `type_recipient` text DEFAULT NULL,
  `type_service` text DEFAULT NULL,
  `know_service` text DEFAULT NULL,
  `complain_register_date` date DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_complains`
--

INSERT INTO `dev_complains` (`pk_complain_id`, `fk_branch_id`, `name`, `type_recipient`, `type_service`, `know_service`, `complain_register_date`, `age`, `gender`, `address`, `remark`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(5, 0, 'Name of service recipient', 'Service recipient (victim, family, relative, community)', 'Type of service seeking advice/complaints/information/support', 'how he/she knows about this service of the project (IPT show, vedio show, scholl quiz, palli somaj,CTC, DLAC, social media, IEC/BCC, other (please sepecify)', '2020-07-01', '22', 'Sex', 'how he/she knows about this service of the project (IPT show, vedio show, scholl quiz, palli somaj,CTC, DLAC, social media, IEC/BCC, other (please sepecify)', 'how he/she knows about this service of the project (IPT show, vedio show, scholl quiz, palli somaj,CTC, DLAC, social media, IEC/BCC, other (please sepecify)', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 0, 'Name of service recipient', '', '', '', '2020-08-09', '', '', '', '', '17:03:16', '2020-08-09', 1, '17:03:32', '2020-08-09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_complain_fileds`
--

CREATE TABLE `dev_complain_fileds` (
  `pk_complain_filed_id` bigint(20) NOT NULL,
  `complain_register_date` date DEFAULT NULL,
  `month` varchar(30) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazila` varchar(100) DEFAULT NULL,
  `police_station` varchar(100) DEFAULT NULL,
  `case_id` varchar(60) DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `type_case` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_complain_fileds`
--

INSERT INTO `dev_complain_fileds` (`pk_complain_filed_id`, `complain_register_date`, `month`, `division`, `district`, `upazila`, `police_station`, `case_id`, `age`, `gender`, `type_case`, `comments`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, '2020-01-01', 'January', NULL, '', 'Select Municipality/Upazilla', 'Name of police station', 'Case ID', 'Age', 'female', 'Flee away with', 'Comments', '17:02:17', '2020-08-09', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_complain_investigations`
--

CREATE TABLE `dev_complain_investigations` (
  `pk_complain_investigation_id` bigint(20) NOT NULL,
  `complain_register_date` date DEFAULT NULL,
  `month` varchar(30) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazila` varchar(100) DEFAULT NULL,
  `police_station` varchar(100) DEFAULT NULL,
  `case_id` varchar(60) DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `type_case` varchar(50) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_config`
--

CREATE TABLE `dev_config` (
  `config_id` int(10) UNSIGNED NOT NULL,
  `config_name` text COLLATE utf8_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_config`
--

INSERT INTO `dev_config` (`config_id`, `config_name`, `config_value`) VALUES
(1, 'site_name', '{:en}BRAC CIFF{:en}{:bn}ব্র্যাক প্রবাসবন্ধু লিমিটেডে{:bn}{:ar} المحدودة براك مرحبا بك في{:ar}'),
(2, 'add_page_title', 'yes'),
(3, 'site_page_title_placement', '2'),
(4, 'site_page_title_separator', ' - '),
(5, 'website_description', '{:en}{:en}{:bn}ব্র্যাক বিপিএল প্রকল্পের আনুষ্ঠানিক ওয়েবসাইট{:bn}{:ar}Official website of the BRAC BPL Project{:ar}'),
(6, 'website_keywords', '{:en}{:en}{:bn}জনশক্তি, বাংলাদেশ জনশক্তি কোম্পানি, বিদেশী কর্মী{:bn}{:ar}Manpower, Bangladesh Manpower Company, Foreign Workers{:ar}'),
(7, 'website_copyright_text', '{:en}Copyright © Brac{:en}{:bn}কপিরাইট © ব্র্যাক{:bn}{:ar}Copyright © Brac{:ar}'),
(8, 'website_favicon', 'logo_icon.jpg'),
(9, 'image_cropping_mode', '1'),
(10, 'image_cropping_mode_force', 'no'),
(11, 'image_bg_color', '#ffffff'),
(12, 'image_quality', '90'),
(13, 'default_share_image', 'Untitled-1.png'),
(14, 'defaultNoImage', '123123.png'),
(15, 'facebook_link', 'https://www.facebook.com/3devs'),
(16, 'googleplus_link', 'https://plus.google.com/u/0/+3DEVsITLtd'),
(17, 'linkedin_link', 'https://www.linkedin.com/company/3devs-it-ltd-/?trk=biz-companies-cym'),
(18, 'twitter_link', 'https://twitter.com/3devsbd'),
(19, 'pinterest_link', ''),
(20, 'youtube_link', 'https://www.youtube.com/channel/UCwu-t_D_RgNt_gWfzm790XQ'),
(21, 'admin_login_page', 'adminers'),
(22, 'admin_page_logo', 'logo.png'),
(23, 'admin_page_heading', 'Admin Login'),
(24, 'admin_page_bg_image', 'migration.jpg'),
(25, 'admin_page_bg_color', '#fafafa'),
(26, 'admin_page_bg_color_opacity', '.5'),
(27, 'admin_page_header_bg_color', '#ffffff'),
(28, 'admin_page_header_text_color', '#000000'),
(29, 'admin_login_prompt_text', 'Sign in to your Account'),
(30, 'admin_login_prompt_text_color', '#ffffff'),
(31, 'admin_page_login_form_bg_color', '#000000'),
(32, 'admin_page_login_form_bg_color_opacity', '.5'),
(33, 'login_form_position', 'default'),
(34, 'required_email', 'on'),
(35, 'sms_id', ''),
(36, 'sms_password', ''),
(37, 'sms_brand', ''),
(38, 'fb_app_id', ''),
(39, 'fb_app_secret', ''),
(40, 'show_user_reset_method_email', 'email'),
(41, 'op_store_id', ''),
(42, 'op_store_password', ''),
(43, 'op_url', ''),
(44, 'op_validation_url', ''),
(45, 'map_api', ''),
(46, '__FILEMANGER_KEY__', 'efc8c0471af3fe5aa736b1fbd6f9d802'),
(47, 'meta_tags_for_header', ''),
(48, 'system_mode', 'online'),
(49, 'noFront', 'true'),
(50, 'time_zone', 'Asia/Dhaka'),
(51, 'mob_url', ''),
(52, 'reserved_pages', 'login,feed,sitemap,sitemap.xml,logout'),
(53, 'religions', ''),
(54, 'smtp_email_address', ''),
(55, 'smtp_email_password', ''),
(56, 'smtp_email_name', ''),
(57, 'smtp_host', ''),
(58, 'smtp_port', ''),
(59, 'adminTheme', 'frost'),
(60, 'required_mobile', '0'),
(61, 'allow_facebook_login', '0'),
(62, 'fb_user_status', '0'),
(63, 'apply_registration_verification', '0'),
(64, 'auto_login', '0'),
(65, 'required_terms', '0'),
(66, 'use_captcha_in_registration', '0'),
(67, 'force_default_user_settings', '0'),
(68, 'show_user_reset_method_sms', '0'),
(69, 'use_smtp_email_account', '0'),
(70, 'mainNavMenuToRight', '0'),
(71, 'mainNavMenuHide', '0'),
(72, 'adminMenu', 'a:2:{s:7:\"default\";a:2:{s:14:\"ADMINISTRATION\";a:8:{s:34:\"3_f9aae5fda8d810a29f12d1e61b4ab25f\";a:2:{s:5:\"label\";s:5:\"Users\";s:4:\"show\";s:3:\"yes\";}s:34:\"1_72b9ff839391f11e15cefb97fe4928c2\";a:2:{s:5:\"label\";s:15:\"User Activities\";s:4:\"show\";s:3:\"yes\";}s:35:\"18_f28128b38efbc6134dc40751ee21fd29\";a:2:{s:5:\"label\";s:9:\"Documents\";s:4:\"show\";s:3:\"yes\";}s:35:\"31_54e1d44609e3abed11f6e1eb6ae54988\";a:2:{s:5:\"label\";s:8:\"Projects\";s:4:\"show\";s:3:\"yes\";}s:34:\"9_dfc7e74a2707af638a0c5539897de3b9\";a:2:{s:5:\"label\";s:8:\"Branches\";s:4:\"show\";s:3:\"yes\";}s:35:\"33_6389f0fab4509d604ba042990fa6a5c7\";a:2:{s:5:\"label\";s:6:\"Staffs\";s:4:\"show\";s:3:\"yes\";}s:34:\"2_e4709a73a287a5f033f5b1b5142cb74d\";a:2:{s:5:\"label\";s:15:\"System Settings\";s:4:\"show\";s:3:\"yes\";}s:34:\"4_a8b8819be53936339e4f4837306a8dbe\";a:2:{s:5:\"label\";s:19:\"Roles & Permissions\";s:4:\"show\";s:3:\"yes\";}}s:9:\"CUSTOMERS\";a:5:{s:35:\"29_f6c94b890626758a4caecd8c444990a3\";a:2:{s:5:\"label\";s:18:\"Potential Customer\";s:4:\"show\";s:3:\"yes\";}s:35:\"16_3549efe3010b914aae47cfe941f9f06e\";a:2:{s:5:\"label\";s:17:\"Detainee Migrants\";s:4:\"show\";s:3:\"yes\";}s:35:\"15_d048e08fa7783e724489f7279d2fabad\";a:2:{s:5:\"label\";s:17:\"Returnee Migrants\";s:4:\"show\";s:3:\"yes\";}s:35:\"17_54f369a5aa0a6bbe0f16e7f26478076b\";a:2:{s:5:\"label\";s:18:\"Profile Conversion\";s:4:\"show\";s:3:\"yes\";}s:35:\"10_d0b16c27492709863e7cbbe6554e069d\";a:2:{s:5:\"label\";s:17:\"Search Case Study\";s:4:\"show\";s:3:\"yes\";}}}s:3:\"top\";a:6:{s:12:\"MIS ACTIVITY\";a:2:{s:34:\"5_0e32ccaf73e39f75ec087dd62e5554bc\";a:2:{s:5:\"label\";s:16:\"Activity Targets\";s:4:\"show\";s:3:\"yes\";}s:34:\"6_6e68a3ef02168755259318cecb9839a2\";a:2:{s:5:\"label\";s:21:\"Activity Achievements\";s:4:\"show\";s:3:\"yes\";}}s:7:\"COURSES\";a:6:{s:35:\"11_7d2bf53c67852bab5f7af751fcf68620\";a:2:{s:5:\"label\";s:17:\"Course Management\";s:4:\"show\";s:3:\"yes\";}s:34:\"7_e69b50ded2f2eb1cb2334a1ff8cc2204\";a:2:{s:5:\"label\";s:16:\"Batch Management\";s:4:\"show\";s:3:\"yes\";}s:34:\"8_e32943cbb5835647dea55db5c8042130\";a:2:{s:5:\"label\";s:14:\"Batch Schedule\";s:4:\"show\";s:3:\"yes\";}s:35:\"12_208f3c5befe91c7407eddc29130a192e\";a:2:{s:5:\"label\";s:16:\"Course Admission\";s:4:\"show\";s:3:\"yes\";}s:35:\"13_8af8c20afc119eb3d7237454d3a68b9a\";a:2:{s:5:\"label\";s:14:\"Course Results\";s:4:\"show\";s:3:\"yes\";}s:35:\"14_a2189a06c5ff1554c0c0e0fbef06898e\";a:2:{s:5:\"label\";s:20:\"Certificate Creation\";s:4:\"show\";s:3:\"yes\";}}s:9:\"FINANCIAL\";a:5:{s:35:\"23_c20595af9052bc9f5c3184de6574cd86\";a:2:{s:5:\"label\";s:12:\"Sales Target\";s:4:\"show\";s:3:\"yes\";}s:35:\"24_dd366d315958096c850d40770158bc69\";a:2:{s:5:\"label\";s:17:\"Sales Achievement\";s:4:\"show\";s:3:\"yes\";}s:35:\"25_9d0347c01fc872cc6cd612dc1b2a18d6\";a:2:{s:5:\"label\";s:12:\"Sales Income\";s:4:\"show\";s:3:\"yes\";}s:35:\"26_0d91740d21fc2f8aa3c5f1406d0b2cf5\";a:2:{s:5:\"label\";s:20:\"Incentive Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"27_db29a3995080d1a3eaaf66d83856a490\";a:2:{s:5:\"label\";s:22:\"Staff Incentive Report\";s:4:\"show\";s:3:\"yes\";}}s:8:\"SUPPORTS\";a:2:{s:35:\"28_6dcd8f470d3a999c0e948fe1e82b4075\";a:2:{s:5:\"label\";s:19:\"Followup Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"35_5c0d93f0d8e4e3962b1589b6fbe5a9ca\";a:2:{s:5:\"label\";s:19:\"Supports Management\";s:4:\"show\";s:3:\"yes\";}}s:8:\"BUSINESS\";a:4:{s:35:\"30_948590535142d16883c105bb52330fb2\";a:2:{s:5:\"label\";s:18:\"Product Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"34_491b992b9b809f4b8797ad1d5e89c93f\";a:2:{s:5:\"label\";s:16:\"Stock Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"32_5570b1274928746e87170e404f4553a1\";a:2:{s:5:\"label\";s:16:\"Sales Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"36_dce45527cf1399882caf9cd6f49f9c8b\";a:2:{s:5:\"label\";s:18:\"Company Management\";s:4:\"show\";s:3:\"yes\";}}s:5:\"ERRIN\";a:4:{s:35:\"20_b3dea5348430e36f0434e0f56a743ec1\";a:2:{s:5:\"label\";s:9:\"Returnees\";s:4:\"show\";s:3:\"yes\";}s:35:\"19_d799928eb48a88cb861f3283005d227a\";a:2:{s:5:\"label\";s:15:\"Case Management\";s:4:\"show\";s:3:\"yes\";}s:35:\"21_86ae5cf23e25be49723747c5b11884a5\";a:2:{s:5:\"label\";s:17:\"Meeting Schedules\";s:4:\"show\";s:3:\"yes\";}s:35:\"22_4d84482f0e785ea6e6edd5a4544c6693\";a:2:{s:5:\"label\";s:18:\"Reintegration Plan\";s:4:\"show\";s:3:\"yes\";}}}}'),
(73, 'unserialize', 'a:1:{i:0;s:9:\"adminMenu\";}'),
(74, 'adminWidgets', ''),
(75, 'current_public_theme', 'default'),
(76, 'default_no_image', '123123.png');

-- --------------------------------------------------------

--
-- Table structure for table `dev_customers`
--

CREATE TABLE `dev_customers` (
  `pk_customer_id` bigint(20) NOT NULL,
  `customer_id` char(20) DEFAULT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `mother_name` varchar(200) DEFAULT NULL,
  `customer_photo` text DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed','separated') DEFAULT NULL,
  `customer_spouse` varchar(200) DEFAULT NULL,
  `customer_birthdate` date DEFAULT NULL,
  `customer_gender` varchar(50) DEFAULT NULL,
  `customer_religion` varchar(50) DEFAULT NULL,
  `nid_number` varchar(20) NOT NULL,
  `passport_number` varchar(45) NOT NULL,
  `birth_reg_number` varchar(45) NOT NULL,
  `bmet_card_number` varchar(45) DEFAULT NULL,
  `travel_pass` varchar(45) DEFAULT NULL,
  `educational_qualification` text DEFAULT NULL,
  `customer_mobile` varchar(20) NOT NULL,
  `emergency_mobile` varchar(20) DEFAULT NULL,
  `emergency_name` varchar(50) DEFAULT NULL,
  `emergency_relation` varchar(50) DEFAULT NULL,
  `present_flat` varchar(10) DEFAULT NULL,
  `present_house` varchar(10) DEFAULT NULL,
  `present_road` varchar(10) DEFAULT NULL,
  `present_village` varchar(100) DEFAULT NULL,
  `present_ward` varchar(10) DEFAULT NULL,
  `present_union` varchar(100) DEFAULT NULL,
  `present_post_office` varchar(100) DEFAULT NULL,
  `present_post_code` varchar(10) DEFAULT NULL,
  `present_police_station` varchar(100) DEFAULT NULL,
  `present_sub_district` varchar(100) DEFAULT NULL,
  `present_district` varchar(100) DEFAULT NULL,
  `present_division` varchar(100) DEFAULT NULL,
  `present_country` varchar(100) DEFAULT NULL,
  `permanent_flat` varchar(10) DEFAULT NULL,
  `permanent_house` text DEFAULT NULL,
  `permanent_road` varchar(10) DEFAULT NULL,
  `permanent_village` varchar(100) DEFAULT NULL,
  `permanent_ward` varchar(100) DEFAULT NULL,
  `permanent_union` varchar(100) DEFAULT NULL,
  `permanent_post_office` varchar(100) DEFAULT NULL,
  `permanent_post_code` varchar(10) DEFAULT NULL,
  `permanent_police_station` varchar(100) DEFAULT NULL,
  `permanent_sub_district` varchar(100) DEFAULT NULL,
  `permanent_district` varchar(100) DEFAULT NULL,
  `permanent_division` varchar(100) DEFAULT NULL,
  `preferred_location` text DEFAULT NULL,
  `last_visited_country` varchar(100) DEFAULT NULL,
  `customer_status` enum('active','inactive') NOT NULL,
  `customer_type` enum('potential','detainee','returnee','errin','ciff') DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) UNSIGNED DEFAULT NULL,
  `fk_staff_id` bigint(20) DEFAULT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customers`
--

INSERT INTO `dev_customers` (`pk_customer_id`, `customer_id`, `full_name`, `father_name`, `mother_name`, `customer_photo`, `marital_status`, `customer_spouse`, `customer_birthdate`, `customer_gender`, `customer_religion`, `nid_number`, `passport_number`, `birth_reg_number`, `bmet_card_number`, `travel_pass`, `educational_qualification`, `customer_mobile`, `emergency_mobile`, `emergency_name`, `emergency_relation`, `present_flat`, `present_house`, `present_road`, `present_village`, `present_ward`, `present_union`, `present_post_office`, `present_post_code`, `present_police_station`, `present_sub_district`, `present_district`, `present_division`, `present_country`, `permanent_flat`, `permanent_house`, `permanent_road`, `permanent_village`, `permanent_ward`, `permanent_union`, `permanent_post_office`, `permanent_post_code`, `permanent_police_station`, `permanent_sub_district`, `permanent_district`, `permanent_division`, `preferred_location`, `last_visited_country`, `customer_status`, `customer_type`, `create_date`, `create_time`, `create_by`, `update_date`, `update_time`, `update_by`, `fk_staff_id`, `fk_branch_id`) VALUES
(4, 'Participant ID', 'Full Name', 'Father Name', 'Mother Name', NULL, 'widowed', '', '2020-08-05', 'Other Gender', NULL, 'NID Number', 'Passport No9', 'Birth Registration Number', NULL, NULL, 'on', 'Mobile Number', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Address', NULL, 'Village', 'Ward No', 'Union/Pourashava', NULL, NULL, NULL, 'Jashore Sadar', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-08-05', '12:50:47', 1, '2020-08-20', '13:28:38', 1, NULL, NULL),
(5, '', 'Full Name', 'Father Name', '', NULL, 'married', 'sada', '2020-08-01', 'male', NULL, '', 'Passport No', '', NULL, NULL, 'sign', '01719020278', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Address', NULL, '', '', '', NULL, NULL, NULL, 'Jhikargacha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-08-20', '13:37:53', 1, NULL, NULL, NULL, NULL, NULL),
(6, '', 'Full Name', 'Father Name', '', NULL, 'married', 'sada', '2020-08-01', 'male', NULL, '', 'Passport No', '', NULL, NULL, 'sign', '01719020278', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Address', NULL, '', '', '', NULL, NULL, NULL, 'Jhikargacha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-08-20', '13:38:52', 1, NULL, NULL, NULL, NULL, NULL),
(7, '', 'Full Name', 'Father Name', '', NULL, 'single', '', '2020-08-20', 'female', NULL, '', '172323GT73e', '', NULL, NULL, '', '01719020274', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'erfds', NULL, '', '', '', NULL, NULL, NULL, 'Jhikargacha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-08-20', '13:59:06', 1, NULL, NULL, NULL, NULL, NULL),
(8, '', 'Full Name', 'Father Name', '', NULL, 'single', '', '2020-08-20', 'female', NULL, '', '172323GT73e', '', NULL, NULL, '', '01719020274', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'erfds', NULL, '', '', '', NULL, NULL, NULL, 'Jhikargacha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-08-20', '13:59:33', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_customer_health`
--

CREATE TABLE `dev_customer_health` (
  `pk_customer_health_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `is_physically_challenged` enum('yes','no') DEFAULT NULL,
  `disability_type` text DEFAULT NULL,
  `having_chronic_disease` enum('yes','no') DEFAULT NULL,
  `disease_type` text DEFAULT NULL,
  `other_disease_type` text DEFAULT NULL,
  `need_psychosocial_support` enum('yes','no') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customer_health`
--

INSERT INTO `dev_customer_health` (`pk_customer_health_id`, `fk_customer_id`, `is_physically_challenged`, `disability_type`, `having_chronic_disease`, `disease_type`, `other_disease_type`, `need_psychosocial_support`) VALUES
(4, 4, 'yes', 'Type of disability', 'yes', 'diabetes,heart_diseases', 'sdfasd', NULL),
(5, 5, 'yes', '', 'no', '', NULL, NULL),
(6, 6, 'yes', '', 'no', '', NULL, NULL),
(7, 7, 'no', '', 'no', '', NULL, NULL),
(8, 8, 'no', '', 'no', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_customer_migrations`
--

CREATE TABLE `dev_customer_migrations` (
  `pk_migration_id` bigint(20) UNSIGNED NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `fk_country_id` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `exit_date` date DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL COMMENT 'number of days',
  `duration_month` int(11) DEFAULT NULL COMMENT 'number of months',
  `duration_year` int(11) DEFAULT NULL COMMENT 'number of years',
  `duration_total_months` int(11) DEFAULT NULL COMMENT 'total number of months',
  `duration_total_days` int(11) DEFAULT NULL COMMENT 'total number of days',
  `is_first` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'no',
  `is_last` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_customer_skills`
--

CREATE TABLE `dev_customer_skills` (
  `pk_customer_skills_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `is_certification_required` enum('yes','no') DEFAULT NULL,
  `required_certification` text NOT NULL,
  `have_earner_skill` enum('yes','no') DEFAULT NULL,
  `have_skills` text DEFAULT NULL,
  `other_have_skills` text DEFAULT NULL,
  `need_skills` text DEFAULT NULL,
  `vocational_skill` text DEFAULT NULL,
  `handicraft_skill` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customer_skills`
--

INSERT INTO `dev_customer_skills` (`pk_customer_skills_id`, `fk_customer_id`, `is_certification_required`, `required_certification`, `have_earner_skill`, `have_skills`, `other_have_skills`, `need_skills`, `vocational_skill`, `handicraft_skill`) VALUES
(4, 4, NULL, '', 'yes', 'on,on,tailor_work,block_batiks', 'sdasda', NULL, 'Vocational', 'Handicrafts'),
(5, 5, NULL, '', 'no', '', NULL, NULL, NULL, NULL),
(6, 6, NULL, '', 'no', '', NULL, NULL, NULL, NULL),
(7, 7, NULL, '', 'no', '', NULL, NULL, NULL, NULL),
(8, 8, NULL, '', 'no', '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_customer_supports`
--

CREATE TABLE `dev_customer_supports` (
  `pk_rel_id` bigint(20) NOT NULL,
  `fk_customer_id` varchar(255) DEFAULT NULL,
  `fk_support_id` varchar(255) DEFAULT NULL,
  `support_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_documents`
--

CREATE TABLE `dev_documents` (
  `pk_document_id` bigint(20) UNSIGNED NOT NULL,
  `document_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_file` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_mime` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_extension` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `document_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_documents`
--

INSERT INTO `dev_documents` (`pk_document_id`, `document_name`, `document_file`, `document_mime`, `document_extension`, `document_type`, `create_by`, `create_date`, `create_time`) VALUES
(1, 'Software Requirement Specification.pdf', 'Software_Requirement_Specification_1561526308.pdf', 'application/pdf', 'pdf', 'Publication Document', 1, '2019-06-26', '11:18:28'),
(2, 'Ariful Islam', 'Ariful_Islam_Nayem_1563786853.pdf', 'application/pdf', 'pdf', 'Publication Document', 1, '2019-07-22', '15:14:13'),
(3, 'Sagar Chandra Shill', 'Sagar_Chandra_Shill_1563786853.pdf', 'application/pdf', 'pdf', 'Publication Document', 1, '2019-07-22', '15:14:13'),
(5, 'dagobert83_female_user_icon.png', 'dagobert83_female_user_icon_1567493799.png', 'image/png', 'png', 'Image', 1, '2019-09-03', '12:56:39'),
(6, 'Nazmul Hossain', 'Nazmul-Hossain_1568292766.doc', 'application/msword', 'doc', 'Word Document', 1, '2019-09-12', '18:52:46'),
(7, 'Nazmul-Hossain.doc', 'Nazmul-Hossain_1568292832.doc', 'application/msword', 'doc', 'Word Document', 1, '2019-09-12', '18:53:52'),
(8, 'Application.doc', 'Application_1568292832.doc', 'application/msword', 'doc', 'Word Document', 1, '2019-09-12', '18:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `dev_economicals`
--

CREATE TABLE `dev_economicals` (
  `pk_economic_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date NOT NULL,
  `is_latest` enum('yes','no') DEFAULT NULL,
  `current_monthly_income` double NOT NULL,
  `feed_male` int(10) DEFAULT NULL,
  `feed_female` int(10) DEFAULT NULL,
  `dependent_feed_members` int(10) DEFAULT NULL,
  `earning_male_members` int(10) DEFAULT NULL,
  `earning_female_members` int(10) DEFAULT NULL,
  `feed_members_monthly_income` double DEFAULT NULL,
  `feed_members_monthly_expense` double DEFAULT NULL,
  `total_saved_money` double DEFAULT NULL,
  `personal_loan_amount` double DEFAULT NULL,
  `loan_source` text DEFAULT NULL,
  `have_mortgages` enum('yes','no') DEFAULT NULL,
  `mortgage_value` varchar(20) DEFAULT NULL,
  `current_residence_ownership` text DEFAULT NULL,
  `current_residence_type` text DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_evaluations`
--

CREATE TABLE `dev_economic_evaluations` (
  `pk_economic_evaluation_id` bigint(20) NOT NULL,
  `fk_economic_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date NOT NULL,
  `happy_income` tinyint(4) NOT NULL,
  `food_security` tinyint(4) NOT NULL,
  `work_support` tinyint(4) NOT NULL,
  `job_support` tinyint(4) NOT NULL,
  `satisfied_economic` tinyint(4) NOT NULL,
  `debt_situation` tinyint(4) NOT NULL,
  `happy_savings` tinyint(4) NOT NULL,
  `satisfied_family` tinyint(4) NOT NULL,
  `peaceful_economic` tinyint(4) NOT NULL,
  `neighbor_respect` tinyint(4) NOT NULL,
  `evaluated_score` int(11) NOT NULL,
  `review_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_evaluations`
--

INSERT INTO `dev_economic_evaluations` (`pk_economic_evaluation_id`, `fk_economic_support_id`, `fk_customer_id`, `entry_date`, `happy_income`, `food_security`, `work_support`, `job_support`, `satisfied_economic`, `debt_situation`, `happy_savings`, `satisfied_family`, `peaceful_economic`, `neighbor_respect`, `evaluated_score`, `review_remarks`) VALUES
(1, 0, 99, '2019-09-15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Not Reintegrated');

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_followups`
--

CREATE TABLE `dev_economic_followups` (
  `pk_economic_review_id` bigint(20) UNSIGNED NOT NULL,
  `fk_economic_support_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_profile`
--

CREATE TABLE `dev_economic_profile` (
  `pk_economic_profile_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `pre_occupation` text DEFAULT NULL,
  `present_occupation` text DEFAULT NULL,
  `present_income` double DEFAULT NULL,
  `total_member` int(11) DEFAULT NULL,
  `male_household_member` int(11) DEFAULT NULL,
  `female_household_member` int(11) DEFAULT NULL,
  `total_dependent_member` int(11) DEFAULT NULL,
  `male_earning_member` int(2) DEFAULT NULL,
  `female_earning_member` int(2) DEFAULT NULL,
  `total_earner` int(3) DEFAULT NULL,
  `household_income` double DEFAULT NULL,
  `household_expenditure` double DEFAULT NULL,
  `personal_savings` double DEFAULT NULL,
  `personal_debt` double DEFAULT NULL,
  `loan_sources` text DEFAULT NULL,
  `have_mortgages` enum('yes','no') DEFAULT NULL,
  `mortgage_name` varchar(100) DEFAULT NULL,
  `mortgage_value` double DEFAULT NULL,
  `current_residence_ownership` text DEFAULT NULL,
  `current_residence_type` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_profile`
--

INSERT INTO `dev_economic_profile` (`pk_economic_profile_id`, `fk_customer_id`, `pre_occupation`, `present_occupation`, `present_income`, `total_member`, `male_household_member`, `female_household_member`, `total_dependent_member`, `male_earning_member`, `female_earning_member`, `total_earner`, `household_income`, `household_expenditure`, `personal_savings`, `personal_debt`, `loan_sources`, `have_mortgages`, `mortgage_name`, `mortgage_value`, `current_residence_ownership`, `current_residence_type`) VALUES
(4, 4, 'Main occupation before', 'Main occupation after', 6000, 50, 20, 30, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 6000, NULL, NULL, NULL, NULL, 'own', '3423423'),
(5, 5, 'Main occupation before', 'Main occupation after', 6000, 8, 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 6000, NULL, NULL, NULL, NULL, 'khas_land', 'pucca'),
(6, 6, 'Main occupation before', 'Main occupation after', 6000, 8, 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 6000, NULL, NULL, NULL, NULL, 'khas_land', 'pucca'),
(7, 7, 'Engi', '00', 0, 8, 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, 'without_paying', 'live'),
(8, 8, 'Engi', '1', 1, 8, 4, 4, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 'without_paying', 'live');

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_reintegration_referrals`
--

CREATE TABLE `dev_economic_reintegration_referrals` (
  `pk_economic_referral_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `is_vocational_training` enum('yes','no') DEFAULT NULL,
  `received_vocational` text DEFAULT NULL,
  `other_received_vocational` text DEFAULT NULL,
  `is_certificate_received` enum('yes','no') DEFAULT NULL,
  `used_far` text DEFAULT NULL,
  `other_comments` text DEFAULT NULL,
  `is_economic_services` enum('yes','no') DEFAULT NULL,
  `economic_support` text DEFAULT NULL,
  `economic_financial_service` text DEFAULT NULL,
  `other_economic_support` text DEFAULT NULL,
  `is_assistance_received` enum('yes','no') DEFAULT NULL,
  `refferd_to` text DEFAULT NULL,
  `trianing_date` date DEFAULT NULL,
  `place_of_training` text DEFAULT NULL,
  `duration_training` text DEFAULT NULL,
  `refferd_address` text DEFAULT NULL,
  `status_traning` text DEFAULT NULL,
  `assistance_utilized` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_reintegration_referrals`
--

INSERT INTO `dev_economic_reintegration_referrals` (`pk_economic_referral_id`, `fk_customer_id`, `is_vocational_training`, `received_vocational`, `other_received_vocational`, `is_certificate_received`, `used_far`, `other_comments`, `is_economic_services`, `economic_support`, `economic_financial_service`, `other_economic_support`, `is_assistance_received`, `refferd_to`, `trianing_date`, `place_of_training`, `duration_training`, `refferd_address`, `status_traning`, `assistance_utilized`) VALUES
(1, 1, 'no', 'Energies supply', NULL, '', '', '', '', '', NULL, NULL, '', '', '2020-07-30', '', '', '', '', ''),
(2, 4, 'yes', 'Manufacturing,Health and social work,on', 'Name of the Vocational Training Received', 'yes', '', 'Any other Comments', 'yes', 'Microbusiness,on', '', 'Types of Economic Support', 'yes', 'Referred To Name', '1950-10-24', 'place of training', 'duration of training', 'Referred To Address', 'yes', 'How has the assistance been utilized?');

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_supports`
--

CREATE TABLE `dev_economic_supports` (
  `pk_economic_support_id` bigint(20) NOT NULL,
  `fk_project_id` bigint(20) NOT NULL,
  `inkind_project` text DEFAULT NULL,
  `other_inkind_project` text DEFAULT NULL,
  `inkind_received` enum('yes','no') NOT NULL,
  `inkind_amount` double NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `end_date` date NOT NULL,
  `is_attended_fair` enum('yes','no') DEFAULT NULL,
  `is_financial_training` enum('yes','no') DEFAULT NULL,
  `training_duration` varchar(20) DEFAULT NULL,
  `received_vocational_training` text DEFAULT NULL COMMENT 'Tag',
  `other_received_vocational_training` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `is_certification_received` enum('yes','no') NOT NULL,
  `training_used` text NOT NULL,
  `other_comments` text NOT NULL,
  `training_status` enum('ongoing','completed','uncompleted') DEFAULT NULL,
  `is_tr_cer_received` enum('yes','no') DEFAULT NULL,
  `training_application` text DEFAULT NULL,
  `training_comments` text DEFAULT NULL,
  `is_referrals_done` enum('yes','no') DEFAULT NULL,
  `referral_support_required` text NOT NULL,
  `referral_name` varchar(100) DEFAULT NULL,
  `referral_address` text DEFAULT NULL,
  `is_assistance_received` enum('yes','no') DEFAULT NULL,
  `is_assistance_remigrate` enum('yes','no') DEFAULT NULL,
  `remigrate_country` text NOT NULL,
  `support_name` enum('loan') DEFAULT NULL,
  `assistant_description` text DEFAULT NULL,
  `assistant_comment` text DEFAULT NULL,
  `assistance_utilize` text DEFAULT NULL COMMENT 'Tag',
  `support_received` text NOT NULL,
  `support_from_whom` text NOT NULL,
  `microbusiness_established` enum('yes','no') DEFAULT NULL,
  `family_training` enum('yes','no') DEFAULT NULL,
  `traning_entry_date` date DEFAULT NULL,
  `place_traning` varchar(100) DEFAULT NULL,
  `duration_traning` varchar(100) DEFAULT NULL,
  `month_inauguration` varchar(100) DEFAULT NULL,
  `year_inauguration` varchar(100) DEFAULT NULL,
  `training_start_date` date DEFAULT NULL,
  `training_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_supports`
--

INSERT INTO `dev_economic_supports` (`pk_economic_support_id`, `fk_project_id`, `inkind_project`, `other_inkind_project`, `inkind_received`, `inkind_amount`, `fk_customer_id`, `entry_date`, `end_date`, `is_attended_fair`, `is_financial_training`, `training_duration`, `received_vocational_training`, `other_received_vocational_training`, `start_date`, `close_date`, `is_certification_received`, `training_used`, `other_comments`, `training_status`, `is_tr_cer_received`, `training_application`, `training_comments`, `is_referrals_done`, `referral_support_required`, `referral_name`, `referral_address`, `is_assistance_received`, `is_assistance_remigrate`, `remigrate_country`, `support_name`, `assistant_description`, `assistant_comment`, `assistance_utilize`, `support_received`, `support_from_whom`, `microbusiness_established`, `family_training`, `traning_entry_date`, `place_traning`, `duration_traning`, `month_inauguration`, `year_inauguration`, `training_start_date`, `training_end_date`) VALUES
(6, 0, '', NULL, '', 0, 1, NULL, '0000-00-00', NULL, NULL, '', '', NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', '', '', '', '2020-07-30', NULL, '', '', '', '2020-07-30', '2020-07-30'),
(7, 0, 'Microbusiness,on', 'n-kind Support from Projec', 'yes', 0, 4, NULL, '0000-00-00', NULL, NULL, 'Received Financial L', 'Manufacturing,Health and social work,on', 'Name of the Vocational Training Received', NULL, NULL, 'yes', 'How has the training been used so far?', 'Any other Comments5', 'completed', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', '', 'no', 'yes', '2010-01-05', NULL, 'sdasdas', 'cads', 'sadasd', '1990-01-01', '1968-11-29');

-- --------------------------------------------------------

--
-- Table structure for table `dev_email_templates`
--

CREATE TABLE `dev_email_templates` (
  `pk_etemplate_id` int(10) UNSIGNED NOT NULL,
  `template_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_code` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_order` tinyint(3) UNSIGNED DEFAULT 0,
  `modified_by` int(10) UNSIGNED NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_email_templates`
--

INSERT INTO `dev_email_templates` (`pk_etemplate_id`, `template_id`, `user_code`, `template_order`, `modified_by`, `modified_at`) VALUES
(1, 'new_general_contact_message', '<strong>Dear Concern</strong>\r\n                                <br />\r\n                                <br />\r\n                                    A new General Contact Message has arrived as follows\r\n                                <br />\r\n                                <br />\r\n                                <div class=\"p10\">\r\n                                <hr />\r\n                                    <strong>Name: </strong>$$NAME$$<br />\r\n                                    <strong>Email: </strong>$$EMAIL$$<br />\r\n                                    <strong>Website: </strong>$$WEBSITE$$<br />\r\n                                <hr />\r\n                                    $$MESSAGE$$\r\n                                <hr />\r\n\r\n                                     <a class=\"mailBtn btnDanger\" href=\"$$LINK$$\">Click Here to Delete This Message</a>\r\n                                </div>\r\n                                <br />\r\n                                <br />\r\n                                    Thank You.\r\n                                ', 0, 0, '2018-08-16 12:28:59'),
(2, 'new_recruiter_contact_message', '<strong>Dear Concern</strong>\r\n                                <br />\r\n                                <br />\r\n                                    A new Recruiter Contact Message has arrived as follows\r\n                                <br />\r\n                                <br />\r\n                                <div class=\"p10\">\r\n                                <hr />\r\n                                    <strong>Name: </strong>$$NAME$$<br />\r\n                                    <strong>Email: </strong>$$EMAIL$$<br />\r\n                                    <strong>Organization: </strong>$$ORGANIZATION$$<br />\r\n                                    <strong>Country: </strong>$$COUNTRY$$<br />\r\n                                    <strong>Skill Requirements: </strong>$$SKILLS$$<br />\r\n                                <hr />\r\n                                    $$MESSAGE$$\r\n                                <hr />\r\n\r\n                                     <a class=\"mailBtn btnDanger\" href=\"$$LINK$$\">Click Here to Delete This Message</a>\r\n                                </div>\r\n                                <br />\r\n                                <br />\r\n                                    Thank You.\r\n                                ', 1, 0, '2018-08-16 12:29:00'),
(3, 'new_job_seeker_contact_message', '<strong>Dear Concern</strong>\r\n                                <br />\r\n                                <br />\r\n                                    A new Job Seeker Contact Message has arrived as follows\r\n                                <br />\r\n                                <br />\r\n                                <div class=\"p10\">\r\n                                <hr />\r\n                                    <strong>Name: </strong>$$NAME$$<br />\r\n                                    <strong>Email: </strong>$$EMAIL$$<br />\r\n                                    <strong>Mobile: </strong>$$MOBILE$$<br />\r\n                                    <strong>Subject: </strong>$$SUBJECT$$<br />\r\n                                <hr />\r\n                                    $$MESSAGE$$\r\n                                <hr />\r\n\r\n                                     <a class=\"mailBtn btnDanger\" href=\"$$LINK$$\">Click Here to Delete This Message</a>\r\n                                </div>\r\n                                <br />\r\n                                <br />\r\n                                    Thank You.\r\n                                ', 2, 0, '2018-08-16 12:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `dev_events`
--

CREATE TABLE `dev_events` (
  `pk_event_id` bigint(20) NOT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `event_branch` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_start_time` time DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazila` varchar(80) DEFAULT NULL,
  `event_union` varchar(80) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `village` varchar(150) DEFAULT NULL,
  `ward` varchar(150) DEFAULT NULL,
  `below_male` int(2) DEFAULT NULL,
  `below_female` int(2) DEFAULT NULL,
  `above_male` int(2) DEFAULT NULL,
  `above_female` int(2) DEFAULT NULL,
  `preparatory_work` int(2) DEFAULT NULL,
  `time_management` int(2) DEFAULT NULL,
  `participants_attention` int(2) DEFAULT NULL,
  `logistical_arrangements` int(2) DEFAULT NULL,
  `relevancy_delivery` int(2) DEFAULT NULL,
  `participants_feedback` int(2) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_events`
--

INSERT INTO `dev_events` (`pk_event_id`, `event_type`, `event_branch`, `event_date`, `event_start_time`, `division`, `district`, `upazila`, `event_union`, `location`, `village`, `ward`, `below_male`, `below_female`, `above_male`, `above_female`, `preparatory_work`, `time_management`, `participants_attention`, `logistical_arrangements`, `relevancy_delivery`, `participants_feedback`, `note`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(2, 'Select One', '', '2020-08-11', '07:22:00', 'Rangpur', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '15:22:09', '2020-08-11', 1, '15:41:42', '2020-08-11', 1),
(3, '3', '', '2020-08-12', '11:52:29', 'Rajshahi', 'Joypurhat', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, '', '11:53:59', '2020-08-12', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_event_types`
--

CREATE TABLE `dev_event_types` (
  `pk_event_type_id` bigint(20) NOT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_event_types`
--

INSERT INTO `dev_event_types` (`pk_event_type_id`, `event_type`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(3, 'Event Type', '23:58:24', '2020-08-11', 1, '00:01:01', '2020-08-12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_event_validations`
--

CREATE TABLE `dev_event_validations` (
  `pk_validation_id` bigint(20) NOT NULL,
  `fk_event_id` bigint(20) NOT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `reviewed_by` varchar(30) DEFAULT NULL,
  `beneficiary_id` varchar(30) DEFAULT NULL,
  `participant_name` varchar(100) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `age` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `enjoyment` varchar(30) DEFAULT NULL,
  `victim` enum('yes','no') DEFAULT NULL,
  `victim_family` enum('yes','no') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `other_message` text DEFAULT NULL,
  `use_message` text DEFAULT NULL,
  `mentioned_event` text DEFAULT NULL,
  `additional_comments` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_event_validations`
--

INSERT INTO `dev_event_validations` (`pk_validation_id`, `fk_event_id`, `interview_date`, `interview_time`, `reviewed_by`, `beneficiary_id`, `participant_name`, `gender`, `age`, `mobile`, `enjoyment`, `victim`, `victim_family`, `message`, `other_message`, `use_message`, `mentioned_event`, `additional_comments`, `quote`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 2, '2020-08-11', '21:14:18', 'internal', 'Beneficiary ID', 'Participant Name', '1', 'Participant Age', 'Participant Mobile', 'no', 'yes', 'yes', 'Trafficking in persons,Result of human trafficking', NULL, 'How do you intend to use these messages in your personal life?', 'What was mentioned in the event show that was not clear to you?', 'Additional comments (if any)', 'Quote', '21:15:54', '2020-08-11', 1, '22:01:28', '2020-08-11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_followups`
--

CREATE TABLE `dev_followups` (
  `pk_followup_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `division_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_district_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `followup_date` date NOT NULL,
  `beneficiary_status` text COLLATE utf8_unicode_ci NOT NULL,
  `followup_challenges` text COLLATE utf8_unicode_ci NOT NULL,
  `action_taken` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) DEFAULT NULL,
  `casedropped` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason_dropping` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_reason_dropping` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_services` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `financial_service` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_protection` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `special_security` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_psychosocial` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_economic` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_social` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `complete_income` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthly_income` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `challenges` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `actions_taken` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark_participant` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_brac` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark_district` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_followups`
--

INSERT INTO `dev_followups` (`pk_followup_id`, `fk_branch_id`, `division_name`, `district_name`, `sub_district_name`, `fk_customer_id`, `followup_date`, `beneficiary_status`, `followup_challenges`, `action_taken`, `create_date`, `create_time`, `create_by`, `casedropped`, `reason_dropping`, `other_reason_dropping`, `confirm_services`, `financial_service`, `social_protection`, `special_security`, `comment_psychosocial`, `comment_economic`, `comment_social`, `complete_income`, `monthly_income`, `challenges`, `actions_taken`, `remark_participant`, `comment_brac`, `remark_district`, `update_date`, `update_time`, `update_by`) VALUES
(1, 1, 'Dhaka', 'Dhaka', 'Dohar', 1, '2019-09-09', 'Status of beneficiary', 'Challenges', 'Action Taken', '2019-09-09', '15:55:10', 1, '', NULL, NULL, '', NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, 4, '0000-00-00', '', '', '', NULL, NULL, NULL, 'yes', 'Lack of interest', 'Reason for Dropping Out', 'Child Care,Education,Financial Services,Loan,Support for land allocation,Job Placement,Job Placement,Medical treatment', 'Other Financial Service', 'dqw', 'qweqwe', 'asAS', 'ASA', 'SASS', 'SAS', '22', 'ca', 'das', 'asdas', 'dasdasd', 'asdasd', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_immediate_supports`
--

CREATE TABLE `dev_immediate_supports` (
  `pk_immediate_support_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `fk_staff_id` bigint(20) DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `immediate_support` text DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_immediate_supports`
--

INSERT INTO `dev_immediate_supports` (`pk_immediate_support_id`, `fk_branch_id`, `fk_staff_id`, `fk_customer_id`, `immediate_support`, `create_date`, `create_time`, `create_by`, `update_date`, `update_time`, `update_by`) VALUES
(1, NULL, NULL, 1, 'Information provision', NULL, NULL, NULL, '2020-08-04', '13:32:13', 1),
(2, NULL, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 0, 0, 4, 'Meet and greet at port of entry', NULL, NULL, NULL, '2020-08-17', '15:28:10', 1),
(4, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, NULL, NULL, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_initial_evaluation`
--

CREATE TABLE `dev_initial_evaluation` (
  `pk_evaluation_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `is_participant` enum('yes','no') DEFAULT NULL,
  `justification_project` text DEFAULT NULL,
  `evaluate_services` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_initial_evaluation`
--

INSERT INTO `dev_initial_evaluation` (`pk_evaluation_id`, `fk_customer_id`, `is_participant`, `justification_project`, `evaluate_services`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 1, 'no', NULL, 'Advance training through referrals,Remigration,Psychosocial Support,Psychosocial Support', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 4, 'no', NULL, 'Child Care,Education,Psychosocial Support', NULL, NULL, NULL, '20:07:49', '2020-08-05', 19),
(3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_jack_settings`
--

CREATE TABLE `dev_jack_settings` (
  `pk_settings_id` int(10) UNSIGNED NOT NULL,
  `fk_jack_id` varchar(300) DEFAULT NULL,
  `settings_type` enum('global','personal') DEFAULT 'global',
  `fk_user_id` int(10) UNSIGNED DEFAULT 0,
  `settings_key` text DEFAULT NULL,
  `settings_value` text DEFAULT NULL,
  `settings_created_by` int(10) UNSIGNED DEFAULT NULL,
  `settings_created_at` datetime DEFAULT NULL,
  `settings_modified_by` int(10) UNSIGNED DEFAULT NULL,
  `settings_modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_jack_settings`
--

INSERT INTO `dev_jack_settings` (`pk_settings_id`, `fk_jack_id`, `settings_type`, `fk_user_id`, `settings_key`, `settings_value`, `settings_created_by`, `settings_created_at`, `settings_modified_by`, `settings_modified_at`) VALUES
(95, 'Theme Settings', 'global', 68, 'jack_id', 'Theme Settings', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(96, 'Theme Settings', 'global', 68, 'facebook', 'https://www.facebook.com/3devs', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(97, 'Theme Settings', 'global', 68, 'twitter', 'https://twitter.com/3devsbd', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(98, 'Theme Settings', 'global', 68, 'linkedin', 'https://www.linkedin.com/company/3devs-it-ltd-/?trk=biz-companies-cym', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(99, 'Theme Settings', 'global', 68, 'googleplus', 'https://plus.google.com/u/0/+3DEVsITLtd', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(100, 'Theme Settings', 'global', 68, 'youtube', 'https://www.youtube.com/channel/UCwu-t_D_RgNt_gWfzm790XQ', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(101, 'Theme Settings', 'global', 68, 'left_hanging_show_link', 'yes', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(102, 'Theme Settings', 'global', 68, 'left_hanging_link', 'contact-us', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(103, 'Theme Settings', 'global', 68, 'left_hanging_link_label', 'Contact us', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(104, 'Theme Settings', 'global', 68, 'footer_description', '3DEVs IT Ltd. is one of the best creative IT Companies in Bangladesh\r\n for developing any kind of website, software, graphic design and all \r\nkinds of IT solution. It proposes customer-leaning web-based software\r\n development services, website design & more extensively provides them successfully. ', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(105, 'Theme Settings', 'global', 68, 'footer_copyright_text', '© 3DEVs IT LTD 2018. Design and Developed  by 3DEVs IT LTD || Powered by 3DEVs CMS ', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(106, 'Theme Settings', 'global', 68, 'blog_page', '9', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(107, 'Theme Settings', 'global', 68, 'services_page', '7', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(108, 'Theme Settings', 'global', 68, 'submitSettings', 'Save Settings', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48'),
(109, 'Theme Settings', 'global', 68, 'left_hanging_link_type', 'internal', 68, '2018-08-29 17:14:48', 68, '2018-08-29 17:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `dev_knowledge`
--

CREATE TABLE `dev_knowledge` (
  `pk_knowledge_id` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `document_file` varchar(100) DEFAULT NULL,
  `tags` text NOT NULL,
  `type` enum('story','research','assessment','organogram','study') DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_lookups`
--

CREATE TABLE `dev_lookups` (
  `pk_lookup_id` bigint(20) NOT NULL,
  `fk_content_id` bigint(20) NOT NULL,
  `lookup_group` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `lookup_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_meetings`
--

CREATE TABLE `dev_meetings` (
  `pk_meeting_id` bigint(20) NOT NULL,
  `meeting_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `fk_case_id` bigint(20) NOT NULL,
  `meeting_type` text COLLATE utf8_unicode_ci NOT NULL,
  `complete_date` date NOT NULL,
  `meeting_note` text COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` bigint(20) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_menus`
--

CREATE TABLE `dev_menus` (
  `pk_menu_id` int(10) UNSIGNED NOT NULL,
  `menu_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `menu_title` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_menus`
--

INSERT INTO `dev_menus` (`pk_menu_id`, `menu_slug`, `menu_title`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 'header-menu', 'Header Menu', '2018-08-19 16:54:26', 68, '2018-08-19 16:54:26', 68),
(2, 'footer-menu', 'Footer Menu', '2018-08-19 16:54:42', 68, '2018-08-19 16:54:42', 68);

-- --------------------------------------------------------

--
-- Table structure for table `dev_menu_assignments`
--

CREATE TABLE `dev_menu_assignments` (
  `pk_menu_assign_id` int(10) UNSIGNED NOT NULL,
  `fk_menu_id` int(10) UNSIGNED NOT NULL,
  `fk_menu_pos_id` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_menu_assignments`
--

INSERT INTO `dev_menu_assignments` (`pk_menu_assign_id`, `fk_menu_id`, `fk_menu_pos_id`) VALUES
(3, 1, 'header-primary-menu-position'),
(4, 2, 'footer-menu-position');

-- --------------------------------------------------------

--
-- Table structure for table `dev_menu_items`
--

CREATE TABLE `dev_menu_items` (
  `pk_item_id` int(10) UNSIGNED NOT NULL,
  `fk_item_id` int(10) UNSIGNED DEFAULT NULL,
  `item_sort_order` smallint(5) UNSIGNED DEFAULT NULL,
  `fk_page_id` int(10) UNSIGNED DEFAULT NULL,
  `fk_menu_id` int(10) UNSIGNED DEFAULT NULL,
  `item_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_ext_url` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `use_page_title` tinyint(1) UNSIGNED DEFAULT 1,
  `item_css_class` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_icon_class` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_menu_items`
--

INSERT INTO `dev_menu_items` (`pk_item_id`, `fk_item_id`, `item_sort_order`, `fk_page_id`, `fk_menu_id`, `item_title`, `item_ext_url`, `use_page_title`, `item_css_class`, `item_icon_class`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(1, 0, 1, 3, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-19 16:55:04', 68, '2018-08-19 16:55:04', 68),
(2, 0, 3, 7, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-19 16:55:20', 68, '2018-08-19 16:55:20', 68),
(3, 0, 4, 8, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-19 16:55:29', 68, '2018-08-19 16:55:29', 68),
(4, 0, 6, 4, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-19 16:55:41', 68, '2018-08-19 16:55:41', 68),
(5, 0, 5, 9, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-19 16:58:01', 68, '2018-08-19 16:58:01', 68),
(12, 0, 2, 12, 1, '{:en}{:en}', 'javascript:', 1, '', '', '2018-08-27 11:19:47', 68, '2018-08-27 11:19:47', 68),
(13, 0, 1, 14, 2, '{:en}{:en}', 'javascript:', 1, '', '', '2018-09-05 19:09:16', 68, '2018-09-05 19:09:16', 68),
(14, 0, 2, 15, 2, '{:en}{:en}', 'javascript:', 1, '', '', '2018-09-05 19:16:42', 68, '2018-09-05 19:16:42', 68),
(15, 0, 3, 16, 2, '{:en}{:en}', 'javascript:', 1, '', '', '2018-09-05 19:22:06', 68, '2018-09-05 19:22:06', 68),
(16, 0, 4, 17, 2, '{:en}{:en}', 'javascript:', 1, '', '', '2018-09-05 19:33:23', 68, '2018-09-05 19:33:23', 68),
(17, 0, 5, 18, 2, '{:en}{:en}', 'javascript:', 1, '', '', '2018-09-05 19:47:33', 68, '2018-09-05 19:47:33', 68);

-- --------------------------------------------------------

--
-- Table structure for table `dev_migrations`
--

CREATE TABLE `dev_migrations` (
  `pk_migration_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `is_cheated` enum('yes','no') DEFAULT NULL,
  `is_money_deducted` enum('yes','no') DEFAULT NULL,
  `is_movement_limitation` enum('yes','no') DEFAULT NULL,
  `is_kept_document` enum('yes','no') DEFAULT NULL,
  `migration_experience` text DEFAULT NULL,
  `left_port` text DEFAULT NULL,
  `preferred_country` varchar(100) DEFAULT NULL,
  `departure_date` varchar(100) DEFAULT NULL,
  `access_path` varchar(200) DEFAULT NULL,
  `transport_modes` text DEFAULT NULL,
  `migration_type` enum('regular','irregular','both') DEFAULT NULL,
  `visa_type` text DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `migration_duration` varchar(100) DEFAULT NULL,
  `migration_medias` text DEFAULT NULL,
  `migration_cost` double DEFAULT NULL,
  `agency_payment` double DEFAULT NULL,
  `migration_occupation` varchar(150) DEFAULT NULL,
  `destination_country_leave_reason` text DEFAULT NULL,
  `other_destination_country_leave_reason` text DEFAULT NULL,
  `earned_money` double DEFAULT NULL,
  `sent_money` double DEFAULT NULL,
  `spent_types` text DEFAULT NULL,
  `forced_work` enum('yes','no') DEFAULT NULL,
  `excessive_work` enum('yes','no') DEFAULT NULL,
  `employer_threatened` enum('yes','no') DEFAULT NULL,
  `final_destination` varchar(100) DEFAULT NULL,
  `migration_reasons` text DEFAULT NULL,
  `other_migration_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_migrations`
--

INSERT INTO `dev_migrations` (`pk_migration_id`, `fk_customer_id`, `is_cheated`, `is_money_deducted`, `is_movement_limitation`, `is_kept_document`, `migration_experience`, `left_port`, `preferred_country`, `departure_date`, `access_path`, `transport_modes`, `migration_type`, `visa_type`, `return_date`, `migration_duration`, `migration_medias`, `migration_cost`, `agency_payment`, `migration_occupation`, `destination_country_leave_reason`, `other_destination_country_leave_reason`, `earned_money`, `sent_money`, `spent_types`, `forced_work`, `excessive_work`, `employer_threatened`, `final_destination`, `migration_reasons`, `other_migration_reason`) VALUES
(5, 4, 'yes', 'no', 'no', 'yes', NULL, 'Port of exit from Bangladesh', 'Desired destination', '2020-08-05', NULL, NULL, 'both', 'otyher', '2020-08-05', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"Relation\",\"media_address\":\"Address\"}', NULL, NULL, 'Occupation in overseas country', 'no_job,low_salary,sickness,family_needs', 'Reasons for returning to Bangladesh', 5000, NULL, NULL, 'yes', 'yes', 'no', 'Final destination', 'higher_income,leave_home', 'other523523'),
(42, 5, '', '', '', '', NULL, 'Dhaka', 'Khulna', '2020-08-20', NULL, NULL, 'irregular', 'student', '2020-08-20', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"\",\"media_address\":\"Address\"}', NULL, NULL, 'Occupation in overseas country', '', NULL, 5000, NULL, NULL, '', '', '', 'Norayel ', '', NULL),
(43, 6, '', '', '', '', NULL, 'Dhaka', 'Khulna', '2020-08-20', NULL, NULL, 'irregular', 'student', '2020-08-20', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"\",\"media_address\":\"Address\"}', NULL, NULL, 'Occupation in overseas country', '', NULL, 5000, NULL, NULL, '', '', '', 'Norayel ', '', NULL),
(44, 7, '', '', '', '', NULL, 'Dhaka', 'Khulna', '2020-08-20', NULL, NULL, 'regular', 'student', '2020-08-20', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"\",\"media_address\":\"Address\"}', NULL, NULL, 'Carpentar', '', NULL, 0, NULL, NULL, '', '', '', 'Norayel ', '', NULL),
(45, 8, '', '', '', '', NULL, 'Dhaka', 'Khulna', '2020-08-20', NULL, NULL, 'regular', 'student', '2020-08-20', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"\",\"media_address\":\"Address\"}', NULL, NULL, 'Carpentar', '', NULL, 0, NULL, NULL, '', '', '', 'Norayel ', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_migration_documents`
--

CREATE TABLE `dev_migration_documents` (
  `pk_document_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `document_name` varchar(150) NOT NULL,
  `document_file` varchar(100) NOT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `modify_time` time DEFAULT NULL,
  `modify_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_pages`
--

CREATE TABLE `dev_pages` (
  `pk_page_id` int(10) UNSIGNED NOT NULL,
  `page_slug` text COLLATE utf8_bin NOT NULL,
  `page_sub_title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page_title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page_meta_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `page_meta_keyword` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_excerpt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `parent_page_id` int(10) UNSIGNED DEFAULT 0,
  `page_extras` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type` enum('static','dynamic') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'static',
  `page_thumbnail` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_square_thumbnail` text COLLATE utf8_bin DEFAULT NULL,
  `page_wide_thumbnail` text COLLATE utf8_bin DEFAULT NULL,
  `page_status` enum('active','inactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `is_locked` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `lock_slug` enum('true','false') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
  `page_landing_template` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_view_count` int(10) UNSIGNED DEFAULT 0,
  `page_as_category` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'no',
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `dev_pages`
--

INSERT INTO `dev_pages` (`pk_page_id`, `page_slug`, `page_sub_title`, `page_title`, `page_description`, `page_meta_description`, `page_meta_keyword`, `page_excerpt`, `parent_page_id`, `page_extras`, `page_type`, `page_thumbnail`, `page_square_thumbnail`, `page_wide_thumbnail`, `page_status`, `is_locked`, `lock_slug`, `page_landing_template`, `page_view_count`, `page_as_category`, `created_at`, `created_by`, `modified_at`, `modified_by`) VALUES
(3, 'home', '{:en}{:en}', '{:en}Home{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:46:{s:21:\"introductory_subtitle\";s:42:\"We carry a passion performance and details\";s:18:\"introductory_title\";s:48:\"The Place Where Professionalism Meets Creativity\";s:22:\"introductory_show_link\";s:3:\"yes\";s:17:\"introductory_link\";s:10:\"contact-us\";s:23:\"introductory_link_label\";s:10:\"Contact Us\";s:23:\"experience_hide_section\";s:2:\"no\";s:23:\"experience_sectionTitle\";s:32:\"Better value, better experience.\";s:18:\"experience_heading\";s:110:\"We help build identities from concept to launch. We rock at web/mobile design & development, apps and branding\";s:22:\"experience_description\";s:199:\"Lorem ipsum dolor sit amet, consectetur adipiscing. Quisque congue dui et justo vulputate, eget vehicula lorem fringilla. Vestibulum vitae edor dolor euimod, scelerisque emenim sed, consectetur nibh.\";s:20:\"experience_show_link\";s:3:\"yes\";s:15:\"experience_link\";s:10:\"contact-us\";s:21:\"experience_link_label\";s:14:\"KNOW US BETTER\";s:21:\"experience_since_year\";s:4:\"2013\";s:17:\"experience_bg_img\";s:14:\"Untitled-1.jpg\";s:19:\"goodin_hide_section\";s:2:\"no\";s:19:\"goodin_sectionTitle\";s:13:\"We\'re good in\";s:14:\"goodin_heading\";s:48:\"We help build identities from concept to launch.\";s:18:\"goodin_description\";s:82:\"We craft beautiful digital solutions for awesome clients across all the platforms.\";s:23:\"ourproduct_hide_section\";s:2:\"no\";s:23:\"ourproduct_sectionTitle\";s:12:\"Our Products\";s:18:\"ourproduct_heading\";s:35:\"Standard price for premium services\";s:22:\"ourproduct_description\";s:0:\"\";s:22:\"portfolio_hide_section\";s:2:\"no\";s:22:\"portfolio_sectionTitle\";s:15:\"What We\'ve Done\";s:17:\"portfolio_heading\";s:33:\"Some Reasons to Make Us Different\";s:21:\"portfolio_description\";s:82:\"We craft beautiful digital solutions for awesome clients across all the platforms.\";s:22:\"testimony_hide_section\";s:2:\"no\";s:22:\"testimony_sectionTitle\";s:11:\"Testimonial\";s:17:\"testimony_heading\";s:48:\"We help build identities from concept to launch.\";s:21:\"testimony_description\";s:82:\"We craft beautiful digital solutions for awesome clients across all the platforms.\";s:20:\"clients_hide_section\";s:2:\"no\";s:17:\"blog_hide_section\";s:2:\"no\";s:17:\"blog_sectionTitle\";s:13:\"We\'re good in\";s:12:\"blog_heading\";s:48:\"We help build identities from concept to launch.\";s:16:\"blog_description\";s:82:\"We craft beautiful digital solutions for awesome clients across all the platforms.\";s:23:\"getintouch_hide_section\";s:2:\"no\";s:23:\"getintouch_sectionTitle\";s:12:\"Get in Touch\";s:18:\"getintouch_heading\";s:29:\"Don\'t be shy, give us a shout\";s:22:\"getintouch_description\";s:92:\"We\'re always curious what fun projects and cool new people urk around the corners of the web\";s:18:\"getintouch_address\";s:63:\"House-29, Road-09, Flat-5B, Sector-4 Uttara, Dhaka, Bangladesh.\";s:16:\"getintouch_phone\";s:16:\"+880 1709 297766\";s:16:\"getintouch_email\";s:15:\"info@3-devs.com\";s:18:\"footer_description\";s:224:\"Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh\r\neuismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Claritas est\r\netiam processus dynamicus, qui sequitur mutationem consuetudium.\";s:16:\"footer_copyright\";s:33:\"© 3devsit 2018. Design by shoroj\";s:22:\"introductory_link_type\";s:8:\"external\";s:20:\"experience_link_type\";s:8:\"external\";}{:en}', 'static', 'homepage-bg.jpg', NULL, NULL, 'active', 'yes', 'false', 'home', 4400, 'no', '2018-08-16 12:28:59', 0, '2018-09-16 18:38:09', 68),
(4, 'contact-us', '{:en}{:en}', '{:en}Contact Us{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:8:{s:18:\"featured_left_part\";s:20:\"LET\'S WORK\r\nTOGETHER\";s:19:\"featured_right_part\";s:126:\"Let’s build your project together. All you need to do is fill out the form, and Marine will get back to you within 24 hours.\";s:12:\"info_heading\";s:23:\"Global and multilingual\";s:16:\"info_description\";s:167:\"We work with big and small companies around the world and across time-zones. Speak a few languages. So, whatever your needs, we\'re looking forward to hearing from you.\";s:12:\"info_address\";s:65:\"House:29, Road:09, Flat:5B, Sector:4, Uttara,\r\nDhaka, Bangladesh.\";s:10:\"info_phone\";s:16:\"+880 1709 297766\";s:10:\"info_email\";s:15:\"info@3-devs.com\";s:8:\"info_img\";s:8:\"con1.jpg\";}{:en}', 'static', 'banner21.png', NULL, NULL, 'active', 'yes', 'false', 'contact_page', 62, 'no', '2018-08-16 12:28:59', 0, '2018-08-29 13:27:13', 68),
(5, '404', '{:en}{:en}', '{:en}404{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}{:en}', 'static', '', NULL, NULL, 'active', 'yes', 'false', '404', 0, 'no', '2018-08-16 14:57:23', 0, '2018-08-16 14:57:23', 0),
(6, 'maintenance', '{:en}{:en}', '{:en}Maintenance{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}{:en}', 'static', '', NULL, NULL, 'active', 'yes', 'false', 'maintenance', 0, 'no', '2018-08-16 14:57:24', 0, '2018-08-16 14:57:24', 0),
(7, 'service', '{:en}{:en}', '{:en}Service{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:12:{s:19:\"featured_first_line\";s:22:\"EFFICIENCY, CREATIVITY\";s:20:\"featured_second_line\";s:13:\"AND PROXIMITY\";s:26:\"service_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:10:\"contact-us\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:15:\"clients_heading\";s:18:\"Clients & Partners\";s:19:\"clients_description\";s:123:\"We work with clients of various business types and sizes, ranging from small businesses to \r\nlarge international companies.\";s:17:\"vacancies_heading\";s:9:\"Vacancies\";s:21:\"vacancies_description\";s:219:\"We are in constant search for talents, inviting them to become the part of our multicultural team. Here at The mads, we welcome a passion for innovation, brilliant ideas and the most unusual options for their execution.\";s:30:\"vacancies_submit_resume_prompt\";s:68:\"No suitable vacancy? Send your CV and we will certainly consider it.\";s:23:\"vacancies_email_address\";s:12:\"hr@3devs.com\";s:18:\"featured_link_type\";s:8:\"internal\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'yes', 'false', 'service_team_client_vacancies_page', 290, 'no', '2018-08-16 17:49:21', 68, '2018-08-29 18:45:24', 68),
(8, 'portfolio', '{:en}{:en}', '{:en}Project{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:8:{s:19:\"featured_first_line\";s:8:\"PROJECTS\";s:20:\"featured_second_line\";s:22:\"EFFICIENCY, CREATIVITY\";s:26:\"project_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:15:\"project_heading\";s:0:\"\";s:19:\"project_description\";s:0:\"\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', 'custom-web-design-tranvo.vn_.jpg', NULL, NULL, 'active', 'yes', 'false', 'project', 95, 'no', '2018-08-19 13:36:53', 0, '2018-09-03 12:26:53', 68),
(9, 'blog', '{:en}{:en}', '{:en}Blog{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:9:{s:19:\"featured_first_line\";s:22:\"EFFICIENCY, CREATIVITY\";s:20:\"featured_second_line\";s:13:\"AND PROXIMITY\";s:23:\"blog_featured_show_link\";s:2:\"no\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:8:\"All Post\";s:17:\"blog_sectionTitle\";s:13:\"We\'re good in\";s:12:\"blog_heading\";s:48:\"We help build identities from concept to launch.\";s:16:\"blog_description\";s:82:\"We craft beautiful digital solutions for awesome clients across all the platforms.\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'blog', 222, 'no', '2018-08-19 16:57:37', 68, '2018-09-04 11:51:56', 68),
(12, 'about-us', '{:en}For the best customer satisfaction {:en}', '{:en}About Us{:en}', '{:en}<h3>Company Profile</h3>\r\n<p style=\"text-align: justify;\">3DEVs IT Ltd. is one of the best creative IT Companies in Bangladesh for developing any kind of website, software, graphic design and all kinds of IT solution. It proposes customer-leaning web-based software development services, website design &amp; more extensively provides them successfully. Either you are a small company or in management of a large business, 3DEVs IT Ltd. is always equipped to assist you to achieve an online maintenance which can send the best messages for you and for your customers.</p>\r\n<h4>Mission</h4>\r\n<p style=\"text-align: justify;\">Providing services of Information Technology and IT consultation worldwide.</p>\r\n<h4>Vision</h4>\r\n<p style=\"text-align: justify;\">We believe providing Intellectual &amp; Contemporary IT solution can ensure long-term relationship with customers and IT training will enable exploring job market globally.</p>\r\n<h4>Solution Areas</h4>\r\n<p style=\"text-align: justify;\">If you want to offer online services, information, news, products or e-commerce, 3DEVs IT Ltd. can make sure that the visitors to your site take satisfaction as interactive, user-friendly, informative and appreciative.</p>\r\n<p style=\"text-align: justify;\">We also develop the skills of specialized programmers, PHP web developers, website designers, as well as software testers that can be useful to our large customer group to develop their websites that have extra image in visitor\'s intelligence.</p>\r\n<p style=\"text-align: justify;\">The main purposes of 3DEVs IT Ltd. is web technologies are to develop and design a website which can leave a remarkable image in user\'s mind that can help its customer get targeted visitors on their websites as well as maintain them to visit their websites on regular basis.</p>\r\n<h4>Customer Relationship</h4>\r\n<p style=\"text-align: justify;\">We believe in a long-term relationship though we have to provide simple business solutions and services. Our fast services outbreak the universal sensitively and characterizes the same. To develop any kind of IT solutions we take logically too short time without compromising with quality. This is an individuality of 3DEVs IT Ltd. to serve the market with competitive products price.</p>\r\n<h4>3DEVs Working Team</h4>\r\n<ul style=\"list-style-type: square;\">\r\n<li>IT and graphics experts</li>\r\n<li>Communication experts</li>\r\n<li>Monitoring and evaluation experts</li>\r\n<li>Sales and service experts</li>\r\n<li>Training experts</li>\r\n</ul>{:en}', '{:en}Company Profile\r\n3DEVs IT Ltd. is one of the best creative IT Companies in Bangladesh for developing any kind of website, software, graphic design and all kinds of IT solution. It proposes customer-leaning web{:en}', '{:en}{:en}', '{:en}Company Profile\r\n3DEVs IT Ltd. is one of the best creative IT Companies in Bangladesh for developing any kind of website, software, graphic design and all kinds of IT solution. It proposes customer-leaning web{:en}', 0, '{:en}a:8:{s:19:\"featured_first_line\";s:8:\"About US\";s:20:\"featured_second_line\";s:34:\"For The Best Customer Satisfaction\";s:27:\"about_us_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:4:\"HOME\";s:12:\"team_heading\";s:0:\"\";s:16:\"team_description\";s:0:\"\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'about_us', 126, 'no', '2018-08-27 11:19:18', 68, '2018-09-16 15:40:09', 68),
(14, 'website-development', '{:en}Website Design and Development{:en}', '{:en}Website Design and Development{:en}', '{:en}<p><img style=\"float: right;\" src=\"../../site/contents/uploads/website%20development.png\" alt=\"website development\" width=\"359\" height=\"305\" /></p>\r\n<p style=\"text-align: left;\">3DEVs IT Ltd. is very proficient of designing and developing magnificent, stylish and standard dynamic website.&nbsp;We&nbsp;have the best&nbsp;team for website design and development which is the secret of our clients&rsquo; satisfaction. We have designed verities of websites both native and internationally which proves our capability of designing world class flawless website. We assure our clients to develop any kind of website with full satisfaction. We always give a special attention to balance between standard quality with creativity and clients&rsquo; budget. With a gorgeous website 3DEVs IT Ltd. assures it to be user friendly, trendy, competitive, informative, attractive, and responsive to any smart device.&nbsp;</p>\r\n<p>&nbsp;</p>{:en}', '{:en}3DEVs IT Ltd. is very proficient of designing and developing magnific{:en}', '{:en}{:en}', '{:en}3DEVs IT Ltd. is very proficient of designing and developing magnific{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:22:\"EFFICIENCY, CREATIVITY\";s:20:\"featured_second_line\";s:13:\"AND PROXIMITY\";s:25:\"static_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 8, 'no', '2018-09-05 19:08:47', 68, '2018-09-05 19:12:18', 68),
(15, 'e-commerce-solution', '{:en}{:en}', '{:en}E-Commerce Solution{:en}', '{:en}<p style=\"text-align: left;\">Your business can be benefited from the growing popularity of online shopping. 3DEVs IT Ltd is offering a <img style=\"float: right;\" src=\"../../site/contents/uploads/e-commerce%20website%20development.png\" alt=\"e-commerce website development\" width=\"324\" height=\"275\" /></p>\r\n<p style=\"text-align: left;\">standard&nbsp;e-commerce solution. We are offering every module you need to sell products, track customers, process transactions, keep a record and so much more such as shopping carts, product catalogs, API\'s and online payment integration. 3DEVs IT Ltd. is empowered to satisfy our clients with standard and professional website designing which is fully featured and functioned for e-commerce that can accommodate with their desire and budget. This is how we are helping our clients to achieve their goal even at low cost.</p>\r\n<p style=\"text-align: left;\">Our e-commerce solution will offer you best benefits, world class design and development making the website most striking, user friendly, easily manageable byCMS at a very competitive price.</p>{:en}', '{:en}Your business can be benefited from the growing popularity of online shopping. 3DEVs IT Ltd is offering a {:en}', '{:en}{:en}', '{:en}Your business can be benefited from the growing popularity of online shopping. 3DEVs IT Ltd is offering a {:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:22:\"FOR THE BEST CUSTOMER \";s:20:\"featured_second_line\";s:13:\"SATISFACTION \";s:25:\"static_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 6, 'no', '2018-09-05 19:16:29', 68, '2018-09-05 19:17:32', 68),
(16, 'magazine-news-portal', '{:en}{:en}', '{:en}Magazine & News Portal{:en}', '{:en}<p>Now a day&rsquo;s people are getting conscious about our social activities. But in this busy life it&rsquo;s hard to make a specific<img style=\"float: right;\" src=\"../../site/contents/uploads/news%20portal.png\" alt=\"news portal\" width=\"320\" height=\"272\" /> time to read news or literature. So, we the smart people of digital World already find our way out. Its online newspaper and magazine. People don&rsquo;t need to make time for all these now rather they can read them online when get free time. Online newspaper and magazine and newspaper become very popular in current days. Now we can have all the updates with in shortest possible time!</p>\r\n<p>3DEVs IT Ltd, as one of best creative IT companies is Bangladesh feels to help those people who are dedicated in this noble cause to distribute news for all, so we are offering online news portal package, and online magazine package at very reasonable cost. Now anyone can be the editor/writer/journalist of their own news portal.&nbsp;&nbsp;</p>\r\n<p>&nbsp;</p>{:en}', '{:en}Now a day&amp;rsquo;s people are getting conscious about our social activities. But in this busy life it&amp;rsquo;s hard to make a specific{:en}', '{:en}{:en}', '{:en}Now a day&amp;rsquo;s people are getting conscious about our social activities. But in this busy life it&amp;rsquo;s hard to make a specific{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:22:\"FOR THE BEST CUSTOMER \";s:20:\"featured_second_line\";s:13:\"SATISFACTION \";s:25:\"static_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 2, 'no', '2018-09-05 19:21:50', 68, '2018-09-05 19:22:37', 68),
(17, 'mobile-apps-development', '{:en}{:en}', '{:en}Mobile Apps Development{:en}', '{:en}<p>MOBILE APPLICATIONS- android, iOS, Smart Phones, Tablets. They&rsquo;re everywhere. You need to be there, too. We can turn your Website into a responsive mobile application. Or, we can take your ideas and turn them into brilliant mobile apps.<img style=\"float: right;\" src=\"../../site/contents/uploads/App-Development.png\" alt=\"App-Development\" /></p>\r\n<h4>Android</h4>\r\n<p><br />Android App Development is more of a moving target than ever before, featuring more variations in operating systems, devices, and new functionality than any other platform. With updates such as the Android 4.4 KitKat operating system, you need a forward thinking development team to successfully balance new requirements and powerful functionality with the simplicity of user experience. 3DEVs IT&rsquo;s Android development team routinely turns out full-featured apps that leverage modern styles, patterns and gestures to amplify the user adoption rate. 3DEVs IT, a well-recognized firm in Android app development, development team has an expert level of experience in all programming languages from Java to CSS, HTML, and Objective C. When you partner with 3DEVs IT, you partner with a mobile team driven by your continued success.</p>\r\n<h4>iOS</h4>\r\n<p><br />iOS is the most popular platform for mobile app development. With the introduction of Apple&rsquo;s iOS 7 update, it is more important than ever to have an innovative development team behind your custom app. By partnering with 3DEVs IT, you can take advantage of cutting edge mobile design and development practices, generating the highest return on your investment.</p>\r\n<h4>Windows</h4>\r\n<p><br />With mobile activity increasing rapidly every year, and mobile e-commerce sales more than tripling over the last two years, it is more critical than ever to have a website geared toward mobile. From smartphones, tablets to desktop computers, 3DEVs IT&rsquo;s web design team specializes in developing creative, and strategic websites that work flawlessly on every device. Through mobile and responsive design architecture, 3DEVs IT can find the most adaptive solution for your business. With Big % of consumers expecting businesses to cater to their mobile devices, having a mobile website is more critical than ever. 3DEVs IT&rsquo;s mobile development team blends the power behind your conventional desktop website with the convenience of mobile devices. By partnering with 3DEVs IT, you can create a new avenue to target new consumers, and turn your on-the-go website visitors into paying customers.</p>{:en}', '{:en}MOBILE APPLICATIONS- android, iOS, Smart Phones, Tablets. They&amp;rsquo;re everywhere. You need to be there, too. We can turn your Website into a responsive mobile application. Or, we can take your ideas and turn them into brilliant mobile apps.{:en}', '{:en}{:en}', '{:en}MOBILE APPLICATIONS- android, iOS, Smart Phones, Tablets. They&amp;rsquo;re everywhere. You need to be there, too. We can turn your Website into a responsive mobile application. Or, we can take your ideas and turn them into brilliant mobile apps.{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:22:\"FOR THE BEST CUSTOMER \";s:20:\"featured_second_line\";s:13:\"SATISFACTION \";s:25:\"static_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', '02.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 5, 'no', '2018-09-05 19:32:37', 68, '2018-09-05 19:32:56', 68),
(18, 'erp-solution', '{:en}{:en}', '{:en}ERP Solution{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:22:\"FOR THE BEST CUSTOMER \";s:20:\"featured_second_line\";s:13:\"SATISFACTION \";s:25:\"static_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', 'Untitled-1.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 0, 'no', '2018-09-05 19:36:15', 68, '2018-09-05 19:36:15', 68),
(19, 'services', '{:en}{:en}', '{:en}Services{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:12:\"Our Services\";s:20:\"featured_second_line\";s:28:\"Our Services Are Listed Here\";s:27:\"services_featured_show_link\";s:3:\"yes\";s:13:\"featured_link\";s:10:\"contact-us\";s:19:\"featured_link_label\";s:10:\"Contact Us\";s:18:\"featured_link_type\";s:8:\"internal\";}{:en}', 'static', 'custom-web-design-tranvo.vn_.jpg', NULL, NULL, 'active', 'no', 'false', 'service', 9, 'no', '2018-09-16 14:12:40', 68, '2018-09-16 14:28:42', 68),
(20, 'temp-page', '{:en}Temp Page{:en}', '{:en}Temp Page{:en}', '{:en}<p><strong>sadasda</strong></p>\r\n<p>adasd</p>{:en}', '{:en}sadasda\r\nadasd{:en}', '{:en}{:en}', '{:en}sadasda\r\nadasd{:en}', 0, '{:en}a:6:{s:19:\"featured_first_line\";s:0:\"\";s:20:\"featured_second_line\";s:0:\"\";s:25:\"static_featured_show_link\";s:2:\"no\";s:13:\"featured_link\";s:0:\"\";s:19:\"featured_link_label\";s:0:\"\";s:18:\"featured_link_type\";s:8:\"external\";}{:en}', 'static', 'Mobile-Marketing.png', NULL, NULL, 'active', 'no', 'false', 'static_page', 0, 'no', '2018-10-08 15:47:32', 68, '2018-10-08 15:47:32', 68),
(21, 'Contact-Us', '{:en}{:en}', '{:en}Contact Us{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}a:8:{s:18:\"featured_left_part\";s:20:\"LET\'S WORK\r\nTOGETHER\";s:19:\"featured_right_part\";s:126:\"Let’s build your project together. All you need to do is fill out the form, and Marine will get back to you within 24 hours.\";s:12:\"info_heading\";s:23:\"Global and multilingual\";s:16:\"info_description\";s:167:\"We work with big and small companies around the world and across time-zones. Speak a few languages. So, whatever your needs, we\'re looking forward to hearing from you.\";s:12:\"info_address\";s:64:\"House:29, Road:09, Flat:5B, Sector:4, Uttara, Dhaka, Bangladesh.\";s:10:\"info_phone\";s:16:\"+880 1709 297766\";s:10:\"info_email\";s:15:\"info@3-devs.com\";s:8:\"info_img\";s:8:\"con1.jpg\";}{:en}', 'static', 'banner21.png', '', '', 'active', 'yes', 'false', 'contact_page', 3, 'no', '2018-11-14 12:36:22', 0, '2018-11-14 13:06:02', 68),
(22, 'recruiters', '{:en}{:en}', '{:en}Recruiters{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}{:en}', 'static', '', '', '', 'active', 'yes', 'false', 'recruiters', 0, 'no', '2019-03-27 16:43:34', 0, '2019-03-27 16:43:34', 0),
(23, 'job_seekers', '{:en}{:en}', '{:en}Job Seekers{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', '{:en}{:en}', 0, '{:en}{:en}', 'static', '', '', '', 'active', 'yes', 'false', 'job_seekers', 0, 'no', '2019-03-27 16:43:34', 0, '2019-03-27 16:43:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dev_page_content_types`
--

CREATE TABLE `dev_page_content_types` (
  `rel_id` int(10) UNSIGNED NOT NULL,
  `fk_page_id` int(10) UNSIGNED DEFAULT NULL,
  `fk_content_type_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_page_content_types`
--

INSERT INTO `dev_page_content_types` (`rel_id`, `fk_page_id`, `fk_content_type_id`) VALUES
(32, 8, 'Project'),
(49, 9, 'article'),
(50, 9, 'Audio'),
(51, 9, 'gallery'),
(52, 9, 'video'),
(60, 19, 'Service');

-- --------------------------------------------------------

--
-- Table structure for table `dev_projects`
--

CREATE TABLE `dev_projects` (
  `pk_project_id` int(10) UNSIGNED NOT NULL,
  `project_name` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_short_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_funded_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `project_status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_target` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dev_projects`
--

INSERT INTO `dev_projects` (`pk_project_id`, `project_name`, `project_short_name`, `project_code`, `project_funded_by`, `project_start`, `project_end`, `project_status`, `project_target`, `create_date`, `create_time`, `create_by`, `update_date`, `update_time`, `update_by`) VALUES
(1, 'Bangladesh: Sustainable Reintegration and Improved Migration Governance Project', 'Prottasha', '626', 'European Union, Implemented by IOM in partnership with BRAC', '2017-06-01', '2020-11-30', 'active', 'Aims to ensure that 3,000 returnee migrants, primarily from Europe, other destinations and transit countries are sustainably reintegrated', '2019-04-17', '14:44:15', 1, '2019-09-03', '16:15:27', 1),
(2, 'Socio Economic Reintegration of Returnee Migrant Workers of Bangladesh', 'Anuprarona - Denmark', '659', 'Embassy of Denmark', '2018-01-01', '2019-12-31', 'active', 'Aims to ensure 3,000 returnee men and women migrants improved social/economic well-being.', '2019-04-17', '14:45:02', 1, '2019-09-03', '16:15:22', 1),
(4, 'Emergency Support for Vulnerable Returnee Migrants Project', 'Emergency Support for Vulnerable Returnee Migrants', '673', 'BRAC', '2018-06-01', '2020-05-31', 'active', '2000 returnees receive instant support after arrival', '2019-04-17', '14:46:25', 1, '2019-09-03', '16:15:15', 1),
(5, 'Sustainable Reintegration of Bangladesh Returnees Project', 'ERRIN', '694', 'European Return and Reintegration Network (ERRIN), co funded by EU', '2018-08-31', '2022-07-31', 'active', 'Provide economic reintegration service to 700 EU returnees.', '2019-04-17', '14:47:35', 1, '2019-09-03', '16:15:09', 1),
(6, 'Socio Economic Reintegration of Returnee Migrant Workers of Bangladesh', 'Anuprarona - SDC', '707', 'Swiss Agency for Development and Cooperation (SDC), Embassy of Switzerland', '2019-01-01', '2019-12-31', 'active', 'Aims to ensure 4,380 returnee men and women migrants improved Psycho-social, social and economic well being.', '2019-04-21', '09:37:48', 1, '2019-09-03', '16:13:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_completions`
--

CREATE TABLE `dev_psycho_completions` (
  `pk_psycho_completion_id` bigint(20) NOT NULL,
  `fk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `is_completed` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `dropout_reason` text COLLATE utf8_unicode_ci NOT NULL,
  `review_session` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_evaluation` text COLLATE utf8_unicode_ci NOT NULL,
  `counselling_impact` text COLLATE utf8_unicode_ci NOT NULL,
  `client_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `counsellor_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `required_session` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_completions`
--

INSERT INTO `dev_psycho_completions` (`pk_psycho_completion_id`, `fk_psycho_support_id`, `fk_customer_id`, `is_completed`, `dropout_reason`, `review_session`, `final_evaluation`, `counselling_impact`, `client_comments`, `counsellor_comments`, `required_session`, `entry_date`) VALUES
(1, 0, 1, 'yes', '', '', '', '', '', '', 'yes', '2020-07-30'),
(2, 0, 4, 'no', 'Reason for drop out from the Counseling Session', 'Review of Counselling Session', 'Final Evaluation', '', 'Comments of the Client', 'Counsellor’s Comment', 'yes', '2020-08-07');

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_counsellors`
--

CREATE TABLE `dev_psycho_counsellors` (
  `pk_counsellor_support_id` bigint(20) NOT NULL,
  `fk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date NOT NULL,
  `entry_time` time NOT NULL,
  `home_visit` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `issues_discussed` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_family_present` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `how_many` int(3) DEFAULT NULL,
  `counsellor_note` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_counsellors`
--

INSERT INTO `dev_psycho_counsellors` (`pk_counsellor_support_id`, `fk_psycho_support_id`, `fk_customer_id`, `entry_date`, `entry_time`, `home_visit`, `issues_discussed`, `is_family_present`, `how_many`, `counsellor_note`) VALUES
(1, 1, 1, '2019-06-11', '03:32:00', 'yes', 'Referral services', 'no', NULL, NULL),
(2, 3, 38, '2019-07-23', '10:21:00', 'no', 'Referral services', 'no', NULL, NULL),
(3, 6, 80, '2019-09-02', '11:48:00', 'yes', '', 'yes', 6, NULL),
(4, 10, 27, '2019-09-02', '12:06:00', 'yes', '', '', NULL, NULL),
(5, 1, 27, '2019-09-09', '05:18:00', 'yes', 'Referral services', 'no', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_evaluations`
--

CREATE TABLE `dev_psycho_evaluations` (
  `pk_psycho_evaluation_id` bigint(20) NOT NULL,
  `fk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date NOT NULL,
  `sleepwell_night` tinyint(4) NOT NULL,
  `happy_family` tinyint(4) NOT NULL,
  `enjoy_life` tinyint(4) NOT NULL,
  `visit_neighbors` tinyint(4) NOT NULL,
  `mental_health` tinyint(4) NOT NULL,
  `family_tension` tinyint(4) NOT NULL,
  `secure_feeling` tinyint(4) NOT NULL,
  `feeling_free` tinyint(4) NOT NULL,
  `family_behave` tinyint(4) NOT NULL,
  `positive_impact` tinyint(4) NOT NULL,
  `evaluated_score` int(11) NOT NULL,
  `review_remarks` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_evaluations`
--

INSERT INTO `dev_psycho_evaluations` (`pk_psycho_evaluation_id`, `fk_psycho_support_id`, `fk_customer_id`, `entry_date`, `sleepwell_night`, `happy_family`, `enjoy_life`, `visit_neighbors`, `mental_health`, `family_tension`, `secure_feeling`, `feeling_free`, `family_behave`, `positive_impact`, `evaluated_score`, `review_remarks`) VALUES
(1, 0, 99, '2019-09-15', 5, 5, 5, 5, 0, 0, 0, 0, 0, 0, 20, 'Not Reintegrated');

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_family_counselling`
--

CREATE TABLE `dev_psycho_family_counselling` (
  `pk_psycho_family_counselling_id` bigint(20) UNSIGNED NOT NULL,
  `fk_psycho_support_id` bigint(20) DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time NOT NULL,
  `session_place` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `members_counseled` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_family_counselling`
--

INSERT INTO `dev_psycho_family_counselling` (`pk_psycho_family_counselling_id`, `fk_psycho_support_id`, `fk_customer_id`, `entry_date`, `entry_time`, `session_place`, `session_comments`, `members_counseled`) VALUES
(4, NULL, 0, '2020-07-30', '10:01:30', '453', '', '3523'),
(5, NULL, 1, '2020-07-30', '10:00:45', 'Place of Family Counseling', 'Comments/Remarks', 'No of Family Members Counseled'),
(6, NULL, 0, '2020-07-30', '10:15:30', 'fvdsfsdfsadfasfa', 'sdfsdf', 'vcxvxc'),
(7, NULL, 0, '2020-07-01', '10:27:45', '', '', ''),
(8, NULL, 1, '2020-07-30', '14:14:00', '', 'EDIT', ''),
(9, NULL, 1, '2020-07-30', '14:16:15', '', 'FASFAS EDIT', ''),
(10, NULL, 4, '2020-05-05', '20:08:20', 'Place of Family Counseling', 'Comments/Remarks', '4');

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_followups`
--

CREATE TABLE `dev_psycho_followups` (
  `pk_psycho_followup_id` bigint(20) UNSIGNED NOT NULL,
  `fk_psycho_support_id` bigint(20) DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time NOT NULL,
  `followup_comments` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_followups`
--

INSERT INTO `dev_psycho_followups` (`pk_psycho_followup_id`, `fk_psycho_support_id`, `fk_customer_id`, `entry_date`, `entry_time`, `followup_comments`) VALUES
(1, NULL, 1, '1970-01-01', '00:00:00', ''),
(2, NULL, 1, '2020-07-30', '14:10:30', ''),
(3, NULL, 1, '2020-07-30', '14:33:15', 'ERFSDFRDS'),
(4, NULL, 4, '2029-11-22', '19:00:22', 'Followup Comment');

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_sessions`
--

CREATE TABLE `dev_psycho_sessions` (
  `pk_psycho_session_id` bigint(20) UNSIGNED NOT NULL,
  `fk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time DEFAULT NULL,
  `session_place` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `session_comments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `initial_plan` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `next_time` time DEFAULT NULL,
  `complete_date` date DEFAULT NULL,
  `activities_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `complete_time` time DEFAULT NULL,
  `is_complete` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropout_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_thought` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `impact_support` enum('anxiety','depression') COLLATE utf8_unicode_ci DEFAULT NULL,
  `followup_comments` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_sessions`
--

INSERT INTO `dev_psycho_sessions` (`pk_psycho_session_id`, `fk_psycho_support_id`, `fk_customer_id`, `entry_date`, `entry_time`, `session_place`, `session_comments`, `initial_plan`, `next_time`, `complete_date`, `activities_description`, `next_date`, `complete_time`, `is_complete`, `dropout_reason`, `final_thought`, `impact_support`, `followup_comments`) VALUES
(1, 0, 1, '2020-07-30', '14:20:15', '', 'Comments', NULL, NULL, NULL, 'Description of Activities', '2020-07-30', NULL, NULL, NULL, NULL, NULL, ''),
(2, 0, 4, '2024-06-04', '05:00:20', '', 'Comments', NULL, NULL, NULL, 'Description of Activities', '2020-08-31', NULL, NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_supports`
--

CREATE TABLE `dev_psycho_supports` (
  `pk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) UNSIGNED NOT NULL,
  `fk_project_id` bigint(20) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `first_meeting` date NOT NULL,
  `support_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `support_place` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `problem_identified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `problem_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `initial_plan` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `problem_history` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `required_session` int(10) DEFAULT NULL,
  `is_family_counceling` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `family_counseling` int(10) NOT NULL,
  `session_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_duration` double DEFAULT NULL,
  `session_place` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_requirements` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `assesment_score` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `reffer_to` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `referr_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason_for_reffer` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_reason_for_reffer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_home_visit` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `issue_discussed` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_issue_discussed` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_supports`
--

INSERT INTO `dev_psycho_supports` (`pk_psycho_support_id`, `fk_customer_id`, `fk_project_id`, `entry_date`, `first_meeting`, `support_note`, `support_place`, `sub_type`, `problem_identified`, `problem_description`, `initial_plan`, `problem_history`, `required_session`, `is_family_counceling`, `family_counseling`, `session_number`, `session_duration`, `session_place`, `other_requirements`, `assesment_score`, `reffer_to`, `referr_address`, `contact_number`, `reason_for_reffer`, `other_reason_for_reffer`, `is_home_visit`, `issue_discussed`, `other_issue_discussed`) VALUES
(1, 27, 1, '2019-09-09', '2019-09-09', 't', 'y', 'psycho_social_counselling', 'problems_socialization', 'g', NULL, 'g', 6, 'yes', 8, NULL, 7, '9', '6', 'r', 'u', 't', '98', 'trauma_counselling', NULL, 'yes', NULL, NULL),
(2, 1, 0, NULL, '2020-07-29', NULL, NULL, NULL, 'Suicidal Ideation/Thought', '', '', NULL, NULL, '', 0, '', 0, '', '', NULL, '', '', '', '', NULL, 'yes', '', NULL),
(3, 4, 0, NULL, '2020-08-01', NULL, NULL, NULL, 'Depression', 'Description of the problem', 'Initial Plan', NULL, NULL, 'yes', 5, '5', 5, 'twegsdg', 'Other Requirements', NULL, 'Referrals', 'Referrals444', 'Referrals333', 'Trauma Counselling,on', 'Reason for Referral', 'yes', 'Referral Services,Other', 'Issues Discussed');

-- --------------------------------------------------------

--
-- Table structure for table `dev_pull_notification`
--

CREATE TABLE `dev_pull_notification` (
  `pk_noti_id` int(10) UNSIGNED NOT NULL,
  `noti_title` text CHARACTER SET utf8 NOT NULL,
  `noti_description` text CHARACTER SET utf8 DEFAULT NULL,
  `noti_url` text CHARACTER SET utf8 DEFAULT NULL,
  `fk_user_id` int(10) UNSIGNED NOT NULL,
  `noti_status` enum('new','view','old') CHARACTER SET utf8 NOT NULL DEFAULT 'new',
  `noti_new_tab` tinyint(1) DEFAULT 0,
  `noti_create_time` datetime NOT NULL,
  `noti_created_by` int(10) UNSIGNED NOT NULL,
  `noti_modify_time` datetime NOT NULL,
  `noti_modified_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_reintegration_plan`
--

CREATE TABLE `dev_reintegration_plan` (
  `pk_reintegration_plan_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `reintegration_plan` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `reintegration_financial_service` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_requested` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_service_requested` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_protection` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `security_measure` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_requested_note` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_reintegration_plan`
--

INSERT INTO `dev_reintegration_plan` (`pk_reintegration_plan_id`, `fk_customer_id`, `reintegration_plan`, `reintegration_financial_service`, `service_requested`, `other_service_requested`, `social_protection`, `security_measure`, `service_requested_note`) VALUES
(1, 333, 'psychosocial', NULL, 'others,Education,Job Placement,Social Protection Schemes,Special Security Measures', NULL, NULL, NULL, ''),
(2, NULL, NULL, NULL, 'Child Care,Job Placement,Legal Services,Psychosocial Support', NULL, NULL, NULL, ''),
(3, 1, NULL, NULL, 'Child Care,Education', NULL, NULL, NULL, ''),
(4, 4, NULL, 'Other Financial Service', 'Child Care,Education,Financial Service', 'Other Services Requested', 'dqw', 'Special Security Measures', 'Note');

-- --------------------------------------------------------

--
-- Table structure for table `dev_reintegration_satisfaction_scale`
--

CREATE TABLE `dev_reintegration_satisfaction_scale` (
  `pk_satisfaction_scale` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `satisfied_assistance` int(2) DEFAULT NULL,
  `satisfied_counseling` int(2) DEFAULT NULL,
  `satisfied_economic` int(2) DEFAULT NULL,
  `satisfied_social` int(2) DEFAULT NULL,
  `satisfied_community` int(2) DEFAULT NULL,
  `satisfied_reintegration` int(2) DEFAULT NULL,
  `total_score` int(3) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_reintegration_satisfaction_scale`
--

INSERT INTO `dev_reintegration_satisfaction_scale` (`pk_satisfaction_scale`, `fk_customer_id`, `satisfied_assistance`, `satisfied_counseling`, `satisfied_economic`, `satisfied_social`, `satisfied_community`, `satisfied_reintegration`, `total_score`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 4, 1, 2, 3, 4, 2, 4, 16, NULL, NULL, NULL, '20:09:19', '2020-08-05', 19),
(3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_sharing_sessions`
--

CREATE TABLE `dev_sharing_sessions` (
  `pk_sharing_session_id` bigint(20) NOT NULL,
  `traning_date` date DEFAULT NULL,
  `traning_name` varchar(150) DEFAULT NULL,
  `evaluator_profession` text DEFAULT NULL,
  `satisfied_training` int(2) DEFAULT NULL,
  `satisfied_supports` int(2) DEFAULT NULL,
  `satisfied_facilitation` int(2) DEFAULT NULL,
  `outcome_training` int(2) DEFAULT NULL,
  `trafficking_law` int(2) DEFAULT NULL,
  `policy_process` int(2) DEFAULT NULL,
  `all_contents` varchar(160) DEFAULT NULL,
  `recommendation` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_sharing_sessions`
--

INSERT INTO `dev_sharing_sessions` (`pk_sharing_session_id`, `traning_date`, `traning_name`, `evaluator_profession`, `satisfied_training`, `satisfied_supports`, `satisfied_facilitation`, `outcome_training`, `trafficking_law`, `policy_process`, `all_contents`, `recommendation`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(2, '2020-08-10', 'Training Name', 'NGO', 5, 5, 5, 5, 5, 5, '5', 'Recommandation', '17:06:59', '2020-08-10', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_social_evaluations`
--

CREATE TABLE `dev_social_evaluations` (
  `pk_social_evaluation_id` bigint(20) NOT NULL,
  `fk_social_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `government_programme` tinyint(4) DEFAULT NULL,
  `public_services` tinyint(4) DEFAULT NULL,
  `access_social` tinyint(4) DEFAULT NULL,
  `family_treat` tinyint(4) DEFAULT NULL,
  `friends_treat` tinyint(4) DEFAULT NULL,
  `relatives_treat` tinyint(4) DEFAULT NULL,
  `community_treat` tinyint(4) DEFAULT NULL,
  `important_family` tinyint(4) DEFAULT NULL,
  `adapted_community` tinyint(4) DEFAULT NULL,
  `pleased_society` tinyint(4) DEFAULT NULL,
  `evaluated_score` int(11) NOT NULL,
  `review_remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_social_evaluations`
--

INSERT INTO `dev_social_evaluations` (`pk_social_evaluation_id`, `fk_social_support_id`, `fk_customer_id`, `entry_date`, `government_programme`, `public_services`, `access_social`, `family_treat`, `friends_treat`, `relatives_treat`, `community_treat`, `important_family`, `adapted_community`, `pleased_society`, `evaluated_score`, `review_remarks`) VALUES
(1, 0, 99, '2019-09-15', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'Not Reintegrated');

-- --------------------------------------------------------

--
-- Table structure for table `dev_social_followups`
--

CREATE TABLE `dev_social_followups` (
  `pk_social_review_id` bigint(20) UNSIGNED NOT NULL,
  `fk_social_support_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_social_supports`
--

CREATE TABLE `dev_social_supports` (
  `pk_social_support_id` bigint(20) NOT NULL,
  `fk_project_id` bigint(20) DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `support_received` text DEFAULT NULL,
  `support_from_whom` text DEFAULT NULL COMMENT 'Tag',
  `is_migration_forum_member` enum('yes','no') DEFAULT NULL,
  `is_participated_show` enum('yes','no') DEFAULT NULL,
  `learn_show` text DEFAULT NULL,
  `is_per_community_video` enum('yes','no') DEFAULT NULL,
  `learn_video` text DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reintegration_economic` text DEFAULT NULL,
  `other_reintegration_economic` text DEFAULT NULL,
  `soical_date` date DEFAULT NULL,
  `medical_date` date DEFAULT NULL,
  `date_housing` date DEFAULT NULL,
  `date_legal` date DEFAULT NULL,
  `attended_ipt` enum('yes','no') NOT NULL,
  `support_referred` text DEFAULT NULL,
  `other_support_referred` text DEFAULT NULL,
  `date_education` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_social_supports`
--

INSERT INTO `dev_social_supports` (`pk_social_support_id`, `fk_project_id`, `fk_customer_id`, `entry_date`, `support_received`, `support_from_whom`, `is_migration_forum_member`, `is_participated_show`, `learn_show`, `is_per_community_video`, `learn_video`, `end_date`, `reintegration_economic`, `other_reintegration_economic`, `soical_date`, `medical_date`, `date_housing`, `date_legal`, `attended_ipt`, `support_referred`, `other_support_referred`, `date_education`) VALUES
(2, NULL, 1, NULL, NULL, NULL, NULL, NULL, '', '', '', NULL, 'Education', NULL, '2020-07-30', '2020-07-30', '2020-07-30', '2020-07-30', '', '', NULL, '2020-07-30'),
(3, NULL, 4, NULL, NULL, NULL, NULL, NULL, 'kjh', 'yes', 'hnjhvhgv', NULL, 'Social Protection Schemes(Place to access to public services & Social Protection),Private/ NGO,Legal Services,on', 'Types of Economic Support', '2020-08-03', '2020-08-02', '2020-08-02', '2020-08-02', 'yes', 'Social Protection Schemes(Place to access to public services & Social Protection),District/Upazila Youth Development office,Legal Aid', 'Types of Support Referred for', '2020-08-02');

-- --------------------------------------------------------

--
-- Table structure for table `dev_supports`
--

CREATE TABLE `dev_supports` (
  `pk_support_id` bigint(20) NOT NULL,
  `fk_project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `division_name` text DEFAULT NULL,
  `district_name` text DEFAULT NULL,
  `sub_district_name` text DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `fk_support_id` bigint(20) NOT NULL,
  `support_status` enum('ongoing','completed','dropout','evaluated') NOT NULL,
  `support_name` varchar(200) NOT NULL,
  `sub_type` varchar(200) NOT NULL,
  `total_session` int(2) NOT NULL,
  `family_counselling` int(2) NOT NULL,
  `para_counsellor` int(3) NOT NULL,
  `total_evaluation` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_support_history`
--

CREATE TABLE `dev_support_history` (
  `pk_history_id` bigint(20) NOT NULL,
  `fk_customer_id` varchar(255) DEFAULT NULL,
  `fk_support_id` varchar(255) DEFAULT NULL,
  `problem_identified` varchar(255) DEFAULT NULL,
  `problem_description` varchar(255) DEFAULT NULL,
  `problem_history` varchar(255) DEFAULT NULL,
  `required_session` varchar(255) DEFAULT NULL,
  `session_duration` varchar(255) DEFAULT NULL,
  `session_place` varchar(255) DEFAULT NULL,
  `other_requirements` varchar(255) DEFAULT NULL,
  `assessment_score` varchar(255) DEFAULT NULL,
  `referred_to` varchar(255) DEFAULT NULL,
  `referred_address` varchar(255) DEFAULT NULL,
  `referred_contact_no` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_tags`
--

CREATE TABLE `dev_tags` (
  `pk_tag_id` int(10) UNSIGNED NOT NULL,
  `fk_tag_id` int(10) UNSIGNED DEFAULT 0,
  `tag_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag_image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fk_tag_group_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_tags`
--

INSERT INTO `dev_tags` (`pk_tag_id`, `fk_tag_id`, `tag_title`, `tag_description`, `tag_image`, `tag_slug`, `fk_tag_group_id`) VALUES
(1, 0, '{:en}USa{:en}', '{:en}{:en}', '', 'usa', 3),
(2, 0, '{:en}IT{:en}', '{:en}{:en}', '', 'it', 4),
(3, 0, '{:en}Germany{:en}', '{:en}{:en}', '', 'germany', 3),
(4, 0, '{:en}Qatar{:en}', '{:en}{:en}', '', 'qatar', 3),
(5, 0, '{:en}House Keeping{:en}', '{:en}{:en}', '', 'house-keeping', 4),
(6, 0, '{:en}Singapore{:en}', '{:en}{:en}', '', 'singapore', 3),
(7, 0, '{:en}Malaysia{:en}', '{:en}{:en}', '', 'malaysia', 3),
(8, 0, '{:en}Cleaning{:en}', '{:en}{:en}', '', 'cleaning', 4),
(9, 0, '{:en}Farmer{:en}', '{:en}{:en}', '', 'farmer', 4),
(10, 0, '{:en}sdfds{:en}', '{:en}{:en}', '', 'sdfds', 3),
(11, 17, '{:en}Portugal{:en}', '{:en}{:en}', '', 'portugal', 3),
(12, 0, '{:en}Dubai{:en}', '{:en}{:en}', '', 'dubai', 3),
(13, 0, '{:en}Chicago{:en}', '{:en}{:en}', '', 'chicago', 3),
(14, 0, '{:en}Driver{:en}', '{:en}{:en}', '', 'driver', 4),
(15, 0, '{:en}Middle East{:en}', '{:en}{:en}', '', 'middle-east', 3),
(16, 0, '{:en}Cook{:en}', '{:en}{:en}', '', 'cook', 4),
(17, 0, '{:en}Mechanic{:en}', '{:en}{:en}', '', 'mechanic', 4),
(18, 0, '{:en}Plumber{:en}', '{:en}{:en}', '', 'plumber', 4),
(19, 0, '{:en}Massionary{:en}', '{:en}{:en}', '', 'massionary', 4),
(20, 0, '{:en}Transporter{:en}', '{:en}{:en}', '', 'transporter', 4),
(21, 0, '{:en}Farmer {:en}', '{:en}{:en}', '', 'farmer-1', 4),
(22, 0, '{:en}Canada{:en}', '{:en}{:en}', '', 'canada', 3),
(23, 0, '{:en}Automobile Engineer{:en}', '{:en}{:en}', '', 'automobile-engineer', 4),
(24, 0, '{:en}America{:en}', '{:en}{:en}', '', 'america', 3),
(25, 0, '{:en}Russia{:en}', '{:en}{:en}', '', 'russia', 3),
(26, 0, '{:en}Doctor{:en}', '{:en}{:en}', '', 'doctor', 4);

-- --------------------------------------------------------

--
-- Table structure for table `dev_tags_group`
--

CREATE TABLE `dev_tags_group` (
  `pk_tag_group_id` int(10) UNSIGNED NOT NULL,
  `tag_group_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tag_group_slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_hierarchical` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_tags_group`
--

INSERT INTO `dev_tags_group` (`pk_tag_group_id`, `tag_group_title`, `tag_group_slug`, `is_hierarchical`) VALUES
(2, 'tags', 'tags', 0),
(3, 'migration_locations', 'migration_locations', 0),
(4, 'occupations', 'occupations', 0);

-- --------------------------------------------------------

--
-- Table structure for table `dev_targets`
--

CREATE TABLE `dev_targets` (
  `pk_target_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) NOT NULL,
  `target_month` varchar(30) DEFAULT NULL,
  `target_name` varchar(150) DEFAULT NULL,
  `target_value` varchar(200) DEFAULT NULL,
  `achievement_value` varchar(150) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_targets`
--

INSERT INTO `dev_targets` (`pk_target_id`, `fk_branch_id`, `target_month`, `target_name`, `target_value`, `achievement_value`, `remark`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(6, 0, 'August', 'Target Name', 'Target Value', 'Achievement', 'Remark', '16:11:25', '2020-08-12', 1, '16:19:20', '2020-08-12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_trainings`
--

CREATE TABLE `dev_trainings` (
  `pk_training_id` bigint(20) NOT NULL,
  `beneficiary_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `training_name` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_trainings`
--

INSERT INTO `dev_trainings` (`pk_training_id`, `beneficiary_id`, `name`, `gender`, `profession`, `training_name`, `address`, `mobile`, `age`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 'Beneficiary ID', 'Participant Name', 'on', 'Profession', 'Training Name', 'Address', 'Mobile', 'Age', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Beneficiary ID', 'Participant Name', '', '', '', '', '', '', '11:14:33', '2020-08-10', 1, NULL, NULL, NULL),
(3, 'Beneficiary ID', 'Participant Name', 'female', '', '', '', '', '', '11:14:46', '2020-08-10', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_users`
--

CREATE TABLE `dev_users` (
  `pk_user_id` int(10) UNSIGNED NOT NULL,
  `user_fb_id` char(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_fullname` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `user_headline` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_picture` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email_verified` tinyint(4) DEFAULT 0,
  `user_password` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `user_password_updated` tinyint(4) NOT NULL DEFAULT 0,
  `user_birthdate` date DEFAULT NULL,
  `user_gender` enum('N/A','male','female','other') COLLATE utf8_unicode_ci DEFAULT 'N/A',
  `user_country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_mobile_verified` tinyint(4) DEFAULT 0,
  `user_profession` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_type` enum('admin','public') COLLATE utf8_unicode_ci DEFAULT 'public',
  `user_status` enum('active','inactive','not_verified') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `user_is_visible` tinyint(1) DEFAULT 1,
  `user_meta_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Add anything that indicates the type of user for example customer, supplier etc.',
  `user_roles` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_permissions` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_password_reset_link` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email_verification_code` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_private_token` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_branch` int(11) DEFAULT NULL,
  `user_designation` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) UNSIGNED NOT NULL,
  `fk_project_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_users`
--

INSERT INTO `dev_users` (`pk_user_id`, `user_fb_id`, `user_fullname`, `user_headline`, `user_name`, `user_description`, `user_picture`, `user_email`, `user_email_verified`, `user_password`, `user_password_updated`, `user_birthdate`, `user_gender`, `user_country`, `user_mobile`, `user_mobile_verified`, `user_profession`, `user_type`, `user_status`, `user_is_visible`, `user_meta_type`, `user_roles`, `user_permissions`, `user_password_reset_link`, `user_email_verification_code`, `user_private_token`, `user_branch`, `user_designation`, `created_at`, `created_by`, `modified_at`, `modified_by`, `fk_project_id`) VALUES
(1, '', '3DEVs IT LTD', NULL, 'sadmin', NULL, NULL, 'sadmin@bracbpl.com', 0, '$2y$10$kYigu3gSbeQIqgx83OaPWun.zxRwyPzYO/aOxqSiL7D0o24kORF66', 1, '1980-06-08', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '-1', NULL, '%242y%2410%24NgmTZ9491Shuk4iPwQlaGuNp26i6GSJzQgJAd28K31neYHK2oDYKC', NULL, NULL, NULL, NULL, '2017-12-01 00:00:00', 1, '0000-00-00 00:00:00', 68, NULL),
(2, '', 'Md Belall', NULL, 'sk_rueei', '', 'PassportphotoExample2_1563786624.jpg', 'belal@email.com', 0, '$2y$10$xfRnvUxKT.kIXzaRzlAXuuQ3co7baBTpOnFkJRRrpsT45hF6fUZp2', 1, '0000-00-00', 'male', '', '01718728387', 0, NULL, 'admin', 'active', 1, 'user', '0', NULL, NULL, NULL, NULL, 0, 0, '2019-06-10 16:18:43', 1, '2019-07-22 15:10:24', 1, NULL),
(3, '', 'Nazmul Biswash', NULL, 'shs_aasja', '', '', 'email@gmail.com', 0, '$2y$10$zfp7WZWNXGxkn2DYaXFeZuKIrneU2FnLALJR2xO5R83zOBLfz3RDm', 1, '0000-00-00', 'male', '', '017186736367', 0, NULL, 'admin', 'active', 1, 'user', '0', NULL, NULL, NULL, NULL, 3, 142, '2019-06-10 16:19:42', 1, '2019-06-10 16:19:42', 1, NULL),
(4, '', 'Pollab Biswash', NULL, 'sjsj_sajsja', '', '', 'bselal@email.com', 0, '$2y$10$6K.6XQj7SMhHdlSub7inWuYpeBBIoBXh3syzxhyQqP56gUO77e.SW', 1, '0000-00-00', 'male', '', '01717363546', 0, NULL, 'admin', 'active', 1, 'user', '0,0', NULL, NULL, NULL, NULL, 3, 141, '2019-06-10 16:22:38', 1, '2019-07-17 17:05:52', 1, NULL),
(5, '', 'Rariq Islam edit 64', NULL, 'rafiq', '', '3F6B966D00000578-4428630-image-m-80_1492690622006_1563785377.jpg', 'rafiq@gmail.com', 0, '$2y$10$vNmaROhxuSB9K9.uOC8Tf.1qqZ7oQXfziNg7/BlonNHxVxP5iAjMi', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '0,0,0,0,0,0', NULL, NULL, NULL, NULL, 1, 137, '2019-07-22 14:47:49', 1, '2019-07-22 15:34:18', 1, NULL),
(6, '', 'Md. Sariful Islam Sajib', NULL, 'sajib87', '', '16146340_1563786535.jpg', 'sajib87@gmail.com', 0, '$2y$10$lVYYeKgZ/mjvqM1xMu7CMuhAnXD/zJVNDsG4yXu/JfGDGbTRWcF0y', 1, '0000-00-00', 'male', 'Bangladesh', '01677066467', 0, NULL, 'admin', 'active', 1, 'user', '1,3,2', NULL, NULL, NULL, NULL, 0, 0, '2019-07-22 14:52:34', 1, '2019-07-22 15:08:55', 1, NULL),
(7, '', 'Abdul Momin', NULL, 'abc', '', '0055-ach-090917175504_1563795559.jpg', 'abc@gmail.com', 0, '$2y$10$AFPbgCUXX0wnUzZwGXi5QeiAXX/eAbO/tj0do8tsd2dkgx/EsUvSi', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 4, 138, '2019-07-22 16:32:14', 1, '2019-07-22 17:39:19', 1, NULL),
(8, '', 'Rariq Islam', NULL, 'rag282', '', '16146340_1563795542.jpg', 'rafiq@gmskd.com', 0, '$2y$10$/nE2FSzrvkEJGgzmU7ZQdeBtScA7OlWU2Bph7RREcFSZWbYtK2SIq', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 1, 137, '2019-07-22 17:36:51', 1, '2019-07-22 17:39:02', 1, NULL),
(9, '', 'MD ABDUR RAHIM', NULL, 'rahim', '', '', 'drsc.narsingdi@brac.net', 0, '$2y$10$SP0QtX5HQtpLpJzMb1TroOuI9HrjcXzzifxHR2UlVln8uHUhOT5We', 1, '0000-00-00', 'male', '', '01713158364', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 5, 152, '2019-07-22 17:41:55', 1, '2019-09-02 10:02:19', 1, NULL),
(10, '', 'Sabina Yesmin', NULL, 'sabina', '', 'Preeti_Singh_(3)(1)_1563795804.jpg', 'sabina@gmail.com', 0, '$2y$10$Bmge4Drxa9TeNHTZ1CxLFONRRJyLgVlNir5Mvw37UAOv4i/3w.e6q', 1, '0000-00-00', 'male', '', '01658956214', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 6, 143, '2019-07-22 17:43:24', 1, '2019-07-22 17:43:24', 1, NULL),
(11, '', 'Mohiful Kabir Saimon', NULL, 'saimon', '', '6323_1567397238.jpg', 'drsc.cumilla@brac.net', 0, '$2y$10$5B9WW90Fuvm4VCZUVOS4m.BSlY.q5BczAKTidQAU9zcIPGnpvnCNa', 1, '0000-00-00', 'male', '', '01729485130', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 7, 152, '2019-07-22 17:44:32', 1, '2019-09-02 10:08:25', 1, NULL),
(12, '', 'Shimul Hasan', NULL, 'shemul', '', 'PassportphotoExample2_1563795916.jpg', 'shemul@gmail.com', 0, '$2y$10$XYriO.DmL8sb6Ckw1ktSDO0EruvNjFTS1RuW7gU7Qhu0t3k1N0NXq', 1, '0000-00-00', 'male', '', '01895623589', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 8, 143, '2019-07-22 17:45:16', 1, '2019-07-22 17:45:16', 1, NULL),
(13, '', 'Mosharaf Hossain', NULL, 'mosharaf', '', 'Mauro-profile-picture_1563796090.jpg', 'mosharaf@gmail.com', 0, '$2y$10$olgQeawVbYM9ycNYr1EFludEQ7Ei9tOngyf3Dnde9PGGV/HbIcyY6', 1, '0000-00-00', 'male', '', '01589658526', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 1, 143, '2019-07-22 17:48:10', 1, '2019-07-22 17:48:10', 1, NULL),
(14, '', 'Samrat Hasan', NULL, 'samrat', '', '', 'samrat@gmail.com', 0, '$2y$10$5xVdsgzmCkjoNfOv6jOwXuUSMg7hykxtEnlUiy6JkJYIfeED.k/Ry', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 4, 137, '2019-07-22 17:49:58', 1, '2019-07-22 17:49:58', 1, NULL),
(15, '', 'Zakir Hossain', NULL, 'zar763r', '', '', 'zakir987@gmail.com', 0, '$2y$10$6N5wLS9Vpi/5HLtDBHD/uur74nlnLfeQJAm05k.JGUEurw5THt7p6', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 0, 0, '2019-07-23 11:47:51', 1, '2019-09-03 12:46:05', 1, NULL),
(16, '', 'Head Office', NULL, 'admin', '', '', 'admin@brac.net', 0, '$2y$10$1N7i5RK.g40bhoHEpRYFVutjls1cE2nJi08MK46sDMa/l5f.aKQYi', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '1', NULL, NULL, NULL, NULL, 0, 0, '2019-08-26 13:18:52', 1, '2019-08-26 13:18:52', 1, NULL),
(17, '', 'Business Associate', NULL, 'Business', '', '', 'business@brac.net', 0, '$2y$10$3iwAWbQI1lU38xz3Ry/oZ.HyVFuFUIp2gqJhKvin.wbJJqI3lXcn.', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '3', NULL, NULL, NULL, NULL, 0, 0, '2019-08-26 16:16:36', 1, '2019-08-26 16:16:36', 1, NULL),
(18, '', 'Ashrafia Rabbi', NULL, 'amiami', '', 'download_1567396739.jpg', 'rabbi@gmail.com', 0, '$2y$10$BpyxsCeX7Tllp7wSCw3.xOaQfce5BkYHpUoKyP9/xu29vci1z5Hjq', 1, '0000-00-00', 'male', '', '01718987654', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 4, 143, '2019-09-02 09:58:59', 1, '2019-09-02 10:04:50', 1, NULL),
(19, '', 'Demo User', NULL, 'demo', '', '17241-200_1567493699.png', 'admin@demo.com', 0, '$2y$10$4Et9nxe51gL/IP/41dlB7eXTS7Mb9Q8CGmnms99Y7YMdxtfMJnnMm', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '1', NULL, NULL, NULL, NULL, 1, 1, '2019-09-03 12:54:59', 1, '2020-07-28 08:32:15', 1, 0),
(20, '', 'Ashraful Kabir', NULL, 'ashraful', '', '', 'ashraful@gmail.com', 0, '$2y$10$AKxHRvHU3tpIya1y0aYiSuP3eOs4q0frJuKhaBsjk2P4cCCzijdeW', 1, '0000-00-00', 'male', '', '01782088923', 0, NULL, 'admin', 'active', 1, 'user', '4', NULL, NULL, NULL, NULL, 4, 138, '2019-09-12 18:56:17', 1, '2019-09-12 18:56:17', 1, NULL),
(21, '', 'Demo User', NULL, 'admin3', '', '', 'we@hsa.com', 0, '$2y$10$h4EG1NweQIH546y/h7xqSOzgvrefI61L6YBF8fqGub.STASSZDuVC', 1, '0000-00-00', 'male', '', '2423453', 0, NULL, 'admin', 'not_verified', 1, 'user', '4,0', NULL, NULL, NULL, NULL, 1, 1, '2020-08-25 10:06:13', 1, '2020-08-25 10:06:13', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_users_roles_relation`
--

CREATE TABLE `dev_users_roles_relation` (
  `pk_rel_id` int(10) UNSIGNED NOT NULL,
  `fk_user_id` int(10) UNSIGNED DEFAULT NULL,
  `fk_role_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_users_roles_relation`
--

INSERT INTO `dev_users_roles_relation` (`pk_rel_id`, `fk_user_id`, `fk_role_id`) VALUES
(1, 2, 0),
(2, 3, 0),
(3, 4, 0),
(4, 4, 0),
(5, 5, 0),
(6, 5, 0),
(7, 5, 0),
(8, 5, 0),
(9, 5, 0),
(10, 6, 1),
(11, 6, 3),
(12, 6, 2),
(13, 5, 0),
(14, 7, 4),
(15, 8, 4),
(16, 9, 4),
(17, 10, 4),
(18, 11, 4),
(19, 12, 4),
(20, 13, 4),
(21, 14, 4),
(22, 15, 4),
(23, 16, 1),
(24, 17, 3),
(25, 18, 4),
(26, 19, 1),
(27, 20, 4),
(28, 21, 4),
(29, 21, 0);

-- --------------------------------------------------------

--
-- Table structure for table `dev_user_activities`
--

CREATE TABLE `dev_user_activities` (
  `pk_activity_log` bigint(20) UNSIGNED NOT NULL,
  `activity_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `activity_url` text COLLATE utf8_unicode_ci NOT NULL,
  `activity_type` enum('create','read','update','delete','login','logout','password_reset_request') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'create',
  `activity_status` enum('success','error') COLLATE utf8_unicode_ci DEFAULT 'success',
  `created_at` datetime NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_user_activities`
--

INSERT INTO `dev_user_activities` (`pk_activity_log`, `activity_msg`, `activity_url`, `activity_type`, `activity_status`, `created_at`, `created_by`) VALUES
(1, 'All User Activity Logs has been deleted.', '', 'delete', 'success', '2020-08-31 10:56:06', 19);

-- --------------------------------------------------------

--
-- Table structure for table `dev_user_login_logout`
--

CREATE TABLE `dev_user_login_logout` (
  `pk_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `last_seen` datetime NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime NOT NULL,
  `login_remote_address` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `login_x_forwarded_for` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `login_referer` text CHARACTER SET utf8 DEFAULT NULL,
  `status` enum('loggedin','loggedout','idle') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'loggedin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_user_login_logout`
--

INSERT INTO `dev_user_login_logout` (`pk_id`, `user_id`, `last_seen`, `login_time`, `logout_time`, `login_remote_address`, `login_x_forwarded_for`, `login_referer`, `status`) VALUES
(1, 68, '2018-08-16 14:57:32', '2018-08-16 14:57:32', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(2, 68, '2018-08-19 13:37:41', '2018-08-19 13:37:41', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(3, 68, '2018-08-19 16:49:32', '2018-08-19 16:49:32', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(4, 68, '2018-08-20 12:20:15', '2018-08-20 12:20:15', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(5, 68, '2018-08-20 17:44:46', '2018-08-20 17:44:46', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(6, 68, '2018-08-27 11:17:54', '2018-08-27 11:17:54', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(7, 68, '2018-08-27 11:45:51', '2018-08-27 11:45:51', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(8, 68, '2018-08-28 12:56:52', '2018-08-28 12:56:52', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(9, 68, '2018-08-28 13:34:16', '2018-08-28 13:34:16', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(10, 68, '2018-08-28 16:45:37', '2018-08-28 16:45:37', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(11, 68, '2018-08-29 12:10:39', '2018-08-29 12:10:39', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(12, 68, '2018-08-30 13:21:04', '2018-08-30 13:21:04', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(13, 68, '2018-09-03 12:00:42', '2018-09-03 12:00:42', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(14, 68, '2018-09-03 13:16:04', '2018-09-03 13:16:04', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(15, 68, '2018-09-03 14:15:53', '2018-09-03 14:15:53', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(16, 68, '2018-09-04 11:40:01', '2018-09-04 11:40:01', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(17, 68, '2018-09-16 14:10:29', '2018-09-16 14:10:29', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(18, 68, '2018-09-16 14:54:27', '2018-09-16 14:54:27', '0000-00-00 00:00:00', '::1', '', 'http://localhost/3DEVS_WEBSITE/cms/adminers', 'loggedin'),
(19, 68, '2018-09-17 12:29:32', '2018-09-17 12:29:32', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(20, 68, '2018-09-25 16:54:26', '2018-09-25 16:54:26', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(21, 68, '2018-10-03 15:16:28', '2018-10-03 15:16:28', '0000-00-00 00:00:00', '192.168.1.10', '', 'http://myserver/3DEVS-WEBSITE-NEW/cms/adminers', 'loggedin'),
(22, 68, '2018-10-08 12:43:03', '2018-10-08 12:43:03', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(23, 68, '2018-10-09 13:33:49', '2018-10-09 13:33:49', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(24, 68, '2018-10-10 16:57:53', '2018-10-10 16:57:53', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin'),
(25, 68, '2018-10-11 15:11:28', '2018-10-11 15:11:28', '0000-00-00 00:00:00', '127.0.0.1', '', 'http://local.3-devs.com/adminers', 'loggedin');

-- --------------------------------------------------------

--
-- Table structure for table `dev_user_meta`
--

CREATE TABLE `dev_user_meta` (
  `pk_id` int(10) UNSIGNED NOT NULL,
  `fk_user_id` int(10) UNSIGNED NOT NULL,
  `meta_name` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_user_meta`
--

INSERT INTO `dev_user_meta` (`pk_id`, `fk_user_id`, `meta_name`, `meta_value`) VALUES
(37, 68, 'youtube_link', ''),
(36, 68, 'pinterest_link', ''),
(35, 68, 'twitter_link', ''),
(34, 68, 'linkedin_link', 'https://www.linkedin.com/company/3devs-it-ltd-/?trk=biz-companies-cym'),
(33, 68, 'googleplus_link', ''),
(32, 68, 'facebook_link', '');

-- --------------------------------------------------------

--
-- Table structure for table `dev_user_roles`
--

CREATE TABLE `dev_user_roles` (
  `pk_role_id` int(10) UNSIGNED NOT NULL,
  `role_slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_permissions` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_user_roles`
--

INSERT INTO `dev_user_roles` (`pk_role_id`, `role_slug`, `role_name`, `role_description`, `role_permissions`) VALUES
(1, 'admin', 'Admin', 'Head Office Administration', 'a:84:{s:22:\"manage_user_activities\";s:3:\"yes\";s:21:\"flush_user_activities\";s:3:\"yes\";s:19:\"access_to_dashboard\";s:3:\"yes\";s:21:\"manage_mis_activities\";s:3:\"yes\";s:23:\"add_new_activity_output\";s:3:\"yes\";s:22:\"update_activity_output\";s:3:\"yes\";s:14:\"set_mis_target\";s:3:\"yes\";s:27:\"set_mis_target_distribution\";s:3:\"yes\";s:26:\"set_mis_target_achievement\";s:3:\"yes\";s:19:\"download_mis_report\";s:3:\"yes\";s:15:\"manage_branches\";s:3:\"yes\";s:10:\"add_branch\";s:3:\"yes\";s:11:\"edit_branch\";s:3:\"yes\";s:22:\"configure_branch_types\";s:3:\"yes\";s:16:\"manage_customers\";s:3:\"yes\";s:12:\"add_customer\";s:3:\"yes\";s:13:\"edit_customer\";s:3:\"yes\";s:15:\"delete_customer\";s:3:\"yes\";s:12:\"manage_cases\";s:3:\"yes\";s:8:\"add_case\";s:3:\"yes\";s:9:\"edit_case\";s:3:\"yes\";s:11:\"delete_case\";s:3:\"yes\";s:14:\"manage_targets\";s:3:\"yes\";s:10:\"add_target\";s:3:\"yes\";s:11:\"edit_target\";s:3:\"yes\";s:13:\"delete_target\";s:3:\"yes\";s:19:\"manage_achievements\";s:3:\"yes\";s:15:\"add_achievement\";s:3:\"yes\";s:16:\"edit_achievement\";s:3:\"yes\";s:18:\"delete_achievement\";s:3:\"yes\";s:18:\"manage_event_types\";s:3:\"yes\";s:14:\"add_event_type\";s:3:\"yes\";s:15:\"edit_event_type\";s:3:\"yes\";s:17:\"delete_event_type\";s:3:\"yes\";s:13:\"manage_events\";s:3:\"yes\";s:9:\"add_event\";s:3:\"yes\";s:10:\"edit_event\";s:3:\"yes\";s:12:\"delete_event\";s:3:\"yes\";s:24:\"manage_event_validations\";s:3:\"yes\";s:20:\"add_event_validation\";s:3:\"yes\";s:21:\"edit_event_validation\";s:3:\"yes\";s:23:\"delete_event_validation\";s:3:\"yes\";s:16:\"manage_complains\";s:3:\"yes\";s:12:\"add_complain\";s:3:\"yes\";s:13:\"edit_complain\";s:3:\"yes\";s:15:\"delete_complain\";s:3:\"yes\";s:22:\"manage_complain_fileds\";s:3:\"yes\";s:18:\"add_complain_filed\";s:3:\"yes\";s:19:\"edit_complain_filed\";s:3:\"yes\";s:21:\"delete_complain_filed\";s:3:\"yes\";s:30:\"manage_complain_investigations\";s:3:\"yes\";s:26:\"add_complain_investigation\";s:3:\"yes\";s:27:\"edit_complain_investigation\";s:3:\"yes\";s:29:\"delete_complain_investigation\";s:3:\"yes\";s:16:\"manage_trainings\";s:3:\"yes\";s:12:\"add_training\";s:3:\"yes\";s:13:\"edit_training\";s:3:\"yes\";s:15:\"delete_training\";s:3:\"yes\";s:14:\"manage_stories\";s:3:\"yes\";s:9:\"add_story\";s:3:\"yes\";s:10:\"edit_story\";s:3:\"yes\";s:12:\"delete_story\";s:3:\"yes\";s:20:\"manage_study_reports\";s:3:\"yes\";s:16:\"add_study_report\";s:3:\"yes\";s:17:\"edit_study_report\";s:3:\"yes\";s:19:\"delete_study_report\";s:3:\"yes\";s:23:\"manage_research_reports\";s:3:\"yes\";s:19:\"add_research_report\";s:3:\"yes\";s:20:\"edit_research_report\";s:3:\"yes\";s:22:\"delete_research_report\";s:3:\"yes\";s:25:\"manage_assessment_reports\";s:3:\"yes\";s:21:\"add_assessment_report\";s:3:\"yes\";s:22:\"edit_assessment_report\";s:3:\"yes\";s:24:\"delete_assessment_report\";s:3:\"yes\";s:18:\"manage_organograms\";s:3:\"yes\";s:14:\"add_organogram\";s:3:\"yes\";s:15:\"edit_organogram\";s:3:\"yes\";s:17:\"delete_organogram\";s:3:\"yes\";s:15:\"manage_projects\";s:3:\"yes\";s:11:\"add_project\";s:3:\"yes\";s:12:\"edit_project\";s:3:\"yes\";s:13:\"manage_staffs\";s:3:\"yes\";s:9:\"add_staff\";s:3:\"yes\";s:10:\"edit_staff\";s:3:\"yes\";}'),
(2, 'field-worker', 'Returnee', 'Returnee Management', 'a:0:{}'),
(3, 'manager', 'Business', 'Business Management', 'a:35:{s:19:\"access_to_dashboard\";s:3:\"yes\";s:14:\"manage_batches\";s:3:\"yes\";s:9:\"add_batch\";s:3:\"yes\";s:10:\"edit_batch\";s:3:\"yes\";s:15:\"batch_schedules\";s:3:\"yes\";s:18:\"add_batch_schedule\";s:3:\"yes\";s:19:\"edit_batch_schedule\";s:3:\"yes\";s:15:\"manage_branches\";s:3:\"yes\";s:10:\"add_branch\";s:3:\"yes\";s:11:\"edit_branch\";s:3:\"yes\";s:22:\"configure_branch_types\";s:3:\"yes\";s:17:\"manage_potentials\";s:3:\"yes\";s:13:\"add_potential\";s:3:\"yes\";s:14:\"edit_potential\";s:3:\"yes\";s:14:\"manage_courses\";s:3:\"yes\";s:10:\"add_course\";s:3:\"yes\";s:11:\"edit_course\";s:3:\"yes\";s:17:\"manage_financials\";s:3:\"yes\";s:13:\"add_financial\";s:3:\"yes\";s:14:\"edit_financial\";s:3:\"yes\";s:15:\"manage_products\";s:3:\"yes\";s:11:\"add_product\";s:3:\"yes\";s:12:\"edit_product\";s:3:\"yes\";s:12:\"manage_sales\";s:3:\"yes\";s:8:\"add_sale\";s:3:\"yes\";s:9:\"edit_sale\";s:3:\"yes\";s:13:\"manage_staffs\";s:3:\"yes\";s:9:\"add_staff\";s:3:\"yes\";s:10:\"edit_staff\";s:3:\"yes\";s:13:\"manage_stocks\";s:3:\"yes\";s:9:\"add_stock\";s:3:\"yes\";s:10:\"edit_stock\";s:3:\"yes\";s:14:\"manage_vendors\";s:3:\"yes\";s:10:\"add_vendor\";s:3:\"yes\";s:11:\"edit_vendor\";s:3:\"yes\";}'),
(4, 'staff', 'Manager', 'Branch Manager', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dev_acitivites_categories`
--
ALTER TABLE `dev_acitivites_categories`
  ADD PRIMARY KEY (`pk_activity_cat_id`);

--
-- Indexes for table `dev_activities`
--
ALTER TABLE `dev_activities`
  ADD PRIMARY KEY (`pk_activity_id`);

--
-- Indexes for table `dev_branches`
--
ALTER TABLE `dev_branches`
  ADD PRIMARY KEY (`pk_branch_id`);

--
-- Indexes for table `dev_branch_types`
--
ALTER TABLE `dev_branch_types`
  ADD PRIMARY KEY (`pk_item_id`);

--
-- Indexes for table `dev_complains`
--
ALTER TABLE `dev_complains`
  ADD PRIMARY KEY (`pk_complain_id`);

--
-- Indexes for table `dev_complain_fileds`
--
ALTER TABLE `dev_complain_fileds`
  ADD PRIMARY KEY (`pk_complain_filed_id`);

--
-- Indexes for table `dev_complain_investigations`
--
ALTER TABLE `dev_complain_investigations`
  ADD PRIMARY KEY (`pk_complain_investigation_id`);

--
-- Indexes for table `dev_config`
--
ALTER TABLE `dev_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `dev_customers`
--
ALTER TABLE `dev_customers`
  ADD PRIMARY KEY (`pk_customer_id`);

--
-- Indexes for table `dev_customer_health`
--
ALTER TABLE `dev_customer_health`
  ADD PRIMARY KEY (`pk_customer_health_id`);

--
-- Indexes for table `dev_customer_migrations`
--
ALTER TABLE `dev_customer_migrations`
  ADD PRIMARY KEY (`pk_migration_id`);

--
-- Indexes for table `dev_customer_skills`
--
ALTER TABLE `dev_customer_skills`
  ADD PRIMARY KEY (`pk_customer_skills_id`);

--
-- Indexes for table `dev_customer_supports`
--
ALTER TABLE `dev_customer_supports`
  ADD PRIMARY KEY (`pk_rel_id`);

--
-- Indexes for table `dev_documents`
--
ALTER TABLE `dev_documents`
  ADD PRIMARY KEY (`pk_document_id`);

--
-- Indexes for table `dev_economicals`
--
ALTER TABLE `dev_economicals`
  ADD PRIMARY KEY (`pk_economic_id`),
  ADD KEY `fk_customer_id` (`fk_customer_id`);

--
-- Indexes for table `dev_economic_evaluations`
--
ALTER TABLE `dev_economic_evaluations`
  ADD PRIMARY KEY (`pk_economic_evaluation_id`);

--
-- Indexes for table `dev_economic_followups`
--
ALTER TABLE `dev_economic_followups`
  ADD PRIMARY KEY (`pk_economic_review_id`);

--
-- Indexes for table `dev_economic_profile`
--
ALTER TABLE `dev_economic_profile`
  ADD PRIMARY KEY (`pk_economic_profile_id`);

--
-- Indexes for table `dev_economic_reintegration_referrals`
--
ALTER TABLE `dev_economic_reintegration_referrals`
  ADD PRIMARY KEY (`pk_economic_referral_id`);

--
-- Indexes for table `dev_economic_supports`
--
ALTER TABLE `dev_economic_supports`
  ADD PRIMARY KEY (`pk_economic_support_id`);

--
-- Indexes for table `dev_email_templates`
--
ALTER TABLE `dev_email_templates`
  ADD PRIMARY KEY (`pk_etemplate_id`);

--
-- Indexes for table `dev_events`
--
ALTER TABLE `dev_events`
  ADD PRIMARY KEY (`pk_event_id`);

--
-- Indexes for table `dev_event_types`
--
ALTER TABLE `dev_event_types`
  ADD PRIMARY KEY (`pk_event_type_id`);

--
-- Indexes for table `dev_event_validations`
--
ALTER TABLE `dev_event_validations`
  ADD PRIMARY KEY (`pk_validation_id`);

--
-- Indexes for table `dev_followups`
--
ALTER TABLE `dev_followups`
  ADD PRIMARY KEY (`pk_followup_id`);

--
-- Indexes for table `dev_immediate_supports`
--
ALTER TABLE `dev_immediate_supports`
  ADD PRIMARY KEY (`pk_immediate_support_id`);

--
-- Indexes for table `dev_initial_evaluation`
--
ALTER TABLE `dev_initial_evaluation`
  ADD PRIMARY KEY (`pk_evaluation_id`);

--
-- Indexes for table `dev_jack_settings`
--
ALTER TABLE `dev_jack_settings`
  ADD PRIMARY KEY (`pk_settings_id`);

--
-- Indexes for table `dev_knowledge`
--
ALTER TABLE `dev_knowledge`
  ADD PRIMARY KEY (`pk_knowledge_id`);

--
-- Indexes for table `dev_lookups`
--
ALTER TABLE `dev_lookups`
  ADD PRIMARY KEY (`pk_lookup_id`);

--
-- Indexes for table `dev_meetings`
--
ALTER TABLE `dev_meetings`
  ADD PRIMARY KEY (`pk_meeting_id`);

--
-- Indexes for table `dev_menus`
--
ALTER TABLE `dev_menus`
  ADD PRIMARY KEY (`pk_menu_id`);

--
-- Indexes for table `dev_menu_assignments`
--
ALTER TABLE `dev_menu_assignments`
  ADD PRIMARY KEY (`pk_menu_assign_id`);

--
-- Indexes for table `dev_menu_items`
--
ALTER TABLE `dev_menu_items`
  ADD PRIMARY KEY (`pk_item_id`);

--
-- Indexes for table `dev_migrations`
--
ALTER TABLE `dev_migrations`
  ADD PRIMARY KEY (`pk_migration_id`);

--
-- Indexes for table `dev_migration_documents`
--
ALTER TABLE `dev_migration_documents`
  ADD PRIMARY KEY (`pk_document_id`);

--
-- Indexes for table `dev_pages`
--
ALTER TABLE `dev_pages`
  ADD PRIMARY KEY (`pk_page_id`),
  ADD UNIQUE KEY `PickBySlug` (`page_slug`(255));
ALTER TABLE `dev_pages` ADD FULLTEXT KEY `Search` (`page_sub_title`,`page_title`,`page_description`,`page_meta_description`,`page_excerpt`);

--
-- Indexes for table `dev_page_content_types`
--
ALTER TABLE `dev_page_content_types`
  ADD PRIMARY KEY (`rel_id`);

--
-- Indexes for table `dev_projects`
--
ALTER TABLE `dev_projects`
  ADD PRIMARY KEY (`pk_project_id`),
  ADD UNIQUE KEY `project_code` (`project_code`);

--
-- Indexes for table `dev_psycho_completions`
--
ALTER TABLE `dev_psycho_completions`
  ADD PRIMARY KEY (`pk_psycho_completion_id`);

--
-- Indexes for table `dev_psycho_counsellors`
--
ALTER TABLE `dev_psycho_counsellors`
  ADD PRIMARY KEY (`pk_counsellor_support_id`);

--
-- Indexes for table `dev_psycho_evaluations`
--
ALTER TABLE `dev_psycho_evaluations`
  ADD PRIMARY KEY (`pk_psycho_evaluation_id`);

--
-- Indexes for table `dev_psycho_family_counselling`
--
ALTER TABLE `dev_psycho_family_counselling`
  ADD PRIMARY KEY (`pk_psycho_family_counselling_id`);

--
-- Indexes for table `dev_psycho_followups`
--
ALTER TABLE `dev_psycho_followups`
  ADD PRIMARY KEY (`pk_psycho_followup_id`);

--
-- Indexes for table `dev_psycho_sessions`
--
ALTER TABLE `dev_psycho_sessions`
  ADD PRIMARY KEY (`pk_psycho_session_id`);

--
-- Indexes for table `dev_psycho_supports`
--
ALTER TABLE `dev_psycho_supports`
  ADD PRIMARY KEY (`pk_psycho_support_id`);

--
-- Indexes for table `dev_pull_notification`
--
ALTER TABLE `dev_pull_notification`
  ADD PRIMARY KEY (`pk_noti_id`);

--
-- Indexes for table `dev_reintegration_plan`
--
ALTER TABLE `dev_reintegration_plan`
  ADD PRIMARY KEY (`pk_reintegration_plan_id`);

--
-- Indexes for table `dev_reintegration_satisfaction_scale`
--
ALTER TABLE `dev_reintegration_satisfaction_scale`
  ADD PRIMARY KEY (`pk_satisfaction_scale`);

--
-- Indexes for table `dev_sharing_sessions`
--
ALTER TABLE `dev_sharing_sessions`
  ADD PRIMARY KEY (`pk_sharing_session_id`);

--
-- Indexes for table `dev_social_evaluations`
--
ALTER TABLE `dev_social_evaluations`
  ADD PRIMARY KEY (`pk_social_evaluation_id`);

--
-- Indexes for table `dev_social_followups`
--
ALTER TABLE `dev_social_followups`
  ADD PRIMARY KEY (`pk_social_review_id`);

--
-- Indexes for table `dev_social_supports`
--
ALTER TABLE `dev_social_supports`
  ADD PRIMARY KEY (`pk_social_support_id`);

--
-- Indexes for table `dev_supports`
--
ALTER TABLE `dev_supports`
  ADD PRIMARY KEY (`pk_support_id`);

--
-- Indexes for table `dev_support_history`
--
ALTER TABLE `dev_support_history`
  ADD PRIMARY KEY (`pk_history_id`);

--
-- Indexes for table `dev_tags`
--
ALTER TABLE `dev_tags`
  ADD PRIMARY KEY (`pk_tag_id`);

--
-- Indexes for table `dev_tags_group`
--
ALTER TABLE `dev_tags_group`
  ADD PRIMARY KEY (`pk_tag_group_id`);

--
-- Indexes for table `dev_targets`
--
ALTER TABLE `dev_targets`
  ADD PRIMARY KEY (`pk_target_id`);

--
-- Indexes for table `dev_trainings`
--
ALTER TABLE `dev_trainings`
  ADD PRIMARY KEY (`pk_training_id`);

--
-- Indexes for table `dev_users`
--
ALTER TABLE `dev_users`
  ADD PRIMARY KEY (`pk_user_id`),
  ADD KEY `GeneralSelect` (`pk_user_id`);

--
-- Indexes for table `dev_users_roles_relation`
--
ALTER TABLE `dev_users_roles_relation`
  ADD PRIMARY KEY (`pk_rel_id`),
  ADD KEY `users` (`fk_user_id`);

--
-- Indexes for table `dev_user_activities`
--
ALTER TABLE `dev_user_activities`
  ADD PRIMARY KEY (`pk_activity_log`);

--
-- Indexes for table `dev_user_login_logout`
--
ALTER TABLE `dev_user_login_logout`
  ADD PRIMARY KEY (`pk_id`);

--
-- Indexes for table `dev_user_meta`
--
ALTER TABLE `dev_user_meta`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `ByUser` (`fk_user_id`);

--
-- Indexes for table `dev_user_roles`
--
ALTER TABLE `dev_user_roles`
  ADD PRIMARY KEY (`pk_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dev_acitivites_categories`
--
ALTER TABLE `dev_acitivites_categories`
  MODIFY `pk_activity_cat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `dev_activities`
--
ALTER TABLE `dev_activities`
  MODIFY `pk_activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `dev_branches`
--
ALTER TABLE `dev_branches`
  MODIFY `pk_branch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `dev_branch_types`
--
ALTER TABLE `dev_branch_types`
  MODIFY `pk_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_complains`
--
ALTER TABLE `dev_complains`
  MODIFY `pk_complain_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dev_complain_fileds`
--
ALTER TABLE `dev_complain_fileds`
  MODIFY `pk_complain_filed_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_complain_investigations`
--
ALTER TABLE `dev_complain_investigations`
  MODIFY `pk_complain_investigation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_config`
--
ALTER TABLE `dev_config`
  MODIFY `config_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `dev_customers`
--
ALTER TABLE `dev_customers`
  MODIFY `pk_customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_customer_health`
--
ALTER TABLE `dev_customer_health`
  MODIFY `pk_customer_health_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_customer_migrations`
--
ALTER TABLE `dev_customer_migrations`
  MODIFY `pk_migration_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_customer_skills`
--
ALTER TABLE `dev_customer_skills`
  MODIFY `pk_customer_skills_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_documents`
--
ALTER TABLE `dev_documents`
  MODIFY `pk_document_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_economicals`
--
ALTER TABLE `dev_economicals`
  MODIFY `pk_economic_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_economic_evaluations`
--
ALTER TABLE `dev_economic_evaluations`
  MODIFY `pk_economic_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_economic_followups`
--
ALTER TABLE `dev_economic_followups`
  MODIFY `pk_economic_review_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_economic_profile`
--
ALTER TABLE `dev_economic_profile`
  MODIFY `pk_economic_profile_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_economic_reintegration_referrals`
--
ALTER TABLE `dev_economic_reintegration_referrals`
  MODIFY `pk_economic_referral_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_economic_supports`
--
ALTER TABLE `dev_economic_supports`
  MODIFY `pk_economic_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dev_email_templates`
--
ALTER TABLE `dev_email_templates`
  MODIFY `pk_etemplate_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_events`
--
ALTER TABLE `dev_events`
  MODIFY `pk_event_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_event_types`
--
ALTER TABLE `dev_event_types`
  MODIFY `pk_event_type_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_event_validations`
--
ALTER TABLE `dev_event_validations`
  MODIFY `pk_validation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_followups`
--
ALTER TABLE `dev_followups`
  MODIFY `pk_followup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_immediate_supports`
--
ALTER TABLE `dev_immediate_supports`
  MODIFY `pk_immediate_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dev_initial_evaluation`
--
ALTER TABLE `dev_initial_evaluation`
  MODIFY `pk_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dev_jack_settings`
--
ALTER TABLE `dev_jack_settings`
  MODIFY `pk_settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `dev_knowledge`
--
ALTER TABLE `dev_knowledge`
  MODIFY `pk_knowledge_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_lookups`
--
ALTER TABLE `dev_lookups`
  MODIFY `pk_lookup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_meetings`
--
ALTER TABLE `dev_meetings`
  MODIFY `pk_meeting_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_menus`
--
ALTER TABLE `dev_menus`
  MODIFY `pk_menu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_menu_assignments`
--
ALTER TABLE `dev_menu_assignments`
  MODIFY `pk_menu_assign_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_menu_items`
--
ALTER TABLE `dev_menu_items`
  MODIFY `pk_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `dev_migrations`
--
ALTER TABLE `dev_migrations`
  MODIFY `pk_migration_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `dev_migration_documents`
--
ALTER TABLE `dev_migration_documents`
  MODIFY `pk_document_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_pages`
--
ALTER TABLE `dev_pages`
  MODIFY `pk_page_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `dev_page_content_types`
--
ALTER TABLE `dev_page_content_types`
  MODIFY `rel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `dev_projects`
--
ALTER TABLE `dev_projects`
  MODIFY `pk_project_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dev_psycho_completions`
--
ALTER TABLE `dev_psycho_completions`
  MODIFY `pk_psycho_completion_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_psycho_counsellors`
--
ALTER TABLE `dev_psycho_counsellors`
  MODIFY `pk_counsellor_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dev_psycho_evaluations`
--
ALTER TABLE `dev_psycho_evaluations`
  MODIFY `pk_psycho_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_psycho_family_counselling`
--
ALTER TABLE `dev_psycho_family_counselling`
  MODIFY `pk_psycho_family_counselling_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dev_psycho_followups`
--
ALTER TABLE `dev_psycho_followups`
  MODIFY `pk_psycho_followup_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_psycho_sessions`
--
ALTER TABLE `dev_psycho_sessions`
  MODIFY `pk_psycho_session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_psycho_supports`
--
ALTER TABLE `dev_psycho_supports`
  MODIFY `pk_psycho_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_pull_notification`
--
ALTER TABLE `dev_pull_notification`
  MODIFY `pk_noti_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_reintegration_plan`
--
ALTER TABLE `dev_reintegration_plan`
  MODIFY `pk_reintegration_plan_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_reintegration_satisfaction_scale`
--
ALTER TABLE `dev_reintegration_satisfaction_scale`
  MODIFY `pk_satisfaction_scale` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dev_sharing_sessions`
--
ALTER TABLE `dev_sharing_sessions`
  MODIFY `pk_sharing_session_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_social_evaluations`
--
ALTER TABLE `dev_social_evaluations`
  MODIFY `pk_social_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_social_followups`
--
ALTER TABLE `dev_social_followups`
  MODIFY `pk_social_review_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_social_supports`
--
ALTER TABLE `dev_social_supports`
  MODIFY `pk_social_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_supports`
--
ALTER TABLE `dev_supports`
  MODIFY `pk_support_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_tags`
--
ALTER TABLE `dev_tags`
  MODIFY `pk_tag_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `dev_tags_group`
--
ALTER TABLE `dev_tags_group`
  MODIFY `pk_tag_group_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_targets`
--
ALTER TABLE `dev_targets`
  MODIFY `pk_target_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dev_trainings`
--
ALTER TABLE `dev_trainings`
  MODIFY `pk_training_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dev_users`
--
ALTER TABLE `dev_users`
  MODIFY `pk_user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `dev_users_roles_relation`
--
ALTER TABLE `dev_users_roles_relation`
  MODIFY `pk_rel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `dev_user_activities`
--
ALTER TABLE `dev_user_activities`
  MODIFY `pk_activity_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_user_login_logout`
--
ALTER TABLE `dev_user_login_logout`
  MODIFY `pk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `dev_user_meta`
--
ALTER TABLE `dev_user_meta`
  MODIFY `pk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `dev_user_roles`
--
ALTER TABLE `dev_user_roles`
  MODIFY `pk_role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
