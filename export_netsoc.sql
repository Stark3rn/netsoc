/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: netsoc
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `content` text NOT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_post` (`id_post`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `1` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_post` (`id_post`,`id_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `1` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `likes` VALUES
(7,2,1),
(2,2,3),
(8,4,1),
(3,4,3);
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `content` text NOT NULL,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `posts` VALUES
(1,1,'hello','2025-12-24 16:55:17',NULL),
(2,1,'hellloo','2025-12-25 18:25:16',NULL),
(3,3,'heelllo','2025-12-25 17:36:37','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRT9ApJg1ouiNEVo6MA4Kd4xONVdgenUKLRvg&s'),
(4,3,'hello','2025-12-25 17:44:27','https://i1.sndcdn.com/avatars-ATWPHCpNLqNVDyuZ-MIDqfw-t240x240.jpg');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `session` (
  `id_session` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ip` varchar(45) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  `expiration_date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_session`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `session` VALUES
(1,1,'','2025-12-17 12:36:13','::1','b7662afaf8d800ac7f20d641f55b69a739460a4076c52379965e808a266e5a94dd22b46c7efafcbb09e083805491ed30c0fb3c6149fce2ca9a5a690bb50beee4','2025-12-17 14:36:13'),
(2,1,'','2025-12-17 12:51:59','::1','be94199fee595289341b2c54fa380824b45cc989821b94058ba5cb77494b2aaed5a1048a91794774487ed2b23e3718508f6e16a95d007a52f1dea0d094177f0d','2025-12-17 14:51:59'),
(3,1,'','2025-12-17 12:52:50','::1','4137b67d6a21caee029635a162e9effdfc49a6d89a252f9f6d080a564f5c6d9ecbbf4dd740737686b923c59b6a024f008327abcea031c4ee5dc5ddc02cf8e8a4','2025-12-17 14:52:50'),
(4,1,'','2025-12-17 14:17:33','::1','df4b76c20d56e1ffbbee1fc4b45a03f4ce4067bc19a66fa643342ca9f63dd09db66c882f1670d5d41c24034a5f073ac713925b590f32d255d18d4f7bda1ddef9','2025-12-17 16:17:33'),
(5,1,'','2025-12-24 16:01:25','::1','1a10ea6b2090f8db8a6e199b727ed58a2f7a30efbffaab4b3ff35c52e087fa77563f33a7fbc4bc65881ece37256d5e2e477eb5c4e0cdb448e09faf244dd6b962','2025-12-24 18:01:25'),
(6,1,'','2025-12-24 16:04:37','::1','afd4836454bb4e7cc1edd6a74dfad7488c9639415dda2b774170f158e25d4cf2089227804b68c5f6e493c61f22c5f376f4e319f7f6970b515c83cbdc7e68778a','2025-12-24 18:04:37'),
(7,1,'','2025-12-24 16:04:40','::1','5c752009336c5ec3247ce014bcc6dc3111add908861f3c3820b7e634e2f6db77f9d8b656839dd65d7b7a1c96763e1347ccffa119bb028df263e6318a214aef06','2025-12-24 18:04:40'),
(8,1,'','2025-12-24 16:04:41','::1','7bc971788c60384e41f222c0334c18a41bfb8fdd725a4d040d66cb60f0d01b62f1ee9607d6a43af40425eaa9befcfe219ae9d0f8f18c80b0006941ba0796158b','2025-12-24 18:04:41'),
(9,1,'','2025-12-24 16:24:01','::1','fe0a959fbf39b8d39cc9effe9deb033a8766a927e7564f65363da014db16bb78583263739bb0ff75d06981ad26fc819b50647a31059217b8db203f71a5989048','2025-12-24 18:24:01'),
(10,3,'','2025-12-24 16:44:35','127.0.0.1','b9788c4eda904b5f8ccd00611c86c6c1ef9d59fab40fa286174bc3f543c8eab1ef7aa14235fe5560a5b0d42f96f668e51c677682e60ff7e3dbefde7174047625','2025-12-24 18:44:35'),
(11,1,'','2025-12-25 18:25:10','::1','409f01939ab0e2199d1f581a855531a6942e4f50fa67d82a1fe15a545c96f49fe911200395357b490af2e135abc7a812308ebed9089bf6ae0afc3fd0cf170c14','2025-12-25 20:25:10'),
(12,3,'','2025-12-25 18:25:30','::1','613dbf748236366f71bab89fb77cf806ca01aab4ebdd3ce121995ba9552450fc189e861fc76a175da9e4ae085888dbf7996dc129a0484ee6a8d434f21a4e2412','2025-12-25 20:25:30'),
(13,1,'','2025-12-25 22:54:00','','bee76c9f60a874ea3563f9e17ba1b7e7932449b9c6840cedb62a3425dfda61e7','2025-12-26 23:54:00'),
(14,1,'','2025-12-27 02:57:01','','04208b399a95e1fe8eb2a4ee0e207a39a61864fbb358a563309365c8f7bed95f','2025-12-28 03:57:01');
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `pass_hash` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created` timestamp NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Faputa',NULL,'test@gmail.com','2025-12-17 12:31:14','$2y$12$zpEloaPwcTIqgcMU7rr5d.Qc2JUrQQ9D369x.3jo6a6PU8M8sH.ue',0,'2025-12-24 16:21:36','https://www.w3schools.com/howto/img_avatar.png'),
(3,'FaputaSosu',NULL,'anavrynlod@gmail.com','2025-12-24 16:44:31','$2y$12$3/81aUOQtDjbGAmVd6AnD./NG5c3pvwk6oHIxnL8rFdu/OoCimQVm',0,'2025-12-24 16:44:31','https://i.redd.it/ikw7zohexlvb1.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-12-27  4:03:51
