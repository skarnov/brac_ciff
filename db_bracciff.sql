-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 24, 2020 at 03:45 PM
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
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dev_activities`
--

INSERT INTO `dev_activities` (`pk_activity_id`, `fk_project_id`, `activity_name`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 1, 'Activity II', NULL, NULL, NULL, '14:15:50', '2020-09-20', 1),
(2, 5, 'Activity II', '11:47:56', '2020-09-17', 1, '14:15:35', '2020-09-20', 1),
(3, 1, 'Activity I', NULL, NULL, NULL, '14:15:13', '2020-09-20', 1);

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
  `modified_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 'Participant ID', 'Full Name', 'Father Name', 'Mother Name', NULL, 'married', '', '2020-09-23', 'male', NULL, 'NID Number', 'Passport No', '', NULL, NULL, 'sign', '01719020278', 'Emergency Mobile No ', 'Name of that person', 'Relation with Participant ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Address', NULL, 'Village', 'Ward No', 'Union', NULL, NULL, NULL, 'Jashore Sadar', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-09-23', '17:50:57', 2, NULL, NULL, NULL, NULL, NULL),
(2, '', 'Shaik Obydullah', 'Father Name', 'Mother Name', NULL, 'single', '', '2020-09-23', 'female', NULL, '', '', 'Birth Registration Number', NULL, NULL, 'ssc', '01719020274', 'Emergency Mobile No ', 'Name of that person', 'Relation with Participant ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dhaka', NULL, '', '', '', NULL, NULL, NULL, 'Sharsha', 'jashore', 'khulna', NULL, NULL, 'active', 'ciff', '2020-09-23', '21:15:38', 2, NULL, NULL, NULL, NULL, NULL);

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
(1, 1, 'no', '', 'no', '', NULL, NULL),
(2, 2, 'yes', 'Type of disability', 'no', '', NULL, NULL);

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
(1, 1, NULL, '', 'yes', 'block_batiks', NULL, NULL, NULL, NULL),
(2, 2, NULL, '', 'yes', 'cultivation', NULL, NULL, NULL, NULL);

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
(1, 1, 'Main occupation before', 'Main occupation after', 6000, 50, 20, 30, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 6000, NULL, NULL, NULL, NULL, 'rental', 'live'),
(2, 2, 'Main occupation before', 'Main occupation after', 6000, 6, 3, 3, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 6000, NULL, NULL, NULL, NULL, 'own', 'pucca');

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
(1, 2, '', '', NULL, '', '', '', '', '', '', NULL, '', '', '2020-09-23', '', '', '', '', '');

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
(1, 0, '', NULL, '', 0, 2, NULL, '0000-00-00', NULL, NULL, '', 'Mining and quarrying', NULL, NULL, NULL, '', '', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', '', '', '', '2020-09-23', NULL, '', '', '', '2020-09-23', '2020-09-23');

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
  `participant_boy` int(2) DEFAULT NULL,
  `participant_girl` int(2) DEFAULT NULL,
  `participant_male` int(2) DEFAULT NULL,
  `participant_female` int(2) DEFAULT NULL,
  `validation_count` int(3) NOT NULL DEFAULT 0,
  `preparatory_work` int(2) DEFAULT NULL,
  `time_management` int(2) DEFAULT NULL,
  `participants_attention` int(2) DEFAULT NULL,
  `logistical_arrangements` int(2) DEFAULT NULL,
  `relevancy_delivery` int(2) DEFAULT NULL,
  `participants_feedback` int(2) DEFAULT NULL,
  `observation_score` int(3) DEFAULT NULL,
  `event_note` text DEFAULT NULL,
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

INSERT INTO `dev_events` (`pk_event_id`, `fk_branch_id`, `month`, `fk_project_id`, `fk_activity_id`, `event_division`, `event_district`, `event_upazila`, `event_union`, `event_location`, `event_village`, `event_ward`, `event_start_date`, `event_start_time`, `event_end_date`, `event_end_time`, `participant_boy`, `participant_girl`, `participant_male`, `participant_female`, `validation_count`, `preparatory_work`, `time_management`, `participants_attention`, `logistical_arrangements`, `relevancy_delivery`, `participants_feedback`, `observation_score`, `event_note`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 50, 1, 1, 3, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-23', '21:20:45', '2020-09-23', '21:20:45', 2, 2, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, '', '21:21:28', '2020-09-23', 2, NULL, NULL, NULL),
(2, 50, 1, 1, 3, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-23', '21:21:45', '2020-09-23', '21:21:45', 2, 2, 3, 3, 0, 5, 4, 3, 5, 4, 2, 23, '', '21:22:17', '2020-09-23', 2, NULL, NULL, NULL),
(3, 1, 1, 1, 3, 'khulna', 'jashore', 'Jashore Sadar', '', '', '', '', '2020-09-24', '19:21:18', '2020-09-24', '19:21:18', 2, 2, 3, 3, 0, 5, 5, 4, 5, 5, 4, 28, '', '19:22:00', '2020-09-24', 1, NULL, NULL, NULL);

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
(1, NULL, NULL, NULL, NULL, 2, '0000-00-00', '', '', '', NULL, NULL, NULL, '', '', NULL, '', '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', NULL, NULL, NULL);

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
(1, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 0, 0, 2, 'Information provision', NULL, NULL, NULL, '2020-09-23', '21:16:31', 2);

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
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `dev_lookups`
--

INSERT INTO `dev_lookups` (`pk_lookup_id`, `fk_content_id`, `lookup_group`, `lookup_value`) VALUES
(1, 0, 'staff_designation', 'Branch Manager');

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
(1, 1, 'no', 'yes', 'no', 'yes', NULL, 'Dhaka', 'Desired destination', '2020-09-23', NULL, NULL, 'regular', 'student', '2020-09-23', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"Relation\",\"media_address\":\"Address\"}', NULL, NULL, 'Occupation in overseas country', 'no_job,low_salary', NULL, 5000, NULL, NULL, 'yes', 'no', 'no', 'Final destination', 'higher_income,family_abroad', NULL),
(2, 2, '', '', '', '', NULL, 'Jashore', 'Khulna', '2020-09-23', NULL, NULL, 'regular', 'student', '2020-09-23', 'Year: 0, Month: 0, Days: 0', '{\"departure_media\":\"Name\",\"media_relation\":\"Relation\",\"media_address\":\"Address\"}', NULL, NULL, 'Occupation in overseas country', 'experienced_violence,no_accommodation', NULL, 5000, NULL, NULL, '', '', '', 'South Korea', 'higher_income', NULL);

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
(1, 2, 0, NULL, '2020-09-23', NULL, NULL, NULL, '', '', '', NULL, NULL, '', 0, '', 0, '', '', NULL, '', '', '', '', NULL, '', '', NULL);

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
(1, 2, NULL, '', '', NULL, NULL, NULL, '');

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
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
(1, NULL, 2, NULL, NULL, NULL, NULL, NULL, '', '', '', NULL, '', NULL, '2020-09-23', '2020-09-23', '2020-09-23', '2020-09-23', '', '', NULL, '2020-09-23');

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
  `activity_achievement` int(5) DEFAULT NULL,
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

INSERT INTO `dev_targets` (`pk_target_id`, `fk_project_id`, `fk_branch_id`, `branch_district`, `branch_sub_district`, `month`, `fk_activity_id`, `activity_target`, `achievement_male`, `achievement_female`, `achievement_boy`, `achievement_girl`, `achievement_total`, `activity_achievement`, `create_time`, `create_date`, `created_by`, `update_time`, `update_date`, `modified_by`) VALUES
(1, 1, 1, 'Tangail', '', '01', 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, '19:19:29', '2020-09-24', 1, NULL, NULL, NULL),
(2, 1, 1, 'Tangail', '', '01', 3, 5, 3, 3, 2, 2, 10, 1, '19:19:29', '2020-09-24', 1, '19:22:00', '2020-09-24', 1);

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
(17, 'Event has been saved.', '', 'create', 'success', '2020-09-24 19:22:00', 1);

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
-- AUTO_INCREMENT for table `dev_activities`
--
ALTER TABLE `dev_activities`
  MODIFY `pk_activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `pk_complain_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_complain_fileds`
--
ALTER TABLE `dev_complain_fileds`
  MODIFY `pk_complain_filed_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_complain_investigations`
--
ALTER TABLE `dev_complain_investigations`
  MODIFY `pk_complain_investigation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_config`
--
ALTER TABLE `dev_config`
  MODIFY `config_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `dev_customers`
--
ALTER TABLE `dev_customers`
  MODIFY `pk_customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_customer_health`
--
ALTER TABLE `dev_customer_health`
  MODIFY `pk_customer_health_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_customer_skills`
--
ALTER TABLE `dev_customer_skills`
  MODIFY `pk_customer_skills_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_economic_profile`
--
ALTER TABLE `dev_economic_profile`
  MODIFY `pk_economic_profile_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_economic_reintegration_referrals`
--
ALTER TABLE `dev_economic_reintegration_referrals`
  MODIFY `pk_economic_referral_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_economic_supports`
--
ALTER TABLE `dev_economic_supports`
  MODIFY `pk_economic_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_events`
--
ALTER TABLE `dev_events`
  MODIFY `pk_event_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dev_event_validations`
--
ALTER TABLE `dev_event_validations`
  MODIFY `pk_validation_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_followups`
--
ALTER TABLE `dev_followups`
  MODIFY `pk_followup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_immediate_supports`
--
ALTER TABLE `dev_immediate_supports`
  MODIFY `pk_immediate_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_initial_evaluation`
--
ALTER TABLE `dev_initial_evaluation`
  MODIFY `pk_evaluation_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_knowledge`
--
ALTER TABLE `dev_knowledge`
  MODIFY `pk_knowledge_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dev_lookups`
--
ALTER TABLE `dev_lookups`
  MODIFY `pk_lookup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_migrations`
--
ALTER TABLE `dev_migrations`
  MODIFY `pk_migration_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `pk_psycho_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_reintegration_plan`
--
ALTER TABLE `dev_reintegration_plan`
  MODIFY `pk_reintegration_plan_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_reintegration_satisfaction_scale`
--
ALTER TABLE `dev_reintegration_satisfaction_scale`
  MODIFY `pk_satisfaction_scale` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_sharing_sessions`
--
ALTER TABLE `dev_sharing_sessions`
  MODIFY `pk_sharing_session_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dev_social_supports`
--
ALTER TABLE `dev_social_supports`
  MODIFY `pk_social_support_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dev_targets`
--
ALTER TABLE `dev_targets`
  MODIFY `pk_target_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dev_trainings`
--
ALTER TABLE `dev_trainings`
  MODIFY `pk_training_id` bigint(20) NOT NULL AUTO_INCREMENT;

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
  MODIFY `pk_activity_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
