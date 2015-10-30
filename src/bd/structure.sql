-- MySQL dump 10.13  Distrib 5.6.24, for osx10.8 (x86_64)
--
-- Host: 127.0.0.1    Database: atmosphereexplorer
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `logger`
--

DROP TABLE IF EXISTS `logger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logger` (
  `idLogger` int(10) unsigned NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `elevation` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `timeOffset` int(11) DEFAULT NULL,
  PRIMARY KEY (`idLogger`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `record`
--

DROP TABLE IF EXISTS `record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `record` (
  `idRecord` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idSensor` int(10) unsigned NOT NULL,
  `dateCreated` datetime NOT NULL,
  `avg` decimal(6,2) DEFAULT NULL,
  `sd` decimal(6,2) DEFAULT NULL,
  `min` decimal(6,2) DEFAULT NULL,
  `max` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`idRecord`),
  UNIQUE KEY `idx_unique_date` (`idSensor`,`dateCreated`) COMMENT 'A sensor only can have a record at a same DATE',
  KEY `fk_record_sensor1_idx` (`idSensor`),
  CONSTRAINT `fk_record_sensor1` FOREIGN KEY (`idSensor`) REFERENCES `sensor` (`idSensor`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sensor`
--

DROP TABLE IF EXISTS `sensor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sensor` (
  `idSensor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idLogger` int(10) unsigned NOT NULL,
  `channelNumber` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `serialNumber` varchar(45) DEFAULT NULL,
  `height` varchar(45) DEFAULT NULL,
  `scaleFactor` varchar(45) DEFAULT NULL,
  `offset` varchar(45) DEFAULT NULL,
  `units` varchar(45) DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT '0',
  `intAvg` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`idSensor`),
  KEY `fk_sensor_logger_idx` (`idLogger`),
  CONSTRAINT `fk_sensor_logger` FOREIGN KEY (`idLogger`) REFERENCES `logger` (`idLogger`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'atmosphereexplorer'
--

--
-- Dumping routines for database 'atmosphereexplorer'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-29 20:18:40
