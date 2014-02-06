-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 15 Janvier 2014 à 08:32
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `e-commerce2`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_categorie` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `prix` float(6,2) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `articles`
--


-- --------------------------------------------------------

--
-- Structure de la table `articlescategories`
--

CREATE TABLE IF NOT EXISTS `articlescategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `articlescategories`
--


-- --------------------------------------------------------

--
-- Structure de la table `articlescategoriestexts`
--

CREATE TABLE IF NOT EXISTS `articlescategoriestexts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `fk_categorie` int(11) DEFAULT NULL,
  `fk_langue` int(11) DEFAULT NULL,
  `categorie` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categorie` (`fk_categorie`),
  KEY `fk_langue` (`fk_langue`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `articlescategoriestexts`
--


-- --------------------------------------------------------

--
-- Structure de la table `articlescommandes`
--

CREATE TABLE IF NOT EXISTS `articlescommandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_commande` int(11) NOT NULL,
  `fk_article` int(11) NOT NULL,
  `nombre` int(11) NOT NULL,
  `prixUnitaire` float(6,2) NOT NULL,
  `prixTotal` float(6,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `articlescommandes`
--

INSERT INTO `articlescommandes` (`id`, `fk_commande`, `fk_article`, `nombre`, `prixUnitaire`, `prixTotal`) VALUES
(1, 1, 7, 7, 200.00, 400.00),
(2, 1, 2, 2, 200.00, 400.00),
(3, 1, 2, 2, 200.00, 400.00),
(4, 1, 2, 2, 200.00, 400.00),
(5, 1, 3, 3, 200.00, 400.00),
(6, 1, 2, 4, 200.00, 800.00),
(7, 1, 3, 3, 20.00, 60.00);

-- --------------------------------------------------------

--
-- Structure de la table `articlestexts`
--

CREATE TABLE IF NOT EXISTS `articlestexts` (
  `id` int(11) NOT NULL DEFAULT '0',
  `fk_article` int(11) DEFAULT NULL,
  `fk_langue` int(11) DEFAULT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `fk_langue` (`fk_langue`),
  KEY `fk_article` (`fk_article`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `articlestexts`
--


-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `adresse` text,
  `tel` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `prenom`, `adresse`, `tel`, `fax`, `email`, `site`, `dateNaissance`, `photo`) VALUES
(1, 'Chiaradia', 'Elliott', 'sdf sfsd', '021 452 14 14', '', '', '', '0000-00-00', ''),
(2, 'Piaget', 'Axel', '', '', '', '', '', '0000-00-00', ''),
(3, 'Buchs', 'Joël', '', '', '', '', '', '0000-00-00', ''),
(4, 'df', 'dfdf', 'dfdf', '', '', 'dfdf@df.com', '', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_client` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `prixTotal` float(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `commandes`
--

INSERT INTO `commandes` (`id`, `fk_client`, `date`, `prixTotal`) VALUES
(1, 1, '2013-12-12 00:00:00', 105.00),
(2, 2, '2013-12-25 00:00:00', 89.00);

-- --------------------------------------------------------

--
-- Structure de la table `langues`
--

CREATE TABLE IF NOT EXISTS `langues` (
  `id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(2) DEFAULT NULL,
  `defaut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `langues`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `articlescategoriestexts`
--
ALTER TABLE `articlescategoriestexts`
  ADD CONSTRAINT `articlescategoriestexts_ibfk_1` FOREIGN KEY (`fk_categorie`) REFERENCES `articlescategories` (`id`),
  ADD CONSTRAINT `articlescategoriestexts_ibfk_2` FOREIGN KEY (`fk_langue`) REFERENCES `langues` (`id`);

--
-- Contraintes pour la table `articlestexts`
--
ALTER TABLE `articlestexts`
  ADD CONSTRAINT `articlestexts_ibfk_1` FOREIGN KEY (`fk_langue`) REFERENCES `langues` (`id`),
  ADD CONSTRAINT `articlestexts_ibfk_2` FOREIGN KEY (`fk_article`) REFERENCES `articles` (`id`);
