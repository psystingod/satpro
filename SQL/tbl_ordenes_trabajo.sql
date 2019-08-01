-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-07-2019 a las 18:21:07
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `satpro`
--
USE satpro;
DROP TABLE IF EXISTS tbl_ordenes_trabajo;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ordenes_trabajo`
--

CREATE TABLE `tbl_ordenes_trabajo` (
  `idOrdenTrabajo` int(6) UNSIGNED ZEROFILL NOT NULL,
  `codigoCliente` int(5) UNSIGNED ZEROFILL DEFAULT NULL,
  `fechaOrdenTrabajo` date NOT NULL,
  `tipoOrdenTrabajo` varchar(20) NOT NULL,
  `diaCobro` int(2) DEFAULT NULL,
  `nombreCliente` varchar(50) NOT NULL,
  `telefonos` varchar(25) NOT NULL,
  `idMunicipio` varchar(10) NOT NULL,
  `actividadCable` varchar(40) DEFAULT NULL,
  `saldoCable` double DEFAULT NULL,
  `direccionCable` varchar(150) DEFAULT NULL,
  `actividadInter` varchar(40) DEFAULT NULL,
  `saldoInter` double DEFAULT NULL,
  `direccionInter` varchar(150) DEFAULT NULL,
  `macModem` varchar(25) DEFAULT NULL,
  `serieModem` varchar(25) DEFAULT NULL,
  `velocidad` varchar(10) DEFAULT NULL,
  `rx` varchar(6) DEFAULT NULL,
  `tx` varchar(6) DEFAULT NULL,
  `snr` varchar(6) DEFAULT NULL,
  `colilla` varchar(10) DEFAULT NULL,
  `fechaTrabajo` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `fechaProgramacion` date DEFAULT NULL,
  `idTecnico` int(6) DEFAULT NULL,
  `mactv` varchar(25) DEFAULT NULL,
  `coordenadas` varchar(50) DEFAULT NULL,
  `marcaModelo` varchar(25) DEFAULT NULL,
  `tecnologia` varchar(20) DEFAULT NULL,
  `observaciones` varchar(80) DEFAULT NULL,
  `nodo` varchar(20) DEFAULT NULL,
  `idVendedor` int(6) NOT NULL,
  `recepcionTv` varchar(10) DEFAULT NULL,
  `tipoServicio` char(1) NOT NULL,
  `creadoPor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_ordenes_trabajo`
--


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_ordenes_trabajo`
--
ALTER TABLE `tbl_ordenes_trabajo`
  ADD PRIMARY KEY (`idOrdenTrabajo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_ordenes_trabajo`
--
ALTER TABLE `tbl_ordenes_trabajo`
  MODIFY `idOrdenTrabajo` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
