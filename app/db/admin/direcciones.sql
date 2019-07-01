  CREATE TABLE `direcciones` (
  `id_empresa` int(11)  PRIMARY KEY NOT NULL REFERENCES `empresas` (`id`),
  `dir` varchar(50) NOT NULL,
  `poblacion` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `cp` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 