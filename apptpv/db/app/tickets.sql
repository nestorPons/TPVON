CREATE TABLE `tickets` (
  `id` SERIAL PRIMARY KEY,
  `id_usuario` INT(11) NOT NULL REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP, 
  `id_cliente` INT(11) NOT NULL REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `estado` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 activo, 0 inactivo',
  `regalo` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 regalo, 0 no regalo',
  `iva`tinyint (3) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;
