CREATE DATABASE IF NOT EXISTS `admin_empresas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `admin_empresas`;

CREATE TABLE `facturacion` (
  `id` int(11) UNSIGNED NOT NULL PRIMARY KEY,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL UNIQUE KEY,
  `telefono` varchar(12) NULL,
  `calle` varchar(50) NOT NULL,
  `numero` SMALLINT NOT NULL,
  `piso` tinyint DEFAULT NULL,
  `escalera` char(1) DEFAULT NULL,
  `poblacion` varchar(30) DEFAULT NULL, 
  `CP` SMALLINT(5) DEFAULT NULL,
  `provincia` VARCHAR(30) DEFAULT NULL,
  `pais` VARCHAR(30) DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;