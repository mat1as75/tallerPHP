/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: tallerphpdb
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-0ubuntu0.24.04.2

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
-- Table structure for table `Administrador`
--

DROP TABLE IF EXISTS `Administrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Administrador` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `Administrador_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `Usuario` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Administrador`
--

LOCK TABLES `Administrador` WRITE;
/*!40000 ALTER TABLE `Administrador` DISABLE KEYS */;
INSERT INTO `Administrador` VALUES
(1),
(3),
(5),
(7);
/*!40000 ALTER TABLE `Administrador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Gestor`
--

DROP TABLE IF EXISTS `Gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Gestor` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  `P_Producto` tinyint(1) DEFAULT NULL,
  `P_Inventario` tinyint(1) DEFAULT NULL,
  `P_Pedidos` tinyint(1) DEFAULT NULL,
  `P_Soporte` tinyint(1) DEFAULT NULL,
  CONSTRAINT `Gestor_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `Usuario` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Administrador`
--

LOCK TABLES `Gestor` WRITE;
/*!40000 ALTER TABLE `Gestor` DISABLE KEYS */;
INSERT INTO `Gestor` VALUES
(8, 0, 1, 0, 0),
(9, 1, 0, 1, 0),
(10, 0, 1, 1, 1);
/*!40000 ALTER TABLE `Gestor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Carrito`
--

DROP TABLE IF EXISTS `Carrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Carrito` (
  `ID_Cliente` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_Cliente`,`ID_Producto`),
  KEY `ID_Producto` (`ID_Producto`),
  CONSTRAINT `Carrito_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `Cliente` (`ID`),
  CONSTRAINT `Carrito_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `Producto` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Carrito`
--

LOCK TABLES `Carrito` WRITE;
/*!40000 ALTER TABLE `Carrito` DISABLE KEYS */;
INSERT INTO `Carrito` VALUES
(2,1,1),
(2,12,2),
(2,16,2),
(4,1,2),
(4,12,3),
(4,16,1),
(6,1,1),
(6,6,1),
(6,15,1);
/*!40000 ALTER TABLE `Carrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categoria`
--

DROP TABLE IF EXISTS `Categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Categoria` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categoria`
--

LOCK TABLES `Categoria` WRITE;
/*!40000 ALTER TABLE `Categoria` DISABLE KEYS */;
INSERT INTO `Categoria` VALUES
(1,'PCs','Computadoras de escritorio y estaciones de trabajo.'),
(2,'Notebooks','Laptops para uso personal y profesional.'),
(3,'Componentes','Componentes internos como procesadores, memorias, etc.'),
(4,'Accesorios','Accesorios como fundas, cables, cargadores.'),
(5,'Periféricos','Periféricos como teclados, ratones, monitores.'),
(6,'Celulares','Smartphones de diferentes marcas y modelos.');
/*!40000 ALTER TABLE `Categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Marca`
--

DROP TABLE IF EXISTS `Marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Marca` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categoria`
--

LOCK TABLES `Marca` WRITE;
/*!40000 ALTER TABLE `Marca` DISABLE KEYS */;
INSERT INTO `Marca` VALUES
(1, 'Samsung'),
(2, 'Gigabyte'),
(3, 'HP'),
(4, 'Logitech'),
(5, 'Apple'),
(6, 'Acer'),
(7, 'Dell'),
(8, 'Asus'),
(9, 'Msi'),
(10, 'Intel');
/*!40000 ALTER TABLE `Marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cliente`
--

DROP TABLE IF EXISTS `Cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Cliente` (
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  `tokenrecuperacion` VARCHAR(100),
  `expiracion_token` datetime DEFAULT current_timestamp(),
  CONSTRAINT `Cliente_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `Usuario` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cliente`
--

LOCK TABLES `Cliente` WRITE;
/*!40000 ALTER TABLE `Cliente` DISABLE KEYS */;
INSERT INTO `Cliente` VALUES
(2, NULL, NULL),
(4, NULL, NULL),
(6, NULL, NULL);
/*!40000 ALTER TABLE `Cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DatosEnvio`
--

DROP TABLE IF EXISTS `DatosEnvio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `DatosEnvio` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TelefonoCliente` varchar(20) DEFAULT NULL,
  `DireccionCliente` varchar(255) DEFAULT NULL,
  `DepartamentoCliente` varchar(100) DEFAULT NULL,
  `CiudadCliente` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DatosEnvio`
--

LOCK TABLES `DatosEnvio` WRITE;
/*!40000 ALTER TABLE `DatosEnvio` DISABLE KEYS */;
INSERT INTO `DatosEnvio` VALUES
(1,'3001234561','Calle 1 #45-1','Depto 1','Ciudad 1'),
(2,'3001234562','Calle 2 #45-2','Depto 2','Ciudad 2'),
(3,'3001234563','Calle 3 #45-3','Depto 3','Ciudad 3'),
(4,'3001234564','Calle 4 #45-4','Depto 4','Ciudad 4'),
(5,'3001234565','Calle 5 #45-5','Depto 5','Ciudad 5'),
(6,'3001234566','Calle 6 #45-6','Depto 6','Ciudad 6'),
(7,'3001234567','Calle 7 #45-7','Depto 7','Ciudad 7'),
(8,'3001234568','Calle 8 #45-8','Depto 8','Ciudad 8'),
(9,'3001234569','Calle 9 #45-9','Depto 9','Ciudad 9'),
(10,'30012345610','Calle 10 #45-10','Depto 10','Ciudad 10');
/*!40000 ALTER TABLE `DatosEnvio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pedido`
--

DROP TABLE IF EXISTS `Pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Pedido` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Cliente` int(11) DEFAULT NULL,
  `ID_DatosEnvio` int(11) DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Estado` enum('pendiente','procesado','enviado','entregado','cancelado') DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `ID_Cliente` (`ID_Cliente`),
  KEY `ID_DatosEnvio` (`ID_DatosEnvio`),
  CONSTRAINT `Pedido_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `Usuario` (`ID`),
  CONSTRAINT `Pedido_ibfk_2` FOREIGN KEY (`ID_DatosEnvio`) REFERENCES `DatosEnvio` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pedido`
--

LOCK TABLES `Pedido` WRITE;
/*!40000 ALTER TABLE `Pedido` DISABLE KEYS */;
INSERT INTO `Pedido` VALUES
(1,4,1,719.96,'pendiente','2025-05-19 18:59:40'),
(2,6,2,299.97,'pendiente','2025-05-19 18:59:40'),
(3,2,3,539.97,'pendiente','2025-05-19 18:59:40'),
(4,4,4,199.99,'pendiente','2025-05-19 18:59:40'),
(5,6,5,34.99,'pendiente','2025-05-19 18:59:40'),
(6,2,6,1298.88,'pendiente','2025-05-19 18:59:40'),
(7,4,7,199.99,'pendiente','2025-05-19 18:59:40'),
(8,6,8,2099.98,'pendiente','2025-05-19 18:59:40'),
(9,2,9,3996,'pendiente','2025-05-19 18:59:40'),
(10,4,10,69.98,'pendiente','2025-05-19 18:59:40');
/*!40000 ALTER TABLE `Pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Producto`
--

DROP TABLE IF EXISTS `Producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Producto` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Descripcion` text DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) DEFAULT NULL,
  `ID_Categoria` int(11) DEFAULT NULL,
  `ID_Marca` int(10) DEFAULT NULL,
  `URL_Imagen` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `ID_Categoria` (`ID_Categoria`),
  KEY `ID_Marca` (`ID_Marca`),
  CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`ID_Categoria`) REFERENCES `Categoria` (`ID`),
  CONSTRAINT `Producto_ibfk_2` FOREIGN KEY (`ID_Marca`) REFERENCES `Marca` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Producto`
--

LOCK TABLES `Producto` WRITE;
/*!40000 ALTER TABLE `Producto` DISABLE KEYS */;
INSERT INTO `Producto` VALUES
(1,'Samsung Galaxy S25 Ultra 5G 256 GB - Titanium Black','El Samsung Galaxy S25 Ultra 5G 256 GB en color Titanium Black es un smartphone de alta gama con pantalla Dynamic AMOLED 2X de 6.9 pulgadas, cámara de 200 MP, batería de 5500 mAh, conectividad 5G y S Pen integrado. Diseñado para usuarios exigentes.',1049.99,29,6,1,'http://localhost:8080/php/tallerPHP/assets/images/prod1.png','2025-05-19 18:59:40'),
(2,'Notebook Aorus 15 9mf‑e2la583sh Core I5 12500h 8GB DDR5 SSD','Notebook potente para gamers y profesionales, equipada con procesador Intel Core i5 de 12ª generación, 8GB DDR5 y almacenamiento SSD. Rendimiento gráfico y velocidad para tareas intensivas.',771.26,100,2,2,'http://localhost:8080/php/tallerPHP/assets/images/prod2.png','2025-05-19 18:59:40'),
(3,'Notebook Hp 255 G10 Amd Ryzen 7 7730u 16gb 512gb 15,6" Fhd','Notebook confiable con procesador AMD Ryzen 7, 16GB RAM y SSD de 512GB. Pantalla Full HD de 15.6'' ideal para productividad y entretenimiento.',564.00,11,2,3,'http://localhost:8080/php/tallerPHP/assets/images/prod3.png','2025-05-19 18:59:40'),
(4,'LogitechMaster Series MX Master 3S','Mouse ergonómico de alto rendimiento diseñado para profesionales. Con precisión avanzada, botones programables y conectividad inalámbrica.',119.99,57,5,4,'http://localhost:8080/php/tallerPHP/assets/images/prod4.png','2025-05-19 18:59:40'),
(5,'Gigabyte Aorus M4','Mouse gamer con sensor óptico de alta precisión, diseño ambidiestro y retroiluminación RGB. Ideal para juegos de alto nivel.',59.99,77,5,2,'http://localhost:8080/php/tallerPHP/assets/images/prod5.png','2025-05-19 18:59:40'),
(6,'Apple MacBook Air 13.6" Chip M4 245GB SSD 16GB RAM','Laptop liviana y potente de Apple con chip M4, 16GB RAM y SSD de 245GB. Rendimiento eficiente y pantalla brillante para usuarios creativos.',867.88,57,2,5,'http://localhost:8080/php/tallerPHP/assets/images/prod6.png','2025-05-19 18:59:40'),
(7,'Pc Intel Core I5 10400f Pro Gamer - 16Gb - SSD - Radeon RX6500XT','PC de escritorio ideal para gaming y edición, con procesador Intel i5, 16GB RAM, almacenamiento SSD y tarjeta gráfica Radeon RX6500XT.',679.99,10,1,10,'http://localhost:8080/php/tallerPHP/assets/images/prod7.png','2025-05-19 18:59:40'),
(8,'Mini PC Dell Optiplex 3046 SFF - Intel Core i5 - 8GB RAM - 180GB SSD - Windows 10 Pro','PC compacta de escritorio con procesador Intel Core i5, 8GB RAM y SSD de 180GB. Incluye Windows 10 Pro. Ideal para oficinas.',157.52,100,1,7,'http://localhost:8080/php/tallerPHP/assets/images/prod8.png','2025-05-19 18:59:40'),
(9,'Mini PC Tiny Dell Optiplex 7070 - Intel Core i5 9na - 8GB RAM - 240GB SSD - Windows 11 Pro','Mini PC eficiente con procesador Intel i5 de 9ª generación, 8GB RAM y SSD de 240GB. Compacta y potente para espacios reducidos.',529.00,41,1,7,'http://localhost:8080/php/tallerPHP/assets/images/prod9.png','2025-05-19 18:59:40'),
(10,'Teclado mecánico óptico para gaming TUF Gaming K7','Teclado mecánico óptico de alto rendimiento para gamers. Respuesta rápida, diseño resistente y retroiluminación personalizable.',179.99,28,5,8,'http://localhost:8080/php/tallerPHP/assets/images/prod10.png','2025-05-19 18:59:40'),
(11,'Mouse Asus P511 Rog Chakram Core','Mouse gamer de precisión con sensor de alto rendimiento, personalización avanzada y diseño ergonómico para largas sesiones de juego.',99.99,94,5,8,'http://localhost:8080/php/tallerPHP/assets/images/prod11.png','2025-05-19 18:59:40'),
(12,'Funda ecológica Vero','Funda ecológica y resistente, fabricada con materiales reciclados. Protección ideal para dispositivos portátiles con conciencia ambiental.',29.99,51,4,6,'http://localhost:8080/php/tallerPHP/assets/images/prod12.png','2025-05-19 18:59:40'),
(13,'Notebook Gamer MSI GF63 Thin i7‑12650H 512GB 16GB RTX4060','Notebook gamer con procesador Intel i7, tarjeta gráfica RTX 4060, 16GB RAM y SSD de 512GB. Ideal para juegos exigentes y multitarea.',1199.00,65,2,9,'http://localhost:8080/php/tallerPHP/assets/images/prod13.png','2025-05-19 18:59:40'),
(14,'Mochila Dell Negro- CP5724S','Mochila resistente y funcional con múltiples compartimentos, ideal para notebooks y accesorios. Diseño discreto y profesional.',79.99,39,4,7,'http://localhost:8080/php/tallerPHP/assets/images/prod14.png','2025-05-19 18:59:40'),
(15,'MSI Adaptador USB tipo C a Gigabit Ethernet','Adaptador compacto que permite conexión Ethernet de alta velocidad mediante puerto USB-C. Ideal para mejorar la conectividad de dispositivos portátiles.',34.99,24,4,9,'http://localhost:8080/php/tallerPHP/assets/images/prod15.png','2025-05-19 18:59:40'),
(16,'Notebook Acer Aspire Lite Core I7 1255u - 16GB RAM - 512GB SSD - Windows 11','Notebook delgada y potente con procesador Intel i7, 16GB RAM y SSD de 512GB. Perfecta para usuarios que necesitan rendimiento y portabilidad.',999.00,1,2,6,'http://localhost:8080/php/tallerPHP/assets/images/prod16.png','2025-05-19 18:59:40'),
(17,'Notebook Gamer Hp Victus 15.6 I7‑12650h - 16GB RAM - 512GB SSD - RTX 4050 Color Performance Blue','Notebook para gaming con procesador Intel i7, GPU RTX 4050, 16GB RAM y 512GB SSD. Pantalla de 15.6'' y diseño moderno en color azul.',649.44,42,2,3,'http://localhost:8080/php/tallerPHP/assets/images/prod17.png','2025-05-19 18:59:40'),
(18,'GPU Gigabyte GeForce RTX 5080 Aero 16GB','Tarjeta gráfica de alto rendimiento con 16GB de VRAM. Perfecta para gaming 4K, edición de video y aplicaciones intensivas.',1199.00,1,3,2,'http://localhost:8080/php/tallerPHP/assets/images/prod18.png','2025-05-19 18:59:40'),
(19,'GPU MSI GeForce RTX 5080 Shadow 3X OC 16GB','GPU potente con overclocking de fábrica, 16GB de memoria y diseño térmico avanzado. Ideal para entusiastas y gamers profesionales.',1249.00,67,3,9,'http://localhost:8080/php/tallerPHP/assets/images/prod19.png','2025-05-19 18:59:40'),
(20,'Mouse Dell Ms700 Bluetooth Travel','Mouse inalámbrico compacto con conectividad Bluetooth. Diseñado para viajes y uso diario, con batería de larga duración.',49.99,100,5,7,'http://localhost:8080/php/tallerPHP/assets/images/prod20.png','2025-05-19 18:59:40');
/*!40000 ALTER TABLE `Producto` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `Producto_Pedido`
--

DROP TABLE IF EXISTS `Producto_Pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Producto_Pedido` (
  `ID_Pedido` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `Precio` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID_Pedido`,`ID_Producto`),
  KEY `ID_Producto` (`ID_Producto`),
  CONSTRAINT `Producto_Pedido_ibfk_1` FOREIGN KEY (`ID_Pedido`) REFERENCES `Pedido` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `Producto_Pedido_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `Producto` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Producto_Pedido`
--

LOCK TABLES `Producto_Pedido` WRITE;
/*!40000 ALTER TABLE `Producto_Pedido` DISABLE KEYS */;
INSERT INTO `Producto_Pedido` VALUES
(1,10,4,179.99),
(2,11,3,99.99),
(3,7,3,179.99),
(4,4,1,119.99),
(5,15,1,34.99),
(6,17,2,649.44),
(7,4,1,119.99),
(8,1,2,1049.99),
(9,16,4,999),
(10,15,2,34.99);
/*!40000 ALTER TABLE `Producto_Pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuario`
--

DROP TABLE IF EXISTS `Usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Usuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) DEFAULT NULL,
  `Apellido` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Contrasena` varchar(255) DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT NULL,
  `Rol` enum('administrador', 'gestor', 'cliente') DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuario`
--

LOCK TABLES `Usuario` WRITE;
/*!40000 ALTER TABLE `Usuario` DISABLE KEYS */;
INSERT INTO `Usuario` VALUES
(1, 'Juan', 'Gómez', 'juan.gomez@correo.com', '$2y$10$bPcGFiMcUCYHDwPsaTxGxO945N40noYX.ADSGP0Jx/qHiViSVWsM.', 1, 'administrador', '2025-05-19 18:59:40'),
(2, 'Pedro', 'López', 'pedro.lopez@correo.com', '$2y$10$SVbeo8R6RKOaa.bv2D/yf.WylVnu76UwxrCIfb5Rt1TtnxTXzRY/i', 0, 'cliente', '2025-05-19 18:59:40'),
(3, 'Lucía', 'Rodríguez', 'lucia.rodriguez@correo.com', '$2y$10$pOvyybIoYEEqOPm/P0unbu.z1Ympt9lwOdpRp6XAlXXGlgsQgXXYC', 1, 'administrador', '2025-05-19 18:59:40'),
(4, 'Sofía', 'Martínez', 'sofia.martinez@correo.com', '$2y$10$3cojLXlTFHTwMzQls/6SKu9zYL6fXw/mqglwR6SVyvu8BWulQhyZC', 1, 'cliente', '2025-05-19 18:59:40'),
(5, 'Diego', 'Díaz', 'diego.diaz@correo.com', '$2y$10$yjcsH/sb8ENaPyxHwp6oLu4z6DA1DsgHrOXzbcSIKW5wZ3utZy2RO', 1, 'administrador', '2025-05-19 18:59:40'),
(6, 'Carlos', 'Álvarez', 'carlos.alvarez@correo.com', '$2y$10$xozmxJiCpogv19GYP3g7lusZH0bX3EfLL92tH72OPe4VQSkKCS39a', 0, 'cliente', '2025-05-19 18:59:40'),
(7, 'Valeria', 'Romero', 'valeria.romero@correo.com', '$2y$10$7jYPeD8GT6TgycyvdY5KreCj1pMoWSBPreZ0PYUJncYxEyqTYoCWe', 0, 'administrador', '2025-05-19 18:59:40'),
(8, 'Ana', 'Pérez', 'ana.perez@correo.com', '$2y$10$R2kzWkcA2tBIl4ygzuzlb.rub3ZxN5pLcD2n/8i9FPrufgtlioDZG', 1, 'gestor', '2025-05-19 18:59:40'),
(9, 'Marcos', 'Fernández', 'marcos.fernandez@correo.com', '$2y$10$XpNjF5Mb2QOneNhhxZwbleDy8ACRKirSf2MMPgA3CUlMB5i1PS1Rm', 1, 'gestor', '2025-05-19 18:59:40'),
(10, 'Elena', 'Romero', 'elena.romero@correo.com', '$2y$10$BVLWqRmbVSAdcGJAnNkdZeVGjNDesFgStDglr/DiUe24XqmNnJjPS', 1, 'gestor', '2025-05-19 18:59:40');
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-19 19:07:19
