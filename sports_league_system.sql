-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 08:21 AM
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
-- Database: `sports_league_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `coaches`
--

CREATE TABLE `coaches` (
  `CoachID` int(11) NOT NULL,
  `CoachName` varchar(100) NOT NULL,
  `ExperienceYears` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coaches`
--

INSERT INTO `coaches` (`CoachID`, `CoachName`, `ExperienceYears`) VALUES
(1, 'Raza Baqir', 11),
(2, 'Ahmed Khan', 7),
(4, 'Abdul Dayan', 16),
(5, 'Yousaf Aurangzaib', 21),
(6, 'Sultan Ayyub', 33);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `MatchID` int(11) NOT NULL,
  `SeasonID` int(11) DEFAULT NULL,
  `VenueID` int(11) DEFAULT NULL,
  `HomeTeamID` int(11) DEFAULT NULL,
  `AwayTeamID` int(11) DEFAULT NULL,
  `MatchDate` date DEFAULT NULL,
  `HomeScore` int(11) DEFAULT NULL,
  `AwayScore` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`MatchID`, `SeasonID`, `VenueID`, `HomeTeamID`, `AwayTeamID`, `MatchDate`, `HomeScore`, `AwayScore`) VALUES
(4, 1, 2, 2, 3, '2026-05-05', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `PlayerID` int(11) NOT NULL,
  `PlayerName` varchar(100) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Position` varchar(50) DEFAULT NULL,
  `TeamID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`PlayerID`, `PlayerName`, `Age`, `Position`, `TeamID`) VALUES
(13, 'Ali Hassan', 22, 'Forward', 2),
(14, 'Usman Tariq', 25, 'Goalkeeper', 2),
(15, 'Bilal Ahmed', 23, 'Midfielder', 3),
(16, 'Hamza Khan', 21, 'Defender', 3),
(20, 'Aslan Zakir', 26, 'Defender', 2),
(21, 'Shujaa Husain', 27, 'Goalkeeper', 3),
(22, 'Raees Haider', 23, 'MidFealder', 2),
(23, 'Wali Bin Yaseen', 21, 'Forward', 3);

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `SeasonID` int(11) NOT NULL,
  `SeasonYear` varchar(20) DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seasons`
--

INSERT INTO `seasons` (`SeasonID`, `SeasonYear`, `StartDate`, `EndDate`) VALUES
(1, '2025', '2025-01-01', '2025-12-31'),
(2, '2025', '2025-06-10', '2025-07-17');

-- --------------------------------------------------------

--
-- Table structure for table `standings`
--

CREATE TABLE `standings` (
  `StandingID` int(11) NOT NULL,
  `TeamID` int(11) DEFAULT NULL,
  `SeasonID` int(11) DEFAULT NULL,
  `MatchesPlayed` int(11) DEFAULT 0,
  `Wins` int(11) DEFAULT 0,
  `Losses` int(11) DEFAULT 0,
  `Points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `standings`
--

INSERT INTO `standings` (`StandingID`, `TeamID`, `SeasonID`, `MatchesPlayed`, `Wins`, `Losses`, `Points`) VALUES
(2, 2, 1, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `TeamID` int(11) NOT NULL,
  `TeamName` varchar(100) NOT NULL,
  `City` varchar(50) DEFAULT NULL,
  `CoachID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`TeamID`, `TeamName`, `City`, `CoachID`) VALUES
(2, 'Tigers', 'Lahore', 2),
(3, 'Eagles', 'Sialkot', 1),
(10, 'LIions', 'Rawalpindi', 5),
(11, 'Spiders', 'Islamabad', 6),
(12, 'Markhors', 'Karachi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `VenueID` int(11) NOT NULL,
  `VenueName` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`VenueID`, `VenueName`, `City`, `Capacity`) VALUES
(1, 'Ayub National Stadium', 'Rawalpindi', 20000),
(2, 'Punjab Stadium', 'Lahore', 15000),
(4, 'Jinnah Sports Stadium', 'Islamabad', 48900),
(5, 'Karachi Port Trust Stadium', 'Karachi', 2000),
(6, 'Qayyum Stadium', 'Peshawar', 15000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`CoachID`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`MatchID`),
  ADD KEY `SeasonID` (`SeasonID`),
  ADD KEY `VenueID` (`VenueID`),
  ADD KEY `HomeTeamID` (`HomeTeamID`),
  ADD KEY `AwayTeamID` (`AwayTeamID`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`PlayerID`),
  ADD KEY `TeamID` (`TeamID`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`SeasonID`);

--
-- Indexes for table `standings`
--
ALTER TABLE `standings`
  ADD PRIMARY KEY (`StandingID`),
  ADD KEY `TeamID` (`TeamID`),
  ADD KEY `SeasonID` (`SeasonID`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`TeamID`),
  ADD KEY `CoachID` (`CoachID`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`VenueID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coaches`
--
ALTER TABLE `coaches`
  MODIFY `CoachID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `MatchID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `PlayerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `SeasonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `standings`
--
ALTER TABLE `standings`
  MODIFY `StandingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `TeamID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `VenueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`SeasonID`) REFERENCES `seasons` (`SeasonID`),
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`VenueID`) REFERENCES `venues` (`VenueID`),
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`HomeTeamID`) REFERENCES `teams` (`TeamID`),
  ADD CONSTRAINT `matches_ibfk_4` FOREIGN KEY (`AwayTeamID`) REFERENCES `teams` (`TeamID`);

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`TeamID`) REFERENCES `teams` (`TeamID`);

--
-- Constraints for table `standings`
--
ALTER TABLE `standings`
  ADD CONSTRAINT `standings_ibfk_1` FOREIGN KEY (`TeamID`) REFERENCES `teams` (`TeamID`),
  ADD CONSTRAINT `standings_ibfk_2` FOREIGN KEY (`SeasonID`) REFERENCES `seasons` (`SeasonID`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`CoachID`) REFERENCES `coaches` (`CoachID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
