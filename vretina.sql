-- MySQL dump 10.13  Distrib 5.7.29, for Linux (x86_64)
--
-- Host: localhost    Database: vretina
-- ------------------------------------------------------
-- Server version	5.7.29

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
-- Table structure for table `filetypes`
--

DROP TABLE IF EXISTS `filetypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filetypes` (
  `id` tinyint(4) DEFAULT NULL,
  `filetype` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filetypes`
--

LOCK TABLES `filetypes` WRITE;
/*!40000 ALTER TABLE `filetypes` DISABLE KEYS */;
INSERT INTO `filetypes` VALUES (0,'jpg');
/*!40000 ALTER TABLE `filetypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `retina_models`
--

DROP TABLE IF EXISTS `retina_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retina_models` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `x_size` int(11) DEFAULT NULL,
  `y_size` int(11) DEFAULT NULL,
  `filetype` tinyint(4) DEFAULT NULL,
  `official` tinyint(1) DEFAULT NULL,
  `uploaded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retina_models`
--

LOCK TABLES `retina_models` WRITE;
/*!40000 ALTER TABLE `retina_models` DISABLE KEYS */;
INSERT INTO `retina_models` VALUES (0,'California-Proliferative-Diabetic-RetinopathyDiabeticRetinopathy8.jpg',2800,1872,0,1,'2020-03-24 18:40:45'),(1,'California-Retinal-Detachment-with-Horseshoe-TearRetinalHolesTearsDetachments1.jpg',2808,1960,0,1,'2020-03-24 18:41:20'),(2,'Color-APMPPE-OD-California.jpg',3064,2600,0,1,'2020-03-25 03:36:10'),(3,'Color-APMPPE-OS-California.jpg',3072,2520,0,1,'2020-03-25 03:36:50'),(4,'Color-Horseshoe-Tear-California_result.jpg',2936,1960,0,1,'2020-03-25 03:37:29'),(5,'Color-PDR2-OD-California.jpg',3096,2560,0,1,'2020-03-25 03:38:04'),(6,'Color-Retinal-Detachment-California_result.jpg',2934,1792,0,1,'2020-03-25 03:38:41'),(7,'Color-Severe-NPDR-California.jpg',2856,2000,0,1,'2020-03-25 03:39:16');
/*!40000 ALTER TABLE `retina_models` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-03-25 16:09:51
