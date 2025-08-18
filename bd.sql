-- Creación de la Base de Datos
CREATE DATABASE IF NOT EXISTS `centro_impresion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `centro_impresion`;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL CHECK (`rol` in ('administrador','empleado')),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `clientes`
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `pedidos`
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `costo_total` decimal(10,2) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `items_pedido`
CREATE TABLE `items_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `tipo_item` varchar(50) NOT NULL,
  `maquina` varchar(100) DEFAULT NULL,
  `tipo_papel` varchar(100) DEFAULT NULL,
  `tamano_papel` varchar(50) DEFAULT NULL,
  `tinta` varchar(50) DEFAULT NULL,
  `caras` varchar(50) DEFAULT NULL,
  `acabado` varchar(100) DEFAULT NULL,
  `nombre_servicio` varchar(100) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `pagos`
CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `materiales`
CREATE TABLE `materiales` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `items_pedido_materiales`
CREATE TABLE `items_pedido_materiales` (
  `id` int(11) NOT NULL,
  `item_pedido_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `cantidad_utilizada` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `pedidos_historial`
CREATE TABLE `pedidos_historial` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `maquina_id` int(11) NOT NULL,
  `tipo_servicio` varchar(50) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- ----- ÍNDICES Y LLAVES PRIMARIAS -----

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `usuario_id` (`usuario_id`);

ALTER TABLE `items_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`);

ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

ALTER TABLE `items_pedido_materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_pedido_id` (`item_pedido_id`),
  ADD KEY `material_id` (`material_id`);

ALTER TABLE `pedidos_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `usuario_id` (`usuario_id`);


-- ----- AUTO_INCREMENT -----

ALTER TABLE `usuarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `clientes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pedidos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `items_pedido` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pagos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `materiales` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `items_pedido_materiales` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pedidos_historial` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- ----- LLAVES FORÁNEAS (CONSTRAINTS) -----

ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `items_pedido`
  ADD CONSTRAINT `items_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE;

ALTER TABLE `items_pedido_materiales`
  ADD CONSTRAINT `items_pedido_materiales_ibfk_1` FOREIGN KEY (`item_pedido_id`) REFERENCES `items_pedido` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_pedido_materiales_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materiales` (`id`) ON DELETE CASCADE;

ALTER TABLE `pedidos_historial`
  ADD CONSTRAINT `pedidos_historial_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_historial_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;