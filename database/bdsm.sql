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
  `player_id` mediumint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `games`
--


/*!40000 ALTER TABLE `games` DISABLE KEYS */;
LOCK TABLES `games` WRITE;
INSERT INTO `games` VALUES (1,1),(1,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;

--
-- Table structure for table `pinfall`
--

DROP TABLE IF EXISTS `pinfall`;
CREATE TABLE `pinfall` (
  `game_id` mediumint(4) NOT NULL default '0',
  `player_id` mediumint(4) NOT NULL default '0',
  `frame` smallint(2) NOT NULL default '0',
  `b1` varchar(20) NOT NULL default '',
  `b2` varchar(20) NOT NULL default '',
  `b3` varchar(32) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pinfall`
--


/*!40000 ALTER TABLE `pinfall` DISABLE KEYS */;
LOCK TABLES `pinfall` WRITE;
INSERT INTO `pinfall` VALUES (1,1,1,'1,5','5',''),(1,1,2,'6,10','',''),(1,1,3,'','',''),(1,1,4,'','',''),(1,1,5,'1,2,3,4,5,6,7,8,9,10','1,2,3,4,5,6,7,8,9,10',''),(1,1,6,'','',''),(1,1,7,'','',''),(1,1,8,'','',''),(1,1,9,'4,7,8','8',''),(1,1,10,'1,2,3,4,5,6,10','','10'),(1,2,1,'','',''),(1,2,2,'','',''),(1,2,3,'','',''),(1,2,4,'10','',''),(1,2,5,'1,3,6,10','1',''),(1,2,6,'1,2,3,5,6','',''),(1,2,7,'','',''),(1,2,8,'3,5,6','5',''),(1,2,9,'7','7',''),(1,2,10,'4,7','','5');
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
INSERT INTO `scores` VALUES (1,1,1,8,1,0),(1,1,2,8,2,0),(1,1,3,10,0,0),(1,1,4,10,0,0),(1,1,5,0,0,0),(1,1,6,10,0,0),(1,1,7,10,0,0),(1,1,8,10,0,0),(1,1,9,7,2,0),(1,1,10,3,7,5),(1,2,1,10,0,0),(1,2,2,10,0,0),(1,2,3,10,0,0),(1,2,4,9,1,0),(1,2,5,6,3,0),(1,2,6,5,5,0),(1,2,7,10,0,0),(1,2,8,7,2,0),(1,2,9,9,0,0),(1,2,10,8,2,9);
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
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--


/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES (1,'jim','7b4bb99a2884641b','Jim'),(2,'suzy','65fc6841714ef0bd','Suzy');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

