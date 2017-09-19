-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2017 at 06:48 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muscle_garage`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `role` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`id`, `email`, `password`, `is_active`, `role`) VALUES
(1, 'admin@mg.com', '0192023a7bbd73250516f069df18b500', 1, 1),
(2, 'ram@mg.com', '6a557ed1005dddd940595b8fc6ed47b2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `resident_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`id`, `user_id`, `name`, `relationship`, `mobile_no`, `resident_no`) VALUES
(1, 1, 'LLLLLL', '', '777777777777', ''),
(2, 2, 'shdkjsahdkjh', '', '97778979879', ''),
(3, 3, 'sdsadsa', '', '56456456', '');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `duration` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `name`, `description`, `duration`, `amount`) VALUES
(1, 'Yearly_Men_Full', 'All Machines With Cardio For 12 Months', 365, 1500.5),
(2, 'Women_Yearly', 'All Machines For 12 Months', 345, 1800.2),
(3, 'Half_Yearly_Men_Full', 'All Machines With Cardio For 6 Months', 180, 2222),
(4, 'aaaa', 'sadasdsad', 1, 10),
(5, 'bbbb', 'zsdsd', 2, 20),
(7, 'dddd', 'zsdsd', 4, 40),
(9, 'dddd', 'zsdsd', 4, 40),
(10, 'cccc', 'sadasdsad', 3, 30),
(11, 'dddd', 'zsdsd', 6, 40),
(12, 'eeeee', 'sadasdsad', 5, 50),
(13, 'fffff', 'zsdsd', 5, 50),
(14, 'PPPPPPP', 'zfsdfdsfsd', 55, 555),
(15, 'zzzzzzzzzzzzzzzzzz', 'zzzzz', 45, 455),
(16, 'Yearly_cardio_1', 'shdghasdhasg sadjhgashgd', 15, 900),
(17, 'ABC 001', 'skdhjakshd sakdjsjkahd', 80, 800);

-- --------------------------------------------------------

--
-- Table structure for table `membership_history`
--

CREATE TABLE `membership_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `membership_no` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_date` date NOT NULL,
  `paid` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership_history`
--

INSERT INTO `membership_history` (`id`, `user_id`, `membership_id`, `membership_no`, `amount`, `start_date`, `end_date`, `created_date`, `paid`) VALUES
(1, 1, 1, 'D900RPJHAW7', 1500.5, '2017-09-18', '2018-09-18', '0000-00-00', 0),
(2, 2, 1, 'EHH1S83FP0D', 1500.5, '2017-09-18', '2018-09-18', '0000-00-00', 0),
(3, 3, 1, 'WUFMOEN3I1P', 1500.5, '2017-09-18', '2018-09-18', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mh_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `user_id`, `mh_id`, `amount`, `paid_date`) VALUES
(1, 1, 1, '300.00', '2017-09-18 20:25:38'),
(2, 1, 1, '10.00', '2017-09-19 04:49:10'),
(3, 1, 1, '10.00', '2017-09-19 04:52:20'),
(4, 1, 1, '10.00', '2017-09-19 04:52:58'),
(5, 1, 1, '20.00', '2017-09-19 04:55:39'),
(6, 1, 1, '5.00', '2017-09-19 04:58:23');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `bmi` varchar(100) NOT NULL,
  `goal` varchar(255) NOT NULL,
  `specification` text NOT NULL,
  `precaution` text NOT NULL,
  `advice` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `user_id`, `height`, `weight`, `bmi`, `goal`, `specification`, `precaution`, `advice`, `start_date`, `end_date`) VALUES
(1, 1, '165', '78', '90', 'G11111111111111222', 'sadsdhds\nasdmkjashdkhsad', 'akjhahsd\nasdkjsadkjhsd\nsdasjd', 'sadksjkfdfhjksjd\nsadjasdjkahsdjk', '2017-09-01', '2017-09-30'),
(2, 1, '175', '79', '78', 'Goal 111111111111', 'jadahdjhsadh\nsadksahdGoal 111111111111', 'xzmcxzmncsahd\nakjhaDS\nDLKJSAHD', 'SDSAKHDA\nSADKJASHD\nASDSAKJHD', '2017-09-01', '2017-09-30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `age` tinyint(4) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `dob` date NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `resident_no` varchar(20) NOT NULL,
  `address` tinytext NOT NULL,
  `referred_by` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `facebook`, `age`, `sex`, `dob`, `mobile_no`, `resident_no`, `address`, `referred_by`, `created_on`, `edited_on`) VALUES
(1, 'Ramakrishnan K', 'asasas@aa.com', 'asasas@aa.com', 32, 'F', '1985-09-06', '111111111111111', '222222222222222', 'sdsadasdsa\nsadsad\nsadsa', 'ssfsfsf', '2017-09-18 19:22:10', '2017-09-18 19:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_membership`
--

CREATE TABLE `user_membership` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mh_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_membership`
--

INSERT INTO `user_membership` (`id`, `user_id`, `mh_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 3, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_history`
--
ALTER TABLE `membership_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_membership`
--
ALTER TABLE `user_membership`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `membership_history`
--
ALTER TABLE `membership_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_membership`
--
ALTER TABLE `user_membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
