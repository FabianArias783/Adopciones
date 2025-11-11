-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 22:33:22
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `eliminado` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellidos`, `correo`, `pass`, `eliminado`) VALUES
(1, 'Marco', 'Madrigal', 'marquitomadrigal19@gmail.com', '1234', 0),
(2, 'Prueba', '1', 'prueba@gmail.com', '1234', 0),
(4, 'Fabian', 'Arias', 'fatakerkane@gmail.com', '202cb962ac59075b964b07152d234b70', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `apellidos` varchar(128) NOT NULL,
  `correo` varchar(128) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `rol` int(1) NOT NULL,
  `archivo_n` varchar(255) DEFAULT NULL,
  `archivo` varchar(128) DEFAULT NULL,
  `eliminado` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `apellidos`, `correo`, `pass`, `rol`, `archivo_n`, `archivo`, `eliminado`) VALUES
(1, 'Marco', 'Madrigal', 'marquitomadrigal19@gmail.com', '$2y$10$Osd9cGNkCdhc3MoHs5SwqeGGXPXeSTmJgkW3pjLcfJAayhroS3h/q', 1, 'Perdil.jpg', 'img_674758b897fd1.jpg', 0),
(34, 'Fabian', 'Arias', 'fatakerkane@gmail.com', '$2y$10$xed/buN22bm7QN2rnZnNR.ZEpAl2oEWgd3Lf4V/8/2rpy.2sCYjXe', 1, 'default.jpg', 'default.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT 0.00,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `fecha`, `total`, `status`) VALUES
(1, 1, '2024-11-25 15:52:26', 24.00, 1),
(2, 2, '2024-11-25 16:24:06', 24.00, 1),
(3, 1, '2024-11-30 15:33:03', 12.00, 1),
(4, 1, '2024-11-30 16:16:32', 36.00, 1),
(5, 1, '2024-12-01 01:08:50', 12.00, 1),
(6, 1, '2024-12-01 02:05:59', 0.00, 1),
(7, 1, '2024-12-01 02:13:47', 0.00, 1),
(8, 1, '2024-12-01 02:16:12', 0.00, 1),
(9, 1, '2024-12-01 02:55:22', 0.00, 1),
(10, 1, '2024-12-01 02:57:28', 0.00, 1),
(11, 1, '2024-12-01 03:02:53', 0.00, 1),
(12, 1, '2024-12-01 03:05:49', 0.00, 1),
(13, 1, '2024-12-01 03:05:58', 0.00, 1),
(14, 1, '2024-12-01 03:08:02', 0.00, 1),
(15, 1, '2024-12-01 03:09:50', 0.00, 1),
(16, 1, '2024-12-01 03:11:35', 0.00, 1),
(17, 1, '2024-12-01 03:17:53', 0.00, 1),
(18, 1, '2024-12-01 03:19:45', 0.00, 1),
(19, 1, '2024-12-01 03:23:46', 0.00, 1),
(20, 1, '2024-12-01 03:24:11', 0.00, 1),
(21, 1, '2024-12-01 03:25:13', 0.00, 1),
(22, 1, '2024-12-01 03:27:00', 0.00, 1),
(23, 1, '2024-12-01 03:28:51', 0.00, 1),
(24, 1, '2024-12-01 03:29:09', 0.00, 1),
(25, 1, '2024-12-01 03:31:31', 0.00, 1),
(26, 1, '2024-12-01 03:32:36', 0.00, 1),
(27, 1, '2024-12-01 03:36:44', 0.00, 1),
(28, 1, '2024-12-01 03:42:14', 0.00, 1),
(29, 1, '2024-12-01 03:58:37', 0.00, 1),
(30, 1, '2024-12-04 01:05:54', 0.00, 1),
(31, 1, '2024-12-04 02:02:59', 0.00, 1),
(32, 1, '2024-12-04 04:31:48', 0.00, 1),
(33, 1, '2024-12-04 14:15:07', 0.00, 1),
(34, 1, '2024-12-04 14:33:19', 0.00, 1),
(35, 1, '2024-12-04 14:41:06', 0.00, 1),
(36, 1, '2025-03-03 22:23:00', 0.00, 1),
(37, 1, '2025-03-05 23:43:18', 0.00, 1),
(38, 1, '2025-03-06 23:14:57', 0.00, 1),
(39, 1, '2025-03-06 23:49:02', 0.00, 1),
(40, 1, '2025-05-08 16:09:23', 0.00, 1),
(41, 1, '2025-05-08 17:23:09', 0.00, 1),
(42, 1, '2025-05-08 17:38:28', 0.00, 1),
(43, 1, '2025-05-08 17:48:54', 0.00, 1),
(44, 1, '2025-05-08 17:54:51', 0.00, 1),
(45, 1, '2025-05-08 18:14:38', 0.00, 1),
(46, 1, '2025-05-08 18:37:54', 0.00, 1),
(47, 1, '2025-05-08 19:34:44', 0.00, 1),
(48, 1, '2025-05-08 19:55:11', 0.00, 1),
(49, 1, '2025-05-08 21:00:46', 0.00, 1),
(50, 1, '2025-05-08 21:03:16', 0.00, 1),
(51, 1, '2025-05-08 21:04:10', 0.00, 1),
(52, 1, '2025-05-08 21:14:06', 0.00, 1),
(53, 1, '2025-05-08 21:14:14', 0.00, 1),
(54, 1, '2025-05-08 21:34:19', 0.00, 1),
(55, 1, '2025-05-08 21:34:51', 0.00, 1),
(56, 1, '2025-05-08 21:51:43', 0.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos_productos`
--

CREATE TABLE `pedidos_productos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos_productos`
--

INSERT INTO `pedidos_productos` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio`, `subtotal`) VALUES
(10, 1, 6, 11, 12.00, 132.00),
(11, 3, 6, 3, 12.00, 36.00),
(12, 4, 6, 3, 12.00, 36.00),
(16, 5, 6, 1, 12.00, 12.00),
(17, 6, 6, 1, 12.00, 12.00),
(18, 7, 6, 1, 12.00, 12.00),
(19, 8, 6, 1, 12.00, 12.00),
(20, 9, 6, 1, 12.00, 12.00),
(21, 10, 6, 1, 12.00, 12.00),
(22, 11, 6, 1, 12.00, 12.00),
(23, 12, 6, 1, 12.00, 12.00),
(24, 13, 6, 1, 12.00, 12.00),
(25, 14, 6, 1, 12.00, 12.00),
(26, 15, 6, 1, 12.00, 12.00),
(27, 16, 6, 1, 12.00, 12.00),
(28, 17, 6, 1, 12.00, 12.00),
(29, 18, 6, 1, 12.00, 12.00),
(30, 19, 6, 1, 12.00, 12.00),
(31, 20, 6, 1, 12.00, 12.00),
(32, 21, 6, 1, 12.00, 12.00),
(33, 22, 6, 1, 12.00, 12.00),
(34, 23, 6, 1, 12.00, 12.00),
(35, 24, 6, 1, 12.00, 12.00),
(36, 25, 6, 1, 12.00, 12.00),
(38, 26, 6, 1, 12.00, 12.00),
(39, 27, 6, 7, 12.00, 84.00),
(40, 28, 6, 2, 12.00, 24.00),
(41, 29, 6, 1, 12.00, 12.00),
(44, 30, 9, 1, 1999.99, 1999.99),
(45, 31, 9, 1, 1999.99, 1999.99),
(46, 32, 7, 5, 1999.00, 9995.00),
(47, 33, 7, 5, 1998.00, 9990.00),
(48, 33, 8, 2, 499.99, 999.98),
(49, 33, 9, 4, 1999.99, 7999.96),
(50, 34, 7, 4, 1998.00, 7992.00),
(51, 34, 8, 1, 499.99, 499.99),
(52, 34, 9, 1, 1999.99, 1999.99),
(53, 35, 7, 7, 1998.00, 13986.00),
(55, 35, 9, 1, 1999.99, 1999.99),
(56, 36, 15, 11, 29999.99, 329999.89),
(57, 36, 9, 10, 1999.99, 19999.90),
(58, 37, 8, 1, 499.00, 499.00),
(59, 38, 7, 12, 1998.00, 23976.00),
(60, 39, 8, 10, 499.00, 4990.00),
(65, 40, 20, 1, 0.00, 0.00),
(66, 41, 18, 1, 0.00, 0.00),
(67, 42, 18, 1, 0.00, 0.00),
(68, 43, 18, 1, 0.00, 0.00),
(70, 44, 17, 1, 0.00, 0.00),
(71, 45, 18, 1, 0.00, 0.00),
(75, 46, 18, 1, 0.00, 0.00),
(77, 48, 17, 0, 0.00, 0.00),
(78, 49, 18, 0, 0.00, 0.00),
(79, 50, 17, 1, 0.00, 0.00),
(80, 51, 19, 1, 0.00, 0.00),
(81, 51, 17, 0, 0.00, 0.00),
(82, 52, 19, 1, 0.00, 0.00),
(83, 53, 18, 1, 0.00, 0.00),
(84, 54, 18, 1, 0.00, 0.00),
(85, 55, 18, 1, 0.00, 0.00),
(86, 56, 18, 1, 0.00, 0.00),
(87, 56, 17, 0, 0.00, 0.00),
(88, 2, 18, 1, 0.00, 0.00),
(89, 2, 19, 1, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `codigo` varchar(32) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` double NOT NULL,
  `stock` int(11) NOT NULL,
  `archivo_n` varchar(255) DEFAULT NULL,
  `archivo` varchar(128) DEFAULT NULL,
  `eliminado` int(11) DEFAULT 0,
  `status` enum('Disponible','Adoptado') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `codigo`, `descripcion`, `costo`, `stock`, `archivo_n`, `archivo`, `eliminado`, `status`) VALUES
(6, 'Agua', '44360', 'agua', 12, 1000, 'Botella de agua.jpg', 'img_6744d5a5f046c.jpg', 1, 'Disponible'),
(7, 'CAMISA KAKA 2006/2007', '12345679', 'Revive la magia del fútbol con esta camisa auténtica de Kaká, inspirada en la legendaria temporada 2007, cuando brilló como uno de los mejores jugadores del mundo.(Talla M)', 1998, 20, 'KAKA.avif', 'img_674fa80255753.avif', 1, 'Disponible'),
(8, 'REI CHIQUITA', '123456789', 'Descubre el encanto de esta figura miniatura de Rei Ayanami, diseñada con detalles exquisitos que capturan la esencia de este icónico personaje.', 499, 10, 'rei chiquita.jpg', 'img_674fa9c9d7338.jpg', 1, 'Disponible'),
(9, 'CAMISA CR7 2007/2008', '1234567', 'Hazte con esta camiseta histórica de Cristiano Ronaldo, inspirada en su inolvidable temporada 2008, cuando dominó el fútbol mundial con su habilidad y determinación.', 1999.99, 10, 'Cr7.jpg', 'img_674faa8b8324a.jpg', 1, 'Disponible'),
(10, 'IPHONE 16 PRO MAX', '123456', 'Descubre el máximo poder y elegancia con el iPhone 16 Pro Max, un dispositivo que redefine lo que significa innovar.\r\n\r\nPantalla de última generación: Una pantalla OLED Super Retina XDR de 6.9\", con tecnología ProMotion y Dynamic Island, para una experiencia visual fluida e inmersiva.\r\nRendimiento imbatible: Equipado con el chip A18 Pro y una arquitectura revolucionaria, ofrece velocidad y eficiencia nunca vistas.\r\nFotografía de nivel profesional: Sistema de cámaras avanzado con un sensor principal de 48 MP, teleobjetivo periscópico y mejoras de captura nocturna, para imágenes nítidas en cualquier condición.\r\nDurabilidad premium: Fabricado con titanio de grado aeroespacial, es ligero, resistente y elegante. Además, resistente al agua y polvo (IP68).', 29999.99, 100, 'Iphone 16 pm.jpg', 'img_674fac6139baf.jpg', 1, 'Disponible'),
(11, '11111', '1111111', '111', 11, 111, 'KAKA.avif', 'img_674fae078572e.avif', 1, 'Disponible'),
(12, '11111111', '11111111111', '11', 11, 11, 'KAKA.avif', 'img_674faef38d722.avif', 1, 'Disponible'),
(13, '111111', '12', '1111', 1111, 11, 'Cr7.jpg', 'img_67505efd9d972.jpg', 1, 'Disponible'),
(14, '111111', '123456799', '11', 1111, 11, 'rei chiquita.jpg', 'img_67505fbd7dbb2.jpg', 1, 'Disponible'),
(15, 'producto prueba', '1234', 'Descubre el máximo poder y elegancia con el iPhone 16 Pro Max, un dispositivo que redefine lo que significa innovar.\r\n\r\nPantalla de última generación: Una pantalla OLED Super Retina XDR de 6.9\", con tecnología ProMotion y Dynamic Island, para una experiencia visual fluida e inmersiva.\r\nRendimiento imbatible: Equipado con el chip A18 Pro y una arquitectura revolucionaria, ofrece velocidad y eficiencia nunca vistas.\r\nFotografía de nivel profesional: Sistema de cámaras avanzado con un sensor principal de 48 MP, teleobjetivo periscópico y mejoras de captura nocturna, para imágenes nítidas en cualquier condición.\r\nDurabilidad premium: Fabricado con titanio de grado aeroespacial, es ligero, resistente y elegante. Además, resistente al agua y polvo (IP68).', 29999.99, 100, 'rei chiquita.jpg', 'img_675069583f0ed.jpg', 1, 'Disponible'),
(16, 'Perro', 'Macho', 'perro bonito', 0, 1, 'dog.1.jpg', 'img_67ca3590c7cfd.jpg', 0, 'Disponible'),
(17, 'GATO', 'Hembra', 'Sarai', 0, 1, 'cat.15.jpg', 'img_67ca361bdf6d8.jpg', 0, 'Disponible'),
(18, 'Firulais', '123', 'Perro bonito', 0, 1, 'dog.2.jpg', 'img_681ce1d84a97e.jpg', 0, 'Disponible'),
(19, 'Guero', '111222', 'Gato Bonito', 0, 1, 'cat.57.jpg', 'img_681ce2a4af12e.jpg', 0, 'Disponible'),
(20, 'Panfilo', '0510', 'Gato bonito', 0, 1, 'cat.59.jpg', 'img_681ce4b4c9d5c.jpg', 0, 'Disponible'),
(21, 'Benito', '00315', 'Gato muy bonito', 0, 1, 'cat.3.jpg', 'img_681ce4daada5b.jpg', 0, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `archivo` varchar(64) NOT NULL,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id`, `nombre`, `archivo`, `eliminado`) VALUES
(1, '2x1', '77bbb1e009670efc1b4014a9820bd598.jpg', 1),
(2, 'a', '77bbb1e009670efc1b4014a9820bd598.jpg', 1),
(3, '2*5', '77bbb1e009670efc1b4014a9820bd598.jpg', 1),
(4, '1234', '77bbb1e009670efc1b4014a9820bd598.jpg', 1),
(5, '2x1 en agua natural (3 pack)', '77bbb1e009670efc1b4014a9820bd598.jpg', 1),
(6, 'Messi', 'bcb900b0e3f0cd3a795348694a5c952a.png', 1),
(7, 'Marco', '888af9d8709ba0751f9626af89bdad37.png', 1),
(8, 'sale', 'b621147b15d2e3c25c3de47d530747ee.jpg', 1),
(9, 'Sale', '3707f0ffe0a3463a12e77c0715a78e0f.png', 1),
(10, 'sale', '3707f0ffe0a3463a12e77c0715a78e0f.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `razon_rechazo` text DEFAULT NULL,
  `mascotas_previas` varchar(10) DEFAULT NULL,
  `tipo_vivienda` varchar(50) DEFAULT NULL,
  `ninos_mayores` varchar(10) DEFAULT NULL,
  `espacio_exterior` varchar(10) DEFAULT NULL,
  `horas_solo` int(11) DEFAULT NULL,
  `respuesta_responsabilidad` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id`, `cliente_id`, `producto_id`, `motivo`, `estado`, `fecha`, `razon_rechazo`, `mascotas_previas`, `tipo_vivienda`, `ninos_mayores`, `espacio_exterior`, `horas_solo`, `respuesta_responsabilidad`, `telefono`) VALUES
(6, 4, 18, 'Compañero', 'aprobada', '2025-11-12 02:43:20', '', 'si', 'casa', 'no', 'si', 2, 'Lo enseño', '3323286466'),
(7, 4, 17, 'para guardia', 'rechazada', '2025-11-12 02:44:52', 'Muchas horas solo y sin lugar para que esté libre, no es un guardia.', 'si', 'casa', 'si', 'no', 10, 'Nada', '3323286466');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `pedidos_productos`
--
ALTER TABLE `pedidos_productos`
  ADD CONSTRAINT `pedidos_productos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedidos_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `solicitudes_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
