-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 28 juil. 2020 à 17:49
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) CHARACTER SET utf8 NOT NULL,
  `prenom` varchar(20) CHARACTER SET utf8 NOT NULL,
  `niveau` varchar(10) CHARACTER SET utf8 NOT NULL,
  `user` varchar(15) CHARACTER SET utf8 NOT NULL,
  `pass` varchar(15) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `nom`, `prenom`, `niveau`, `user`, `pass`) VALUES
(2, 'miri', 'mohamed', 'cp1', 'momed100', 'useruser'),
(6, 'benali', 'yasmine', 'cp2', 'yasmine', 'password'),
(7, 'dalaoui', 'amine', 'cp1', 'user11', 'pass11'),
(8, 'e', 'e', 'cp1', 'e', 'azerty123');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `niveau` varchar(10) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`niveau`) VALUES
('cp1'),
('cp2'),
('gi1');

-- --------------------------------------------------------

--
-- Structure de la table `prof`
--

CREATE TABLE `prof` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `prof`
--

INSERT INTO `prof` (`id`, `nom`, `prenom`, `user`, `pass`) VALUES
(1, 'mehouti', 'mohamed', 'lel', 'motdepas'),
(2, 'wahid', 'hamza', 'hamza', 'hamza'),
(8, 'k', 'k', 'younes', 'younes'),
(9, 'a', 'a', 'a', 'azerty123'),
(10, 'GHADBAN ', 'AMINA', 'amina11', 'useruser');

-- --------------------------------------------------------

--
-- Structure de la table `qcm`
--

CREATE TABLE `qcm` (
  `id_qcm` int(10) NOT NULL,
  `niveau` varchar(10) CHARACTER SET utf8 NOT NULL,
  `nb_question` int(5) NOT NULL,
  `ddebut` datetime NOT NULL,
  `dfin` datetime NOT NULL,
  `titre` varchar(25) CHARACTER SET utf8 NOT NULL,
  `description` varchar(50) CHARACTER SET utf8 NOT NULL,
  `pdf` mediumblob NOT NULL,
  `c1` float NOT NULL,
  `c2` float NOT NULL,
  `id_prof` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `qcm`
--

INSERT INTO `qcm` (`id_qcm`, `niveau`, `nb_question`, `ddebut`, `dfin`, `titre`, `description`, `pdf`, `c1`, `c2`, `id_prof`) VALUES
INSERT INTO `qcm` (`id_qcm`, `niveau`, `nb_question`, `ddebut`, `dfin`, `titre`, `description`, `pdf`, `c1`, `c2`, `id_prof`) VALUES

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `qcm_etud`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `qcm_etud` (
`id_qcm` int(11)
,`id_etudiant` int(11)
,`notes` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE `reponse` (
  `id_qcm` int(11) NOT NULL,
  `No_question` int(11) NOT NULL,
  `nb_choix` int(11) NOT NULL,
  `A` tinyint(1) NOT NULL,
  `B` tinyint(1) NOT NULL,
  `C` tinyint(1) NOT NULL,
  `D` tinyint(1) NOT NULL,
  `E` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reponse`
--

INSERT INTO `reponse` (`id_qcm`, `No_question`, `nb_choix`, `A`, `B`, `C`, `D`, `E`) VALUES
(128, 0, 2, 1, 0, 0, 0, 0),
(128, 1, 3, 0, 0, 0, 0, 0),
(128, 2, 5, 0, 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `reponse_etudiant`
--

CREATE TABLE `reponse_etudiant` (
  `id_qcm` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `No_question` int(11) NOT NULL,
  `A` tinyint(1) NOT NULL,
  `B` tinyint(1) NOT NULL,
  `C` tinyint(1) NOT NULL,
  `D` tinyint(1) NOT NULL,
  `E` tinyint(1) NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reponse_etudiant`
--

INSERT INTO `reponse_etudiant` (`id_qcm`, `id_etudiant`, `No_question`, `A`, `B`, `C`, `D`, `E`, `note`) VALUES
(128, 2, 0, 1, 0, 0, 0, 0, 2),
(128, 2, 1, 0, 1, 0, 0, 0, -1),
(128, 2, 2, 0, 0, 1, 0, 1, 4),
(128, 7, 0, 0, 0, 0, 0, 0, 0),
(128, 7, 1, 1, 1, 0, 0, 0, -2),
(128, 7, 2, 0, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la vue `qcm_etud`
--
DROP TABLE IF EXISTS `qcm_etud`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `qcm_etud`  AS  select `reponse_etudiant`.`id_qcm` AS `id_qcm`,`reponse_etudiant`.`id_etudiant` AS `id_etudiant`,sum(`reponse_etudiant`.`note`) AS `notes` from `reponse_etudiant` group by `reponse_etudiant`.`id_qcm`,`reponse_etudiant`.`id_etudiant` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `niveau` (`niveau`);

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD PRIMARY KEY (`niveau`);

--
-- Index pour la table `prof`
--
ALTER TABLE `prof`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `qcm`
--
ALTER TABLE `qcm`
  ADD PRIMARY KEY (`id_qcm`),
  ADD KEY `id_prof` (`id_prof`),
  ADD KEY `niveau` (`niveau`);

--
-- Index pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD KEY `id_qcm` (`id_qcm`);

--
-- Index pour la table `reponse_etudiant`
--
ALTER TABLE `reponse_etudiant`
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_qcm` (`id_qcm`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `prof`
--
ALTER TABLE `prof`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `qcm`
--
ALTER TABLE `qcm`
  MODIFY `id_qcm` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `niv-t` FOREIGN KEY (`niveau`) REFERENCES `niveau` (`niveau`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `qcm`
--
ALTER TABLE `qcm`
  ADD CONSTRAINT `niv` FOREIGN KEY (`niveau`) REFERENCES `niveau` (`niveau`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prof_qcm` FOREIGN KEY (`id_prof`) REFERENCES `prof` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `qcm_res` FOREIGN KEY (`id_qcm`) REFERENCES `qcm` (`id_qcm`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reponse_etudiant`
--
ALTER TABLE `reponse_etudiant`
  ADD CONSTRAINT `rep-et_qcm` FOREIGN KEY (`id_qcm`) REFERENCES `qcm` (`id_qcm`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reponse-etudiant` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;