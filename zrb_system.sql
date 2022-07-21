-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2022 at 08:51 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zrb_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `short_name`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'current', 'Current Account', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'savings', 'Savings Account', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'salary', 'Salary Account', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, 'fixed-deposit', 'Fixed Deposit Account', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, 'recurring-deposit', 'Reccuring Deposit Account', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 09:13:58', '2022-07-12 09:13:58'),
(2, 'App\\Models\\User', 4, 'created', 'App\\Models\\TaPaymentConfiguration', 1, '[]', '{\"category\":\"registration fee\",\"duration\":null,\"no_of_days\":null,\"amount\":\"100000\",\"created_by\":4,\"id\":1}', 'http://127.0.0.1:8000/livewire/message/tax-agent-fee-modal', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 09:14:25', '2022-07-12 09:14:25'),
(3, 'App\\Models\\User', 4, 'created', 'App\\Models\\TaPaymentConfiguration', 2, '[]', '{\"category\":\"renewal fee\",\"duration\":\"yearly\",\"no_of_days\":\"12\",\"amount\":\"100000\",\"created_by\":4,\"id\":2}', 'http://127.0.0.1:8000/livewire/message/tax-agent-fee-modal', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 09:14:40', '2022-07-12 09:14:40'),
(5, 'App\\Models\\User', 4, 'updated', 'App\\Models\\TaxAgent', 2, '{\"status\":\"pending\"}', '{\"status\":\"verified\"}', 'http://127.0.0.1:8000/livewire/message/tax-agent.verification-requests-table', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 09:18:02', '2022-07-12 09:18:02'),
(6, 'App\\Models\\User', 4, 'updated', 'App\\Models\\TaxAgent', 2, '{\"reference_no\":null,\"status\":\"verified\",\"app_first_date\":null,\"app_expire_date\":null}', '{\"reference_no\":\"ZRB105183\",\"status\":\"approved\",\"app_first_date\":\"2022-07-12T09:32:28.151211Z\",\"app_expire_date\":\"2023-07-12 12:32:28\"}', 'http://127.0.0.1:8000/livewire/message/tax-agent.actions', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 09:32:28', '2022-07-12 09:32:28'),
(7, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 12:27:28', '2022-07-12 12:27:28'),
(8, 'App\\Models\\User', 4, 'updated', 'App\\Models\\TaxAgent', 2, '{\"app_first_date\":\"2022-07-12 12:32:28\",\"app_expire_date\":\"2022-07-12 12:32:28\"}', '{\"app_first_date\":\"2022-07-12 15:28:06\",\"app_expire_date\":\"2023-07-12 15:28:06\"}', 'http://127.0.0.1:8000/livewire/message/tax-agent.renewal-requests', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-12 12:28:06', '2022-07-12 12:28:06'),
(9, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-14 05:45:00', '2022-07-14 05:45:00'),
(10, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-14 18:56:44', '2022-07-14 18:56:44'),
(11, 'App\\Models\\User', 4, 'logged out', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/logout', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-14 19:00:06', '2022-07-14 19:00:06'),
(12, 'App\\Models\\User', 1, 'logged in', 'App\\Models\\User', 1, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-14 19:00:39', '2022-07-14 19:00:39'),
(13, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-18 05:59:49', '2022-07-18 05:59:49'),
(14, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-18 09:20:09', '2022-07-18 09:20:09'),
(15, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-18 18:17:22', '2022-07-18 18:17:22'),
(16, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.124 Safari/537.36 Edg/102.0.1245.41', NULL, '2022-07-19 06:17:52', '2022-07-19 06:17:52'),
(17, 'App\\Models\\User', 4, 'logged in', 'App\\Models\\User', 4, NULL, NULL, 'http://127.0.0.1:8000/twoFactorAuth', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.114 Safari/537.36 Edg/103.0.1264.62', NULL, '2022-07-20 11:55:35', '2022-07-20 11:55:35');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_status` enum('SUCCESS','FAILED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` enum('Create','Delete','Edit','Block','Unblock','ViewItem','ViewList') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `row_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PBZ', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'CRDB', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'NMB', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, 'EXIM Bank', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, 'UBA', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(6, 'Azania Bank', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(7, 'DTB', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(8, 'Equity Bank', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `biometrics`
--

CREATE TABLE `biometrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxpayer_id` bigint(20) UNSIGNED NOT NULL,
  `hand` enum('left','right') COLLATE utf8mb4_unicode_ci NOT NULL,
  `finger` enum('thumb','index','middle','ring','little') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_category_id` bigint(20) UNSIGNED NOT NULL,
  `taxpayer_id` bigint(20) UNSIGNED NOT NULL,
  `bpra_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','pending','approved','correction','closed','temp_closed','deregistered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `business_type` enum('hotel','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `business_activities_type_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_business` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_commencing` datetime NOT NULL,
  `pre_estimated_turnover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_estimated_turnover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goods_and_services_types` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `goods_and_services_example` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsible_person_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_own_consultant` tinyint(1) NOT NULL DEFAULT 1,
  `reg_date` datetime DEFAULT NULL,
  `z_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marking` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isiic_i` bigint(20) UNSIGNED DEFAULT NULL,
  `isiic_ii` bigint(20) UNSIGNED DEFAULT NULL,
  `isiic_iii` bigint(20) UNSIGNED DEFAULT NULL,
  `isiic_iv` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `business_category_id`, `taxpayer_id`, `bpra_no`, `status`, `business_type`, `business_activities_type_id`, `currency_id`, `name`, `tin`, `reg_no`, `owner_designation`, `mobile`, `alt_mobile`, `email`, `place_of_business`, `physical_address`, `date_of_commencing`, `pre_estimated_turnover`, `post_estimated_turnover`, `goods_and_services_types`, `goods_and_services_example`, `responsible_person_id`, `is_own_consultant`, `reg_date`, `z_no`, `marking`, `isiic_i`, `isiic_ii`, `isiic_iii`, `isiic_iv`, `verified_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'approved', 'other', 2, 1, 'Berk Duncan', 'Dolorem dolorem recu', 'Excepteur ipsum minu', 'Est quibusdam qui to', '0763218007', '', 'fypabyw@mailinator.com', 'Distinctio Qui cons', 'Ducimus et qui ipsa', '1978-04-25 00:00:00', '0', '300000', 'Optio commodo molli', 'Consequatur ullamco ', 1, 1, NULL, NULL, '{\"registration_officer\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,3],\"status\":1}}', NULL, NULL, NULL, NULL, NULL, NULL, '2022-07-12 09:40:36', '2022-07-12 09:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `business_activities`
--

CREATE TABLE `business_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_activities`
--

INSERT INTO `business_activities` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Wholesale', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'Retailer', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `business_banks`
--

CREATE TABLE `business_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `account_type_id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `acc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_banks`
--

INSERT INTO `business_banks` (`id`, `business_id`, `bank_id`, `account_type_id`, `currency_id`, `acc_no`, `branch`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 5, 2, '763', 'Incidunt optio aut', NULL, '2022-07-12 09:50:41', '2022-07-12 09:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `business_categories`
--

CREATE TABLE `business_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_categories`
--

INSERT INTO `business_categories` (`id`, `short_name`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'sole-proprietor', 'Sole Proprietor', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'partnership', 'Partnership', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'company', 'Company', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, 'ngo', 'NGO', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `business_consultants`
--

CREATE TABLE `business_consultants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `taxpayer_id` int(10) UNSIGNED NOT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `status` enum('pending','approved','rejected','removed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `removed_at` datetime DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_consultants`
--

INSERT INTO `business_consultants` (`id`, `business_id`, `taxpayer_id`, `reviewed_at`, `status`, `removed_at`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2022-07-12 01:11:22', 'approved', NULL, 'yes it is ok', NULL, '2022-07-12 09:50:35', '2022-07-12 10:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `business_deregistrations`
--

CREATE TABLE `business_deregistrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `deregistration_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','approved','rejected','correction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marking` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_on` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_hotels`
--

CREATE TABLE `business_hotels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `business_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `management_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hotel_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_rooms` int(10) UNSIGNED NOT NULL,
  `number_of_single_rooms` int(10) UNSIGNED NOT NULL,
  `number_of_double_rooms` int(10) UNSIGNED NOT NULL,
  `number_of_other_rooms` int(10) UNSIGNED NOT NULL,
  `hotel_capacity` int(10) UNSIGNED NOT NULL,
  `average_rate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_services` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_locations`
--

CREATE TABLE `business_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `ward_id` bigint(20) UNSIGNED NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nature_of_possession` enum('Owned','Rented') COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meter_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_headquarter` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('draft','pending','approved','correction','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_locations`
--

INSERT INTO `business_locations` (`id`, `business_id`, `region_id`, `district_id`, `ward_id`, `latitude`, `longitude`, `nature_of_possession`, `street`, `physical_address`, `house_no`, `owner_name`, `owner_phone_no`, `meter_no`, `is_headquarter`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 3, 34, '11', '22', 'Owned', 'Esse itaque sapient', 'Placeat est optio ', 'Porro iste consectet', NULL, NULL, '84', 1, 'approved', NULL, '2022-07-12 09:40:48', '2022-07-12 09:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `business_owners`
--

CREATE TABLE `business_owners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxpayer_id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_responsible_person` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_partners`
--

CREATE TABLE `business_partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` int(10) UNSIGNED NOT NULL,
  `taxpayer_id` int(10) UNSIGNED NOT NULL,
  `shares` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_tax_type`
--

CREATE TABLE `business_tax_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `tax_type_id` bigint(20) UNSIGNED NOT NULL,
  `currency` enum('TZS','USD') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_tax_type`
--

INSERT INTO `business_tax_type` (`id`, `business_id`, `tax_type_id`, `currency`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'TZS', '2022-07-12 09:40:36', NULL),
(2, 1, 7, 'TZS', '2022-07-12 09:40:36', NULL),
(3, 1, 9, 'TZS', '2022-07-12 09:40:36', NULL),
(4, 1, 10, 'TZS', '2022-07-12 09:40:36', NULL),
(5, 1, 13, 'TZS', '2022-07-12 09:40:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_temp_closures`
--

CREATE TABLE `business_temp_closures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `closing_date` datetime NOT NULL,
  `opening_date` datetime NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_extended` tinyint(1) NOT NULL DEFAULT 0,
  `show_extension` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','approved','rejected','correction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_on` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `marking` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_turnovers`
--

CREATE TABLE `business_turnovers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `next_12_months` decimal(15,2) NOT NULL,
  `last_12_months` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `nationality`, `created_at`, `updated_at`) VALUES
(1, 'TZ', 'Tanzania', 'Tanzanian', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'KE', 'Kenya', 'Kenyan', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(3, 'UG', 'Uganda', 'Ugandan', '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `iso`, `name`, `symbol`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'TZS', 'Tanzanian Shillings', 'Sh', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'USD', 'United States Dollar', '$', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `region_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kaskazini A', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 1, 'Kaskazini B', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(3, 2, 'Micheweni', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(4, 2, 'Wete', '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE `exchange_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spot_buying` decimal(8,2) NOT NULL,
  `mean` decimal(8,2) NOT NULL,
  `spot_selling` decimal(8,2) NOT NULL,
  `exchange_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_months`
--

CREATE TABLE `financial_months` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL,
  `code` varchar(35) NOT NULL,
  `financial_year_id` bigint(20) NOT NULL,
  `due_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `financial_months`
--

INSERT INTO `financial_months` (`id`, `name`, `code`, `financial_year_id`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 'july', 'J7', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(2, 'august', 'A8', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(3, 'september', 'S9', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(4, 'october', 'O10', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(5, 'november', 'N11', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(6, 'december', 'D12', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(7, 'september', 'J11', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(8, 'february', 'F2', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(9, 'march', 'M3', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(10, 'april', 'A4', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(11, 'may', 'M5', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL),
(12, 'june', 'J6', 1, '2022-08-22 23:59:59', '2022-07-20 08:13:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `financial_year`
--

CREATE TABLE `financial_year` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL,
  `code` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `financial_year`
--

INSERT INTO `financial_year` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, '2022', 'Y22', '2022-07-22 08:16:13', NULL),
(2, '2021', 'Y21', '2022-07-22 08:16:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `id_types`
--

CREATE TABLE `id_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `id_types`
--

INSERT INTO `id_types` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'NIDA', NULL, NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'PASSPORT', NULL, NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `isic1s`
--

CREATE TABLE `isic1s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `isic1s`
--

INSERT INTO `isic1s` (`id`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'A', 'Agriculture, forestry and fishing', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'B', 'Mining and quarrying', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'C', 'Manufacturing', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, 'D', 'Electricity, gas, steam and air conditioning supply', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, 'E', 'Water supply; sewerage, waste management and remediation activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(6, 'F', 'Construction', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(7, 'G', 'Wholesale and retail trade; repair of motor vehicles and motorcycles', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(8, 'H', 'Transportation and storage', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(9, 'I', 'Accommodation and food service activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(10, 'J', 'Information and communication', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(11, 'K', 'Financial and insurance activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(12, 'L', 'Real estate activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(13, 'M', 'Professional, scientific and technical activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(14, 'N', 'Administrative and support service activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(15, 'O', 'Public administration and defence; compulsory social security', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(16, 'P', 'Education', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(17, 'Q', 'Human health and social work activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(18, 'R', 'Arts, entertainment and recreation', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(19, 'S', 'Other service activities', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(20, 'T', 'Activities of households as employers; undifferentiated goods- and services-producing activities of households for own use', '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(21, 'U', 'Activities of extraterritorial organizations and bodies', '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `isic2s`
--

CREATE TABLE `isic2s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isic1_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `isic2s`
--

INSERT INTO `isic2s` (`id`, `code`, `description`, `isic1_id`, `created_at`, `updated_at`) VALUES
(1, '01', 'Crop and animal production, hunting and related service activities', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, '02', 'Forestry and logging', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, '03', 'Fishing and aquaculture', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, '05', 'Mining of coal and lignite', 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, '06', 'Extraction of crude petroleum and natural gas', 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(6, '07', 'Mining of metal ores', 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(7, '08', 'Other mining and quarrying', 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(8, '09', 'Mining support service activities', 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(9, '10', 'Manufacture of food products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(10, '11', 'Manufacture of beverages', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(11, '12', 'Manufacture of tobacco products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(12, '13', 'Manufacture of textiles', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(13, '14', 'Manufacture of wearing apparel', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(14, '15', 'Manufacture of leather and related products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(15, '16', 'Manufacture of wood and of products of wood and cork, except furniture; manufacture of articles of straw and plaiting materials', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(16, '17', 'Manufacture of paper and paper products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(17, '18', 'Printing and reproduction of recorded media', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(18, '19', 'Manufacture of coke and refined petroleum products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(19, '20', 'Manufacture of chemicals and chemical products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(20, '21', 'Manufacture of basic pharmaceutical products and pharmaceutical preparations', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(21, '22', 'Manufacture of rubber and plastics products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(22, '23', 'Manufacture of other non-metallic mineral products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(23, '24', 'Manufacture of basic metals', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(24, '25', 'Manufacture of fabricated metal products, except machinery and equipment', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(25, '26', 'Manufacture of computer, electronic and optical products', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(26, '27', 'Manufacture of electrical equipment', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(27, '28', 'Manufacture of machinery and equipment n.e.c.', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(28, '29', 'Manufacture of motor vehicles, trailers and semi-trailers', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(29, '30', 'Manufacture of other transport equipment', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(30, '31', 'Manufacture of furniture', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(31, '32', 'Other manufacturing', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(32, '33', 'Repair and installation of machinery and equipment', 3, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(33, '35', 'Electricity, gas, steam and air conditioning supply', 4, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(34, '36', 'Water collection, treatment and supply', 5, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(35, '37', 'Sewerage', 5, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(36, '38', 'Waste collection, treatment and disposal activities; materials recovery', 5, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(37, '39', 'Remediation activities and other waste management services', 5, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(38, '41', 'Construction of buildings', 6, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(39, '42', 'Civil engineering', 6, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(40, '43', 'Specialized construction activities', 6, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(41, '45', 'Wholesale and retail trade and repair of motor vehicles and motorcycles', 7, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(42, '46', 'Wholesale trade, except of motor vehicles and motorcycles', 7, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(43, '47', 'Retail trade, except of motor vehicles and motorcycles', 7, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(44, '49', 'Land transport and transport via pipelines', 8, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(45, '50', 'Water transport', 8, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(46, '51', 'Air transport', 8, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(47, '52', 'Warehousing and support activities for transportation', 8, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(48, '53', 'Postal and courier activities', 8, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(49, '55', 'Accommodation', 9, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(50, '56', 'Food and beverage service activities', 9, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(51, '58', 'Publishing activities', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(52, '59', 'Motion picture, video and television programme production, sound recording and music publishing activities', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(53, '60', 'Programming and broadcasting activities', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(54, '61', 'Telecommunications', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(55, '62', 'Computer programming, consultancy and related activities', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(56, '63', 'Information service activities', 10, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(57, '64', 'Financial service activities, except insurance and pension funding', 11, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(58, '65', 'Insurance, reinsurance and pension funding, except compulsory social security', 11, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(59, '66', 'Activities auxiliary to financial service and insurance activities', 11, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(60, '68', 'Real estate activities', 12, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(61, '69', 'Legal and accounting activities', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(62, '70', 'Activities of head offices; management consultancy activities', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(63, '71', 'Architectural and engineering activities; technical testing and analysis', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(64, '72', 'Scientific research and development', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(65, '73', 'Advertising and market research', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(66, '74', 'Other professional, scientific and technical activities', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(67, '75', 'Veterinary activities', 13, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(68, '77', 'Rental and leasing activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(69, '78', 'Employment activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(70, '79', 'Travel agency, tour operator, reservation service and related activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(71, '80', 'Security and investigation activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(72, '81', 'Services to buildings and landscape activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(73, '82', 'Office administrative, office support and other business support activities', 14, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(74, '84', 'Public administration and defence; compulsory social security', 15, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(75, '85', 'Education', 16, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(76, '86', 'Human health activities', 17, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(77, '87', 'Residential care activities', 17, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(78, '88', 'Social work activities without accommodation', 17, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(79, '90', 'Creative, arts and entertainment activities', 18, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(80, '91', 'Libraries, archives, museums and other cultural activities', 18, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(81, '92', 'Gambling and betting activities', 18, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(82, '93', 'Sports activities and amusement and recreation activities', 18, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(83, '94', 'Activities of membership organizations', 19, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(84, '95', 'Repair of computers and personal and household goods', 19, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(85, '96', 'Other personal service activities', 19, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(86, '97', 'Activities of households as employers of domestic personnel', 20, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(87, '98', 'Undifferentiated goods- and services-producing activities of private households for own use', 20, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(88, '99', 'Activities of extraterritorial organizations and bodies', 21, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `isic3s`
--

CREATE TABLE `isic3s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isic2_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `isic3s`
--

INSERT INTO `isic3s` (`id`, `code`, `description`, `isic2_id`, `created_at`, `updated_at`) VALUES
(1, '491', 'Transport via railways', 44, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, '492', 'Other land transport', 44, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, '493', 'Transport via pipeline', 44, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, '501', 'Sea and coastal water transport', 45, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, '502', 'Inland water transport', 45, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(6, '511', 'Passenger air transport', 46, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(7, '512', 'Freight air transport', 46, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(8, '521', 'Warehousing and storage', 47, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(9, '522', 'Support activities for transportation', 47, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(10, '531', 'Postal activities', 48, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(11, '532', 'Courier activities', 48, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(12, '551', 'Short term accommodation activities', 49, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(13, '552', 'Camping grounds, recreational vehicle parks and trailer parks', 49, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(14, '559', 'Other accommodation', 49, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(15, '561', 'Restaurants and mobile food service activities', 50, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(16, '562', 'Event catering and other food service activities', 50, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(17, '563', 'Beverage serving activities', 50, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(18, '581', 'Publishing of books, periodicals and other publishing activities', 51, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(19, '582', 'Software publishing', 51, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(20, '591', 'Motion picture, video and television programme activities', 52, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(21, '592', 'Sound recording and music publishing activities', 52, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(22, '601', 'Radio broadcasting', 53, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(23, '602', 'Television programming and broadcasting activities', 53, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(24, '611', 'Wired telecommunications activities', 54, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(25, '612', 'Wireless telecommunications activities', 54, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(26, '613', 'Satellite telecommunications activities', 54, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(27, '619', 'Other telecommunications activities', 54, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(28, '620', 'Computer programming, consultancy and related activities', 55, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(29, '631', 'Data processing, hosting and related activities; web portals', 56, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(30, '639', 'Other information service activities', 56, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(31, '641', 'Monetary intermediation', 57, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(32, '642', 'Activities of holding companies', 57, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(33, '643', 'Trusts, funds and similar financial entities', 57, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(34, '649', 'Other financial service activities, except insurance and pension funding activities', 57, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(35, '651', 'Insurance', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(36, '652', 'Reinsurance', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(37, '653', 'Pension funding', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(38, '661', 'Activities auxiliary to financial service activities, except insurance and pension funding', 59, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(39, '662', 'Activities auxiliary to insurance and pension funding', 59, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(40, '663', 'Fund management activities', 59, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(41, '681', 'Real estate activities with own or leased property', 60, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(42, '682', 'Real estate activities on a fee or contract basis', 60, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(43, '691', 'Legal activities', 61, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(44, '692', 'Accounting, bookkeeping and auditing activities; tax consultancy', 61, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(45, '701', 'Activities of head offices', 62, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(46, '702', 'Management consultancy activities', 62, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(47, '711', 'Architectural and engineering activities and related technical consultancy', 63, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(48, '712', 'Technical testing and analysis', 63, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(49, '721', 'Research and experimental development on natural sciences and engineering', 64, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(50, '722', 'Research and experimental development on social sciences and humanities', 64, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(51, '731', 'Advertising', 65, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(52, '732', 'Market research and public opinion polling', 65, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(53, '741', 'Specialized design activities', 66, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(54, '742', 'Photographic activities', 66, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(55, '749', 'Other professional, scientific and technical activities n.e.c.', 66, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(56, '750', 'Veterinary activities', 67, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(57, '771', 'Renting and leasing of motor vehicles', 68, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(58, '772', 'Renting and leasing of personal and household goods', 68, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(59, '773', 'Renting and leasing of other machinery, equipment and tangible goods', 68, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(60, '774', 'Leasing of intellectual property and similar products, except copyrighted works', 68, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(61, '781', 'Activities of employment placement agencies', 69, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(62, '782', 'Temporary employment agency activities', 69, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(63, '783', 'Other human resources provision', 69, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(64, '791', 'Travel agency and tour operator activities', 70, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(65, '799', 'Other reservation service and related activities', 70, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(66, '801', 'Private security activities', 71, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(67, '802', 'Security systems service activities', 71, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(68, '803', 'Investigation activities', 71, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(69, '811', 'Combined facilities support activities', 72, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(70, '812', 'Cleaning activities', 72, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(71, '813', 'Landscape care and maintenance service activities', 72, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(72, '821', 'Office administrative and support activities', 73, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(73, '822', 'Activities of call centres', 73, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(74, '823', 'Organization of conventions and trade shows', 73, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(75, '829', 'Business support service activities n.e.c.', 73, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(76, '841', 'Administration of the State and the economic and social policy of the community', 74, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(77, '842', 'Provision of services to the community as a whole', 74, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(78, '843', 'Compulsory social security activities', 74, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(79, '851', 'Pre-primary and primary education', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(80, '852', 'Secondary education', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(81, '853', 'Higher education', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(82, '854', 'Other education', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(83, '855', 'Educational support activities', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(84, '861', 'Hospital activities', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(85, '862', 'Medical and dental practice activities', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(86, '869', 'Other human health activities', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(87, '871', 'Residential nursing care facilities', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(88, '872', 'Residential care activities for mental retardation, mental health and substance abuse', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(89, '873', 'Residential care activities for the elderly and disabled', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(90, '879', 'Other residential care activities', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(91, '881', 'Social work activities without accommodation for the elderly and disabled', 78, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(92, '889', 'Other social work activities without accommodation', 78, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(93, '900', 'Creative, arts and entertainment activities', 79, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(94, '910', 'Libraries, archives, museums and other cultural activities', 80, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(95, '920', 'Gambling and betting activities', 81, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(96, '931', 'Sports activities', 82, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(97, '932', 'Other amusement and recreation activities', 82, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(98, '941', 'Activities of business, employers and professional membership organizations', 83, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(99, '942', 'Activities of trade unions', 83, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(100, '949', 'Activities of other membership organizations', 83, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(101, '951', 'Repair of computers and communication equipment', 84, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(102, '952', 'Repair of personal and household goods', 84, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(103, '960', 'Other personal service activities', 85, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(104, '970', 'Activities of households as employers of domestic personnel', 86, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(105, '981', 'Undifferentiated goods-producing activities of private households for own use', 87, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(106, '982', 'Undifferentiated service-producing activities of private households for own use', 87, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(107, '990', 'Activities of extraterritorial organizations and bodies', 88, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(108, '011', 'Growing of non-perennial crops', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(109, '012', 'Growing of perennial crops', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(110, '013', 'Plant propagation', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(111, '014', 'Animal production', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(112, '015', 'Mixed farming', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(113, '016', 'Support activities to agriculture and post-harvest crop activities', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(114, '017', 'Hunting, trapping and related service activities', 1, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(115, '021', 'Silviculture and other forestry activities', 2, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(116, '022', 'Logging', 2, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(117, '023', 'Gathering of non-wood forest products', 2, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(118, '024', 'Support services to forestry', 2, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(119, '031', 'Fishing', 3, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(120, '032', 'Aquaculture', 3, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(121, '051', 'Mining of hard coal', 4, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(122, '052', 'Mining of lignite', 4, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(123, '061', 'Extraction of crude petroleum', 5, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(124, '062', 'Extraction of natural gas', 5, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(125, '071', 'Mining of iron ores', 6, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(126, '072', 'Mining of non-ferrous metal ores', 6, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(127, '081', 'Quarrying of stone, sand and clay', 7, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(128, '089', 'Mining and quarrying n.e.c.', 7, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(129, '091', 'Support activities for petroleum and natural gas extraction', 8, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(130, '099', 'Support activities for other mining and quarrying', 8, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(131, '101', 'Processing and preserving of meat', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(132, '102', 'Processing and preserving of fish, crustaceans and molluscs', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(133, '103', 'Processing and preserving of fruit and vegetables', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(134, '104', 'Manufacture of vegetable and animal oils and fats', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(135, '105', 'Manufacture of dairy products', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(136, '106', 'Manufacture of grain mill products, starches and starch products', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(137, '107', 'Manufacture of other food products', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(138, '108', 'Manufacture of prepared animal feeds', 9, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(139, '110', 'Manufacture of beverages', 10, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(140, '120', 'Manufacture of tobacco products', 11, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(141, '131', 'Spinning, weaving and finishing of textiles', 12, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(142, '139', 'Manufacture of other textiles', 12, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(143, '141', 'Manufacture of wearing apparel, except fur apparel', 13, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(144, '142', 'Manufacture of articles of fur', 13, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(145, '143', 'Manufacture of knitted and crocheted apparel', 13, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(146, '151', 'Tanning and dressing of leather; manufacture of luggage, handbags, saddlery and harness; dressing and dyeing of fur', 14, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(147, '152', 'Manufacture of footwear', 14, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(148, '161', 'Sawmilling and planing of wood', 15, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(149, '162', 'Manufacture of products of wood, cork, straw and plaiting materials', 15, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(150, '170', 'Manufacture of paper and paper products', 16, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(151, '181', 'Printing and service activities related to printing', 17, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(152, '182', 'Reproduction of recorded media', 17, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(153, '191', 'Manufacture of coke oven products', 18, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(154, '192', 'Manufacture of refined petroleum products', 18, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(155, '201', 'Manufacture of basic chemicals, fertilizers and nitrogen compounds, plastics and synthetic rubber in primary forms', 19, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(156, '202', 'Manufacture of other chemical products', 19, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(157, '203', 'Manufacture of man-made fibres', 19, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(158, '210', 'Manufacture of pharmaceuticals, medicinal chemical and botanical products', 20, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(159, '221', 'Manufacture of rubber products', 21, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(160, '222', 'Manufacture of plastics products', 21, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(161, '231', 'Manufacture of glass and glass products', 22, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(162, '239', 'Manufacture of non-metallic mineral products n.e.c.', 22, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(163, '241', 'Manufacture of basic iron and steel', 23, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(164, '242', 'Manufacture of basic precious and other non-ferrous metals', 23, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(165, '243', 'Casting of metals', 23, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(166, '251', 'Manufacture of structural metal products, tanks, reservoirs and steam generators', 24, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(167, '252', 'Manufacture of weapons and ammunition', 24, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(168, '259', 'Manufacture of other fabricated metal products; metalworking service activities', 24, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(169, '261', 'Manufacture of electronic components and boards', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(170, '262', 'Manufacture of computers and peripheral equipment', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(171, '263', 'Manufacture of communication equipment', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(172, '264', 'Manufacture of consumer electronics', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(173, '265', 'Manufacture of measuring, testing, navigating and control equipment; watches and clocks', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(174, '266', 'Manufacture of irradiation, electromedical and electrotherapeutic equipment', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(175, '267', 'Manufacture of optical instruments and photographic equipment', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(176, '268', 'Manufacture of magnetic and optical media', 25, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(177, '271', 'Manufacture of electric motors, generators, transformers and electricity distribution and control apparatus', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(178, '272', 'Manufacture of batteries and accumulators', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(179, '273', 'Manufacture of wiring and wiring devices', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(180, '274', 'Manufacture of electric lighting equipment', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(181, '275', 'Manufacture of domestic appliances', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(182, '279', 'Manufacture of other electrical equipment', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(183, '281', 'Manufacture of general-purpose machinery', 27, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(184, '282', 'Manufacture of special-purpose machinery', 27, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(185, '291', 'Manufacture of motor vehicles', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(186, '292', 'Manufacture of bodies (coachwork) for motor vehicles; manufacture of trailers and semi-trailers', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(187, '293', 'Manufacture of parts and accessories for motor vehicles', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(188, '301', 'Building of ships and boats', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(189, '302', 'Manufacture of railway locomotives and rolling stock', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(190, '303', 'Manufacture of air and spacecraft and related machinery', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(191, '304', 'Manufacture of military fighting vehicles', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(192, '309', 'Manufacture of transport equipment n.e.c.', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(193, '310', 'Manufacture of furniture', 30, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(194, '321', 'Manufacture of jewellery, bijouterie and related articles', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(195, '322', 'Manufacture of musical instruments', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(196, '323', 'Manufacture of sports goods', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(197, '324', 'Manufacture of games and toys', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(198, '325', 'Manufacture of medical and dental instruments and supplies', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(199, '329', 'Other manufacturing n.e.c.', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(200, '331', 'Repair of fabricated metal products, machinery and equipment', 32, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(201, '332', 'Installation of industrial machinery and equipment', 32, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(202, '351', 'Electric power generation, transmission and distribution', 33, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(203, '352', 'Manufacture of gas; distribution of gaseous fuels through mains', 33, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(204, '353', 'Steam and air conditioning supply', 33, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(205, '360', 'Water collection, treatment and supply', 34, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(206, '370', 'Sewerage', 35, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(207, '381', 'Waste collection', 36, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(208, '382', 'Waste treatment and disposal', 36, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(209, '383', 'Materials recovery', 36, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(210, '390', 'Remediation activities and other waste management services', 37, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(211, '410', 'Construction of buildings', 38, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(212, '421', 'Construction of roads and railways', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(213, '422', 'Construction of utility projects', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(214, '429', 'Construction of other civil engineering projects', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(215, '431', 'Demolition and site preparation', 40, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(216, '432', 'Electrical, plumbing and other construction installation activities', 40, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(217, '433', 'Building completion and finishing', 40, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(218, '439', 'Other specialized construction activities', 40, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(219, '451', 'Sale of motor vehicles', 41, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(220, '452', 'Maintenance and repair of motor vehicles', 41, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(221, '453', 'Sale of motor vehicle parts and accessories', 41, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(222, '454', 'Sale, maintenance and repair of motorcycles and related parts and accessories', 41, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(223, '461', 'Wholesale on a fee or contract basis', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(224, '462', 'Wholesale of agricultural raw materials and live animals', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(225, '463', 'Wholesale of food, beverages and tobacco', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(226, '464', 'Wholesale of household goods', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(227, '465', 'Wholesale of machinery, equipment and supplies', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(228, '466', 'Other specialized wholesale', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(229, '469', 'Non-specialized wholesale trade', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(230, '471', 'Retail sale in non-specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(231, '472', 'Retail sale of food, beverages and tobacco in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(232, '473', 'Retail sale of automotive fuel in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(233, '474', 'Retail sale of information and communications equipment in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(234, '475', 'Retail sale of other household equipment in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(235, '476', 'Retail sale of cultural and recreation goods in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(236, '477', 'Retail sale of other goods in specialized stores', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(237, '478', 'Retail sale via stalls and markets', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(238, '479', 'Retail trade not in stores, stalls or markets', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `isic4s`
--

CREATE TABLE `isic4s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isic3_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `isic4s`
--

INSERT INTO `isic4s` (`id`, `code`, `description`, `isic3_id`, `created_at`, `updated_at`) VALUES
(1, '6130', 'Satellite telecommunications activities', 26, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(2, '6190', 'Other telecommunications activities', 27, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(3, '6201', 'Computer programming activities', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(4, '6202', 'Computer consultancy and computer facilities management activities', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(5, '6209', 'Other information technology and computer service activities', 28, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(6, '6311', 'Data processing, hosting and related activities', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(7, '6312', 'Web portals', 29, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(8, '6391', 'News agency activities', 30, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(9, '6399', 'Other information service activities n.e.c.', 30, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(10, '6411', 'Central banking', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(11, '6419', 'Other monetary intermediation', 31, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(12, '6420', 'Activities of holding companies', 32, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(13, '6430', 'Trusts, funds and similar financial entities', 33, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(14, '6491', 'Financial leasing', 34, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(15, '6492', 'Other credit granting', 34, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(16, '6499', 'Other financial service activities, except insurance and pension funding activities, n.e.c.', 34, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(17, '6511', 'Life insurance', 35, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(18, '6512', 'Non-life insurance', 35, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(19, '6520', 'Reinsurance', 36, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(20, '6530', 'Pension funding', 37, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(21, '6611', 'Administration of financial markets', 38, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(22, '6612', 'Security and commodity contracts brokerage', 38, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(23, '6619', 'Other activities auxiliary to financial service activities', 38, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(24, '6621', 'Risk and damage evaluation', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(25, '6622', 'Activities of insurance agents and brokers', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(26, '6629', 'Other activities auxiliary to insurance and pension funding', 39, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(27, '6630', 'Fund management activities', 40, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(28, '6810', 'Real estate activities with own or leased property', 41, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(29, '6820', 'Real estate activities on a fee or contract basis', 42, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(30, '6910', 'Legal activities', 43, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(31, '6920', 'Accounting, bookkeeping and auditing activities; tax consultancy', 44, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(32, '7010', 'Activities of head offices', 45, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(33, '7020', 'Management consultancy activities', 46, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(34, '7110', 'Architectural and engineering activities and related technical consultancy', 47, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(35, '7120', 'Technical testing and analysis', 48, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(36, '7210', 'Research and experimental development on natural sciences and engineering', 49, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(37, '7220', 'Research and experimental development on social sciences and humanities', 50, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(38, '7310', 'Advertising', 51, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(39, '7320', 'Market research and public opinion polling', 52, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(40, '7410', 'Specialized design activities', 53, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(41, '7420', 'Photographic activities', 54, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(42, '7490', 'Other professional, scientific and technical activities n.e.c.', 55, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(43, '7500', 'Veterinary activities', 56, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(44, '7710', 'Renting and leasing of motor vehicles', 57, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(45, '7721', 'Renting and leasing of recreational and sports goods', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(46, '7722', 'Renting of video tapes and disks', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(47, '7729', 'Renting and leasing of other personal and household goods', 58, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(48, '7730', 'Renting and leasing of other machinery, equipment and tangible goods', 59, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(49, '7740', 'Leasing of intellectual property and similar products, except copyrighted works', 60, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(50, '7810', 'Activities of employment placement agencies', 61, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(51, '7820', 'Temporary employment agency activities', 62, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(52, '7830', 'Other human resources provision', 63, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(53, '7911', 'Travel agency activities', 64, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(54, '7912', 'Tour operator activities', 64, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(55, '7990', 'Other reservation service and related activities', 65, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(56, '8010', 'Private security activities', 66, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(57, '8020', 'Security systems service activities', 67, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(58, '8030', 'Investigation activities', 68, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(59, '8110', 'Combined facilities support activities', 69, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(60, '8121', 'General cleaning of buildings', 70, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(61, '8129', 'Other building and industrial cleaning activities', 70, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(62, '8130', 'Landscape care and maintenance service activities', 71, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(63, '8211', 'Combined office administrative service activities', 72, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(64, '8219', 'Photocopying, document preparation and other specialized office support activities', 72, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(65, '8220', 'Activities of call centres', 73, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(66, '8230', 'Organization of conventions and trade shows', 74, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(67, '8291', 'Activities of collection agencies and credit bureaus', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(68, '8292', 'Packaging activities', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(69, '8299', 'Other business support service activities n.e.c.', 75, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(70, '8411', 'General public administration activities', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(71, '8412', 'Regulation of the activities of providing health care, education, cultural services and other social services, excluding social security', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(72, '8413', 'Regulation of and contribution to more efficient operation of businesses', 76, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(73, '8421', 'Foreign affairs', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(74, '8422', 'Defence activities', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(75, '8423', 'Public order and safety activities', 77, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(76, '8430', 'Compulsory social security activities', 78, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(77, '8510', 'Pre-primary and primary education', 79, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(78, '8521', 'General secondary education', 80, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(79, '8522', 'Technical and vocational secondary education', 80, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(80, '8530', 'Higher education', 81, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(81, '8541', 'Sports and recreation education', 82, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(82, '8542', 'Cultural education', 82, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(83, '8549', 'Other education n.e.c.', 82, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(84, '8550', 'Educational support activities', 83, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(85, '8610', 'Hospital activities', 84, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(86, '8620', 'Medical and dental practice activities', 85, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(87, '8690', 'Other human health activities', 86, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(88, '8710', 'Residential nursing care facilities', 87, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(89, '8720', 'Residential care activities for mental retardation, mental health and substance abuse', 88, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(90, '8730', 'Residential care activities for the elderly and disabled', 89, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(91, '8790', 'Other residential care activities', 90, '2022-07-12 09:10:54', '2022-07-12 09:10:54'),
(92, '8810', 'Social work activities without accommodation for the elderly and disabled', 91, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(93, '8890', 'Other social work activities without accommodation', 92, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(94, '9000', 'Creative, arts and entertainment activities', 93, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(95, '9101', 'Library and archives activities', 94, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(96, '9102', 'Museums activities and operation of historical sites and buildings', 94, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(97, '9103', 'Botanical and zoological gardens and nature reserves activities', 94, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(98, '9200', 'Gambling and betting activities', 95, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(99, '9311', 'Operation of sports facilities', 96, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(100, '9312', 'Activities of sports clubs', 96, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(101, '9319', 'Other sports activities', 96, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(102, '9321', 'Activities of amusement parks and theme parks', 97, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(103, '9329', 'Other amusement and recreation activities n.e.c.', 97, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(104, '9411', 'Activities of business and employers membership organizations', 98, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(105, '9412', 'Activities of professional membership organizations', 98, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(106, '9420', 'Activities of trade unions', 99, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(107, '9491', 'Activities of religious organizations', 100, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(108, '9492', 'Activities of political organizations', 100, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(109, '9499', 'Activities of other membership organizations n.e.c.', 100, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(110, '9511', 'Repair of computers and peripheral equipment', 101, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(111, '9512', 'Repair of communication equipment', 101, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(112, '9521', 'Repair of consumer electronics', 102, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(113, '9522', 'Repair of household appliances and home and garden equipment', 102, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(114, '9523', 'Repair of footwear and leather goods', 102, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(115, '9524', 'Repair of furniture and home furnishings', 102, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(116, '9529', 'Repair of other personal and household goods', 102, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(117, '9601', 'Washing and (dry-) cleaning of textile and fur products', 103, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(118, '9602', 'Hairdressing and other beauty treatment', 103, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(119, '9603', 'Funeral and related activities', 103, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(120, '9609', 'Other personal service activities n.e.c.', 103, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(121, '9700', 'Activities of households as employers of domestic personnel', 104, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(122, '9810', 'Undifferentiated goods-producing activities of private households for own use', 105, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(123, '9820', 'Undifferentiated service-producing activities of private households for own use', 106, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(124, '9900', 'Activities of extraterritorial organizations and bodies', 107, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(125, '0111', 'Growing of cereals (except rice), leguminous crops and oil seeds', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(126, '0112', 'Growing of rice', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(127, '0113', 'Growing of vegetables and melons, roots and tubers', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(128, '0114', 'Growing of sugar cane', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(129, '0115', 'Growing of tobacco', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(130, '0116', 'Growing of fibre crops', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(131, '0119', 'Growing of other non-perennial crops', 108, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(132, '0121', 'Growing of grapes', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(133, '0122', 'Growing of tropical and subtropical fruits', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(134, '0123', 'Growing of citrus fruits', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(135, '0124', 'Growing of pome fruits and stone fruits', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(136, '0125', 'Growing of other tree and bush fruits and nuts', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(137, '0126', 'Growing of oleaginous fruits', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(138, '0127', 'Growing of beverage crops', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(139, '0128', 'Growing of spices, aromatic, drug and pharmaceutical crops', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(140, '0129', 'Growing of other perennial crops', 109, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(141, '0130', 'Plant propagation', 110, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(142, '0141', 'Raising of cattle and buffaloes', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(143, '0142', 'Raising of horses and other equines', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(144, '0143', 'Raising of camels and camelids', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(145, '0144', 'Raising of sheep and goats', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(146, '0145', 'Raising of swine/pigs', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(147, '0146', 'Raising of poultry', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(148, '0149', 'Raising of other animals', 111, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(149, '0150', 'Mixed farming', 112, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(150, '0161', 'Support activities for crop production', 113, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(151, '0162', 'Support activities for animal production', 113, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(152, '0163', 'Post-harvest crop activities', 113, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(153, '0164', 'Seed processing for propagation', 113, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(154, '0170', 'Hunting, trapping and related service activities', 114, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(155, '0210', 'Silviculture and other forestry activities', 115, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(156, '0220', 'Logging', 116, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(157, '0230', 'Gathering of non-wood forest products', 117, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(158, '0240', 'Support services to forestry', 118, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(159, '0311', 'Marine fishing', 119, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(160, '0312', 'Freshwater fishing', 119, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(161, '0321', 'Marine aquaculture', 120, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(162, '0322', 'Freshwater aquaculture', 120, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(163, '0510', 'Mining of hard coal', 121, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(164, '0520', 'Mining of lignite', 122, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(165, '0610', 'Extraction of crude petroleum', 123, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(166, '0620', 'Extraction of natural gas', 124, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(167, '0710', 'Mining of iron ores', 125, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(168, '0721', 'Mining of uranium and thorium ores', 126, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(169, '0729', 'Mining of other non-ferrous metal ores', 126, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(170, '0810', 'Quarrying of stone, sand and clay', 127, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(171, '0891', 'Mining of chemical and fertilizer minerals', 128, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(172, '0892', 'Extraction of peat', 128, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(173, '0893', 'Extraction of salt', 128, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(174, '0899', 'Other mining and quarrying n.e.c.', 128, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(175, '0910', 'Support activities for petroleum and natural gas extraction', 129, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(176, '0990', 'Support activities for other mining and quarrying', 130, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(177, '1010', 'Processing and preserving of meat', 131, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(178, '1020', 'Processing and preserving of fish, crustaceans and molluscs', 132, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(179, '1030', 'Processing and preserving of fruit and vegetables', 133, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(180, '1040', 'Manufacture of vegetable and animal oils and fats', 134, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(181, '1050', 'Manufacture of dairy products', 135, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(182, '1061', 'Manufacture of grain mill products', 136, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(183, '1062', 'Manufacture of starches and starch products', 136, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(184, '1071', 'Manufacture of bakery products', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(185, '1072', 'Manufacture of sugar', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(186, '1073', 'Manufacture of cocoa, chocolate and sugar confectionery', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(187, '1074', 'Manufacture of macaroni, noodles, couscous and similar farinaceous products', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(188, '1075', 'Manufacture of prepared meals and dishes', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(189, '1079', 'Manufacture of other food products n.e.c.', 137, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(190, '1080', 'Manufacture of prepared animal feeds', 138, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(191, '1101', 'Distilling, rectifying and blending of spirits', 139, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(192, '1102', 'Manufacture of wines', 139, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(193, '1103', 'Manufacture of malt liquors and malt', 139, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(194, '1104', 'Manufacture of soft drinks; production of mineral waters and other bottled waters', 139, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(195, '1200', 'Manufacture of tobacco products', 140, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(196, '1311', 'Preparation and spinning of textile fibres', 141, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(197, '1312', 'Weaving of textiles', 141, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(198, '1313', 'Finishing of textiles', 141, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(199, '1391', 'Manufacture of knitted and crocheted fabrics', 142, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(200, '1392', 'Manufacture of made-up textile articles, except apparel', 142, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(201, '1393', 'Manufacture of carpets and rugs', 142, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(202, '1394', 'Manufacture of cordage, rope, twine and netting', 142, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(203, '1399', 'Manufacture of other textiles n.e.c.', 142, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(204, '1410', 'Manufacture of wearing apparel, except fur apparel', 143, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(205, '1420', 'Manufacture of articles of fur', 144, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(206, '1430', 'Manufacture of knitted and crocheted apparel', 145, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(207, '1511', 'Tanning and dressing of leather; dressing and dyeing of fur', 146, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(208, '1512', 'Manufacture of luggage, handbags and the like, saddlery and harness', 146, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(209, '1520', 'Manufacture of footwear', 147, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(210, '1610', 'Sawmilling and planing of wood', 148, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(211, '1621', 'Manufacture of veneer sheets and wood-based panels', 149, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(212, '1622', 'Manufacture of builders\' carpentry and joinery', 149, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(213, '1623', 'Manufacture of wooden containers', 149, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(214, '1629', 'Manufacture of other products of wood; manufacture of articles of cork, straw and plaiting materials', 149, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(215, '1701', 'Manufacture of pulp, paper and paperboard', 150, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(216, '1702', 'Manufacture of corrugated paper and paperboard and of containers of paper and paperboard', 150, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(217, '1709', 'Manufacture of other articles of paper and paperboard', 150, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(218, '1811', 'Printing', 151, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(219, '1812', 'Service activities related to printing', 151, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(220, '1820', 'Reproduction of recorded media', 152, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(221, '1910', 'Manufacture of coke oven products', 153, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(222, '1920', 'Manufacture of refined petroleum products', 154, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(223, '2011', 'Manufacture of basic chemicals', 155, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(224, '2012', 'Manufacture of fertilizers and nitrogen compounds', 155, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(225, '2013', 'Manufacture of plastics and synthetic rubber in primary forms', 155, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(226, '2021', 'Manufacture of pesticides and other agrochemical products', 156, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(227, '2022', 'Manufacture of paints, varnishes and similar coatings, printing ink and mastics', 156, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(228, '2023', 'Manufacture of soap and detergents, cleaning and polishing preparations, perfumes and toilet preparations', 156, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(229, '2029', 'Manufacture of other chemical products n.e.c.', 156, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(230, '2030', 'Manufacture of man-made fibres', 157, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(231, '2100', 'Manufacture of pharmaceuticals, medicinal chemical and botanical products', 158, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(232, '2211', 'Manufacture of rubber tyres and tubes; retreading and rebuilding of rubber tyres', 159, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(233, '2219', 'Manufacture of other rubber products', 159, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(234, '2220', 'Manufacture of plastics products', 160, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(235, '2310', 'Manufacture of glass and glass products', 161, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(236, '2391', 'Manufacture of refractory products', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(237, '2392', 'Manufacture of clay building materials', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(238, '2393', 'Manufacture of other porcelain and ceramic products', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(239, '2394', 'Manufacture of cement, lime and plaster', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(240, '2395', 'Manufacture of articles of concrete, cement and plaster', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(241, '2396', 'Cutting, shaping and finishing of stone', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(242, '2399', 'Manufacture of other non-metallic mineral products n.e.c.', 162, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(243, '2410', 'Manufacture of basic iron and steel', 163, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(244, '2420', 'Manufacture of basic precious and other non-ferrous metals', 164, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(245, '2431', 'Casting of iron and steel', 165, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(246, '2432', 'Casting of non-ferrous metals', 165, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(247, '2511', 'Manufacture of structural metal products', 166, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(248, '2512', 'Manufacture of tanks, reservoirs and containers of metal', 166, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(249, '2513', 'Manufacture of steam generators, except central heating hot water boilers', 166, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(250, '2520', 'Manufacture of weapons and ammunition', 167, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(251, '2591', 'Forging, pressing, stamping and roll-forming of metal; powder metallurgy', 168, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(252, '2592', 'Treatment and coating of metals; machining', 168, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(253, '2593', 'Manufacture of cutlery, hand tools and general hardware', 168, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(254, '2599', 'Manufacture of other fabricated metal products n.e.c.', 168, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(255, '2610', 'Manufacture of electronic components and boards', 169, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(256, '2620', 'Manufacture of computers and peripheral equipment', 170, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(257, '2630', 'Manufacture of communication equipment', 171, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(258, '2640', 'Manufacture of consumer electronics', 172, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(259, '2651', 'Manufacture of measuring, testing, navigating and control equipment', 173, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(260, '2652', 'Manufacture of watches and clocks', 173, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(261, '2660', 'Manufacture of irradiation, electromedical and electrotherapeutic equipment', 174, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(262, '2670', 'Manufacture of optical instruments and photographic equipment', 175, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(263, '2680', 'Manufacture of magnetic and optical media', 176, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(264, '2710', 'Manufacture of electric motors, generators, transformers and electricity distribution and control apparatus', 177, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(265, '2720', 'Manufacture of batteries and accumulators', 178, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(266, '2731', 'Manufacture of fibre optic cables', 179, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(267, '2732', 'Manufacture of other electronic and electric wires and cables', 179, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(268, '2733', 'Manufacture of wiring devices', 179, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(269, '2740', 'Manufacture of electric lighting equipment', 180, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(270, '2750', 'Manufacture of domestic appliances', 181, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(271, '2790', 'Manufacture of other electrical equipment', 182, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(272, '2811', 'Manufacture of engines and turbines, except aircraft, vehicle and cycle engines', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(273, '2812', 'Manufacture of fluid power equipment', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(274, '2813', 'Manufacture of other pumps, compressors, taps and valves', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(275, '2814', 'Manufacture of bearings, gears, gearing and driving elements', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(276, '2815', 'Manufacture of ovens, furnaces and furnace burners', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(277, '2816', 'Manufacture of lifting and handling equipment', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(278, '2817', 'Manufacture of office machinery and equipment (except computers and peripheral equipment)', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(279, '2818', 'Manufacture of power-driven hand tools', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(280, '2819', 'Manufacture of other general-purpose machinery', 183, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(281, '2821', 'Manufacture of agricultural and forestry machinery', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(282, '2822', 'Manufacture of metal-forming machinery and machine tools', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(283, '2823', 'Manufacture of machinery for metallurgy', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(284, '2824', 'Manufacture of machinery for mining, quarrying and construction', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(285, '2825', 'Manufacture of machinery for food, beverage and tobacco processing', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(286, '2826', 'Manufacture of machinery for textile, apparel and leather production', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(287, '2829', 'Manufacture of other special-purpose machinery', 184, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(288, '2910', 'Manufacture of motor vehicles', 185, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(289, '2920', 'Manufacture of bodies (coachwork) for motor vehicles; manufacture of trailers and semi-trailers', 186, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(290, '2930', 'Manufacture of parts and accessories for motor vehicles', 187, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(291, '3011', 'Building of ships and floating structures', 188, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(292, '3012', 'Building of pleasure and sporting boats', 188, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(293, '3020', 'Manufacture of railway locomotives and rolling stock', 189, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(294, '3030', 'Manufacture of air and spacecraft and related machinery', 190, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(295, '3040', 'Manufacture of military fighting vehicles', 191, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(296, '3091', 'Manufacture of motorcycles', 192, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(297, '3092', 'Manufacture of bicycles and invalid carriages', 192, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(298, '3099', 'Manufacture of other transport equipment n.e.c.', 192, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(299, '3100', 'Manufacture of furniture', 193, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(300, '3211', 'Manufacture of jewellery and related articles', 194, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(301, '3212', 'Manufacture of imitation jewellery and related articles', 194, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(302, '3220', 'Manufacture of musical instruments', 195, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(303, '3230', 'Manufacture of sports goods', 196, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(304, '3240', 'Manufacture of games and toys', 197, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(305, '3250', 'Manufacture of medical and dental instruments and supplies', 198, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(306, '3290', 'Other manufacturing n.e.c.', 199, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(307, '3311', 'Repair of fabricated metal products', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(308, '3312', 'Repair of machinery', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(309, '3313', 'Repair of electronic and optical equipment', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(310, '3314', 'Repair of electrical equipment', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(311, '3315', 'Repair of transport equipment, except motor vehicles', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(312, '3319', 'Repair of other equipment', 200, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(313, '3320', 'Installation of industrial machinery and equipment', 201, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(314, '3510', 'Electric power generation, transmission and distribution', 202, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(315, '3520', 'Manufacture of gas; distribution of gaseous fuels through mains', 203, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(316, '3530', 'Steam and air conditioning supply', 204, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(317, '3600', 'Water collection, treatment and supply', 205, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(318, '3700', 'Sewerage', 206, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(319, '3811', 'Collection of non-hazardous waste', 207, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(320, '3812', 'Collection of hazardous waste', 207, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(321, '3821', 'Treatment and disposal of non-hazardous waste', 208, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(322, '3822', 'Treatment and disposal of hazardous waste', 208, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(323, '3830', 'Materials recovery', 209, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(324, '3900', 'Remediation activities and other waste management services', 210, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(325, '4100', 'Construction of buildings', 211, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(326, '4210', 'Construction of roads and railways', 212, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(327, '4220', 'Construction of utility projects', 213, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(328, '4290', 'Construction of other civil engineering projects', 214, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(329, '4311', 'Demolition', 215, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(330, '4312', 'Site preparation', 215, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(331, '4321', 'Electrical installation', 216, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(332, '4322', 'Plumbing, heat and air-conditioning installation', 216, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(333, '4329', 'Other construction installation', 216, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(334, '4330', 'Building completion and finishing', 217, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(335, '4390', 'Other specialized construction activities', 218, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(336, '4510', 'Sale of motor vehicles', 219, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(337, '4520', 'Maintenance and repair of motor vehicles', 220, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(338, '4530', 'Sale of motor vehicle parts and accessories', 221, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(339, '4540', 'Sale, maintenance and repair of motorcycles and related parts and accessories', 222, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(340, '4610', 'Wholesale on a fee or contract basis', 223, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(341, '4620', 'Wholesale of agricultural raw materials and live animals', 224, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(342, '4630', 'Wholesale of food, beverages and tobacco', 225, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(343, '4641', 'Wholesale of textiles, clothing and footwear', 226, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(344, '4649', 'Wholesale of other household goods', 226, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(345, '4651', 'Wholesale of computers, computer peripheral equipment and software', 227, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(346, '4652', 'Wholesale of electronic and telecommunications equipment and parts', 227, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(347, '4653', 'Wholesale of agricultural machinery, equipment and supplies', 227, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(348, '4659', 'Wholesale of other machinery and equipment', 227, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(349, '4661', 'Wholesale of solid, liquid and gaseous fuels and related products', 228, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(350, '4662', 'Wholesale of metals and metal ores', 228, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(351, '4663', 'Wholesale of construction materials, hardware, plumbing and heating equipment and supplies', 228, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(352, '4669', 'Wholesale of waste and scrap and other products n.e.c.', 228, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(353, '4690', 'Non-specialized wholesale trade', 229, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(354, '4711', 'Retail sale in non-specialized stores with food, beverages or tobacco predominating', 230, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(355, '4719', 'Other retail sale in non-specialized stores', 230, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(356, '4721', 'Retail sale of food in specialized stores', 231, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(357, '4722', 'Retail sale of beverages in specialized stores', 231, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(358, '4723', 'Retail sale of tobacco products in specialized stores', 231, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(359, '4730', 'Retail sale of automotive fuel in specialized stores', 232, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(360, '4741', 'Retail sale of computers, peripheral units, software and telecommunications equipment in specialized stores', 233, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(361, '4742', 'Retail sale of audio and video equipment in specialized stores', 233, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(362, '4751', 'Retail sale of textiles in specialized stores', 234, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(363, '4752', 'Retail sale of hardware, paints and glass in specialized stores', 234, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(364, '4753', 'Retail sale of carpets, rugs, wall and floor coverings in specialized stores', 234, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(365, '4759', 'Retail sale of electrical household appliances, furniture, lighting equipment and other household articles in specialized stores', 234, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(366, '4761', 'Retail sale of books, newspapers and stationary in specialized stores', 235, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(367, '4762', 'Retail sale of music and video recordings in specialized stores', 235, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(368, '4763', 'Retail sale of sporting equipment in specialized stores', 235, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(369, '4764', 'Retail sale of games and toys in specialized stores', 235, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(370, '4771', 'Retail sale of clothing, footwear and leather articles in specialized stores', 236, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(371, '4772', 'Retail sale of pharmaceutical and medical goods, cosmetic and toilet articles in specialized stores', 236, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(372, '4773', 'Other retail sale of new goods in specialized stores', 236, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(373, '4774', 'Retail sale of second-hand goods', 236, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(374, '4781', 'Retail sale via stalls and markets of food, beverages and tobacco products', 237, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(375, '4782', 'Retail sale via stalls and markets of textiles, clothing and footwear', 237, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(376, '4789', 'Retail sale via stalls and markets of other goods', 237, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(377, '4791', 'Retail sale via mail order houses or via Internet', 238, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(378, '4799', 'Other retail sale not in stores, stalls or markets', 238, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(379, '4911', 'Passenger rail transport, interurban', 1, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(380, '4912', 'Freight rail transport', 1, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(381, '4921', 'Urban and suburban passenger land transport', 2, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(382, '4922', 'Other passenger land transport', 2, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(383, '4923', 'Freight transport by road', 2, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(384, '4930', 'Transport via pipeline', 3, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(385, '5011', 'Sea and coastal passenger water transport', 4, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(386, '5012', 'Sea and coastal freight water transport', 4, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(387, '5021', 'Inland passenger water transport', 5, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(388, '5022', 'Inland freight water transport', 5, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(389, '5110', 'Passenger air transport', 6, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(390, '5120', 'Freight air transport', 7, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(391, '5210', 'Warehousing and storage', 8, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(392, '5221', 'Service activities incidental to land transportation', 9, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(393, '5222', 'Service activities incidental to water transportation', 9, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(394, '5223', 'Service activities incidental to air transportation', 9, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(395, '5224', 'Cargo handling', 9, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(396, '5229', 'Other transportation support activities', 9, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(397, '5310', 'Postal activities', 10, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(398, '5320', 'Courier activities', 11, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(399, '5510', 'Short term accommodation activities', 12, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(400, '5520', 'Camping grounds, recreational vehicle parks and trailer parks', 13, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(401, '5590', 'Other accommodation', 14, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(402, '5610', 'Restaurants and mobile food service activities', 15, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(403, '5621', 'Event catering', 16, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(404, '5629', 'Other food service activities', 16, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(405, '5630', 'Beverage serving activities', 17, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(406, '5811', 'Book publishing', 18, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(407, '5812', 'Publishing of directories and mailing lists', 18, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(408, '5813', 'Publishing of newspapers, journals and periodicals', 18, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(409, '5819', 'Other publishing activities', 18, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(410, '5820', 'Software publishing', 19, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(411, '5911', 'Motion picture, video and television programme production activities', 20, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(412, '5912', 'Motion picture, video and television programme post-production activities', 20, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(413, '5913', 'Motion picture, video and television programme distribution activities', 20, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(414, '5914', 'Motion picture projection activities', 20, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(415, '5920', 'Sound recording and music publishing activities', 21, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(416, '6010', 'Radio broadcasting', 22, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(417, '6020', 'Television programming and broadcasting activities', 23, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(418, '6110', 'Wired telecommunications activities', 24, '2022-07-12 09:10:55', '2022-07-12 09:10:55'),
(419, '6120', 'Wireless telecommunications activities', 25, '2022-07-12 09:10:55', '2022-07-12 09:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kycs`
--

CREATE TABLE `kycs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_type` bigint(20) UNSIGNED NOT NULL,
  `id_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` enum('Unguja','Pemba') COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residence_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_citizen` tinyint(1) NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `authorities_verified_at` datetime DEFAULT NULL,
  `biometric_verified_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2020_05_24_100000_create_sys_modules_table', 1),
(5, '2020_05_24_101033_create_roles_table', 1),
(6, '2020_05_24_101038_create_users_table', 1),
(7, '2020_05_24_101115_create_permissions_table', 1),
(8, '2020_05_24_102403_create_roles_permissions_table', 1),
(9, '2022_02_25_125620_create_audit_logs_table', 1),
(10, '2022_02_26_221412_create_jobs_table', 1),
(11, '2022_06_14_111828_create_countries_table', 1),
(12, '2022_06_14_112148_create_regions_table', 1),
(13, '2022_06_14_112202_create_districts_table', 1),
(14, '2022_06_14_112211_create_wards_table', 1),
(15, '2022_06_14_113413_create_id_types_table', 1),
(16, '2022_06_14_113647_create_kycs_table', 1),
(17, '2022_06_14_123932_create_taxpayers_table', 1),
(18, '2022_06_14_130926_create_biometrics_table', 1),
(19, '2022_06_14_132434_create_user_otps_table', 1),
(20, '2022_06_14_180558_create_isic1s_table', 1),
(21, '2022_06_14_180606_create_isic2s_table', 1),
(22, '2022_06_14_180613_create_isic3s_table', 1),
(23, '2022_06_14_180623_create_isic4s_table', 1),
(24, '2022_06_14_180725_create_business_categories_table', 1),
(25, '2022_06_14_180743_create_businesses_table', 1),
(26, '2022_06_14_180905_create_tax_types_table', 1),
(27, '2022_06_14_180914_create_banks_table', 1),
(28, '2022_06_14_181023_create_business_owners_table', 1),
(29, '2022_06_14_181330_create_business_tax_type_table', 1),
(30, '2022_06_14_181358_create_business_locations_table', 1),
(31, '2022_06_14_181414_create_business_banks_table', 1),
(32, '2022_06_14_181434_create_business_turnovers_table', 1),
(33, '2022_06_21_132141_create_audits_table', 1),
(34, '2022_06_21_165759_create_business_activities_table', 1),
(35, '2022_06_22_113601_create_currencies_table', 1),
(36, '2022_06_22_141124_create_withholding_agents_table', 1),
(37, '2022_06_23_124247_create_tax_agents_table', 1),
(38, '2022_06_23_125229_create_tax_agent_academic_qualifications_table', 1),
(39, '2022_06_23_125718_create_tax_agent_professionals_table', 1),
(40, '2022_06_23_130221_create_tax_agent_training_experiences_table', 1),
(41, '2022_06_27_111102_create_notifications_table', 1),
(42, '2022_06_27_113053_create_business_consultants_table', 1),
(43, '2022_06_27_172321_create_business_temp_closures', 1),
(44, '2022_06_29_122126_create_business_partners_table', 1),
(45, '2022_06_29_154741_creates_table_ta_payment_configurations_table', 1),
(46, '2022_06_30_142625_creates_table_ta_payment_configuration_history_table', 1),
(47, '2022_07_01_110237_create_account_types_table', 1),
(48, '2022_07_02_184523_create_workflows_table', 1),
(49, '2022_07_02_185251_create_workflow_tasks_table', 1),
(50, '2022_07_04_151115_create_zm_bills_table', 1),
(51, '2022_07_04_151404_create_zm_bill_items_table', 1),
(52, '2022_07_04_161303_create_zm_payments_table', 1),
(53, '2022_07_05_094358_create_exchange_rates_table', 1),
(54, '2022_07_05_160405_create_tax_agent_history_table', 1),
(55, '2022_07_05_170314_create_renew_tax_agent_requests_table', 1),
(56, '2022_07_06_092711_business_deregestrations', 1),
(57, '2022_07_08_083142_create_business_hotels_table', 1),
(58, '2022_07_13_135546_create_wa_responsible_persons_table', 2),
(60, '2022_07_19_143752_add_code_to_tax_types_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sys_module_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `sys_module_id`, `created_at`, `updated_at`) VALUES
(1, 'roles_add', 1, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'withholding_agents_add', 2, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(3, 'withholding_agents_edit', 2, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(4, 'withholding_agents_view', 2, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(5, 'withholding_agents_disable', 2, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(6, 'business_registrations_view', 3, '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `port_tax_categories`
--

CREATE TABLE `port_tax_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate_sign` enum('TZS','USD','%','') NOT NULL,
  `code` varchar(10) NOT NULL,
  `port_tax_service_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `port_tax_categories`
--

INSERT INTO `port_tax_categories` (`id`, `name`, `rate_sign`, `code`, `port_tax_service_code`, `created_at`, `updated_at`) VALUES
(1, 'No. of foreign passengers', 'USD', 'S101', 'PT100', '2022-07-18 09:07:20', NULL),
(2, 'No. of local passengers', 'TZS', 'S102', 'PT100', '2022-07-18 09:07:20', NULL),
(3, 'No. of foreign passengers', 'USD', 'S103', 'PT200', '2022-07-18 09:07:20', NULL),
(4, 'No. of local passengers', 'TZS', 'S104', 'PT200', '2022-07-18 09:07:20', NULL),
(5, 'Infrastructure tax', 'TZS', 'P101', 'PT100', '2022-07-18 09:07:20', NULL),
(6, 'No. of foreign passengers', 'USD', 'P102', 'PT400', '2022-07-18 09:07:20', NULL),
(7, 'No. of local passengers (ZNZ - T/M)', 'TZS', 'P103', 'PT400', '2022-07-18 09:07:20', NULL),
(8, 'No. of local passengers (ZNZ - ZNZ)', 'TZS', 'P104', 'PT400', '2022-07-18 09:07:20', NULL),
(9, 'Infrastructure Tax (ZNZ - ZNZ)', 'TZS', 'P105', 'PT300', '2022-07-18 09:07:20', NULL),
(10, 'Infrastructure Tax (ZNZ - T/M)', 'TZS', 'P106', 'PT300', '2022-07-18 09:07:20', NULL),
(11, 'Value of net sales (USD)', '%', 'P107', 'PT400', '2022-07-18 09:07:20', NULL),
(12, 'value of net sales (TZS)', '%', 'P108', 'PT300', '2022-07-18 09:07:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `port_tax_config_rates`
--

CREATE TABLE `port_tax_config_rates` (
  `id` int(11) NOT NULL,
  `port_tax_category_code` varchar(35) NOT NULL,
  `rate` decimal(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `port_tax_config_rates`
--

INSERT INTO `port_tax_config_rates` (`id`, `port_tax_category_code`, `rate`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'S101', '40.00', 4, '2022-07-19 07:21:11', '2022-07-19 07:30:22'),
(2, 'S102', '10000.00', 4, '2022-07-19 07:46:38', '2022-07-19 07:46:38'),
(3, 'P101', '2000.00', 4, '2022-07-19 07:47:12', '2022-07-19 07:47:12'),
(4, 'S103', '9.00', 4, '2022-07-19 07:47:32', '2022-07-19 07:47:32'),
(5, 'S104', '3000.00', 4, '2022-07-19 07:47:50', '2022-07-19 07:47:50'),
(8, 'P102', '10.00', 4, '2022-07-19 07:50:15', '2022-07-19 07:50:15'),
(9, 'P103', '2000.00', 4, '2022-07-19 07:51:02', '2022-07-19 07:51:02'),
(10, 'P108', '8.00', 4, '2022-07-19 08:38:36', '2022-07-19 08:38:36'),
(11, 'P107', '8.00', 4, '2022-07-19 08:39:01', '2022-07-19 08:39:01'),
(12, 'P104', '1000.00', 4, '2022-07-19 08:39:51', '2022-07-19 08:39:51'),
(13, 'P105', '1000.00', 4, '2022-07-19 08:40:37', '2022-07-19 08:40:37'),
(14, 'P106', '2000.00', 4, '2022-07-19 08:40:51', '2022-07-19 08:40:51');

-- --------------------------------------------------------

--
-- Table structure for table `port_tax_config_rate_history`
--

CREATE TABLE `port_tax_config_rate_history` (
  `id` int(11) NOT NULL,
  `port_tax_config_rates_id` bigint(20) NOT NULL,
  `port_tax_category_code` varchar(35) NOT NULL,
  `rate` decimal(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `port_tax_config_rate_history`
--

INSERT INTO `port_tax_config_rate_history` (`id`, `port_tax_config_rates_id`, `port_tax_category_code`, `rate`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'S101', '20.00', 4, '2022-07-18 18:31:56', '2022-07-18 18:31:56'),
(2, 3, 'S101', '18.00', 4, '2022-07-18 18:41:34', '2022-07-18 18:41:34'),
(3, 1, 'S101', '40.00', 4, '2022-07-19 07:30:08', '2022-07-19 07:30:08'),
(4, 1, 'S101', '50.00', 4, '2022-07-19 07:30:22', '2022-07-19 07:30:22'),
(5, 7, 'P106', '2000.00', 4, '2022-07-19 07:49:08', '2022-07-19 07:49:08'),
(6, 6, 'P105', '1000.00', 4, '2022-07-19 08:28:03', '2022-07-19 08:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `port_tax_returns`
--

CREATE TABLE `port_tax_returns` (
  `id` bigint(20) NOT NULL,
  `business_id` bigint(20) NOT NULL,
  `return_month` varchar(35) NOT NULL,
  `port_tax_type` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `port_tax_returns`
--

INSERT INTO `port_tax_returns` (`id`, `business_id`, `return_month`, `port_tax_type`, `created_at`, `updated_at`) VALUES
(8, 1, 'Jun, 2022', 'cargo vat', '2022-07-14 14:49:51', '2022-07-14 14:49:51'),
(9, 1, 'Jul, 2022', 'passenger vat', '2022-07-14 17:45:46', '2022-07-14 17:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `port_tax_services`
--

CREATE TABLE `port_tax_services` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `port_tax_services`
--

INSERT INTO `port_tax_services` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Airport tax ', 'PT100', '2022-07-18 08:53:44', NULL),
(2, 'Sea port', 'PT300', '2022-07-18 08:53:44', NULL),
(3, 'Safety fees', 'PT200', '2022-07-18 08:53:44', NULL),
(4, 'Sea transport tax', 'PT400', '2022-07-18 08:53:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Unguja', '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'Pemba', '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `renew_tax_agent_requests`
--

CREATE TABLE `renew_tax_agent_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_agent_id` bigint(20) NOT NULL,
  `status` enum('pending','processed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `renew_tax_agent_requests`
--

INSERT INTO `renew_tax_agent_requests` (`id`, `tax_agent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'pending', '2022-07-12 12:15:19', '2022-07-12 12:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `report_to`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 'Registration Manager', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(3, 'Registration Officer', 2, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(4, 'Compliance Manager', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(5, 'Compliance Officer', 4, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(6, 'Directory Of TRAI', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(7, 'Commissioner', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(8, 'Audit Manager', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(1, 3, NULL, NULL),
(1, 4, NULL, NULL),
(1, 5, NULL, NULL),
(1, 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sys_modules`
--

CREATE TABLE `sys_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_modules`
--

INSERT INTO `sys_modules` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Configurations', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(2, 'WithholdingAgents', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(3, 'BusinessManagement', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxpayers`
--

CREATE TABLE `taxpayers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_type` bigint(20) UNSIGNED NOT NULL,
  `id_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `physical_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` enum('Unguja','Pemba') COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residence_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_citizen` tinyint(1) NOT NULL,
  `is_first_login` tinyint(1) NOT NULL DEFAULT 1,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biometric_verified_at` datetime NOT NULL,
  `authorities_verified_at` datetime NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxpayers`
--

INSERT INTO `taxpayers` (`id`, `reference_no`, `id_type`, `id_number`, `first_name`, `middle_name`, `last_name`, `physical_address`, `street`, `email`, `mobile`, `alt_mobile`, `location`, `work_permit`, `residence_permit`, `is_citizen`, `is_first_login`, `country_id`, `password`, `biometric_verified_at`, `authorities_verified_at`, `email_verified_at`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '556677', 1, '12312123123123123', 'John', 'Tim', 'Doe', 'P.O.Box 887, Unguja, Zanzibar.', 'Main Street', 'meshackf1@gmail.com', '0700000000', '0754555555', 'Unguja', 'sample', 'sample', 1, 0, 1, '$2y$10$TbH.Dy7Nw5fVnPMvlupq2eLQm8dxhAeZJwYaQxRhp4C9MCuNh.eVO', '2022-07-12 12:10:53', '2022-07-12 12:10:53', NULL, NULL, NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, '556688', 1, '12312123123123123', 'Jane', 'Middle', 'Doe', 'P.O.Box 887, Unguja, Zanzibar.', 'Main Street', 'v.meshack@live.co.uk', '0700000001', '0754555555', 'Unguja', 'sample', 'sample', 1, 0, 1, '$2y$10$pm0BPWPVMFRiVpLhF7PpOeNIjzZSYcUP4j/vJBkKKx//3GrjSbppO', '2022-07-12 12:10:53', '2022-07-12 12:10:53', NULL, NULL, NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `tax_agents`
--

CREATE TABLE `tax_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tin_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plot_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `block` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `town` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_no` varchar(35) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('drafting','pending','approved','rejected','completed','verified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'drafting',
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `is_first_application` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `app_first_date` datetime DEFAULT NULL,
  `app_expire_date` datetime DEFAULT NULL,
  `taxpayer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_agents`
--

INSERT INTO `tax_agents` (`id`, `tin_no`, `plot_no`, `block`, `town`, `region`, `reference_no`, `status`, `is_paid`, `is_first_application`, `app_first_date`, `app_expire_date`, `taxpayer_id`, `created_at`, `updated_at`) VALUES
(1, '123123', '123123', 'Block A', 'Unguja', 'Unguja', 'ZRB909090', 'drafting', 0, '1', NULL, NULL, 2, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'Quam quia molestias ', 'In et Nam aliquid vo', 'Brady Jimenez', 'Pariatur Hic numqua', 'A nulla numquam aliq', 'ZRB105183', 'approved', 0, '0', '2022-07-12 15:28:06', '2023-07-12 15:28:06', 1, '2022-07-12 09:12:26', '2022-07-12 12:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `tax_agent_academic_qualifications`
--

CREATE TABLE `tax_agent_academic_qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `examining_body` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `division_id` int(11) NOT NULL,
  `tax_agent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_agent_academic_qualifications`
--

INSERT INTO `tax_agent_academic_qualifications` (`id`, `school_name`, `from`, `to`, `examining_body`, `division_id`, `tax_agent_id`, `created_at`, `updated_at`) VALUES
(1, 'Aperiam iure numquam', '1972-06-26', '1986-09-05', 'Consequatur Earum s', 2, 2, '2022-07-12 09:12:32', '2022-07-12 09:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `tax_agent_history`
--

CREATE TABLE `tax_agent_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_agent_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('first','renew') COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_first_date` datetime NOT NULL,
  `app_expire_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_agent_history`
--

INSERT INTO `tax_agent_history` (`id`, `tax_agent_id`, `status`, `app_first_date`, `app_expire_date`, `created_at`, `updated_at`) VALUES
(1, 2, 'first', '2022-07-12 12:32:28', '2022-07-12 12:32:28', '2022-07-12 12:15:19', '2022-07-12 12:15:19');

-- --------------------------------------------------------

--
-- Table structure for table `tax_agent_professionals`
--

CREATE TABLE `tax_agent_professionals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `body_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passed_sections` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_passed` date NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_agent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_agent_professionals`
--

INSERT INTO `tax_agent_professionals` (`id`, `body_name`, `reg_no`, `passed_sections`, `date_passed`, `remarks`, `tax_agent_id`, `created_at`, `updated_at`) VALUES
(1, 'Laboriosam eos des', 'Eveniet ipsam ut ad', 'Et veniam eum volup', '2000-07-05', 'Possimus quo corpor', 2, '2022-07-12 09:12:36', '2022-07-12 09:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `tax_agent_training_experiences`
--

CREATE TABLE `tax_agent_training_experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `position_held` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_agent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_agent_training_experiences`
--

INSERT INTO `tax_agent_training_experiences` (`id`, `org_name`, `from`, `to`, `position_held`, `description`, `tax_agent_id`, `created_at`, `updated_at`) VALUES
(1, 'Aliqua Facere tenet', '2021-05-15', '2022-02-19', 'Ullam reiciendis ius', 'Doloremque quidem ul', 2, '2022-07-12 09:12:56', '2022-07-12 09:12:56');

-- --------------------------------------------------------

--
-- Table structure for table `tax_types`
--

CREATE TABLE `tax_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_types`
--

INSERT INTO `tax_types` (`id`, `code`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'vat', 'VAT', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'hotel-levy', 'Hotel Levy', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'restaurant-levy', 'Restaurant Levy', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(4, 'tour-operator-levy', 'Tour Operation Levy', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(5, 'land-lease', 'Land Lease', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(6, 'public-service', 'Public Services', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(7, 'excise-duty', 'Excercise Duty', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(8, 'petroleum-levy', 'Petroleum Levy', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(9, 'airport-service', 'Airport Service Charge', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(10, 'airport-safety', 'Airport Safety Fee', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(11, 'seaport-service', 'Sea Port Service Charge', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(12, 'seaport-transport', 'Sea Port Transport Charges', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(13, 'tax-consultant', 'Tax Consultant Licences', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `ta_payment_configurations`
--

CREATE TABLE `ta_payment_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` enum('registration fee','renewal fee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `amount` double(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ta_payment_configurations`
--

INSERT INTO `ta_payment_configurations` (`id`, `category`, `duration`, `no_of_days`, `amount`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'registration fee', NULL, NULL, 100000.00, 4, '2022-07-12 09:14:25', '2022-07-12 09:14:25'),
(2, 'renewal fee', 'yearly', 12, 100000.00, 4, '2022-07-12 09:14:40', '2022-07-12 09:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `ta_payment_configuration_history`
--

CREATE TABLE `ta_payment_configuration_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` enum('first fee','renewal fee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tapc_id` bigint(20) NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_days` int(11) DEFAULT NULL,
  `amount` double(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `is_first_login` tinyint(1) NOT NULL DEFAULT 1,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `phone`, `gender`, `email`, `email_verified_at`, `password`, `status`, `is_first_login`, `role_id`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super', 'Admin', '12323232323', NULL, 'admin@gmail.com', '2022-07-12 09:10:52', '$2y$10$AJNHsLDnyz3v1lAHrszGSOL5IfjzRz6xOYi7cNOwsSvcx.bkAqJ0O', 1, 0, 1, '2fK4H4et1r', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(2, 'Kedmon', 'Joseph', '0675580888', NULL, 'jkedmon95@gmail.com', '2022-07-12 09:10:52', '$2y$10$3wGmNnZumkSA67eFz6uGyOuFZ2uwSSZGiqewyC75fwtRb.VpUefGO', 1, 1, 1, 'MhVIzkJXfY', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(3, 'Gozbert', 'Stanslaus', '0766583354', NULL, 'gozbeths@gmail.com', '2022-07-12 09:10:52', '$2y$10$YrHLXnnhg9/B9q5KdNS4xOBj0DiqPaX7WCmdlQ8AbBOJJIc3N37wi', 1, 1, 1, 'Q6oelFHPAv', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(4, 'Lucks', 'Isack', '0759155015', NULL, 'lucksisack2@gmail.com', '2022-07-12 09:10:52', '$2y$10$kIKjRC.m4FRF3VDMwMIezes6Vztc9sX7ChqNiLAAirc9y3dOLOEdu', 1, 0, 1, '8HV3g66PznXytlncVtVUA9dp8vjKUrlKztgG7mNkbswfyRT4tnXYTjsxSBwo', '2022-07-12 09:10:52', '2022-07-12 09:13:39', NULL),
(5, 'Meshack', 'Victor', '0753550590', NULL, 'meshackf1@gmail.com', '2022-07-12 09:10:52', '$2y$10$r0EV87hHHqsXF8HEemmmeO0.VaTo5Ax3OWhLAeNXMpLRDh.9zoX5O', 1, 0, 1, 'AXT6kY54Qu', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(6, 'Mang\'erere', 'Mgini', '0743317069', NULL, 'juniorshemm@gmail.com', '2022-07-12 09:10:52', '$2y$10$LFO9dLatPo57niLWRhqQMObwnSWBsq2rKWPTHE013AXW.Oe9iW6Mq', 1, 1, 1, 'J5uacTzSz2', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(7, 'Noor', 'Noor', '0656731663', NULL, 'noor.abdulrahim@ubx.co.tz', '2022-07-12 09:10:52', '$2y$10$vTuiEjGfdAX4pBAGtScycOh7H36hM8bdu.JUTVKtF.HE1mHCNfFBe', 1, 1, 1, '2wj0fbDuVo', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL),
(8, 'Victor', 'Massawe', '0656642323', NULL, 'Victor.Massawe@ubx.co.tz', '2022-07-12 09:10:52', '$2y$10$L9hb67Dd//6Tn91PUX22g..vwR3gUAq7JxARBteq3K7pWTt754DHe', 1, 1, 1, 'kO4hqgp4Hp', '2022-07-12 09:10:52', '2022-07-12 09:10:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_otps`
--

CREATE TABLE `user_otps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_otps`
--

INSERT INTO `user_otps` (`id`, `user_id`, `user_type`, `code`, `used`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Taxpayer', '123456', 0, '2022-07-12 09:11:38', '2022-07-21 06:03:16'),
(2, 4, 'App\\Models\\User', '123456', 0, '2022-07-12 09:13:53', '2022-07-20 11:55:29'),
(3, 2, 'App\\Models\\Taxpayer', '123456', 0, '2022-07-12 09:51:55', '2022-07-13 08:39:27'),
(4, 1, 'App\\Models\\User', '123456', 0, '2022-07-14 19:00:32', '2022-07-14 19:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `vat_categories`
--

CREATE TABLE `vat_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL,
  `code` varchar(10) NOT NULL,
  `vat_services_code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vat_categories`
--

INSERT INTO `vat_categories` (`id`, `name`, `code`, `vat_services_code`, `created_at`, `updated_at`) VALUES
(1, 'Standard rated supplies', 'S101', 'VS100', '2022-07-18 09:07:20', NULL),
(2, 'Zero rated supplies', 'S102', 'VS100', '2022-07-18 09:07:20', NULL),
(3, 'Exempt supplies', 'S103', 'VS100', '2022-07-18 09:07:20', NULL),
(4, 'Special relief', 'S104', 'VS100', '2022-07-18 09:07:20', NULL),
(5, 'Exempt Import Purchases', 'P101', 'VS200', '2022-07-18 09:07:20', NULL),
(6, 'Exempt local purchases', 'P102', 'VS200', '2022-07-18 09:07:20', NULL),
(7, 'Non-credible purchases', 'P103', 'VS200', '2022-07-18 09:07:20', NULL),
(8, 'Vat differed purchases', 'P104', 'VS200', '2022-07-18 09:07:20', NULL),
(9, 'Standard local purchases', 'P105', 'VS200', '2022-07-18 09:07:20', NULL),
(10, 'Standard rated imports', 'P106', 'VS200', '2022-07-18 09:07:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vat_config_rates`
--

CREATE TABLE `vat_config_rates` (
  `id` int(11) NOT NULL,
  `vat_category_code` varchar(35) NOT NULL,
  `rate` decimal(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vat_config_rates`
--

INSERT INTO `vat_config_rates` (`id`, `vat_category_code`, `rate`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'S102', '10.00', 4, '2022-07-18 18:23:39', '2022-07-18 18:23:39'),
(3, 'S101', '15.00', 4, '2022-07-18 18:31:56', '2022-07-19 08:43:27'),
(4, 'S103', '10.00', 4, '2022-07-18 18:38:05', '2022-07-18 18:38:05');

-- --------------------------------------------------------

--
-- Table structure for table `vat_config_rate_history`
--

CREATE TABLE `vat_config_rate_history` (
  `id` int(11) NOT NULL,
  `vat_config_rates_id` bigint(20) NOT NULL,
  `vat_category_code` varchar(35) NOT NULL,
  `rate` decimal(40,2) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vat_config_rate_history`
--

INSERT INTO `vat_config_rate_history` (`id`, `vat_config_rates_id`, `vat_category_code`, `rate`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'S101', '20.00', 4, '2022-07-18 18:31:56', '2022-07-18 18:31:56'),
(2, 3, 'S101', '18.00', 4, '2022-07-18 18:41:34', '2022-07-18 18:41:34'),
(3, 3, 'S101', '16.00', 4, '2022-07-19 08:43:27', '2022-07-19 08:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `vat_returns`
--

CREATE TABLE `vat_returns` (
  `id` int(11) NOT NULL,
  `business_id` bigint(20) NOT NULL,
  `financial_year` varchar(35) NOT NULL DEFAULT current_timestamp(),
  `return_month` varchar(35) NOT NULL,
  `taxtype_code` varchar(35) NOT NULL,
  `standard_rated_supplies` decimal(40,2) NOT NULL,
  `zero_rated_supplies` decimal(40,2) DEFAULT NULL,
  `exempt_supplies` decimal(40,2) DEFAULT NULL,
  `special_relief` decimal(40,2) DEFAULT NULL,
  `exempt_import_purchases` decimal(40,2) DEFAULT NULL,
  `standard_local_purchases` decimal(40,2) DEFAULT NULL,
  `standard_rated_imports` decimal(40,2) DEFAULT NULL,
  `total_input_tax` decimal(40,2) NOT NULL,
  `total_vat_payable` decimal(40,2) NOT NULL,
  `vat_withheld` decimal(40,2) NOT NULL,
  `vat_credit_brought_forward` decimal(40,2) NOT NULL,
  `infrastructure_tax` decimal(40,2) DEFAULT NULL,
  `total_vat_amount_due` decimal(40,2) NOT NULL,
  `has_exemption` enum('yes','no','','') NOT NULL,
  `status` enum('drafting','pending','verified','rejected','approved') NOT NULL DEFAULT 'drafting',
  `payment_due_date` date NOT NULL DEFAULT current_timestamp(),
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vat_returns`
--

INSERT INTO `vat_returns` (`id`, `business_id`, `financial_year`, `return_month`, `taxtype_code`, `standard_rated_supplies`, `zero_rated_supplies`, `exempt_supplies`, `special_relief`, `exempt_import_purchases`, `standard_local_purchases`, `standard_rated_imports`, `total_input_tax`, `total_vat_payable`, `vat_withheld`, `vat_credit_brought_forward`, `infrastructure_tax`, `total_vat_amount_due`, `has_exemption`, `status`, `payment_due_date`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2022', 'july', 'vat', '150000.00', NULL, NULL, '0.00', NULL, '60000.00', '3660.00', '63660.00', '86340.00', '0.00', '0.00', '0.00', '86340.00', 'no', 'drafting', '2022-07-20', 1, '2022-07-20 11:37:24', '2022-07-20 11:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `vat_services`
--

CREATE TABLE `vat_services` (
  `id` int(11) NOT NULL,
  `name` varchar(35) NOT NULL,
  `code` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vat_services`
--

INSERT INTO `vat_services` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Supplies of goods & services', 'VS100', '2022-07-18 08:53:44', NULL),
(2, 'Purchases (Inputs)', 'VS200', '2022-07-18 08:53:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`id`, `district_id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bandamaji', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(2, 1, 'Chaani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(3, 1, 'Kubwa', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(4, 1, 'Masingini', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(5, 1, 'Fukuchani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(6, 1, 'Gamba', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(7, 1, 'Kandwi', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(8, 1, 'Kibeni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(9, 1, 'Kidoti', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(10, 1, 'Kigunda', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(11, 1, 'Kijini', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(12, 1, 'Kikobweni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(13, 1, 'Kinyasini', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(14, 1, 'Kivinge', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(15, 1, 'Matemwe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(16, 1, 'Mchena', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(17, 1, 'Shauri', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(18, 1, 'Mkokotoni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(19, 1, 'Mkwajuni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(20, 1, 'Moga', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(21, 1, 'Mto', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(22, 1, 'Pwani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(23, 1, 'Muwange', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(24, 1, 'Nungwi', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(25, 1, 'Pale', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(26, 1, 'Pitanazako', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(27, 1, 'Potoa', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(28, 1, 'Mchangani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(29, 1, 'Tazari', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(30, 1, 'Tumbatu', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(31, 1, 'Gomani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(32, 1, 'Jongowe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(33, 2, 'Done Mchagani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(34, 2, 'Donge Karange', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(35, 2, 'Donge Kipange', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(36, 2, 'Donge Mbiji', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(37, 2, 'Donge Mnyimbi', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(38, 2, 'Donge Mtambile', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(39, 2, 'Donge Vijibweni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(40, 2, 'Fujoni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(41, 2, 'Kinduni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(42, 2, 'Kiomba Mvua', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(43, 2, 'Kiombero', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(44, 2, 'Kitope', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(45, 2, 'Kiwengwa', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(46, 2, 'Mahonda', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(47, 2, 'Makoba', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(48, 2, 'Manga Pwani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(49, 2, 'Mgambo', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(50, 2, 'Misufini', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(51, 2, 'Mkadini', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(52, 2, 'Muwanda', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(53, 2, 'Pangeni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(54, 2, 'Upenja', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(55, 2, 'Zingwe Zingwe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(56, 4, 'Bopwe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(57, 4, 'Fundo', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(58, 4, 'Gando', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(59, 4, 'Jadida', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(60, 4, 'Kangagani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(61, 4, 'Kipangani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(62, 4, 'Kisiwani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(63, 4, 'Kizimbani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(64, 4, 'Kojani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(65, 4, 'Limbani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(66, 4, 'Mchanga', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(67, 4, 'Mdogo', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(68, 4, 'Mtambwe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(69, 4, 'Ole', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(70, 4, 'Pandani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(71, 4, 'Selemu', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(72, 4, 'Shengejuu', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(73, 4, 'Utaani', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(74, 3, 'Kinowe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(75, 3, 'Kiuyu', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(76, 3, 'Maziwa', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(77, 3, 'Ng\'ombe', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(78, 3, 'Konde', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(79, 3, 'Mgogoni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(80, 3, 'Micheweni', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(81, 3, 'Msuka', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(82, 3, 'Shumba', NULL, '2022-07-12 09:10:52', '2022-07-12 09:10:52'),
(83, 3, 'Viamboni', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(84, 3, 'Tumbe', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(85, 3, 'Wingwi', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(86, 3, 'Mapofu', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(87, 3, 'Njuguni', NULL, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `wa_responsible_persons`
--

CREATE TABLE `wa_responsible_persons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `withholding_agent_id` bigint(20) UNSIGNED NOT NULL,
  `responsible_person_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withholding_agents`
--

CREATE TABLE `withholding_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tin` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wa_number` bigint(20) NOT NULL,
  `institution_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institution_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_commencing` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `responsible_person_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withholding_agents`
--

INSERT INTO `withholding_agents` (`id`, `tin`, `address`, `wa_number`, `institution_name`, `institution_place`, `email`, `mobile`, `position`, `date_of_commencing`, `title`, `ward_id`, `region_id`, `district_id`, `responsible_person_id`, `officer_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 434543960, 'PO BOX 345 Ilala, Dar es salaam', 123456789, 'Necta', 'Amani', 'necta@go.tz', '0743900900', 'Director', '2022-07-12 09:10:53', 'Mr', 1, 1, 1, 1, 1, 'active', '2022-07-12 09:10:53', '2022-07-12 09:10:53', NULL),
(2, 545049506, 'PO BOX 139 Posta, Dar es salaam', 2345678901, 'UNHCR', '23 Kibeni Road', 'unhcr@un.org', '0692700700', 'Chairman', '2022-07-12 09:10:53', 'Dr', 1, 1, 1, 1, 1, 'active', '2022-07-12 09:10:53', '2022-07-12 09:10:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workflows`
--

CREATE TABLE `workflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marking_store` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initial_marking` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supports` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `places` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transitions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workflows`
--

INSERT INTO `workflows` (`id`, `code`, `name`, `type`, `marking_store`, `initial_marking`, `supports`, `places`, `transitions`, `summary`, `active`, `created_at`, `updated_at`) VALUES
(1, 'BUSSINESS_REGISTRATION', 'business_registration', 'workflow', '{\"type\":\"multiple_state\",\"property\":[\"marking\"]}', 'apply', 'App\\Models\\Business', '{\"apply\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[]},\"correct_application\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[]},\"registration_officer\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,3]},\"registration_manager\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,2]},\"director_of_trai\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,6]},\"completed\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[]}}', '{\"application_submitted\":{\"from\":\"apply\",\"to\":\"registration_officer\",\"condition\":\"\"},\"registration_officer_review\":{\"from\":\"registration_officer\",\"to\":\"registration_manager\",\"condition\":\"\"},\"application_filled_incorrect\":{\"from\":\"registration_officer\",\"to\":\"correct_application\",\"condition\":\"\"},\"application_corrected\":{\"from\":\"correct_application\",\"to\":\"registration_officer\",\"condition\":\"\"},\"registration_manager_review\":{\"from\":\"registration_manager\",\"to\":\"director_of_trai\",\"condition\":\"\"},\"registration_manager_reject\":{\"from\":\"registration_manager\",\"to\":\"registration_officer\",\"condition\":\"\"},\"director_of_trai_review\":{\"from\":\"director_of_trai\",\"to\":\"completed\",\"condition\":\"\"},\"director_of_trai_reject\":{\"from\":\"director_of_trai\",\"to\":\"registration_manager\",\"condition\":\"\"}}', 'Bussiness Registraiton Workflow', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(2, 'BUSSINESS_CLOSURE', 'business_closure', 'workflow', '{\"type\":\"multiple_state\",\"property\":[\"marking\"]}', 'apply', 'App\\Models\\BusinessTempClosure', '{\"apply\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[]},\"correct_application\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[]},\"compliance_manager\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,2,3]},\"compliance_officer\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,2,3]},\"completed\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[]}}', '{\"application_submitted\":{\"from\":\"apply\",\"to\":\"compliance_manager\",\"condition\":\"\"},\"compliance_manager_review\":{\"from\":\"compliance_manager\",\"to\":\"compliance_officer\",\"condition\":\"\"},\"compliance_officer_review\":{\"from\":\"compliance_officer\",\"to\":\"completed\",\"condition\":\"\"},\"application_filled_incorrect\":{\"from\":\"compliance_officer\",\"to\":\"correct_application\",\"condition\":\"\"},\"application_corrected\":{\"from\":\"correct_application\",\"to\":\"compliance_officer\",\"condition\":\"\"}}', 'Bussiness Closure Workflow', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53'),
(3, 'BUSSINESS_DEREGISTRATION', 'business_deregister', 'workflow', '{\"type\":\"multiple_state\",\"property\":[\"marking\"]}', 'apply', 'App\\Models\\BusinessDeregistration', '{\"apply\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[]},\"correct_application\":{\"owner\":\"taxpayer\",\"operator_type\":\"user\",\"operators\":[1,2,3]},\"audit_manager\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,2,3]},\"commissioner\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[1,2,3]},\"completed\":{\"owner\":\"staff\",\"operator_type\":\"role\",\"operators\":[]}}', '{\"application_submitted\":{\"from\":\"apply\",\"to\":\"audit_manager\",\"condition\":\"\"},\"audit_manager_review\":{\"from\":\"audit_manager\",\"to\":\"commissioner\",\"condition\":\"\"},\"commissioner_review\":{\"from\":\"commissioner\",\"to\":\"completed\",\"condition\":\"\"},\"commissioner_reject\":{\"from\":\"commissioner\",\"to\":\"audit_manager\",\"condition\":\"\"},\"application_filled_incorrect\":{\"from\":\"audit_manager\",\"to\":\"correct_application\",\"condition\":\"\"},\"application_corrected\":{\"from\":\"correct_application\",\"to\":\"audit_manager\",\"condition\":\"\"}}', 'Bussiness Deregistration Workflow', 1, '2022-07-12 09:10:53', '2022-07-12 09:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_tasks`
--

CREATE TABLE `workflow_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pinstance_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinstance_id` bigint(20) UNSIGNED NOT NULL,
  `workflow_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` enum('staff','system','taxpayer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operators` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_on` datetime DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('hold','destroy','reject','running','completed','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `workflow_tasks`
--

INSERT INTO `workflow_tasks` (`id`, `pinstance_type`, `pinstance_id`, `workflow_id`, `name`, `from_place`, `to_place`, `owner`, `operator_type`, `operators`, `approved_on`, `user_id`, `user_type`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Business', 1, 1, 'application_submitted', 'apply', 'registration_officer', 'staff', 'role', '[1,3]', '2022-07-12 12:50:41', 1, 'App\\Models\\Taxpayer', 'running', NULL, '2022-07-12 09:50:41', '2022-07-12 09:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `zm_bills`
--

CREATE TABLE `zm_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `misc_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `currency` enum('TZS','USD') COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` decimal(8,2) NOT NULL,
  `equivalent_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `control_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expire_date` date NOT NULL,
  `payer_id` bigint(20) UNSIGNED NOT NULL,
  `payer_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_option` int(11) NOT NULL,
  `status` enum('pending','paid','partially','failed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancellation_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zan_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zan_trx_sts_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdby_id` bigint(20) UNSIGNED DEFAULT NULL,
  `createdby_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zm_bills`
--

INSERT INTO `zm_bills` (`id`, `amount`, `misc_amount`, `paid_amount`, `currency`, `exchange_rate`, `equivalent_amount`, `control_number`, `expire_date`, `payer_id`, `payer_type`, `payer_name`, `payer_phone_number`, `payer_email`, `description`, `payment_option`, `status`, `cancellation_reason`, `zan_status`, `zan_trx_sts_code`, `createdby_id`, `createdby_type`, `created_at`, `updated_at`) VALUES
(1, '100000.00', '0.00', '0.00', 'TZS', '0.00', '0.00', '98822', '2022-08-12', 1, 'App\\Models\\Taxpayer', 'John Doe', '0700000000', 'meshackf1@gmail.com', 'Tax agent registration fee', 1, 'paid', NULL, NULL, NULL, 4, 'App\\Models\\User', '2022-07-12 09:18:02', '2022-07-12 09:18:02'),
(2, '100000.00', '0.00', '0.00', 'TZS', '0.00', '0.00', NULL, '2022-08-12', 1, 'App\\Models\\Taxpayer', 'John Doe', '0700000000', 'meshackf1@gmail.com', 'Tax agent renew fee', 1, 'pending', NULL, NULL, NULL, 4, 'App\\Models\\User', '2022-07-12 12:28:06', '2022-07-12 12:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `zm_bill_items`
--

CREATE TABLE `zm_bill_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zm_bill_id` bigint(20) UNSIGNED NOT NULL,
  `billable_id` bigint(20) UNSIGNED NOT NULL,
  `billable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fee_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `exchange_rate` decimal(8,2) NOT NULL,
  `equivalent_amount` decimal(8,2) NOT NULL,
  `currency` enum('TZS','USD') COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT 0,
  `use_item_ref_on_pay` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `gfs_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zm_bill_items`
--

INSERT INTO `zm_bill_items` (`id`, `zm_bill_id`, `billable_id`, `billable_type`, `fee_id`, `fee_type`, `amount`, `exchange_rate`, `equivalent_amount`, `currency`, `paid`, `use_item_ref_on_pay`, `gfs_code`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'App\\Models\\TaxAgent', 1, 'App\\Models\\TaPaymentConfiguration', '100000.00', '0.00', '0.00', 'TZS', 0, 'N', '116101', '2022-07-12 09:18:02', '2022-07-12 09:18:02'),
(2, 2, 1, 'App\\Models\\Taxpayer', 2, 'App\\Models\\TaPaymentConfiguration', '100000.00', '0.00', '0.00', 'TZS', 0, 'N', '116101', '2022-07-12 12:28:06', '2022-07-12 12:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `zm_payments`
--

CREATE TABLE `zm_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zm_bill_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trx_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sp_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_ref_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `control_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_pay_out` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trx_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usd_pay_channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `psp_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `crt_acc_num` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biometrics`
--
ALTER TABLE `biometrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `biometrics_taxpayer_id_foreign` (`taxpayer_id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_activities`
--
ALTER TABLE `business_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_banks`
--
ALTER TABLE `business_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_categories`
--
ALTER TABLE `business_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_consultants`
--
ALTER TABLE `business_consultants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_deregistrations`
--
ALTER TABLE `business_deregistrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_deregistrations_business_id_foreign` (`business_id`),
  ADD KEY `business_deregistrations_approved_by_foreign` (`approved_by`),
  ADD KEY `business_deregistrations_rejected_by_foreign` (`rejected_by`);

--
-- Indexes for table `business_hotels`
--
ALTER TABLE `business_hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_locations`
--
ALTER TABLE `business_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_owners`
--
ALTER TABLE `business_owners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_owners_taxpayer_id_foreign` (`taxpayer_id`),
  ADD KEY `business_owners_business_id_foreign` (`business_id`);

--
-- Indexes for table `business_partners`
--
ALTER TABLE `business_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_tax_type`
--
ALTER TABLE `business_tax_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_temp_closures`
--
ALTER TABLE `business_temp_closures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_temp_closures_business_id_foreign` (`business_id`),
  ADD KEY `business_temp_closures_approved_by_foreign` (`approved_by`),
  ADD KEY `business_temp_closures_rejected_by_foreign` (`rejected_by`);

--
-- Indexes for table `business_turnovers`
--
ALTER TABLE `business_turnovers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_turnovers_business_id_foreign` (`business_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_name_unique` (`name`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_region_id_foreign` (`region_id`);

--
-- Indexes for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_months`
--
ALTER TABLE `financial_months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `financial_year`
--
ALTER TABLE `financial_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `id_types`
--
ALTER TABLE `id_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_types_name_unique` (`name`);

--
-- Indexes for table `isic1s`
--
ALTER TABLE `isic1s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `isic2s`
--
ALTER TABLE `isic2s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `isic2s_isic1_id_foreign` (`isic1_id`);

--
-- Indexes for table `isic3s`
--
ALTER TABLE `isic3s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `isic3s_isic2_id_foreign` (`isic2_id`);

--
-- Indexes for table `isic4s`
--
ALTER TABLE `isic4s`
  ADD PRIMARY KEY (`id`),
  ADD KEY `isic4s_isic3_id_foreign` (`isic3_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `kycs`
--
ALTER TABLE `kycs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kycs_reference_no_unique` (`reference_no`),
  ADD UNIQUE KEY `kycs_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `kycs_email_unique` (`email`),
  ADD KEY `kycs_id_type_foreign` (`id_type`),
  ADD KEY `kycs_country_id_foreign` (`country_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_sys_module_id_foreign` (`sys_module_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `port_tax_categories`
--
ALTER TABLE `port_tax_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `port_tax_config_rates`
--
ALTER TABLE `port_tax_config_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `port_tax_config_rate_history`
--
ALTER TABLE `port_tax_config_rate_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `port_tax_returns`
--
ALTER TABLE `port_tax_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `port_tax_services`
--
ALTER TABLE `port_tax_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regions_name_unique` (`name`);

--
-- Indexes for table `renew_tax_agent_requests`
--
ALTER TABLE `renew_tax_agent_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `roles_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `sys_modules`
--
ALTER TABLE `sys_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxpayers`
--
ALTER TABLE `taxpayers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `taxpayers_reference_no_unique` (`reference_no`),
  ADD UNIQUE KEY `taxpayers_email_unique` (`email`),
  ADD UNIQUE KEY `taxpayers_mobile_unique` (`mobile`),
  ADD KEY `taxpayers_id_type_foreign` (`id_type`),
  ADD KEY `taxpayers_country_id_foreign` (`country_id`);

--
-- Indexes for table `tax_agents`
--
ALTER TABLE `tax_agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_agent_academic_qualifications`
--
ALTER TABLE `tax_agent_academic_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_agent_academic_qualifications_tax_agent_id_foreign` (`tax_agent_id`);

--
-- Indexes for table `tax_agent_history`
--
ALTER TABLE `tax_agent_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_agent_professionals`
--
ALTER TABLE `tax_agent_professionals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_agent_professionals_tax_agent_id_foreign` (`tax_agent_id`);

--
-- Indexes for table `tax_agent_training_experiences`
--
ALTER TABLE `tax_agent_training_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_agent_training_experiences_tax_agent_id_foreign` (`tax_agent_id`);

--
-- Indexes for table `tax_types`
--
ALTER TABLE `tax_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ta_payment_configurations`
--
ALTER TABLE `ta_payment_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ta_payment_configuration_history`
--
ALTER TABLE `ta_payment_configuration_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `user_otps`
--
ALTER TABLE `user_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_categories`
--
ALTER TABLE `vat_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_config_rates`
--
ALTER TABLE `vat_config_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_config_rate_history`
--
ALTER TABLE `vat_config_rate_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_returns`
--
ALTER TABLE `vat_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_services`
--
ALTER TABLE `vat_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wards_district_id_foreign` (`district_id`);

--
-- Indexes for table `wa_responsible_persons`
--
ALTER TABLE `wa_responsible_persons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wa_responsible_persons_officer_id_foreign` (`officer_id`),
  ADD KEY `wa_responsible_persons_withholding_agent_id_foreign` (`withholding_agent_id`),
  ADD KEY `wa_responsible_persons_responsible_person_id_foreign` (`responsible_person_id`);

--
-- Indexes for table `withholding_agents`
--
ALTER TABLE `withholding_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withholding_agents_wa_number_unique` (`wa_number`),
  ADD UNIQUE KEY `withholding_agents_email_unique` (`email`),
  ADD UNIQUE KEY `withholding_agents_mobile_unique` (`mobile`),
  ADD KEY `withholding_agents_responsible_person_id_foreign` (`responsible_person_id`),
  ADD KEY `withholding_agents_ward_id_foreign` (`ward_id`),
  ADD KEY `withholding_agents_region_id_foreign` (`region_id`),
  ADD KEY `withholding_agents_district_id_foreign` (`district_id`),
  ADD KEY `withholding_agents_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workflow_tasks`
--
ALTER TABLE `workflow_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zm_bills`
--
ALTER TABLE `zm_bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zm_bill_items`
--
ALTER TABLE `zm_bill_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zm_payments`
--
ALTER TABLE `zm_payments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `biometrics`
--
ALTER TABLE `biometrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_activities`
--
ALTER TABLE `business_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_banks`
--
ALTER TABLE `business_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_categories`
--
ALTER TABLE `business_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `business_consultants`
--
ALTER TABLE `business_consultants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_deregistrations`
--
ALTER TABLE `business_deregistrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_hotels`
--
ALTER TABLE `business_hotels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_locations`
--
ALTER TABLE `business_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_owners`
--
ALTER TABLE `business_owners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_partners`
--
ALTER TABLE `business_partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_tax_type`
--
ALTER TABLE `business_tax_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `business_temp_closures`
--
ALTER TABLE `business_temp_closures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `business_turnovers`
--
ALTER TABLE `business_turnovers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exchange_rates`
--
ALTER TABLE `exchange_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_months`
--
ALTER TABLE `financial_months`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `financial_year`
--
ALTER TABLE `financial_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `id_types`
--
ALTER TABLE `id_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `isic1s`
--
ALTER TABLE `isic1s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `isic2s`
--
ALTER TABLE `isic2s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `isic3s`
--
ALTER TABLE `isic3s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `isic4s`
--
ALTER TABLE `isic4s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=420;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kycs`
--
ALTER TABLE `kycs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `port_tax_categories`
--
ALTER TABLE `port_tax_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `port_tax_config_rates`
--
ALTER TABLE `port_tax_config_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `port_tax_config_rate_history`
--
ALTER TABLE `port_tax_config_rate_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `port_tax_returns`
--
ALTER TABLE `port_tax_returns`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `port_tax_services`
--
ALTER TABLE `port_tax_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `renew_tax_agent_requests`
--
ALTER TABLE `renew_tax_agent_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sys_modules`
--
ALTER TABLE `sys_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taxpayers`
--
ALTER TABLE `taxpayers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_agents`
--
ALTER TABLE `tax_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tax_agent_academic_qualifications`
--
ALTER TABLE `tax_agent_academic_qualifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_agent_history`
--
ALTER TABLE `tax_agent_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_agent_professionals`
--
ALTER TABLE `tax_agent_professionals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_agent_training_experiences`
--
ALTER TABLE `tax_agent_training_experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tax_types`
--
ALTER TABLE `tax_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ta_payment_configurations`
--
ALTER TABLE `ta_payment_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ta_payment_configuration_history`
--
ALTER TABLE `ta_payment_configuration_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_otps`
--
ALTER TABLE `user_otps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vat_categories`
--
ALTER TABLE `vat_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `vat_config_rates`
--
ALTER TABLE `vat_config_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vat_config_rate_history`
--
ALTER TABLE `vat_config_rate_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vat_returns`
--
ALTER TABLE `vat_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vat_services`
--
ALTER TABLE `vat_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `wa_responsible_persons`
--
ALTER TABLE `wa_responsible_persons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withholding_agents`
--
ALTER TABLE `withholding_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workflows`
--
ALTER TABLE `workflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workflow_tasks`
--
ALTER TABLE `workflow_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zm_bills`
--
ALTER TABLE `zm_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zm_bill_items`
--
ALTER TABLE `zm_bill_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zm_payments`
--
ALTER TABLE `zm_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biometrics`
--
ALTER TABLE `biometrics`
  ADD CONSTRAINT `biometrics_taxpayer_id_foreign` FOREIGN KEY (`taxpayer_id`) REFERENCES `taxpayers` (`id`);

--
-- Constraints for table `business_deregistrations`
--
ALTER TABLE `business_deregistrations`
  ADD CONSTRAINT `business_deregistrations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `business_deregistrations_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`),
  ADD CONSTRAINT `business_deregistrations_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `business_owners`
--
ALTER TABLE `business_owners`
  ADD CONSTRAINT `business_owners_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`),
  ADD CONSTRAINT `business_owners_taxpayer_id_foreign` FOREIGN KEY (`taxpayer_id`) REFERENCES `taxpayers` (`id`);

--
-- Constraints for table `business_temp_closures`
--
ALTER TABLE `business_temp_closures`
  ADD CONSTRAINT `business_temp_closures_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `business_temp_closures_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`),
  ADD CONSTRAINT `business_temp_closures_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `business_turnovers`
--
ALTER TABLE `business_turnovers`
  ADD CONSTRAINT `business_turnovers_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`);

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);

--
-- Constraints for table `isic2s`
--
ALTER TABLE `isic2s`
  ADD CONSTRAINT `isic2s_isic1_id_foreign` FOREIGN KEY (`isic1_id`) REFERENCES `isic1s` (`id`);

--
-- Constraints for table `isic3s`
--
ALTER TABLE `isic3s`
  ADD CONSTRAINT `isic3s_isic2_id_foreign` FOREIGN KEY (`isic2_id`) REFERENCES `isic2s` (`id`);

--
-- Constraints for table `isic4s`
--
ALTER TABLE `isic4s`
  ADD CONSTRAINT `isic4s_isic3_id_foreign` FOREIGN KEY (`isic3_id`) REFERENCES `isic3s` (`id`);

--
-- Constraints for table `kycs`
--
ALTER TABLE `kycs`
  ADD CONSTRAINT `kycs_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `kycs_id_type_foreign` FOREIGN KEY (`id_type`) REFERENCES `id_types` (`id`);

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_sys_module_id_foreign` FOREIGN KEY (`sys_module_id`) REFERENCES `sys_modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD CONSTRAINT `roles_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taxpayers`
--
ALTER TABLE `taxpayers`
  ADD CONSTRAINT `taxpayers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `taxpayers_id_type_foreign` FOREIGN KEY (`id_type`) REFERENCES `id_types` (`id`);

--
-- Constraints for table `tax_agent_academic_qualifications`
--
ALTER TABLE `tax_agent_academic_qualifications`
  ADD CONSTRAINT `tax_agent_academic_qualifications_tax_agent_id_foreign` FOREIGN KEY (`tax_agent_id`) REFERENCES `tax_agents` (`id`);

--
-- Constraints for table `tax_agent_professionals`
--
ALTER TABLE `tax_agent_professionals`
  ADD CONSTRAINT `tax_agent_professionals_tax_agent_id_foreign` FOREIGN KEY (`tax_agent_id`) REFERENCES `tax_agents` (`id`);

--
-- Constraints for table `tax_agent_training_experiences`
--
ALTER TABLE `tax_agent_training_experiences`
  ADD CONSTRAINT `tax_agent_training_experiences_tax_agent_id_foreign` FOREIGN KEY (`tax_agent_id`) REFERENCES `tax_agents` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`);

--
-- Constraints for table `wa_responsible_persons`
--
ALTER TABLE `wa_responsible_persons`
  ADD CONSTRAINT `wa_responsible_persons_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wa_responsible_persons_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `taxpayers` (`id`),
  ADD CONSTRAINT `wa_responsible_persons_withholding_agent_id_foreign` FOREIGN KEY (`withholding_agent_id`) REFERENCES `withholding_agents` (`id`);

--
-- Constraints for table `withholding_agents`
--
ALTER TABLE `withholding_agents`
  ADD CONSTRAINT `withholding_agents_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `withholding_agents_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `withholding_agents_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`),
  ADD CONSTRAINT `withholding_agents_responsible_person_id_foreign` FOREIGN KEY (`responsible_person_id`) REFERENCES `taxpayers` (`id`),
  ADD CONSTRAINT `withholding_agents_ward_id_foreign` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
