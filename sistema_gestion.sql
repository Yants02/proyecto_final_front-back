-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2026 a las 17:42:30
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
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `dni`, `nombre`, `email`) VALUES
(11, 45654777, 'Jesús Ignacio', 'jesusgarcxx@gmail.com'),
(12, 123657880, 'Jesús Ignacio García', 'maixl@gmacil.comm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `nombre_proveedor` varchar(100) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `id_proveedor`, `nombre_proveedor`, `fecha`, `total`) VALUES
(10, 6, 'arcor', '2026-05-06 04:08:32', 770000.00),
(15, 5, 'manaos', '2026-05-06 04:42:15', 620000.00),
(16, 5, 'manaos', '2026-05-06 04:50:47', 101400.00),
(17, 6, 'arcor', '2026-05-07 04:00:18', 0.00),
(18, 6, 'arcor', '2026-05-07 04:02:56', 0.00),
(19, 6, 'arcor', '2026-05-07 04:03:31', 0.00),
(20, 3, 'coca colax', '2026-05-07 04:03:46', 0.00),
(21, 5, 'manaos', '2026-05-07 04:05:47', 0.00),
(22, 3, 'coca colax', '2026-05-07 04:07:45', 200000.00),
(23, 3, 'coca colax', '2026-05-07 04:23:35', 0.00),
(24, 3, 'coca colax', '2026-05-07 04:23:45', 0.00),
(26, 6, 'arcor', '2026-05-07 05:10:20', 0.00),
(27, 6, 'arcor', '2026-05-07 05:19:26', 1400.00),
(28, 5, 'manaos', '2026-05-07 05:23:12', 14400.00),
(29, 5, 'manaos', '2026-05-07 05:26:05', 10000.00),
(30, 6, 'arcor', '2026-05-07 05:30:08', 10999.00),
(31, 3, 'coca colax', '2026-05-07 05:30:52', 1400.00);

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
(1, 10, 10, 'chocolate blanco', 100, 20.00),
(2, 10, 9, 'coca cola', 300, 2500.00),
(3, 10, 10, 'chocolate blanco', 600, 30.00),
(7, 15, 10, 'chocolate blanco', 100, 1200.00),
(8, 15, 9, 'coca cola', 100, 5000.00),
(9, 16, 9, 'coca cola', 100, 14.00),
(10, 16, 10, 'chocolate blanco', 500, 200.00),
(11, 22, 9, 'coca cola', 1000, 200.00),
(12, 27, 10, 'chocolate blanco', 100, 14.00),
(13, 28, 10, 'chocolate blanco', 100, 144.00),
(14, 29, 9, 'coca cola', 100, 100.00),
(15, 30, 9, 'coca cola', 100, 10.00),
(16, 30, 10, 'chocolate blanco', 100, 99.99),
(17, 31, 9, 'coca cola', 100, 14.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio_venta`, `stock`) VALUES
(9, 'coca cola', 2500.00, 1900),
(10, 'chocolate blanco', 2000.00, 4600);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(3, 'coca colax', '1122334400', 'coca2@gmail.com', 'calle 555'),
(5, 'manaos', '2255778899', 'man@gmail.com', 'calle 1234'),
(6, 'arcor', '1144778899', 'arcor@gmail.com', 'cale 7777');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `sesion_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `created_at`, `sesion_token`) VALUES
(1, 'Juan Yotti', 'jy2002@gmail.com', '20enero02', 'administrador', '2026-04-22 20:46:12', NULL),
(2, 'Pablo Yotti', 'pabloyotti@gmail.com', '$2y$10$9U.pdIdaYk0B1B5GiE6YyO9dxsNeJYKNIU7bzMYBigKFAoDqxmO76', 'ventas', '2026-04-22 20:52:17', NULL),
(4, 'Belen Yotti', 'belu@gmail.com', '$2y$10$CkGDccbAQVkav/OKACZcLud0wIms9sp8hTfXtKCRIjSj5miLHehCi', 'ventas', '2026-05-17 09:19:34', NULL),
(5, 'Andres Yotti', 'andres@gmail.com', '$2y$10$WCHcFuROl5iK8lpnYA4hSeRl6sgxWDX47g/W9iZD5yuYz6qunfMIG', 'administrador', '2026-05-17 09:25:55', NULL),
(6, 'checkbox test', 'checkbox@gmail.com', '$2y$10$R1qYPfK7/pIWyk0rOSsNj.yAoTGeduffGFSg78BpKqoRbYR/g/5dK', 'ventas', '2026-05-17 11:45:59', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

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
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
