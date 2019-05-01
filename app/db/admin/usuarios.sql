CREATE TABLE `usuarios` (
  `id` int(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `id_empresa` INT(11) NOT NULL REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `dni` char(9) NOT NULL UNIQUE KEY,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;