CREATE TABLE `emails` (
  `id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_empresa` INT(11) NOT NULL REFERENCES `empresas` (`id`),
  `nombre`varchar(30) NOT NULL, 
  `numero` char(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;
