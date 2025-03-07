-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2024 a las 22:23:37
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lubricentro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agregar_productos`
--

CREATE TABLE `agregar_productos` (
  `id_agregar_productos` int(11) NOT NULL,
  `id_planilla` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `precio` varchar(100) NOT NULL,
  `cantidad` varchar(100) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `agregar_productos`
--

INSERT INTO `agregar_productos` (`id_agregar_productos`, `id_planilla`, `producto`, `precio`, `cantidad`, `total`) VALUES
(416, 321, 'Neumatico 155 R12 Nexen 8PR CP321', '6556', '3', 19668),
(417, 321, 'Neumatico 155/65 R14 Haida 75h', '35000', '2', 70000),
(418, 321, 'Neumatico 155 R12 Nexen 8PR CP321', '12000', '1', 12000),
(419, 323, 'Neumatico 145 R13 LT Ling Long ', '24000', '1', 24000),
(420, 323, 'Neumatico 145 R13C 8PR HANKOOK', '14000', '2', 28000),
(421, 324, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(422, 324, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(423, 325, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(424, 325, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(425, 326, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(426, 326, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(427, 327, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(428, 327, 'Neumatico 145 R13 Nexen 10PR ', '2000', '1', 2000),
(429, 328, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(430, 328, 'Neumatico 145 R13 Nexen 10PR ', '2000', '1', 2000),
(431, 329, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(432, 329, 'Neumatico 145 R13 Nexen 10PR ', '2000', '1', 2000),
(433, 330, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(434, 331, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(435, 332, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(436, 333, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(437, 334, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(438, 335, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(439, 336, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(440, 337, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(441, 338, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(442, 339, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(449, 342, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(450, 342, 'Neumatico 145 R13 Nexen 10PR ', '2000', '1', 2000),
(451, 343, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(452, 343, 'Neumatico 145 R13 Nexen 10PR ', '2000', '3', 6000),
(453, 344, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(454, 345, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(455, 346, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(456, 347, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(457, 347, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(458, 348, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(459, 348, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(460, 349, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(461, 349, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(462, 350, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(463, 350, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(464, 351, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(465, 351, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(466, 352, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(467, 352, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(468, 353, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(469, 353, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(470, 354, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(471, 354, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(472, 355, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(473, 355, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(474, 356, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(475, 356, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(476, 357, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(477, 357, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(478, 358, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(479, 358, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(480, 359, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(481, 359, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(482, 360, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(483, 360, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(484, 361, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(485, 361, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(486, 362, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(487, 362, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(488, 363, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(489, 363, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(490, 364, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(491, 364, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(492, 365, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(493, 365, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(494, 366, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(495, 366, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(496, 367, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(497, 368, 'Neumatico 145 R13 LT Ling Long ', '5000', '3', 15000),
(498, 369, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(499, 370, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(500, 370, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(501, 371, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(502, 371, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(503, 372, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(504, 372, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(505, 373, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(506, 373, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(507, 374, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(508, 374, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(509, 375, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(510, 375, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(511, 376, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(512, 376, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(513, 377, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(514, 377, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(515, 378, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(516, 378, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(517, 379, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(518, 379, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(519, 380, 'Neumatico 145 R13 LT Ling Long ', '5000', '1', 5000),
(520, 380, 'Neumatico 145 R13 Nexen 10PR ', '2000', '2', 4000),
(521, 381, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(522, 381, 'Neumatico 145 R13 Nexen 10PR ', '2000', '3', 6000),
(523, 382, 'Neumatico 145 R13 LT Ling Long ', '5000', '2', 10000),
(524, 382, 'Neumatico 145 R13 Nexen 10PR ', '2000', '3', 6000),
(525, 383, 'Neumatico 145 R13 LT Ling Long ', '3000', '1', 3000),
(526, 383, 'Neumatico 145 R13C 8PR HANKOOK', '3000', '4', 12000),
(527, 340, '2593', '5000', '2', 10000),
(529, 384, '2587', '1000', '3', 3000),
(530, 384, '2588', '5000', '2', 10000),
(531, 385, '2593', '5000', '3', 3000),
(532, 386, '2593', '5000', '2', 10000),
(533, 387, '2593', '5000', '2', 10000),
(534, 388, '2593', '5000', '2', 10000),
(535, 389, '2593', '5000', '2', 10000),
(536, 390, '2593', '5000', '2', 10000),
(537, 390, '2592', '2000', '1', 2000),
(538, 391, '2593', '5000', '2', 10000),
(539, 391, '2592', '2000', '1', 2000),
(540, 392, '2593', '5000', '2', 10000),
(541, 392, '2592', '2000', '2', 4000),
(542, 393, '2593', '5000', '1', 5000),
(543, 393, '2592', '2000', '2', 4000),
(544, 394, '2593', '5000', '2', 10000),
(545, 395, '2593', '5000', '19', 95000),
(546, 396, '2593', '5000', '2', 10000),
(547, 397, '2593', '5000', '1', 5000),
(548, 398, '2593', '5000', '1', 5000),
(611, 400, '2592', '2000', '2', 4000),
(612, 400, '2576', '5000', '4', 20000),
(613, 401, '2591', '3000', '1', 3000),
(614, 401, '', '', '0', 0),
(615, 402, '', '', '0', 0),
(616, 402, '', '', '0', 0),
(617, 403, '2579', '5000', '2', 10000),
(618, 403, '', '', '0', 0),
(619, 399, '2593', '5000', '2', 10000),
(620, 399, '2590', '4000', '1', 4000),
(621, 404, '2595', '2000', '2', 4000),
(622, 405, '2588', '5000', '', 0),
(624, 341, '2593', '2000', '5', 10000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backup`
--

CREATE TABLE `backup` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `archivo` varchar(300) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `backup`
--

INSERT INTO `backup` (`id`, `usuario`, `archivo`, `fecha`, `hora`) VALUES
(28, 'ana', '/lubricentro/app/script/uploads/lubricentro1708179671.sql', '2024-02-17', '11:21:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_clientes` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rut` varchar(100) NOT NULL,
  `direccion` text NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fono` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_clientes`, `nombre`, `rut`, `direccion`, `correo`, `fono`) VALUES
(2, 'javier lopez', '11.111.111-1', 'demo 123', 'demo@juan.cl', '+569 76452442'),
(3, 'mario vejar', '44.444.444-4', 'demo 1234', 'sdsds@fkdjf.ccc', '+562 32323232'),
(5, 'Marcelo Reyes', '22.222.222-2', 'demo 123', 'marcelo@reyes.cl', '+569 83283237');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `columnas_planilla`
--

CREATE TABLE `columnas_planilla` (
  `id_columnas_planilla` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `color_columna` varchar(200) NOT NULL,
  `color_texto_columna` varchar(200) NOT NULL,
  `numeracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `columnas_planilla`
--

INSERT INTO `columnas_planilla` (`id_columnas_planilla`, `nombre`, `color_columna`, `color_texto_columna`, `numeracion`) VALUES
(1, 'tipo_vehiculo', '#fff7f0', '#000000', 3),
(2, 'ubicacion', '#fff7f0', '#000000', 4),
(3, 'marca', '#fff7f0', '#000000', 5),
(4, 'modelo', '#fff7f0', '#000000', 6),
(5, 'patente', '#fff7f0', '#000000', 7),
(6, 'info_cliente', '#ffcc99', '#000000', 8),
(7, 'mecanica', '#e8ded5', '#000000', 9),
(8, 'fecha', '#fff7f0', '#000000', 10),
(9, 'medida', '#c5dcde', '#000000', 11),
(10, 'marca_aceite', '#c5dcde', '#000000', 12),
(11, 'unidad', '#c5dcde', '#000000', 13),
(12, 'precio_litros', '#c5dcde', '#000000', 14),
(13, 'filtro_aceite', '#e1dae6', '#000000', 15),
(14, 'precio_filtro_aceite', '#e1dae6', '#000000', 16),
(15, 'aceite_caja', '#d7f0fe', '#000000', 17),
(16, 'precio_aceite_caja', '#d7f0fe', '#000000', 18),
(17, 'filtro_aire', '#c5dcde', '#000000', 19),
(18, 'precio_filtro_aire', '#c5dcde', '#000000', 20),
(19, 'filtro_combustible', '#e1dae6', '#000000', 21),
(20, 'precio_filtro_combustible', '#e1dae6', '#000000', 22),
(21, 'filtro_cabina', '#d7f0fe', '#000000', 23),
(22, 'producto', '#d7f0fe', '#000000', 24),
(23, 'precio', '#d7f0fe', '#000000', 25),
(24, 'cantidad', '#d7f0fe', '#000000', 26),
(25, 'precio_filtro_cabina', '#d7f0fe', '#000000', 27),
(26, 'mecanico', '#d4d4f5', '#000000', 28),
(27, 'mes', '#d4d4f5', '#000000', 29),
(28, 'dia', '#d4d4f5', '#000000', 30),
(29, 'total', '#008000', '#ffffff', 31),
(30, 'estado_total', '#ffcc99', '#000000', 32),
(31, 'entrada', '#ffcc99', '#000000', 33),
(32, 'salida', '#ffcc99', '#000000', 34),
(33, 'observacion', '#eeff07', '#000000', 35),
(36, 'usuario', '#fff7f0', '#000000', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `estado_cemaforo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id_configuracion`, `estado_cemaforo`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `importar`
--

CREATE TABLE `importar` (
  `id_importar` int(11) NOT NULL,
  `archivo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `importar`
--

INSERT INTO `importar` (`id_importar`, `archivo`) VALUES
(1, '/lubricentro/app/script/uploads/planilla_1651967511_1652125217_1655213370_1658342564.xlsx');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `import_product`
--

CREATE TABLE `import_product` (
  `id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `import_product`
--

INSERT INTO `import_product` (`id`, `file`) VALUES
(1, '/lubricentro/app/script/uploads/inventario_1651855735_1651856869.xlsx');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory`
--

CREATE TABLE `inventory` (
  `id_inventory` int(11) NOT NULL,
  `name_product` varchar(255) NOT NULL,
  `type` varchar(200) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `observacion` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `inventory`
--

INSERT INTO `inventory` (`id_inventory`, `name_product`, `type`, `quantity`, `location`, `price`, `observacion`) VALUES
(2593, 'Neumatico 145 R13 LT Ling Long ', 'litros', '397,7', 'Bodega 1', 5000, ''),
(2592, 'Neumatico 145 R13 Nexen 10PR ', 'demo 2', '293', 'Bodega 2', 2000, ''),
(2591, 'Neumatico 145 R13C 8PR HANKOOK', 'demo 3', '291', 'Bodega 3', 3000, ''),
(2590, 'Neumatico 145/70 R12 Ling long 69S', 'litros', '190', '2', 4000, ''),
(2587, 'Neumatico 155 R13 Nexen 8PR', 'litros', '300', '2', 1000, ''),
(2588, 'Neumatico 155 R12C Goodride ', 'litros', '900', '2', 5000, ''),
(2589, 'Neumatico 155 R12 Nexen 8PR CP321', 'litros', '245', '2', 5000, ''),
(2586, 'Neumatico 155 R13 Triangle TR 999', '', '8', '2', 5000, ''),
(2585, 'Neumatico 155 R13C Nexen 8PR ', '', '1', '2', 5000, ''),
(2584, 'Neumatico 155/65 R13 Goodride RP28', '', '12', '2', 5000, ''),
(2583, 'Neumatico 155/65 R13 Triangle TR256', '', '3', '2', 5000, ''),
(2582, 'Neumatico 155/65 R14 Haida 75h', '', '3', '2', 5000, ''),
(2581, 'Neumatico 155/70 R12 Nexen 73T', '', '4', '2', 5000, ''),
(2580, 'Neumatico 155/70 R13 Goodride RP28', '', '7', '2', 5000, ''),
(2579, 'Neumatico 155/70 R13 ling long 75T', '', '0', '2', 5000, ''),
(2577, 'Neumatico 165 R13 C Sunfull SF05', '', '3', '2', 5000, ''),
(2578, 'Neumatico 155/70 R14 TracMax 77T', '', '5', '2', 5000, ''),
(2576, 'Neumatico 165/60 R14 Goodride RP28', '', '0', '2', 5000, ''),
(2575, 'Neumatico 165/60 R14 Nexen 75H ', '', '3', '2', 5000, ''),
(2574, 'Neumatico 165/60 R14 Nexen AH5', '', '1', '2', 5000, ''),
(2573, 'Neumatico 165/60 R14 Zeta ZTR50', '', '1', '2', 5000, ''),
(2572, 'Neumatico 165/65 R13 Goodride RP28', '', '6', '2', 5000, ''),
(2571, 'Neumatico 165/65 R14 Goodride RP28', '', '1', '2', 5000, ''),
(2570, 'Neumatico 165/65 R14 Triangle TE301', '', '5', '2', 5000, ''),
(2568, 'Neumatico 165/70 R13 Goodride RP28', '', '1', '2', 5000, ''),
(2569, 'Neumatico 165/65 R14 Tristar 79T', '', '1', '2', 5000, ''),
(2566, 'Neumatico 165/70 R14 Goodride RP28', '', '4', '2', 5000, ''),
(2567, 'Neumatico 165/70 R13 Windforce 79T', '', '4', '2', 5000, ''),
(2564, 'Neumatico 175/60 R15 Windforce 81H', '', '1', '2', 5000, ''),
(2565, 'Neumatico 175/60 R15 Goodride RP28', '', '2', '2', 5000, ''),
(2562, 'Neumatico 175/65 R14 Continental 82H', '', '6', '3', 5000, ''),
(2563, 'Neumatico 175/60 R16 Nexen 82H', '', '4', '2', 5000, ''),
(2561, 'Neumatico 175/65 R14 Coopertires 82H', '', '8', '2', 5000, ''),
(2560, 'Neumatico 175/65 R14 Haida 82T', '', '12', '2', 5000, ''),
(2559, 'Neumatico 175/65 R14 Haida 82T ', '', '4', '3', 5000, ''),
(2558, 'Neumatico 175/65 R14 Jinyu 86T', '', '2', '2', 5000, ''),
(2557, 'Neumatico 175/65 R14 ling long 82 T ', '', '15', '2', 5000, ''),
(2556, 'Neumatico 175/65 R14 Neolin 86T', '', '1', '2', 5000, ''),
(2555, 'Neumatico 175/65 R14 PIRELLI 82T', '', '2', '2', 5000, ''),
(2554, 'Neumatico 175/65 R15 Nexen  84H', '', '2', '2', 5000, ''),
(2553, 'Neumatico 175/70 R14 Goodride', '', '15', '2', 5000, ''),
(2551, 'Neumatico 175/70 R14 Winrun 84T', '', '8', '2', 5000, ''),
(2552, 'Neumatico 175/70 R14 Nexen 84T', '', '12', '2', 5000, ''),
(2550, 'Neumatico 185 R14 C Goodride  H188', '', '7', '2', 5000, ''),
(2549, 'Neumatico 185 R14 C LingLong 8PR', '', '1', '1', 5000, ''),
(2548, 'Neumatico 185 R14 c Tristar 100Q', '', '1', '1', 5000, ''),
(2547, 'Neumatico 185 R14 C Windorce', '', '12', '2', 5000, ''),
(2546, 'Neumatico 185 R14 Nexen 8pr', '', '6', '2', 5000, ''),
(2545, 'Neumatico 185/55 R15 Goodride RP28', '', '8', '2', 5000, ''),
(2543, 'Neumatico 185/55 R16 Windforce 83h', '', '4', '2', 5000, ''),
(2544, 'Neumatico 185/55 R16 Goodride RP28', '', '4', '2', 5000, ''),
(2541, 'Neumatico 185/60 R14 COOPER 82H', '', '6', '2', 5000, ''),
(2542, 'Neumatico 185/60 R14 COOPER ', '', '3', '2', 5000, ''),
(2540, 'Neumatico 185/60 R14 Duration 82H ', '', '1', '2', 5000, ''),
(2539, 'Neumatico 185/60 R14 Goodride RP28', '', '7', '2', 5000, ''),
(2538, 'Neumatico 185/60 R14 Nexen 82H', '', '4', '2', 5000, ''),
(2537, 'Neumatico 185/60 R15 Cooper RP 28', '', '5', '2', 5000, ''),
(2536, 'Neumatico 185/60 R15 Windforce 88H ', '', '6', '2', 5000, ''),
(2535, 'Neumatico 185/65 R14 Continental 86H', '', '6', '2', 5000, ''),
(2534, 'Neumatico 185/65 R14 Goodride RP28', '', '22', '2', 5000, ''),
(2533, 'Neumatico 185/65 R14 Hankook 86T ', '', '3', '2', 5000, ''),
(2532, 'Neumatico 185/65 r14 Sumitomo 86H ', '', '1', '2', 5000, ''),
(2531, 'Neumatico 185/65 R15 COOPER ', '', '1', '2', 5000, ''),
(2530, 'Neumatico 185/65 R15 Cooper 88V ', '', '1', '2', 5000, ''),
(2529, 'Neumatico 185/65 R15 Farroad 88H', '', '4', '2', 5000, ''),
(2528, 'Neumatico 185/65 R15 Goodride RP28', '', '12', '2', 5000, ''),
(2527, 'Neumatico 185/65 R15 Triangle 88H', '', '4', '2', 5000, ''),
(2526, 'Neumatico 185/70 R14 82T HANKOOK ', '', '4', '2', 5000, ''),
(2524, 'Neumatico 185/70 R14 Jinyu 88H', '', '1', '2', 5000, ''),
(2525, 'Neumatico 185/70 R14 Goodride RP28', '', '12', '2', 5000, ''),
(2522, 'Neumatico 185/70 R14 Sumitomo 88H', '', '1', '2', 5000, ''),
(2523, 'Neumatico 185/70 R14 Nexen 88T', '', '1', '2', 5000, ''),
(2521, 'Neumatico 186/65 R14 COOPER ', '', '1', '2', 5000, ''),
(2520, 'Neumatico 195 R15C Goodride h188', '', '13', '2', 5000, ''),
(2519, 'Neumatico 195 R15C Winforce 104R ', '', '1', '2', 5000, ''),
(2518, 'Neumatico 195 R15C Zeta 8PR', '', '1', '2', 5000, ''),
(2517, 'Neumatico 195/50 R15 Goodride RP 28 ', '', '5', '2', 5000, ''),
(2516, 'Neumatico 195/50 R15 Neolyn', '', '7', '2', 5000, ''),
(2515, 'Neumatico 195/50 R16 Goodride RP28', '', '11', '2', 5000, ''),
(2514, 'Neumatico 195/50 R16 Nexen 84H', '', '3', '2', 5000, ''),
(2513, 'Neumatico 195/55 R15 85V ', '', '2', '2', 5000, ''),
(2512, 'Neumatico 195/55 R15 Cooper 85V', '', '1', '2', 5000, ''),
(2511, 'Neumatico 195/55 R15 Goodride RP28', '', '2', '2', 5000, ''),
(2510, 'Neumatico 195/55 R16 Goodride RP28', '', '8', '2', 5000, ''),
(2509, 'Neumatico 195/55 R16 Onyx 91V', '', '1', '2', 5000, ''),
(2508, 'Neumatico 195/60 R14 Goodride RP28', '', '1', '2', 5000, ''),
(2507, 'Neumatico 195/60 R14 Xbri 86H ', '', '4', '2', 5000, ''),
(2506, 'Neumatico 195/60 R15 Goodride RP28', '', '8', '2', 5000, ''),
(2505, 'Neumatico 195/60 R15 Ling long 88H', '', '6', '2', 5000, ''),
(2504, 'Neumatico 195/60 R16 Goodride RP28', '', '1', '2', 5000, ''),
(2502, 'Neumatico 195/70 R15 Hankook 8PR', '', '2', '2', 5000, ''),
(2503, 'Neumatico 195/65 R15 Windforce 95H', '', '19', '2', 5000, ''),
(2501, 'Neumatico 205 R16 Nexen 8PR', '', '5', '2', 5000, ''),
(2500, 'Neumatico 205/40 R17 Triangle 44W', '', '8', '2', 5000, ''),
(2499, 'Neumatico 205/45 R17 Michelin 88V', '', '2', '2', 5000, ''),
(2498, 'Neumatico 205/50 R16 Nexen 87V', '', '1', '2', 5000, ''),
(2497, 'Neumatico 205/50 R17 Goodride SA37', '', '7', '2', 5000, ''),
(2496, 'Neumatico 205/55 R16 Continental 91H', '', '4', '2', 5000, ''),
(2494, 'Neumatico 205/55 R16 Goodride SA57', '', '1', '2', 5000, ''),
(2495, 'Neumatico 205/55 R16 Goodride RP28', '', '9', '2', 5000, ''),
(2493, 'Neumatico 205/55 R16 Nexen 89H ', '', '1', '2', 5000, ''),
(2492, 'Neumatico 205/55 R16 SA57', '', '2', '2', 5000, ''),
(2491, 'Neumatico 205/55 R16 Windforce 91V', '', '1', '2', 5000, ''),
(2489, 'Neumatico 205/60 R15 Goodride RP28', '', '7', '2', 5000, ''),
(2490, 'Neumatico 205/55 R17 Goodride SA37', '', '4', '2', 5000, ''),
(2488, 'Neumatico 205/60 R15 Nexen 90H', '', '1', '2', 5000, ''),
(2487, 'Neumatico 205/60 R16 Linglong 92h', '', '1', '2', 5000, ''),
(2486, 'Neumatico 205/65 R15 Cooper 94H', '', '4', '2', 5000, ''),
(2484, 'Neumatico 205/65 R15 Goodride SL369', '', '2', '2', 5000, ''),
(2485, 'Neumatico 205/65 R15 Goodride RP28', '', '2', '2', 5000, ''),
(2483, 'Neumatico 205/65 R15 Nexen 94H', '', '2', '2', 5000, ''),
(2482, 'Neumatico 205/65 R15 Tristar 94H', '', '1', '2', 5000, ''),
(2481, 'Neumatico 205/65 R16 Nexen 95H', '', '1', '2', 5000, ''),
(2480, 'Neumatico 205/70 R14 Nexen 6PR ', '', '4', '2', 5000, ''),
(2479, 'Neumatico 205/70 R15 C Goodride H188', '', '4', '2', 5000, ''),
(2478, 'Neumatico 205/70 R15 C Windforce 104R', '', '1', '2', 5000, ''),
(2477, 'Neumatico 205/70 R15 Hankook 104R', '', '1', '1', 5000, ''),
(2476, 'Neumatico 205/70 R15 Hankook 8PR ', '', '2', '2', 5000, ''),
(2475, 'Neumatico 205/70 R15 Nexen 6pr', '', '7', '2', 5000, ''),
(2472, 'Neumatico 215/45 R18 NEXEN CP672', '', '1', '2', 5000, ''),
(2473, 'Neumatico 215/45 R17 SA57', '', '7', '2', 5000, ''),
(2474, 'Neumatico 215/45 R17 NEXEN cp671', '', '3', '2', 5000, ''),
(2471, 'Neumatico 215/50 R17 Goodride 95W', '', '1', '2', 5000, ''),
(2470, 'Neumatico 215/55 R16 Nexen 93V', '', '2', '2', 5000, ''),
(2469, 'Neumatico 215/60 R16 Goodride RP28', '', '4', '2', 5000, ''),
(2468, 'Neumatico 215/60 R17 Lingling 96H', '', '4', '2', 5000, ''),
(2467, 'Neumatico 215/60 R17 Nexen 96H ', '', '1', '2', 5000, ''),
(2466, 'Neumatico 215/65 R15 Goodride 96H', '', '1', '2', 5000, ''),
(2465, 'Neumatico 215/65 R15 Goodride SP06', '', '1', '2', 5000, ''),
(2464, 'Neumatico 215/65 R16 C GrenLander 8PR ', '', '2', '2', 5000, ''),
(2462, 'Neumatico 215/65 R16 Nexen 98H', '', '1', '1', 5000, ''),
(2463, 'Neumatico 215/65 R16 Goodride RP28', '', '2', '2', 5000, ''),
(2461, 'Neumatico 215/65 R16 Nexen 98H', '', '5', '2', 5000, ''),
(2460, 'Neumatico 215/65 R17 Nexen 104T', '', '3', '2', 5000, ''),
(2459, 'Neumatico 215/65 R17 Nexen 104T ', '', '1', '3', 5000, ''),
(2458, 'Neumatico 215/65 R17 NEXEN CP521', '', '3', '2', 5000, ''),
(2457, 'Neumatico 215/70 R14 S ++++ marca', '', '2', '2', 5000, ''),
(2456, 'Neumatico 215/70 R15 C Nexen 8PR', '', '1', '3', 5000, ''),
(2455, 'Neumatico 215/70 R15 C Windforce 105R', '', '2', '2', 5000, ''),
(2453, 'Neumatico 215/70 R16 C Goodride SC328', '', '1', '2', 5000, ''),
(2454, 'Neumatico 215/70 R15 Windforce 98H', '', '1', '2', 5000, ''),
(2451, 'Neumatico 215/70 R16 Goodride SL369', '', '12', '2', 5000, ''),
(2452, 'Neumatico 215/70 R16 Goodride 100S', '', '3', '1', 5000, ''),
(2449, 'Neumatico 215/70 R16 Nexen 10PR ', '', '3', '2', 5000, ''),
(2450, 'Neumatico 215/70 R16 Hankook 6PR', '', '2', '2', 5000, ''),
(2448, 'Neumatico 215/75 R14 C Haida 101Q', '', '1', '2', 5000, ''),
(2447, 'Neumatico 215/75 R14 C LingLong 8PR', '', '1', '3', 5000, ''),
(2445, 'Neumatico 215/75 R14 General 100S', '', '1', '3', 5000, ''),
(2446, 'Neumatico 215/75 R14 C LingLong R666', '', '3', '3', 5000, ''),
(2444, 'Neumatico 215/75 R14C LingLong 100R ', '', '5', '2', 5000, ''),
(2443, 'Neumatico 215/75 R15 General tires ', '', '2', '2', 5000, ''),
(2442, 'Neumatico 215/75 R15 Goodride ', '', '1', '1', 5000, ''),
(2441, 'Neumatico 215/75 R15 Haida 100S', '', '2', '3', 5000, ''),
(2440, 'Neumatico 215/75 R15 Nexen 100S', '', '2', '2', 5000, ''),
(2439, 'Neumatico 215/75 R17.5 Fesite 16PR ', '', '4', '3', 5000, ''),
(2438, 'Neumatico 225/40 R18 ZETA', '', '2', '2', 5000, ''),
(2437, 'neumatico 225/50 R17 Goodride SA05', '', '1', '2', 5000, ''),
(2436, 'Neumatico 225/50 R17 Nexen 94V', '', '2', '2', 5000, ''),
(2435, 'Neumatico 225/50 R17 Nexen CP672', '', '1', '2', 5000, ''),
(2434, 'Neumatico 225/55 R17 Nexen 97V', '', '5', '2', 5000, ''),
(2433, 'Neumatico 225/55 R18 Nexen 97H CP672', '', '1', '2', 5000, ''),
(2432, 'Neumatico 225/60 R16 Nexen 98H', '', '1', '2', 5000, ''),
(2431, 'Neumatico 225/60 R16 Nexen CP672', '', '4', '2', 5000, ''),
(2430, 'Neumatico 225/60 R17 Cooper 99H', '', '4', '2', 5000, ''),
(2428, 'Neumatico 225/60 R18 Nexen 99h ', '', '1', '3', 5000, ''),
(2429, 'Neumatico 225/60 R17 Goodride SU318', '', '5', '3', 5000, ''),
(2427, 'Neumatico 225/60 R18 Nexen 99H ', '', '1', '2', 5000, ''),
(2426, 'Neumatico 225/65 R16 Nexen 99H ', '', '3', '2', 5000, ''),
(2425, 'Neumatico 225/65 R17 Goodride 102T ', '', '1', '3', 5000, ''),
(2423, 'Neumatico 225/65 R17 Nexen CP521', '', '2', '2', 5000, ''),
(2424, 'Neumatico 225/65 R17 Goodride SU318', '', '3', '3', 5000, ''),
(2422, 'Neumatico 225/65 R17 Triangle 102H ', '', '5', '3', 5000, ''),
(2421, 'Neumatico 225/70 R15 Goodride SL369 ', '', '4', '2', 5000, ''),
(2420, 'Neumatico 225/70 R15 Nexen 100S ', '', '5', '3', 5000, ''),
(2419, 'Neumatico 225/70 R15 Nexen 8PR ', '', '1', '2', 5000, ''),
(2418, 'neumatico 225/70 R15C Linglong 110R', '', '1', '2', 5000, ''),
(2417, 'Neumatico 225/70 R16 Cooper 103T', '', '4', '3', 5000, ''),
(2416, 'Neumatico 225/70 R16 Mazzini 107T ', '', '5', '3', 5000, ''),
(2415, 'Neumatico 225/70 R17 Goodride SL369', '', '5', '3', 5000, ''),
(2411, 'Neumatico 235/60 R16 Goodride RP28', '', '4', '3', 5000, ''),
(2412, 'Neumatico 225/75 R16 Nexen 104H ', '', '1', '3', 5000, ''),
(2413, 'Neumatico 225/75 R16 Goodride SL369', '', '4', '3', 5000, ''),
(2414, 'Neumatico 225/75 R16 Cooper 104T ', '', '4', '3', 5000, ''),
(2410, 'Neumatico 235/60 R16 Hankook 100W ', '', '2', '3', 5000, ''),
(2409, 'Neumatico 235/60 R16 Nexen 100V', '', '2', '3', 5000, ''),
(2408, 'Neumatico 235/60 R17 LingLong 102H ', '', '6', '3', 5000, ''),
(2407, 'Neumatico 235/60 R17 Nexen 102H ', '', '2', '3', 5000, ''),
(2406, 'Neumatico 235/60 R18 Nexen 102H ', '', '3', '3', 5000, ''),
(2403, 'Neumatico 235/70 R15 Goodride 103H', '', '1', '3', 5000, ''),
(2404, 'Neumatico 235/65 R17 Nexen 104H', '', '1', '3', 5000, ''),
(2405, 'Neumatico 235/65 R17 Goodride SU318', '', '2', '3', 5000, ''),
(2400, 'Neumatico 235/70 R16 Onyx 106T', '', '4', '3', 5000, ''),
(2402, 'Neumatico 235/70 R16 Cooper 106T ', '', '5', '3', 5000, ''),
(2401, 'Neumatico 235/70 R16 Goodride SL369', '', '8', '3', 5000, ''),
(2399, 'Neumatico 235/70 R16 WindForce 106H ', '', '2', '3', 5000, ''),
(2398, 'Neumatico 235/75 R15 Goodride SL369', '', '4', '3', 5000, ''),
(2397, 'Neumatico 235/75 R15 Maxxis 6PR', '', '1', '3', 5000, ''),
(2396, 'Neumatico 235/75 R15 Nexen 105S', '', '1', '3', 5000, ''),
(2395, 'Neumatico 235/75 R15 Nexen 109S', '', '2', '3', 5000, ''),
(2394, 'Neumatico 235/75 R16 Cooper 108T', '', '2', '3', 5000, ''),
(2393, 'Neumatico 235/75 R16 Goodride 108H ', '', '2', '3', 5000, ''),
(2392, 'Neumatico 235/75 R16 Nexen 108H', '', '1', '3', 5000, ''),
(2391, 'Neumatico 235/75 R16 Runway 108T ', '', '1', '3', 5000, ''),
(2390, 'Neumatico 245/60 R18 Goodride SU318', '', '7', '3', 5000, ''),
(2389, 'Neumatico 245/65 R17 Goodride SU317', '', '2', '3', 5000, ''),
(2388, 'Neumatico 245/65 R17 Nexen 105S ', '', '1', '3', 5000, ''),
(2387, 'Neumatico 245/70 R16 Cooper 107T ', '', '2', '3', 5000, ''),
(2386, 'Neumatico 245/70 R16 DuraTurn 107T ', '', '8', '3', 5000, ''),
(2385, 'Neumatico 245/70 R16 Goodride SL369', '', '6', '3', 5000, ''),
(2384, 'Neumatico 245/70 R16 Hankook 107H', '', '1', '3', 5000, ''),
(2383, 'Neumatico 245/75 R16 Cooper 116R ', '', '8', '3', 5000, ''),
(2382, 'Neumatico 245/75 R16 Cooper LT ', '', '3', '3', 5000, ''),
(2381, 'Neumatico 245/75 R16 Goodride SL369', '', '5', '3', 5000, ''),
(2380, 'Neumatico 245/75 R16 Mazzini 10PR', '', '7', '3', 5000, ''),
(2379, 'Neumatico 245/75 R16 Mazzini 10PR', '', '12', '3', 5000, ''),
(2378, 'Neumatico 245/75 R16 Nexen 109 S', '', '2', '3', 5000, ''),
(2377, 'Neumatico 245/75 R16 Nexen 10PR ', '', '4', '3', 5000, ''),
(2376, 'Neumatico 255/40 R18 Nexen 99Y', '', '2', '2', 5000, ''),
(2375, 'Neumatico 255/55 R18 Nexen 109V', '', '1', '1', 5000, ''),
(2374, 'Neumatico 255/60 R17 Nexen 106V', '', '1', '3', 5000, ''),
(2373, 'Neumatico 255/65 R16 Maxxis 109H', '', '1', '3', 5000, ''),
(2372, 'Neumatico 255/65 R16 Nexen 109V ', '', '2', '3', 5000, ''),
(2371, 'Neumatico 255/65 R17 Cooper 110T', '', '1', '3', 5000, ''),
(2369, 'Neumatico 255/65 R17 Nexen 110S', '', '3', '3', 5000, ''),
(2370, 'Neumatico 255/65 R17 Goodride SL369 ', '', '3', '3', 5000, ''),
(2368, 'Neumatico 255/65 R17 Nexen 110S', '', '1', '3', 5000, ''),
(2367, 'Neumatico 255/70 R16 Antares ', '', '1', '3', 5000, ''),
(2366, 'Neumatico 255/70 R16 Cooper 111T ', '', '2', '3', 5000, ''),
(2365, 'Neumatico 255/70 R16 Goodride 111T', '', '4', '3', 5000, ''),
(2364, 'Neumatico 255/70 R16 Goodride SL369', '', '1', '3', 5000, ''),
(2363, 'Neumatico 255/70 R16 Mazzini 111T ', '', '4', '3', 5000, ''),
(2362, 'Neumatico 255/70 R16 Nexen 109S', '', '4', '3', 5000, ''),
(2361, 'Neumatico 255/70 R16 Nexen 109S', '', '1', '3', 5000, ''),
(2360, 'Neumatico 265/60 R17 Nexen 108V', '', '1', '3', 5000, ''),
(2354, 'Neumatico 265/70 R16 Onyx 112T ', '', '4', '3', 5000, ''),
(2359, 'Neumatico 265/60 R18 Nexen 110H', '', '6', '3', 5000, ''),
(2358, 'Neumatico 265/65 R17 Goodride SL369', '', '4', '3', 5000, ''),
(2357, 'Neumatico 265/65 R17 Nexen 112T', '', '1', '3', 5000, ''),
(2356, 'Neumatico 265/70 R16 Goodride SL369', '', '5', '3', 5000, ''),
(2355, 'Neumatico 265/70 R16 Nexen 112S ', '', '1', '3', 5000, ''),
(2353, 'Neumatico 265/70 R17 Cooper AT', '', '1', '3', 5000, ''),
(2352, 'Neumatico 265/70 R17 Nexen 113S', '', '3', '3', 5000, ''),
(2351, 'Neumatico 265/75 R16 Goodride SL369', '', '1', '3', 5000, ''),
(2349, 'Neumatico 275/60 R17 Nexen 110V', '', '2', '3', 5000, ''),
(2350, 'Neumatico 265/75 R16 Goodride SL369  ', '', '4', '3', 5000, ''),
(2346, 'Neumatico 31x10.50 R15 Ling Long 109R', '', '1', '3', 5000, ''),
(2348, 'Neumatico 295/45 R20 Nexen 114V', '', '2', '3', 5000, ''),
(2347, 'Neumatico 31X10.50 R15 Ling Long 109Q', '', '6', '3', 5000, ''),
(2345, 'Neumatico 500 R12 C', '', '2', '2', 5000, ''),
(2344, 'Neumatico 500 R12 Goodride ', '', '14', '2', 5000, ''),
(2343, 'Neumatico 500 R12 Nexen 8PR', '', '1', '2', 5000, ''),
(2342, 'Neumatico 700 R15 Goodride 10PR LT', '', '5', '3', 5000, ''),
(2341, 'Neumatico 700 R15 Goodride ST313', '', '1', '3', 5000, ''),
(2340, 'Neumatico 700 R16 Ling Long LT', '', '2', '3', 5000, ''),
(2339, 'Neumatico 750 R16 Aushine 8PR', '', '2', '3', 5000, ''),
(2338, 'Neumatico 750 R16 LingLong ', '', '4', '3', 5000, ''),
(2337, 'Neumatico 750 R6 LingLong 112L', '', '2', '3', 5000, ''),
(2594, 'shell helix hx7 10W40 ', '', '2', 'Bodega 1', 23000, ''),
(2595, '10W40 VALVOLINE ', 'LITROS', '0', '', 2000, ''),
(2648, 'demo', 'demo', '24', NULL, 25000, ''),
(2649, 'prueba', 'prueba', '2', NULL, 233333, 'sadsad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id_items` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name_product` varchar(300) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` varchar(100) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id_items`, `id`, `name_product`, `quantity`, `location`, `price`, `total`) VALUES
(50, 34, 'Aceite 10w40 4 litros shell', '1', 'Bodega 2', '27000', 27000),
(49, 33, 'Aceite 10w40 4 litros total', '3', 'Bodega 2', '19009', 57027),
(171, 101, '2593', '1,2', '', '5000', 6000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id_marcas` int(11) NOT NULL,
  `id_vehiculos` int(11) NOT NULL,
  `nombre_marca` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id_marcas`, `id_vehiculos`, `nombre_marca`) VALUES
(1, 3, 'Hyundai'),
(2, 3, 'Suzuki'),
(3, 3, 'Chevrolet'),
(4, 3, 'Chery'),
(5, 3, 'Mitsubishi'),
(6, 3, 'Toyota'),
(8, 3, 'Ford'),
(9, 3, 'Kia'),
(10, 3, 'Nissan'),
(11, 3, 'Mazda'),
(12, 3, 'ByD'),
(13, 3, 'Ssangyong'),
(14, 3, 'Isuzu'),
(15, 3, 'Peugeot');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanicos`
--

CREATE TABLE `mecanicos` (
  `id_mecanicos` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mecanicos`
--

INSERT INTO `mecanicos` (`id_mecanicos`, `nombre`) VALUES
(1, 'Danilo'),
(2, 'Jose/Victor'),
(3, 'Eduardo'),
(4, 'Hans'),
(5, 'Victor'),
(6, 'Fabio'),
(7, 'Clemente'),
(8, 'Ivan'),
(9, 'José'),
(10, 'Eugenio'),
(11, 'Jorge');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `id_modelos` int(11) NOT NULL,
  `id_marcas` int(11) NOT NULL,
  `nombre_modelo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `modelos`
--

INSERT INTO `modelos` (`id_modelos`, `id_marcas`, `nombre_modelo`) VALUES
(1, 1, 'Sail'),
(2, 1, 'Celerio'),
(3, 2, 'Tiggo 2'),
(4, 2, 'L200'),
(5, 2, 'Yaris'),
(6, 3, 'HiAce'),
(7, 3, 'Maruti'),
(8, 4, 'Fiesta'),
(9, 2, 'Sorento'),
(11, 1, 'Sentra II'),
(12, 3, 'B2900'),
(13, 3, 'F0'),
(14, 4, 'Frontier'),
(15, 4, 'DMAX');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `area` varchar(100) NOT NULL,
  `accion_a_realizar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permisos`, `id_usuario`, `area`, `accion_a_realizar`) VALUES
(3, 3, 'Productos', 'Agregar,Editar,Eliminar,Acciones,Busqueda,PDF'),
(4, 3, 'Vehiculos', 'Agregar,Editar,Eliminar,Eliminacion masiva,Busqueda'),
(5, 3, 'Modelos', 'Agregar,Editar,Eliminar,Eliminacion masiva,Busqueda'),
(6, 3, 'Mecanicos', 'Agregar,Editar,Acciones,Busqueda'),
(7, 3, 'Ubicacion', 'Agregar,Editar,Acciones,Busqueda'),
(8, 3, 'Marcas', 'Agregar,Editar,Eliminacion masiva,Busqueda'),
(9, 3, 'Administrar Vehiculos', 'Agregar,Editar,Acciones,Busqueda,PDF'),
(10, 3, 'Agregar mas Productos', 'Agregar,Editar,Eliminar,Eliminacion masiva,Busqueda'),
(11, 3, 'Inventario de Productos', 'Agregar,Editar,Acciones,Busqueda'),
(13, 3, 'Respalda tus datos', 'Agregar,Eliminar,Eliminacion masiva,Busqueda'),
(51, 3, 'Clientes', 'Agregar,Editar,Acciones,Busqueda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planilla`
--

CREATE TABLE `planilla` (
  `id_planilla` int(11) NOT NULL,
  `usuario` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tipo_vehiculo` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `patente` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ano` varchar(100) NOT NULL,
  `fecha` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `mes` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `estado_total` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `confirmar_pago` int(11) NOT NULL,
  `n_carril` int(11) NOT NULL,
  `mecanico` varchar(200) NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL,
  `observacion` text CHARACTER SET utf8 DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `rut` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fono` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `planilla`
--

INSERT INTO `planilla` (`id_planilla`, `usuario`, `tipo_vehiculo`, `marca`, `modelo`, `patente`, `ano`, `fecha`, `mes`, `dia`, `estado_total`, `confirmar_pago`, `n_carril`, `mecanico`, `hora_entrada`, `hora_salida`, `observacion`, `nombre`, `rut`, `direccion`, `correo`, `fono`) VALUES
(341, 'ana', '3', '1', '1', 'jop905', '2003', '2022-09-05', 'Febrero', 5, 'Pagado', 1, 2, 'Jose/Victor', '00:00:00', '00:00:00', '', 'mario vejar', '44.444.444-4', 'demo 1234', 'sdsds@fkdjf.ccc', '+562 32323232'),
(399, 'ana', '3', '2', '3', 'jop905', '1993', '2023-05-25', 'Agosto', 2, 'Pendiente de pago', 0, 5, 'Danilo,Eduardo', '00:00:00', '00:00:00', 'asdsadasdasdadad', 'Marcelo Reyes', '22.222.222-2', 'demo 123', 'marcelo@reyes.cl', '+569 83283237');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `hour` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `username`, `date`, `hour`) VALUES
(34, 'demo', '2021-01-26', '14:56:14'),
(33, 'Ana Velasquez', '2021-01-26', '14:07:41'),
(101, 'ana', '2024-07-31', '15:40:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `name`) VALUES
(1, 'Administrador'),
(2, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_ubicacion` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_ubicacion`, `nombre`) VALUES
(1, 'Pozo'),
(2, 'Se Fue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rol` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `image`, `background_image`, `email_verified_at`, `password`, `old_password`, `remember_token`, `created_at`, `updated_at`, `rol`, `state`) VALUES
(3, 'ana', 'ana', 'lubrivelasquez@hotmail.com', '/lubricentro/app/script/uploads/2.png', '/lubricentro/app/script/uploads/tienda3.jpg', NULL, '$2y$10$Xd.8d6Jb6kcJXVNFM6Q67uvW0FURkAHxc/AklrwD2P9JN4lF6gMx6', '$2y$10$AvYk5QlDDpQCqHXXk8zyEOF8EcE0NjR5bTCa5JT1CLKpeU/zamghK', NULL, '2021-01-24 10:47:18', '2021-02-03 19:45:08', 'Administrador', 1),
(5, 'demo', 'demo', 'demo@demo.cl', '/lubricentro/app/script/uploads/2.png', '/lubricentro/app/script/uploads/tienda3.jpg', NULL, '$2y$10$K/PrlQPD.atpovAkjZx8zupnrw8Vd3ZXnTYP3DtOg.1HI23X3hFxi', NULL, NULL, '2022-09-02 19:42:54', '2022-09-02 19:53:11', 'Vendedor', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculos` int(11) NOT NULL,
  `nombre_vehiculo` varchar(100) NOT NULL,
  `vehiculo_patente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculos`, `nombre_vehiculo`, `vehiculo_patente`) VALUES
(3, 'Camioneta', 'jop905'),
(4, 'Furgon', 'oli957y'),
(5, 'Camion', 'ky905y'),
(6, 'Taxi', 'ncc27'),
(7, 'Moto', 'es430'),
(9, 'Auto', 'ae000aa'),
(10, 'Suv', 'knj605'),
(72, 'Tractor', '6y7885fed');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vista_pdf`
--

CREATE TABLE `vista_pdf` (
  `id_vista` int(11) NOT NULL,
  `columnas` mediumtext NOT NULL,
  `textos` mediumtext NOT NULL,
  `usuario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `vista_pdf`
--

INSERT INTO `vista_pdf` (`id_vista`, `columnas`, `textos`, `usuario`) VALUES
(5, 'ubicacion,marca', 'modelo,patente,info_cliente', 'demo'),
(6, 'tipo_vehiculo', 'marca', 'clemente'),
(7, 'tipo_vehiculo', 'ubicacion', 'ana');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agregar_productos`
--
ALTER TABLE `agregar_productos`
  ADD PRIMARY KEY (`id_agregar_productos`);

--
-- Indices de la tabla `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_clientes`);

--
-- Indices de la tabla `columnas_planilla`
--
ALTER TABLE `columnas_planilla`
  ADD PRIMARY KEY (`id_columnas_planilla`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indices de la tabla `importar`
--
ALTER TABLE `importar`
  ADD PRIMARY KEY (`id_importar`);

--
-- Indices de la tabla `import_product`
--
ALTER TABLE `import_product`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id_inventory`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id_items`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marcas`);

--
-- Indices de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD PRIMARY KEY (`id_mecanicos`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id_modelos`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`);

--
-- Indices de la tabla `planilla`
--
ALTER TABLE `planilla`
  ADD PRIMARY KEY (`id_planilla`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculos`);

--
-- Indices de la tabla `vista_pdf`
--
ALTER TABLE `vista_pdf`
  ADD PRIMARY KEY (`id_vista`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agregar_productos`
--
ALTER TABLE `agregar_productos`
  MODIFY `id_agregar_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=625;

--
-- AUTO_INCREMENT de la tabla `backup`
--
ALTER TABLE `backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_clientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `columnas_planilla`
--
ALTER TABLE `columnas_planilla`
  MODIFY `id_columnas_planilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `importar`
--
ALTER TABLE `importar`
  MODIFY `id_importar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `import_product`
--
ALTER TABLE `import_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id_inventory` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2650;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id_items` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id_marcas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  MODIFY `id_mecanicos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id_modelos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `planilla`
--
ALTER TABLE `planilla`
  MODIFY `id_planilla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `vista_pdf`
--
ALTER TABLE `vista_pdf`
  MODIFY `id_vista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
