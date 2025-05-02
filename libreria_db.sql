-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 29-04-2025 a las 03:00:01
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `libreria_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

DROP TABLE IF EXISTS `libros`;
CREATE TABLE IF NOT EXISTS `libros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `sinopsis` text,
  `isbn` varchar(20) DEFAULT NULL,
  `imagen_portada` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `categoria`, `precio`, `sinopsis`, `isbn`, `imagen_portada`) VALUES
(1, 'Cien Años de Soledad', 'Gabriel García Márquez', 'Novela', 15.99, 'Una saga familiar mítica en el pueblo de Macondo.', '978-3-16-148410-0', 'cienaños.jpg'),
(2, 'El Principito', 'Antoine de Saint-Exupéry', 'Fábula', 10.00, 'Un clásico de la literatura infantil.', '978-1-56619-909-4', 'principito'),
(5, '1984', 'George Orwell', 'Distopía', 12.50, 'Una novela sobre un régimen totalitario que vigila todos los aspectos de la vida.', '978-0-452-28423-4', '1984'),
(6, 'Orgullo y Prejuicio', 'Jane Austen', 'Romance', 9.99, 'Una historia de amor y prejuicios en la Inglaterra georgiana.', '978-0-19-953556-9', 'orgulloyprejuicio'),
(7, 'Don Quijote de la Mancha', 'Miguel de Cervantes', 'Clásico', 18.00, 'Las aventuras cómicas y trágicas de un caballero soñador.', '978-0-14-243723-0', 'quijote'),
(8, 'La Odisea', 'Homero', 'Épica', 14.50, 'La legendaria travesía de Odiseo para regresar a Ítaca.', '978-0-14-026886-7', 'odisea'),
(9, 'Crimen y Castigo', 'Fiódor Dostoyevski', 'Novela psicológica', 13.75, 'La lucha moral de un joven tras cometer un crimen.', '978-0-14-305814-4', 'castigo'),
(10, 'Rayuela', 'Julio Cortázar', 'Novela', 16.25, 'Una novela innovadora sobre la vida, el amor y la búsqueda de sentido.', '978-0-394-73696-1', 'rayuela'),
(11, 'Los Miserables', 'Victor Hugo', 'Drama', 17.90, 'Una épica sobre la justicia, la pobreza y la redención en la Francia del siglo XIX.', '978-0-451-52899-0', 'miserables'),
(12, 'Matar a un Ruiseñor', 'Harper Lee', 'Ficción', 11.80, 'Una niña enfrenta las injusticias raciales en el sur de Estados Unidos.', '978-0-06-112008-4', 'ruiseñor'),
(13, 'Fahrenheit 451', 'Ray Bradbury', 'Ciencia ficción', 10.75, 'Un mundo donde los libros están prohibidos y deben ser quemados.', '978-1-4516-7331-9', '451');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
