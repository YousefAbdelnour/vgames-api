-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2024 at 08:37 PM
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
  `Most_Played_Game_Id` int(11) DEFAULT NULL,
  `Development_Companies` varchar(5000) DEFAULT NULL COMMENT 'CSV Format for array parsing',
  `Most_Poplar_Genre` varchar(255) NOT NULL,
  `Average_Age` double NOT NULL,
  `Average_Internet_Speed` double NOT NULL,
  `Language` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`Country_Name`, `Most_Played_Game_Id`, `Development_Companies`, `Most_Poplar_Genre`, `Average_Age`, `Average_Internet_Speed`, `Language`) VALUES
('Canada', NULL, 'Indie Studio A', 'Adventure', 22.5, 120, 'English, French'),
('France', 2, 'Ubisoft', 'RPG', 24, 100, 'French'),
('Poland', 3, 'CD Projekt Red', 'Strategy', 30.2, 75, 'Polish'),
('Sweden', 4, 'Paradox Interactive', 'Simulation', 28.4, 95, 'Swedish'),
('USA', 1, 'Valve,Indie Studio A', 'Action', 25.5, 150, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `developer`
--

CREATE TABLE `developer` (
  `Dev_Id` int(11) NOT NULL,
  `Dev_Name` varchar(255) NOT NULL,
  `Founder` varchar(255) NOT NULL,
  `Headquarters` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Parent` int(11) NOT NULL,
  `Prog_Lang` int(11) NOT NULL,
  `Number_Games_Made` bigint(20) NOT NULL,
  `Founded_Date` date NOT NULL,
  `Number_Of_Employees` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `developer`
--

INSERT INTO `developer` (`Dev_Id`, `Dev_Name`, `Founder`, `Headquarters`, `Type`, `Parent`, `Prog_Lang`, `Number_Games_Made`, `Founded_Date`, `Number_Of_Employees`) VALUES
(1, 'Ubisoft', 'Yves Guillemot', 'Montreuil, France', 'AAA', 0, 1, 100, '1986-03-28', 20000),
(2, 'Valve', 'Gabe Newell', 'Bellevue, USA', 'AAA', 0, 2, 50, '1996-08-24', 400),
(3, 'CD Projekt Red', 'Marcin Iwi?ski', 'Warsaw, Poland', 'AAA', 0, 3, 15, '2002-07-01', 800),
(4, 'Indie Studio A', 'Jane Doe', 'Toronto, Canada', 'Indie', 0, 4, 5, '2015-05-12', 20),
(5, 'Paradox Interactive', 'Fredrik Wester', 'Stockholm, Sweden', 'AAA', 0, 5, 40, '1999-06-20', 1000);

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

--
-- Dumping data for table `dlc`
--

INSERT INTO `dlc` (`DLC_Id`, `Game_Id`, `Name`, `Release_Date`, `Price`, `Description`, `Total_Sales`, `Sales_Id`, `Revenue`, `Hard_Copies_Sold`, `Digital_Copies_Sold`, `Highest_Reveue_Region`) VALUES
(1, 1, 'The Hidden Ones', '2018-01-23', 9.99, 'Expansion for Assassin\'s Creed', 1000000, 1, 9990000, 50000, 950000, 'USA'),
(2, 2, 'Episode One', '2006-06-01', 7.99, 'Expansion for Half-Life 2', 1200000, 2, 9590000, 70000, 1130000, 'USA'),
(3, 3, 'Blood and Wine', '2016-05-31', 14.99, 'Expansion for The Witcher 3', 500000, 3, 7495000, 10000, 490000, 'Poland');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `Game_Id` int(11) NOT NULL,
  `Developer_Id` int(11) NOT NULL,
  `Genre_Name` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Founder` varchar(255) NOT NULL,
  `Release_Date` date NOT NULL,
  `Country_Name` varchar(255) NOT NULL,
  `ESRB` varchar(255) NOT NULL,
  `Price` double NOT NULL,
  `Number_Of_Players` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`Game_Id`, `Developer_Id`, `Genre_Name`, `Name`, `Founder`, `Release_Date`, `Country_Name`, `ESRB`, `Price`, `Number_Of_Players`) VALUES
(1, 1, 'Action', 'Assassin\'s Creed', 'Yves Guillemot', '2007-11-13', 'France', 'M', 59.99, 1),
(2, 2, 'RPG', 'Half-Life 2', 'Gabe Newell', '2004-11-16', 'USA', 'M', 49.99, 1),
(3, 3, 'Strategy', 'The Witcher 3', 'Marcin Iwi?ski', '2015-05-19', 'Poland', 'M', 39.99, 1),
(4, 4, 'Adventure', 'Indie Quest', 'Jane Doe', '2017-09-10', 'Canada', 'T', 19.99, 1),
(5, 5, 'Simulation', 'Cities: Skylines', 'Fredrik Wester', '2015-03-10', 'Sweden', 'E', 29.99, 1);

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

--
-- Dumping data for table `game_update`
--

INSERT INTO `game_update` (`Update_Id`, `Update_type`, `Limited_Time_Event`, `Game_Id`, `Date`, `Description`, `Version_Number`, `Update_Size`, `New_Features`) VALUES
(1, 1, 0, 1, '2018-12-20', 'Bug fixes and performance improvements.', '1.01', 1.5, 'Improved AI, Better performance'),
(2, 2, 1, 2, '2007-10-10', 'Added multiplayer mode.', '2.0', 2, 'Multiplayer mode, New maps'),
(3, 1, 0, 3, '2017-08-15', 'New expansion content.', '1.22', 4.5, 'New quests, new regions');

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

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`Genre_Name`, `Description`, `Popularity_Score`, `Target_Audience`, `Average_Rating`, `Average_Game_Length`) VALUES
('Action', 'Fast-paced gameplay with physical challenges.', 8.7, 16, 4.5, 15),
('Adventure', 'Exploration and puzzle-solving elements.', 7.5, 14, 4.2, 20),
('RPG', 'Role-playing game with character development.', 9, 18, 4.8, 50),
('Simulation', 'Simulating real-world activities.', 6.8, 12, 3.8, 25),
('Strategy', 'Focused on planning and skillful thinking.', 8.3, 18, 4.6, 30);

-- --------------------------------------------------------

--
-- Table structure for table `platform`
--

CREATE TABLE `platform` (
  `Platform_Name` varchar(255) NOT NULL,
  `Founder` varchar(255) NOT NULL,
  `Current_Owner` varchar(255) NOT NULL,
  `Tagline` varchar(500) NOT NULL,
  `Website` varchar(1000) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Release_Date` date NOT NULL,
  `Num_Of_Languages` bigint(20) NOT NULL,
  `Cloud_Gaming_Support` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `platform`
--

INSERT INTO `platform` (`Platform_Name`, `Founder`, `Current_Owner`, `Tagline`, `Website`, `Type`, `Release_Date`, `Num_Of_Languages`, `Cloud_Gaming_Support`) VALUES
('Nintendo Switch', 'Hiroshi Yamauchi', 'Nintendo', 'Play Anywhere', 'https://www.nintendo.com/switch', 'Console', '2017-03-03', 15, 0),
('PlayStation', 'Ken Kutaragi', 'Sony', 'For the Players', 'https://www.playstation.com', 'Console', '1994-12-03', 50, 1),
('Steam', 'Gabe Newell', 'Valve', 'The Ultimate Online Game Platform', 'https://store.steampowered.com', 'PC', '2003-09-12', 25, 1),
('Xbox', 'Seamus Blackley', 'Microsoft', 'Jump In', 'https://www.xbox.com', 'Console', '2001-11-15', 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `platform_game`
--

CREATE TABLE `platform_game` (
  `Game_Id` int(11) NOT NULL,
  `Platform_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `platform_game`
--

INSERT INTO `platform_game` (`Game_Id`, `Platform_Name`) VALUES
(1, 'PlayStation'),
(2, 'Steam'),
(3, 'Steam'),
(4, 'Nintendo Switch'),
(5, 'Steam');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `Review_Id` int(11) NOT NULL,
  `Game_Id` int(11) NOT NULL,
  `Rating` double NOT NULL,
  `Date` date NOT NULL,
  `Likes` bigint(20) NOT NULL,
  `Platform_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`Review_Id`, `Game_Id`, `Rating`, `Date`, `Likes`, `Platform_Id`) VALUES
(1, 1, 4.5, '2020-06-15', 50000, 1),
(2, 2, 4.8, '2006-07-01', 80000, 2),
(3, 3, 4.9, '2016-06-12', 70000, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`Country_Name`),
  ADD KEY `Country_Most_Popular_Genre_FK` (`Most_Poplar_Genre`);

--
-- Indexes for table `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`Dev_Id`);

--
-- Indexes for table `dlc`
--
ALTER TABLE `dlc`
  ADD PRIMARY KEY (`DLC_Id`),
  ADD KEY `DLC_Game_Id_FK` (`Game_Id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`Game_Id`),
  ADD KEY `Game_Dev_Id_FK` (`Developer_Id`),
  ADD KEY `Game_Genre_Name_FK` (`Genre_Name`),
  ADD KEY `Game_Country_Name_FK` (`Country_Name`);

--
-- Indexes for table `game_update`
--
ALTER TABLE `game_update`
  ADD PRIMARY KEY (`Update_Id`),
  ADD KEY `Game_Update_Game_Id_FK` (`Game_Id`);

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
-- Indexes for table `platform_game`
--
ALTER TABLE `platform_game`
  ADD PRIMARY KEY (`Game_Id`,`Platform_Name`),
  ADD KEY `Platform_Game_Platform_Name_FK` (`Platform_Name`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`Review_Id`),
  ADD KEY `Review_Game_Id_FK` (`Game_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dlc`
--
ALTER TABLE `dlc`
  MODIFY `DLC_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `Game_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `country`
--
ALTER TABLE `country`
  ADD CONSTRAINT `Country_Most_Popular_Genre_FK` FOREIGN KEY (`Most_Poplar_Genre`) REFERENCES `genre` (`Genre_Name`);

--
-- Constraints for table `dlc`
--
ALTER TABLE `dlc`
  ADD CONSTRAINT `DLC_Game_Id_FK` FOREIGN KEY (`Game_Id`) REFERENCES `game` (`Game_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `Game_Country_Name_FK` FOREIGN KEY (`Country_Name`) REFERENCES `country` (`Country_Name`),
  ADD CONSTRAINT `Game_Dev_Id_FK` FOREIGN KEY (`Developer_Id`) REFERENCES `developer` (`Dev_Id`),
  ADD CONSTRAINT `Game_Genre_Name_FK` FOREIGN KEY (`Genre_Name`) REFERENCES `genre` (`Genre_Name`);

--
-- Constraints for table `game_update`
--
ALTER TABLE `game_update`
  ADD CONSTRAINT `Game_Update_Game_Id_FK` FOREIGN KEY (`Game_Id`) REFERENCES `game` (`Game_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `platform_game`
--
ALTER TABLE `platform_game`
  ADD CONSTRAINT `Platform_Game_Game_Id_FK` FOREIGN KEY (`Game_Id`) REFERENCES `game` (`Game_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Platform_Game_Platform_Name_FK` FOREIGN KEY (`Platform_Name`) REFERENCES `platform` (`Platform_Name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `Review_Game_Id_FK` FOREIGN KEY (`Game_Id`) REFERENCES `game` (`Game_Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
