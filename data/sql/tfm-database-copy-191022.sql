-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 19-10-2022 a las 19:52:44
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tfm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_ASIGNATURA`
--

CREATE TABLE `TFM_ASIGNATURA` (
  `COD_ASIGNATURA` varchar(8) NOT NULL,
  `NOMBRE_ASIGNATURA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_ASIGNATURA`
--

INSERT INTO `TFM_ASIGNATURA` (`COD_ASIGNATURA`, `NOMBRE_ASIGNATURA`) VALUES
('2000001', 'DISEÑO DE BASES DE DATOS'),
('2000002', 'ESTRUCTURA DE COMPUTADORES'),
('6000001', 'FRAMEWORKS DE NUEVA GENERACIÓN'),
('6000002', 'DOCKERIZACIÓN DE APLICACIONES WEB');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_DOCENTE`
--

CREATE TABLE `TFM_DOCENTE` (
  `NOMBRE` varchar(300) NOT NULL,
  `APELLIDO1` varchar(300) NOT NULL,
  `APELLIDO2` varchar(300) NOT NULL,
  `USUARIO` varchar(200) NOT NULL,
  `DOCUMENTO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_DOCENTE`
--

INSERT INTO `TFM_DOCENTE` (`NOMBRE`, `APELLIDO1`, `APELLIDO2`, `USUARIO`, `DOCUMENTO`) VALUES
('FERNANDO', 'SANZ', 'HOLGADO', 'fernando.sanz', '53100692V');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_DOCENTE_PLAN`
--

CREATE TABLE `TFM_DOCENTE_PLAN` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `USUARIO_DOCENTE` varchar(250) NOT NULL,
  `COD_PLAN` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_DOCENTE_PLAN`
--

INSERT INTO `TFM_DOCENTE_PLAN` (`CURSO_ACADEMICO`, `USUARIO_DOCENTE`, `COD_PLAN`) VALUES
('2022-23', 'fernando.sanz', '2000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_ESTUDIANTE`
--

CREATE TABLE `TFM_ESTUDIANTE` (
  `USUARIO` varchar(150) NOT NULL,
  `NOMBRE` varchar(300) NOT NULL,
  `APELLIDO1` varchar(300) NOT NULL,
  `DOCUMENTO` varchar(100) NOT NULL,
  `APELLIDO2` varchar(60) DEFAULT NULL,
  `TELEFONO1` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_ESTUDIANTE`
--

INSERT INTO `TFM_ESTUDIANTE` (`USUARIO`, `NOMBRE`, `APELLIDO1`, `DOCUMENTO`, `APELLIDO2`, `TELEFONO1`) VALUES
('estrella.parrilla', 'ESTRELLA', 'PARRILLA', '47488594B', 'SANZ', '64675756'),
('rus.poves', 'RUS', 'POVES', '47488599B', 'MORENO', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_ESTUDIANTE_OFERTA`
--

CREATE TABLE `TFM_ESTUDIANTE_OFERTA` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `COD_OFERTA` int(11) NOT NULL,
  `USUARIO_ESTUDIANTE` varchar(300) NOT NULL,
  `FECHA_ALTA` timestamp NOT NULL DEFAULT current_timestamp(),
  `ESTADO` varchar(50) NOT NULL DEFAULT 'Validado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_ESTUDIANTE_OFERTA`
--

INSERT INTO `TFM_ESTUDIANTE_OFERTA` (`CURSO_ACADEMICO`, `COD_OFERTA`, `USUARIO_ESTUDIANTE`, `FECHA_ALTA`, `ESTADO`) VALUES
('2022-23', 1, 'estrella.parrilla', '2022-10-14 22:00:00', 'Validado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_EXPEDIENTE`
--

CREATE TABLE `TFM_EXPEDIENTE` (
  `COD_PLAN` varchar(4) NOT NULL,
  `NUMORD` int(11) NOT NULL,
  `DOCUMENTO_ESTUDIANTE` varchar(50) NOT NULL,
  `ACTIVO` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_EXPEDIENTE`
--

INSERT INTO `TFM_EXPEDIENTE` (`COD_PLAN`, `NUMORD`, `DOCUMENTO_ESTUDIANTE`, `ACTIVO`) VALUES
('2000', 1, '47488594B', 'N'),
('6000', 1, '47488594B', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_LINEAS_MATRICULA`
--

CREATE TABLE `TFM_LINEAS_MATRICULA` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `COD_PLAN` varchar(4) NOT NULL,
  `EXP_NUMORD` int(11) NOT NULL,
  `COD_ASIGNATURA` varchar(8) NOT NULL,
  `COD_ANULACION` int(11) DEFAULT NULL,
  `NOTA_NUMERICA` float NOT NULL,
  `NOTA_ALFANUMERICA` varchar(2) NOT NULL,
  `USUARIO_DOCENTE` varchar(250) NOT NULL,
  `CERRADO` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_LINEAS_MATRICULA`
--

INSERT INTO `TFM_LINEAS_MATRICULA` (`CURSO_ACADEMICO`, `COD_PLAN`, `EXP_NUMORD`, `COD_ASIGNATURA`, `COD_ANULACION`, `NOTA_NUMERICA`, `NOTA_ALFANUMERICA`, `USUARIO_DOCENTE`, `CERRADO`) VALUES
('2017-18', '2000', 1, '2000001', NULL, 9.8, 'SB', 'fernando.sanz', 'S'),
('2017-18', '2000', 1, '2000002', NULL, 8.8, 'NT', 'fernando.sanz', 'S'),
('2022-23', '6000', 1, '6000001', NULL, 10, 'MH', 'micael.lopez', 'S'),
('2022-23', '6000', 1, '6000002', NULL, 9, 'SB', 'edisa.perez', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_MATRICULA`
--

CREATE TABLE `TFM_MATRICULA` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `COD_PLAN` varchar(4) NOT NULL,
  `EXP_NUMORD` int(11) NOT NULL,
  `COD_ANULACION` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_MATRICULA`
--

INSERT INTO `TFM_MATRICULA` (`CURSO_ACADEMICO`, `COD_PLAN`, `EXP_NUMORD`, `COD_ANULACION`) VALUES
('2017-18', '2000', 1, NULL),
('2022-23', '6000', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_OFERTAS`
--

CREATE TABLE `TFM_OFERTAS` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `COD_OFERTA` int(10) NOT NULL,
  `TITULO` varchar(250) NOT NULL,
  `DESCRIPCION` varchar(500) NOT NULL,
  `USUARIO_DOCENTE` varchar(200) NOT NULL,
  `FECHA_ALTA` date NOT NULL DEFAULT current_timestamp(),
  `ESTADO` varchar(50) NOT NULL DEFAULT 'Validada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_OFERTAS`
--

INSERT INTO `TFM_OFERTAS` (`CURSO_ACADEMICO`, `COD_OFERTA`, `TITULO`, `DESCRIPCION`, `USUARIO_DOCENTE`, `FECHA_ALTA`, `ESTADO`) VALUES
('2022-23', 1, 'APLICACIÓN WEB PARA LA MONITORIZACIÓN DE BASES DE DATOS', 'bla bla bla', 'fernando.sanz', '2022-10-15', 'Validada'),
('2022-23', 2, 'Dockerizar aplicaciones desde 0', '---', 'jesus.bruma', '2022-10-19', 'Validada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_PLANES`
--

CREATE TABLE `TFM_PLANES` (
  `COD_PLAN` varchar(4) NOT NULL,
  `NOMBRE_PLAN` varchar(400) NOT NULL,
  `VIGENTE` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `TFM_PLANES`
--

INSERT INTO `TFM_PLANES` (`COD_PLAN`, `NOMBRE_PLAN`, `VIGENTE`) VALUES
('2000', 'GRADO EN INGENIERÍA INFORMÁTICA', 'S'),
('6000', 'MÁSTER EN DESARROLLO DE SITIOS Y APLICACIONES WEB', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TFM_SOLICITUD_DEFENSA`
--

CREATE TABLE `TFM_SOLICITUD_DEFENSA` (
  `CURSO_ACADEMICO` varchar(7) NOT NULL,
  `COD_SOLICITUD` bigint(20) UNSIGNED NOT NULL,
  `COD_OFERTA` int(11) NOT NULL,
  `USUARIO_ESTUDIANTE` int(200) NOT NULL,
  `FECHA_SOLICITUD` date NOT NULL DEFAULT current_timestamp(),
  `ESTADO` varchar(50) NOT NULL,
  `NOTA_FINAL` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `TFM_ASIGNATURA`
--
ALTER TABLE `TFM_ASIGNATURA`
  ADD PRIMARY KEY (`COD_ASIGNATURA`);

--
-- Indices de la tabla `TFM_ESTUDIANTE`
--
ALTER TABLE `TFM_ESTUDIANTE`
  ADD PRIMARY KEY (`USUARIO`);

--
-- Indices de la tabla `TFM_ESTUDIANTE_OFERTA`
--
ALTER TABLE `TFM_ESTUDIANTE_OFERTA`
  ADD PRIMARY KEY (`COD_OFERTA`,`USUARIO_ESTUDIANTE`,`ESTADO`);

--
-- Indices de la tabla `TFM_EXPEDIENTE`
--
ALTER TABLE `TFM_EXPEDIENTE`
  ADD PRIMARY KEY (`COD_PLAN`,`NUMORD`,`DOCUMENTO_ESTUDIANTE`);

--
-- Indices de la tabla `TFM_MATRICULA`
--
ALTER TABLE `TFM_MATRICULA`
  ADD PRIMARY KEY (`CURSO_ACADEMICO`,`COD_PLAN`,`EXP_NUMORD`);

--
-- Indices de la tabla `TFM_OFERTAS`
--
ALTER TABLE `TFM_OFERTAS`
  ADD UNIQUE KEY `COD_OFERTA` (`COD_OFERTA`);

--
-- Indices de la tabla `TFM_PLANES`
--
ALTER TABLE `TFM_PLANES`
  ADD PRIMARY KEY (`COD_PLAN`);

--
-- Indices de la tabla `TFM_SOLICITUD_DEFENSA`
--
ALTER TABLE `TFM_SOLICITUD_DEFENSA`
  ADD PRIMARY KEY (`CURSO_ACADEMICO`,`COD_OFERTA`,`USUARIO_ESTUDIANTE`),
  ADD UNIQUE KEY `COD_SOLICITUD` (`COD_SOLICITUD`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `TFM_OFERTAS`
--
ALTER TABLE `TFM_OFERTAS`
  MODIFY `COD_OFERTA` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `TFM_SOLICITUD_DEFENSA`
--
ALTER TABLE `TFM_SOLICITUD_DEFENSA`
  MODIFY `COD_SOLICITUD` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
