-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: trustcoin
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `Address_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Address_Currency` int(10) unsigned NOT NULL,
  `Address_Address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Address_User` int(10) DEFAULT '0',
  `Address_PrivateKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address_HexAddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address_CreateAt` datetime DEFAULT NULL,
  `Address_UpdateAt` datetime DEFAULT NULL,
  `Address_IsUse` tinyint(1) DEFAULT '0' COMMENT '0:Not use 1:Used',
  `Address_Comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`Address_ID`),
  KEY `FK_ADDRESS_CURRENCY` (`Address_Currency`),
  CONSTRAINT `FK_ADDRESS_CURRENCY` FOREIGN KEY (`Address_Currency`) REFERENCES `currency` (`Currency_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,1,'TaUgo5SFFNB9ZtcKoZZmDSdp5pibMkRwMt',111111,NULL,NULL,'2019-09-10 07:42:38','2019-09-10 07:42:38',1,'Create new address'),(2,1,'TvT7S3rSahuKoBmZsf5Z11tMB2ejHkqJCA',426077,NULL,NULL,'2019-09-10 08:17:11','2019-09-10 08:17:11',1,'Create new address');
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currency` (
  `Currency_ID` int(10) unsigned NOT NULL,
  `Currency_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Currency_Symbol` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Currency_Active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`Currency_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency`
--

LOCK TABLES `currency` WRITE;
/*!40000 ALTER TABLE `currency` DISABLE KEYS */;
INSERT INTO `currency` VALUES (1,'Trustcoin','GTC',1);
/*!40000 ALTER TABLE `currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investment`
--

DROP TABLE IF EXISTS `investment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investment` (
  `investment_ID` int(11) NOT NULL AUTO_INCREMENT,
  `investment_User` int(11) NOT NULL,
  `investment_Amount` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `investment_Currency` int(11) NOT NULL,
  `investment_Rate` decimal(18,8) NOT NULL,
  `investment_Hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `investment_Insurrance` tinyint(1) NOT NULL DEFAULT '0',
  `investment_InsurDate` int(11) DEFAULT NULL,
  `investment_Time` int(11) NOT NULL,
  `investment_Status` tinyint(1) NOT NULL,
  PRIMARY KEY (`investment_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investment`
--

LOCK TABLES `investment` WRITE;
/*!40000 ALTER TABLE `investment` DISABLE KEYS */;
INSERT INTO `investment` VALUES (3,111111,1000.00000000,1,0.19700000,NULL,0,NULL,1568193711,1),(4,111111,1000.00000000,1,0.19700000,NULL,0,NULL,1568193786,1);
/*!40000 ALTER TABLE `investment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `money`
--

DROP TABLE IF EXISTS `money`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `money` (
  `Money_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Money_User` int(10) unsigned NOT NULL,
  `Money_USDT` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_USDTFee` decimal(18,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT 'Phí',
  `Money_SaleBinary` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_Investment` int(11) DEFAULT NULL,
  `Money_Borrow` int(11) DEFAULT NULL,
  `Money_Time` bigint(20) NOT NULL,
  `Money_Comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_MoneyAction` int(10) unsigned NOT NULL DEFAULT '0',
  `Money_MoneyStatus` int(10) NOT NULL,
  `Money_Token` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_TXID` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_Address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Money_Currency` int(10) DEFAULT NULL,
  `Money_CurrentAmount` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_Rate` decimal(18,8) NOT NULL DEFAULT '0.00000000',
  `Money_Confirm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Money_ID`),
  KEY `FK_MONEY_MONEYACTION` (`Money_MoneyAction`),
  KEY `FK_MONEY_MONEYSTATUS` (`Money_MoneyStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `money`
--

LOCK TABLES `money` WRITE;
/*!40000 ALTER TABLE `money` DISABLE KEYS */;
INSERT INTO `money` VALUES (1,111111,2000.00000000,0.00000000,0.00000000,NULL,NULL,1568175142,'Deposit Trustcoin',1,1,NULL,'429a9b8c651b4aa7a538f94b67341ca856065e3c7b9631f695763acfc315d3a4','TaUgo5SFFNB9ZtcKoZZmDSdp5pibMkRwMt',1,0.00000000,0.19700000,1),(2,111111,2000.00000000,0.00000000,0.00000000,NULL,NULL,1568177812,'Deposit Trustcoin',1,1,NULL,'220f65732e0cf35763650ed1bd3a7cf6c37c8b4a9fa63527c017cfb0ab8ef094','TaUgo5SFFNB9ZtcKoZZmDSdp5pibMkRwMt',1,0.00000000,0.19700000,1),(3,111111,1999.00000000,0.00000000,0.00000000,NULL,NULL,1568177816,'Deposit Trustcoin',1,1,NULL,'d1fa2cb95cd5875d1982a66ab51aeba8b38b7db5eff47713557f60dfeb3a2b74','TaUgo5SFFNB9ZtcKoZZmDSdp5pibMkRwMt',1,0.00000000,0.19700000,1),(4,426077,100.00000000,0.00000000,0.00000000,NULL,NULL,1568168729,'Deposit Trustcoin',1,1,NULL,'53bba506731547dc644edf5bb14575f06eddce0c824f6591bf5b899c2f368d6c','TvT7S3rSahuKoBmZsf5Z11tMB2ejHkqJCA',1,0.00000000,0.19700000,1),(5,426077,2000.00000000,0.00000000,0.00000000,NULL,NULL,1568168840,'Deposit Trustcoin',1,1,NULL,'40efb5219eed716fc18e530fa03716ff28289a609bec4410ac423224d154bc41','TvT7S3rSahuKoBmZsf5Z11tMB2ejHkqJCA',1,0.00000000,0.19700000,1),(6,426077,2000.00000000,0.00000000,0.00000000,NULL,NULL,1568168858,'Deposit Trustcoin',1,1,NULL,'3cfdb349594fe41d2bd7e7f4e9397b48fda18ff46f7578ad73a76cdc8392e203','TvT7S3rSahuKoBmZsf5Z11tMB2ejHkqJCA',1,0.00000000,0.19700000,1),(7,111111,-100.00000000,0.00000000,0.00000000,NULL,NULL,1568184958,'Withdraw Trustcoin',2,1,NULL,NULL,'asdasdfasdf',1,0.00000000,0.19700000,0),(8,111111,-100.00000000,0.00000000,0.00000000,NULL,NULL,1568185121,'Withdraw Trustcoin',2,1,NULL,NULL,'asdasdfasdf',1,0.00000000,0.19700000,0),(9,111111,-50.00000000,0.00000000,0.00000000,NULL,NULL,1568185238,'Withdraw Trustcoin',2,1,NULL,NULL,'asdfasdfasdfasfd',1,0.00000000,0.19700000,0),(10,111111,-10.00000000,0.00000000,0.00000000,NULL,NULL,1568185252,'Withdraw Trustcoin',2,1,NULL,NULL,'fgsdfgsdfgsdfgsdd',1,0.00000000,0.19700000,0),(13,111111,-1000.00000000,0.00000000,0.00000000,NULL,NULL,1568193711,'Inverstment Trustcoint',3,1,NULL,NULL,NULL,1,0.00000000,0.19700000,1),(14,111111,-1000.00000000,0.00000000,0.00000000,NULL,NULL,1568193786,'Inverstment Trustcoint',3,1,NULL,NULL,NULL,1,-1000.00000000,0.19700000,1);
/*!40000 ALTER TABLE `money` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moneyaction`
--

DROP TABLE IF EXISTS `moneyaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneyaction` (
  `MoneyAction_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MoneyAction_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`MoneyAction_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneyaction`
--

LOCK TABLES `moneyaction` WRITE;
/*!40000 ALTER TABLE `moneyaction` DISABLE KEYS */;
INSERT INTO `moneyaction` VALUES (1,'Deposit'),(2,'Send'),(3,'Investment'),(4,'Interest');
/*!40000 ALTER TABLE `moneyaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `User_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_Name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `User_Email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `User_Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `User_RegisteredDatetime` datetime NOT NULL,
  `User_Status` int(1) NOT NULL,
  `User_Level` int(1) NOT NULL,
  `User_Parent` int(10) NOT NULL,
  `User_Tree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `User_EmailActive` tinyint(1) DEFAULT '0',
  `User_WalletGTC` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `users_user_email_unique` (`User_Email`)
) ENGINE=InnoDB AUTO_INCREMENT=977932 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (111111,'','khangdh9@gmail.com','$2y$10$eTdbmb96rQpva1J/NkQR..rZvoBbVc2NFk/sYb9f2uGqNyLf6jLve','2019-09-07 13:53:56',1,1,0,'111111',1,NULL),(281442,NULL,'f11@gmail.com','$2y$10$B3.CeP0VUFRGL6aSyf1HXOR.j5B/JsIK1Aq6jlmLL.qS/2iHjPPSu','2019-09-11 00:52:20',0,0,111111,'111111,281442',0,NULL),(560947,NULL,'f13@mail.com','$2y$10$dVeO92sySneuqIywPXjUh.57k15X979U.ah656xtWAlWpGvo/P.4.','2019-09-11 00:53:36',0,0,111111,'111111,560947',0,NULL),(616409,NULL,'f12@mail.com','$2y$10$g2d3XB4LPgvDjbJXl9aIFOVtipqkaK5Eyf.9Hg2Np7uGxFfZz4sxa','2019-09-11 00:52:55',0,0,111111,'111111,616409',0,NULL),(849497,NULL,'F121@mail.com','$2y$10$lrMn.FigFTMmGU31IVP08uiDMITcZED78CHI/luAi5JKiWefPD.cG','2019-09-11 00:55:41',0,0,616409,'111111,616409,849497',0,NULL),(891255,NULL,'f1311@mail.com','$2y$10$XOCJhkR8r.m72sAj/2jvRuIXI5UGLEkRDLMYkyyOhcLMeJZTmzQRy','2019-09-11 00:54:55',0,0,977931,'111111,616409,977931,891255',0,NULL),(977931,NULL,'f131@mail.com','$2y$10$QR3boRMjBfVqWEtH0w3SxuDYdjh8V3oh7fvuSxofv/TVhiNdws.oy','2019-09-11 00:54:18',0,0,616409,'111111,616409,977931',0,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-09-11 18:01:18
