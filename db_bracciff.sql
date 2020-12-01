-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2020 at 10:35 AM
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
-- Table structure for table `dev_access_to_pp`
--

CREATE TABLE `dev_access_to_pp` (
  `pk_access_id` bigint(20) NOT NULL,
  `fk_project_id` int(10) DEFAULT NULL,
  `brac_info_id` varchar(30) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `disability` enum('yes','no') DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazilla` varchar(100) DEFAULT NULL,
  `user_union` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `service_type` text DEFAULT NULL,
  `other_service_type` text DEFAULT NULL,
  `rescue_reason` text DEFAULT NULL,
  `destination_country` varchar(50) DEFAULT NULL,
  `support_date` date DEFAULT NULL,
  `complain_to` text DEFAULT NULL,
  `other_complain_to` text DEFAULT NULL,
  `service_result` varchar(30) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `comment` text NOT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `modify_time` time DEFAULT NULL,
  `modify_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_activities`
--

CREATE TABLE `dev_activities` (
  `pk_activity_id` bigint(20) NOT NULL,
  `fk_project_id` int(10) NOT NULL,
  `activity_name` varchar(100) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_activities`
--

INSERT INTO `dev_activities` (`pk_activity_id`, `fk_project_id`, `activity_name`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 1, 'Activity II', NULL, NULL, NULL, '14:15:50', '2020-09-20', 1),
(2, 5, 'Activity II', '11:47:56', '2020-09-17', 1, '14:15:35', '2020-09-20', 1),
(3, 1, 'Activity I', NULL, NULL, NULL, '14:15:13', '2020-09-20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_airport_land_supports`
--

CREATE TABLE `dev_airport_land_supports` (
  `pk_support_id` bigint(20) NOT NULL,
  `fk_project_id` int(10) DEFAULT NULL,
  `brac_info_id` varchar(30) DEFAULT NULL,
  `return_route` enum('land','air','sea') DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `person_type` enum('trafficked_survivor','returnee_migrant_worker') DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `is_disable` enum('yes','no') DEFAULT NULL,
  `passport_number` varchar(30) DEFAULT NULL,
  `travel_pass` varchar(30) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `emergency_mobile` varchar(20) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `upazilla` varchar(50) DEFAULT NULL,
  `user_union` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `destination_country` varchar(50) DEFAULT NULL,
  `service_received` text DEFAULT NULL,
  `other_service_received` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `modify_time` time DEFAULT NULL,
  `modify_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dev_branches`
--

INSERT INTO `dev_branches` (`pk_branch_id`, `fk_branch_id`, `fk_branch_type`, `fk_project_id`, `branch_name`, `branch_division`, `branch_district`, `branch_sub_district`, `branch_address`, `branch_contact_person`, `branch_contact_number`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 0, 1, 1, 'Tangail DRSC', 'Dhaka', 'Tangail', '', '', NULL, NULL, '13:28:46', '2019-07-08', 1, '13:28:46', '2019-07-08', 1),
(2, 1, 2, 2, 'Tangail Sadar', 'Dhaka', 'Tangail', 'Tangail Sadar', '', NULL, NULL, '13:29:47', '2019-07-08', 1, '13:29:47', '2019-07-08', 1),
(3, 1, 2, 2, 'Ghatail', 'Dhaka', 'Tangail', 'Ghatail', '', NULL, NULL, '13:30:19', '2019-07-08', 1, '13:30:19', '2019-07-08', 1),
(50, 0, 1, 1, 'Jashore DRSC', 'Khulna', 'Jashore', '', '', NULL, NULL, '17:34:41', '2020-09-23', 2, '17:34:41', '2020-09-23', 2);

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
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_branch_types`
--

INSERT INTO `dev_branch_types` (`pk_item_id`, `fk_item_id`, `item_sort_order`, `item_title`, `item_short_title`, `_branch_type_slug`, `create_date`, `create_time`, `created_by`, `update_date`, `update_time`, `updated_by`) VALUES
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
  `branch_district` varchar(150) DEFAULT NULL,
  `branch_sub_district` int(150) DEFAULT NULL,
  `upazila` varchar(150) DEFAULT NULL,
  `branch_union` varchar(150) DEFAULT NULL,
  `village` varchar(150) DEFAULT NULL,
  `name` varchar(160) DEFAULT NULL,
  `type_recipient` text DEFAULT NULL,
  `type_service` text DEFAULT NULL,
  `other_type_service` text DEFAULT NULL,
  `know_service` text DEFAULT NULL,
  `other_know_service` int(11) DEFAULT NULL,
  `complain_register_date` date DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_complains`
--

INSERT INTO `dev_complains` (`pk_complain_id`, `fk_branch_id`, `branch_district`, `branch_sub_district`, `upazila`, `branch_union`, `village`, `name`, `type_recipient`, `type_service`, `other_type_service`, `know_service`, `other_know_service`, `complain_register_date`, `age`, `gender`, `remark`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 1, 'Tangail', 0, 'Jashore Sadar', 'Union', 'Village', '', 'family', '', NULL, '', NULL, '2020-11-18', '43', 'male', '', '11:49:46', '2020-11-18', 1, '11:50:06', '2020-11-18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_complain_fileds`
--

CREATE TABLE `dev_complain_fileds` (
  `pk_complain_filed_id` bigint(20) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
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
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_complain_fileds`
--

INSERT INTO `dev_complain_fileds` (`pk_complain_filed_id`, `full_name`, `complain_register_date`, `month`, `division`, `district`, `upazila`, `police_station`, `case_id`, `age`, `gender`, `type_case`, `comments`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 'asdas', '2020-11-18', 'January', '', '', '', '', '', '65', 'male', '', '', '11:46:32', '2020-11-18', 1, '11:46:47', '2020-11-18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_complain_investigations`
--

CREATE TABLE `dev_complain_investigations` (
  `pk_complain_investigation_id` bigint(20) NOT NULL,
  `running_investigation` enum('yes','no') DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
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
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_complain_investigations`
--

INSERT INTO `dev_complain_investigations` (`pk_complain_investigation_id`, `running_investigation`, `full_name`, `complain_register_date`, `month`, `division`, `district`, `upazila`, `police_station`, `case_id`, `age`, `gender`, `type_case`, `comments`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 'yes', 'Full Name', '2020-11-18', 'January', 'Rajshahi', 'Naogaon', 'Manirampur', '3r43ewr', '3423', '17', 'female', 'Missing', 'sdfsd', '11:22:45', '2020-11-18', 1, '19:39:46', '2020-11-26', 1);

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
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `fk_staff_id` bigint(20) DEFAULT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customers`
--

INSERT INTO `dev_customers` (`pk_customer_id`, `customer_id`, `full_name`, `father_name`, `mother_name`, `customer_photo`, `marital_status`, `customer_spouse`, `customer_birthdate`, `customer_gender`, `customer_religion`, `nid_number`, `passport_number`, `birth_reg_number`, `bmet_card_number`, `travel_pass`, `educational_qualification`, `customer_mobile`, `emergency_mobile`, `emergency_name`, `emergency_relation`, `present_flat`, `present_house`, `present_road`, `present_village`, `present_ward`, `present_union`, `present_post_office`, `present_post_code`, `present_police_station`, `present_sub_district`, `present_district`, `present_division`, `present_country`, `permanent_flat`, `permanent_house`, `permanent_road`, `permanent_village`, `permanent_ward`, `permanent_union`, `permanent_post_office`, `permanent_post_code`, `permanent_police_station`, `permanent_sub_district`, `permanent_district`, `permanent_division`, `preferred_location`, `last_visited_country`, `customer_status`, `customer_type`, `create_date`, `create_time`, `created_by`, `update_date`, `update_time`, `updated_by`, `fk_staff_id`, `fk_branch_id`) VALUES
(1, 'Participant ID', 'Full Name', 'Father Name', 'Mother Name', NULL, 'married', '', '2020-09-23', 'male', NULL, 'NID Number', 'Passport No', '', NULL, NULL, 'sign', '01719020278', 'Emergency Mobile No ', 'Name of that person', 'Relation with Participant ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Address', NULL, 'Village', 'Ward No', 'Union', NULL, NULL, NULL, 'Jashore Sadar', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-09-23', '17:50:57', 2, NULL, NULL, NULL, NULL, NULL),
(2, '', 'Shaik Obydullah', 'Father Name', 'Mother Name', NULL, 'single', '', '2020-09-23', 'female', NULL, '', '', 'Birth Registration Number', NULL, NULL, 'ssc', '01719020274', 'Emergency Mobile No ', 'Name of that person', 'Relation with Participant ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dhaka', NULL, '', '', '', NULL, NULL, NULL, 'Sharsha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-09-23', '21:15:38', 2, NULL, NULL, NULL, NULL, NULL),
(3, 'Participant ID', 'Full Namee', 'Father Namee', 'Mother Name', NULL, 'divorced', '', '1992-12-24', 'male', NULL, '', '', '', NULL, NULL, 'sign', '017190202799', 'Emergency Mobile No ', 'Name of that person ', 'Relation with Participant', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee', NULL, 'Goal Batahn', 'Ward No', 'Union/Pourashava', NULL, NULL, NULL, 'Jashore Sadar', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-11-05', '15:19:22', 1, '2020-11-05', '16:25:51', 1, NULL, NULL);

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
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customer_health`
--

INSERT INTO `dev_customer_health` (`pk_customer_health_id`, `fk_customer_id`, `is_physically_challenged`, `disability_type`, `having_chronic_disease`, `disease_type`, `other_disease_type`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 1, 'no', '', 'no', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'yes', 'Type of disability', 'no', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'no', '', 'no', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_customer_skills`
--

CREATE TABLE `dev_customer_skills` (
  `pk_customer_skills_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `have_earner_skill` enum('yes','no') DEFAULT NULL,
  `have_skills` text DEFAULT NULL,
  `other_have_skills` text DEFAULT NULL,
  `vocational_skill` text DEFAULT NULL,
  `handicraft_skill` text DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_customer_skills`
--

INSERT INTO `dev_customer_skills` (`pk_customer_skills_id`, `fk_customer_id`, `have_earner_skill`, `have_skills`, `other_have_skills`, `vocational_skill`, `handicraft_skill`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 1, 'yes', 'block_batiks', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'yes', 'cultivation', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'no', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Table structure for table `dev_economic_profile`
--

CREATE TABLE `dev_economic_profile` (
  `pk_economic_profile_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `property_name` varchar(150) DEFAULT NULL,
  `property_value` varchar(50) DEFAULT NULL,
  `returnee_income_source` varchar(150) DEFAULT NULL,
  `income_source` varchar(150) DEFAULT NULL,
  `family_income` double DEFAULT NULL,
  `pre_occupation` text DEFAULT NULL,
  `present_occupation` text DEFAULT NULL,
  `present_income` double DEFAULT NULL,
  `total_member` tinyint(2) DEFAULT NULL,
  `male_household_member` tinyint(2) DEFAULT NULL,
  `female_household_member` tinyint(2) DEFAULT NULL,
  `boy_household_member` tinyint(2) DEFAULT NULL,
  `girl_household_member` tinyint(2) DEFAULT NULL,
  `personal_savings` double DEFAULT NULL,
  `personal_debt` double DEFAULT NULL,
  `current_residence_ownership` text DEFAULT NULL,
  `current_residence_type` text DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_profile`
--

INSERT INTO `dev_economic_profile` (`pk_economic_profile_id`, `fk_customer_id`, `property_name`, `property_value`, `returnee_income_source`, `income_source`, `family_income`, `pre_occupation`, `present_occupation`, `present_income`, `total_member`, `male_household_member`, `female_household_member`, `boy_household_member`, `girl_household_member`, `personal_savings`, `personal_debt`, `current_residence_ownership`, `current_residence_type`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, 'Main occupation before', 'Main occupation after', 6000, 50, 20, 30, NULL, NULL, 1000, 6000, 'rental', 'live', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, 'Main occupation before', 'Main occupation after', 6000, 6, 3, 3, NULL, NULL, 1000, 6000, 'own', 'pucca', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, NULL, NULL, NULL, NULL, 'FD', 'Main occupation after', 6000, 16, 4, 4, 4, 4, 1000, 6000, 'rental', 'pucca', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_reintegration_referrals`
--

CREATE TABLE `dev_economic_reintegration_referrals` (
  `pk_economic_referral_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `received_vocational_training` text DEFAULT NULL,
  `other_received_vocational_training` text DEFAULT NULL,
  `training_start_date` date DEFAULT NULL,
  `training_end_date` date DEFAULT NULL,
  `is_vocational_training` enum('yes','no') DEFAULT NULL,
  `received_vocational` text DEFAULT NULL,
  `other_received_vocational` text DEFAULT NULL,
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
  `assistance_utilized` text DEFAULT NULL,
  `job_placement_date` date DEFAULT NULL,
  `financial_services_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_reintegration_referrals`
--

INSERT INTO `dev_economic_reintegration_referrals` (`pk_economic_referral_id`, `fk_customer_id`, `entry_date`, `received_vocational_training`, `other_received_vocational_training`, `training_start_date`, `training_end_date`, `is_vocational_training`, `received_vocational`, `other_received_vocational`, `other_comments`, `is_economic_services`, `economic_support`, `economic_financial_service`, `other_economic_support`, `is_assistance_received`, `refferd_to`, `trianing_date`, `place_of_training`, `duration_training`, `refferd_address`, `status_traning`, `assistance_utilized`, `job_placement_date`, `financial_services_date`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 2, '2020-11-12', '', NULL, '2020-11-12', '2020-11-12', '', '', NULL, '', '', '', '', NULL, '', '', '2020-09-23', '', '', '', '', '', '1970-01-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, '2020-11-12', '', NULL, '2020-11-12', '2020-11-12', '', '', NULL, '', '', '', '', NULL, '', '', '2020-11-12', '', '', '', '', '', '1970-01-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_economic_supports`
--

CREATE TABLE `dev_economic_supports` (
  `pk_economic_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `inkind_project` text DEFAULT NULL,
  `other_inkind_project` text DEFAULT NULL,
  `is_certification_received` enum('yes','no') NOT NULL,
  `training_used` text NOT NULL,
  `other_comments` text NOT NULL,
  `training_status` enum('ongoing','completed','uncompleted') DEFAULT NULL,
  `microbusiness_established` enum('yes','no') DEFAULT NULL,
  `family_training` enum('yes','no') DEFAULT NULL,
  `traning_entry_date` date DEFAULT NULL,
  `place_traning` varchar(100) DEFAULT NULL,
  `duration_traning` varchar(100) DEFAULT NULL,
  `financial_literacy_date` date DEFAULT NULL,
  `business_development_date` date DEFAULT NULL,
  `product_development_date` date DEFAULT NULL,
  `entrepreneur_training_date` date DEFAULT NULL,
  `other_financial_training_name` varchar(100) DEFAULT NULL,
  `other_financial_training_date` date DEFAULT NULL,
  `month_inauguration` varchar(100) DEFAULT NULL,
  `year_inauguration` varchar(100) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_economic_supports`
--

INSERT INTO `dev_economic_supports` (`pk_economic_support_id`, `fk_customer_id`, `entry_date`, `inkind_project`, `other_inkind_project`, `is_certification_received`, `training_used`, `other_comments`, `training_status`, `microbusiness_established`, `family_training`, `traning_entry_date`, `place_traning`, `duration_traning`, `financial_literacy_date`, `business_development_date`, `product_development_date`, `entrepreneur_training_date`, `other_financial_training_name`, `other_financial_training_date`, `month_inauguration`, `year_inauguration`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 2, '2020-11-12', '', NULL, '', '', '', '', '', '', '2020-09-23', NULL, '', '1970-01-01', '1970-01-01', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, '2020-11-12', '', NULL, '', '', '', '', '', '', '2020-11-12', NULL, '', '1970-01-01', '1970-01-01', '1970-01-01', '1970-01-01', '', '1970-01-01', '', '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_events`
--

CREATE TABLE `dev_events` (
  `pk_event_id` bigint(20) NOT NULL,
  `fk_branch_id` int(10) NOT NULL,
  `month` int(2) NOT NULL,
  `fk_project_id` int(10) NOT NULL,
  `fk_activity_id` bigint(20) NOT NULL,
  `event_division` varchar(100) DEFAULT NULL,
  `event_district` varchar(100) DEFAULT NULL,
  `event_upazila` varchar(80) DEFAULT NULL,
  `event_union` varchar(80) DEFAULT NULL,
  `event_location` text DEFAULT NULL,
  `event_village` varchar(150) DEFAULT NULL,
  `event_ward` varchar(150) DEFAULT NULL,
  `event_start_date` date DEFAULT NULL,
  `event_start_time` time DEFAULT NULL,
  `event_end_date` date DEFAULT NULL,
  `event_end_time` time DEFAULT NULL,
  `participant_boy` tinyint(2) DEFAULT NULL,
  `participant_girl` tinyint(2) DEFAULT NULL,
  `participant_male` tinyint(2) DEFAULT NULL,
  `participant_female` tinyint(2) DEFAULT NULL,
  `validation_count` tinyint(3) DEFAULT NULL,
  `preparatory_work` tinyint(2) DEFAULT NULL,
  `time_management` tinyint(2) DEFAULT NULL,
  `participants_attention` tinyint(2) DEFAULT NULL,
  `logistical_arrangements` tinyint(2) DEFAULT NULL,
  `relevancy_delivery` tinyint(2) DEFAULT NULL,
  `participants_feedback` tinyint(2) DEFAULT NULL,
  `observation_score` tinyint(3) DEFAULT NULL,
  `event_note` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_events`
--

INSERT INTO `dev_events` (`pk_event_id`, `fk_branch_id`, `month`, `fk_project_id`, `fk_activity_id`, `event_division`, `event_district`, `event_upazila`, `event_union`, `event_location`, `event_village`, `event_ward`, `event_start_date`, `event_start_time`, `event_end_date`, `event_end_time`, `participant_boy`, `participant_girl`, `participant_male`, `participant_female`, `validation_count`, `preparatory_work`, `time_management`, `participants_attention`, `logistical_arrangements`, `relevancy_delivery`, `participants_feedback`, `observation_score`, `event_note`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 50, 1, 1, 3, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-23', '21:20:45', '2020-09-23', '21:20:45', 2, 2, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '21:21:28', '2020-09-23', 2, NULL, NULL, NULL),
(2, 50, 1, 1, 0, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-23', '21:21:45', '2020-09-23', '21:21:45', 24, 2, 3, 3, 0, 5, 4, 3, 5, 3, 2, 22, '', '21:22:17', '2020-09-23', 2, '20:02:20', '2020-09-24', 1),
(3, 1, 1, 1, 3, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-24', '19:21:18', '2020-09-24', '19:21:18', 24, 2, 3, 3, 0, 5, 5, 4, 5, 4, 4, 27, '', '19:22:00', '2020-09-24', 1, '20:02:49', '2020-09-24', 1),
(4, 1, 1, 1, 3, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '14:57:12', '2020-09-28', '14:57:12', 3, 3, 3, -4, 0, 5, 4, 2, 5, 3, 4, 23, '', '14:58:14', '2020-09-28', 2, NULL, NULL, NULL),
(5, 1, 1, 1, 3, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '14:58:42', '2020-09-28', '14:58:42', 0, 0, 0, 0, 0, 5, 4, 3, 4, 2, 3, 21, '', '14:59:23', '2020-09-28', 2, NULL, NULL, NULL),
(6, 1, 1, 1, 3, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '15:26:17', '2020-09-28', '15:26:17', 4, 4, 4, 4, 0, 5, 4, 3, 4, 2, 3, 21, '', '15:26:30', '2020-09-28', 2, NULL, NULL, NULL),
(7, 1, 1, 1, 2, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '19:34:52', '2020-09-28', '19:34:52', 4, 4, 5, 5, 0, 4, 3, 3, 4, 2, 5, 21, '', '19:36:16', '2020-09-28', 2, NULL, NULL, NULL),
(8, 1, 1, 1, 2, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '19:38:40', '2020-09-28', '19:38:40', 0, 0, 0, 0, 0, 5, 3, 3, 4, 5, 3, 23, '', '19:39:21', '2020-09-28', 2, NULL, NULL, NULL),
(9, 1, 1, 1, 1, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '19:41:40', '2020-09-28', '19:41:40', 4, 4, 4, 4, 0, 5, 3, 3, 4, 5, 3, 23, '', '19:41:58', '2020-09-28', 2, NULL, NULL, NULL),
(10, 1, 1, 1, 1, 'khulna', 'jashore', '', '', '', '', '', '2020-09-28', '19:41:40', '2020-09-28', '19:41:40', 4, 4, 4, 4, 0, 5, 3, 3, 4, 5, 3, 23, '', '19:42:40', '2020-09-28', 2, NULL, NULL, NULL),
(11, 1, 11, 1, 3, 'Khulna', 'Jhenaida', 'Jhenaidah Sadar', 'Union', 'ggf', 'Village', '', '2020-11-28', '17:35:57', '2020-11-28', '17:35:57', 1, 1, 2, 2, NULL, 4, 3, 3, 5, 3, 5, 23, '', '17:37:35', '2020-11-28', 1, NULL, NULL, NULL);

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
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dev_followups`
--

CREATE TABLE `dev_followups` (
  `pk_followup_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `casedropped` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason_dropping` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_reason_dropping` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirm_services` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_protection` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `special_security` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_psychosocial` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_psychosocial_date` date DEFAULT NULL,
  `comment_economic` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_economic_date` date DEFAULT NULL,
  `comment_social` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_social_date` date DEFAULT NULL,
  `comment_income` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_income_date` date DEFAULT NULL,
  `monthly_income` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `challenges` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `actions_taken` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark_participant` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `comment_brac` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark_district` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_followups`
--

INSERT INTO `dev_followups` (`pk_followup_id`, `entry_date`, `fk_customer_id`, `casedropped`, `reason_dropping`, `other_reason_dropping`, `confirm_services`, `social_protection`, `special_security`, `comment_psychosocial`, `comment_psychosocial_date`, `comment_economic`, `comment_economic_date`, `comment_social`, `comment_social_date`, `comment_income`, `comment_income_date`, `monthly_income`, `challenges`, `actions_taken`, `remark_participant`, `comment_brac`, `remark_district`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, NULL, 2, '', '', NULL, '', NULL, NULL, '', NULL, '', NULL, '', NULL, '', NULL, '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 3, 'yes', '', NULL, 'Child Care,Education,Admission', NULL, NULL, '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '', '', '', '', '', '2020-11-12', '16:34:03', 1, '2020-11-12', '16:04:22', 1),
(3, NULL, 3, '', '', NULL, 'Financial Services,Loan', NULL, NULL, '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '', '', '', '', '', NULL, NULL, NULL, '2020-11-12', '16:39:22', 1),
(4, '2020-11-12', 3, '', '', NULL, 'Child Care', NULL, NULL, '', '2020-11-11', '', '1970-01-01', '', '1970-01-01', '', '1970-01-01', '', '', '', '', '', '', NULL, NULL, NULL, '2020-11-12', '18:25:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_immediate_supports`
--

CREATE TABLE `dev_immediate_supports` (
  `pk_immediate_support_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `fk_staff_id` bigint(20) DEFAULT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `arrival_place` varchar(150) DEFAULT NULL,
  `immediate_support` text DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_immediate_supports`
--

INSERT INTO `dev_immediate_supports` (`pk_immediate_support_id`, `entry_date`, `fk_branch_id`, `fk_staff_id`, `fk_customer_id`, `arrival_place`, `immediate_support`, `create_date`, `create_time`, `created_by`, `update_date`, `update_time`, `updated_by`) VALUES
(1, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 0, 0, 2, '', 'Information provision', '0000-00-00', NULL, NULL, '2020-11-12', '16:01:32', 1),
(3, NULL, 0, 0, 3, '', '', '0000-00-00', NULL, NULL, '2020-11-12', '16:04:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_initial_evaluation`
--

CREATE TABLE `dev_initial_evaluation` (
  `pk_evaluation_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `is_participant` enum('yes','no') DEFAULT NULL,
  `justification_project` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_initial_evaluation`
--

INSERT INTO `dev_initial_evaluation` (`pk_evaluation_id`, `fk_customer_id`, `entry_date`, `is_participant`, `justification_project`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, NULL, 'no', '', NULL, NULL, NULL, '14:14:41', '2020-11-13', 1),
(4, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_knowledge`
--

INSERT INTO `dev_knowledge` (`pk_knowledge_id`, `name`, `document_file`, `tags`, `type`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(9, 'New Success Story', '120276576_143753304097597_4818097669573778964_o_1605355510.jpg', 'New Story', 'story', '18:05:10', '2020-11-14', 1, NULL, NULL, NULL),
(10, 'Shaik Obydullah3423', '120235427_143753114097616_7438402392771913529_o_1605356507.jpg', 'Another Story', 'story', '18:18:35', '2020-11-14', 1, '18:21:47', '2020-11-14', 1),
(11, 'Study Report file', '120328829_143753207430940_7270828347992875268_o_1605360532.jpg', 'Study Report', 'study', '19:28:52', '2020-11-14', 1, NULL, NULL, NULL),
(12, 'kk', '120719453_143753227430938_3001250403292603602_o_1605361348.jpg', 'rsc', 'research', '19:42:28', '2020-11-14', 1, NULL, NULL, NULL),
(13, 'saas', '120491497_143753384097589_5602514357535726567_o_1605362739.jpg', 'asdfas', 'assessment', '20:05:39', '2020-11-14', 1, NULL, NULL, NULL),
(14, 'Shaik Obydullah', '120277586_143753537430907_5700419952644746905_o_1605362864.jpg', 'fasdf', 'organogram', '20:07:44', '2020-11-14', 1, NULL, NULL, NULL);

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

--
-- Dumping data for table `dev_lookups`
--

INSERT INTO `dev_lookups` (`pk_lookup_id`, `fk_content_id`, `lookup_group`, `lookup_value`) VALUES
(1, 0, 'staff_designation', 'Branch Manager'),
(2, 9, 'success_story', 'New Story'),
(3, 10, 'success_story', 'Another Story'),
(4, 11, 'success_study_report', 'Study Report'),
(5, 12, 'success_research_report', 'rsc'),
(6, 13, 'success_assessment_report', 'asdfas'),
(7, 14, 'success_organogram', 'fasdf');

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
  `left_port` text DEFAULT NULL,
  `preferred_country` varchar(100) DEFAULT NULL,
  `departure_date` varchar(100) DEFAULT NULL,
  `migration_type` enum('regular','irregular','both') DEFAULT NULL,
  `visa_type` text DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `returned_age` varchar(50) DEFAULT NULL,
  `migration_duration` varchar(100) DEFAULT NULL,
  `migration_medias` text DEFAULT NULL,
  `migration_occupation` varchar(150) DEFAULT NULL,
  `destination_country_leave_reason` text DEFAULT NULL,
  `other_destination_country_leave_reason` text DEFAULT NULL,
  `earned_money` double DEFAULT NULL,
  `forced_work` enum('yes','no') DEFAULT NULL,
  `excessive_work` enum('yes','no') DEFAULT NULL,
  `employer_threatened` enum('yes','no') DEFAULT NULL,
  `final_destination` varchar(100) DEFAULT NULL,
  `migration_reasons` text DEFAULT NULL,
  `other_migration_reason` text DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_migrations`
--

INSERT INTO `dev_migrations` (`pk_migration_id`, `fk_customer_id`, `is_cheated`, `is_money_deducted`, `is_movement_limitation`, `is_kept_document`, `left_port`, `preferred_country`, `departure_date`, `migration_type`, `visa_type`, `return_date`, `returned_age`, `migration_duration`, `migration_medias`, `migration_occupation`, `destination_country_leave_reason`, `other_destination_country_leave_reason`, `earned_money`, `forced_work`, `excessive_work`, `employer_threatened`, `final_destination`, `migration_reasons`, `other_migration_reason`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 1, 'no', 'yes', 'no', 'yes', 'Dhaka', 'Desired destination', '2020-09-23', 'regular', 'student', '2020-09-23', NULL, 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"Relation\",\"media_address\":\"Address\"}', 'Occupation in overseas country', 'no_job,low_salary', NULL, 5000, 'yes', 'no', 'no', 'Final destination', 'higher_income,family_abroad', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, '', '', '', '', 'Jashore', 'Khulna', '2020-09-23', 'regular', 'student', '2020-09-23', NULL, 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"Relation\",\"media_address\":\"Address\"}', 'Occupation in overseas country', 'experienced_violence,no_accommodation', NULL, 5000, '', '', '', 'South Korea', 'higher_income', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, '', '', '', '', 'e', '4', '2020-11-05', 'irregular', 'student', '2018-12-24', 'Age: 26, Month: 0, Days: 6', 'Year: 1, Month: 10, Days: 17', '{\"departure_media\":\"4\",\"media_relation\":\"4\",\"media_address\":\"FASF\"}', 'Carpentar', '', NULL, 5000, '', '', '', '4', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created_by` int(11) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dev_projects`
--

INSERT INTO `dev_projects` (`pk_project_id`, `project_name`, `project_short_name`, `project_code`, `project_funded_by`, `project_start`, `project_end`, `project_status`, `project_target`, `create_date`, `create_time`, `created_by`, `update_date`, `update_time`, `updated_by`) VALUES
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
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `is_completed` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `dropout_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `review_session` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `final_evaluation` text COLLATE utf8_unicode_ci NOT NULL,
  `client_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `counsellor_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `required_session` enum('yes','no') COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `male_counseled` tinyint(2) DEFAULT NULL,
  `female_counseled` tinyint(2) DEFAULT NULL,
  `members_counseled` tinyint(2) DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_followups`
--

CREATE TABLE `dev_psycho_followups` (
  `pk_psycho_followup_id` bigint(20) UNSIGNED NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time NOT NULL,
  `followup_comments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_sessions`
--

CREATE TABLE `dev_psycho_sessions` (
  `pk_psycho_session_id` bigint(20) UNSIGNED NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `entry_time` time DEFAULT NULL,
  `session_comments` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `activities_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dev_psycho_supports`
--

CREATE TABLE `dev_psycho_supports` (
  `pk_psycho_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) UNSIGNED NOT NULL,
  `first_meeting` date NOT NULL,
  `problem_identified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `problem_description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `initial_plan` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `family_counseling` tinyint(3) NOT NULL,
  `session_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_duration` double DEFAULT NULL,
  `session_place` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_requirements` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `reffer_to` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `referr_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason_for_reffer` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_reason_for_reffer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_psycho_supports`
--

INSERT INTO `dev_psycho_supports` (`pk_psycho_support_id`, `fk_customer_id`, `first_meeting`, `problem_identified`, `problem_description`, `initial_plan`, `family_counseling`, `session_number`, `session_duration`, `session_place`, `other_requirements`, `reffer_to`, `referr_address`, `contact_number`, `reason_for_reffer`, `other_reason_for_reffer`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 2, '2020-09-23', '', '', '', 0, '', 0, '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, '2020-11-12', '', '', '', 0, '', 0, '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_reintegration_plan`
--

CREATE TABLE `dev_reintegration_plan` (
  `pk_reintegration_plan_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `plan_date` date DEFAULT NULL,
  `reintegration_financial_service` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_requested` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_service_requested` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `social_protection` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `security_measure` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_requested_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dev_reintegration_plan`
--

INSERT INTO `dev_reintegration_plan` (`pk_reintegration_plan_id`, `fk_customer_id`, `plan_date`, `reintegration_financial_service`, `service_requested`, `other_service_requested`, `social_protection`, `security_measure`, `service_requested_note`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 2, '0000-00-00', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, '0000-00-00', '', '', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_reintegration_satisfaction_scale`
--

CREATE TABLE `dev_reintegration_satisfaction_scale` (
  `pk_satisfaction_scale` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `satisfied_assistance` tinyint(2) DEFAULT NULL,
  `satisfied_assistance_date` date DEFAULT NULL,
  `satisfied_counseling` tinyint(2) DEFAULT NULL,
  `satisfied_counseling_date` date DEFAULT NULL,
  `satisfied_economic` tinyint(2) DEFAULT NULL,
  `satisfied_economic_date` date DEFAULT NULL,
  `satisfied_social` tinyint(2) DEFAULT NULL,
  `satisfied_social_date` date DEFAULT NULL,
  `satisfied_community` tinyint(2) DEFAULT NULL,
  `satisfied_community_date` date DEFAULT NULL,
  `satisfied_reintegration` tinyint(2) DEFAULT NULL,
  `satisfied_reintegration_date` date DEFAULT NULL,
  `total_score` int(3) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_reintegration_satisfaction_scale`
--

INSERT INTO `dev_reintegration_satisfaction_scale` (`pk_satisfaction_scale`, `fk_customer_id`, `entry_date`, `satisfied_assistance`, `satisfied_assistance_date`, `satisfied_counseling`, `satisfied_counseling_date`, `satisfied_economic`, `satisfied_economic_date`, `satisfied_social`, `satisfied_social_date`, `satisfied_community`, `satisfied_community_date`, `satisfied_reintegration`, `satisfied_reintegration_date`, `total_score`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, '2020-11-01', 4, '2020-11-01', 4, '1970-01-01', 5, '1970-01-01', 4, '1970-01-01', 3, '1970-01-01', 4, '1970-01-07', 24, NULL, NULL, NULL, '15:32:52', '2020-11-13', 1),
(4, 36, '2020-11-08', 0, '1970-01-01', 0, '1970-01-01', 0, '1970-01-01', 0, '1970-01-01', 0, '1970-01-01', 0, '1970-01-01', 0, '15:37:55', '2020-11-13', 1, NULL, NULL, NULL),
(5, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_returnees`
--

CREATE TABLE `dev_returnees` (
  `pk_returnee_id` bigint(20) NOT NULL,
  `fk_project_id` int(10) DEFAULT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `returnee_id` char(20) DEFAULT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `father_name` varchar(200) DEFAULT NULL,
  `mother_name` varchar(200) DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed','separated') DEFAULT NULL,
  `returnee_spouse` varchar(200) DEFAULT NULL,
  `returnee_gender` varchar(50) DEFAULT NULL,
  `educational_qualification` text DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `emergency_mobile` varchar(20) DEFAULT NULL,
  `nid_number` varchar(20) DEFAULT NULL,
  `birth_reg_number` varchar(25) DEFAULT NULL,
  `passport_number` varchar(20) DEFAULT NULL,
  `permanent_village` varchar(100) DEFAULT NULL,
  `permanent_union` varchar(100) DEFAULT NULL,
  `permanent_sub_district` varchar(100) DEFAULT NULL,
  `permanent_district` varchar(100) DEFAULT NULL,
  `permanent_division` varchar(30) DEFAULT NULL,
  `brac_info_id` varchar(30) DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `person_type` enum('trafficked_survivor','returnee_migrant') DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `destination_country` varchar(100) DEFAULT NULL,
  `legal_document` text DEFAULT NULL,
  `other_legal_document` text DEFAULT NULL,
  `remigrate_intention` enum('yes','no') DEFAULT NULL,
  `destination_country_profession` text DEFAULT NULL,
  `profile_selection` enum('yes','no') DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_returnees`
--

INSERT INTO `dev_returnees` (`pk_returnee_id`, `fk_project_id`, `fk_branch_id`, `returnee_id`, `full_name`, `father_name`, `mother_name`, `marital_status`, `returnee_spouse`, `returnee_gender`, `educational_qualification`, `mobile_number`, `emergency_mobile`, `nid_number`, `birth_reg_number`, `passport_number`, `permanent_village`, `permanent_union`, `permanent_sub_district`, `permanent_district`, `permanent_division`, `brac_info_id`, `collection_date`, `person_type`, `return_date`, `destination_country`, `legal_document`, `other_legal_document`, `remigrate_intention`, `destination_country_profession`, `profile_selection`, `remarks`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 0, 2, '24214', 'Full Name', 'Father Name', 'Mother Name', NULL, 'REW', 'male', 'sign', '23432', '3423', '234', '2342', '23423', 'Village', 'Union/Pourashava', 'Jhikargacha', 'Bogura', 'Rajshahi', 'DSDS', '2020-10-09', 'trafficked_survivor', '2020-10-09', 'SDASD', 'Passport', NULL, 'yes', 'SDASD', 'yes', 'SADASDA', '20:53:06', '2020-10-09', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_sharing_sessions`
--

CREATE TABLE `dev_sharing_sessions` (
  `pk_sharing_session_id` bigint(20) NOT NULL,
  `fk_training_id` bigint(20) NOT NULL,
  `traning_date` date DEFAULT NULL,
  `evaluator_profession` text DEFAULT NULL,
  `satisfied_training` int(2) DEFAULT NULL,
  `satisfied_supports` int(2) DEFAULT NULL,
  `satisfied_facilitation` int(2) DEFAULT NULL,
  `outcome_training` int(2) DEFAULT NULL,
  `trafficking_law` int(2) DEFAULT NULL,
  `policy_process` int(2) DEFAULT NULL,
  `all_contents` int(2) DEFAULT NULL,
  `recommendation` text DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_sharing_sessions`
--

INSERT INTO `dev_sharing_sessions` (`pk_sharing_session_id`, `fk_training_id`, `traning_date`, `evaluator_profession`, `satisfied_training`, `satisfied_supports`, `satisfied_facilitation`, `outcome_training`, `trafficking_law`, `policy_process`, `all_contents`, `recommendation`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(2, 2, '2020-11-30', 'NGO', 4, 0, 0, 0, 5, 0, 0, '', '19:10:16', '2020-11-30', 1, NULL, NULL, NULL),
(3, 1, '2020-11-30', 'Judicial govt. employee', 0, 0, 0, 0, 0, 0, 0, '', '19:22:32', '2020-11-30', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_social_supports`
--

CREATE TABLE `dev_social_supports` (
  `pk_social_support_id` bigint(20) NOT NULL,
  `fk_customer_id` bigint(20) NOT NULL,
  `reintegration_economic` text DEFAULT NULL,
  `other_reintegration_economic` text DEFAULT NULL,
  `soical_date` date DEFAULT NULL,
  `medical_date` date DEFAULT NULL,
  `date_housing` date DEFAULT NULL,
  `date_legal` date DEFAULT NULL,
  `date_education` date DEFAULT NULL,
  `support_referred` text DEFAULT NULL,
  `other_support_referred` text DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_social_supports`
--

INSERT INTO `dev_social_supports` (`pk_social_support_id`, `fk_customer_id`, `reintegration_economic`, `other_reintegration_economic`, `soical_date`, `medical_date`, `date_housing`, `date_legal`, `date_education`, `support_referred`, `other_support_referred`, `update_date`, `update_time`, `updated_by`, `create_date`, `create_time`, `created_by`) VALUES
(1, 2, '', NULL, '2020-09-23', '2020-09-23', '2020-09-23', '2020-09-23', '2020-09-23', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, '', NULL, '2020-11-12', '2020-11-12', '2020-11-12', '2020-11-12', '2020-11-12', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dev_targets`
--

CREATE TABLE `dev_targets` (
  `pk_target_id` bigint(20) NOT NULL,
  `fk_project_id` int(10) NOT NULL,
  `fk_branch_id` bigint(20) NOT NULL,
  `branch_district` varchar(100) DEFAULT NULL,
  `branch_sub_district` varchar(100) DEFAULT NULL,
  `month` varchar(5) NOT NULL,
  `fk_activity_id` bigint(20) NOT NULL,
  `activity_target` int(5) DEFAULT NULL,
  `achievement_male` int(3) DEFAULT NULL,
  `achievement_female` int(3) DEFAULT NULL,
  `achievement_boy` int(3) DEFAULT NULL,
  `achievement_girl` int(3) DEFAULT NULL,
  `achievement_total` int(5) DEFAULT NULL,
  `activity_achievement` int(5) DEFAULT 0,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_targets`
--

INSERT INTO `dev_targets` (`pk_target_id`, `fk_project_id`, `fk_branch_id`, `branch_district`, `branch_sub_district`, `month`, `fk_activity_id`, `activity_target`, `achievement_male`, `achievement_female`, `achievement_boy`, `achievement_girl`, `achievement_total`, `activity_achievement`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, 1, 1, 'Tangail', '', '01', 1, 5, 4, 4, 4, 4, 16, 1, '19:19:29', '2020-09-24', 1, '19:41:58', '2020-09-28', 2),
(2, 1, 1, 'Tangail', '', '01', 3, 5, 4, 4, 4, 4, 16, 5, '19:19:29', '2020-09-24', 1, '19:41:58', '2020-09-28', 2),
(3, 1, 1, 'Tangail', '', '11', 1, 10, NULL, NULL, NULL, NULL, NULL, 0, '17:35:21', '2020-11-28', 1, NULL, NULL, NULL),
(4, 1, 1, 'Tangail', '', '11', 3, 11, 2, 2, 1, 1, 6, 1, '17:35:21', '2020-11-28', 1, '17:37:35', '2020-11-28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dev_trainings`
--

CREATE TABLE `dev_trainings` (
  `pk_training_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `beneficiary_id` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `training_name` varchar(150) DEFAULT NULL,
  `workshop_name` varchar(100) DEFAULT NULL,
  `workshop_duration` varchar(50) DEFAULT NULL,
  `training_duration` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `age` varchar(30) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_trainings`
--

INSERT INTO `dev_trainings` (`pk_training_id`, `date`, `beneficiary_id`, `name`, `gender`, `profession`, `training_name`, `workshop_name`, `workshop_duration`, `training_duration`, `address`, `mobile`, `age`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `updated_by`) VALUES
(1, '2020-12-01', 'Beneficiary ID', 'Shaik Obydullah', 'male', 'Profession', 'PHP', 'Day Long', '2 Years', '4 Hours', 'Dhaka', '0154445748984', '43', '19:01:29', '2020-11-30', 1, NULL, NULL, NULL),
(2, '2020-11-30', 'Modhu', 'Modhu', 'male', 'Modhu', 'Modhu', 'Modhu', 'Modhu', 'Modhu', 'Modhu', '01977698715', '66', '19:09:32', '2020-11-30', 1, NULL, NULL, NULL);

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
(2, '', 'Demo User', NULL, 'demo', '', '', 'demo@brac.com', 0, '$2y$10$VYDvkyqcczI/lNi3skAVMOtlXoLpf1fJVNmA8GEm3p9mc92yEelii', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', '1', NULL, NULL, NULL, NULL, 0, 0, '2020-09-23 17:29:34', 1, '2020-09-23 17:29:34', 1, 0),
(3, '', 'Sheikh Arnov', NULL, 'sk_arnov', '', '', 'arnov@brac.com', 0, '$2y$10$B/zYSyRHK6O2iWPLiZ5.IuX/QtLhNGumKMQz35nwz6RJ29wnBg.Hm', 1, '0000-00-00', 'male', '', '', 0, NULL, 'admin', 'active', 1, 'user', NULL, NULL, NULL, NULL, NULL, 50, 1, '2020-09-23 21:02:39', 2, '2020-09-23 21:02:39', 2, 1);

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
(1, 2, 1);

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
(1, 'New  branch Jashore DRSC has been created', '', 'create', 'success', '2020-09-23 17:34:41', 2),
(2, 'Basic information of participant profile Full Name (ID: Participant ID) has been saved.', '', 'create', 'success', '2020-09-23 17:50:57', 2),
(3, 'Demo User has logged in.', '', 'login', 'success', '2020-09-23 20:41:15', 2),
(4, 'Demo User has logged in.', '', 'login', 'success', '2020-09-23 20:57:44', 2),
(5, 'The staff (ID: 3) has been created.', '', 'create', 'success', '2020-09-23 21:02:39', 2),
(6, 'Basic information of participant profile Shaik Obydullah (ID: ) has been saved.', '', 'create', 'success', '2020-09-23 21:15:38', 2),
(7, 'Information of case has been updated.', '', 'update', 'success', '2020-09-23 21:16:31', 2),
(8, 'Information of target has been saved.', '', 'create', 'success', '2020-09-23 21:20:11', 2),
(9, 'Event has been saved.', '', 'create', 'success', '2020-09-23 21:21:28', 2),
(10, 'Event has been saved.', '', 'create', 'success', '2020-09-23 21:22:17', 2),
(11, 'Demo User has logged out.', '', 'logout', 'success', '2020-09-23 22:50:58', 2),
(12, 'Sheikh Arnov has logged in.', '', 'login', 'success', '2020-09-23 22:51:06', 3),
(13, 'Sheikh Arnov has logged out.', '', 'logout', 'success', '2020-09-23 22:51:16', 3),
(14, 'Demo User has logged in.', '', 'login', 'success', '2020-09-23 22:51:29', 2),
(15, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-09-24 19:06:01', 1),
(16, 'Information of target has been saved.', '', 'create', 'success', '2020-09-24 19:19:29', 1),
(17, 'Event has been saved.', '', 'create', 'success', '2020-09-24 19:22:00', 1),
(18, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-09-24 19:59:59', 1),
(19, 'Event has been updated.', '', 'update', 'success', '2020-09-24 20:01:09', 1),
(20, 'Event has been updated.', '', 'update', 'success', '2020-09-24 20:01:28', 1),
(21, 'Event has been updated.', '', 'update', 'success', '2020-09-24 20:02:20', 1),
(22, 'Event has been updated.', '', 'update', 'success', '2020-09-24 20:02:49', 1),
(23, 'Demo User has logged in.', '', 'login', 'success', '2020-09-28 14:19:25', 2),
(24, 'Demo User has logged in.', '', 'login', 'success', '2020-09-28 17:26:52', 2),
(25, 'Demo User has logged in.', '', 'login', 'success', '2020-09-28 19:33:43', 2),
(26, 'Event has been saved.', '', 'create', 'success', '2020-09-28 19:36:16', 2),
(27, 'Event has been saved.', '', 'create', 'success', '2020-09-28 19:42:40', 2),
(28, 'Demo User has logged in.', '', 'login', 'success', '2020-09-29 18:06:58', 2),
(29, 'Demo User has logged in.', '', 'login', 'success', '2020-09-29 19:18:09', 2),
(30, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-01 17:56:24', 1),
(31, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-06 12:26:09', 1),
(32, 'Jack (ID: immediate_support) has been turned on', '', 'update', 'success', '2020-10-06 16:30:17', 1),
(33, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-08 19:33:01', 1),
(34, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-09 12:37:50', 1),
(35, 'Basic information of returnee profile Full Name (ID: 24214) has been saved.', '', 'create', 'success', '2020-10-09 20:53:06', 1),
(36, 'Access To Public And Private Support information has been saved.', '', 'create', 'success', '2020-10-10 18:43:21', 1),
(37, 'Access To Public And Private Support information has been saved.', '', 'create', 'success', '2020-10-10 18:44:03', 1),
(38, 'Access To Public And Private Support information has been saved.', '', 'create', 'success', '2020-10-10 18:45:27', 1),
(39, 'Access To Public And Private Support information has been saved.', '', 'create', 'success', '2020-10-10 18:49:27', 1),
(40, 'Access To Public And Private Support information has been updated.', '', 'update', 'success', '2020-10-10 19:21:33', 1),
(41, 'Access To Public And Private Support information has been updated.', '', 'update', 'success', '2020-10-10 19:22:04', 1),
(42, 'Access To Public And Private Support information has been updated.', '', 'update', 'success', '2020-10-10 19:22:53', 1),
(43, 'Access To Public And Private Support information has been updated.', '', 'update', 'success', '2020-10-10 19:25:25', 1),
(44, 'Access To Public And Private Support information has been updated.', '', 'update', 'success', '2020-10-10 19:25:42', 1),
(45, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-11 15:03:23', 1),
(46, 'Immediate assistance after arrival information has been saved.', '', 'create', 'success', '2020-10-11 19:10:22', 1),
(47, 'Immediate assistance after arrival information has been updated.', '', 'update', 'success', '2020-10-11 19:11:50', 1),
(48, 'Immediate assistance after arrival information has been updated.', '', 'update', 'success', '2020-10-11 19:12:19', 1),
(49, 'Immediate assistance after arrival information has been updated.', '', 'update', 'success', '2020-10-11 19:13:46', 1),
(50, 'Immediate assistance after arrival information has been updated.', '', 'update', 'success', '2020-10-11 19:16:06', 1),
(51, 'Immediate assistance after arrival information has been updated.', '', 'update', 'success', '2020-10-11 19:16:28', 1),
(52, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-11 19:26:12', 1),
(53, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-13 11:14:08', 1),
(54, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-15 09:49:34', 1),
(55, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-22 16:00:49', 1),
(56, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-27 18:17:57', 1),
(57, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-10-29 11:34:48', 1),
(58, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-03 13:37:22', 1),
(59, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-04 19:44:55', 1),
(60, 'Basic information of participant profile Full Namee (ID: Participant ID) has been saved.', '', 'create', 'success', '2020-11-05 15:19:22', 1),
(61, 'Basic information of participant profile Full Namee (ID: Participant ID) has been updated.', '', 'update', 'success', '2020-11-05 16:10:37', 1),
(62, 'Basic information of participant profile Full Namee (ID: Participant ID) has been updated.', '', 'update', 'success', '2020-11-05 16:25:51', 1),
(63, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-07 20:27:46', 1),
(64, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-08 15:19:52', 1),
(65, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-09 16:36:07', 1),
(66, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-10 15:36:45', 1),
(67, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-10 19:09:38', 1),
(68, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-10 21:55:01', 1),
(69, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-11 00:12:57', 1),
(70, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-11 11:13:20', 1),
(71, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-12 11:56:28', 1),
(72, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-12 15:31:11', 1),
(73, 'Information of case has been updated.', '', 'update', 'success', '2020-11-12 15:56:56', 1),
(74, 'Information of case has been updated.', '', 'update', 'success', '2020-11-12 15:59:27', 1),
(75, 'Information of case has been updated.', '', 'update', 'success', '2020-11-12 16:01:32', 1),
(76, 'Case Review Data has been updated.', '', 'update', 'success', '2020-11-12 16:34:03', 1),
(77, 'Case Review Data has been saved.', '', 'create', 'success', '2020-11-12 16:39:22', 1),
(78, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-12 17:56:33', 1),
(79, 'Case Review Data has been saved.', '', 'create', 'success', '2020-11-12 18:25:43', 1),
(80, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-12 19:23:21', 1),
(81, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-13 14:10:28', 1),
(82, 'Basic information of participant profile S (ID: ) has been saved.', '', 'create', 'success', '2020-11-14 01:15:19', 1),
(83, 'Basic information of participant profile S (ID: ) has been updated.', '', 'update', 'success', '2020-11-14 01:41:13', 1),
(84, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-14 16:26:44', 1),
(85, 'Story has been saved.', '', 'create', 'success', '2020-11-14 18:05:10', 1),
(86, 'Story has been saved.', '', 'create', 'success', '2020-11-14 18:18:35', 1),
(87, 'Story has been updated.', '', 'update', 'success', '2020-11-14 18:21:12', 1),
(88, 'Story has been updated.', '', 'update', 'success', '2020-11-14 18:21:22', 1),
(89, 'Story has been updated.', '', 'update', 'success', '2020-11-14 18:21:47', 1),
(90, 'Study Report has been saved.', '', 'create', 'success', '2020-11-14 19:28:52', 1),
(91, 'Research Report has been saved.', '', 'create', 'success', '2020-11-14 19:42:28', 1),
(92, 'Assessment Report has been saved.', '', 'create', 'success', '2020-11-14 20:05:39', 1),
(93, 'Organogram has been saved.', '', 'create', 'success', '2020-11-14 20:07:44', 1),
(94, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-15 01:39:03', 1),
(95, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-15 10:13:21', 1),
(96, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-16 18:02:08', 1),
(97, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 11:12:05', 1),
(98, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 11:17:23', 1),
(99, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 15:55:42', 1),
(100, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 18:45:38', 1),
(101, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 19:47:51', 1),
(102, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-17 22:43:35', 1),
(103, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-18 10:41:45', 1),
(104, 'Training has been saved.', '', 'create', 'success', '2020-11-18 11:13:31', 1),
(105, 'Complain Investigation has been saved.', '', 'create', 'success', '2020-11-18 11:22:45', 1),
(106, 'Complain Investigation has been updated.', '', 'update', 'success', '2020-11-18 11:26:02', 1),
(107, 'Complain Investigation has been updated.', '', 'update', 'success', '2020-11-18 11:29:23', 1),
(108, 'Complain Investigation has been updated.', '', 'update', 'success', '2020-11-18 11:32:26', 1),
(109, 'Complain Filed has been saved.', '', 'create', 'success', '2020-11-18 11:46:32', 1),
(110, 'Complain Filed has been updated.', '', 'update', 'success', '2020-11-18 11:46:47', 1),
(111, 'Complain has been saved.', '', 'create', 'success', '2020-11-18 11:49:46', 1),
(112, 'Complain has been updated.', '', 'update', 'success', '2020-11-18 11:50:06', 1),
(113, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-22 12:18:05', 1),
(114, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-23 11:40:06', 1),
(115, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-23 19:57:27', 1),
(116, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-25 12:58:41', 1),
(117, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-25 17:55:30', 1),
(118, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-26 17:40:37', 1),
(119, 'Complain Investigation has been updated.', '', 'update', 'success', '2020-11-26 19:39:46', 1),
(120, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-28 16:34:55', 1),
(121, 'Information of target has been saved.', '', 'create', 'success', '2020-11-28 17:35:21', 1),
(122, 'Event has been saved.', '', 'create', 'success', '2020-11-28 17:37:35', 1),
(123, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-30 13:36:55', 1),
(124, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-30 16:41:33', 1),
(125, 'Training/Workshop Validation has been saved.', '', 'create', 'success', '2020-11-30 18:39:13', 1),
(126, 'Training has been saved.', '', 'create', 'success', '2020-11-30 19:01:29', 1),
(127, 'Training has been saved.', '', 'create', 'success', '2020-11-30 19:09:32', 1),
(128, 'Training/Workshop Validation has been saved.', '', 'create', 'success', '2020-11-30 19:10:16', 1),
(129, 'Training/Workshop Validation has been saved.', '', 'create', 'success', '2020-11-30 19:22:32', 1),
(130, '3DEVs IT LTD has logged in.', '', 'login', 'success', '2020-11-30 22:13:05', 1);

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
(1, 'admin', 'Admin', 'Head Office Administration', 'a:79:{s:22:\"manage_user_activities\";s:3:\"yes\";s:21:\"flush_user_activities\";s:3:\"yes\";s:19:\"access_to_dashboard\";s:3:\"yes\";s:15:\"manage_branches\";s:3:\"yes\";s:10:\"add_branch\";s:3:\"yes\";s:11:\"edit_branch\";s:3:\"yes\";s:22:\"configure_branch_types\";s:3:\"yes\";s:16:\"manage_customers\";s:3:\"yes\";s:12:\"add_customer\";s:3:\"yes\";s:13:\"edit_customer\";s:3:\"yes\";s:15:\"delete_customer\";s:3:\"yes\";s:12:\"manage_cases\";s:3:\"yes\";s:8:\"add_case\";s:3:\"yes\";s:9:\"edit_case\";s:3:\"yes\";s:11:\"delete_case\";s:3:\"yes\";s:13:\"manage_events\";s:3:\"yes\";s:9:\"add_event\";s:3:\"yes\";s:10:\"edit_event\";s:3:\"yes\";s:12:\"delete_event\";s:3:\"yes\";s:24:\"manage_event_validations\";s:3:\"yes\";s:20:\"add_event_validation\";s:3:\"yes\";s:21:\"edit_event_validation\";s:3:\"yes\";s:23:\"delete_event_validation\";s:3:\"yes\";s:16:\"manage_complains\";s:3:\"yes\";s:12:\"add_complain\";s:3:\"yes\";s:13:\"edit_complain\";s:3:\"yes\";s:15:\"delete_complain\";s:3:\"yes\";s:22:\"manage_complain_fileds\";s:3:\"yes\";s:18:\"add_complain_filed\";s:3:\"yes\";s:19:\"edit_complain_filed\";s:3:\"yes\";s:21:\"delete_complain_filed\";s:3:\"yes\";s:30:\"manage_complain_investigations\";s:3:\"yes\";s:26:\"add_complain_investigation\";s:3:\"yes\";s:27:\"edit_complain_investigation\";s:3:\"yes\";s:29:\"delete_complain_investigation\";s:3:\"yes\";s:16:\"manage_trainings\";s:3:\"yes\";s:12:\"add_training\";s:3:\"yes\";s:13:\"edit_training\";s:3:\"yes\";s:15:\"delete_training\";s:3:\"yes\";s:14:\"manage_stories\";s:3:\"yes\";s:9:\"add_story\";s:3:\"yes\";s:10:\"edit_story\";s:3:\"yes\";s:12:\"delete_story\";s:3:\"yes\";s:20:\"manage_study_reports\";s:3:\"yes\";s:16:\"add_study_report\";s:3:\"yes\";s:17:\"edit_study_report\";s:3:\"yes\";s:19:\"delete_study_report\";s:3:\"yes\";s:23:\"manage_research_reports\";s:3:\"yes\";s:19:\"add_research_report\";s:3:\"yes\";s:20:\"edit_research_report\";s:3:\"yes\";s:22:\"delete_research_report\";s:3:\"yes\";s:25:\"manage_assessment_reports\";s:3:\"yes\";s:21:\"add_assessment_report\";s:3:\"yes\";s:22:\"edit_assessment_report\";s:3:\"yes\";s:24:\"delete_assessment_report\";s:3:\"yes\";s:18:\"manage_organograms\";s:3:\"yes\";s:14:\"add_organogram\";s:3:\"yes\";s:15:\"edit_organogram\";s:3:\"yes\";s:17:\"delete_organogram\";s:3:\"yes\";s:20:\"manage_misactivities\";s:3:\"yes\";s:15:\"add_misactivity\";s:3:\"yes\";s:16:\"edit_misactivity\";s:3:\"yes\";s:18:\"delete_misactivity\";s:3:\"yes\";s:14:\"manage_targets\";s:3:\"yes\";s:10:\"add_target\";s:3:\"yes\";s:11:\"edit_target\";s:3:\"yes\";s:13:\"delete_target\";s:3:\"yes\";s:19:\"manage_achievements\";s:3:\"yes\";s:15:\"add_achievement\";s:3:\"yes\";s:16:\"edit_achievement\";s:3:\"yes\";s:18:\"delete_achievement\";s:3:\"yes\";s:17:\"manage_misreports\";s:3:\"yes\";s:14:\"view_misreport\";s:3:\"yes\";s:15:\"manage_projects\";s:3:\"yes\";s:11:\"add_project\";s:3:\"yes\";s:12:\"edit_project\";s:3:\"yes\";s:13:\"manage_staffs\";s:3:\"yes\";s:9:\"add_staff\";s:3:\"yes\";s:10:\"edit_staff\";s:3:\"yes\";}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dev_access_to_pp`
--
ALTER TABLE `dev_access_to_pp`
  ADD PRIMARY KEY (`pk_access_id`);

--
-- Indexes for table `dev_activities`
--
ALTER TABLE `dev_activities`
  ADD PRIMARY KEY (`pk_activity_id`);

--
-- Indexes for table `dev_airport_land_supports`
--
ALTER TABLE `dev_airport_land_supports`
  ADD PRIMARY KEY (`pk_support_id`);

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
-- Indexes for table `dev_events`
--
ALTER TABLE `dev_events`
  ADD PRIMARY KEY (`pk_event_id`);

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
-- Indexes for table `dev_returnees`
--
ALTER TABLE `dev_returnees`
  ADD PRIMARY KEY (`pk_returnee_id`);

--
-- Indexes for table `dev_sharing_sessions`
--
ALTER TABLE `dev_sharing_sessions`
  ADD PRIMARY KEY (`pk_sharing_session_id`);

--
-- Indexes for table `dev_social_supports`
--
ALTER TABLE `dev_social_supports`
  ADD PRIMARY KEY (`pk_social_support_id`);

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
-- AUTO_INCREMENT for table `dev_access_to_pp`
--
ALTER TABLE `dev_access_to_pp`
  MODIFY `pk_access_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_activities`
--
ALTER TABLE `dev_activities`
  MODIFY `pk_activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_airport_land_supports`
--
ALTER TABLE `dev_airport_land_supports`
  MODIFY `pk_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_branches`
--
ALTER TABLE `dev_branches`
  MODIFY `pk_branch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `dev_branch_types`
--
ALTER TABLE `dev_branch_types`
  MODIFY `pk_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_complains`
--
ALTER TABLE `dev_complains`
  MODIFY `pk_complain_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_complain_fileds`
--
ALTER TABLE `dev_complain_fileds`
  MODIFY `pk_complain_filed_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `pk_customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_customer_health`
--
ALTER TABLE `dev_customer_health`
  MODIFY `pk_customer_health_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_customer_skills`
--
ALTER TABLE `dev_customer_skills`
  MODIFY `pk_customer_skills_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_economic_profile`
--
ALTER TABLE `dev_economic_profile`
  MODIFY `pk_economic_profile_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_economic_reintegration_referrals`
--
ALTER TABLE `dev_economic_reintegration_referrals`
  MODIFY `pk_economic_referral_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_economic_supports`
--
ALTER TABLE `dev_economic_supports`
  MODIFY `pk_economic_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_events`
--
ALTER TABLE `dev_events`
  MODIFY `pk_event_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `dev_event_validations`
--
ALTER TABLE `dev_event_validations`
  MODIFY `pk_validation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_followups`
--
ALTER TABLE `dev_followups`
  MODIFY `pk_followup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_immediate_supports`
--
ALTER TABLE `dev_immediate_supports`
  MODIFY `pk_immediate_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_initial_evaluation`
--
ALTER TABLE `dev_initial_evaluation`
  MODIFY `pk_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_knowledge`
--
ALTER TABLE `dev_knowledge`
  MODIFY `pk_knowledge_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `dev_lookups`
--
ALTER TABLE `dev_lookups`
  MODIFY `pk_lookup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dev_migrations`
--
ALTER TABLE `dev_migrations`
  MODIFY `pk_migration_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_migration_documents`
--
ALTER TABLE `dev_migration_documents`
  MODIFY `pk_document_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_projects`
--
ALTER TABLE `dev_projects`
  MODIFY `pk_project_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dev_psycho_completions`
--
ALTER TABLE `dev_psycho_completions`
  MODIFY `pk_psycho_completion_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_psycho_family_counselling`
--
ALTER TABLE `dev_psycho_family_counselling`
  MODIFY `pk_psycho_family_counselling_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_psycho_followups`
--
ALTER TABLE `dev_psycho_followups`
  MODIFY `pk_psycho_followup_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_psycho_sessions`
--
ALTER TABLE `dev_psycho_sessions`
  MODIFY `pk_psycho_session_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_psycho_supports`
--
ALTER TABLE `dev_psycho_supports`
  MODIFY `pk_psycho_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_reintegration_plan`
--
ALTER TABLE `dev_reintegration_plan`
  MODIFY `pk_reintegration_plan_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_reintegration_satisfaction_scale`
--
ALTER TABLE `dev_reintegration_satisfaction_scale`
  MODIFY `pk_satisfaction_scale` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dev_returnees`
--
ALTER TABLE `dev_returnees`
  MODIFY `pk_returnee_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_sharing_sessions`
--
ALTER TABLE `dev_sharing_sessions`
  MODIFY `pk_sharing_session_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_social_supports`
--
ALTER TABLE `dev_social_supports`
  MODIFY `pk_social_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_targets`
--
ALTER TABLE `dev_targets`
  MODIFY `pk_target_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dev_trainings`
--
ALTER TABLE `dev_trainings`
  MODIFY `pk_training_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_users`
--
ALTER TABLE `dev_users`
  MODIFY `pk_user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_users_roles_relation`
--
ALTER TABLE `dev_users_roles_relation`
  MODIFY `pk_rel_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_user_activities`
--
ALTER TABLE `dev_user_activities`
  MODIFY `pk_activity_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `dev_user_meta`
--
ALTER TABLE `dev_user_meta`
  MODIFY `pk_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_user_roles`
--
ALTER TABLE `dev_user_roles`
  MODIFY `pk_role_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
