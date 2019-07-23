CREATE TABLE `promos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(10) NOT NULL UNIQUE,
  `nombre` varchar(30) DEFAULT NULL UNIQUE,
  `descripcion` varchar(100) DEFAULT NULL, 
  `precio` float(10) NOT NULL,
  `coste` float(10) DEFAULT NULL,
  `id_iva` TINYINT(1) NOT NULL  DEFAULT '1' REFERENCES `tipo_iva` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `tipo` TINYINT(1) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 desabilitado, 1 activo'
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;