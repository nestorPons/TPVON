CREATE TABLE `login` (
  `id` SERIAL,
  `id_usuario` INT(11) NOT NULL REFERENCES `usuarios` (`id`),
  `token` varchar(255) NOT NULL UNIQUE KEY
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;