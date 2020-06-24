-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 24 juin 2020 à 16:30
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `drmbarbershop`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `Last_Name` varchar(255) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `E_Mail` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Auth` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`Last_Name`, `First_Name`, `E_Mail`, `Password`, `Auth`) VALUES
('mohammad', 'najafy', 'm.najafy@hotmail.com', 'azerty', 1234);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `title`) VALUES
(1, 'alpha'),
(2, 'delta'),
(3, 'romeo');

-- --------------------------------------------------------

--
-- Structure de la table `imgs`
--

DROP TABLE IF EXISTS `imgs`;
CREATE TABLE IF NOT EXISTS `imgs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `imgs`
--

INSERT INTO `imgs` (`id`, `img`, `title`, `content`, `category_id`) VALUES
(1, 'gallery_1.jpg', 'titre N°1', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 2),
(2, 'gallery_2.jpg', 'titre N°2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 1),
(3, 'gallery_3.jpg', 'titre N°3', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 1),
(4, 'gallery_4.jpg', 'titre N°4', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 3),
(5, 'gallery_5.jpg', 'titre N°5', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 2),
(6, 'gallery_6.jpg', 'titre N°6', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, fugit?', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
