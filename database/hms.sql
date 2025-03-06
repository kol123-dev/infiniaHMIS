-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2024 at 10:26 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountants`
--

CREATE TABLE `accountants` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `owner_id` int DEFAULT NULL,
  `owner_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_custom_fields`
--

CREATE TABLE `add_custom_fields` (
  `id` bigint UNSIGNED NOT NULL,
  `module_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  `values` text COLLATE utf8mb4_unicode_ci,
  `grid` int NOT NULL DEFAULT '12',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

CREATE TABLE `add_ons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `is_default` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `user_id`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '1', '2024-12-26 04:55:25', '2024-12-26 04:55:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `advanced_payments`
--

CREATE TABLE `advanced_payments` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `receipt_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ambulances`
--

CREATE TABLE `ambulances` (
  `id` int UNSIGNED NOT NULL,
  `vehicle_number` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_model` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_made` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_license` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver_contact` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `vehicle_type` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ambulance_calls`
--

CREATE TABLE `ambulance_calls` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `ambulance_id` int UNSIGNED NOT NULL,
  `driver_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `opd_date` datetime NOT NULL,
  `problem` text COLLATE utf8mb4_unicode_ci,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `custom_field` longtext COLLATE utf8mb4_unicode_ci,
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `payment_type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_transactions`
--

CREATE TABLE `appointment_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` int NOT NULL,
  `amount` double NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `id` int UNSIGNED NOT NULL,
  `bed_type` int UNSIGNED NOT NULL,
  `bed_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `charge` double NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed_assigns`
--

CREATE TABLE `bed_assigns` (
  `id` int UNSIGNED NOT NULL,
  `bed_id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED DEFAULT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `case_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assign_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed_types`
--

CREATE TABLE `bed_types` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int UNSIGNED NOT NULL,
  `bill_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `bill_date` datetime NOT NULL,
  `amount` decimal(16,2) DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `patient_admission_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` int UNSIGNED NOT NULL,
  `item_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bill_id` int UNSIGNED NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `price` double(8,2) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_transactions`
--

CREATE TABLE `bill_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` int NOT NULL COMMENT '1 Stripe, 2 Manual',
  `amount` double NOT NULL,
  `bill_id` int UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `is_manual_payment` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `birth_reports`
--

CREATE TABLE `birth_reports` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `case_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_banks`
--

CREATE TABLE `blood_banks` (
  `id` int UNSIGNED NOT NULL,
  `blood_group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remained_bags` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_donations`
--

CREATE TABLE `blood_donations` (
  `id` int UNSIGNED NOT NULL,
  `blood_donor_id` int UNSIGNED NOT NULL,
  `bags` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_donors`
--

CREATE TABLE `blood_donors` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `gender` int NOT NULL,
  `blood_group` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_donate_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blood_issues`
--

CREATE TABLE `blood_issues` (
  `id` bigint UNSIGNED NOT NULL,
  `issue_date` datetime NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `donor_id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `amount` double DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `call_type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_handlers`
--

CREATE TABLE `case_handlers` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

CREATE TABLE `charges` (
  `id` int UNSIGNED NOT NULL,
  `charge_type` int NOT NULL,
  `charge_category_id` int UNSIGNED NOT NULL,
  `code` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `standard_charge` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charge_categories`
--

CREATE TABLE `charge_categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `charge_type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency_settings`
--

CREATE TABLE `currency_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `currency_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_settings`
--

INSERT INTO `currency_settings` (`id`, `currency_name`, `currency_icon`, `currency_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'United states dollar', '$', 'USD', '2024-12-26 04:55:07', '2024-12-26 04:55:07', NULL),
(2, 'Indian rupee', '₹', 'INR', '2024-12-26 04:55:07', '2024-12-26 04:55:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `death_reports`
--

CREATE TABLE `death_reports` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `case_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `is_active`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(2, 'Doctor', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(3, 'Patient', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(4, 'Nurse', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(5, 'Receptionist', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(6, 'Pharmacist', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(7, 'Accountant', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(8, 'Case Manager', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24'),
(9, 'Lab Technician', 1, 'web', '2024-12-26 04:55:24', '2024-12-26 04:55:24');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis_categories`
--

CREATE TABLE `diagnosis_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `doctor_department_id` bigint UNSIGNED NOT NULL,
  `specialist` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `appointment_charge` double DEFAULT '0',
  `google_json_file_path` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_departments`
--

CREATE TABLE `doctor_departments` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_holidays`
--

CREATE TABLE `doctor_holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_opd_charges`
--

CREATE TABLE `doctor_opd_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `standard_charge` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `uploaded_by` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_payrolls`
--

CREATE TABLE `employee_payrolls` (
  `id` int UNSIGNED NOT NULL,
  `sr_no` bigint NOT NULL,
  `payroll_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL,
  `owner_id` int NOT NULL,
  `owner_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `net_salary` double NOT NULL,
  `status` int NOT NULL,
  `basic_salary` double NOT NULL,
  `allowance` double NOT NULL,
  `deductions` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int UNSIGNED NOT NULL,
  `full_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `viewed_by` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_google_calendars`
--

CREATE TABLE `event_google_calendars` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `google_calendar_list_id` bigint UNSIGNED NOT NULL,
  `google_calendar_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `expense_head` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `amount` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `front_services`
--

CREATE TABLE `front_services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_services`
--

INSERT INTO `front_services` (`id`, `name`, `short_description`, `created_at`, `updated_at`) VALUES
(1, 'Cardiology', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(2, 'Orthopedics', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(3, 'Pulmonology', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(4, 'Dental Care', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(5, 'Medicine', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(6, 'Ambulance', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(7, 'Ophthalmology', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(8, 'Neurology', 'image Cardiology Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor.', '2024-12-26 04:55:26', '2024-12-26 04:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `front_settings`
--

CREATE TABLE `front_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `front_settings`
--

INSERT INTO `front_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'about_us_title', 'About For HMS', '1', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(2, 'about_us_description', 'HMS will teach physicians and nurses from around the world the principles of blood management, as well as how to manage their own blood conservation programs. The hospital was chosen based on the reputation its bloodless program has established in the medical community and because of its nationally recognized results.\n\nWe are a group of creative nerds making awesome stuff for Web and Mobile. We just love to contribute to open source technologies. We always try to build something which helps developers to save their time. so they can spend a bit more time with their friends And family.', '1', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(3, 'about_us_mission', 'We are providing advanced emergency services. We have well-equipped emergency and trauma center with facilities.', '1', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(4, 'about_us_image', 'http://e-hms.test/assets/img/default_image.jpg', '1', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(5, 'home_page_experience', '10', '2', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(6, 'home_page_title', 'Find Local Specialists Best Services', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(7, 'home_page_description', 'Our medical clinic provides quality care for the entire family while maintaining a personable atmosphere best services.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(8, 'home_page_image', 'web_front/images/doctors/doctor.png', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(9, 'terms_conditions', 'terms_conditions', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(10, 'privacy_policy', 'privacy_policy', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(11, 'home_page_certified_doctor_image', 'web_front/images/healthcare-doctor/doctor-1.png', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(12, 'home_page_certified_doctor_text', 'Quality Healthcare', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(13, 'home_page_certified_doctor_title', 'Have Certified and High Quality Doctor For You', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(14, 'home_page_certified_doctor_description', 'Our medical clinic provides quality care for the entire family while maintaining a personable atmosphere best services. Our medical clinic provides quality. Our medical clinic provides quality care for the entire family while maintaining lizam a personable atmosphere best services. Our medical clinic provides.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(15, 'home_page_box_title', 'Free Consulting', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(16, 'home_page_box_description', 'Proin gravida nibh vel velit auctor aliquet.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(17, 'home_page_step_1_title', 'Check Doctor Profile', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(18, 'home_page_step_1_description', 'Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin lorem quis bibendum auctor nisi elit.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(19, 'home_page_step_2_title', 'Request Consulting', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(20, 'home_page_step_2_description', 'Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin lorem quis bibendum auctor nisi elit.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(21, 'home_page_step_3_title', 'Receive Consulting', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(22, 'home_page_step_3_description', 'Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin lorem quis bibendum auctor nisi elit.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(23, 'home_page_step_4_title', 'Get Your Solution', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(24, 'home_page_step_4_description', 'Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin lorem quis bibendum auctor nisi elit.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(25, 'home_page_certified_box_title', 'Certified Doctor', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(26, 'home_page_certified_box_description', 'Proin gravida nibh vel velit auctor aliquet.', '2', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(27, 'appointment_title', 'Contact Now and Get the Best Doctor Service Today', '3', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(28, 'appointment_description', 'Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin lorem quis bibendum auctor nisi elit consequat ipsum nec sagittis.', '3', '2024-12-26 04:55:26', '2024-12-26 04:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `google_calendar_integrations`
--

CREATE TABLE `google_calendar_integrations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_used_at` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_calendar_lists`
--

CREATE TABLE `google_calendar_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `calendar_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_calendar_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospital_schedules`
--

CREATE TABLE `hospital_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `day_of_week` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint UNSIGNED NOT NULL,
  `income_head` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `amount` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurances`
--

CREATE TABLE `insurances` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_tax` double NOT NULL,
  `discount` double DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `insurance_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurance_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hospital_rate` double NOT NULL,
  `total` double NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insurance_diseases`
--

CREATE TABLE `insurance_diseases` (
  `id` bigint UNSIGNED NOT NULL,
  `insurance_id` int UNSIGNED NOT NULL,
  `disease_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disease_charge` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investigation_reports`
--

CREATE TABLE `investigation_reports` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int UNSIGNED NOT NULL,
  `invoice_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `invoice_date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT '0.00',
  `discount` double(8,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int UNSIGNED NOT NULL,
  `account_id` int UNSIGNED NOT NULL,
  `invoice_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `total` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_bills`
--

CREATE TABLE `ipd_bills` (
  `id` bigint UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `total_charges` double NOT NULL,
  `total_payments` double NOT NULL,
  `gross_total` double NOT NULL,
  `discount_in_percentage` int NOT NULL,
  `tax_in_percentage` int NOT NULL,
  `other_charges` double NOT NULL,
  `net_payable_amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_charges`
--

CREATE TABLE `ipd_charges` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `charge_type_id` int NOT NULL,
  `charge_category_id` int UNSIGNED NOT NULL,
  `charge_id` int UNSIGNED NOT NULL,
  `standard_charge` double DEFAULT NULL,
  `applied_charge` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_consultant_registers`
--

CREATE TABLE `ipd_consultant_registers` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `applied_date` datetime NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_diagnoses`
--

CREATE TABLE `ipd_diagnoses` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `report_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_operation`
--

CREATE TABLE `ipd_operation` (
  `id` bigint UNSIGNED NOT NULL,
  `ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_category_id` bigint UNSIGNED NOT NULL,
  `operation_id` bigint UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `operation_date` date NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `assistant_consultant_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assistant_consultant_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anesthetist` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `anesthesia_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ot_technician` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ot_assistant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_patient_departments`
--

CREATE TABLE `ipd_patient_departments` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `ipd_number` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symptoms` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `admission_date` datetime NOT NULL,
  `discharge` tinyint(1) NOT NULL DEFAULT '0',
  `case_id` int UNSIGNED DEFAULT NULL,
  `is_old_patient` tinyint(1) DEFAULT '0',
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `bed_type_id` int UNSIGNED DEFAULT NULL,
  `bed_id` int UNSIGNED NOT NULL,
  `custom_field` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bill_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_payments`
--

CREATE TABLE `ipd_payments` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  `payment_mode` tinyint NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `transaction_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_prescriptions`
--

CREATE TABLE `ipd_prescriptions` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `header_note` text COLLATE utf8mb4_unicode_ci,
  `footer_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_prescription_items`
--

CREATE TABLE `ipd_prescription_items` (
  `id` int UNSIGNED NOT NULL,
  `ipd_prescription_id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `medicine_id` int UNSIGNED NOT NULL,
  `dosage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dose_interval` int NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipd_timelines`
--

CREATE TABLE `ipd_timelines` (
  `id` int UNSIGNED NOT NULL,
  `ipd_patient_department_id` int UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `visible_to_person` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issued_items`
--

CREATE TABLE `issued_items` (
  `id` int UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `issued_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issued_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `item_category_id` int UNSIGNED NOT NULL,
  `item_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_category_id` int UNSIGNED NOT NULL,
  `unit` bigint NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `available_quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_stocks`
--

CREATE TABLE `item_stocks` (
  `id` int UNSIGNED NOT NULL,
  `item_category_id` int UNSIGNED NOT NULL,
  `item_id` int UNSIGNED NOT NULL,
  `supplier_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `purchase_price` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_technicians`
--

CREATE TABLE `lab_technicians` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_consultations`
--

CREATE TABLE `live_consultations` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `consultation_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consultation_date` datetime NOT NULL,
  `host_video` tinyint(1) NOT NULL,
  `participant_video` tinyint(1) NOT NULL,
  `consultation_duration_minutes` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meeting_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_meetings`
--

CREATE TABLE `live_meetings` (
  `id` bigint UNSIGNED NOT NULL,
  `consultation_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consultation_date` datetime NOT NULL,
  `consultation_duration_minutes` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host_video` tinyint(1) NOT NULL,
  `participant_video` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `meeting_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_zone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_meetings_candidates`
--

CREATE TABLE `live_meetings_candidates` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `live_meeting_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lunch_breaks`
--

CREATE TABLE `lunch_breaks` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `break_from` time NOT NULL,
  `break_to` time NOT NULL,
  `every_day` tinyint(1) DEFAULT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE `mails` (
  `id` bigint UNSIGNED NOT NULL,
  `to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachments` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `collection_name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_properties` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsive_images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `conversions_disk` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generated_conversions` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `brand_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selling_price` double NOT NULL,
  `buying_price` double NOT NULL,
  `quantity` int NOT NULL,
  `available_quantity` int NOT NULL,
  `salt_composition` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `side_effects` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_bills`
--

CREATE TABLE `medicine_bills` (
  `id` bigint UNSIGNED NOT NULL,
  `bill_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `doctor_id` int UNSIGNED DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` double(25,2) NOT NULL,
  `net_amount` double(25,2) NOT NULL,
  `total` double(25,2) NOT NULL,
  `tax_amount` double(25,2) NOT NULL,
  `payment_status` int NOT NULL,
  `payment_type` int NOT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_05_03_000001_create_customer_columns', 1),
(4, '2019_05_03_000002_create_subscriptions_table', 1),
(5, '2019_05_03_000003_create_subscription_items_table', 1),
(6, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2020_02_06_031618_create_categories_table', 1),
(9, '2020_02_12_053840_create_doctor_departments_table', 1),
(10, '2020_02_12_053932_create_departments_table', 1),
(11, '2020_02_13_042835_create_brands_table', 1),
(12, '2020_02_13_053840_create_doctors_table', 1),
(13, '2020_02_13_054103_create_patients_table', 1),
(14, '2020_02_13_094724_create_bills_table', 1),
(15, '2020_02_13_095024_create_medicines_table', 1),
(16, '2020_02_13_095125_create_bill_items_table', 1),
(17, '2020_02_13_111857_create_nurses_table', 1),
(18, '2020_02_13_125601_create_addresses_table', 1),
(19, '2020_02_13_141104_create_media_table', 1),
(20, '2020_02_14_051650_create_lab_technicians_table', 1),
(21, '2020_02_14_055353_create_appointments_table', 1),
(22, '2020_02_14_091441_create_receptionists_table', 1),
(23, '2020_02_14_093246_create_pharmacists_table', 1),
(24, '2020_02_17_053450_create_accountants_table', 1),
(25, '2020_02_17_080856_create_bed_types_table', 1),
(26, '2020_02_17_092326_create_blood_banks_table', 1),
(27, '2020_02_17_105627_create_beds_table', 1),
(28, '2020_02_17_110620_create_blood_donors_table', 1),
(29, '2020_02_17_135716_create_permission_tables', 1),
(30, '2020_02_18_042327_create_notice_boards_table', 1),
(31, '2020_02_18_042442_create_document_types_table', 1),
(32, '2020_02_18_060222_create_patient_cases_table', 1),
(33, '2020_02_18_060223_create_operation_reports_table', 1),
(34, '2020_02_18_064953_create_bed_assigns_table', 1),
(35, '2020_02_18_092202_create_documents_table', 1),
(36, '2020_02_18_094758_create_birth_reports_table', 1),
(37, '2020_02_18_111020_create_death_reports_table', 1),
(38, '2020_02_19_080336_create_employee_payrolls_table', 1),
(39, '2020_02_19_134502_create_settings_table', 1),
(40, '2020_02_21_090236_create_investigation_reports_table', 1),
(41, '2020_02_21_095439_create_accounts_table', 1),
(42, '2020_02_22_070658_create_payments_table', 1),
(43, '2020_02_22_090112_create_insurances_table', 1),
(44, '2020_02_22_091537_create_insurance_disease_table', 1),
(45, '2020_02_24_055136_create_invoices_table', 1),
(46, '2020_02_24_055518_create_schedules_table', 1),
(47, '2020_02_24_055702_create_invoice_items_table', 1),
(48, '2020_02_25_105042_create_services_table', 1),
(49, '2020_02_25_131030_create_packages_table', 1),
(50, '2020_02_25_131108_create_package_services_table', 1),
(51, '2020_02_27_120948_create_patient_admissions_table', 1),
(52, '2020_02_28_031410_create_case_handlers_table', 1),
(53, '2020_03_02_043813_create_advanced_payments_table', 1),
(54, '2020_03_02_065845_add_patient_admission_id_to_bills', 1),
(55, '2020_03_03_062243_add_patient_id_to_bills', 1),
(56, '2020_03_03_113334_create_schedule_day_table', 1),
(57, '2020_03_26_052336_create_ambulances_table', 1),
(58, '2020_03_26_081157_create_mails_table', 1),
(59, '2020_03_27_061641_create_enquiries_table', 1),
(60, '2020_03_27_063148_create_ambulance_calls_table', 1),
(61, '2020_03_31_122219_create_prescriptions_table', 1),
(62, '2020_04_11_052629_create_charge_categories_table', 1),
(63, '2020_04_11_053929_create_pathology_categories_table', 1),
(64, '2020_04_11_070859_create_radiology_categories_table', 1),
(65, '2020_04_11_090903_create_charges_table', 1),
(66, '2020_04_13_050643_create_radiology_tests_table', 1),
(67, '2020_04_14_093339_create_pathology_tests_table', 1),
(68, '2020_04_24_111205_create_doctor_opd_charge_table', 1),
(69, '2020_04_28_094118_create_expenses_table', 1),
(70, '2020_05_01_055137_create_incomes_table', 1),
(71, '2020_05_11_083050_add_notes_documents_table', 1),
(72, '2020_05_12_075825_create_sms_table', 1),
(73, '2020_06_22_071531_add_index_to_accounts_table', 1),
(74, '2020_06_22_071943_add_index_to_doctor_opd_charges_table', 1),
(75, '2020_06_22_072921_add_index_to_bed_assigns_table', 1),
(76, '2020_06_22_073042_add_index_to_medicines_table', 1),
(77, '2020_06_22_073457_add_index_to_employee_payrolls_table', 1),
(78, '2020_06_22_074937_add_index_to_notice_boards_table', 1),
(79, '2020_06_22_075222_add_index_to_blood_donors_table', 1),
(80, '2020_06_22_075359_add_index_to_packages_table', 1),
(81, '2020_06_22_075506_add_index_to_bed_types_table', 1),
(82, '2020_06_22_075725_add_index_to_services_table', 1),
(83, '2020_06_22_080944_add_index_to_invoices_table', 1),
(84, '2020_06_22_081601_add_index_to_payments_table', 1),
(85, '2020_06_22_081802_add_index_to_advanced_payments_table', 1),
(86, '2020_06_22_081909_add_index_to_bills_table', 1),
(87, '2020_06_22_082548_add_index_to_beds_table', 1),
(88, '2020_06_22_082942_add_index_to_blood_banks_table', 1),
(89, '2020_06_22_083511_add_index_to_users_table', 1),
(90, '2020_06_22_084750_add_index_to_patient_cases_table', 1),
(91, '2020_06_22_084912_add_index_to_patient_admissions_table', 1),
(92, '2020_06_22_085036_add_index_to_document_types_table', 1),
(93, '2020_06_22_085128_add_index_to_insurances_table', 1),
(94, '2020_06_22_085317_add_index_to_ambulances_table', 1),
(95, '2020_06_22_090509_add_index_to_ambulance_calls_table', 1),
(96, '2020_06_22_091253_add_index_to_doctor_departments_table', 1),
(97, '2020_06_22_091455_add_index_to_appointments_table', 1),
(98, '2020_06_22_091617_add_index_to_birth_reports_table', 1),
(99, '2020_06_22_091632_add_index_to_death_reports_table', 1),
(100, '2020_06_22_091651_add_index_to_investigation_reports_table', 1),
(101, '2020_06_22_091828_add_index_to_operation_reports_table', 1),
(102, '2020_06_22_092018_add_index_to_categories_table', 1),
(103, '2020_06_22_092149_add_index_to_brands_table', 1),
(104, '2020_06_22_092324_add_index_to_pathology_tests_table', 1),
(105, '2020_06_22_092338_add_index_to_pathology_categories_table', 1),
(106, '2020_06_22_092347_add_index_to_radiology_tests_table', 1),
(107, '2020_06_22_092357_add_index_to_radiology_categories_table', 1),
(108, '2020_06_22_092651_add_index_to_expenses_table', 1),
(109, '2020_06_22_092702_add_index_to_incomes_table', 1),
(110, '2020_06_22_092855_add_index_to_charges_table', 1),
(111, '2020_06_22_092905_add_index_to_charge_categories_table', 1),
(112, '2020_06_22_093234_add_index_to_enquiries_table', 1),
(113, '2020_06_24_044648_create_diagnosis_categories_table', 1),
(114, '2020_06_25_080242_create_patient_diagnosis_tests_table', 1),
(115, '2020_06_26_054352_create_patient_diagnosis_properties_table', 1),
(116, '2020_07_15_044653_remove_serial_visibility_from_schedules_table', 1),
(117, '2020_07_15_121336_change_ambulances_table_column', 1),
(118, '2020_07_22_052934_change_bed_assigns_table_column', 1),
(119, '2020_07_29_095430_change_invoice_items_table_column', 1),
(120, '2020_08_26_081235_create_item_categories_table', 1),
(121, '2020_08_26_101134_create_items_table', 1),
(122, '2020_08_26_125032_create_item_stocks_table', 1),
(123, '2020_08_27_141547_create_issued_items_table', 1),
(124, '2020_09_08_064222_create_ipd_patient_departments_table', 1),
(125, '2020_09_08_114627_create_ipd_diagnoses_table', 1),
(126, '2020_09_09_065624_create_ipd_consultant_registers_table', 1),
(127, '2020_09_09_135505_create_ipd_charges_table', 1),
(128, '2020_09_10_112306_create_ipd_prescriptions_table', 1),
(129, '2020_09_10_114203_create_ipd_prescription_items_table', 1),
(130, '2020_09_11_045308_create_modules_table', 1),
(131, '2020_09_12_050715_create_ipd_payments_table', 1),
(132, '2020_09_12_071821_create_ipd_timelines_table', 1),
(133, '2020_09_12_103003_create_ipd_bills_table', 1),
(134, '2020_09_14_083759_create_opd_patient_departments_table', 1),
(135, '2020_09_14_144731_add_ipd_patient_department_id_to_bed_assigns_table', 1),
(136, '2020_09_15_064044_create_transactions_table', 1),
(137, '2020_09_16_103204_create_opd_diagnoses_table', 1),
(138, '2020_09_16_114031_create_opd_timelines_table', 1),
(139, '2020_09_23_045100_change_patient_diagnosis_properties_table', 1),
(140, '2020_09_24_053229_add_ipd_bill_column_in_ipd_patient_departments_table', 1),
(141, '2020_10_09_085838_create_call_logs_table', 1),
(142, '2020_10_12_125133_create_visitors_table', 1),
(143, '2020_10_14_044134_create_postals_table', 1),
(144, '2020_10_30_043500_add_route_in_modules_table', 1),
(145, '2020_10_31_062448_add_complete_in_appointments_table', 1),
(146, '2020_11_02_050736_create_testimonials_table', 1),
(147, '2020_11_07_121633_add_region_code_in_sms_table', 1),
(148, '2020_11_19_093810_create_blood_donations_table', 1),
(149, '2020_11_20_113830_create_blood_issues_table', 1),
(150, '2020_11_24_131253_create_notifications_table', 1),
(151, '2020_12_28_131351_create_live_consultations_table', 1),
(152, '2020_12_31_062506_create_live_meetings_table', 1),
(153, '2020_12_31_091242_create_live_meetings_candidates_table', 1),
(154, '2021_01_05_100425_create_user_zoom_credential_table', 1),
(155, '2021_01_06_105407_add_metting_id_to_live_meetings_table', 1),
(156, '2021_02_23_065200_create_vaccinations_table', 1),
(157, '2021_02_23_065252_create_vaccinated_patients_table', 1),
(158, '2021_04_05_085646_create_front_settings_table', 1),
(159, '2021_05_10_000000_add_uuid_to_failed_jobs_table', 1),
(160, '2021_05_29_103036_add_conversions_disk_column_in_media_table', 1),
(161, '2021_06_07_104022_change_patient_foreign_key_type_in_appointments_table', 1),
(162, '2021_06_08_073918_change_department_foreign_key_in_appointments_table', 1),
(163, '2021_06_21_082754_update_amount_datatype_in_bills_table', 1),
(164, '2021_06_21_082845_update_amount_datatype_in_bill_items_table', 1),
(165, '2021_11_11_061443_create_front_services_table', 1),
(166, '2021_11_12_100750_create_hospital_schedules_table', 1),
(167, '2021_11_12_105805_add_social_details_in_users_table', 1),
(168, '2022_02_18_101938_add_darkmode_to_users_table', 1),
(169, '2022_04_09_064645_change_doctor_foreign_in_operation_reports_table', 1),
(170, '2022_05_16_104947_add_default_length_in_table', 1),
(171, '2022_07_29_200345_add_prescription_fields', 1),
(172, '2022_08_01_204917_create_prescriptions_medicines_table', 1),
(173, '2022_08_26_225704_change_charges_standard_charge_column', 1),
(174, '2022_08_30_011825_change_item_unit_column', 1),
(175, '2022_09_06_202047_change_amount_at_blood_issue', 1),
(176, '2022_09_07_184901_change_dose_number_column', 1),
(177, '2022_09_08_065652_add_country_code_field_in_settings', 1),
(178, '2022_09_08_201840_defalut_new_module_seeder', 1),
(179, '2022_09_26_214705_create_admins_table', 1),
(180, '2022_09_30_205212_create_currency_settings_table', 1),
(181, '2022_10_06_165905_create_admin_module_seeder_migration', 1),
(182, '2022_10_07_204913_create_default_currency_seeder_migration', 1),
(183, '2022_10_11_183203_create_change_field_type_employee_payroll', 1),
(184, '2022_11_02_163443_add_currency_field', 1),
(185, '2022_11_04_161324_add_default_column_at_admin', 1),
(186, '2022_11_12_014432_change_length_of_password_reset_table', 1),
(187, '2022_11_30_125757_change_net_salary_datatype_employee_payroll', 1),
(188, '2022_12_10_064724_add_column_at_personal_access_token', 1),
(189, '2022_12_23_064152_add_key_at_setting_table', 1),
(190, '2023_01_12_112215_change_case_id_in_ipd_patient_department_table', 1),
(191, '2023_01_18_100008_create_operation_categories_table', 1),
(192, '2023_01_18_130141_add_operation_category_in_modules', 1),
(193, '2023_01_20_041105_create_operations_table', 1),
(194, '2023_01_20_091507_create_operation_seeder_run_migration', 1),
(195, '2023_01_24_084500_create_ipd_operation_table', 1),
(196, '2023_01_26_111156_change_ipd_operation_table', 1),
(197, '2023_01_27_113441_add_reference_no_field_at_ipd_operation_table', 1),
(198, '2023_06_30_115440_create_used_medicines_table', 1),
(199, '2023_06_30_115536_add_quantity_field_in_medicines_table', 1),
(200, '2023_06_30_120038_create_purchase_medicines_table', 1),
(201, '2023_06_30_120054_create_purchased_medicines_table', 1),
(202, '2023_07_06_095020_add_dose_interval_field_in_prescriptions_medicines_table', 1),
(203, '2023_07_06_130647_add_dose_interval_field_in_ipd_prescription_items_table', 1),
(204, '2023_07_07_053436_create_medicine_bills_table', 1),
(205, '2023_07_07_053704_create_sale_medicines_table', 1),
(206, '2023_07_11_070125_add_time_field_in_ipd_prescription_items_table', 1),
(207, '2023_08_23_084806_create_zoom_o_auth_credentials_table', 1),
(208, '2023_08_29_120924_add_charge_id_field_to_radiology_tests_table', 1),
(209, '2023_09_20_000000_rename_password_resets_table', 1),
(210, '2023_10_05_043821_create_pathology_units_table', 1),
(211, '2023_10_05_053003_create_pathology_parameters_table', 1),
(212, '2023_10_05_063557_create_pathology_parameter_items_table', 1),
(213, '2023_10_06_111045_create_doctor_holidays_table', 1),
(214, '2023_10_09_092910_create_lunch_breaks_table', 1),
(215, '2023_10_20_104235_create_default_pathology_module', 1),
(216, '2023_10_30_044424_create_patient_id_card_templates_table', 1),
(217, '2023_10_30_050649_add_description_to_doctors_table', 1),
(218, '2023_10_30_053356_add_template_id_field_to_patients_table', 1),
(219, '2023_10_31_040501_add_patient_unique_id_field_in_patients_table', 1),
(220, '2023_11_03_104055_create_default_language_seeder_migration', 1),
(221, '2023_11_09_095046_create_run_default_patient_unique_id_seeder_table', 1),
(222, '2023_12_01_044623_add_status_column_in_bills_table', 1),
(223, '2023_12_01_045242_create_bill_transactions_table', 1),
(224, '2023_12_01_112531_add_payment_mode_column', 1),
(225, '2023_12_04_084628_add_discharge_filed_ipd_patient_departments_to_table', 1),
(226, '2023_12_19_071947_create_add_custom_fields_table', 1),
(227, '2023_12_21_061835_add_custom_field_to_appointments_table', 1),
(228, '2023_12_21_105145_add_custom_field_to_ipd_patient_departments_table', 1),
(229, '2023_12_21_114522_add_custom_field_to_opd_patient_departments_table', 1),
(230, '2023_12_21_121555_add_custom_field_to_patients_table', 1),
(231, '2023_12_26_094330_create_ipd_patient_department_seeder_table', 1),
(232, '2024_01_16_094958_add_ipd_patient_department_bed_assign_seeder_table', 1),
(233, '2024_01_16_103253_create_ipd_patient_department_bed_assign_seeder_table', 1),
(234, '2024_01_26_062011_add_appointment_charge_at_doctors_table', 1),
(235, '2024_01_26_094657_create_appointment_transactions_table', 1),
(236, '2024_01_26_113651_add_payment_status_at_appointments_table', 1),
(237, '2024_02_02_063538_create_setting_table_payement_field_sedder_table', 1),
(238, '2024_02_02_111802_create_setting_table_paystack_field_seeder_table', 1),
(239, '2024_02_17_112301_create_google_calendar_integrations_table', 1),
(240, '2024_02_17_112840_create_google_calendar_lists_table', 1),
(241, '2024_02_17_113008_create_event_google_calendars_table', 1),
(242, '2024_02_17_113110_add_column_in_live_consultations_table', 1),
(243, '2024_02_17_113257_create_user_google_event_schedules_table', 1),
(244, '2024_02_23_043211_create_phone_pay_seeder_table', 1),
(245, '2024_02_26_105319_add_patient_id_to_pathology_tests_table', 1),
(246, '2024_02_28_095146_create_setting_table_payment_getaway_field_seeder_table', 1),
(247, '2024_03_01_115646_create_opd_prescriptions_table', 1),
(248, '2024_03_04_070757_create_opd_prescription_items_table', 1),
(249, '2024_03_16_072822_add_payment_status_field_in_purchase_medicines_table', 1),
(250, '2024_05_01_045327_change_token_field_in_password_reset_tokens_table', 1),
(251, '2024_05_14_084350_add_google_json_file_path_field_in_doctor_table', 1),
(252, '2024_05_15_113139_change_custom_field_to_patients_table', 1),
(253, '2024_05_15_113152_change_custom_field_to_appointments_table', 1),
(254, '2024_05_15_113203_change_custom_field_to_ipd_patient_departments_table', 1),
(255, '2024_05_15_113219_change_custom_field_to_opd_patient_departments_table', 1),
(256, '2024_05_20_072708_change_expiry_date_field_type_in_sale_medicines_table', 1),
(257, '2024_05_28_035934_change_amount_table_column', 1),
(258, '2024_05_28_044844_change_price_table_column', 1),
(259, '2024_05_28_085124_change_net_salary_table_column', 1),
(260, '2024_05_28_094532_change_charge_column_to_beds_table', 1),
(261, '2024_05_28_094617_change_amount_column_to_blood_issues_table', 1),
(262, '2024_05_28_095246_change_standard_charge_column_to_charges_table', 1),
(263, '2024_05_28_095826_change_standard_charge_column_to_radiology_tests_table', 1),
(264, '2024_05_28_100518_change_standard_charge_column_to_pathology_tests_table', 1),
(265, '2024_05_30_035823_change_amount_column_to_ipd_charges_table', 1),
(266, '2024_05_30_035950_change_amount_column_to_ipd_payments_table', 1),
(267, '2024_05_30_041541_change_charges_amount_column_to_ipd_bills_table', 1),
(268, '2024_06_04_072039_change_rate_field_datatype_in_services_table', 1),
(269, '2024_06_25_084154_add_open_ai_key_field_in_settings_table', 1),
(270, '2024_07_22_054344_change_appointment_charge_field_datatype_in_doctors_table', 1),
(271, '2024_09_02_093150_default_pyament_type_seeder', 1),
(272, '2024_11_11_062027_create_add_ons_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `route` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `is_active`, `route`, `created_at`, `updated_at`) VALUES
(1, 'Employee Bills', 1, 'employee.bills.index', '2024-12-26 04:55:06', '2024-12-26 04:55:06'),
(2, 'Employee Bills Show', 1, 'employee.bills.show', '2024-12-26 04:55:06', '2024-12-26 04:55:06'),
(3, 'Employee Noticeboard', 1, 'employee.noticeboard', '2024-12-26 04:55:06', '2024-12-26 04:55:06'),
(4, 'Employee Patient Diagnosis Test Pdf', 1, 'employee.patient.diagnosis.test.pdf', '2024-12-26 04:55:06', '2024-12-26 04:55:06'),
(5, 'Admin', 1, 'admins.index', '2024-12-26 04:55:07', '2024-12-26 04:55:07'),
(6, 'Operation Categories', 1, 'operation.category.index', '2024-12-26 04:55:09', '2024-12-26 04:55:09'),
(7, 'Operation', 1, 'operations.index', '2024-12-26 04:55:09', '2024-12-26 04:55:09'),
(8, 'Pathology Units', 1, 'pathology.unit.index', '2024-12-26 04:55:11', '2024-12-26 04:55:11'),
(9, 'Pathology Parameters', 1, 'pathology.parameter.index', '2024-12-26 04:55:11', '2024-12-26 04:55:11'),
(10, 'Patients', 1, 'patients.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(11, 'Doctors', 1, 'doctors.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(12, 'Accountants', 1, 'accountants.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(13, 'Medicines', 1, 'medicines.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(14, 'Nurses', 1, 'nurses.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(15, 'Receptionists', 1, 'receptionists.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(16, 'Lab Technicians', 1, 'lab-technicians.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(17, 'Pharmacists', 1, 'pharmacists.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(18, 'Birth Reports', 1, 'birth-reports.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(19, 'Death Reports', 1, 'death-reports.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(20, 'Investigation Reports', 1, 'investigation-reports.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(21, 'Operation Reports', 1, 'operation-reports.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(22, 'Income', 1, 'incomes.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(23, 'Expense', 1, 'expenses.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(24, 'SMS', 1, 'sms.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(25, 'IPD Patients', 1, 'ipd.patient.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(26, 'OPD Patients', 1, 'opd.patient.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(27, 'Accounts', 1, 'accounts.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(28, 'Employee Payrolls', 1, 'employee-payrolls.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(29, 'Invoices', 1, 'invoices.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(30, 'Payments', 1, 'payments.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(31, 'Payment Reports', 1, 'payment.reports', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(32, 'Advance Payments', 1, 'advanced-payments.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(33, 'Bills', 1, 'bills.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(34, 'Bed Types', 1, 'bed-types.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(35, 'Beds', 1, 'beds.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(36, 'Bed Assigns', 1, 'bed-assigns.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(37, 'Blood Banks', 1, 'blood-banks.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(38, 'Blood Donors', 1, 'blood-donors.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(39, 'Documents', 1, 'documents.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(40, 'Document Types', 1, 'document-types.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(41, 'Services', 1, 'services.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(42, 'Insurances', 1, 'insurances.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(43, 'Packages', 1, 'packages.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(44, 'Ambulances', 1, 'ambulances.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(45, 'Ambulances Calls', 1, 'ambulance-calls.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(46, 'Appointments', 1, 'appointments.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(47, 'Call Logs', 1, 'call_logs.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(48, 'Visitors', 1, 'visitors.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(49, 'Postal Receive', 1, 'receives.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(50, 'Postal Dispatch', 1, 'dispatches.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(51, 'Notice Boards', 1, 'noticeboard', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(52, 'Mail', 1, 'mail', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(53, 'Enquires', 1, 'enquiries', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(54, 'Charge Categories', 1, 'charge-categories.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(55, 'Charges', 1, 'charges.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(56, 'Doctor OPD Charges', 1, 'doctor-opd-charges.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(57, 'Items Categories', 1, 'item-categories.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(58, 'Items', 1, 'items.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(59, 'Item Stocks', 1, 'item.stock.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(60, 'Issued Items', 1, 'issued.item.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(61, 'Diagnosis Categories', 1, 'diagnosis.category.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(62, 'Diagnosis Tests', 1, 'patient.diagnosis.test.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(63, 'Pathology Categories', 1, 'pathology.category.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(64, 'Pathology Tests', 1, 'pathology.test.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(65, 'Radiology Categories', 1, 'radiology.category.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(66, 'Radiology Tests', 1, 'radiology.test.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(67, 'Medicine Categories', 1, 'categories.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(68, 'Medicine Brands', 1, 'brands.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(69, 'Doctor Departments', 1, 'doctor-departments.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(70, 'Schedules', 1, 'schedules.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(71, 'Prescriptions', 1, 'prescriptions.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(72, 'Cases', 1, 'patient-cases.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(73, 'Case Handlers', 1, 'case-handlers.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(74, 'Patient Admissions', 1, 'patient-admissions.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(75, 'My Payrolls', 1, 'payroll', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(76, 'Patient Cases', 1, 'patients.cases', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(77, 'Testimonial', 1, 'testimonials.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(78, 'Blood Donations', 1, 'blood-donations.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(79, 'Blood Issues', 1, 'blood-issues.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(80, 'Live Consultations', 1, 'live.consultation.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(81, 'Live Meetings', 1, 'live.meeting.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(82, 'Vaccinations', 1, 'vaccinations.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(83, 'Vaccinated Patients', 1, 'vaccinated-patients.index', '2024-12-26 04:55:25', '2024-12-26 04:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `notice_boards`
--

CREATE TABLE `notice_boards` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `type` int NOT NULL,
  `notification_for` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nurses`
--

CREATE TABLE `nurses` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_diagnoses`
--

CREATE TABLE `opd_diagnoses` (
  `id` int UNSIGNED NOT NULL,
  `opd_patient_department_id` int UNSIGNED NOT NULL,
  `report_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_patient_departments`
--

CREATE TABLE `opd_patient_departments` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `opd_number` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symptoms` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `appointment_date` datetime NOT NULL,
  `case_id` int UNSIGNED DEFAULT NULL,
  `is_old_patient` tinyint(1) DEFAULT '0',
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `standard_charge` double NOT NULL,
  `payment_mode` tinyint NOT NULL,
  `custom_field` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_prescriptions`
--

CREATE TABLE `opd_prescriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `opd_patient_department_id` int UNSIGNED NOT NULL,
  `header_note` text COLLATE utf8mb4_unicode_ci,
  `footer_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_prescription_items`
--

CREATE TABLE `opd_prescription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `opd_prescription_id` bigint UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `medicine_id` int UNSIGNED NOT NULL,
  `dosage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dose_interval` int NOT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `opd_timelines`
--

CREATE TABLE `opd_timelines` (
  `id` int UNSIGNED NOT NULL,
  `opd_patient_department_id` int UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `visible_to_person` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operations`
--

CREATE TABLE `operations` (
  `id` bigint UNSIGNED NOT NULL,
  `operation_category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operation_categories`
--

CREATE TABLE `operation_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operation_reports`
--

CREATE TABLE `operation_reports` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `case_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount` double NOT NULL,
  `total_amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_services`
--

CREATE TABLE `package_services` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `quantity` double NOT NULL,
  `rate` double NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pathology_categories`
--

CREATE TABLE `pathology_categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pathology_parameters`
--

CREATE TABLE `pathology_parameters` (
  `id` int UNSIGNED NOT NULL,
  `parameter_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_range` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pathology_parameter_items`
--

CREATE TABLE `pathology_parameter_items` (
  `id` bigint UNSIGNED NOT NULL,
  `pathology_id` int UNSIGNED NOT NULL,
  `patient_result` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameter_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pathology_tests`
--

CREATE TABLE `pathology_tests` (
  `id` int UNSIGNED NOT NULL,
  `test_name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `unit` int DEFAULT NULL,
  `subcategory` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_days` int DEFAULT NULL,
  `charge_category_id` int UNSIGNED NOT NULL,
  `standard_charge` double NOT NULL,
  `patient_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pathology_units`
--

CREATE TABLE `pathology_units` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `patient_unique_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template_id` bigint UNSIGNED DEFAULT NULL,
  `custom_field` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_admissions`
--

CREATE TABLE `patient_admissions` (
  `id` int UNSIGNED NOT NULL,
  `patient_admission_id` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `admission_date` datetime NOT NULL,
  `discharge_date` datetime DEFAULT NULL,
  `package_id` int UNSIGNED DEFAULT NULL,
  `insurance_id` int UNSIGNED DEFAULT NULL,
  `bed_id` int UNSIGNED DEFAULT NULL,
  `policy_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agent_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_relation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_cases`
--

CREATE TABLE `patient_cases` (
  `id` int UNSIGNED NOT NULL,
  `case_id` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `fee` double NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_diagnosis_properties`
--

CREATE TABLE `patient_diagnosis_properties` (
  `id` int UNSIGNED NOT NULL,
  `patient_diagnosis_id` bigint UNSIGNED NOT NULL,
  `property_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_diagnosis_tests`
--

CREATE TABLE `patient_diagnosis_tests` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `report_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_id_card_templates`
--

CREATE TABLE `patient_id_card_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` tinyint(1) NOT NULL DEFAULT '1',
  `phone` tinyint(1) NOT NULL DEFAULT '1',
  `dob` tinyint(1) NOT NULL DEFAULT '1',
  `blood_group` tinyint(1) NOT NULL DEFAULT '1',
  `address` tinyint(1) NOT NULL DEFAULT '1',
  `patient_unique_id` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `account_id` int UNSIGNED NOT NULL,
  `pay_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage_users', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(2, 'manage_beds', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(3, 'manage_wards', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(4, 'manage_appointments', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(5, 'manage_prescriptions', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(6, 'manage_patients', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(7, 'manage_blood_bank', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(8, 'manage_reports', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(9, 'manage_payrolls', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(10, 'manage_settings', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(11, 'manage_notice_board', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(12, 'manage_doctors', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(13, 'manage_nurses', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(14, 'manage_receptionists', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(15, 'manage_pharmacists', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(16, 'manage_accountants', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(17, 'manage_invoices', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(18, 'manage_operations_history', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(19, 'manage_admit_history', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(20, 'manage_blood_donor', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(21, 'manage_medicines', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(22, 'manage_department', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(23, 'manage_doctor_departments', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(24, 'manage_lab_technicians', 'web', '2024-12-26 04:55:25', '2024-12-26 04:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacists`
--

CREATE TABLE `pharmacists` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `postals`
--

CREATE TABLE `postals` (
  `id` bigint UNSIGNED NOT NULL,
  `from_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `type` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED DEFAULT NULL,
  `food_allergies` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tendency_bleed` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heart_disease` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `high_blood_pressure` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diabetic` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surgery` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accident` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `others` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_history` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_medication` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `female_pregnancy` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breast_feeding` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_insurance` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `low_income` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `plus_rate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperature` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problem_description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `test` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advice` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_visit_qty` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_visit_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions_medicines`
--

CREATE TABLE `prescriptions_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `prescription_id` int UNSIGNED NOT NULL,
  `medicine` int UNSIGNED NOT NULL,
  `dosage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `day` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dose_interval` int NOT NULL,
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchased_medicines`
--

CREATE TABLE `purchased_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_medicines_id` bigint UNSIGNED NOT NULL,
  `medicine_id` int UNSIGNED DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `lot_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax` double(25,2) NOT NULL,
  `quantity` int NOT NULL,
  `amount` double(25,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_medicines`
--

CREATE TABLE `purchase_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax` double(25,2) NOT NULL,
  `total` double(25,2) NOT NULL,
  `net_amount` double(25,2) NOT NULL,
  `payment_type` int NOT NULL,
  `payment_status` tinyint(1) DEFAULT '1',
  `discount` double(25,2) NOT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `radiology_categories`
--

CREATE TABLE `radiology_categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `radiology_tests`
--

CREATE TABLE `radiology_tests` (
  `id` int UNSIGNED NOT NULL,
  `test_name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `subcategory` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_days` int DEFAULT NULL,
  `charge_category_id` int UNSIGNED NOT NULL,
  `standard_charge` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receptionists`
--

CREATE TABLE `receptionists` (
  `id` int UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_medicines`
--

CREATE TABLE `sale_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `medicine_bill_id` int UNSIGNED NOT NULL,
  `medicine_id` int UNSIGNED NOT NULL,
  `sale_quantity` int NOT NULL,
  `sale_price` double(25,2) NOT NULL,
  `tax` double(25,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `amount` double(25,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `per_patient_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_days`
--

CREATE TABLE `schedule_days` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `schedule_id` int UNSIGNED NOT NULL,
  `available_on` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_from` time NOT NULL,
  `available_to` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL,
  `rate` double NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_symbol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'country_code', '+91', '2024-12-26 04:55:06', '2024-12-26 04:55:06'),
(2, 'country_name', 'in', '2024-12-26 04:55:09', '2024-12-26 04:55:09'),
(3, 'default_lang', 'en', '2024-12-26 04:55:12', '2024-12-26 04:55:12'),
(4, 'stripe_enable', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(5, 'stripe_key', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(6, 'stripe_secret', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(7, 'paypal_enable', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(8, 'paypal_client_id', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(9, 'paypal_secret', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(10, 'paypal_mode', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(11, 'razorpay_enable', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(12, 'razorpay_key', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(13, 'razorpay_secret', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(14, 'paystack_enable', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(15, 'paystack_public_key', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(16, 'paystack_secret_key', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(17, 'phone_pe_enable', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(18, 'phonepe_merchant_id', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(19, 'phonepe_merchant_user_id', NULL, '2024-12-26 04:55:13', '2024-12-26 04:55:13'),
(20, 'phonepe_env', NULL, '2024-12-26 04:55:14', '2024-12-26 04:55:14'),
(21, 'phonepe_salt_key', NULL, '2024-12-26 04:55:14', '2024-12-26 04:55:14'),
(22, 'phonepe_salt_index', NULL, '2024-12-26 04:55:14', '2024-12-26 04:55:14'),
(23, 'phonepe_merchant_transaction_id', NULL, '2024-12-26 04:55:14', '2024-12-26 04:55:14'),
(24, 'flutterwave_enable', NULL, '2024-12-26 04:55:17', '2024-12-26 04:55:17'),
(25, 'flutterwave_public_key', NULL, '2024-12-26 04:55:17', '2024-12-26 04:55:17'),
(26, 'flutterwave_secret_key', NULL, '2024-12-26 04:55:17', '2024-12-26 04:55:17'),
(27, 'open_ai_enable', '0', '2024-12-26 04:55:23', '2024-12-26 04:55:23'),
(28, 'open_ai_key', NULL, '2024-12-26 04:55:23', '2024-12-26 04:55:23'),
(29, 'app_name', 'HMS', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(30, 'app_logo', 'web/img/logo.jpg', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(31, 'company_name', 'InfyOmLabs', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(32, 'current_currency', 'inr', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(33, 'hospital_address', '16/A saint Joseph Park', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(34, 'hospital_email', 'cityhospital@gmail.com', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(35, 'hospital_phone', '+919876543210', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(36, 'hospital_from_day', 'Mon to Fri', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(37, 'hospital_from_time', '9 AM to 9 PM', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(38, 'default_lang', 'en', '2024-12-26 04:55:25', '2024-12-26 04:55:25'),
(39, 'favicon', 'http://e-hms.test/web/img/favicon.png', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(40, 'facebook_url', 'https://www.facebook.com/infyom/', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(41, 'twitter_url', 'https://twitter.com/infyom?lang=en', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(42, 'instagram_url', 'https://www.instagram.com/?hl=en', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(43, 'linkedIn_url', 'https://www.linkedin.com/organization-guest/company/infyom-technologies?challengeId=AQFgQaMxwSxCdAAAAXOA_wosiB2vYdQEoITs6w676AzV8cu8OzhnWEBNUQ7LCG4vds5-A12UIQk1M4aWfKmn6iM58OFJbpoRiA&submissi', '2024-12-26 04:55:26', '2024-12-26 04:55:26'),
(44, 'about_us', 'Over past 10+ years of experience and skills in various technologies, we built great scalable products.\nWhatever technology we worked with, we just not build products for our clients but we a', '2024-12-26 04:55:26', '2024-12-26 04:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` bigint UNSIGNED NOT NULL,
  `send_to` bigint UNSIGNED DEFAULT NULL,
  `region_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `send_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `stripe_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `stripe_transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int NOT NULL,
  `user_id` int NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `used_medicines`
--

CREATE TABLE `used_medicines` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_used` int NOT NULL,
  `medicine_id` int UNSIGNED DEFAULT NULL,
  `model_id` int NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` int NOT NULL,
  `qualification` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `owner_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `language` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedIn_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `thememode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `card_brand` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `department_id`, `first_name`, `last_name`, `email`, `password`, `designation`, `phone`, `gender`, `qualification`, `blood_group`, `dob`, `email_verified_at`, `owner_id`, `owner_type`, `status`, `language`, `remember_token`, `facebook_url`, `twitter_url`, `instagram_url`, `linkedIn_url`, `created_at`, `updated_at`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`, `thememode`, `card_brand`) VALUES
(1, 1, 'Super', 'Admin', 'admin@hms.com', '$2y$10$K1LJbF2q.mizwElGHSZ.cuJx9N5KN7hlSnIe4dFX9nHgrmWvTfeim', NULL, '7878454512', 1, NULL, 'B+', '1994-12-12', '2024-12-26 04:55:24', NULL, NULL, 1, 'en', NULL, NULL, NULL, NULL, NULL, '2024-12-26 04:55:24', '2024-12-26 04:55:24', NULL, NULL, NULL, NULL, '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_google_event_schedules`
--

CREATE TABLE `user_google_event_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `google_live_consultation_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_calendar_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_event_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_meet_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_zoom_credential`
--

CREATE TABLE `user_zoom_credential` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `zoom_api_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zoom_api_secret` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccinated_patients`
--

CREATE TABLE `vaccinated_patients` (
  `id` int UNSIGNED NOT NULL,
  `patient_id` int UNSIGNED NOT NULL,
  `vaccination_id` int UNSIGNED NOT NULL,
  `vaccination_serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dose_number` bigint NOT NULL,
  `dose_given_date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vaccinations`
--

CREATE TABLE `vaccinations` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufactured_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` bigint UNSIGNED NOT NULL,
  `purpose` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `in_time` time DEFAULT NULL,
  `out_time` time DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zoom_o_auth_credentials`
--

CREATE TABLE `zoom_o_auth_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountants`
--
ALTER TABLE `accountants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountants_user_id_foreign` (`user_id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_name_index` (`name`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_custom_fields`
--
ALTER TABLE `add_custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_ons`
--
ALTER TABLE `add_ons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `add_ons_name_unique` (`name`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advanced_payments`
--
ALTER TABLE `advanced_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advanced_payments_patient_id_foreign` (`patient_id`),
  ADD KEY `advanced_payments_amount_index` (`amount`);

--
-- Indexes for table `ambulances`
--
ALTER TABLE `ambulances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ambulances_vehicle_number_index` (`vehicle_number`);

--
-- Indexes for table `ambulance_calls`
--
ALTER TABLE `ambulance_calls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ambulance_calls_patient_id_foreign` (`patient_id`),
  ADD KEY `ambulance_calls_ambulance_id_foreign` (`ambulance_id`),
  ADD KEY `ambulance_calls_date_index` (`date`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_opd_date_index` (`opd_date`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `appointments_department_id_foreign` (`department_id`);

--
-- Indexes for table `appointment_transactions`
--
ALTER TABLE `appointment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_transactions_appointment_id_foreign` (`appointment_id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beds_bed_type_foreign` (`bed_type`),
  ADD KEY `beds_is_available_index` (`is_available`);

--
-- Indexes for table `bed_assigns`
--
ALTER TABLE `bed_assigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bed_assigns_bed_id_foreign` (`bed_id`),
  ADD KEY `bed_assigns_patient_id_foreign` (`patient_id`),
  ADD KEY `bed_assigns_created_at_assign_date_index` (`created_at`,`assign_date`),
  ADD KEY `bed_assigns_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `bed_types`
--
ALTER TABLE `bed_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bed_types_title_index` (`title`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bills_patient_id_foreign` (`patient_id`),
  ADD KEY `bills_bill_date_index` (`bill_date`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_items_bill_id_foreign` (`bill_id`);

--
-- Indexes for table `bill_transactions`
--
ALTER TABLE `bill_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_transactions_bill_id_foreign` (`bill_id`);

--
-- Indexes for table `birth_reports`
--
ALTER TABLE `birth_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `birth_reports_patient_id_foreign` (`patient_id`),
  ADD KEY `birth_reports_doctor_id_foreign` (`doctor_id`),
  ADD KEY `birth_reports_date_index` (`date`);

--
-- Indexes for table `blood_banks`
--
ALTER TABLE `blood_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blood_banks_remained_bags_index` (`remained_bags`);

--
-- Indexes for table `blood_donations`
--
ALTER TABLE `blood_donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blood_donations_blood_donor_id_foreign` (`blood_donor_id`);

--
-- Indexes for table `blood_donors`
--
ALTER TABLE `blood_donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blood_donors_created_at_last_donate_date_index` (`created_at`,`last_donate_date`);

--
-- Indexes for table `blood_issues`
--
ALTER TABLE `blood_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blood_issues_doctor_id_foreign` (`doctor_id`),
  ADD KEY `blood_issues_donor_id_foreign` (`donor_id`),
  ADD KEY `blood_issues_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brands_name_index` (`name`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `case_handlers`
--
ALTER TABLE `case_handlers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_handlers_user_id_foreign` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_name_index` (`name`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `charges_charge_category_id_foreign` (`charge_category_id`),
  ADD KEY `charges_code_index` (`code`);

--
-- Indexes for table `charge_categories`
--
ALTER TABLE `charge_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `charge_categories_name_index` (`name`);

--
-- Indexes for table `currency_settings`
--
ALTER TABLE `currency_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `death_reports`
--
ALTER TABLE `death_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `death_reports_patient_id_foreign` (`patient_id`),
  ADD KEY `death_reports_doctor_id_foreign` (`doctor_id`),
  ADD KEY `death_reports_date_index` (`date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diagnosis_categories`
--
ALTER TABLE `diagnosis_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diagnosis_categories_name_index` (`name`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_user_id_foreign` (`user_id`),
  ADD KEY `doctors_doctor_department_id_foreign` (`doctor_department_id`);

--
-- Indexes for table `doctor_departments`
--
ALTER TABLE `doctor_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_departments_title_index` (`title`);

--
-- Indexes for table `doctor_holidays`
--
ALTER TABLE `doctor_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_holidays_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `doctor_opd_charges`
--
ALTER TABLE `doctor_opd_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_opd_charges_doctor_id_foreign` (`doctor_id`),
  ADD KEY `doctor_opd_charges_created_at_index` (`created_at`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_types_name_index` (`name`);

--
-- Indexes for table `employee_payrolls`
--
ALTER TABLE `employee_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_payrolls_id_sr_no_index` (`id`,`sr_no`);

--
-- Indexes for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enquiries_viewed_by_foreign` (`viewed_by`),
  ADD KEY `enquiries_created_at_index` (`created_at`);

--
-- Indexes for table `event_google_calendars`
--
ALTER TABLE `event_google_calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_google_calendars_user_id_foreign` (`user_id`),
  ADD KEY `event_google_calendars_google_calendar_list_id_foreign` (`google_calendar_list_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_date_index` (`date`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `front_services`
--
ALTER TABLE `front_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `front_settings`
--
ALTER TABLE `front_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `google_calendar_integrations`
--
ALTER TABLE `google_calendar_integrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `google_calendar_integrations_user_id_foreign` (`user_id`);

--
-- Indexes for table `google_calendar_lists`
--
ALTER TABLE `google_calendar_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `google_calendar_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `hospital_schedules`
--
ALTER TABLE `hospital_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incomes_date_index` (`date`);

--
-- Indexes for table `insurances`
--
ALTER TABLE `insurances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurances_name_index` (`name`);

--
-- Indexes for table `insurance_diseases`
--
ALTER TABLE `insurance_diseases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `insurance_diseases_insurance_id_foreign` (`insurance_id`);

--
-- Indexes for table `investigation_reports`
--
ALTER TABLE `investigation_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investigation_reports_patient_id_foreign` (`patient_id`),
  ADD KEY `investigation_reports_doctor_id_foreign` (`doctor_id`),
  ADD KEY `investigation_reports_date_index` (`date`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_patient_id_foreign` (`patient_id`),
  ADD KEY `invoices_invoice_date_index` (`invoice_date`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_account_id_foreign` (`account_id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `ipd_bills`
--
ALTER TABLE `ipd_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_bills_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `ipd_charges`
--
ALTER TABLE `ipd_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_charges_ipd_patient_department_id_foreign` (`ipd_patient_department_id`),
  ADD KEY `ipd_charges_charge_category_id_foreign` (`charge_category_id`),
  ADD KEY `ipd_charges_charge_id_foreign` (`charge_id`);

--
-- Indexes for table `ipd_consultant_registers`
--
ALTER TABLE `ipd_consultant_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_consultant_registers_ipd_patient_department_id_foreign` (`ipd_patient_department_id`),
  ADD KEY `ipd_consultant_registers_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `ipd_diagnoses`
--
ALTER TABLE `ipd_diagnoses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_diagnoses_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `ipd_operation`
--
ALTER TABLE `ipd_operation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_operation_operation_category_id_foreign` (`operation_category_id`),
  ADD KEY `ipd_operation_doctor_id_foreign` (`doctor_id`),
  ADD KEY `ipd_operation_operation_id_foreign` (`operation_id`),
  ADD KEY `ipd_operation_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `ipd_patient_departments`
--
ALTER TABLE `ipd_patient_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ipd_patient_departments_ipd_number_unique` (`ipd_number`),
  ADD KEY `ipd_patient_departments_patient_id_foreign` (`patient_id`),
  ADD KEY `ipd_patient_departments_case_id_foreign` (`case_id`),
  ADD KEY `ipd_patient_departments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `ipd_patient_departments_bed_type_id_foreign` (`bed_type_id`),
  ADD KEY `ipd_patient_departments_bed_id_foreign` (`bed_id`);

--
-- Indexes for table `ipd_payments`
--
ALTER TABLE `ipd_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_payments_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `ipd_prescriptions`
--
ALTER TABLE `ipd_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_prescriptions_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `ipd_prescription_items`
--
ALTER TABLE `ipd_prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_prescription_items_ipd_prescription_id_foreign` (`ipd_prescription_id`),
  ADD KEY `ipd_prescription_items_category_id_foreign` (`category_id`),
  ADD KEY `ipd_prescription_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `ipd_timelines`
--
ALTER TABLE `ipd_timelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipd_timelines_ipd_patient_department_id_foreign` (`ipd_patient_department_id`);

--
-- Indexes for table `issued_items`
--
ALTER TABLE `issued_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issued_items_department_id_foreign` (`department_id`),
  ADD KEY `issued_items_user_id_foreign` (`user_id`),
  ADD KEY `issued_items_item_category_id_foreign` (`item_category_id`),
  ADD KEY `issued_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_item_category_id_foreign` (`item_category_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_categories_name_unique` (`name`);

--
-- Indexes for table `item_stocks`
--
ALTER TABLE `item_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_stocks_item_category_id_foreign` (`item_category_id`),
  ADD KEY `item_stocks_item_id_foreign` (`item_id`);

--
-- Indexes for table `lab_technicians`
--
ALTER TABLE `lab_technicians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_technicians_user_id_foreign` (`user_id`);

--
-- Indexes for table `live_consultations`
--
ALTER TABLE `live_consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `live_consultations_doctor_id_foreign` (`doctor_id`),
  ADD KEY `live_consultations_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `live_meetings`
--
ALTER TABLE `live_meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_meetings_candidates`
--
ALTER TABLE `live_meetings_candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lunch_breaks`
--
ALTER TABLE `lunch_breaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lunch_breaks_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mails_user_id_foreign` (`user_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicines_category_id_foreign` (`category_id`),
  ADD KEY `medicines_brand_id_foreign` (`brand_id`),
  ADD KEY `160` (`name`);

--
-- Indexes for table `medicine_bills`
--
ALTER TABLE `medicine_bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD UNIQUE KEY `model_has_permissions_model_type_unique` (`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice_boards`
--
ALTER TABLE `notice_boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notice_boards_created_at_id_index` (`created_at`,`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `nurses`
--
ALTER TABLE `nurses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nurses_user_id_foreign` (`user_id`);

--
-- Indexes for table `opd_diagnoses`
--
ALTER TABLE `opd_diagnoses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opd_diagnoses_opd_patient_department_id_foreign` (`opd_patient_department_id`);

--
-- Indexes for table `opd_patient_departments`
--
ALTER TABLE `opd_patient_departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `opd_patient_departments_opd_number_unique` (`opd_number`),
  ADD KEY `opd_patient_departments_patient_id_foreign` (`patient_id`),
  ADD KEY `opd_patient_departments_case_id_foreign` (`case_id`),
  ADD KEY `opd_patient_departments_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `opd_prescriptions`
--
ALTER TABLE `opd_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opd_prescriptions_opd_patient_department_id_foreign` (`opd_patient_department_id`);

--
-- Indexes for table `opd_prescription_items`
--
ALTER TABLE `opd_prescription_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opd_prescription_items_opd_prescription_id_foreign` (`opd_prescription_id`),
  ADD KEY `opd_prescription_items_category_id_foreign` (`category_id`),
  ADD KEY `opd_prescription_items_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `opd_timelines`
--
ALTER TABLE `opd_timelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opd_timelines_opd_patient_department_id_foreign` (`opd_patient_department_id`);

--
-- Indexes for table `operations`
--
ALTER TABLE `operations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operations_name_unique` (`name`),
  ADD KEY `operations_operation_category_id_foreign` (`operation_category_id`);

--
-- Indexes for table `operation_categories`
--
ALTER TABLE `operation_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operation_categories_name_unique` (`name`);

--
-- Indexes for table `operation_reports`
--
ALTER TABLE `operation_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operation_reports_patient_id_foreign` (`patient_id`),
  ADD KEY `operation_reports_date_index` (`date`),
  ADD KEY `operation_reports_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_created_at_name_index` (`created_at`,`name`);

--
-- Indexes for table `package_services`
--
ALTER TABLE `package_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_services_package_id_foreign` (`package_id`),
  ADD KEY `package_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pathology_categories`
--
ALTER TABLE `pathology_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pathology_categories_name_unique` (`name`),
  ADD KEY `pathology_categories_name_index` (`name`);

--
-- Indexes for table `pathology_parameters`
--
ALTER TABLE `pathology_parameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pathology_parameters_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `pathology_parameter_items`
--
ALTER TABLE `pathology_parameter_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pathology_parameter_items_pathology_id_foreign` (`pathology_id`),
  ADD KEY `pathology_parameter_items_parameter_id_foreign` (`parameter_id`);

--
-- Indexes for table `pathology_tests`
--
ALTER TABLE `pathology_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pathology_tests_category_id_foreign` (`category_id`),
  ADD KEY `pathology_tests_charge_category_id_foreign` (`charge_category_id`),
  ADD KEY `pathology_tests_test_name_index` (`test_name`),
  ADD KEY `pathology_tests_patient_id_foreign` (`patient_id`);

--
-- Indexes for table `pathology_units`
--
ALTER TABLE `pathology_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patients_user_id_foreign` (`user_id`),
  ADD KEY `patients_template_id_foreign` (`template_id`);

--
-- Indexes for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_admissions_patient_admission_id_unique` (`patient_admission_id`),
  ADD KEY `patient_admissions_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_admissions_doctor_id_foreign` (`doctor_id`),
  ADD KEY `patient_admissions_package_id_foreign` (`package_id`),
  ADD KEY `patient_admissions_insurance_id_foreign` (`insurance_id`),
  ADD KEY `patient_admissions_bed_id_foreign` (`bed_id`),
  ADD KEY `patient_admissions_admission_date_index` (`admission_date`);

--
-- Indexes for table `patient_cases`
--
ALTER TABLE `patient_cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_cases_case_id_unique` (`case_id`),
  ADD KEY `patient_cases_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_cases_doctor_id_foreign` (`doctor_id`),
  ADD KEY `patient_cases_date_index` (`date`);

--
-- Indexes for table `patient_diagnosis_properties`
--
ALTER TABLE `patient_diagnosis_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_diagnosis_properties_created_at_index` (`created_at`),
  ADD KEY `patient_diagnosis_properties_patient_diagnosis_id_foreign` (`patient_diagnosis_id`);

--
-- Indexes for table `patient_diagnosis_tests`
--
ALTER TABLE `patient_diagnosis_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_diagnosis_tests_created_at_index` (`created_at`),
  ADD KEY `patient_diagnosis_tests_patient_id_foreign` (`patient_id`),
  ADD KEY `patient_diagnosis_tests_doctor_id_foreign` (`doctor_id`),
  ADD KEY `patient_diagnosis_tests_category_id_foreign` (`category_id`);

--
-- Indexes for table `patient_id_card_templates`
--
ALTER TABLE `patient_id_card_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_account_id_foreign` (`account_id`),
  ADD KEY `payments_payment_date_index` (`payment_date`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pharmacists`
--
ALTER TABLE `pharmacists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pharmacists_user_id_foreign` (`user_id`);

--
-- Indexes for table `postals`
--
ALTER TABLE `postals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`),
  ADD KEY `prescriptions_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `prescriptions_medicines`
--
ALTER TABLE `prescriptions_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_medicines_prescription_id_foreign` (`prescription_id`),
  ADD KEY `prescriptions_medicines_medicine_foreign` (`medicine`);

--
-- Indexes for table `purchased_medicines`
--
ALTER TABLE `purchased_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchased_medicines_medicine_id_foreign` (`medicine_id`),
  ADD KEY `purchased_medicines_purchase_medicines_id_foreign` (`purchase_medicines_id`);

--
-- Indexes for table `purchase_medicines`
--
ALTER TABLE `purchase_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `radiology_categories`
--
ALTER TABLE `radiology_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `radiology_categories_name_unique` (`name`),
  ADD KEY `radiology_categories_name_index` (`name`);

--
-- Indexes for table `radiology_tests`
--
ALTER TABLE `radiology_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `radiology_tests_category_id_foreign` (`category_id`),
  ADD KEY `radiology_tests_charge_category_id_foreign` (`charge_category_id`),
  ADD KEY `radiology_tests_test_name_index` (`test_name`),
  ADD KEY `radiology_tests_charge_id_foreign` (`charge_id`);

--
-- Indexes for table `receptionists`
--
ALTER TABLE `receptionists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receptionists_user_id_foreign` (`user_id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sale_medicines`
--
ALTER TABLE `sale_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `schedule_days`
--
ALTER TABLE `schedule_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_days_doctor_id_foreign` (`doctor_id`),
  ADD KEY `schedule_days_schedule_id_foreign` (`schedule_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_name_index` (`name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_send_to_foreign` (`send_to`),
  ADD KEY `sms_send_by_foreign` (`send_by`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indexes for table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_subscription_id_stripe_price_unique` (`subscription_id`,`stripe_price`),
  ADD UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `used_medicines`
--
ALTER TABLE `used_medicines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `used_medicines_medicine_id_foreign` (`medicine_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_stripe_id_index` (`stripe_id`),
  ADD KEY `users_first_name_index` (`first_name`);

--
-- Indexes for table `user_google_event_schedules`
--
ALTER TABLE `user_google_event_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_google_event_schedules_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_zoom_credential`
--
ALTER TABLE `user_zoom_credential`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_zoom_credential_user_id_foreign` (`user_id`);

--
-- Indexes for table `vaccinated_patients`
--
ALTER TABLE `vaccinated_patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vaccinated_patients_id_index` (`id`),
  ADD KEY `vaccinated_patients_patient_id_index` (`patient_id`),
  ADD KEY `vaccinated_patients_vaccination_id_index` (`vaccination_id`);

--
-- Indexes for table `vaccinations`
--
ALTER TABLE `vaccinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vaccinations_id_index` (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zoom_o_auth_credentials`
--
ALTER TABLE `zoom_o_auth_credentials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zoom_o_auth_credentials_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountants`
--
ALTER TABLE `accountants`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `add_custom_fields`
--
ALTER TABLE `add_custom_fields`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `add_ons`
--
ALTER TABLE `add_ons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `advanced_payments`
--
ALTER TABLE `advanced_payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambulances`
--
ALTER TABLE `ambulances`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambulance_calls`
--
ALTER TABLE `ambulance_calls`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment_transactions`
--
ALTER TABLE `appointment_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_assigns`
--
ALTER TABLE `bed_assigns`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bed_types`
--
ALTER TABLE `bed_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_transactions`
--
ALTER TABLE `bill_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `birth_reports`
--
ALTER TABLE `birth_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_banks`
--
ALTER TABLE `blood_banks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_donations`
--
ALTER TABLE `blood_donations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_donors`
--
ALTER TABLE `blood_donors`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blood_issues`
--
ALTER TABLE `blood_issues`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_handlers`
--
ALTER TABLE `case_handlers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charges`
--
ALTER TABLE `charges`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charge_categories`
--
ALTER TABLE `charge_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency_settings`
--
ALTER TABLE `currency_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `death_reports`
--
ALTER TABLE `death_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `diagnosis_categories`
--
ALTER TABLE `diagnosis_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_departments`
--
ALTER TABLE `doctor_departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_holidays`
--
ALTER TABLE `doctor_holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_opd_charges`
--
ALTER TABLE `doctor_opd_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_payrolls`
--
ALTER TABLE `employee_payrolls`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_google_calendars`
--
ALTER TABLE `event_google_calendars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `front_services`
--
ALTER TABLE `front_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `front_settings`
--
ALTER TABLE `front_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `google_calendar_integrations`
--
ALTER TABLE `google_calendar_integrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `google_calendar_lists`
--
ALTER TABLE `google_calendar_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hospital_schedules`
--
ALTER TABLE `hospital_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurances`
--
ALTER TABLE `insurances`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insurance_diseases`
--
ALTER TABLE `insurance_diseases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investigation_reports`
--
ALTER TABLE `investigation_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_bills`
--
ALTER TABLE `ipd_bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_charges`
--
ALTER TABLE `ipd_charges`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_consultant_registers`
--
ALTER TABLE `ipd_consultant_registers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_diagnoses`
--
ALTER TABLE `ipd_diagnoses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_operation`
--
ALTER TABLE `ipd_operation`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_patient_departments`
--
ALTER TABLE `ipd_patient_departments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_payments`
--
ALTER TABLE `ipd_payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_prescriptions`
--
ALTER TABLE `ipd_prescriptions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_prescription_items`
--
ALTER TABLE `ipd_prescription_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipd_timelines`
--
ALTER TABLE `ipd_timelines`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issued_items`
--
ALTER TABLE `issued_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_stocks`
--
ALTER TABLE `item_stocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_technicians`
--
ALTER TABLE `lab_technicians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_consultations`
--
ALTER TABLE `live_consultations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_meetings`
--
ALTER TABLE `live_meetings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_meetings_candidates`
--
ALTER TABLE `live_meetings_candidates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lunch_breaks`
--
ALTER TABLE `lunch_breaks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mails`
--
ALTER TABLE `mails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_bills`
--
ALTER TABLE `medicine_bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `notice_boards`
--
ALTER TABLE `notice_boards`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nurses`
--
ALTER TABLE `nurses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opd_diagnoses`
--
ALTER TABLE `opd_diagnoses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opd_patient_departments`
--
ALTER TABLE `opd_patient_departments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opd_prescriptions`
--
ALTER TABLE `opd_prescriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opd_prescription_items`
--
ALTER TABLE `opd_prescription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `opd_timelines`
--
ALTER TABLE `opd_timelines`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operations`
--
ALTER TABLE `operations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_categories`
--
ALTER TABLE `operation_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_reports`
--
ALTER TABLE `operation_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_services`
--
ALTER TABLE `package_services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathology_categories`
--
ALTER TABLE `pathology_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathology_parameters`
--
ALTER TABLE `pathology_parameters`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathology_parameter_items`
--
ALTER TABLE `pathology_parameter_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathology_tests`
--
ALTER TABLE `pathology_tests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pathology_units`
--
ALTER TABLE `pathology_units`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_cases`
--
ALTER TABLE `patient_cases`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_diagnosis_properties`
--
ALTER TABLE `patient_diagnosis_properties`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_diagnosis_tests`
--
ALTER TABLE `patient_diagnosis_tests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient_id_card_templates`
--
ALTER TABLE `patient_id_card_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacists`
--
ALTER TABLE `pharmacists`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postals`
--
ALTER TABLE `postals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions_medicines`
--
ALTER TABLE `prescriptions_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchased_medicines`
--
ALTER TABLE `purchased_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_medicines`
--
ALTER TABLE `purchase_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `radiology_categories`
--
ALTER TABLE `radiology_categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `radiology_tests`
--
ALTER TABLE `radiology_tests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receptionists`
--
ALTER TABLE `receptionists`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_medicines`
--
ALTER TABLE `sale_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_days`
--
ALTER TABLE `schedule_days`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `used_medicines`
--
ALTER TABLE `used_medicines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_google_event_schedules`
--
ALTER TABLE `user_google_event_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_zoom_credential`
--
ALTER TABLE `user_zoom_credential`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccinated_patients`
--
ALTER TABLE `vaccinated_patients`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vaccinations`
--
ALTER TABLE `vaccinations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zoom_o_auth_credentials`
--
ALTER TABLE `zoom_o_auth_credentials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accountants`
--
ALTER TABLE `accountants`
  ADD CONSTRAINT `accountants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `advanced_payments`
--
ALTER TABLE `advanced_payments`
  ADD CONSTRAINT `advanced_payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `ambulance_calls`
--
ALTER TABLE `ambulance_calls`
  ADD CONSTRAINT `ambulance_calls_ambulance_id_foreign` FOREIGN KEY (`ambulance_id`) REFERENCES `ambulances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ambulance_calls_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `doctor_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointment_transactions`
--
ALTER TABLE `appointment_transactions`
  ADD CONSTRAINT `appointment_transactions_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_bed_type_foreign` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bed_assigns`
--
ALTER TABLE `bed_assigns`
  ADD CONSTRAINT `bed_assigns_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bed_assigns_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bed_assigns_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bill_transactions`
--
ALTER TABLE `bill_transactions`
  ADD CONSTRAINT `bill_transactions_bill_id_foreign` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `birth_reports`
--
ALTER TABLE `birth_reports`
  ADD CONSTRAINT `birth_reports_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `birth_reports_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blood_donations`
--
ALTER TABLE `blood_donations`
  ADD CONSTRAINT `blood_donations_blood_donor_id_foreign` FOREIGN KEY (`blood_donor_id`) REFERENCES `blood_donors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `blood_issues`
--
ALTER TABLE `blood_issues`
  ADD CONSTRAINT `blood_issues_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blood_issues_donor_id_foreign` FOREIGN KEY (`donor_id`) REFERENCES `blood_donors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `blood_issues_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `case_handlers`
--
ALTER TABLE `case_handlers`
  ADD CONSTRAINT `case_handlers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `charges`
--
ALTER TABLE `charges`
  ADD CONSTRAINT `charges_charge_category_id_foreign` FOREIGN KEY (`charge_category_id`) REFERENCES `charge_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `death_reports`
--
ALTER TABLE `death_reports`
  ADD CONSTRAINT `death_reports_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `death_reports_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_doctor_department_id_foreign` FOREIGN KEY (`doctor_department_id`) REFERENCES `doctor_departments` (`id`),
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_holidays`
--
ALTER TABLE `doctor_holidays`
  ADD CONSTRAINT `doctor_holidays_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_opd_charges`
--
ALTER TABLE `doctor_opd_charges`
  ADD CONSTRAINT `doctor_opd_charges_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enquiries`
--
ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_viewed_by_foreign` FOREIGN KEY (`viewed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event_google_calendars`
--
ALTER TABLE `event_google_calendars`
  ADD CONSTRAINT `event_google_calendars_google_calendar_list_id_foreign` FOREIGN KEY (`google_calendar_list_id`) REFERENCES `google_calendar_lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_google_calendars_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `google_calendar_integrations`
--
ALTER TABLE `google_calendar_integrations`
  ADD CONSTRAINT `google_calendar_integrations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `google_calendar_lists`
--
ALTER TABLE `google_calendar_lists`
  ADD CONSTRAINT `google_calendar_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `insurance_diseases`
--
ALTER TABLE `insurance_diseases`
  ADD CONSTRAINT `insurance_diseases_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `insurances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `investigation_reports`
--
ALTER TABLE `investigation_reports`
  ADD CONSTRAINT `investigation_reports_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `investigation_reports_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_bills`
--
ALTER TABLE `ipd_bills`
  ADD CONSTRAINT `ipd_bills_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_charges`
--
ALTER TABLE `ipd_charges`
  ADD CONSTRAINT `ipd_charges_charge_category_id_foreign` FOREIGN KEY (`charge_category_id`) REFERENCES `charge_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_charges_charge_id_foreign` FOREIGN KEY (`charge_id`) REFERENCES `charges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_charges_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_consultant_registers`
--
ALTER TABLE `ipd_consultant_registers`
  ADD CONSTRAINT `ipd_consultant_registers_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_consultant_registers_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_diagnoses`
--
ALTER TABLE `ipd_diagnoses`
  ADD CONSTRAINT `ipd_diagnoses_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_operation`
--
ALTER TABLE `ipd_operation`
  ADD CONSTRAINT `ipd_operation_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_operation_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_operation_operation_category_id_foreign` FOREIGN KEY (`operation_category_id`) REFERENCES `operation_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_operation_operation_id_foreign` FOREIGN KEY (`operation_id`) REFERENCES `operations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_patient_departments`
--
ALTER TABLE `ipd_patient_departments`
  ADD CONSTRAINT `ipd_patient_departments_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_patient_departments_bed_type_id_foreign` FOREIGN KEY (`bed_type_id`) REFERENCES `bed_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_patient_departments_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `patient_cases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_patient_departments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_patient_departments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_payments`
--
ALTER TABLE `ipd_payments`
  ADD CONSTRAINT `ipd_payments_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_prescriptions`
--
ALTER TABLE `ipd_prescriptions`
  ADD CONSTRAINT `ipd_prescriptions_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_prescription_items`
--
ALTER TABLE `ipd_prescription_items`
  ADD CONSTRAINT `ipd_prescription_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_prescription_items_ipd_prescription_id_foreign` FOREIGN KEY (`ipd_prescription_id`) REFERENCES `ipd_prescriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ipd_prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ipd_timelines`
--
ALTER TABLE `ipd_timelines`
  ADD CONSTRAINT `ipd_timelines_ipd_patient_department_id_foreign` FOREIGN KEY (`ipd_patient_department_id`) REFERENCES `ipd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issued_items`
--
ALTER TABLE `issued_items`
  ADD CONSTRAINT `issued_items_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issued_items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issued_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issued_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `item_stocks`
--
ALTER TABLE `item_stocks`
  ADD CONSTRAINT `item_stocks_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_stocks_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_technicians`
--
ALTER TABLE `lab_technicians`
  ADD CONSTRAINT `lab_technicians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `live_consultations`
--
ALTER TABLE `live_consultations`
  ADD CONSTRAINT `live_consultations_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `live_consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lunch_breaks`
--
ALTER TABLE `lunch_breaks`
  ADD CONSTRAINT `lunch_breaks_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mails`
--
ALTER TABLE `mails`
  ADD CONSTRAINT `mails_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `medicines_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nurses`
--
ALTER TABLE `nurses`
  ADD CONSTRAINT `nurses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opd_diagnoses`
--
ALTER TABLE `opd_diagnoses`
  ADD CONSTRAINT `opd_diagnoses_opd_patient_department_id_foreign` FOREIGN KEY (`opd_patient_department_id`) REFERENCES `opd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opd_patient_departments`
--
ALTER TABLE `opd_patient_departments`
  ADD CONSTRAINT `opd_patient_departments_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `patient_cases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `opd_patient_departments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `opd_patient_departments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opd_prescriptions`
--
ALTER TABLE `opd_prescriptions`
  ADD CONSTRAINT `opd_prescriptions_opd_patient_department_id_foreign` FOREIGN KEY (`opd_patient_department_id`) REFERENCES `opd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opd_prescription_items`
--
ALTER TABLE `opd_prescription_items`
  ADD CONSTRAINT `opd_prescription_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `opd_prescription_items_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `opd_prescription_items_opd_prescription_id_foreign` FOREIGN KEY (`opd_prescription_id`) REFERENCES `opd_prescriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opd_timelines`
--
ALTER TABLE `opd_timelines`
  ADD CONSTRAINT `opd_timelines_opd_patient_department_id_foreign` FOREIGN KEY (`opd_patient_department_id`) REFERENCES `opd_patient_departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operations`
--
ALTER TABLE `operations`
  ADD CONSTRAINT `operations_operation_category_id_foreign` FOREIGN KEY (`operation_category_id`) REFERENCES `operation_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operation_reports`
--
ALTER TABLE `operation_reports`
  ADD CONSTRAINT `operation_reports_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `operation_reports_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package_services`
--
ALTER TABLE `package_services`
  ADD CONSTRAINT `package_services_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `package_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pathology_parameters`
--
ALTER TABLE `pathology_parameters`
  ADD CONSTRAINT `pathology_parameters_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `pathology_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pathology_parameter_items`
--
ALTER TABLE `pathology_parameter_items`
  ADD CONSTRAINT `pathology_parameter_items_parameter_id_foreign` FOREIGN KEY (`parameter_id`) REFERENCES `pathology_parameters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pathology_parameter_items_pathology_id_foreign` FOREIGN KEY (`pathology_id`) REFERENCES `pathology_tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pathology_tests`
--
ALTER TABLE `pathology_tests`
  ADD CONSTRAINT `pathology_tests_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `pathology_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pathology_tests_charge_category_id_foreign` FOREIGN KEY (`charge_category_id`) REFERENCES `charge_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pathology_tests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `patient_id_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_admissions`
--
ALTER TABLE `patient_admissions`
  ADD CONSTRAINT `patient_admissions_bed_id_foreign` FOREIGN KEY (`bed_id`) REFERENCES `beds` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_admissions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_admissions_insurance_id_foreign` FOREIGN KEY (`insurance_id`) REFERENCES `insurances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_admissions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_admissions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_cases`
--
ALTER TABLE `patient_cases`
  ADD CONSTRAINT `patient_cases_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_cases_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_diagnosis_properties`
--
ALTER TABLE `patient_diagnosis_properties`
  ADD CONSTRAINT `patient_diagnosis_properties_patient_diagnosis_id_foreign` FOREIGN KEY (`patient_diagnosis_id`) REFERENCES `patient_diagnosis_tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_diagnosis_tests`
--
ALTER TABLE `patient_diagnosis_tests`
  ADD CONSTRAINT `patient_diagnosis_tests_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `diagnosis_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_diagnosis_tests_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `patient_diagnosis_tests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacists`
--
ALTER TABLE `pharmacists`
  ADD CONSTRAINT `pharmacists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescriptions_medicines`
--
ALTER TABLE `prescriptions_medicines`
  ADD CONSTRAINT `prescriptions_medicines_medicine_foreign` FOREIGN KEY (`medicine`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prescriptions_medicines_prescription_id_foreign` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchased_medicines`
--
ALTER TABLE `purchased_medicines`
  ADD CONSTRAINT `purchased_medicines_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `purchased_medicines_purchase_medicines_id_foreign` FOREIGN KEY (`purchase_medicines_id`) REFERENCES `purchase_medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `radiology_tests`
--
ALTER TABLE `radiology_tests`
  ADD CONSTRAINT `radiology_tests_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `radiology_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `radiology_tests_charge_category_id_foreign` FOREIGN KEY (`charge_category_id`) REFERENCES `charge_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `radiology_tests_charge_id_foreign` FOREIGN KEY (`charge_id`) REFERENCES `charges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receptionists`
--
ALTER TABLE `receptionists`
  ADD CONSTRAINT `receptionists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule_days`
--
ALTER TABLE `schedule_days`
  ADD CONSTRAINT `schedule_days_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedule_days_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sms`
--
ALTER TABLE `sms`
  ADD CONSTRAINT `sms_send_by_foreign` FOREIGN KEY (`send_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sms_send_to_foreign` FOREIGN KEY (`send_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `used_medicines`
--
ALTER TABLE `used_medicines`
  ADD CONSTRAINT `used_medicines_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_google_event_schedules`
--
ALTER TABLE `user_google_event_schedules`
  ADD CONSTRAINT `user_google_event_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_zoom_credential`
--
ALTER TABLE `user_zoom_credential`
  ADD CONSTRAINT `user_zoom_credential_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vaccinated_patients`
--
ALTER TABLE `vaccinated_patients`
  ADD CONSTRAINT `vaccinated_patients_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vaccinated_patients_vaccination_id_foreign` FOREIGN KEY (`vaccination_id`) REFERENCES `vaccinations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `zoom_o_auth_credentials`
--
ALTER TABLE `zoom_o_auth_credentials`
  ADD CONSTRAINT `zoom_o_auth_credentials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
