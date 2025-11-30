-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ong_transport
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abonnements`
--

DROP TABLE IF EXISTS `abonnements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `abonnements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `date_expiration` date NOT NULL,
  `dette` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `eleve_id` (`eleve_id`),
  CONSTRAINT `abonnements_ibfk_1` FOREIGN KEY (`eleve_id`) REFERENCES `eleves` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonnements`
--

LOCK TABLES `abonnements` WRITE;
/*!40000 ALTER TABLE `abonnements` DISABLE KEYS */;
INSERT INTO `abonnements` VALUES (1,8,20.00,'2025-11-18','2025-12-18',4.00),(2,4,25.00,'2025-11-18','2025-12-18',0.00),(3,2,50.00,'2025-11-20','2026-01-20',0.00),(4,10,20.00,'2025-11-19','2026-01-17',20.00),(5,5,55.00,'2025-11-20','2026-02-20',20.00),(6,3,75.00,'2025-11-05','2026-02-05',0.00),(7,9,57.00,'2025-11-18','2026-02-18',18.00),(8,7,50.00,'2025-11-18','2026-03-13',30.00),(9,6,10.00,'2025-10-30','2025-11-30',15.00),(10,1,101.00,'2025-11-19','2026-04-19',24.00);
/*!40000 ALTER TABLE `abonnements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bus`
--

DROP TABLE IF EXISTS `bus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_bus` varchar(20) DEFAULT NULL,
  `point_arret` enum('Kamutsha','Kilidja','Garage','Igota Nzambi','Musudja','Station Salongo','Route ByPass','Route Biangana') DEFAULT NULL,
  `conducteur_nom` varchar(50) DEFAULT NULL,
  `conducteur_postnom` varchar(50) DEFAULT NULL,
  `conducteur_prenom` varchar(50) DEFAULT NULL,
  `conducteur_tel` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bus`
--

LOCK TABLES `bus` WRITE;
/*!40000 ALTER TABLE `bus` DISABLE KEYS */;
INSERT INTO `bus` VALUES (1,'001','Kamutsha','Kitoko','Kanda','Emmanuel','1234567890'),(2,'002','Kilidja','Mbemba','Lukombo','Jean','2345678901'),(3,'003','Garage','Nkosi','Mbuyi','Paul','3456789012'),(4,'004','Igota Nzambi','Lemba','Kiala','Pierre','4567890123'),(5,'005','Musudja','Kalala','Masele','David','5678901234'),(6,'006','Station Salongo','Mbuyi','Kitumaini','Jacques','6789012345');
/*!40000 ALTER TABLE `bus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eleves`
--

DROP TABLE IF EXISTS `eleves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eleves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `postnom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) NOT NULL,
  `avenue` varchar(100) DEFAULT NULL,
  `quartier` enum('Salongo','Gombele','Madrendele') DEFAULT NULL,
  `commune` enum('Lemba','Matete','Ngaba') DEFAULT NULL,
  `ecole` varchar(100) DEFAULT NULL,
  `cycle` enum('Maternelle','Primaire','Secondaire','Humanité') DEFAULT NULL,
  `classe` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  `point_arret` enum('Kamutsha','Kilidja','Garage','Igota Nzambi','Musudja','Station Salongo','Route ByPass','Route Biangana') DEFAULT NULL,
  `parent_nom` varchar(100) DEFAULT NULL,
  `parent_tel` varchar(10) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eleves`
--

LOCK TABLES `eleves` WRITE;
/*!40000 ALTER TABLE `eleves` DISABLE KEYS */;
INSERT INTO `eleves` VALUES (1,'Tobo','Lozulu','Benjamin','Ngabwaki','Gombele','Matete','Collège Saint Théophile','Humanité','6ème',17,'M','Station Salongo','Infirmier','1243546475','2025-11-18 12:36:16'),(2,'Kiese','Lukusa','Alice','Ngawu','Salongo','Lemba','École Primaire Saint Paul','Secondaire','5ème',16,'F','Kamutsha','Jean','1234567891','2025-11-18 12:36:16'),(3,'Mbuta','Nkosi','David','Lumumba','Madrendele','Ngaba','Collège Saint Michel','Humanité','6ème',17,'M','Kilidja','Pierre','1234567892','2025-11-18 12:36:16'),(4,'Kalala','Mbuyi','Emilie','Gombé','Gombele','Matete','École Saint François','Primaire','4ème',12,'F','Garage','Marie','1234567893','2025-11-18 12:36:16'),(5,'Lemba','Kiala','Paul','Ngaba','Salongo','Lemba','Collège Saint André','Secondaire','3ème',15,'M','Igota Nzambi','Joseph','1234567894','2025-11-18 12:36:16'),(6,'Nkosi','Mbemba','Sophie','Lumumba','Madrendele','Ngaba','École Saint Claire','Humanité','5ème',16,'F','Musudja','Claire','1234567895','2025-11-18 12:36:16'),(7,'Mbuyi','Kitumaini','Jacques','Ngawu','Salongo','Matete','Collège Saint Joseph','Humanité','6ème',17,'M','Station Salongo','Albert','1234567896','2025-11-18 12:36:16'),(8,'Kalala','Masele','Isabelle','Ngabwaki','Gombele','Lemba','École Sainte Marie','Primaire','4ème',11,'F','Route ByPass','Monique','1234567897','2025-11-18 12:36:16'),(9,'Lukombo','Mbombo','Henri','Lumumba','Madrendele','Ngaba','Collège Saint Théophile','Secondaire','5ème',16,'M','Route Biangana','Michel','1234567898','2025-11-18 12:36:16'),(10,'Kitoko','Kanda','Emma','Gombé','Gombele','Matete','École Saint Pierre','Primaire','3ème',10,'F','Kamutsha','Juliette','1234567899','2025-11-18 12:36:16');
/*!40000 ALTER TABLE `eleves` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-24 15:38:29
