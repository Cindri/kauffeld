-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Aug 2016 um 22:16
-- Server-Version: 10.1.8-MariaDB
-- PHP-Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `test`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `catering`
--

CREATE TABLE IF NOT EXISTS `catering` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `display` tinyint(4) NOT NULL,
  `subpage` varchar(127) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` varchar(15) NOT NULL,
  `unit` varchar(31) NOT NULL DEFAULT 'Stück',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `catering`
--

INSERT INTO `catering` (`ID`, `display`, `subpage`, `title`, `description`, `price`, `unit`) VALUES
(1, 1, 'fingerfood', 'Canapees garniert', 'Lachs, Forelle, Roastbeef, kalter Braten, Kasseler, Käse usw.', '2,50', 'Set'),
(2, 1, 'fingerfood', 'Belegte Brötchen garniert', 'Schinken, Salami, Aufschnitt, Käse mit Fisch, Roastbeef, Braten', '2,50', 'Brötchen');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(63) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `login`
--

INSERT INTO `login` (`ID`, `token`, `time`, `ip`) VALUES
(17, 'QVh5PUjpCLoB95GGUfIK', '2016-08-12 17:03:54', '::1'),
(18, 'IXRf8XGpYYGEz1U6cLEy', '2016-08-12 18:24:40', '::1'),
(19, 'fkjOgbkJQm5mEaKJpF3I', '2016-08-14 10:15:11', '::1'),
(20, 'WMUprVdr1NWL1AA2i10b', '2016-08-14 13:22:29', '::1'),
(21, 'KV3sx8Wzq2pCqCfUofaO', '2016-08-16 19:36:53', '::1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mittagskarten`
--

CREATE TABLE IF NOT EXISTS `mittagskarten` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `geschaeft` varchar(31) NOT NULL,
  `werbetext` text NOT NULL,
  `last_change` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `mittagskarten`
--

INSERT INTO `mittagskarten` (`ID`, `startDate`, `endDate`, `geschaeft`, `werbetext`, `last_change`) VALUES
(1, '2016-08-11', '2016-08-18', 'hauptgeschaeft', 'HAHAHA', '2016-08-10 21:00:55'),
(2, '2016-08-04', '2016-08-11', 'rheinstrasse', 'Werbetext ', '2016-08-10 00:13:57');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mittagsspeisen`
--

CREATE TABLE IF NOT EXISTS `mittagsspeisen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `kartenID` int(11) NOT NULL,
  `type` varchar(63) NOT NULL DEFAULT 'Angebot',
  `day` smallint(6) NOT NULL,
  `title` varchar(127) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(15) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `mittagsspeisen`
--

INSERT INTO `mittagsspeisen` (`ID`, `kartenID`, `type`, `day`, `title`, `description`, `price`) VALUES
(1, 1, 'Angebot', 1, 'asdasd', 'asd', 'asd'),
(2, 1, 'Angebot', 1, '', '', ''),
(3, 1, 'Angebot', 2, '', '', ''),
(4, 1, 'Angebot', 2, '', '', ''),
(5, 1, 'Angebot', 3, '', '', ''),
(6, 1, 'Angebot', 3, '', '', ''),
(7, 1, 'Angebot', 4, '', '', ''),
(8, 1, 'Angebot', 4, '', '', ''),
(9, 1, 'Angebot', 5, '', '', ''),
(10, 1, 'Angebot', 5, '', '', ''),
(11, 1, 'Angebot', 6, '', '', ''),
(12, 1, 'Salat der Woche', 99, 'HAHAHA', 'YEES', '1,23'),
(13, 2, 'Angebot', 1, 'asdasd', 'asd', 'asd'),
(14, 2, 'Angebot', 2, '', '', ''),
(15, 2, 'Angebot', 3, 'retert', 'wer', 'wer'),
(16, 2, 'Angebot', 4, '', '', ''),
(17, 2, 'Angebot', 5, '', '', ''),
(19, 2, 'Salat der Woche', 99, '', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wochenangebot`
--

CREATE TABLE IF NOT EXISTS `wochenangebot` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `kartenID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` varchar(15) NOT NULL,
  `unit` varchar(31) NOT NULL,
  `type` varchar(63) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `wochenangebot`
--

INSERT INTO `wochenangebot` (`ID`, `kartenID`, `title`, `description`, `price`, `unit`, `type`) VALUES
(1, 1, 'Zartes Rindersteak', 'aus der Keule in Kräuterbuttermarinade', '15,99', 'Kilo', 'Angebot'),
(2, 1, 'Schaschlikspieß', 'mit magerem Schweinefleisch, frischem Paprika, Zwiebeln und Dürrfleisch', '10,99', 'Kilo', 'Angebot'),
(3, 1, 'Cevapcici', '', '8,99', 'Kilo', 'Angebot'),
(4, 1, 'Schwarzwälder Spaltschinken', '', '1,49', '100g', 'Angebot'),
(5, 1, '', '', '', '', 'Angebot'),
(6, 1, '', '', '', '', 'Angebot'),
(7, 1, '', '', '', '', 'Salat der Woche');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wochenkarten`
--

CREATE TABLE IF NOT EXISTS `wochenkarten` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `werbetext` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `wochenkarten`
--

INSERT INTO `wochenkarten` (`ID`, `startDate`, `endDate`, `werbetext`) VALUES
(1, '2016-08-08', '2016-08-14', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
