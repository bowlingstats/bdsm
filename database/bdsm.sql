-- MySQL dump 10.9
--
-- Host: localhost    Database: bdsm
-- ------------------------------------------------------
-- Server version	4.1.15-Debian_1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `game_id` mediumint(4) NOT NULL default '0',
  `player_id` mediumint(4) NOT NULL default '0',
  `score` smallint(3) NOT NULL default '0',
  `track_pins` tinyint(1) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `location` varchar(50) NOT NULL default 'Not Specified'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `games`
--


/*!40000 ALTER TABLE `games` DISABLE KEYS */;
LOCK TABLES `games` WRITE;

UNLOCK TABLES;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

--
-- Table structure for table `pinfall`
--

DROP TABLE IF EXISTS `pinfall`;
CREATE TABLE `pinfall` (
  `game_id` mediumint(4) NOT NULL default '0',
  `player_id` mediumint(4) NOT NULL default '0',
  `rack` smallint(2) NOT NULL default '0',
  `pin1` tinyint(4) default NULL,
  `pin2` tinyint(4) default NULL,
  `pin3` tinyint(4) default NULL,
  `pin4` tinyint(4) default NULL,
  `pin5` tinyint(4) default NULL,
  `pin6` tinyint(4) default NULL,
  `pin7` tinyint(4) default NULL,
  `pin8` tinyint(4) default NULL,
  `pin9` tinyint(4) default NULL,
  `pin10` tinyint(4) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pinfall`
--


/*!40000 ALTER TABLE `pinfall` DISABLE KEYS */;
LOCK TABLES `pinfall` WRITE;

UNLOCK TABLES;
/*!40000 ALTER TABLE `pinfall` ENABLE KEYS */;

--
-- Table structure for table `scores`
--

DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `game_id` mediumint(4) NOT NULL default '0',
  `player_id` mediumint(4) NOT NULL default '0',
  `frame` smallint(2) NOT NULL default '0',
  `b1` smallint(2) NOT NULL default '0',
  `b2` smallint(2) NOT NULL default '0',
  `b3` smallint(2) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scores`
--


/*!40000 ALTER TABLE `scores` DISABLE KEYS */;
LOCK TABLES `scores` WRITE;

UNLOCK TABLES;
/*!40000 ALTER TABLE `scores` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `uid` mediumint(4) NOT NULL auto_increment,
  `username` varchar(8) NOT NULL default '',
  `password` varchar(64) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `admin` smallint(1) NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'admin','6f4e3e047e55c46a','Administrator',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

