-- MySQL dump 10.13  Distrib 5.5.53, for Win32 (AMD64)
--
-- Host: localhost    Database: huiyi
-- ------------------------------------------------------
-- Server version	5.5.53

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `aid` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '”√ªßπ‹¿Ì',
  `aname` varchar(20) NOT NULL COMMENT 'π‹¿Ì‘±√˚',
  `apassword` varchar(32) NOT NULL COMMENT '√‹¬Î',
  `is_use` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT 'π‹¿Ì‘± «∑Òø…”√,1:∆Ù”√,0:Ω˚”√',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='∫ÛÃ®π‹¿Ì‘±±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'root','b9be11166d72e9e3ae7fd407165e4bd2',1);
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_role` (
  `aid` smallint(4) unsigned NOT NULL COMMENT 'π‹¿Ì‘±Id',
  `rid` smallint(4) unsigned NOT NULL COMMENT 'Ω«…´Id',
  KEY `aid` (`aid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='π‹¿Ì‘±Ω«…´¡™±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role`
--

LOCK TABLES `admin_role` WRITE;
/*!40000 ALTER TABLE `admin_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business`
--

DROP TABLE IF EXISTS `business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business` (
  `bid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'π´ÀæID',
  `bname` varchar(50) NOT NULL COMMENT '∆Û“µ√˚',
  `pcode` varchar(32) NOT NULL COMMENT '∆Û“µ ⁄»®¬Î',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='∆Û“µ–≈œ¢±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business`
--

LOCK TABLES `business` WRITE;
/*!40000 ALTER TABLE `business` DISABLE KEYS */;
INSERT INTO `business` VALUES (16,'Ê±üË•øÁêÜÂ∑•Â§ßÂ≠¶','DP3SVHNC9M4DB1PY8WR9Y9WCGDRILIIZ'),(17,'Ê∏ÖÂçéÂ§ßÂ≠¶','K0THBJ1ZFHGL2Z3MSD10CAETAUHJTSMK');
/*!40000 ALTER TABLE `business` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meet_create`
--

DROP TABLE IF EXISTS `meet_create`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meet_create` (
  `mid` varchar(15) NOT NULL COMMENT 'ª·“ÈID',
  `uid` varchar(15) NOT NULL COMMENT '”√ªßID',
  `mtitle` varchar(50) NOT NULL COMMENT 'ª·“È±ÍÃ‚',
  `mcontent` tinytext COMMENT 'ª·“Èƒ⁄»›',
  `maddress` varchar(150) NOT NULL COMMENT 'ª·“Èµÿµ„',
  `mstartime` int(10) unsigned NOT NULL COMMENT 'ª·“Èø™ º ±º‰',
  `mendtime` int(10) unsigned NOT NULL COMMENT 'ª·“ÈΩ· ¯ ±º‰',
  `wlanmac` varchar(17) NOT NULL COMMENT '◊¢≤· ÷ª˙¡¨Ω”µƒŒﬁœﬂ¬∑”…∆˜',
  `bluetoothmac` varchar(17) NOT NULL COMMENT '◊¢≤· ÷ª˙¡¨Ω”µƒ¿∂—¿',
  PRIMARY KEY (`mid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ª·“È¥¥Ω®±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meet_create`
--

LOCK TABLES `meet_create` WRITE;
/*!40000 ALTER TABLE `meet_create` DISABLE KEYS */;
/*!40000 ALTER TABLE `meet_create` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privilege`
--

DROP TABLE IF EXISTS `privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privilege` (
  `pid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '»®œﬁID',
  `pname` varchar(30) NOT NULL COMMENT '»®œﬁ√˚',
  `mname` varchar(20) NOT NULL COMMENT 'ƒ£øÈ√˚',
  `cname` varchar(20) NOT NULL COMMENT 'øÿ÷∆∆˜√˚',
  `aname` varchar(20) NOT NULL COMMENT '∂Ø◊˜√˚',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '∏∏º∂»®œﬁ√˚',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='π‹¿Ì‘±»®œﬁ±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privilege`
--

LOCK TABLES `privilege` WRITE;
/*!40000 ALTER TABLE `privilege` DISABLE KEYS */;
/*!40000 ALTER TABLE `privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privilege_role`
--

DROP TABLE IF EXISTS `privilege_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privilege_role` (
  `rid` smallint(4) unsigned NOT NULL COMMENT 'Ω«…´Id',
  `pid` smallint(5) unsigned NOT NULL COMMENT '»®œﬁID',
  KEY `rid` (`rid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='π‹¿Ì‘±Ω«…´»®œﬁ±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privilege_role`
--

LOCK TABLES `privilege_role` WRITE;
/*!40000 ALTER TABLE `privilege_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `privilege_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `rid` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Ω«…´Id',
  `rname` varchar(30) NOT NULL COMMENT 'Ω«…´√˚',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='π‹¿Ì‘±Ω«…´±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` varchar(15) NOT NULL COMMENT '”√ªßID',
  `uname` varchar(20) NOT NULL COMMENT '”√ªß÷–Œƒ√˚',
  `uphoto` varchar(150) NOT NULL COMMENT '”√ªßÕ∑œÒ',
  `uphone` int(11) unsigned DEFAULT NULL COMMENT '”√ªßµÁª∞',
  `pid` varchar(150) DEFAULT NULL COMMENT '÷∏Œ∆ID',
  `user_type` tinyint(2) unsigned NOT NULL COMMENT '”√ªß¿‡–Õ',
  `pwd` varchar(32) NOT NULL,
  `bid` int(11) unsigned NOT NULL COMMENT 'π´ÀæID',
  PRIMARY KEY (`uid`),
  KEY `bid` (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='”√ªß–≈œ¢±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('15570126331','Êú±‰∏ñÈπè','',NULL,NULL,1,'39511a12edcfc9982e3c08b84358763e',16),('15070324860','ÊùéÈ£éÊù∞','',NULL,'WE812DF400000000000000000000000000000000000000000000000000000000',1,'2e028b726acb9b691ace56dc42488573',16);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_sign`
--

DROP TABLE IF EXISTS `user_sign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_sign` (
  `uid` varchar(15) NOT NULL COMMENT '”√ªßID',
  `mid` varchar(15) NOT NULL COMMENT 'ª·“ÈID',
  `isfingerprint` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '”√ªß «∑Ò”√÷∏Œ∆«©µΩ1: «0:≤ª «',
  `rssi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Œﬁœﬂ–≈∫≈÷µ',
  `signtime` int(10) unsigned NOT NULL COMMENT '”√ªß«©µΩ ±º‰',
  `issign` tinyint(2) unsigned NOT NULL COMMENT '”√ªß «∑Ò«©µΩ',
  KEY `uid` (`uid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='”√ªß«©µΩ±Ì';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_sign`
--

LOCK TABLES `user_sign` WRITE;
/*!40000 ALTER TABLE `user_sign` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_sign` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-03  7:45:47
