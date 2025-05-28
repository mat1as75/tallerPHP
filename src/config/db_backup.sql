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
(4),
(7),
(10);
/*!40000 ALTER TABLE `Administrador` ENABLE KEYS */;
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
(3,1,1),
(3,12,2),
(3,13,3),
(3,16,2),
(6,1,2),
(6,12,3),
(6,16,1),
(9,1,1),
(9,6,1),
(9,15,1);
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
-- Table structure for table `Cliente`
--

DROP TABLE IF EXISTS `Cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Cliente` (
  `ID` int(11) NOT NULL,
  `URL_Imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `Cliente_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `Usuario` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cliente`
--

LOCK TABLES `Cliente` WRITE;
/*!40000 ALTER TABLE `Cliente` DISABLE KEYS */;
INSERT INTO `Cliente` VALUES
(3,'https://img.cliente.com/3.jpg'),
(6,'https://img.cliente.com/6.jpg'),
(9,'https://img.cliente.com/9.jpg');
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
-- Table structure for table `Gestor`
--

DROP TABLE IF EXISTS `Gestor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Gestor` (
  `ID` int(11) NOT NULL,
  `P_Producto` tinyint(1) DEFAULT NULL,
  `P_Inventario` tinyint(1) DEFAULT NULL,
  `P_Pedidos` tinyint(1) DEFAULT NULL,
  `P_Validacion` tinyint(1) DEFAULT NULL,
  `P_Soporte` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `Gestor_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `Usuario` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Gestor`
--

LOCK TABLES `Gestor` WRITE;
/*!40000 ALTER TABLE `Gestor` DISABLE KEYS */;
INSERT INTO `Gestor` VALUES
(2,0,1,0,0,0),
(5,1,0,1,0,0),
(8,0,1,1,0,1);
/*!40000 ALTER TABLE `Gestor` ENABLE KEYS */;
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
  `Estado` enum('pendiente','pago','entregado','cancelado') DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `ID_Cliente` (`ID_Cliente`),
  KEY `ID_DatosEnvio` (`ID_DatosEnvio`),
  CONSTRAINT `Pedido_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `Usuario` (`ID`),
  CONSTRAINT `Pedido_ibfk_2` FOREIGN KEY (`ID_DatosEnvio`) REFERENCES `DatosEnvio` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pedido`
--

LOCK TABLES `Pedido` WRITE;
/*!40000 ALTER TABLE `Pedido` DISABLE KEYS */;
INSERT INTO `Pedido` VALUES
(1,6,1,1377.58,'pendiente','2025-05-19 18:59:40'),
(2,9,2,1720.23,'pendiente','2025-05-19 18:59:40'),
(3,3,3,1633.00,'pendiente','2025-05-19 18:59:40'),
(4,6,4,1412.20,'pendiente','2025-05-19 18:59:40'),
(5,9,5,1097.62,'pendiente','2025-05-19 18:59:40'),
(6,3,6,2336.20,'pendiente','2025-05-19 18:59:40'),
(7,6,7,1529.91,'pendiente','2025-05-19 18:59:40'),
(8,9,8,2126.44,'pendiente','2025-05-19 18:59:40'),
(9,3,9,1536.95,'pendiente','2025-05-19 18:59:40'),
(10,6,10,660.43,'pendiente','2025-05-19 18:59:40'),
(11,9,1,24.00,'pendiente','2025-05-28 19:19:26');
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
  `Marca` varchar(100) DEFAULT NULL,
  `URL_Imagen` varchar(255) DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`),
  KEY `ID_Categoria` (`ID_Categoria`),
  CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`ID_Categoria`) REFERENCES `Categoria` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Producto`
--

LOCK TABLES `Producto` WRITE;
/*!40000 ALTER TABLE `Producto` DISABLE KEYS */;
INSERT INTO `Producto` VALUES
(1,'Producto1','Descripción del producto 1',974.06,29,4,'Samsung','https://img.producto.com/1.jpg','2025-05-19 18:59:40'),
(2,'Producto2','Descripción del producto 2',1983.09,100,2,'Gigabyte','https://img.producto.com/2.jpg','2025-05-19 18:59:40'),
(3,'Producto3','Descripción del producto 3',1872.05,11,3,'HP','https://img.producto.com/3.jpg','2025-05-19 18:59:40'),
(4,'Producto4','Descripción del producto 4',1800.42,57,1,'Logitech','https://img.producto.com/4.jpg','2025-05-19 18:59:40'),
(5,'Producto5','Descripción del producto 5',933.50,77,5,'Gigabyte','https://img.producto.com/5.jpg','2025-05-19 18:59:40'),
(6,'Producto6','Descripción del producto 6',1159.78,57,2,'Apple','https://img.producto.com/6.jpg','2025-05-19 18:59:40'),
(7,'Producto7','Descripción del producto 7',1861.14,10,1,'Acer','https://img.producto.com/7.jpg','2025-05-19 18:59:40'),
(8,'Producto8','Descripción del producto 8',513.26,100,1,'Dell','https://img.producto.com/8.jpg','2025-05-19 18:59:40'),
(9,'Producto9','Descripción del producto 9',262.53,41,1,'Dell','https://img.producto.com/9.jpg','2025-05-19 18:59:40'),
(10,'Producto10','Descripción del producto 10',1453.25,28,5,'Asus','https://img.producto.com/10.jpg','2025-05-19 18:59:40'),
(11,'Producto11','Descripción del producto 11',1026.94,94,5,'Asus','https://img.producto.com/11.jpg','2025-05-19 18:59:40'),
(12,'Producto12','Descripción del producto 12',1929.93,51,4,'Acer','https://img.producto.com/12.jpg','2025-05-19 18:59:40'),
(13,'Producto13','Descripción del producto 13',219.84,65,6,'MSI','https://img.producto.com/13.jpg','2025-05-19 18:59:40'),
(14,'Producto14','Descripción del producto 14',1362.58,39,4,'Dell','https://img.producto.com/14.jpg','2025-05-19 18:59:40'),
(15,'Producto15','Descripción del producto 15',323.08,24,4,'MSI','https://img.producto.com/15.jpg','2025-05-19 18:59:40'),
(16,'Producto16','Descripción del producto 16',1785.28,1,4,'Acer','https://img.producto.com/16.jpg','2025-05-19 18:59:40'),
(17,'Producto17','Descripción del producto 17',534.25,42,2,'HP','https://img.producto.com/17.jpg','2025-05-19 18:59:40'),
(18,'Producto18','Descripción del producto 18',493.27,1,3,'Asus','https://img.producto.com/18.jpg','2025-05-19 18:59:40'),
(19,'Producto19','Descripción del producto 19',1862.18,67,1,'MSI','https://img.producto.com/19.jpg','2025-05-19 18:59:40'),
(20,'Producto20','Descripción del producto 20',678.06,100,5,'Dell','https://img.producto.com/20.jpg','2025-05-19 18:59:40');
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
(1,10,4,314.92),
(2,11,3,668.39),
(3,7,3,157.59),
(4,4,1,1773.38),
(5,15,1,762.48),
(6,17,2,376.79),
(7,4,1,344.35),
(8,1,2,1520.89),
(9,16,4,1267.22),
(10,15,2,415.71);
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
  `Rol` enum('administrador','gestor','cliente') DEFAULT NULL,
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
(1,'Juan','Gómez','juan.gómez@correo.com','pass0',1,'administrador','2025-05-19 18:59:40'),
(2,'Ana','Pérez','ana.pérez@correo.com','pass1',0,'gestor','2025-05-19 18:59:40'),
(3,'Pedro','López','pedro.lópez@correo.com','pass2',0,'cliente','2025-05-19 18:59:40'),
(4,'Lucía','Rodríguez','lucía.rodríguez@correo.com','pass3',1,'administrador','2025-05-19 18:59:40'),
(5,'Marcos','Fernández','marcos.fernández@correo.com','pass4',0,'gestor','2025-05-19 18:59:40'),
(6,'Sofía','Martínez','sofía.martínez@correo.com','pass5',1,'cliente','2025-05-19 18:59:40'),
(7,'Diego','Díaz','diego.díaz@correo.com','pass6',1,'administrador','2025-05-19 18:59:40'),
(8,'Elena','Moreno','elena.moreno@correo.com','pass7',0,'gestor','2025-05-19 18:59:40'),
(9,'Carlos','Álvarez','carlos.álvarez@correo.com','pass8',0,'cliente','2025-05-19 18:59:40'),
(10,'Valeria','Romero','valeria.romero@correo.com','pass9',0,'administrador','2025-05-19 18:59:40');
/*!40000 ALTER TABLE `Usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Valoracion`
--

DROP TABLE IF EXISTS `Valoracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Valoracion` (
  `ID_Cliente` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Clasificacion` int(11) DEFAULT NULL CHECK (`Clasificacion` between 1 and 5),
  `Comentario` text DEFAULT NULL,
  `CreatedAt` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ID_Cliente`,`ID_Producto`),
  KEY `ID_Producto` (`ID_Producto`),
  CONSTRAINT `Valoracion_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `Cliente` (`ID`),
  CONSTRAINT `Valoracion_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `Producto` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Valoracion`
--

LOCK TABLES `Valoracion` WRITE;
/*!40000 ALTER TABLE `Valoracion` DISABLE KEYS */;
INSERT INTO `Valoracion` VALUES
(3,2,3,'Comentario del cliente 3 sobre producto 2','2025-05-19 18:59:40'),
(3,16,2,'Comentario del cliente 3 sobre producto 16','2025-05-19 18:59:40'),
(3,20,5,'Comentario del cliente 3 sobre producto 20','2025-05-19 18:59:40'),
(6,1,4,'Comentario del cliente 6 sobre producto 1','2025-05-19 18:59:40'),
(6,6,4,'Comentario del cliente 6 sobre producto 6','2025-05-19 18:59:40'),
(6,8,3,'Comentario del cliente 6 sobre producto 8','2025-05-19 18:59:40'),
(6,9,3,'Comentario del cliente 6 sobre producto 9','2025-05-19 18:59:40'),
(9,3,5,'Comentario del cliente 9 sobre producto 3','2025-05-19 18:59:40'),
(9,4,4,'Comentario del cliente 9 sobre producto 4','2025-05-19 18:59:40'),
(9,19,2,'Comentario del cliente 9 sobre producto 19','2025-05-19 18:59:40');
/*!40000 ALTER TABLE `Valoracion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-28 19:30:16
