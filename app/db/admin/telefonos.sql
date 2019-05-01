CREATE TABLE `telefonos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_empresa` INT(11) NOT NULL REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `nombre`varchar(30) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL, 
  `numero` char(13) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;
