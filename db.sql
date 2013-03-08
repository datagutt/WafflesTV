SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `banners` (
  `showID` int(11) NOT NULL,
  `url` varchar(50) NOT NULL,
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `episodes` (
  `episodeID` int(11) NOT NULL AUTO_INCREMENT,
  `showID` int(11) NOT NULL,
  `imdbID` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `season` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `airDate` bigint(20) NOT NULL,
  `watched` tinyint(1) NOT NULL,
  PRIMARY KEY (`episodeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `shows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` tinytext NOT NULL,
  `url` varchar(40) NOT NULL,
  `tvdbID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
