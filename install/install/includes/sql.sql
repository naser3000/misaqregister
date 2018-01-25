-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2018 at 12:41 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `misaqregister`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit`
--

CREATE TABLE `audit` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  `viewed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `audit`
--

INSERT INTO `audit` (`id`, `user`, `page`, `timestamp`, `ip`, `viewed`) VALUES
(1, 1, '42', '2017-02-20 17:31:13', '::1', 0),
(2, 3, '12', '2017-09-14 06:09:56', '::1', 0),
(3, 0, '12', '2017-09-14 07:15:04', '::1', 0),
(4, 0, '4', '2017-09-14 07:15:20', '::1', 0),
(5, 0, '10', '2017-09-15 11:27:02', '::1', 0),
(6, 0, '10', '2017-09-15 11:27:13', '::1', 0),
(7, 0, '3', '2017-09-16 20:04:53', '::1', 0),
(8, 0, '12', '2017-09-23 01:45:56', '::1', 0),
(9, 0, '12', '2017-09-23 01:45:58', '::1', 0),
(10, 0, '4', '2017-09-23 03:24:24', '::1', 0),
(11, 0, '3', '2017-09-24 17:12:15', '::1', 0),
(12, 0, '4', '2017-09-25 03:15:08', '::1', 0),
(13, 0, '3', '2017-09-25 14:36:59', '::1', 0),
(14, 0, '3', '2017-09-26 03:22:19', '::1', 0),
(15, 0, '3', '2017-09-27 02:03:45', '::1', 0),
(16, 0, '3', '2017-09-27 02:46:02', '::1', 0),
(17, 0, '3', '2017-09-27 02:48:02', '::1', 0),
(18, 0, '3', '2017-09-27 02:55:11', '::1', 0),
(19, 0, '3', '2017-09-28 10:03:18', '::1', 0),
(20, 0, '3', '2017-10-02 16:25:50', '::1', 0),
(21, 0, '3', '2017-10-02 19:21:35', '::1', 0),
(22, 0, '24', '2017-10-03 01:50:54', '::1', 0),
(23, 0, '24', '2017-10-03 01:50:56', '::1', 0),
(24, 0, '3', '2017-10-03 04:24:17', '::1', 0),
(25, 0, '3', '2017-10-05 02:03:56', '::1', 0),
(26, 0, '3', '2017-10-05 02:05:32', '::1', 0),
(27, 0, '3', '2017-10-05 02:06:07', '::1', 0),
(28, 0, '3', '2017-10-05 02:09:19', '::1', 0),
(29, 0, '3', '2017-10-05 02:13:19', '::1', 0),
(30, 0, '3', '2017-10-05 02:15:10', '::1', 0),
(31, 0, '3', '2017-10-05 02:16:04', '::1', 0),
(32, 0, '3', '2017-10-05 02:17:22', '::1', 0),
(33, 0, '3', '2017-10-05 09:55:53', '::1', 0),
(34, 0, '3', '2017-10-05 09:55:56', '::1', 0),
(35, 0, '3', '2017-10-06 01:45:17', '::1', 0),
(36, 0, '3', '2017-10-10 09:24:08', '::1', 0),
(37, 0, '3', '2017-10-11 05:12:16', '::1', 0),
(38, 0, '3', '2017-10-11 16:50:45', '::1', 0),
(39, 0, '3', '2017-10-12 09:28:44', '::1', 0),
(40, 0, '3', '2017-10-13 10:06:19', '::1', 0),
(41, 0, '3', '2017-10-14 16:54:43', '::1', 0),
(42, 0, '24', '2017-10-14 18:17:19', '::1', 0),
(43, 26, '6', '2017-10-14 19:24:49', '::1', 0),
(44, 26, '4', '2017-10-14 19:25:05', '::1', 0),
(45, 0, '5', '2017-10-14 19:46:31', '::1', 0),
(46, 0, '3', '2017-10-15 02:07:45', '::1', 0),
(47, 0, '3', '2017-10-15 16:51:57', '::1', 0),
(48, 0, '3', '2017-10-16 02:04:20', '::1', 0),
(49, 0, '3', '2017-10-17 02:29:10', '::1', 0),
(50, 0, '3', '2017-10-17 15:49:09', '::1', 0),
(51, 0, '3', '2017-10-18 15:36:00', '::1', 0),
(52, 0, '3', '2017-10-19 01:55:36', '::1', 0),
(53, 0, '3', '2017-10-19 03:10:32', '::1', 0),
(54, 0, '3', '2017-10-19 04:39:14', '::1', 0),
(55, 0, '3', '2017-10-19 09:08:58', '::1', 0),
(56, 0, '3', '2017-10-19 18:00:09', '::1', 0),
(57, 0, '3', '2017-10-20 03:44:27', '::1', 0),
(58, 0, '24', '2017-10-20 09:51:08', '::1', 0),
(59, 16, '4', '2017-10-20 10:29:51', '::1', 0),
(60, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(61, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(62, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(63, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(64, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(65, 10, '3', '2017-10-20 10:48:48', '::1', 0),
(66, 10, '3', '2017-10-20 10:48:49', '::1', 0),
(67, 10, '3', '2017-10-20 10:48:49', '::1', 0),
(68, 10, '3', '2017-10-20 10:48:49', '::1', 0),
(69, 10, '3', '2017-10-20 10:48:49', '::1', 0),
(70, 10, '3', '2017-10-20 10:48:56', '::1', 0),
(71, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(72, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(73, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(74, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(75, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(76, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(77, 10, '3', '2017-10-20 10:48:57', '::1', 0),
(78, 10, '3', '2017-10-20 10:48:58', '::1', 0),
(79, 10, '3', '2017-10-20 10:48:58', '::1', 0),
(80, 2, '4', '2017-10-20 11:31:14', '::1', 0),
(81, 2, '6', '2017-10-20 11:32:48', '::1', 0),
(82, 2, '8', '2017-10-20 11:40:32', '::1', 0),
(83, 22, '27', '2017-10-20 12:45:10', '::1', 0),
(84, 10, '24', '2017-10-24 13:09:37', '::1', 0),
(85, 10, '24', '2017-10-24 13:09:41', '::1', 0),
(86, 10, '22', '2017-10-24 13:09:51', '::1', 0),
(87, 0, '3', '2017-10-24 15:21:51', '::1', 0),
(88, 20, '27', '2017-10-24 15:55:04', '::1', 0),
(89, 0, '3', '2017-10-24 16:55:53', '::1', 0),
(90, 0, '3', '2017-10-24 16:55:53', '::1', 0),
(91, 0, '3', '2017-10-25 06:54:15', '::1', 0),
(92, 0, '3', '2017-10-26 02:03:53', '::1', 0),
(93, 0, '3', '2017-10-28 03:12:45', '::1', 0),
(94, 0, '3', '2017-10-28 03:12:46', '::1', 0),
(95, 0, '3', '2017-10-28 03:44:01', '::1', 0),
(96, 0, '3', '2017-10-29 18:22:42', '::1', 0),
(97, 10, '24', '2017-12-25 15:27:49', '::1', 0),
(98, 10, '24', '2017-12-25 15:27:53', '::1', 0),
(99, 10, '4', '2017-12-25 15:28:24', '::1', 0),
(100, 10, '10', '2017-12-25 15:28:57', '::1', 0),
(101, 10, '24', '2017-12-25 15:29:08', '::1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `capacity`
--

CREATE TABLE `capacity` (
  `status` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `yinter` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gender` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `cost` int(11) NOT NULL,
  `capacity_number` int(11) NOT NULL,
  `participant_number` int(11) NOT NULL,
  `participant_cost` int(11) NOT NULL,
  `registered` int(11) NOT NULL,
  `reserved` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `capacity`
--

INSERT INTO `capacity` (`status`, `yinter`, `gender`, `cost`, `capacity_number`, `participant_number`, `participant_cost`, `registered`, `reserved`, `plan_id`, `id`) VALUES
('ÙØ§Ø±Øº Ø§Ù„ØªØ­ØµÛŒÙ„,   Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '85, 86', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 1, 1),
('Ø§Ø³ØªØ§Ø¯,   Ø¢Ø²Ø§Ø¯', '85, 86', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 1, 2),
('Ø§Ø³ØªØ§Ø¯,   Ø¢Ø²Ø§Ø¯', '85, 86', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 1, 3),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '88', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 2, 5),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '88', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 2, 6),
('ÙØ§Ø±Øº Ø§Ù„ØªØ­ØµÛŒÙ„,   Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '87, 94', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 20000, 52, 2, 20000, 0, 0, 3, 7),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '96', 'Ø¨Ø¯ÙˆÙ† Ø§Ù‡Ù…ÛŒØª', 0, 0, 0, 0, 0, 0, 3, 15),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ (90), Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ (91), Ø¯Ú©ØªØ±Ø§ (92), 95', 'Ø¢Ù‚Ø§', 23000, 23, 2, 12000, 0, 0, 4, 17),
('ÙØ§Ø±Øº Ø§Ù„ØªØ­ØµÛŒÙ„,   Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ø¯Ú©ØªØ±Ø§ (86), Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ (90), 92', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 2356, 55, 3, 2136, 0, 0, 1, 20),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ,   Ø§Ø³ØªØ§Ø¯,   Ø¢Ø²Ø§Ø¯', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ (96)', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 7500, 0, 3, 6000, 0, 142, 1, 21),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ (96)', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 13000, 15, 2, 14000, 10, 0, 3, 23),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '95', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 50000, 100, 1, 55000, 0, 0, 5, 24),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '95, 96', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 10000, 15, 2, 12000, 0, 0, 6, 26),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ ( - )', 'Ø¢Ù‚Ø§', 12500, 3, 2, 13000, 0, 0, 2, 29),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯ ( - )', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 12500, 3, 2, 13000, 0, 0, 2, 30),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯', 'Ø¢Ù‚Ø§, Ø®Ø§Ù†Ù…', 12500, 3, 2, 13000, 3, 4, 2, 32),
('Ø¯Ø§Ù†Ø´Ø¬Ùˆ', 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯', 'Ø¢Ù‚Ø§', 12500, 3, 2, 13000, 3, 3, 2, 33);

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `website_name` varchar(100) NOT NULL,
  `smtp_server` varchar(100) NOT NULL,
  `smtp_port` int(10) NOT NULL,
  `email_login` varchar(150) NOT NULL,
  `email_pass` varchar(100) NOT NULL,
  `from_name` varchar(100) NOT NULL,
  `from_email` varchar(150) NOT NULL,
  `transport` varchar(255) NOT NULL,
  `verify_url` varchar(255) NOT NULL,
  `email_act` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`id`, `website_name`, `smtp_server`, `smtp_port`, `email_login`, `email_pass`, `from_name`, `from_email`, `transport`, `verify_url`, `email_act`) VALUES
(1, 'User Spice', 'smtp.gmail.com', 465, 'naser.shokri7@gmail.com', '4310741691', 'Your Name', 'naser.shokri7@gmail.com', 'tls', 'http://localhost/misaqregister/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `stripe_ts` varchar(255) NOT NULL,
  `stripe_tp` varchar(255) NOT NULL,
  `stripe_ls` varchar(255) NOT NULL,
  `stripe_lp` varchar(255) NOT NULL,
  `recap_pub` varchar(100) NOT NULL,
  `recap_pri` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `msg_from` int(11) NOT NULL,
  `msg_to` int(11) NOT NULL,
  `msg_body` text NOT NULL,
  `msg_read` int(1) NOT NULL,
  `msg_thread` int(11) NOT NULL,
  `deleted` int(1) NOT NULL,
  `sent_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_threads`
--

CREATE TABLE `message_threads` (
  `id` int(11) NOT NULL,
  `msg_to` int(11) NOT NULL,
  `msg_from` int(11) NOT NULL,
  `msg_subject` varchar(255) NOT NULL,
  `last_update` datetime NOT NULL,
  `last_update_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `private` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page`, `private`) VALUES
(1, 'index.php', 0),
(2, 'z_us_root.php', 0),
(3, 'users/account.php', 1),
(4, 'users/admin.php', 1),
(5, 'users/admin_page.php', 1),
(6, 'users/admin_pages.php', 1),
(7, 'users/admin_permission.php', 1),
(8, 'users/admin_permissions.php', 1),
(9, 'users/admin_user.php', 1),
(10, 'users/admin_users.php', 1),
(11, 'users/edit_profile.php', 1),
(12, 'users/email_settings.php', 1),
(13, 'users/email_test.php', 1),
(14, 'users/forgot_password.php', 0),
(15, 'users/forgot_password_reset.php', 0),
(16, 'users/index.php', 0),
(17, 'users/init.php', 0),
(18, 'users/join.php', 0),
(19, 'users/joinThankYou.php', 0),
(20, 'users/login.php', 0),
(21, 'users/logout.php', 0),
(22, 'users/profile.php', 1),
(23, 'users/times.php', 0),
(24, 'users/user_settings.php', 1),
(25, 'users/verify.php', 0),
(26, 'users/verify_resend.php', 0),
(27, 'users/view_all_users.php', 1),
(28, 'usersc/empty.php', 0),
(31, 'users/oauth_success.php', 0),
(33, 'users/fb-callback.php', 0),
(37, 'users/check_updates.php', 1),
(38, 'users/google_helpers.php', 0),
(39, 'users/tomfoolery.php', 1),
(40, 'users/create_message.php', 1),
(41, 'users/messages.php', 1),
(42, 'users/message.php', 1),
(44, 'users/admin_backup.php', 1),
(45, 'users/maintenance.php', 0),
(48, 'users/admin_add_plan.php', 0),
(49, 'users/admin_plans.php', 0),
(50, 'users/admin_plan.php', 0),
(51, 'users/data_completion.php', 0),
(52, 'users/user_plan.php', 0),
(53, 'users/developer.php', 1),
(54, 'users/epay.php', 0),
(55, 'users/epay_verify.php', 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`) VALUES
(1, 'Ú©Ø§Ø±Ø¨Ø±'),
(2, 'Ù…Ø¯ÛŒØ± Ø¨Ø±Ù†Ø§Ù…Ù‡'),
(3, 'Ù…Ø¯ÛŒØ± Ø³Ø§ÛŒØª'),
(4, 'ØªÙˆØ³Ø¹Ù‡ Ø¯Ù‡Ù†Ø¯Ù‡');

-- --------------------------------------------------------

--
-- Table structure for table `permission_page_matches`
--

CREATE TABLE `permission_page_matches` (
  `id` int(11) NOT NULL,
  `permission_id` int(15) NOT NULL,
  `page_id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permission_page_matches`
--

INSERT INTO `permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
(3, 1, 24),
(4, 1, 22),
(7, 1, 11),
(15, 1, 3),
(27, 1, 42),
(29, 1, 41),
(30, 1, 40),
(32, 2, 46),
(33, 3, 46),
(34, 2, 47),
(35, 3, 47),
(36, 2, 48),
(37, 3, 48),
(38, 2, 49),
(39, 3, 49),
(40, 2, 50),
(41, 3, 50),
(42, 1, 51),
(43, 2, 51),
(44, 3, 51),
(45, 1, 52),
(46, 2, 52),
(47, 3, 52),
(49, 3, 27),
(50, 3, 24),
(51, 3, 22),
(52, 3, 13),
(53, 3, 12),
(54, 3, 11),
(55, 3, 10),
(56, 3, 9),
(57, 3, 4),
(58, 3, 3),
(59, 2, 3),
(60, 4, 53),
(61, 4, 44),
(62, 4, 42),
(63, 4, 41),
(64, 4, 40),
(65, 4, 39),
(66, 4, 37),
(67, 4, 27),
(68, 4, 24),
(69, 4, 22),
(70, 4, 13),
(71, 4, 12),
(72, 4, 11),
(73, 4, 10),
(74, 4, 9),
(75, 4, 8),
(76, 4, 7),
(77, 4, 6),
(78, 4, 5),
(79, 4, 4),
(80, 4, 3),
(81, 1, 55),
(82, 2, 55),
(83, 3, 55),
(84, 4, 55);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `title` text CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `register_start_time` time NOT NULL,
  `register_end_time` time NOT NULL,
  `confirm_end_time` time NOT NULL,
  `plan_start_time` time NOT NULL,
  `plan_end_time` time NOT NULL,
  `register_start_date` date NOT NULL,
  `register_end_date` date NOT NULL,
  `confirm_end_date` date NOT NULL,
  `plan_start_date` date NOT NULL,
  `plan_end_date` date NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`title`, `description`, `register_start_time`, `register_end_time`, `confirm_end_time`, `plan_start_time`, `plan_end_time`, `register_start_date`, `register_end_date`, `confirm_end_date`, `plan_start_date`, `plan_end_date`, `id`) VALUES
('Ù‚Ù…', 'Ø§Ø±Ø¯ÙˆÛŒ Ù‚Ù… Ùˆ Ø¬Ù…Ú©Ø±Ø§Ù† Ø¢Ø®Ø± Ù‡ÙØªÙ‡ Ø¨Ø±Ù‚Ø±Ø§Ø± Ù…ÛŒ&lrm;Ø¨Ø§Ø´Ø¯. Ø«Ø¨Øª Ù†Ø§Ù… Ú©Ù†ÛŒØ¯.', '06:55:00', '14:44:00', '14:44:00', '14:44:00', '14:44:00', '1396-07-28', '1396-07-30', '1396-07-29', '1396-07-13', '1396-07-10', 1),
('Ø§Ø±Ø¯ÙˆÛŒ Ù…Ø´Ù‡Ø¯.', 'Ø¨Ø±ÛŒÙ… Ù‚Ù…. Ø¨ÛŒØ§ Ø¨Ø±ÛŒÙ… Ø¯ÛŒÚ¯Ù‡. Ø¨Ø±ÛŒÙ… Ù‚Ù…. Ø¨ÛŒØ§ Ø¨Ø±ÛŒÙ… Ø¯ÛŒÚ¯Ù‡. Ø¨Ø±ÛŒÙ… Ù‚Ù…. Ø¨ÛŒØ§ Ø¨Ø±ÛŒÙ… Ø¯ÛŒÚ¯Ù‡.', '14:46:00', '14:46:00', '14:46:00', '14:46:00', '14:46:00', '1396-07-28', '1396-08-04', '1396-08-05', '1396-08-10', '1396-08-15', 2),
('Ù‚Ù…', 'ØªØ³Øª Ø§Ø¬Ø±Ø§ÛŒ Ø§Ø¶Ø§ÙÙ‡ Ú©Ø±Ø¯Ù† Ø§Ø±Ø¯ÙˆÛŒ Ù‚Ù….\r\nØªØ³Øª Ø§Ø¬Ø±Ø§ÛŒ Ø§Ø¶Ø§ÙÙ‡ Ú©Ø±Ø¯Ù† Ø§Ø±Ø¯ÙˆÛŒ Ù‚Ù….\r\nØªØ³Øª Ø§Ø¬Ø±Ø§ÛŒ Ø§Ø¶Ø§ÙÙ‡ Ú©Ø±Ø¯Ù† Ø§Ø±Ø¯ÙˆÛŒ Ù‚Ù….', '16:15:00', '16:15:00', '16:15:00', '16:15:00', '16:15:00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 3),
('Ø«Ø¨Øª Ù†Ø§Ù… Ø¨Ø±Ù†Ø§Ù…Ù‡', 'ØªØ³Øª Ø¯Ø±Ø³ Ú©Ø§Ø±Ú©Ø±Ø¯Ù† Ø«Ø¨Øª Ù†Ø§Ù… Ø¯Ø± Ø¨Ø±Ù†Ø§Ù…Ù‡ Ù‡Ø§ ØªÙˆØ³Ø· Ú©Ø§Ø±Ø¨Ø±. ØªØ³Øª Ø¯Ø±Ø³ Ú©Ø§Ø±Ú©Ø±Ø¯Ù† Ø«Ø¨Øª Ù†Ø§Ù… Ø¯Ø± Ø¨Ø±Ù†Ø§Ù…Ù‡ Ù‡Ø§ ØªÙˆØ³Ø· Ú©Ø§Ø±Ø¨Ø±.', '10:53:00', '10:53:00', '10:53:00', '10:53:00', '10:53:00', '0000-00-00', '1396-07-27', '0000-00-00', '0000-00-00', '0000-00-00', 4),
('Ú©Ø±Ø¨Ù„Ø§', 'Ø§Ø±Ø¯ÙˆÛŒ Ú©Ø±Ø¨Ù„Ø§ Ø¢ØºØ§Ø² Ø´Ø¯. Ø§Ø±Ø¯ÙˆÛŒ Ú©Ø±Ø¨Ù„Ø§ Ø¢ØºØ§Ø² Ø´Ø¯. Ø§Ø±Ø¯ÙˆÛŒ Ú©Ø±Ø¨Ù„Ø§ Ø¢ØºØ§Ø² Ø´Ø¯. Ø§Ø±Ø¯ÙˆÛŒ Ú©Ø±Ø¨Ù„Ø§ Ø¢ØºØ§Ø² Ø´Ø¯.', '06:25:00', '06:25:00', '06:25:00', '06:25:00', '06:25:00', '1396-07-30', '1396-08-05', '1396-08-10', '1396-08-09', '1396-08-30', 5),
('Ø§Ø±Ø¯ÙˆÛŒ Ø¬Ù…Ú©Ø±Ø§Ù†', 'Ø§Ø±Ø¯ÙˆÛŒ Ø¬Ù…Ú©Ø±Ø§Ù† Ø¢Ø®Ø± Ù‡ÙØªÙ‡ Ø¨Ø±Ù‚Ø±Ø§Ø± Ø§Ø³Øª. Ø§Ø±Ø¯ÙˆÛŒ Ø¬Ù…Ú©Ø±Ø§Ù† Ø¢Ø®Ø± Ù‡ÙØªÙ‡ Ø¨Ø±Ù‚Ø±Ø§Ø± Ø§Ø³Øª. Ø§Ø±Ø¯ÙˆÛŒ Ø¬Ù…Ú©Ø±Ø§Ù† Ø¢Ø®Ø± Ù‡ÙØªÙ‡ Ø¨Ø±Ù‚Ø±Ø§Ø± Ø§Ø³Øª.', '22:15:00', '22:15:00', '22:15:00', '22:15:00', '22:15:00', '1396-07-28', '1396-07-30', '1396-08-01', '1396-08-03', '1396-08-05', 6);

-- --------------------------------------------------------

--
-- Table structure for table `plan_register`
--

CREATE TABLE `plan_register` (
  `id` int(6) NOT NULL,
  `user_id` int(6) NOT NULL,
  `plan_id` int(6) NOT NULL,
  `capacity_id` int(6) NOT NULL,
  `status` varchar(20) NOT NULL,
  `reserved_number` int(3) NOT NULL,
  `participant_name1` varchar(35) NOT NULL,
  `participant_code1` varchar(15) NOT NULL,
  `reserved_number1` int(3) NOT NULL,
  `participant_name2` varchar(35) NOT NULL,
  `participant_code2` varchar(15) NOT NULL,
  `reserved_number2` int(3) NOT NULL,
  `participant_name3` varchar(35) NOT NULL,
  `participant_code3` varchar(15) NOT NULL,
  `reserved_number3` int(11) NOT NULL,
  `participant_gender1` varchar(10) NOT NULL,
  `participant_gender2` varchar(10) NOT NULL,
  `participant_gender3` varchar(10) NOT NULL,
  `paid_cost` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `plan_register`
--

INSERT INTO `plan_register` (`id`, `user_id`, `plan_id`, `capacity_id`, `status`, `reserved_number`, `participant_name1`, `participant_code1`, `reserved_number1`, `participant_name2`, `participant_code2`, `reserved_number2`, `participant_name3`, `participant_code3`, `reserved_number3`, `participant_gender1`, `participant_gender2`, `participant_gender3`, `paid_cost`) VALUES
(75, 22, 4, 18, 'Ø«Ø¨Øª Ù†Ø§Ù…', 0, 'Ú©Ø±ÛŒÙ… Ù‡Ø¬Ø±Ø§Ù† ÙØ±', '1230589647', 0, 'Ø®Ø§Ù†Ù…', '2583014769', 0, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø®Ø§Ù†Ù…', '', 22000),
(77, 16, 3, 23, 'Ø«Ø¨Øª Ù†Ø§Ù…', 0, 'Ø¨Ø±Ø§Ø¯Ø±Ù…', '2536014789', 0, '', '', 0, '', '', 0, 'Ø¢Ù‚Ø§', '', '', 27000),
(82, 20, 3, 23, 'Ø«Ø¨Øª Ù†Ø§Ù…', 0, '', '', 0, '', '', 0, '', '', 0, '', '', '', 13000),
(87, 16, 1, 21, 'Ø±Ø²Ø±Ùˆ', 137, 'Ù¾Ø¯Ø± Ø­Ø³Ù†ÛŒ', '0258369741', 139, 'Ø¨Ø±Ø§Ø¯Ø± Ø­Ø³Ù†ÛŒ', '2583697410', 139, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø¢Ù‚Ø§', '', 19500),
(88, 21, 1, 21, 'Ø±Ø²Ø±Ùˆ', 141, 'Ø¨Ø±Ø§Ø¯Ø±', '2589631470', 142, '', '', 0, '', '', 0, 'Ø¢Ù‚Ø§', '', '', 13500),
(89, 16, 2, 31, 'Ø«Ø¨Øª Ù†Ø§Ù…', 0, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯', '1234567890', 0, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯ 2', '0258369741', 0, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø¢Ù‚Ø§', '', 38500),
(91, 20, 2, 32, 'Ø±Ø²Ø±Ùˆ', 0, 'Ø§ÙØ´ÛŒÙ† Ù…Ø´Ù‡Ø¯', '2456389170', 0, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯ 2', '2583691470', 4, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø®Ø§Ù†Ù…', '', 38500),
(92, 16, 2, 32, 'Ø±Ø²Ø±Ùˆ', 0, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯', '2583691470', 1, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯ 2', '3698521470', 2, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø®Ø§Ù†Ù…', '', 38500),
(93, 20, 2, 33, 'Ø±Ø²Ø±Ùˆ', 0, 'Ø§ÙØ´ÛŒÙ† Ù…Ø´Ù‡Ø¯', '0321456897', 0, 'Ù¾Ø¯Ø±', '0258369741', 3, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø¢Ù‚Ø§', '', 38500),
(94, 21, 2, 33, 'Ø±Ø²Ø±Ùˆ', 0, 'Ù‡Ù…Ø±Ø§Ù‡ Ù…Ø´Ù‡Ø¯', '2583691470', 1, 'Ù…Ø§Ø¯Ø±', '0258369741', 2, '', '', 0, 'Ø¢Ù‚Ø§', 'Ø®Ø§Ù†Ù…', '', 38500),
(95, 21, 2, 32, 'Ø±Ø²Ø±Ùˆ', 3, '', '', 0, '', '', 0, '', '', 0, '', '', '', 12500);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `bio`) VALUES
(1, 1, '<h1>This is the Admin\'s bio.</h1>'),
(2, 2, 'This is your bio'),
(10, 10, 'This is your bio'),
(15, 15, 'This is your bio'),
(16, 16, 'This is your bio'),
(17, 17, 'This is your bio'),
(18, 18, 'This is your bio'),
(19, 19, 'This is your bio'),
(20, 20, 'This is your bio'),
(21, 21, 'This is your bio'),
(22, 22, 'This is your bio'),
(23, 23, 'This is your bio'),
(24, 23, 'This is your bio'),
(26, 25, 'This is your bio'),
(28, 27, 'This is your bio'),
(29, 28, 'This is your bio');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(50) NOT NULL,
  `recaptcha` int(1) NOT NULL DEFAULT '0',
  `force_ssl` int(1) NOT NULL,
  `login_type` varchar(20) NOT NULL,
  `css_sample` int(1) NOT NULL,
  `us_css1` varchar(255) NOT NULL,
  `us_css2` varchar(255) NOT NULL,
  `us_css3` varchar(255) NOT NULL,
  `css1` varchar(255) NOT NULL,
  `css2` varchar(255) NOT NULL,
  `css3` varchar(255) NOT NULL,
  `site_name` varchar(100) NOT NULL,
  `language` varchar(255) NOT NULL,
  `track_guest` int(1) NOT NULL,
  `site_offline` int(1) NOT NULL,
  `force_pr` int(1) NOT NULL,
  `reserved1` varchar(100) NOT NULL,
  `reserverd2` varchar(100) NOT NULL,
  `custom1` varchar(100) NOT NULL,
  `custom2` varchar(100) NOT NULL,
  `custom3` varchar(100) NOT NULL,
  `glogin` int(1) NOT NULL DEFAULT '0',
  `fblogin` int(1) NOT NULL,
  `gid` varchar(255) NOT NULL,
  `gsecret` varchar(255) NOT NULL,
  `gredirect` varchar(255) NOT NULL,
  `ghome` varchar(255) NOT NULL,
  `fbid` varchar(255) NOT NULL,
  `fbsecret` varchar(255) NOT NULL,
  `fbcallback` varchar(255) NOT NULL,
  `graph_ver` varchar(255) NOT NULL,
  `finalredir` varchar(255) NOT NULL,
  `req_cap` int(1) NOT NULL,
  `req_num` int(1) NOT NULL,
  `min_pw` int(2) NOT NULL,
  `max_pw` int(3) NOT NULL,
  `min_un` int(2) NOT NULL,
  `max_un` int(3) NOT NULL,
  `messaging` int(1) NOT NULL,
  `snooping` int(1) NOT NULL,
  `echouser` int(11) NOT NULL,
  `wys` int(1) NOT NULL,
  `change_un` int(1) NOT NULL,
  `backup_dest` varchar(255) NOT NULL,
  `backup_source` varchar(255) NOT NULL,
  `backup_table` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `recaptcha`, `force_ssl`, `login_type`, `css_sample`, `us_css1`, `us_css2`, `us_css3`, `css1`, `css2`, `css3`, `site_name`, `language`, `track_guest`, `site_offline`, `force_pr`, `reserved1`, `reserverd2`, `custom1`, `custom2`, `custom3`, `glogin`, `fblogin`, `gid`, `gsecret`, `gredirect`, `ghome`, `fbid`, `fbsecret`, `fbcallback`, `graph_ver`, `finalredir`, `req_cap`, `req_num`, `min_pw`, `max_pw`, `min_un`, `max_un`, `messaging`, `snooping`, `echouser`, `wys`, `change_un`, `backup_dest`, `backup_source`, `backup_table`) VALUES
(1, 0, 0, '', 1, '../users/css/color_schemes/standard.css', '../users/css/bootstrap-rtl.css', '../users/css/misaq.css', '', '', '', 'Ú©Ø§Ù†ÙˆÙ† Ù…ÛŒØ«Ø§Ù‚', 'en', 1, 0, 0, '', '', '', '', '', 0, 0, 'Google ID Here', 'Google Secret Here', 'http://localhost/userspice/users/oauth_success.php', 'http://localhost/userspice/', 'FB ID Here', 'FB Secret Here', 'http://localhost/userspice/users/fb-callback.php', 'v2.2', 'account.php', 1, 1, 6, 20, 2, 40, 0, 1, 0, 1, 0, '', 'everything', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(155) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `permissions` int(11) NOT NULL,
  `logins` int(100) NOT NULL,
  `account_owner` tinyint(4) NOT NULL DEFAULT '0',
  `account_id` int(11) NOT NULL DEFAULT '0',
  `company` varchar(255) NOT NULL,
  `stripe_cust_id` varchar(255) NOT NULL,
  `billing_phone` varchar(20) NOT NULL,
  `billing_srt1` varchar(255) NOT NULL,
  `billing_srt2` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_zip_code` varchar(255) NOT NULL,
  `join_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `email_verified` tinyint(4) NOT NULL DEFAULT '0',
  `vericode` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `active` int(1) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `std_number` varchar(255) NOT NULL,
  `yinter` int(255) NOT NULL,
  `grade` varchar(25) DEFAULT NULL,
  `emp_number` varchar(255) NOT NULL,
  `oauth_provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gpluslink` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `fb_uid` varchar(255) NOT NULL,
  `un_changed` int(1) NOT NULL,
  `phnumber` varchar(11) NOT NULL,
  `icode` varchar(10) NOT NULL,
  `account_charge` int(11) NOT NULL DEFAULT '0',
  `major` varchar(50) DEFAULT NULL,
  `dorms` varchar(30) DEFAULT NULL,
  `interested` varchar(10) DEFAULT 'Ø®ÛŒØ±',
  `data_completion` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `fname`, `lname`, `permissions`, `logins`, `account_owner`, `account_id`, `company`, `stripe_cust_id`, `billing_phone`, `billing_srt1`, `billing_srt2`, `billing_city`, `billing_state`, `billing_zip_code`, `join_date`, `last_login`, `email_verified`, `vericode`, `title`, `active`, `status`, `std_number`, `yinter`, `grade`, `emp_number`, `oauth_provider`, `oauth_uid`, `gender`, `locale`, `gpluslink`, `picture`, `created`, `modified`, `fb_uid`, `un_changed`, `phnumber`, `icode`, `account_charge`, `major`, `dorms`, `interested`, `data_completion`) VALUES
(3, 'naser.shokri7@gmail.com', 'nasershokri', '$2y$12$J1ZCuxOvvVoqQttMq4PD7uBsArdPgRo64uBXwufL7wYvM7l/awFCS', 'naser', 'shokri', 1, 135, 1, 0, 'nsh', '', '', '', '', '', '', '', '2016-01-01 00:00:00', '2017-10-29 18:49:32', 1, '322418', '', 0, 'ØªÙˆØ³Ø¹Ù‡ Ø¯Ù‡Ù†Ø¯Ù‡', '', 0, NULL, '', '', '', 'آقا', '', '', '', '0000-00-00 00:00:00', '1899-11-30 00:00:00', '', 0, '0', '0', 0, NULL, NULL, NULL, 1),
(4, 'misaaq@gmail.com', 'Ù…ÛŒØ«Ø§Ù‚', '$2y$12$1iQGNRPPzcXHqUFFCgCOXOVqQyUq7xdR8Slu7TdSIDtFlgZuiUkj6', 'Ø³Ø§ÛŒØª', 'Ù…ÛŒØ«Ø§Ù‚', 1, 12, 1, 0, 'میثاق', '', '', '', '', '', '', '', '2016-01-02 00:00:00', '2017-10-26 02:04:01', 1, '970748', '', 1, 'Ù…Ø¯ÛŒØ± Ø³Ø§ÛŒØª', '', 0, NULL, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '0', '0', 0, NULL, NULL, NULL, 1),
(1, 'userspicephp@gmail.com', 'admin', '$2y$12$1v06jm2KMOXuuo3qP7erTuTIJFOnzhpds1Moa8BadnUUeX0RV3ex.', 'Dan', 'Hoover', 1, 40, 1, 0, 'UserSpice', '', '', '', '', '', '', '', '2016-01-01 00:00:00', '2017-02-20 10:58:05', 1, '322418', '', 0, 0, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '1899-11-30 00:00:00', '', 0),
(2, 'noreply@userspice.com', 'user', '$2y$12$HZa0/d7evKvuHO8I3U8Ff.pOjJqsGTZqlX8qURratzP./EvWetbkK', 'Sample', 'User', 1, 5, 1, 0, 'none', '', '', '', '', '', '', '', '2016-01-02 00:00:00', '2017-02-20 12:14:10', 1, '970748', '', 1, 0, '', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0),
(10, 'naser.shokri@chmail.ir', 'ÛŒÙˆØ³Ù', '$2y$12$78dhgmdryyc8L5gmVNLk.eRYa1q/6XQYPeVfSh.H1pkWq0aZWg/Au', 'ÛŒÙˆØ³Ù', 'Ø³Ù„ÛŒÙ…Ø§Ù†ÛŒ', 1, 10, 1, 0, '', '', '', '', '', '', '', '', '2017-09-14 07:21:08', '2017-12-25 15:27:41', 1, '946469', '', 1, 'Ù…Ø¯ÛŒØ± Ø¨Ø±Ù†Ø§Ù…Ù‡ Ù‡Ø§', '', 0, NULL, '', '', '', 'آقا', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '0', '0', 0, NULL, NULL, NULL, 1),
(16, 'hasan@gmail.com', 'Ø­Ø³Ù†', '$2y$12$t99TOaoDe.stQdQi0/OwR.sPd73vJNmwcad6DwWAcpfUskY/Zitmu', 'Ø­Ø³Ù†', 'Ø­Ø³Ù†ÛŒ', 1, 20, 1, 0, '', '', '', '', '', '', '', '', '2017-09-23 02:56:20', '2017-10-26 02:24:14', 1, '947979', '', 1, 'Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '96230146', 96, 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯', '', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '0', '0', 58500, NULL, '', 'Ø®ÛŒØ±', 1),
(17, 'r@gmail.com', 'Ø±', '$2y$12$oXrJTfT64.LAKVizdgkpS.wD7WmySc5a/tYUL8hlC7CHPzJIGU31O', 'Ø±', 'Ø±ÛŒ', 1, 0, 1, 0, '', '', '', '', '', '', '', '', '2017-09-23 02:59:25', '0000-00-00 00:00:00', 1, '449145', '', 1, 'Ø§Ø³ØªØ§Ø¯', '', 0, '0', '0', '', '', 'Ø®Ø§Ù†Ù…', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '0', '0', 0, NULL, NULL, NULL, 1),
(18, 'h@gmail.com', 'Ù‡', '$2y$12$Mw7t4TvzDjVaOSGAeko8leIVKQCBWc2ZVbNxvlDxOit.al8tGsHeG', 'Ù‡', 'Ù‡ÛŒ', 1, 0, 1, 0, '', '', '', '', '', '', '', '', '2017-09-23 03:02:18', '0000-00-00 00:00:00', 0, '647640', '', 1, 'Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '95310427', 95, '3', '0', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '0', '0', 0, NULL, NULL, NULL, 1),
(20, 'a.f.t@gmail.com', 'Ø§ÙØ´ÛŒÙ†', '$2y$12$oBSHwRW4VVEJg.6HLMqo3OPTf9juHMLael0KkF7WAC2OGPobvDriS', 'Ø§ÙØ´ÛŒÙ†', 'Ú©Ø´ØªÚ©Ø§Ø±', 1, 39, 1, 0, '', '', '', '', '', '', '', '', '2017-09-24 17:13:05', '2017-10-29 19:37:49', 1, '970776', '', 1, 'Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '96203648', 96, 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯', '', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '09394165691', '4310741692', 23922, 'Ø¨Ø±Ù‚ (Ø§Ù„Ú©ØªØ±ÙˆÙ†ÛŒÚ©)', 'Ø·Ø±Ø´Øª 3', 'Ø¨Ù„Ù‡', 1),
(21, 'e.b@gmail.com', 'Ø§Ø­Ø³Ø§Ù†', '$2y$12$ikyLatloxaUS3PwVV8lqCeAyvD9hkYydAc0hUmcE54BkQRUv8kteC', 'Ø§Ø­Ø³Ø§Ù†', 'Ø¨Ø±Ú©Ù…', 1, 39, 1, 0, '', '', '', '', '', '', '', '', '2017-09-25 02:50:18', '2017-12-25 15:15:30', 1, '961849', '', 1, 'Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '96210546', 96, 'Ú©Ø§Ø±Ø´Ù†Ø§Ø³ÛŒ Ø§Ø±Ø´Ø¯', '', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '09127851378', '5896341207', 9000, 'Ø¨Ø±Ù‚', 'Ø·Ø±Ø´Øª 3', 'Ø®ÛŒØ±', 1),
(22, 'k.h@gmail.com', 'Ú©Ø§Ø¸Ù…', '$2y$12$n1xqOVBXMZaKRzuChQFrd.XoVnTPnSdtbLj0pG.yP3CmPU8pjSCmO', 'Ú©Ø§Ø¸Ù…', 'Ù‡Ø¬Ø±Ø§Ù† ÙØ±', 1, 9, 1, 0, '', '', '', '', '', '', '', '', '2017-09-25 03:03:24', '2017-10-20 12:45:04', 1, '415939', '', 1, 'Ø§Ø³ØªØ§Ø¯', '', 0, NULL, '', '', '', 'Ø®Ø§Ù†Ù…', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '09394165692', '4310741693', 3000, NULL, NULL, 'Ø®ÛŒØ±', 1),
(23, 'n.a@gmail.com', 'Ù†ÛŒÙ…Ø§', '$2y$12$ma.DIhK5JO3tw7xCS72.aeWOfhyPms.6rVRYX0VqX8c5tsqzuk9W6', 'Ù†ÛŒÙ…Ø§', 'Ø§Ø³Ø¯ÛŒØ§Ù†', 0, 6, 1, 0, '', '', '', '', '', '', '', '', '2017-09-27 04:10:07', '2017-10-24 15:57:20', 1, '589413', '', 1, 'Ø§Ø³ØªØ§Ø¯', '', 0, NULL, '', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '09394165695', '5486279364', 0, 'Ù…Ú©Ø§Ù†ÛŒÚ© ÙØ¶Ø§ÛŒÛŒ', NULL, 'Ø®ÛŒØ±', 1),
(27, 'm.a@gmail.com', 'Ù…Ø­Ù…Ø¯ Ø§Ø­Ù…Ø¯ÛŒ', '$2y$12$RQp7udYWUfOmDjQ9VxV0SONV7FWPruKm614c1afofh.ZcK9rVSGuO', 'Ù…Ø­Ù…Ø¯', 'Ø§Ø­Ù…Ø¯ÛŒ', 1, 1, 1, 0, '', '', '', '', '', '', '', '', '2017-10-14 19:47:37', '2017-10-14 19:48:16', 1, '560491', '', 1, 'Ø¯Ø§Ù†Ø´Ø¬Ùˆ', '96305762', 96, 'Ø¯Ú©ØªØ±Ø§', '', '', '', 'Ø¢Ù‚Ø§', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '09394165691', '5896341207', 0, '', '', 'Ø®ÛŒØ±', 1),
(28, 's.gh@gmail.com', 'Ø§Ø³Ù…Ø§Ø¹ÛŒÙ„', '$2y$12$68QJNB0DcvrdydoftS/3OepyqzXybQ.VOx6IiAI9qAi0rmoZY1Vbi', '', '', 1, 3, 1, 0, '', '', '', '', '', '', '', '', '2017-10-19 02:46:44', '2017-10-19 02:51:09', 1, '230709', '', 1, '', '', 0, NULL, '', '', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '', '', 0, NULL, NULL, 'Ø®ÛŒØ±', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_online`
--

CREATE TABLE `users_online` (
  `id` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `timestamp` varchar(15) NOT NULL,
  `user_id` int(10) NOT NULL,
  `session` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_online`
--

INSERT INTO `users_online` (`id`, `ip`, `timestamp`, `user_id`, `session`) VALUES
(24, '::1', '1509303513', 1, ''),
(27, '::1', '1514214973', 21, ''),
(28, '::1', '1509306010', 20, ''),
(29, '::1', '1514215748', 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE `users_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `uagent` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_permission_matches`
--

CREATE TABLE `user_permission_matches` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_permission_matches`
--

INSERT INTO `user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
(100, 1, 1),
(101, 1, 2),
(115, 15, 1),
(116, 16, 1),
(117, 17, 1),
(118, 18, 1),
(119, 19, 1),
(120, 20, 1),
(121, 21, 1),
(122, 22, 1),
(123, 23, 1),
(124, 23, 1),
(126, 25, 1),
(128, 27, 1),
(129, 28, 1),
(130, 2, 3),
(131, 1, 3),
(134, 10, 2),
(135, 1, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `capacity`
--
ALTER TABLE `capacity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_threads`
--
ALTER TABLE `message_threads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_page_matches`
--
ALTER TABLE `permission_page_matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_register`
--
ALTER TABLE `plan_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `EMAIL` (`email`) USING BTREE;

--
-- Indexes for table `users_online`
--
ALTER TABLE `users_online`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permission_matches`
--
ALTER TABLE `user_permission_matches`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit`
--
ALTER TABLE `audit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `capacity`
--
ALTER TABLE `capacity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `message_threads`
--
ALTER TABLE `message_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `permission_page_matches`
--
ALTER TABLE `permission_page_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `plan_register`
--
ALTER TABLE `plan_register`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `users_online`
--
ALTER TABLE `users_online`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_permission_matches`
--
ALTER TABLE `user_permission_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
