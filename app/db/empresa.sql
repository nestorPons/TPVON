CREATE TABLE `empresa` (
  `id` TINYINT(1) PRIMARY KEY,
  `NIF` char(10) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_es_0900_ai_ci  NOT NULL,
  `web` varchar(50) COLLATE utf8mb4_es_0900_ai_ci ,
  `id_admin` int(11), 
  `id_usuario_facturacion` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;