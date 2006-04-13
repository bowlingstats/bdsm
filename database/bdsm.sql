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
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `location` varchar(50) NOT NULL default 'Not Specified'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `games`
--


/*!40000 ALTER TABLE `games` DISABLE KEYS */;
LOCK TABLES `games` WRITE;
INSERT INTO `games` VALUES (1,1,159,'2006-03-21 00:00:00','Suburban Lanes'),(1,2,158,'2006-03-21 00:00:00','Suburban Lanes'),(2,3,258,'2006-03-30 00:00:00','Classic Lanes'),(2,4,267,'2006-03-30 00:00:00','Classic Lanes'),(3,7,149,'2006-04-23 00:00:00','Bulldog Lanes'),(4,1,133,'2006-03-21 00:00:00','Suburban Lanes'),(5,1,137,'2006-03-23 00:00:00','Suburban Lanes'),(6,4,172,'2006-04-23 00:00:00','Bulldog Lanes'),(6,3,164,'2006-04-23 00:00:00','Bulldog Lanes'),(6,6,143,'2006-04-23 00:00:00','Bulldog Lanes'),(7,3,139,'2006-04-23 00:00:00','Bulldog Lanes'),(7,7,170,'2006-04-23 00:00:00','Bulldog Lanes'),(7,5,160,'2006-04-23 00:00:00','Bulldog Lanes'),(8,2,210,'2006-04-10 01:13:23','Suburban Lanes'),(8,4,170,'2006-04-10 01:13:23','Suburban Lanes'),(9,8,147,'2006-04-10 02:37:23','AMF West'),(10,1,205,'2006-04-10 02:45:02','Bulldog Lanes'),(11,3,155,'2006-04-10 04:00:04','Suburban Lanes'),(11,1,160,'2006-04-10 04:00:04','Suburban Lanes'),(12,4,179,'2006-04-10 21:57:05','Suburban Lanes'),(13,2,300,'2006-04-12 16:13:36','Suburban Lanes');
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
INSERT INTO `scores` VALUES (1,1,1,8,1,0),(1,1,2,8,2,0),(1,1,3,10,0,0),(1,1,4,10,0,0),(1,1,5,0,0,0),(1,1,6,10,0,0),(1,1,7,10,0,0),(1,1,8,10,0,0),(1,1,9,7,2,0),(1,1,10,3,7,5),(1,2,1,10,0,0),(1,2,2,10,0,0),(1,2,3,9,0,0),(1,2,4,9,1,0),(1,2,5,6,3,0),(1,2,6,5,5,0),(1,2,7,10,0,0),(1,2,8,7,2,0),(1,2,9,9,0,0),(1,2,10,8,2,9),(2,3,1,9,0,0),(2,3,2,5,4,0),(2,3,3,10,0,0),(2,3,4,10,0,0),(2,3,5,10,0,0),(2,3,6,10,0,0),(2,3,7,10,0,0),(2,3,8,10,0,0),(2,3,9,10,0,0),(2,3,10,10,10,10),(2,4,1,9,1,0),(2,4,2,8,1,0),(2,4,3,10,0,0),(2,4,4,10,0,0),(2,4,5,10,0,0),(2,4,6,10,0,0),(2,4,7,10,0,0),(2,4,8,10,0,0),(2,4,9,10,0,0),(2,4,10,10,10,10),(3,7,1,5,3,0),(3,7,2,5,5,0),(3,7,3,6,3,0),(3,7,4,10,0,0),(3,7,5,9,1,0),(3,7,6,4,5,0),(3,7,7,4,6,0),(3,7,8,8,2,0),(3,7,9,10,0,0),(3,7,10,7,3,5),(4,1,1,8,2,0),(4,1,2,3,7,0),(4,1,3,10,0,0),(4,1,4,5,4,0),(4,1,5,5,4,0),(4,1,6,7,3,0),(4,1,7,7,2,0),(4,1,8,7,2,0),(4,1,9,7,1,0),(4,1,10,9,1,10),(5,1,1,8,1,0),(5,1,2,8,2,0),(5,1,3,9,0,0),(5,1,4,10,0,0),(5,1,5,8,1,0),(5,1,6,7,3,0),(5,1,7,8,1,0),(5,1,8,7,2,0),(5,1,9,9,1,0),(5,1,10,7,3,9),(6,4,1,10,0,0),(6,4,2,9,1,0),(6,4,3,8,2,0),(6,4,4,10,0,0),(6,4,5,7,2,0),(6,4,6,8,2,0),(6,4,7,9,0,0),(6,4,8,7,3,0),(6,4,9,9,1,0),(6,4,10,9,1,10),(6,3,1,5,4,0),(6,3,2,7,3,0),(6,3,3,8,1,0),(6,3,4,7,3,0),(6,3,5,8,1,0),(6,3,6,10,0,0),(6,3,7,10,0,0),(6,3,8,9,1,0),(6,3,9,7,3,0),(6,3,10,8,2,7),(6,6,1,10,0,0),(6,6,2,7,2,0),(6,6,3,8,2,0),(6,6,4,8,1,0),(6,6,5,7,2,0),(6,6,6,7,3,0),(6,6,7,10,0,0),(6,6,8,6,4,0),(6,6,9,5,4,0),(6,6,10,6,4,5),(7,3,1,3,7,0),(7,3,2,10,0,0),(7,3,3,7,2,0),(7,3,4,7,1,0),(7,3,5,8,2,0),(7,3,6,9,1,0),(7,3,7,8,1,0),(7,3,8,9,1,0),(7,3,9,9,0,0),(7,3,10,7,2,0),(7,7,1,7,3,0),(7,7,2,7,3,0),(7,7,3,7,3,0),(7,7,4,7,3,0),(7,7,5,7,3,0),(7,7,6,7,3,0),(7,7,7,7,3,0),(7,7,8,7,3,0),(7,7,9,7,3,0),(7,7,10,7,3,7),(7,5,1,6,4,0),(7,5,2,6,4,0),(7,5,3,6,4,0),(7,5,4,6,4,0),(7,5,5,6,4,0),(7,5,6,6,4,0),(7,5,7,6,4,0),(7,5,8,6,4,0),(7,5,9,6,4,0),(7,5,10,6,4,6),(10,1,10,6,4,6),(10,1,9,6,4,0),(10,1,8,6,4,0),(10,1,7,6,4,0),(10,1,6,6,4,0),(10,1,5,9,1,0),(10,1,4,10,0,0),(10,1,3,10,0,0),(10,1,2,10,0,0),(10,1,1,10,0,0),(9,8,10,5,4,0),(9,8,9,10,0,0),(9,8,8,8,1,0),(9,8,7,10,0,0),(9,8,6,10,0,0),(9,8,5,7,1,0),(9,8,4,10,0,0),(9,8,3,9,0,0),(9,8,2,7,3,0),(9,8,1,5,4,0),(8,4,10,10,10,10),(8,4,9,9,1,0),(8,4,8,8,1,0),(8,4,7,7,3,0),(8,4,6,10,0,0),(8,4,5,8,1,0),(8,4,4,8,2,0),(8,4,3,9,1,0),(8,4,2,8,1,0),(8,4,1,10,0,0),(8,2,10,10,10,4),(8,2,9,10,0,0),(8,2,8,10,0,0),(8,2,7,8,2,0),(8,2,6,7,2,0),(8,2,5,5,0,0),(8,2,4,7,3,0),(8,2,3,10,0,0),(8,2,2,10,0,0),(8,2,1,10,0,0),(11,3,1,7,1,0),(11,3,2,8,2,0),(11,3,3,8,1,0),(11,3,4,10,0,0),(11,3,5,9,1,0),(11,3,6,7,3,0),(11,3,7,8,1,0),(11,3,8,6,4,0),(11,3,9,7,3,0),(11,3,10,10,8,1),(11,1,1,6,4,0),(11,1,2,6,4,0),(11,1,3,6,4,0),(11,1,4,6,4,0),(11,1,5,6,4,0),(11,1,6,6,4,0),(11,1,7,6,4,0),(11,1,8,6,4,0),(11,1,9,6,4,0),(11,1,10,6,4,6),(12,4,1,5,5,0),(12,4,2,8,2,0),(12,4,3,7,3,0),(12,4,4,9,1,0),(12,4,5,5,5,0),(12,4,6,6,4,0),(12,4,7,7,3,0),(12,4,8,9,1,0),(12,4,9,8,2,0),(12,4,10,10,9,1),(13,2,1,10,0,0),(13,2,2,10,0,0),(13,2,3,10,0,0),(13,2,4,10,0,0),(13,2,5,10,0,0),(13,2,6,10,0,0),(13,2,7,10,0,0),(13,2,8,10,0,0),(13,2,9,10,0,0),(13,2,10,10,10,10);
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
INSERT INTO `users` VALUES (1,'jim','7b4bb99a2884641b','Jim'),(2,'suzy','65fc6841714ef0bd','Suzy'),(3,'nosaj','689eede556f523c7','nosaJ'),(4,'wehttam','54581e1855416325','wehttaM'),(5,'mada','64e440797f87fd87','madA'),(6,'etan','48417bee333537d5','etaN'),(7,'dude','649d43786b4e6ee4','El Duderino'),(8,'walter','28fb446926d73a10','Walter Sobczek'),(9,'jfrancis','28fb446926d73a10','Jason Francis'),(10,'mlaird','1f18c6d30bdadfca','Matthew Laird'),(11,'aledyard','360aed2002c50102','Adam Ledyard'),(12,'nscholte','664b3e280bd2bc72','Nate Scholten');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

