-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2026 a las 23:39:01
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
(1, 45928501, 'Germán Bisutti', 'gerbisu@gmail.com', 1),
(2, 45654777, 'Juan Yottii', 'jy2002@gmail.com', 1),
(3, 12365788, 'Jesús Ignacio García', 'jesusgarcxx@gmail.com', 1),
(4, 38123456, 'Juan Román Riquelme', 'roman10@gmail.com', 1);

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
(1, 1, 'Arcor Oficial', '2026-05-31 18:04:33', 14000.00, 1),
(2, 2, 'Coca-Cola FEMSA', '2026-06-01 18:04:33', 20000.00, 1),
(3, 3, 'Distribuidora Tuti', '2026-06-03 18:04:33', 20000.00, 1),
(4, 4, 'Pepsico Snacks', '2026-06-04 18:04:33', 23000.00, 1);

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
  `precio_costo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id`, `id_compra`, `id_producto`, `nombre_producto`, `cantidad`, `precio_costo`) VALUES
(1, 1, 1, 'Galletitas Chocolinas 170g', 10, 400.00),
(2, 1, 2, 'Caja Bon o Bon x 30 un.', 5, 2000.00),
(3, 2, 11, 'Coca-Cola Sabor Original 1.5L', 20, 1000.00),
(4, 3, 21, 'Yerba Playadito 1Kg', 10, 2000.00),
(5, 4, 31, 'Papas Lays Clásicas 145g', 15, 1000.00),
(6, 4, 32, 'Doritos Queso 100g', 10, 800.00);

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
(1, 1, 11, 'Coca-Cola Sabor Original 1.5L', 2, 2200.00, 1),
(2, 1, 31, 'Papas Lays Clásicas 145g', 1, 2100.00, 1),
(3, 2, 1, 'Galletitas Chocolinas 170g', 1, 850.00, 1),
(4, 2, 21, 'Yerba Playadito 1Kg', 1, 4500.00, 1),
(5, 3, 17, 'Monster Energy 473ml', 2, 2100.00, 1),
(6, 3, 4, 'Alfajor Blanco Simple', 3, 500.00, 1),
(7, 4, 22, 'Yerba Taragüi 500g', 1, 2400.00, 1),
(8, 4, 5, 'Alfajor Negro Simple', 2, 500.00, 1);

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
(1, 'Galletitas Chocolinas 170g', 850.00, 45, 1),
(2, 'Caja Bon o Bon x 30 un.', 5400.00, 15, 1),
(3, 'Caramelos Butter Toffees Bolsa', 1200.00, 25, 1),
(4, 'Alfajor Blanco Simple', 500.00, 50, 1),
(5, 'Alfajor Negro Simple', 500.00, 50, 1),
(6, 'Gomitas Mogul Rollo', 400.00, 60, 1),
(7, 'Rocklets Paquete Chico', 650.00, 40, 1),
(8, 'Turrón de Maní', 250.00, 100, 1),
(9, 'Chocolate Cofler Block 170g', 2800.00, 20, 1),
(10, 'Menthoplus Menta Fuerte', 350.00, 70, 1),
(11, 'Coca-Cola Sabor Original 1.5L', 2200.00, 36, 1),
(12, 'Coca-Cola Sin Azúcar 1.5L', 2200.00, 24, 1),
(13, 'Sprite Sabor Original 1.5L', 2000.00, 30, 1),
(14, 'Fanta Naranja 1.5L', 2000.00, 20, 1),
(15, 'Agua Mineral Kin 1.5L', 1100.00, 48, 1),
(16, 'Powerade Mountain Blast 500ml', 1350.00, 30, 1),
(17, 'Monster Energy 473ml', 2100.00, 40, 1),
(18, 'Cepita Naranja 1L', 1500.00, 15, 1),
(19, 'Aquarius Manzana 1.5L', 1600.00, 25, 1),
(20, 'Schweppes Pomelo 1.5L', 1800.00, 12, 1),
(21, 'Yerba Playadito 1Kg', 4500.00, 20, 1),
(22, 'Yerba Taragüi 500g', 2400.00, 25, 1),
(23, 'Azúcar Ledesma 1Kg', 1200.00, 40, 1),
(24, 'Harina Blancaflor 0000 1Kg', 1100.00, 30, 1),
(25, 'Fideos Matarazzo Mostachol', 1300.00, 35, 1),
(26, 'Puré de Tomate Arcor 520g', 800.00, 50, 1),
(27, 'Aceite Natura 900ml', 2500.00, 20, 1),
(28, 'Leche Larga Vida La Serenísima', 1400.00, 60, 1),
(29, 'Queso Rallado La Serenísima 130g', 1900.00, 15, 1),
(30, 'Manteca La Serenísima 200g', 2200.00, 20, 1),
(31, 'Papas Lays Clásicas 145g', 2100.00, 25, 1),
(32, 'Doritos Queso 100g', 1800.00, 30, 1),
(33, 'Cheetos Queso 90g', 1600.00, 20, 1),
(34, 'Snack 3D 80g', 1500.00, 20, 1),
(35, 'Galletitas Toddy Chispas', 1200.00, 40, 1),
(36, 'Ruffles Sabor Jamón 120g', 2000.00, 15, 1),
(37, 'Twistos Jamón Ibérico', 1100.00, 25, 1),
(38, 'Pehuamar Palitos Salados', 900.00, 35, 1),
(39, 'Maní Salado Pehuamar', 1000.00, 30, 1),
(40, 'Papas Pay Lays 100g', 1700.00, 10, 1);

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
(1, 'Arcor Oficial', '1144556677', 'ventas@arcor.com.ar', 'Av. Corrientes 1234', 1),
(2, 'Coca-Cola FEMSA', '1122334455', 'pedidos@coca-cola.com.ar', 'Ruta 9 Km 30', 1),
(3, 'Distribuidora Tuti', '1166778899', 'contacto@distribuidoratuti.com', 'Calle Falsa 123', 1),
(4, 'Pepsico Snacks', '1188990011', 'ventas@pepsico.com.ar', 'Parque Industrial 5', 1);

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
(1, 'Maria Del Carmen Acri', 'm.acri@bue.edu.ar', '$2y$10$pVvL4Fj3PbQraNOkZhDoTu/r7NDLY1l05Sk9O6/XFRelpEpN0Bu/u', 'administrador', '2026-06-05 17:50:07'),
(3, 'Ventas', 'ventas@gmail.com', '$2y$10$y8yDYnSPiMXqhZYcL9nhxe3q3DdwuS6vvh1CJ.Nf/ev2kcR1Hlvz.', 'ventas', '2026-06-05 18:08:09');

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
(1, 1, 'Germán Bisutti', '2026-06-02 18:04:33', 6500.00, 1),
(2, 2, 'Juan Yottii', '2026-06-03 18:04:33', 5350.00, 1),
(3, 3, 'Jesús Ignacio García', '2026-06-04 18:04:33', 5700.00, 1),
(4, 4, 'Juan Román Riquelme', '2026-06-05 18:04:33', 3400.00, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_clientes_usuarios` (`usuario_id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

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
  ADD KEY `fk_productos_usuarios` (`usuario_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proveedores_usuarios` (`usuario_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE SET NULL;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
