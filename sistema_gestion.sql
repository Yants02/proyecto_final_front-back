-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2026 a las 01:41:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_gestion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `dni` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `dni`, `nombre`, `email`, `usuario_id`) VALUES
(3, 43977228, 'Juan Yotti', 'yottijuan2002@gmail.com', 4),
(4, 43977228, 'Juan Yotti', 'yottijuan2002@gmail.com', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `nombre_proveedor` varchar(100) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `id_proveedor`, `nombre_proveedor`, `fecha`, `total`, `usuario_id`) VALUES
(33, 9, 'Arcorrrrr', '2026-05-28 18:21:43', 22000.00, NULL),
(35, 2, 'arcor2', '2026-05-29 03:47:25', 10000.00, NULL),
(40, 2, 'arcor2', '2026-06-01 02:03:10', 20000.00, NULL),
(41, 2, 'arcor2', '2026-06-01 02:04:55', 100000.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_costo` decimal(10,2) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `id_compra`, `id_producto`, `nombre_producto`, `cantidad`, `precio_costo`, `usuario_id`) VALUES
(19, 33, NULL, 'coca cola', 11, 2000.00, NULL),
(21, 35, 8, 'harina', 100, 100.00, NULL),
(26, 40, 12, 'Sprite 3 Litros', 100, 200.00, NULL),
(27, 41, 7, 'coca', 100, 1000.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `id_venta`, `id_producto`, `nombre_producto`, `cantidad`, `precio_venta`, `usuario_id`) VALUES
(1, 1, 11, 'Coca Cola Zero 1.5 Litros', 2, 0.02, NULL),
(2, 2, 11, 'Coca Cola Zero 1.5 Litros', 12, 2300.00, NULL),
(3, 2, 8, 'harina', 10, 2000.00, NULL),
(4, 2, 10, 'Sprite 3Litros', 1, 3500.00, NULL),
(5, 3, NULL, 'coca cola', 11, 100.00, NULL),
(6, 4, 10, 'Sprite 3Litros', 10, 3500.00, NULL),
(7, 5, NULL, 'coca cola', 10, 100.00, NULL),
(8, 6, NULL, 'coca cola', 70, 100.00, NULL),
(9, 7, NULL, 'coca cola', 10, 100.00, NULL),
(10, 8, NULL, 'cereal', 10, 100.00, NULL),
(13, 11, NULL, 'chocolate', 51, 100.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio_venta`, `stock`, `usuario_id`) VALUES
(7, 'coca', 1200.00, 2, NULL),
(8, 'harina', 2000.00, 18, NULL),
(10, 'Sprite 3Litros', 3500.00, 25, NULL),
(11, 'Coca Cola Zero 1.5 Litros', 2300.00, 40, 4),
(12, 'Sprite 3 Litros', 4540.00, 25, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`, `direccion`, `usuario_id`) VALUES
(2, 'arcor2', '11392826', 'arcor2@gmail.com', 'calle 1234', NULL),
(9, 'Arcorrrrr', '1198472630', 'arcorarg@gmail.com', 'calle 4321', 4),
(10, 'Arcorrrrr', '1184927234', 'arcorarg@gmail.com', 'calle 3245', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('administrador','ventas') NOT NULL DEFAULT 'ventas',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `created_at`) VALUES
(1, 'Juan Yotti', 'jy2002@gmail.com', '20enero02', 'administrador', '2026-04-22 20:46:12'),
(2, 'Pablo Yotti', 'pabloyotti@gmail.com', '$2y$10$9U.pdIdaYk0B1B5GiE6YyO9dxsNeJYKNIU7bzMYBigKFAoDqxmO76', 'ventas', '2026-04-22 20:52:17'),
(4, 'Juan Yotti', 'yottijuan2002@gmail.com', '$2y$10$ZcD5Eq.B0iiZikdPY8PqSeze8tCHRj1D9yGuIqp6adnuFblhvf1J2', 'administrador', '2026-05-18 20:48:02'),
(5, 'Belen Yotti', 'belyotti@gmail.com', '$2y$10$prmuRgL0epLXqVzNkFmxcuToScXM6OD/CZJKUokw7qC8I72JYi5K.', 'ventas', '2026-05-18 20:53:39'),
(6, 'Lionel Messi', 'LM10@gmail.com', '$2y$10$.M9x.XM56buYDmk51iNYTuASIk.lsoH0Ni8eD6xPRBDK6HwMoLUEm', 'administrador', '2026-05-26 22:13:06'),
(8, 'usuario prueba 1', 'user1@gmail.com', '$2y$10$rB0zndgbyVOCc/N2xQk5a.RFr4AM5z7O5qvO3cYyQI9aP8lSrzd7G', 'administrador', '2026-06-01 19:17:39'),
(9, 'admin', 'admin@gmail.com', '$2y$10$Sb8lde7xyqYMGpJ2Miak7uGkEyLdZxeY9KLrZmO4SRAmER28i8dhO', 'administrador', '2026-06-01 19:54:34'),
(10, 'ventas', 'ventas@gmail.com', '$2y$10$jVBt38zMB7GQ.2E5KUi8CunYwfDd9pRMabOXyKLWZpJxyoXiFfYEe', 'ventas', '2026-06-01 19:56:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT 0.00,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_cliente`, `nombre_cliente`, `fecha`, `total`, `usuario_id`) VALUES
(1, 4, 'Juan Yotti', '2026-05-28 03:26:33', 0.04, NULL),
(2, NULL, 'Jesús Ignacio', '2026-05-29 02:41:41', 51100.00, NULL),
(3, 3, 'Juan Yotti', '2026-05-29 03:07:19', 1100.00, NULL),
(4, 4, 'Juan Yotti', '2026-05-29 03:09:09', 35000.00, NULL),
(5, 3, 'Juan Yotti', '2026-05-29 03:13:01', 1000.00, NULL),
(6, 3, 'Juan Yotti', '2026-05-29 03:13:49', 7000.00, NULL),
(7, 3, 'Juan Yotti', '2026-05-29 03:21:38', 1000.00, NULL),
(8, NULL, 'Jesús Ignacio', '2026-05-29 04:03:30', 1000.00, NULL),
(11, 3, 'Juan Yotti', '2026-06-01 02:06:48', 5100.00, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `detalle_ventas_ibfk_2` (`id_producto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `usuario_id_2` (`usuario_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_clientes_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_compras_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_detalle_compras_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_detalle_ventas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD CONSTRAINT `fk_proveedores_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
