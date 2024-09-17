-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2024 at 03:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `videogames`
--

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `Country_Name` varchar(255) NOT NULL,
  `Country_Most_Played_Game_Id` int(11) DEFAULT NULL,
  `Country_Development_Companies` varchar(5000) DEFAULT NULL COMMENT 'CSV Format for array parsing',
  `Country_Most_Poplar_Genre` varchar(255) NOT NULL,
  `Country_Average_Age` double NOT NULL,
  `Country_Average_Internet_Speed` double NOT NULL,
  `Country_Language` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer`
--

CREATE TABLE `developer` (
  `Dev_Name` varchar(255) NOT NULL,
  `Dev_Founder` varchar(255) NOT NULL,
  `Dev_Headquarters` varchar(255) NOT NULL,
  `Dev_Type` varchar(255) NOT NULL,
  `Dev_Parent` int(11) NOT NULL,
  `Dev_Prog_Lang` int(11) NOT NULL,
  `Dev_Number_Games_Made` bigint(20) NOT NULL,
  `Dev_Founded_Date` date NOT NULL,
  `Dev_Number_Of_Employees` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dlc`
--

CREATE TABLE `dlc` (
  `DLC_Id` int(11) NOT NULL,
  `Game_Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Release_Date` date NOT NULL,
  `Price` double NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Total_Sales` bigint(20) NOT NULL,
  `Sales_Id` int(11) NOT NULL,
  `Revenue` double NOT NULL,
  `Hard_Copies_Sold` bigint(20) NOT NULL,
  `Digital_Copies_Sold` bigint(20) NOT NULL,
  `Highest_Reveue_Region` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `Game_Id` int(11) NOT NULL,
  `Game_Name` varchar(255) NOT NULL,
  `Game_Founder` varchar(255) NOT NULL,
  `Game_Release_Date` date NOT NULL,
  `Game_Country_Name` varchar(255) NOT NULL,
  `Game_Genre_Name` varchar(255) NOT NULL,
  `Game_Dev_Id` int(11) NOT NULL,
  `Game_ESRB` varchar(255) NOT NULL,
  `Game_Price` double NOT NULL,
  `Game_Number_Of_Players` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `game_update`
--

CREATE TABLE `game_update` (
  `Update_Id` int(11) NOT NULL,
  `Update_type` int(11) NOT NULL,
  `Limited_Time_Event` tinyint(4) NOT NULL,
  `Game_Id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Description` varchar(2000) NOT NULL,
  `Version_Number` varchar(255) NOT NULL,
  `Update_Size` double NOT NULL,
  `New_Features` varchar(5000) NOT NULL COMMENT 'CSV Format to be parsed as an Array In service'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `Genre_Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Popularity_Score` double NOT NULL,
  `Target_Audience` int(11) NOT NULL,
  `Average_Rating` double NOT NULL,
  `Average_Game_Length` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform`
--

CREATE TABLE `platform` (
  `Platform_Name` varchar(255) NOT NULL,
  `Platform_Founder` varchar(255) NOT NULL,
  `Platform_Current_Owner` varchar(255) NOT NULL,
  `Platform_Tagline` varchar(500) NOT NULL,
  `Platform_Website` varchar(1000) NOT NULL,
  `Platform_Type` varchar(255) NOT NULL,
  `Platform_Release_Date` date NOT NULL,
  `Platform_Num_Of_Languages` bigint(20) NOT NULL,
  `Platform_Cloud_Gaming_Support` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_game`
--

CREATE TABLE `platform_game` (
  `Game_Id` int(11) NOT NULL,
  `Platform_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Review_Id` int(11) NOT NULL,
  `Review_Game_Id` int(11) NOT NULL,
  `Review_Rating` double NOT NULL,
  `Review_Date` date NOT NULL,
  `Review_Likes` bigint(20) NOT NULL,
  `Review_Platform_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`Country_Name`);

--
-- Indexes for table `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`Dev_Name`);

--
-- Indexes for table `dlc`
--
ALTER TABLE `dlc`
  ADD PRIMARY KEY (`DLC_Id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`Game_Id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`Genre_Name`);

--
-- Indexes for table `platform`
--
ALTER TABLE `platform`
  ADD PRIMARY KEY (`Platform_Name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dlc`
--
ALTER TABLE `dlc`
  MODIFY `DLC_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `Game_Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
