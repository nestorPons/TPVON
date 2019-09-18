CREATE TABLE `tipo_iva`(
  `id` TINYINT(1) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `nombre` varchar(50) NOT NULL,
  `valor` float(5) NOT NULL,
  `pre` TINYINT(1)
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;