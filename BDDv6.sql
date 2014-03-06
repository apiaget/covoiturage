-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 06 Mars 2014 à 09:23
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `covoiturage`
--
DROP DATABASE `covoiturage`;
CREATE DATABASE IF NOT EXISTS `covoiturage` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `covoiturage`;

-- --------------------------------------------------------

--
-- Structure de la table `badges`
--

CREATE TABLE IF NOT EXISTS `badges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  `picture` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_fk` int(11) DEFAULT NULL,
  `ride_fk` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `fk_comments_users1_idx` (`author_fk`),
  KEY `fk_comments_rides1_idx` (`ride_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `holidays`
--

CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `begin` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `registrations`
--

CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fk` int(11) NOT NULL,
  `ride_fk` int(11) NOT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `accepted` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_has_rides_rides1_idx` (`ride_fk`),
  KEY `fk_users_has_rides_users1_idx` (`user_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `registrations`
--

INSERT INTO `registrations` (`id`, `user_fk`, `ride_fk`, `startDate`, `endDate`, `accepted`) VALUES
(1, 2, 2, '2014-04-18 00:00:00', '2014-05-14 00:00:00', 0),
(2, 2, 1, '2014-04-18 00:00:00', '2014-05-14 00:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ridebadges`
--

CREATE TABLE IF NOT EXISTS `ridebadges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ride_fk` int(11) NOT NULL,
  `badge_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rides_has_badges_badges1_idx` (`badge_fk`),
  KEY `fk_rides_has_badges_rides1_idx` (`ride_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `rides`
--

CREATE TABLE IF NOT EXISTS `rides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_fk` int(11) NOT NULL,
  `departuretown_fk` int(11) NOT NULL,
  `arrivaltown_fk` int(11) NOT NULL,
  `bindedride` int(11) DEFAULT NULL,
  `description` text,
  `departure` time DEFAULT NULL,
  `arrival` time DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `startDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `visibility` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_rides_towns1_idx` (`departuretown_fk`),
  KEY `fk_rides_towns2_idx` (`arrivaltown_fk`),
  KEY `fk_rides_rides1_idx` (`bindedride`),
  KEY `fk_rides_users1_idx` (`driver_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `rides`
--

INSERT INTO `rides` (`id`, `driver_fk`, `departuretown_fk`, `arrivaltown_fk`, `bindedride`, `description`, `departure`, `arrival`, `seats`, `startDate`, `endDate`, `day`, `visibility`) VALUES
(1, 2, 2, 4, NULL, NULL, '06:24:00', '06:31:00', 2, '2014-03-04 00:00:00', '2014-06-27 00:00:00', 3, 0),
(2, 1, 3, 4, 1, NULL, '04:18:00', '04:33:00', 3, '2014-03-05 00:00:00', '2014-06-18 00:00:00', 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `towns`
--

CREATE TABLE IF NOT EXISTS `towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `towns`
--

INSERT INTO `towns` (`id`, `name`) VALUES
(1, 'Sainte-Croix'),
(2, 'Vevey'),
(3, 'Moudon'),
(4, 'Lausanne');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpnvId` varchar(45) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `hideEmail` int(11) DEFAULT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `hideTelephone` int(11) DEFAULT NULL,
  `notifInscription` int(11) DEFAULT NULL,
  `notifComment` int(11) DEFAULT NULL,
  `notifUnsuscribe` int(11) DEFAULT NULL,
  `notifDeleteRide` int(11) DEFAULT NULL,
  `notifModification` int(11) DEFAULT NULL,
  `blacklisted` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `cpnvId`, `email`, `hideEmail`, `telephone`, `hideTelephone`, `notifInscription`, `notifComment`, `notifUnsuscribe`, `notifDeleteRide`, `notifModification`, `blacklisted`, `admin`) VALUES
(1, 'Axel', 'sdff', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Joel', 'sdsf@cpnv.ch', 0, '0948777733', 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passenger_fk` int(11) NOT NULL,
  `targetuser_fk` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_votes_registrations1_idx` (`passenger_fk`),
  KEY `fk_votes_users1_idx` (`targetuser_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_rides1` FOREIGN KEY (`ride_fk`) REFERENCES `rides` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comments_users1` FOREIGN KEY (`author_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_users_has_rides_rides1` FOREIGN KEY (`ride_fk`) REFERENCES `rides` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_has_rides_users1` FOREIGN KEY (`user_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ridebadges`
--
ALTER TABLE `ridebadges`
  ADD CONSTRAINT `fk_rides_has_badges_badges1` FOREIGN KEY (`badge_fk`) REFERENCES `badges` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rides_has_badges_rides1` FOREIGN KEY (`ride_fk`) REFERENCES `rides` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `fk_rides_rides1` FOREIGN KEY (`bindedride`) REFERENCES `rides` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rides_towns1` FOREIGN KEY (`departuretown_fk`) REFERENCES `towns` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rides_towns2` FOREIGN KEY (`arrivaltown_fk`) REFERENCES `towns` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rides_users1` FOREIGN KEY (`driver_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `fk_votes_registrations1` FOREIGN KEY (`passenger_fk`) REFERENCES `registrations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_votes_users1` FOREIGN KEY (`targetuser_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
