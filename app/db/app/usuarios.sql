CREATE TABLE `usuarios` (
  `id` int(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `dni` char(9) NULL UNIQUE,
  `nombre` varchar(90) NOT NULL,
  `email` varchar(60) NULL UNIQUE, 
  `fecha_nacimiento` int DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 desabilitado, 1 activo, 2 bloqueado',
  `nivel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '2 administrador, 1 usuario, 0 cliente',
  `password` varchar(255) NULL,
  `intentos` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;