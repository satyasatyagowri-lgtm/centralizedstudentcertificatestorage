-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2026 at 07:36 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `centralized_student_storgae`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `admin_email` varchar(100) DEFAULT NULL,
  `mobile` varchar(60) DEFAULT NULL,
  `admin_password` varchar(200) DEFAULT NULL,
  `admin_status` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `user_name`, `admin_email`, `mobile`, `admin_password`, `admin_status`) VALUES
(1, 'CHIT FUNDS', 'admin', 'highfuturetech@gmail.com', '1234567890', '123456', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branch_names`
--

CREATE TABLE `branch_names` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(100) DEFAULT NULL,
  `branch_short_name` varchar(30) DEFAULT NULL,
  `branch_city` varchar(50) DEFAULT NULL,
  `city_short_name` varchar(20) NOT NULL,
  `branch_adress` varchar(200) DEFAULT NULL,
  `branch_mobile` varchar(30) DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `incharge_sign` varchar(50) DEFAULT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `branch_incharge` varchar(50) DEFAULT NULL,
  `branch_start_date` varchar(30) DEFAULT NULL,
  `branch_status` int(5) DEFAULT '1',
  `is_hostel` int(5) NOT NULL,
  `book_delar` varchar(100) NOT NULL,
  `book_address` varchar(100) NOT NULL,
  `uniform_delar` varchar(100) NOT NULL,
  `uniform_address` varchar(100) NOT NULL,
  `recognised` varchar(100) NOT NULL,
  `website_name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch_names`
--

INSERT INTO `branch_names` (`branch_id`, `branch_name`, `branch_short_name`, `branch_city`, `city_short_name`, `branch_adress`, `branch_mobile`, `org_id`, `incharge_sign`, `image_path`, `branch_incharge`, `branch_start_date`, `branch_status`, `is_hostel`, `book_delar`, `book_address`, `uniform_delar`, `uniform_address`, `recognised`, `website_name`) VALUES
(1, 'HFT FINANCE', 'HFT', 'KAKINADA', '', 'KAKINADA', '1234567890', 1, NULL, NULL, 'SIVA KUMAR', '2010', 1, 0, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `branch_sms_users`
--

CREATE TABLE `branch_sms_users` (
  `id` int(10) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `user_name` varchar(25) DEFAULT NULL,
  `pword` varchar(25) DEFAULT NULL,
  `sender_id` varchar(10) DEFAULT NULL,
  `branch_id` int(5) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `component_id` int(15) NOT NULL,
  `component_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `component_type` int(2) NOT NULL,
  `controller_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` int(2) NOT NULL,
  `is_download_excel` int(2) NOT NULL DEFAULT '0',
  `is_menu` int(2) NOT NULL DEFAULT '1',
  `is_accountantmenus` int(2) NOT NULL,
  `is_supervisormenus` int(2) NOT NULL,
  `is_playstore_app_permissionpage` int(2) NOT NULL,
  `is_default_check` int(2) NOT NULL,
  `menu_view` int(3) NOT NULL,
  `mainview_order` int(3) NOT NULL,
  `first_subveiw_order` int(4) NOT NULL,
  `second_subveiw_order` int(4) NOT NULL,
  `logo_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_component` int(5) NOT NULL,
  `inserted_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`component_id`, `component_name`, `component_type`, `controller_name`, `page_name`, `is_active`, `is_download_excel`, `is_menu`, `is_accountantmenus`, `is_supervisormenus`, `is_playstore_app_permissionpage`, `is_default_check`, `menu_view`, `mainview_order`, `first_subveiw_order`, `second_subveiw_order`, `logo_name`, `parent_component`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`) VALUES
(1, 'Home', 0, NULL, NULL, 1, 0, 1, 0, 0, 0, 0, 1, 1, 0, 0, 'fas fa-home me-2', 0, 'SK', '2022-02-18 00:00:00', '', '2022-02-18 00:00:00'),
(7, 'Users', 0, '', NULL, 0, 0, 1, 0, 0, 0, 0, 1, 7, 0, 0, 'fas fa-users-cog me-2', 0, '', '2022-04-11 00:00:00', '', '2022-04-11 00:00:00'),
(8, 'Users', 0, 'user_details', 'userdts.php', 1, 0, 1, 0, 0, 0, 0, 3, 0, 0, 0, 'settings', 7, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(9, 'Cancel', 0, '', NULL, 0, 0, 1, 0, 0, 0, 0, 2, 0, 0, 0, '', 2, '', '2022-04-11 00:00:00', '', '2022-04-11 00:00:00'),
(17, 'Roll Mapping', 0, 'user_rolldt', 'user_rolldts.php', 1, 0, 1, 0, 0, 0, 0, 3, 0, 0, 0, 'settings', 7, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(18, 'Settings', 0, 'settings', 'settings.php', 1, 0, 0, 1, 1, 0, 1, 3, 0, 0, 0, 'settings', 7, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(21, 'Upoload Event Certificates', 0, 'dailyevent_certificate', 'dailyevent_certificates.php', 1, 0, 1, 1, 0, 0, 0, 3, 0, 0, 0, 'settings', 1, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(22, 'Daily Event Certificates', 0, 'dailyevent_certificateslist', 'dailyevent_certificateslist.php', 1, 0, 1, 1, 0, 0, 0, 3, 0, 0, 0, 'settings', 1, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(25, 'Assigned List', 0, 'dailyevent_staff_assignedlist', 'dailyevent_staff_assignedlist.php', 1, 0, 1, 0, 0, 0, 0, 3, 0, 0, 0, '', 1, 'sk', '2022-03-10 00:00:00', '', '2022-03-10 00:00:00'),
(26, 'Approved/Disapproved List', 0, 'dailyevent_certificates_approvedlist', 'dailyevent_certificates_approvedlist.php', 1, 0, 1, 0, 0, 0, 0, 3, 0, 0, 0, '', 1, 'sk', '2022-03-10 00:00:00', '', '2022-03-10 00:00:00'),
(29, 'Logout', 0, 'logout', 'logout.php', 0, 0, 0, 1, 1, 0, 1, 3, 0, 0, 0, 'settings', 7, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00'),
(31, 'Success Page', 0, 'success_pg', 'success_pg.php', 1, 0, 0, 1, 1, 0, 1, 3, 0, 0, 0, 'settings', 7, 'sk', '2022-03-10 00:00:00', '', '2022-03-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `empuser_attendance_list`
--

CREATE TABLE `empuser_attendance_list` (
  `id` int(25) NOT NULL,
  `line_id` int(15) NOT NULL,
  `emp_user_id` int(15) NOT NULL,
  `attendance_date` varchar(50) DEFAULT NULL,
  `enter_by` int(15) NOT NULL,
  `enter_date` varchar(50) DEFAULT NULL,
  `update_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empuser_details`
--

CREATE TABLE `empuser_details` (
  `emp_user_id` int(25) NOT NULL,
  `emp_name` varchar(50) DEFAULT NULL,
  `father_name` varchar(50) DEFAULT NULL,
  `empmobile_no` varchar(30) DEFAULT NULL,
  `empaltermobile_no` varchar(30) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `aadhar_no` varchar(50) DEFAULT NULL,
  `active_date` varchar(50) DEFAULT NULL,
  `join_date` varchar(50) DEFAULT NULL,
  `is_delete` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_category`
--

CREATE TABLE `expenditure_category` (
  `exp_catg_id` int(6) NOT NULL,
  `exp_category` varchar(30) DEFAULT NULL,
  `exp_catg_shortname` varchar(20) DEFAULT NULL,
  `is_bank_person` int(2) NOT NULL,
  `status` int(2) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expenditure_category`
--

INSERT INTO `expenditure_category` (`exp_catg_id`, `exp_category`, `exp_catg_shortname`, `is_bank_person`, `status`) VALUES
(1, 'Cash', 'NONE', 1, 1),
(2, 'Gen Expense', '', 0, 1),
(3, 'Banks', '', 2, 1),
(4, 'Loans', '', 5, 1),
(5, 'Directors', 'Direct', 0, 1),
(6, 'Salaries', 'SAL', 0, 1),
(7, 'Hostel', 'HOST', 0, 1),
(8, 'Advances', 'Adv', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exp_table_amount`
--

CREATE TABLE `exp_table_amount` (
  `id` int(11) NOT NULL,
  `given_by` int(15) DEFAULT NULL,
  `amount` double(10,2) DEFAULT NULL,
  `reason` text NOT NULL,
  `given_date` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `year_id` int(5) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `is_cancelled` int(2) NOT NULL DEFAULT '0',
  `cancel_permission` int(2) NOT NULL DEFAULT '0',
  `cancel_reason` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cancel_by` int(5) DEFAULT NULL,
  `update_date` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_due` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments_expenses_tractionshistory`
--

CREATE TABLE `payments_expenses_tractionshistory` (
  `id` int(25) NOT NULL,
  `transaction_typeid` int(4) NOT NULL,
  `tranpaytypidexp_type_id` int(6) NOT NULL,
  `exp_type_id` int(25) NOT NULL,
  `given_to` int(15) DEFAULT NULL,
  `given_by` int(15) NOT NULL,
  `branch_id` int(9) NOT NULL,
  `line_id` int(15) NOT NULL,
  `year_id` int(9) NOT NULL,
  `user_id` int(15) NOT NULL,
  `is_receive` int(2) NOT NULL DEFAULT '0',
  `exp_type_persion` int(2) NOT NULL,
  `bill_no` varchar(155) DEFAULT NULL,
  `transref_id` varchar(15) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `is_income` int(2) NOT NULL DEFAULT '1',
  `transaction_no` varchar(155) DEFAULT NULL,
  `exp_received_by` text,
  `description` text,
  `date_time` varchar(95) DEFAULT NULL,
  `is_cancel` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_discount`
--

CREATE TABLE `payment_discount` (
  `conces_id` int(25) NOT NULL,
  `customer_id` int(25) NOT NULL,
  `line_id` int(15) NOT NULL,
  `branch_id` int(9) NOT NULL,
  `year_id` int(10) NOT NULL,
  `borrow_id` int(10) NOT NULL,
  `cones_amt` decimal(10,2) NOT NULL,
  `conces_date` varchar(20) NOT NULL,
  `user_id` int(9) NOT NULL,
  `reason` text NOT NULL,
  `is_chit` int(2) NOT NULL DEFAULT '1',
  `paid_week_months` text NOT NULL,
  `paid_amts` text NOT NULL,
  `is_cancel` int(2) NOT NULL,
  `cancel_date` varchar(50) DEFAULT NULL,
  `cancel_reason` text,
  `cancel_by` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `pay_type_id` int(15) NOT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  `status` int(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_type`
--

INSERT INTO `payment_type` (`pay_type_id`, `pay_name`, `status`) VALUES
(1, 'Cash', 1),
(2, 'Upi', 1),
(3, 'Draft', 1),
(4, 'Cheque', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type_details`
--

CREATE TABLE `transaction_type_details` (
  `transaction_typeid` int(6) NOT NULL,
  `transaction_name` varchar(50) DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `date_time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_type_details`
--

INSERT INTO `transaction_type_details` (`transaction_typeid`, `transaction_name`, `status`, `date_time`) VALUES
(1, 'Customer Colc', 1, NULL),
(2, 'Student Other Fees', 1, NULL),
(3, 'School Other Amount', 1, NULL),
(4, 'Loans', 1, NULL),
(5, 'Expenditure', 1, NULL),
(6, 'Banks', 1, NULL),
(7, 'EmpusrGivamts', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `upload_academic_certificates`
--

CREATE TABLE `upload_academic_certificates` (
  `id` int(25) NOT NULL,
  `event_title` varchar(50) DEFAULT NULL,
  `activity_category` varchar(50) DEFAULT NULL,
  `issuing_organization` varchar(50) DEFAULT NULL,
  `date_of_issue` varchar(50) DEFAULT NULL,
  `enter_date` varchar(50) DEFAULT NULL,
  `frmuser_id` int(15) NOT NULL,
  `assigned_touser_id` int(15) NOT NULL,
  `assigned_by` int(15) NOT NULL,
  `assigned_date` varchar(50) DEFAULT NULL,
  `approval_status` int(2) NOT NULL,
  `approval_by` int(15) NOT NULL,
  `approval_date` varchar(50) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `is_delete` int(2) NOT NULL,
  `delete_by` int(6) NOT NULL,
  `delete_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` int(15) NOT NULL,
  `user_name` varchar(200) DEFAULT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `aadhar_no` varchar(20) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `sms_mobile` text NOT NULL,
  `address` text,
  `email` varchar(100) DEFAULT NULL,
  `user_type_id` int(10) DEFAULT NULL,
  `branch_id` int(10) DEFAULT NULL,
  `org_id` int(10) DEFAULT NULL,
  `assign_branch_ids` varchar(100) DEFAULT NULL,
  `assign_org_ids` text,
  `user_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_password` tinytext,
  `user_status` varchar(200) DEFAULT NULL,
  `is_cashier` int(2) NOT NULL DEFAULT '0',
  `staff_id` int(4) NOT NULL,
  `assign_course_ids` text,
  `assign_line_ids` text,
  `empuser_ids` text,
  `biometric_campus_ids` text,
  `user_type` varchar(25) DEFAULT NULL,
  `expire_time` varchar(100) DEFAULT NULL,
  `user_log_permiss` varchar(15) DEFAULT NULL,
  `data_permission_enddate` varchar(50) DEFAULT NULL,
  `is_admin` int(2) NOT NULL DEFAULT '0',
  `user_pages` text,
  `user_default_controller` varchar(50) DEFAULT NULL,
  `user_default_page` varchar(500) DEFAULT NULL,
  `user_menus` text,
  `permission_levels` text,
  `insert_by` int(9) NOT NULL,
  `insert_date` varchar(95) DEFAULT NULL,
  `update_by` int(9) NOT NULL,
  `update_date` varchar(95) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `user_name`, `full_name`, `gender`, `aadhar_no`, `mobile`, `sms_mobile`, `address`, `email`, `user_type_id`, `branch_id`, `org_id`, `assign_branch_ids`, `assign_org_ids`, `user_create_date`, `user_password`, `user_status`, `is_cashier`, `staff_id`, `assign_course_ids`, `assign_line_ids`, `empuser_ids`, `biometric_campus_ids`, `user_type`, `expire_time`, `user_log_permiss`, `data_permission_enddate`, `is_admin`, `user_pages`, `user_default_controller`, `user_default_page`, `user_menus`, `permission_levels`, `insert_by`, `insert_date`, `update_by`, `update_date`) VALUES
(1, 'admin', 'satya gowri', '', '', '', '', '', '', 1, 0, 0, '', '', '2026-06-14 21:04:15', '369369', '1', 0, 0, '', '', '', '', 'admin', NULL, NULL, '', 1, '{\"pages\":{\"22\":{\"p\":\"dailyevent_certificateslist\",\"page\":\"dailyevent_certificateslist.php\",\"is_excel\":\"0\",\"component_id\":\"22\",\"mparent_id\":\"1\",\"sparent_id\":0},\"26\":{\"p\":\"dailyevent_certificates_approvedlist\",\"page\":\"dailyevent_certificates_approvedlist.php\",\"is_excel\":\"0\",\"component_id\":\"26\",\"mparent_id\":\"1\",\"sparent_id\":0},\"8\":{\"p\":\"user_details\",\"page\":\"userdts.php\",\"is_excel\":\"0\",\"component_id\":\"8\",\"mparent_id\":\"7\",\"sparent_id\":0},\"17\":{\"p\":\"user_rolldt\",\"page\":\"user_rolldts.php\",\"is_excel\":\"0\",\"component_id\":\"17\",\"mparent_id\":\"7\",\"sparent_id\":0},\"18\":{\"p\":\"settings\",\"page\":\"settings.php\",\"is_excel\":\"0\",\"component_id\":\"18\",\"mparent_id\":\"7\",\"sparent_id\":0},\"29\":{\"p\":\"logout\",\"page\":\"logout.php\",\"is_excel\":\"0\",\"component_id\":\"29\",\"mparent_id\":\"7\",\"sparent_id\":0},\"31\":{\"p\":\"success_pg\",\"page\":\"success_pg.php\",\"is_excel\":\"0\",\"component_id\":\"31\",\"mparent_id\":\"7\",\"sparent_id\":0}},\"defalut_page\":\"dailyevent_certificates.php\"}', 'dashboard', 'dailyevent_certificates.php', '{\"main_menu\":[{\"menu_name\":\"Home\",\"menu_id\":\"1\",\"logo_name\":\"fas fa-home me-2\",\"firstsub\":[{\"firstmenu_id\":\"22\",\"firstmenu_name\":\"Daily Event Certificates\",\"controller_name\":\"dailyevent_certificateslist\",\"page_name\":\"dailyevent_certificateslist.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"26\",\"firstmenu_name\":\"Approved\\/Disapproved List\",\"controller_name\":\"dailyevent_certificates_approvedlist\",\"page_name\":\"dailyevent_certificates_approvedlist.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0}],\"ismenuvisable\":1},{\"menu_name\":\"Users\",\"menu_id\":\"7\",\"logo_name\":\"fas fa-users-cog me-2\",\"firstsub\":[{\"firstmenu_id\":\"8\",\"firstmenu_name\":\"Users\",\"controller_name\":\"user_details\",\"page_name\":\"userdts.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"17\",\"firstmenu_name\":\"Roll Mapping\",\"controller_name\":\"user_rolldt\",\"page_name\":\"user_rolldts.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"18\",\"firstmenu_name\":\"Settings\",\"controller_name\":\"settings\",\"page_name\":\"settings.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"0\",\"is_default_check\":\"1\",\"ischecked\":true,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"29\",\"firstmenu_name\":\"Logout\",\"controller_name\":\"logout\",\"page_name\":\"logout.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"0\",\"is_default_check\":\"1\",\"ischecked\":true,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"31\",\"firstmenu_name\":\"Success Page\",\"controller_name\":\"success_pg\",\"page_name\":\"success_pg.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"0\",\"is_default_check\":\"1\",\"ischecked\":true,\"is_havescnd_sub\":0}],\"ismenuvisable\":1}]}', '{\"data_permisdts\":{\"is_havedata_permission\":\"\",\"datapermis_validdte\":\"\",\"mobile_permission\":null,\"studentaddress_permission\":null,\"prevschool_detail_permission\":null},\"datapermisvarbles\":\"\",\"is_feepaydate_permission\":\"\",\"paydate_permis_validdte\":\"\",\"is_concession_permission\":\"\",\"concession_permis_validdte\":\"\",\"is_changeborrowdate_permission\":\"\",\"changeborrowdate_permis_validdte\":\"\",\"is_expensehave_directpermission\":0,\"expenpermission_permis_validdte\":\"\"}', 0, NULL, 1, '14-06-2026 16:45:40'),
(2, 'satyaadmin', 'santhosh', '', '', '', '', '', '', 1, 0, 0, '', '', '2026-06-14 21:02:16', '123456', '1', 0, 0, '', '', '', '', 'admin', NULL, NULL, '', 0, '{\"pages\":{\"8\":{\"p\":\"user_details\",\"page\":\"userdts.php\",\"is_excel\":\"0\",\"component_id\":\"8\",\"mparent_id\":\"7\",\"sparent_id\":0},\"18\":{\"p\":\"settings\",\"page\":\"settings.php\",\"is_excel\":\"0\",\"component_id\":\"18\",\"mparent_id\":\"7\",\"sparent_id\":0},\"22\":{\"p\":\"dailyevent_certificateslist\",\"page\":\"dailyevent_certificateslist.php\",\"is_excel\":\"0\",\"component_id\":\"22\",\"mparent_id\":\"1\",\"sparent_id\":0},\"25\":{\"p\":\"dailyevent_staff_assignedlist\",\"page\":\"dailyevent_staff_assignedlist.php\",\"is_excel\":\"0\",\"component_id\":\"25\",\"mparent_id\":\"1\",\"sparent_id\":0},\"26\":{\"p\":\"dailyevent_certificates_approvedlist\",\"page\":\"dailyevent_certificates_approvedlist.php\",\"is_excel\":\"0\",\"component_id\":\"26\",\"mparent_id\":\"1\",\"sparent_id\":0},\"29\":{\"p\":\"logout\",\"page\":\"logout.php\",\"is_excel\":\"0\",\"component_id\":\"29\",\"mparent_id\":\"7\",\"sparent_id\":0}},\"defalut_page\":\"dailyevent_certificateslist.php\"}', NULL, 'dailyevent_certificateslist.php', '{\"main_menu\":[{\"menu_name\":\"Home\",\"menu_id\":\"1\",\"logo_name\":\"fas fa-home me-2\",\"firstsub\":[{\"firstmenu_id\":\"22\",\"firstmenu_name\":\"Daily Event Certificates\",\"controller_name\":\"dailyevent_certificateslist\",\"page_name\":\"dailyevent_certificateslist.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"25\",\"firstmenu_name\":\"Assigned List\",\"controller_name\":\"dailyevent_staff_assignedlist\",\"page_name\":\"dailyevent_staff_assignedlist.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"26\",\"firstmenu_name\":\"Approved\\/Disapproved List\",\"controller_name\":\"dailyevent_certificates_approvedlist\",\"page_name\":\"dailyevent_certificates_approvedlist.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0}],\"ismenuvisable\":1},{\"menu_name\":\"Users\",\"menu_id\":\"7\",\"logo_name\":\"fas fa-users-cog me-2\",\"firstsub\":[{\"firstmenu_id\":\"8\",\"firstmenu_name\":\"Users\",\"controller_name\":\"user_details\",\"page_name\":\"userdts.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"1\",\"is_default_check\":\"0\",\"ischecked\":false,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"18\",\"firstmenu_name\":\"Settings\",\"controller_name\":\"settings\",\"page_name\":\"settings.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"0\",\"is_default_check\":\"1\",\"ischecked\":true,\"is_havescnd_sub\":0},{\"firstmenu_id\":\"29\",\"firstmenu_name\":\"Logout\",\"controller_name\":\"logout\",\"page_name\":\"logout.php\",\"first_subveiw_order\":\"0\",\"is_excel\":\"0\",\"is_menu\":\"0\",\"is_default_check\":\"1\",\"ischecked\":true,\"is_havescnd_sub\":0}],\"ismenuvisable\":1}]}', '{\"data_permisdts\":{\"is_havedata_permission\":\"\",\"datapermis_validdte\":\"\",\"mobile_permission\":null,\"studentaddress_permission\":null,\"prevschool_detail_permission\":null},\"datapermisvarbles\":\"\",\"is_feepaydate_permission\":\"\",\"paydate_permis_validdte\":\"\",\"is_concession_permission\":\"\",\"concession_permis_validdte\":\"\",\"is_changeborrowdate_permission\":\"\",\"changeborrowdate_permis_validdte\":\"\",\"is_expensehave_directpermission\":0,\"expenpermission_permis_validdte\":\"\"}', 1, '15-06-2026 02:32:04', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_details`
--

CREATE TABLE `user_login_details` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `login_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `user_type_id` int(11) NOT NULL,
  `type_name` varchar(100) DEFAULT NULL,
  `type_short_name` varchar(8) DEFAULT NULL,
  `default_assign_rollids` text,
  `status` int(11) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`user_type_id`, `type_name`, `type_short_name`, `default_assign_rollids`, `status`) VALUES
(1, 'admin', '', '{\n    \"usermenuids\": [8, 18, 22, 25, 26, 29],\n    \"defaultmenuid\": 22\n  }', 1),
(2, 'faculty', '', '{\n    \"usermenuids\": [18, 25, 26, 29],\n    \"defaultmenuid\": 25\n  }', 1),
(3, 'student', '', '{\n    \"usermenuids\": [18, 21, 26, 29],\n    \"defaultmenuid\": 21\n  }', 1);

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `year_id` int(11) NOT NULL,
  `year` varchar(30) DEFAULT NULL,
  `start_date` varchar(30) DEFAULT NULL,
  `end_date` varchar(30) DEFAULT NULL,
  `current_year` int(11) DEFAULT NULL,
  `is_promote` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`year_id`, `year`, `start_date`, `end_date`, `current_year`, `is_promote`) VALUES
(1, '2025-2026', '01-04-2025', '31-05-2026', 2025, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `branch_names`
--
ALTER TABLE `branch_names`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `branch_sms_users`
--
ALTER TABLE `branch_sms_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`component_id`);

--
-- Indexes for table `empuser_attendance_list`
--
ALTER TABLE `empuser_attendance_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empuser_details`
--
ALTER TABLE `empuser_details`
  ADD PRIMARY KEY (`emp_user_id`);

--
-- Indexes for table `expenditure_category`
--
ALTER TABLE `expenditure_category`
  ADD PRIMARY KEY (`exp_catg_id`);

--
-- Indexes for table `exp_table_amount`
--
ALTER TABLE `exp_table_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments_expenses_tractionshistory`
--
ALTER TABLE `payments_expenses_tractionshistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_discount`
--
ALTER TABLE `payment_discount`
  ADD PRIMARY KEY (`conces_id`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`pay_type_id`);

--
-- Indexes for table `transaction_type_details`
--
ALTER TABLE `transaction_type_details`
  ADD PRIMARY KEY (`transaction_typeid`);

--
-- Indexes for table `upload_academic_certificates`
--
ALTER TABLE `upload_academic_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_login_details`
--
ALTER TABLE `user_login_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`user_type_id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`year_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `branch_names`
--
ALTER TABLE `branch_names`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `branch_sms_users`
--
ALTER TABLE `branch_sms_users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `component_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `empuser_attendance_list`
--
ALTER TABLE `empuser_attendance_list`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `empuser_details`
--
ALTER TABLE `empuser_details`
  MODIFY `emp_user_id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenditure_category`
--
ALTER TABLE `expenditure_category`
  MODIFY `exp_catg_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `exp_table_amount`
--
ALTER TABLE `exp_table_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments_expenses_tractionshistory`
--
ALTER TABLE `payments_expenses_tractionshistory`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_discount`
--
ALTER TABLE `payment_discount`
  MODIFY `conces_id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `pay_type_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `transaction_type_details`
--
ALTER TABLE `transaction_type_details`
  MODIFY `transaction_typeid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `upload_academic_certificates`
--
ALTER TABLE `upload_academic_certificates`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_login_details`
--
ALTER TABLE `user_login_details`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `year`
--
ALTER TABLE `year`
  MODIFY `year_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
