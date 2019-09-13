
CREATE TABLE `empresa` (
  `id` tinyint (1) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NULL,
  `nif` char(9) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ultimo_acceso` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) COLLATE utf8_spanish2_ci NULL,
  `telefono` varchar(12) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `calle` varchar(50) COLLATE utf8_spanish2_ci NULL,
  `numero` smallint(6) NULL,
  `piso` tinyint(4) DEFAULT NULL,
  `escalera` varchar(2) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `poblacion` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `CP` smallint(5) DEFAULT NULL,
  `provincia` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `pais` varchar(30) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa`(`id`) VALUES (1);