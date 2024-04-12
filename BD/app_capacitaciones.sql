-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2024 a las 15:16:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `app_capacitaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `nit` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `municipio_ciudad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`nit`, `nombre`, `departamento`, `municipio_ciudad`) VALUES
(123456789, 'Empresa ABC', 'Departamento 1', 'Ciudad A'),
(987654321, 'Empresa XYZ', 'Departamento 2', 'Ciudad B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre`, `apellido`, `id_empresa`) VALUES
(1, 'Juan', 'Perez', 123456789),
(2, 'María', 'López', 987654321),
(1129580584, 'Donna', 'Lopez', 123456789);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id_examen` int(11) NOT NULL,
  `nombre_examen` varchar(255) NOT NULL,
  `descripcion` longtext NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `tipo_examen` enum('antes','despues') DEFAULT NULL,
  `fecha_vigencia` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes`
--

INSERT INTO `examenes` (`id_examen`, `nombre_examen`, `descripcion`, `id_modulo`, `tipo_examen`, `fecha_vigencia`, `activo`) VALUES
(1, 'TRABAJO EN CONFINADO', 'EVALUACIÓN DE CONOCIMIENTOS PREVIOS', 1, 'antes', '2025-01-01', 1),
(2, 'TRABAJO EN CONFINADO', 'EVALUACIÓN DE CONOCIMIENTO', 1, 'despues', '2025-01-01', 1),
(3, 'Examen 03', '', 2, 'antes', '2024-01-10', 1),
(4, 'Examen 04', '', 2, 'despues', '2024-02-15', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_asignados`
--

CREATE TABLE `examenes_asignados` (
  `id_asignacion` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `id_examen` int(11) DEFAULT NULL,
  `tipo_examen` varchar(255) DEFAULT NULL,
  `fecha_asignacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes_asignados`
--

INSERT INTO `examenes_asignados` (`id_asignacion`, `id_estudiante`, `id_examen`, `tipo_examen`, `fecha_asignacion`) VALUES
(1, 1129580584, 1, 'antes', '2024-03-30'),
(2, 1129580584, 2, 'despues', '2024-04-03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL,
  `nombre_menu` varchar(100) NOT NULL,
  `url_menu` varchar(100) NOT NULL,
  `nivel` int(11) NOT NULL,
  `padre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id_menu`, `nombre_menu`, `url_menu`, `nivel`, `padre`) VALUES
(1, 'Dashboard', 'x', 0, 0),
(2, 'Gestión de usuarios', 'x', 0, 0),
(3, 'Examenes', 'x', 1, 0),
(4, 'Examenes pendientes', 'reservation-pending.php', 2, 3),
(5, 'Modulos', '', 1, 0),
(6, 'Usuarios', '', 1, 0),
(7, 'Empresas', '', 1, 0),
(8, 'Asignar examenes', 'reservation-new.php', 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulos`
--

CREATE TABLE `modulos` (
  `id_modulo` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `fecha_vigencia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulos`
--

INSERT INTO `modulos` (`id_modulo`, `nombre`, `fecha_vigencia`) VALUES
(1, 'MODULO UAS', '2025-01-01'),
(2, 'Módulo 2', '2024-02-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `nombre_perfil` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'ESTUDIANTE'),
(3, 'EVALUADOR01'),
(4, 'EVALUADOR02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(50) NOT NULL,
  `id_menu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nombre_permiso`, `id_menu`) VALUES
(1, 'ver_dashboard', 1),
(2, 'editar_usuarios', 2),
(3, 'Examenes', 3),
(4, 'Examenes pendientes', 4),
(13, 'Modulos', 5),
(14, 'Usuarios', 6),
(15, 'Empresa', 7),
(16, 'Asignar examenes', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id_pregunta` int(11) NOT NULL,
  `id_examen` int(11) DEFAULT NULL,
  `texto_pregunta` text DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id_pregunta`, `id_examen`, `texto_pregunta`, `imagen_url`) VALUES
(1, 1, '¿Cuál de los siguientes factores NO es un criterio que establece la presencia de\nun espacio confinado?', NULL),
(2, 1, '¿Cuáles son los métodos para identificar la presencia de atmósferas tóxicas o\nexplosivas dentro de un espacio confinado?', NULL),
(3, 1, '¿Un espacio confinado con una pequeña abertura y atmosfera toxica, está\ncategorizado como tipo __ y grado __?', NULL),
(4, 1, '¿Qué peligro NO corresponde a una situación inminente que comprometa la vida o\nla salud de las personas en un espacio confinado clasificado como grado A?', NULL),
(5, 1, 'En Colombia, ¿Cuáles son reconocidas como medidas preventivas para el trabajo\nen espacios confinados?', NULL),
(6, 1, '¿Cuál de los siguientes elementos forma parte de las medidas de protección para\ntrabajos en espacios confinados?', NULL),
(7, 1, '¿Cuál de los siguientes sistemas representa una medida de protección para\ntrabajos en espacios confinados?', NULL),
(8, 1, '¿Cuál de los siguientes elementos es el más adecuado para bloquear una energía\npeligrosa de una línea presurizada?', NULL),
(9, 1, 'El control de acceso en los espacios confinados pertenece a:', NULL),
(10, 1, '¿Qué elemento de los siguientes es una medida preventiva diseñada para\nsalvaguardar al rescatista durante una operación de rescate?', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `id_respuesta` int(11) NOT NULL,
  `id_pregunta` int(11) DEFAULT NULL,
  `texto_respuesta` text DEFAULT NULL,
  `correcta` tinyint(1) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`id_respuesta`, `id_pregunta`, `texto_respuesta`, `correcta`, `imagen_url`) VALUES
(1, 1, 'Que tengas accesos y salidas restringidas', 0, NULL),
(2, 1, 'Que sea abierto en su parte superior', 1, NULL),
(3, 1, 'Que no esté diseñado para la permanencia de una persona', 0, NULL),
(4, 1, 'Que sea lo suficientemente grande para que un trabajador pueda entrar', 0, NULL),
(5, 2, 'Utilizando equipos de respiración autónoma.', 0, NULL),
(6, 2, 'Liberando un ave dentro del espacio.', 0, NULL),
(7, 2, 'Realizando mediciones estratificadas.', 1, NULL),
(8, 2, 'Todas las anteriores', 0, NULL),
(9, 3, 'Tipo 1 Grado A', 0, NULL),
(10, 3, 'Tipo 2 Grado A', 1, NULL),
(11, 3, 'Grado 2 Tipo B', 0, NULL),
(12, 3, 'Grado 2 Tipo C', 0, NULL),
(13, 4, 'Falta de oxígeno.', 0, NULL),
(14, 4, 'Presencia de gases tóxicos.', 0, NULL),
(15, 4, 'Riesgo de incendio repentino.', 0, NULL),
(16, 4, 'Peligros potenciales como lesiones y/o enfermedades.', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas_estudiantes`
--

CREATE TABLE `respuestas_estudiantes` (
  `id_respuesta_estudiante` int(11) NOT NULL,
  `id_estudiante` int(11) DEFAULT NULL,
  `id_examen` int(11) DEFAULT NULL,
  `respuestas_correctas` int(11) DEFAULT NULL,
  `respuestas_incorrectas` int(11) DEFAULT NULL,
  `total_preguntas` int(11) DEFAULT NULL,
  `porcentaje` float NOT NULL,
  `fecha_realizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuestas_estudiantes`
--

INSERT INTO `respuestas_estudiantes` (`id_respuesta_estudiante`, `id_estudiante`, `id_examen`, `respuestas_correctas`, `respuestas_incorrectas`, `total_preguntas`, `porcentaje`, `fecha_realizacion`) VALUES
(8, 1129580584, 1, 3, 1, 4, 75, '2024-04-04 04:41:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo_usuario` varchar(100) NOT NULL,
  `contrasena_usuario` varchar(255) NOT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `estudiante` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo_usuario`, `contrasena_usuario`, `id_perfil`, `estudiante`) VALUES
(1, '1129580584', 'abc@abc.com', '123456', 2, 1129580584),
(3, 'administrador', 'abcd@abcd.com', '123456', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_permisos`
--

CREATE TABLE `usuarios_permisos` (
  `id_usuario_permiso` int(11) NOT NULL,
  `id_perfil` int(11) DEFAULT NULL,
  `id_permiso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_permisos`
--

INSERT INTO `usuarios_permisos` (`id_usuario_permiso`, `id_perfil`, `id_permiso`) VALUES
(1, 2, 4),
(2, 2, 3),
(5, 1, 3),
(6, 1, 4),
(7, 1, 15),
(8, 1, 13),
(9, 1, 16);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`nit`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id_examen`),
  ADD KEY `id_modulo` (`id_modulo`);

--
-- Indices de la tabla `examenes_asignados`
--
ALTER TABLE `examenes_asignados`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_examen` (`id_examen`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indices de la tabla `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id_pregunta`),
  ADD KEY `id_examen` (`id_examen`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`id_respuesta`),
  ADD KEY `id_pregunta` (`id_pregunta`);

--
-- Indices de la tabla `respuestas_estudiantes`
--
ALTER TABLE `respuestas_estudiantes`
  ADD PRIMARY KEY (`id_respuesta_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_examen` (`id_examen`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_usuario` (`correo_usuario`),
  ADD KEY `id_perfil` (`id_perfil`);

--
-- Indices de la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD PRIMARY KEY (`id_usuario_permiso`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1129580585;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id_examen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `examenes_asignados`
--
ALTER TABLE `examenes_asignados`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `respuestas_estudiantes`
--
ALTER TABLE `respuestas_estudiantes`
  MODIFY `id_respuesta_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  MODIFY `id_usuario_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`nit`);

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id_modulo`);

--
-- Filtros para la tabla `examenes_asignados`
--
ALTER TABLE `examenes_asignados`
  ADD CONSTRAINT `examenes_asignados_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `examenes_asignados_ibfk_2` FOREIGN KEY (`id_examen`) REFERENCES `examenes` (`id_examen`);

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`id_examen`) REFERENCES `examenes` (`id_examen`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas` (`id_pregunta`);

--
-- Filtros para la tabla `respuestas_estudiantes`
--
ALTER TABLE `respuestas_estudiantes`
  ADD CONSTRAINT `respuestas_estudiantes_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `respuestas_estudiantes_ibfk_2` FOREIGN KEY (`id_examen`) REFERENCES `examenes` (`id_examen`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id_perfil`);

--
-- Filtros para la tabla `usuarios_permisos`
--
ALTER TABLE `usuarios_permisos`
  ADD CONSTRAINT `usuarios_permisos_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
