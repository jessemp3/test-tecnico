-- MySQL dump 10.13  Distrib 8.0.37, for Win64 (x86_64)
--
-- Host: localhost    Database: starwars_db
-- ------------------------------------------------------
-- Server version	8.0.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `api_logs`
--

DROP TABLE IF EXISTS `api_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `endpoint` varchar(255) NOT NULL,
  `request_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_logs`
--

LOCK TABLES `api_logs` WRITE;
/*!40000 ALTER TABLE `api_logs` DISABLE KEYS */;
INSERT INTO `api_logs` VALUES (1,'getAllFilms','2025-01-08 06:31:29','2025-01-08 09:31:29'),(2,'getAllFilms','2025-01-08 06:32:41','2025-01-08 09:32:41'),(3,'getAllFilms','2025-01-08 06:33:10','2025-01-08 09:33:10'),(4,'getAllFilms','2025-01-08 06:33:36','2025-01-08 09:33:36'),(5,'getAllFilms','2025-01-08 06:35:00','2025-01-08 09:35:00'),(6,'getAllFilms','2025-01-08 06:35:37','2025-01-08 09:35:37'),(7,'/api/films','2025-01-11 00:00:15','2025-01-11 03:00:15'),(8,'/api/films','2025-01-11 00:00:19','2025-01-11 03:00:19'),(9,'/api/films','2025-01-11 00:04:03','2025-01-11 03:04:03'),(10,'/api/people','2025-01-11 00:09:39','2025-01-11 03:09:39'),(11,'/api/films','2025-01-11 00:27:47','2025-01-11 03:27:47'),(12,'/api/films','2025-01-11 00:29:15','2025-01-11 03:29:15'),(13,'/api/films','2025-01-11 00:30:40','2025-01-11 03:30:40'),(14,'/api/films','2025-01-11 00:35:23','2025-01-11 03:35:23'),(15,'/api/films','2025-01-11 00:38:53','2025-01-11 03:38:53'),(16,'/api/films','2025-01-11 00:38:55','2025-01-11 03:38:55'),(17,'/api/films','2025-01-11 00:42:51','2025-01-11 03:42:51'),(18,'/api/films','2025-01-11 01:01:06','2025-01-11 04:01:06');
/*!40000 ALTER TABLE `api_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-11  1:11:30
