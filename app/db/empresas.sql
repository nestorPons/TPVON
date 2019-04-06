SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `empresas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `empresas`;

CREATE TABLE `facturacion` (
  `id` SERIAL PRIMARY KEY,
  `dni` char(9) COLLATE utf8mb4_unicode_ci NOT NULL KEY,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provincia` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CP` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresa` (
  `id` SERIAL PRIMARY KEY,
  `nombre` varchar(30) NOT NULL UNIQUE KEY,
  `email` varchar(30) NOT NULL UNIQUE KEY,
  `web` varchar(30) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sector` tinyint(1) NOT NULL,
  `plan` tinyint(1) NOT NULL DEFAULT '1',
  `ultimo_acceso` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dir` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `continente` varchar(50) NOT NULL,
  `CP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id` tinyint(4) PRIMARY KEY UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` text NOT NULL,
  `cod` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
