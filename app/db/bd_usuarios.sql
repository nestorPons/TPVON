
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8_spanish_ci,
  `email` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `tel` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `obs` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `lang` tinyint(1) NOT NULL DEFAULT '1',
  `date_reg` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_drop` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 = active, 1 = block bruteForce, 2= block autorization',
  `attempts` tinyint(4) NOT NULL DEFAULT '0',
  `color` varchar(8) COLLATE utf8_spanish_ci DEFAULT NULL,
  `auth_email` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);