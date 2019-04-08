CREATE TABLE `lineas` (
  `id` SERIAL PRIMARY KEY,
  `id_ticket` BIGINT(20) NOT NULL REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `articulos` int(11) NOT NULL REFERENCES `articulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  `precio` float(10) NOT NULL, 
  `cantidad` int(10) DEFAULT 1 NOT NULL, 
  `iva`float(5) NOT NULL, 
  `dto` float(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
