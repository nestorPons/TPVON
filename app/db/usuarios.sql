CREATE TABLE `usuarios` (
  `id` int(11) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
  `DNI` char(9) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(60) NOT NULL, 
  `fecha_nacimiento` int DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 desabilitado, 1 activo, 2 bloqueado',
  `password` varchar(30) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL,
  `intentos` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci;