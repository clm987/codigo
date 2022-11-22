-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-03-2021 a las 21:21:28
-- Versión del servidor: 8.0.13-4
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pqElWX5WY2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `mail`   varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tipo`   varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `clave`  varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `perfil` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Volcado inicial de datos para la tabla `usuario`
--
INSERT INTO `usuario` (`id`, `nombre`, `mail`, `tipo`, `clave`, `perfil`, `fechaBaja`) VALUES
(1, 'Putin', 'DrEvil@gmail.com', 'Comprador', 'Hsu23sDsjseWs','Cliente', NULL),
(2, 'Obama', 'TheDarkSide@gmail.com', 'Vendedor', 'dasdqsdw2sd23', 'Vendedor', NULL),
(3, 'Merkel','BigBossMamuska@gmail.com', 'Vendedor', 'sda2s2f332f2', 'Admin', NULL);
--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

----******************************************************************-------
----                       Tabla Arma                               *-------
----******************************************************************------- 

-- Estructura de tabla para la tabla `Arma`
--
CREATE TABLE `arma` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `nacionalidad`   varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `precio`   float(17) NOT NULL,
  `stock` int(11) NOT NULL,
  `foto` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `fechaBaja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Volcado inicial de datos para la tabla `Arma`
--
INSERT INTO `arma` (`id`, `nombre`, `nacionalidad`, `precio`, `stock`,`foto`, `fechaBaja`) VALUES
(1, 'Granada', 'Americana', '1000', '50000','/lalala/lalala',NULL),
(2, 'Pistola', 'Americana', '2000', '50000','/lalala/lalala',NULL),
(3, 'AK47', 'Americana', '5000', '50000','/lalala/lalala',NULL),;
--
-- Indices de la tabla `Arma`
--
ALTER TABLE `arma`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `Arma`
--
ALTER TABLE `arma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

----******************************************************************-------
-----                      Tabla Venta                               *-------
----******************************************************************------- 

-- Estructura de tabla para la tabla `Venta`
--
CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto`   float(17) NOT NULL,
  `foto` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `fechaAlta` date DEFAULT NULL,
  `fechaAnulacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Volcado inicial de datos para la tabla `Venta`
--
INSERT INTO `venta` (`id`, `id_usuario`, `monto`, `foto`, `fechaAlta`,`fechaAnulacion`) VALUES
(1, 1, 3000, '/lalala/lalala',NULL,NULL),
(2, 1, 2000, 'lalala/lalala',NULL,NULL),
(3, 1, 2500, 'lalala/lalala',NULL,NULL),;
--
-- Indices de la tabla `Venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `Venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

----******************************************************************-------
-----                       Tabla VentaProducto                        ------
----******************************************************************------- 

-- Estructura de tabla para la tabla `VentaProducto`
--
CREATE TABLE `ventaproducto` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad`  int(15) NOT NULL,
  `fechaAlta` date DEFAULT NULL,
  `fechaAnulacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Volcado inicial de datos para la tabla `VentaProducto`
--
INSERT INTO `ventaproducto` (`id_venta`, `id_producto`,`cantidad`, `fechaAlta`,`fechaAnulacion`) VALUES
(1, 1, 3, '2022-11-13',NULL),
(1, 2, 2, '2022-11-13',NULL),
(1, 3, 2, '2022-11-13',NULL);
