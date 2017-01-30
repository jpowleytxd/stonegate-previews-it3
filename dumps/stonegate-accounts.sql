-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: stonegate_lookup
-- ------------------------------------------------------
-- Server version	5.7.16-log

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
-- Table structure for table `stonegate_lookup`
--

DROP TABLE IF EXISTS `stonegate_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stonegate_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) DEFAULT NULL,
  `account_id` varchar(10) DEFAULT NULL,
  `profile_id` varchar(10) DEFAULT NULL,
  `brand_id` varchar(10) DEFAULT NULL,
  `venue_id` varchar(10) DEFAULT NULL,
  `ve_account` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stonegate_lookup`
--

LOCK TABLES `stonegate_lookup` WRITE;
/*!40000 ALTER TABLE `stonegate_lookup` DISABLE KEYS */;
INSERT INTO `stonegate_lookup` VALUES (1,'Admiral Duncan','1222','2115','1703','1704','1242'),(2,'Beduin','1222','2115','1705','1706','1242'),(3,'Charles Street','1222','2115','1707','1708','1242'),(4,'Colors','1222','2115','1709','1710','1242'),(5,'Duke Of Wellington','1222','2115','1711','1712','1242'),(6,'Edwards','1222','2121','1713','1714','1235'),(7,'Finnegans Wake','1222','2115','1715','1716','1242'),(8,'Flares','1222','2117','1717','1718','1236'),(9,'Halfway To Heaven','1222','2115','1719','1720','1242'),(10,'Kings Arms','1222','2115','1721','1722','1242'),(11,'Luna','1222','2115','1723','1723','1237'),(12,'Marys','1222','2115','1725','1726','1242'),(13,'Missoula','1222','2123','1727','1728','1238'),(14,'Pit And Pendulum','1222','2115','1729','1730','1242'),(15,'Popworld','1222','2127','1731','1732','1239'),(16,'Queens Court','1222','2115','1733','1734','1242'),(17,'Reflex','1222','2129','1735','1736','1240'),(18,'Retro Bar','1222','2115','1737','1738','1242'),(19,'Rosies','1222','2115','1739','1740','1242'),(20,'Rupert Street','1222','2115','1741','1742','1242'),(21,'Slains Castle','1222','2115','1743','1744','1242'),(22,'Slug','1222','2131','1745','1746','1241'),(23,'Two Brewers','1222','2115','1747','1748','1242'),(24,'Via','1222','2115','1749','1750','1242'),(25,'Yates','1222','2107','1951',NULL,'2132'),(26,'Bosleys','1222',NULL,'1949',NULL,NULL),(27,'Common Room','1222','1229','1950',NULL,'1229');
/*!40000 ALTER TABLE `stonegate_lookup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-30 17:19:22
