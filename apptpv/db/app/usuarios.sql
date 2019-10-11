CREATE TABLE `usuarios` (
  `id` int(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `codigo` varchar(10) DEFAULT NULL,
  `dni` char(9) NULL UNIQUE ,
  `nombre` varchar(90) NOT NULL,
  `email` varchar(60) NULL UNIQUE, 
  `tel` varchar(10) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 desactivado, 1 activo, 2 bloqueado',
  `nivel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '2 administrador, 1 usuario, 0 cliente',
  `password` varchar(255) NULL,
  `promos` tinyint(2) DEFAULT '0', 
  `intentos` tinyint(2) NOT NULL DEFAULT '0',
  `obs` varchar(100) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_spanish2_ci;