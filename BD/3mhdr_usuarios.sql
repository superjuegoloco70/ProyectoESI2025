-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: 3mhdr
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.27-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `CI` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Sueldo` varchar(255) DEFAULT NULL,
  `ID_ViviendaUsuario` int(11) DEFAULT NULL,
  `Estado` int(11) DEFAULT NULL,
  PRIMARY KEY (`CI`),
  KEY `ID_ViviendaUsuario` (`ID_ViviendaUsuario`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`ID_ViviendaUsuario`) REFERENCES `vivienda` (`ID_Vivienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (81,'lala','898','',NULL,0),(85,'sadsad','2131','',NULL,NULL),(89,'Pepe Silva','567','',NULL,NULL),(589,'Koko','hola123','',NULL,NULL),(789,'OOO','qwerty',NULL,NULL,0),(999,'Aleman','pepito45',NULL,NULL,1),(5668,'1234','9090',NULL,NULL,0),(12313,'Hola','jhiaopj',NULL,NULL,0),(999888,'Hola','pololo',NULL,NULL,0),(5968954,'fsdfds','opopdas',NULL,NULL,0),(9998887,'Hola','holita',NULL,NULL,0),(11111111,'Carlita','54321','',NULL,NULL),(56984963,'P4Aloss','221220071',NULL,NULL,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-27 17:52:31
