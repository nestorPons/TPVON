CREATE DATABASE IF NOT EXISTS `admin_empresas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `admin_empresas`;

CREATE TABLE `empresas` (
  `id` SERIAL PRIMARY KEY,
  `nombre` varchar(30) NOT NULL UNIQUE KEY,
  `nif` varchar(10) NOT NULL UNIQUE KEY,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sector` tinyint(1) NOT NULL,
  `plan` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_acceso` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;