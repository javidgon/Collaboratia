-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-03-2011 a las 12:39:52
-- Versión del servidor: 5.1.44
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `Collaboratia`
--
CREATE DATABASE `Collaboratia` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Collaboratia`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Board_tweet`
--

CREATE TABLE IF NOT EXISTS `Board_tweet` (
  `id_project` int(11) NOT NULL,
  `id_tweet` int(11) NOT NULL,
  PRIMARY KEY (`id_project`,`id_tweet`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de unión de cada proyecto y los diferentes tweets.';

--
-- Volcar la base de datos para la tabla `Board_tweet`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Milestone`
--

CREATE TABLE IF NOT EXISTS `Milestone` (
  `id_project` int(11) NOT NULL,
  `id_milestone` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `finalization_day` int(11) NOT NULL,
  `finalization_month` varchar(15) NOT NULL,
  `finalization_year` int(11) NOT NULL,
  PRIMARY KEY (`id_milestone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los milestones de cada proyecto.' AUTO_INCREMENT=0 ;



--
-- Estructura de tabla para la tabla `Preferences`
--

CREATE TABLE IF NOT EXISTS `Preferences` (
  `id_project` int(11) NOT NULL,
  `n_tweets` int(11) NOT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena las diferentes preferencias de cada proyecto.';

--
-- Volcar la base de datos para la tabla `Preferences`
--


--
-- Estructura de tabla para la tabla `Project`
--

CREATE TABLE IF NOT EXISTS `Project` (
  `id_project` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `n_people` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(10) NOT NULL,
  `category` varchar(50) NOT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los proyectos existentes en la aplicación.' AUTO_INCREMENT=0 ;


--
-- Estructura de tabla para la tabla `Project_code`
--

CREATE TABLE IF NOT EXISTS `Project_code` (
  `id_project` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que guarda los codigos de los proyectos privados.';



--
-- Estructura de tabla para la tabla `Project_user`
--

CREATE TABLE IF NOT EXISTS `Project_user` (
  `email` varchar(50) NOT NULL,
  `id_project` int(11) NOT NULL,
  `user_type` varchar(15) NOT NULL,
  PRIMARY KEY (`email`,`id_project`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que relaciona los usuarios y los proyectos.';


--
-- Estructura de tabla para la tabla `Session`
--

CREATE TABLE IF NOT EXISTS `Session` (
  `id_session` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `finalization_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_session`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena las sesiones de la aplicación.' AUTO_INCREMENT=0 ;

--
-- Estructura de tabla para la tabla `Task`
--

CREATE TABLE IF NOT EXISTS `Task` (
  `id_project` int(11) NOT NULL,
  `id_todo` int(11) NOT NULL,
  `id_task` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `responsible` varchar(50) NOT NULL,
  PRIMARY KEY (`id_task`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los tasks de la aplicación.' AUTO_INCREMENT=0 ;


--
-- Estructura de tabla para la tabla `To_do`
--

CREATE TABLE IF NOT EXISTS `To_do` (
  `id_project` int(11) NOT NULL,
  `id_todo` int(11) NOT NULL AUTO_INCREMENT,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(300) NOT NULL,
  PRIMARY KEY (`id_todo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los TO-DOs de la aplicación.' AUTO_INCREMENT=0 ;

--
-- Volcar la base de datos para la tabla `To_do`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tweet`
--

CREATE TABLE IF NOT EXISTS `Tweet` (
  `id_tweet` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(30) NOT NULL,
  `description` varchar(160) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `random` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_tweet`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los Tweets de la aplicación.' AUTO_INCREMENT=0 ;


--
-- Estructura de tabla para la tabla `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `picture` varchar(50) NOT NULL,
  `dateOfBirth_Day` int(10) unsigned NOT NULL,
  `dateOfBirth_Month` varchar(15) NOT NULL,
  `dateOfBirth_Year` int(10) unsigned NOT NULL,
  `field` varchar(50) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `university` varchar(50) NOT NULL,
  `country` varchar(30) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que almacena los usuarios de la aplicación.';

