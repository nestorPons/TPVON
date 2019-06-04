CREATE TABLE `login` (
  `id` SERIAL,
  `id_usuario` INT(11) NOT NULL REFERENCES `usuarios` (`id`),
  `token` varchar(255) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL UNIQUE KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci;
