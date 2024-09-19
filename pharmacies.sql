-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: apipharma-db:3310
-- Tiempo de generación: 17-09-2024 a las 23:43:35
-- Versión del servidor: 10.11.9-MariaDB-ubu2204
-- Versión de PHP: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `apipharma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pharmacies`
--

CREATE TABLE `pharmacies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `latitud` decimal(10,7) NOT NULL,
  `longitud` decimal(10,7) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pharmacies`
--

INSERT INTO `pharmacies` (`id`, `nombre`, `direccion`, `latitud`, `longitud`, `created_at`, `updated_at`) VALUES
(71, 'Farmacia Nairobi', 'Kenia', -1.2864000, 36.8171000, NULL, NULL),
(72, 'Farmacia Tokio', 'Japón', 35.6762000, 139.6503000, NULL, NULL),
(73, 'Farmacia Washington DC 67890', 'Estados Unidos', 38.8951000, -77.0369000, NULL, NULL),
(74, 'Farmacia Canberra 33445', 'Australia', -35.2809000, 149.1300000, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pharmacies`
--
ALTER TABLE `pharmacies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pharmacies`
--
ALTER TABLE `pharmacies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
