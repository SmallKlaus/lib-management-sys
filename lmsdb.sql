-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2022 at 12:22 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`admin_id`, `admin_email`, `admin_password`) VALUES
(1, 'm.akhayat1@gmail.com', 'rootuser0000');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `library_name` varchar(100) NOT NULL,
  `building_number` int(11) NOT NULL,
  `library_address` varchar(200) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email_contact` varchar(100) NOT NULL,
  `membership_fee` int(11) NOT NULL,
  `loan_peruser` int(11) NOT NULL,
  `loan_daylimit` int(11) NOT NULL,
  `fine` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`library_name`, `building_number`, `library_address`, `contact_number`, `email_contact`, `membership_fee`, `loan_peruser`, `loan_daylimit`, `fine`, `currency`, `open`, `close`) VALUES
('Al Assil', 2033, 'France, Marseille, Boulevard Saint Pierro, 5th Avenue, 201.', '+65465464563', 'assil.lib@lib.com', 3, 10, 10, 2, 'Dollars', '09:30:00', '22:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `announcements_table`
--

CREATE TABLE `announcements_table` (
  `announce_id` int(11) NOT NULL,
  `announce_title` varchar(100) NOT NULL,
  `announce_text` text NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `announcements_table`
--

INSERT INTO `announcements_table` (`announce_id`, `announce_title`, `announce_text`, `created_on`) VALUES
(1, 'First announcement of the year.', 'fdsflfhlsdajfsdf;sdajf\r\ndfsdfkf;skjf fasd;fjkasd;kfj df\r\ndfdsa f;jkf;sf asdfkdf; j\r\n fdkjf;dsfj ;fkj dasf\r\ndfjd; fkdjf; askfj;\r\nf d;sfj;asdjf;dasjkf \r\n d;fkj;a f;akjdfdf;asdjfasklfj;adjkf;dlasjkf;skladajksl;ajf;kaslf;asljf;s', '2022-10-03 00:35:46'),
(2, 'Second announcement of the year', 'We apologize for any inconvenience in advance dear members.\r\nThe library will be close for the next week due to construction work.\r\nMembers won\'t have to worry about late returns for the period.\r\nTake care.', '0000-00-00 00:00:00'),
(4, 'Closed Next Week', 'We will be close next week from ... to ....', '2022-10-05 00:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `authors_table`
--

CREATE TABLE `authors_table` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authors_table`
--

INSERT INTO `authors_table` (`author_id`, `author_name`, `created_on`, `updated_on`) VALUES
(3, 'Jule Verne', '2022-09-20 15:01:43', '2022-09-20 15:01:43'),
(5, 'Stephen King', '2022-09-21 15:01:54', '2022-09-21 15:01:54'),
(6, 'Charles Dickens', '2022-09-21 15:02:05', '2022-09-21 15:02:05'),
(7, 'Victor Hugo', '2022-09-21 15:38:09', '2022-09-21 15:38:09'),
(9, 'Marcel Proust', '2022-09-29 20:09:07', '2022-09-29 20:09:07'),
(10, 'Colleen Hoover', '2022-10-05 17:34:43', '2022-10-05 17:34:43'),
(11, 'Taylor Jenkins Reid', '2022-10-05 17:35:02', '2022-10-05 17:35:02'),
(12, 'James Clear', '2022-10-05 17:35:13', '2022-10-05 17:35:13'),
(13, 'Alex Michaelides', '2022-10-05 17:35:32', '2022-10-05 17:35:32'),
(14, 'J.R.R Tolkien', '2022-10-05 17:35:45', '2022-10-05 17:35:45'),
(15, 'J.K Rowling', '2022-10-05 17:36:00', '2022-10-05 17:36:00'),
(16, 'Harper Lee', '2022-10-05 17:36:11', '2022-10-05 17:36:11'),
(17, 'Eric Carle', '2022-10-05 17:36:23', '2022-10-05 17:36:23'),
(18, 'Robert Greene', '2022-10-05 17:36:40', '2022-10-05 17:36:40'),
(19, 'Oscar Wilde', '2022-10-05 20:54:20', '2022-10-05 20:54:20'),
(20, 'Edwidge Danticat', '2022-10-05 20:54:39', '2022-10-05 20:54:39'),
(21, 'Ernest Hemingway', '2022-10-05 20:54:52', '2022-10-05 20:54:52'),
(22, 'Saul Bellow', '2022-10-05 20:55:05', '2022-10-05 20:55:05'),
(23, 'Franz Kafka', '2022-10-05 20:55:14', '2022-10-05 20:55:14'),
(24, 'Marguerite Duras', '2022-10-05 20:55:23', '2022-10-05 20:55:23'),
(25, 'Kingsley Amis', '2022-10-05 20:55:34', '2022-10-05 20:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `books_authors`
--

CREATE TABLE `books_authors` (
  `book_id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books_authors`
--

INSERT INTO `books_authors` (`book_id`, `author_name`) VALUES
(9, 'Marcel Proust'),
(10, 'Marcel Proust'),
(13, 'Colleen Hoover'),
(14, 'Colleen Hoover'),
(15, 'Taylor Jenkins Reid'),
(16, 'James Clear');

-- --------------------------------------------------------

--
-- Table structure for table `books_genres`
--

CREATE TABLE `books_genres` (
  `book_id` int(11) NOT NULL,
  `genre_label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books_genres`
--

INSERT INTO `books_genres` (`book_id`, `genre_label`) VALUES
(9, 'Modern Literature'),
(9, 'Philosophical Fiction'),
(9, 'Social Novel'),
(9, 'Fictional Autobiography'),
(10, 'Modern Literature'),
(10, 'Philosophical Fiction'),
(10, 'Social Novel'),
(10, 'Fictional Autobiography'),
(13, 'Novel'),
(13, 'Romance'),
(13, 'Fiction'),
(13, 'Contemporary'),
(14, 'Novel'),
(14, 'Romance'),
(14, 'Thriller'),
(14, 'Fiction'),
(14, 'Suspense'),
(15, 'Historical Fiction'),
(15, 'Novel'),
(15, 'Romance'),
(15, 'Psychological'),
(16, 'Self-Help');

-- --------------------------------------------------------

--
-- Table structure for table `books_table`
--

CREATE TABLE `books_table` (
  `book_isbn` varchar(13) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `book_rack` varchar(3) NOT NULL,
  `book_price` float(11,2) NOT NULL,
  `book_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books_table`
--

INSERT INTO `books_table` (`book_isbn`, `book_id`, `book_name`, `book_rack`, `book_price`, `book_stock`) VALUES
('9780330375764', 10, 'In Search of Lost Time', 'C2', 20.55, 98),
('9781501110368', 13, 'It Ends with Us', 'A1', 30.99, 149),
('9788711986387', 14, 'Verity', 'A1', 25.49, 99),
('9782811235949', 15, 'The Seven Husbands of Evelyn Hugo', 'A2', 14.99, 199),
('9783442178582', 16, 'Atomic Habits: An Easy & Proven Way to Build Good Habit & Break Bad Ones', 'A4', 11.99, 44);

-- --------------------------------------------------------

--
-- Table structure for table `genres_table`
--

CREATE TABLE `genres_table` (
  `genre_id` int(11) NOT NULL,
  `genre_label` varchar(100) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genres_table`
--

INSERT INTO `genres_table` (`genre_id`, `genre_label`, `created_on`, `updated_on`) VALUES
(1, 'Action', '2022-09-21 15:01:15', '2022-09-21 15:01:15'),
(2, 'Comedy', '2022-09-21 15:01:21', '2022-09-21 15:01:21'),
(3, 'Epic', '2022-09-21 15:26:51', '2022-09-21 15:26:51'),
(4, 'Historical Fiction', '2022-09-21 15:26:28', '2022-09-21 15:27:08'),
(5, 'Novel', '2022-09-21 15:26:56', '2022-09-21 15:26:56'),
(6, 'Romance', '2022-09-20 14:49:40', '2022-09-20 14:49:40'),
(7, 'Science Fiction', '2022-09-21 15:01:02', '2022-09-21 15:01:02'),
(8, 'Thriller', '2022-09-21 15:00:49', '2022-09-21 15:00:49'),
(9, 'Tragedy', '2022-09-21 15:26:33', '2022-09-21 15:26:33'),
(10, 'Modern Literature', '2022-09-29 20:08:06', '2022-09-29 20:08:06'),
(11, 'Philosophical Fiction', '2022-09-29 20:08:19', '2022-09-29 20:08:19'),
(12, 'Social Novel', '2022-09-29 20:08:28', '2022-09-29 20:08:28'),
(13, 'Fictional Autobiography', '2022-09-29 20:08:42', '2022-09-29 20:08:42'),
(14, 'Self-Help', '2022-10-05 17:36:56', '2022-10-05 17:36:56'),
(15, 'Picture Book', '2022-10-05 17:37:11', '2022-10-05 17:37:11'),
(16, 'Children Literature', '2022-10-05 17:37:40', '2022-10-05 17:37:40'),
(17, 'Fiction', '2022-10-05 17:37:51', '2022-10-05 17:37:51'),
(18, 'Young Adult Fiction', '2022-10-05 17:38:11', '2022-10-05 17:38:11'),
(19, 'Fantasy Fiction', '2022-10-05 17:38:17', '2022-10-05 17:38:17'),
(20, 'High Fantasy', '2022-10-05 17:38:53', '2022-10-05 17:38:53'),
(21, 'Adventure', '2022-10-05 17:38:59', '2022-10-05 17:38:59'),
(22, 'Heroic', '2022-10-05 17:39:12', '2022-10-05 17:39:12'),
(23, 'Mystery', '2022-10-05 17:39:32', '2022-10-05 17:39:32'),
(24, 'Psychological', '2022-10-05 17:39:42', '2022-10-05 17:39:42'),
(25, 'Suspense', '2022-10-05 17:39:48', '2022-10-05 17:39:48'),
(26, 'Crime Fiction', '2022-10-05 17:39:59', '2022-10-05 17:39:59'),
(27, 'Contemporary', '2022-10-05 17:40:21', '2022-10-05 17:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `issues_table`
--

CREATE TABLE `issues_table` (
  `user_id` varchar(13) NOT NULL,
  `book_isbn` varchar(13) NOT NULL,
  `created_on` datetime NOT NULL,
  `billed_late_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `issues_table`
--

INSERT INTO `issues_table` (`user_id`, `book_isbn`, `created_on`, `billed_late_days`) VALUES
('6335e5b432e41', '9780330375764', '2022-10-05 19:10:15', 65),
('6335e5b432e41', '9781501110368', '2022-10-05 19:10:45', 65),
('6335e5b432e41', '9782811235949', '2022-10-05 19:10:49', 65),
('6335e5b432e41', '9783442178582', '2022-10-05 19:11:00', 65),
('6335e5b432e41', '9788711986387', '2022-10-05 19:11:04', 65),
('63384d6a0ed55', '9780330375764', '2022-10-05 01:02:58', 66);

-- --------------------------------------------------------

--
-- Table structure for table `locations_table`
--

CREATE TABLE `locations_table` (
  `location_name` varchar(3) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations_table`
--

INSERT INTO `locations_table` (`location_name`, `created_on`, `updated_on`) VALUES
('A1', '2022-09-20 15:49:47', '2022-09-20 15:50:57'),
('A2', '2022-09-20 15:40:08', '2022-09-20 15:40:08'),
('A3', '2022-09-20 15:49:53', '2022-09-20 15:49:53'),
('A4', '2022-09-20 15:49:58', '2022-09-20 15:49:58'),
('B1', '2022-09-20 15:50:02', '2022-09-20 15:50:02'),
('B2', '2022-09-20 15:50:06', '2022-09-20 15:50:06'),
('B3', '2022-09-20 15:50:12', '2022-09-20 15:50:12'),
('B4', '2022-09-20 15:50:19', '2022-09-20 15:50:19'),
('C2', '2022-09-20 15:50:32', '2022-09-20 15:50:32'),
('C4', '2022-09-20 15:50:43', '2022-09-20 15:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_id` varchar(13) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_address` varchar(200) NOT NULL,
  `confirmation_status` char(1) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `confirmation_code` varchar(100) NOT NULL,
  `user_bill` float(11,2) NOT NULL,
  `user_borrowed` int(11) NOT NULL,
  `month_start` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `user_email`, `user_password`, `user_name`, `user_address`, `confirmation_status`, `created_on`, `updated_on`, `contact_number`, `confirmation_code`, `user_bill`, `user_borrowed`, `month_start`) VALUES
('6335e5b432e41', 'oloka2018@gmail.com', 'Amine1Amine-', 'Amine2001', 'Casablanca, BERNOUSSI, IMM 8, N 6', 'Y', '2022-09-01 20:36:36', '2022-09-29 20:47:35', '+212700191025', 'd63dfdbe3e4ca739b8c6641f5357672b', 683.00, 5, '2022-12-15 20:36:36'),
('63384d6a0ed55', 'm.akhayat01@gmail.com', 'Amine1Amine-', 'SantaGo96', 'Casablanca, BERNOUSSI, IMM 8, N 6', 'Y', '2022-09-01 16:23:38', '2022-10-01 18:01:41', '+212700191025', '285010404d27f941928755f30b7b81b1', 165.00, 1, '2022-12-15 16:23:38'),
('633cbe3dbd2c6', 'o.akhayat1@gmail.com', 'Oumaima1-', 'Oumaima96', '', 'Y', '2022-10-05 01:14:05', '2022-10-05 01:14:05', '', '573a70587a66d583d707c97082bdef96', 30.00, 0, '2022-12-14 01:14:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`building_number`);

--
-- Indexes for table `announcements_table`
--
ALTER TABLE `announcements_table`
  ADD PRIMARY KEY (`announce_id`);

--
-- Indexes for table `authors_table`
--
ALTER TABLE `authors_table`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books_table`
--
ALTER TABLE `books_table`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `genres_table`
--
ALTER TABLE `genres_table`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `issues_table`
--
ALTER TABLE `issues_table`
  ADD PRIMARY KEY (`user_id`,`book_isbn`);

--
-- Indexes for table `locations_table`
--
ALTER TABLE `locations_table`
  ADD PRIMARY KEY (`location_name`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements_table`
--
ALTER TABLE `announcements_table`
  MODIFY `announce_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `authors_table`
--
ALTER TABLE `authors_table`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `books_table`
--
ALTER TABLE `books_table`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `genres_table`
--
ALTER TABLE `genres_table`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
