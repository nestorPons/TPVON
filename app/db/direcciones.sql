CREATE TABLE `direcciones` (
  `id` int(22) AUTO_INCREMENT NOT NULL UNSIGNED PRIMARY KEY,
  `id_usuario` int(11) NOT NULL REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `calle` varchar(50) NOT NULL,
  `numero` SMALLINT NOT NULL,
  `piso` tinyint DEFAULT NULL,
  `escalera` char(1) DEFAULT NULL,
  `poblacion` varchar(30) DEFAULT NULL, 
  `CP` SMALLINT(5) DEFAULT NULL,
  `provincia` VARCHAR(30) DEFAULT NULL,
  `pais` VARCHAR(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;