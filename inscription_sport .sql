-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 12 mai 2020 à 15:56
-- Version du serveur :  5.7.24
-- Version de PHP : 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `inscription_sport`
--

-- --------------------------------------------------------

--
-- Structure de la table `event_base`
--

CREATE TABLE `event_base` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start` datetime(6) NOT NULL,
  `end` datetime(6) NOT NULL,
  `registrationlimit` int(255) NOT NULL,
  `queuelimit` int(255) NOT NULL,
  `coach_id` int(255) NOT NULL,
  `gym_id` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `event_base`
--

INSERT INTO `event_base` (`id`, `name`, `description`, `start`, `end`, `registrationlimit`, `queuelimit`, `coach_id`, `gym_id`) VALUES
(11, 'Boxe', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In non iaculis justo. Cras ac libero quis est auctor sagittis id a elit. Proin ac elementum quam. Phasellus vitae egestas turpis, ac fringilla mi. Curabitur molestie odio suscipit, blandit tortor pellentesque, molestie eros. Quisque ullamcorper tempor ex vitae vehicula. Aenean eu nulla orci. Nunc tortor nibh, dignissim ut mi vel, vehicula porta nulla.', '2020-05-12 15:00:00.000000', '2020-05-12 16:00:00.000000', 10, 10, 26, 1);

-- --------------------------------------------------------

--
-- Structure de la table `gym_base`
--

CREATE TABLE `gym_base` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `gym_base`
--

INSERT INTO `gym_base` (`id`, `name`, `address`) VALUES
(1, 'L\'Orange bleue', '245 Rue Antoine de Saint-Exupéry, 69140 Rillieux-la-Pape');

-- --------------------------------------------------------

--
-- Structure de la table `participation_base`
--

CREATE TABLE `participation_base` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `event_id` int(255) NOT NULL,
  `registeredon` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `attendancecode` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `participation_base`
--

INSERT INTO `participation_base` (`id`, `user_id`, `event_id`, `registeredon`, `attendancecode`) VALUES
(432, 26, 11, '2020-05-12 15:55:54.000000', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role_base`
--

CREATE TABLE `role_base` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role_base`
--

INSERT INTO `role_base` (`id`, `name`) VALUES
(1, 'Administrateur'),
(2, 'Coach'),
(3, 'Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `society_base`
--

CREATE TABLE `society_base` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `society_base`
--

INSERT INTO `society_base` (`id`, `name`) VALUES
(1, 'MARSEILLE'),
(2, 'LYON'),
(3, 'PARIS');

-- --------------------------------------------------------

--
-- Structure de la table `user_base`
--

CREATE TABLE `user_base` (
  `id` int(11) NOT NULL,
  `forename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isdefaultpassword` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `society_id` int(255) NOT NULL,
  `role_id` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_base`
--

INSERT INTO `user_base` (`id`, `forename`, `lastname`, `username`, `password`, `isdefaultpassword`, `email`, `society_id`, `role_id`) VALUES
(10, 'Yann', 'BONAUDO', 'y.bonaudo', '111', 0, 'y.bonaudo@mail.fr', 1, 3),
(26, 'Rabire', 'HAKIM', 'r.hakim', '123123', 0, 'r.hakim@mail.fr', 3, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `event_base`
--
ALTER TABLE `event_base`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gym_base`
--
ALTER TABLE `gym_base`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `participation_base`
--
ALTER TABLE `participation_base`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role_base`
--
ALTER TABLE `role_base`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `society_base`
--
ALTER TABLE `society_base`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_base`
--
ALTER TABLE `user_base`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `event_base`
--
ALTER TABLE `event_base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `gym_base`
--
ALTER TABLE `gym_base`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `participation_base`
--
ALTER TABLE `participation_base`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT pour la table `role_base`
--
ALTER TABLE `role_base`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `society_base`
--
ALTER TABLE `society_base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `user_base`
--
ALTER TABLE `user_base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
