-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2021 at 05:52 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bluegarden_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_type`
--

CREATE TABLE `additional_type` (
  `additional_id` int(32) NOT NULL,
  `additional_type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `additional_amount` float(10,2) NOT NULL,
  `status` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `additional_type`
--

INSERT INTO `additional_type` (`additional_id`, `additional_type`, `description`, `additional_amount`, `status`) VALUES
(1, 'Extra Bed', 'Extra Bed', 100.00, 1),
(2, 'Gym', 'Gym', 100.00, 1),
(3, 'Pool', 'Pool', 60.00, 1),
(4, 'Additional Guest', 'Additional Guest', 250.00, 1),
(5, 'Function Room Extra Hour', 'Function Room Extra Hour', 110.00, 1),
(6, 'Family Room Extra Hour', 'Family Room Extra Hour', 400.00, 1),
(7, 'VIP 1 Extra Hour', 'VIP 1 Extra Hour', 120.00, 1),
(8, 'VIP 2 ', 'VIP 2 ', 80.00, 1),
(9, 'Playhouse Inn AC Extra Hour', 'Playhouse Inn AC Extra Hour', 60.00, 1),
(10, 'Playhouse Inn OR Extra Hour', 'Playhouse Inn OR Extra Hour', 40.00, 1),
(11, 'Cottage', 'Cottage', 300.00, 1),
(12, 'Test Additional', 'Test Additional', 500.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(32) NOT NULL,
  `billing_id` int(32) NOT NULL,
  `original_capital` float(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `billing_id`, `original_capital`) VALUES
(55, 35322698, 1100.00),
(56, 35462026, 36000.00),
(57, 35462026, 39600.00),
(58, 36191441, 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `guest_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `addressline_1` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zipcode` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`guest_id`, `first_name`, `last_name`, `gender`, `contact_number`, `email`, `addressline_1`, `city`, `zipcode`) VALUES
(13533898, 'Rauf', 'Dimaampao', 'Male', '0947245073', '6933rauf@gmail.com', '41241', 'manila', 1001),
(13547882, 'Mean', 'Rendon', 'Male', '0947245073', 'meanrendon@gmail.com', '111', '555', 4444),
(13619106, 'Rauf', 'Dimaampao', 'Male', '0947245073', '6933rauf@gmail.com', '41241', 'manila', 1001);

-- --------------------------------------------------------

--
-- Table structure for table `guest_additional`
--

CREATE TABLE `guest_additional` (
  `id` int(32) NOT NULL,
  `reservation_id` int(32) NOT NULL,
  `additional_id` int(32) NOT NULL,
  `additional_type` varchar(255) NOT NULL,
  `additional_amount` float(32,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(32) NOT NULL,
  `billing_id` int(32) NOT NULL,
  `payed_capital` float(32,2) NOT NULL,
  `payment_date` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `billing_id`, `payed_capital`, `payment_date`) VALUES
(67, 35322698, 550.00, '2021-02-17 11:49:51.000000'),
(68, 35322698, 550.00, '2021-02-18 10:33:11.000000'),
(69, 35322698, 0.00, '2021-02-18 10:54:00.000000'),
(70, 35462026, 37800.00, '2021-02-18 11:04:33.000000'),
(71, 35462026, 37800.00, '2021-02-18 11:04:40.000000'),
(72, 35462026, 0.00, '2021-02-18 11:04:47.000000'),
(73, 36191441, 4000.00, '2021-02-18 11:51:49.000000');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_photo`
--

CREATE TABLE `receipt_photo` (
  `receipt_photo_id` int(32) NOT NULL,
  `reservation_id` int(32) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `upload_date` datetime(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receipt_photo`
--

INSERT INTO `receipt_photo` (`receipt_photo_id`, `reservation_id`, `photo`, `upload_date`) VALUES
(21, 61361723, '9461-green_Wallpaper.jpg', '2021-02-18 11:46:27.00000');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(32) NOT NULL,
  `guest_id` int(32) NOT NULL,
  `billing_id` int(32) NOT NULL,
  `reservation_date` datetime NOT NULL,
  `booking_reference` varchar(255) NOT NULL,
  `num_guest` int(32) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `expiration_date` datetime NOT NULL,
  `reservation_type` int(32) NOT NULL,
  `status` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `guest_id`, `billing_id`, `reservation_date`, `booking_reference`, `num_guest`, `checkin_date`, `checkout_date`, `expiration_date`, `reservation_type`, `status`) VALUES
(61353396, 13547882, 35462026, '2021-02-17 15:18:36', '8C0A23', 14, '2021-02-19', '2021-02-28', '2021-02-18 15:18:36', 0, 7),
(61354399, 13533898, 35322698, '2021-02-17 11:33:43', 'F9EC9E', 2, '2021-02-18', '2021-02-19', '2021-02-17 23:59:59', 0, 7),
(61361723, 13619106, 36191441, '2021-02-18 11:43:32', '2ECF2F', 3, '2021-02-20', '2021-02-22', '2021-02-19 11:43:32', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `roomtype_id` int(32) NOT NULL,
  `room_num` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `roomtype_id`, `room_num`, `status`) VALUES
(16, 43, 'FamilyRoom', 0),
(18, 44, 'FunctionRoomR2', 0),
(19, 44, 'FunctionRoomR3', 0),
(20, 44, 'FunctionRoomR4', 0),
(22, 44, 'FunctionRoomR5', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_reservation`
--

CREATE TABLE `room_reservation` (
  `room_reservation_id` int(32) NOT NULL,
  `reservation_id` int(32) NOT NULL,
  `room_id` int(32) NOT NULL,
  `roomtype_name` varchar(255) NOT NULL,
  `room_price` float(32,2) NOT NULL,
  `room_num` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_reservation`
--

INSERT INTO `room_reservation` (`room_reservation_id`, `reservation_id`, `room_id`, `roomtype_name`, `room_price`, `room_num`) VALUES
(83, 61354399, 17, 'Function Room', 1100.00, 'FunctionRoomR1'),
(84, 61353396, 16, 'Family Room', 4000.00, 'FamilyRoom'),
(85, 61353396, 18, 'Function Room', 1100.00, 'FunctionRoomR2'),
(86, 61353396, 19, 'Function Room', 1100.00, 'FunctionRoomR3'),
(87, 61353396, 20, 'Function Room', 1100.00, 'FunctionRoomR4'),
(88, 61353396, 22, 'Function Room', 1100.00, 'FunctionRoomR5'),
(89, 61361723, 16, 'Family Room', 4000.00, 'FamilyRoom');

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `roomtype_id` int(32) NOT NULL,
  `roomtype_name` varchar(255) NOT NULL,
  `roomtype_capacity` int(32) NOT NULL,
  `roomtype_price` int(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `roomtype_photo` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`roomtype_id`, `roomtype_name`, `roomtype_capacity`, `roomtype_price`, `description`, `roomtype_photo`, `status`) VALUES
(43, 'Family Room', 8, 4000, '3 Bedroom with living room', 'sample room-4221-opa.jpg', 1),
(44, 'Function Room', 2, 1100, 'Good for 2 people', 'sample room-4221-opa.jpg', 1),
(46, 'VIP ROOM 1', 5, 1200, 'Semi family room, good for 4-5 person', 'VIP ROOM 1-227-wallpaper_2.jpg', 1),
(48, 'Playhouse Inn Airconditioned Room', 2, 690, 'Good for 2 person.', 'sample room-4221-opa.jpg', 1),
(49, 'Playhouse Inn Ordinary Room', 2, 590, 'Good for 2 person', 'sample room-4221-opa.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_id` int(32) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `first_name`, `last_name`, `username`, `password`, `status`) VALUES
(20205126, 'Admin', 'Account', 'admin', '123', 0),
(202034992, 'klyde', 'francisco', 'klyde_francisco', 'test', 0),
(202096718, 'test', 'account', 'test', '123', 1),
(202134867, 'rauf', 'dimaampao', 'rauf_dimaampao', '123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_type`
--
ALTER TABLE `additional_type`
  ADD PRIMARY KEY (`additional_id`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `guest_additional`
--
ALTER TABLE `guest_additional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `receipt_photo`
--
ALTER TABLE `receipt_photo`
  ADD PRIMARY KEY (`receipt_photo_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `room_reservation`
--
ALTER TABLE `room_reservation`
  ADD PRIMARY KEY (`room_reservation_id`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`roomtype_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_type`
--
ALTER TABLE `additional_type`
  MODIFY `additional_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `guest_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99969722;

--
-- AUTO_INCREMENT for table `guest_additional`
--
ALTER TABLE `guest_additional`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `receipt_photo`
--
ALTER TABLE `receipt_photo`
  MODIFY `receipt_photo_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61361724;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `room_reservation`
--
ALTER TABLE `room_reservation`
  MODIFY `room_reservation_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `roomtype_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
