-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 29 Avril 2016 à 22:18
-- Version du serveur :  10.1.10-MariaDB
-- Version de PHP :  7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `technote`
--

-- --------------------------------------------------------

--
-- Structure de la table `action`
--

CREATE TABLE `action` (
  `nom` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `codeAct` char(2) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `action`
--

INSERT INTO `action` (`nom`, `codeAct`) VALUES
('mettre un commentaire', 'C'),
('modifier/supprimer un post', 'E1'),
('modifier/supprimer son propre post(Edit)', 'E2'),
('lecture technotes/commentaires/question/reponse', 'L'),
('envoyer un message/ou acceder à la messagerie', 'M'),
('consulter le profil d''un membre', 'P'),
('acceder aux parametres', 'Pr'),
('poser/repondre question', 'Q'),
('supression compte', 'S'),
('depot technote', 'T');

-- --------------------------------------------------------

--
-- Structure de la table `caracterise`
--

CREATE TABLE `caracterise` (
  `idmc` int(5) NOT NULL,
  `id_technote` int(5) DEFAULT NULL,
  `id_question` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `caracterise`
--

INSERT INTO `caracterise` (`idmc`, `id_technote`, `id_question`) VALUES
(1, 18, NULL),
(1, 19, NULL),
(1, 20, NULL),
(1, 21, NULL),
(1, 23, NULL),
(1, 25, NULL),
(2, 25, NULL),
(3, 25, NULL),
(4, 25, NULL),
(5, 25, NULL),
(6, 27, NULL),
(7, 27, NULL),
(11, 29, NULL),
(12, 29, NULL),
(13, 30, NULL),
(14, 30, NULL),
(15, 30, NULL),
(16, 30, NULL),
(24, 6, NULL),
(22, 6, NULL),
(25, 6, NULL),
(24, 32, NULL),
(3, 32, NULL),
(25, 32, NULL),
(22, 5, NULL),
(29, 5, NULL),
(30, 5, NULL),
(31, 5, NULL),
(32, 5, NULL),
(33, 5, NULL),
(34, 5, NULL),
(19, 5, NULL),
(35, 5, NULL),
(36, 8, NULL),
(25, 0, 6),
(38, 0, 6),
(39, 0, 6),
(40, 0, 6),
(41, 0, 6),
(22, 0, 6),
(25, 0, 10),
(46, 0, 10),
(47, 35, NULL),
(25, 35, NULL),
(48, 36, NULL),
(49, 36, NULL),
(50, 0, 11),
(51, 0, 11),
(52, 0, 11),
(53, 0, 11),
(54, 0, 11),
(22, 0, 12),
(57, 0, 12),
(58, 37, NULL),
(59, 37, NULL),
(60, 37, NULL),
(61, 37, NULL),
(62, 37, NULL),
(63, 0, 13),
(64, 0, 13),
(65, 0, 13),
(22, 0, 18),
(59, 0, 18),
(22, 0, 19),
(70, 0, 19),
(71, 9, NULL),
(22, NULL, 20),
(14, NULL, 20),
(56, NULL, 20),
(72, NULL, 20);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `IDC` int(11) NOT NULL,
  `id_reponse` int(11) DEFAULT NULL,
  `id_technote` int(11) DEFAULT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `mail_membre` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `commentaire`
--

INSERT INTO `commentaire` (`IDC`, `id_reponse`, `id_technote`, `message`, `mail_membre`, `date`) VALUES
(9, NULL, 9, 'on est sur un site d''informatique!', 'couteau07@yahoo.fr', '2016-03-11 00:00:00'),
(10, NULL, 9, ';) :) :/', 'couteau07@yahoo.fr', '2016-03-11 00:00:00'),
(11, NULL, 9, ':p', 'couteau07@yahoo.fr', '2016-03-11 00:00:00'),
(12, NULL, 9, 'arrÃ©te de te moquer de moi mickael!!! :/', 'lola@gmail.com', '2016-03-22 00:00:00'),
(15, NULL, 9, 'lkk', 'couteau07@yahoo.fr', '2016-03-30 00:00:00'),
(17, 1, NULL, 'blabla', 'lola@gmail.com', '2016-04-05 00:00:00'),
(19, NULL, 32, '[code]blablbala[/code]', 'couteau07@yahoo.fr', '2016-04-05 00:00:00'),
(20, NULL, 32, '[code]blablbal[/code]                         [code]boubou[/code]               ', 'couteau07@yahoo.fr', '2016-04-05 00:00:00'),
(21, 3, NULL, 'bonjour\r\n', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(22, 3, NULL, 'salut', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(23, 3, NULL, 'la', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(24, 16, NULL, 'bllla', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(26, 18, NULL, 'ohhhhhh', 'couteau07@yahoo.fr', '2016-04-10 00:00:00'),
(27, 18, NULL, 'coment va', 'couteau07@yahoo.fr', '2016-04-10 00:00:00'),
(30, 16, NULL, 'quÃ© passa', 'lola@gmail.com', '2016-04-17 22:34:14'),
(36, 19, NULL, 'Ca risque rien?', 'ivan@gmail.com', '2016-04-18 02:18:00'),
(37, 19, NULL, 'Mais non t''inquiÃ¨te pas!', 'couteau07@yahoo.fr', '2016-04-18 02:18:43'),
(39, 20, NULL, 'ahlallaa', 'couteau07@yahoo.fr', '2016-04-23 18:58:30');

-- --------------------------------------------------------

--
-- Structure de la table `droits`
--

CREATE TABLE `droits` (
  `idrole` int(11) NOT NULL,
  `codeAct` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `droits`
--

INSERT INTO `droits` (`idrole`, `codeAct`) VALUES
(1, 'L'),
(2, 'L'),
(3, 'L'),
(2, 'D'),
(3, 'D'),
(2, 'C'),
(3, 'C'),
(2, 'Q'),
(3, 'S'),
(3, 'Q'),
(3, 'E1'),
(2, 'E2'),
(2, 'M'),
(3, 'M'),
(1, 'P'),
(2, 'P'),
(3, 'P'),
(2, 'Pr'),
(3, 'Pr');

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `mail` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pseudo` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `mot_de_passe` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date_inscription` date NOT NULL,
  `idr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`mail`, `pseudo`, `mot_de_passe`, `date_inscription`, `idr`) VALUES
('couteau07@outlook.com', 'micka', 'chobits', '2016-03-01', 2),
('couteau07@yahoo.fr', 'mickael0794', 'af466b3596e6544d89b9b30c6a573956f869586e', '2016-03-01', 3),
('elodie@gmail.com', 'elodie', '185c4399fdc91d4b9ae69c588ee175674af9ba47', '2016-04-23', 2),
('faustine@gmail.com', 'faustine', 'faustine', '2016-04-18', 2),
('ivan@gmail.com', 'ivanbrunet', 'ivanbrunet', '2016-04-18', 2),
('lola@gmail.com', 'lolabiga', 'lolabiga', '2016-03-11', 2),
('membre_supprime', 'membre supprim?', 'membresupprime', '2016-04-09', 2),
('miriam@gmail.com', 'miriam', 'miriam', '2016-04-17', 2),
('remi@gmail.com', 'remi89', 'd27fafa49545295be37aac7ecff5c8292184a127', '2016-04-23', 2),
('remi@yahoo.fr', 'remi90', '4a95dd65668e4a915fca99ab9ca4633a5ddff794', '2016-04-23', 2),
('troll@yahoo.fr', 'trolito', '183225fd899921e76f737e383902f4d7414d6c5b', '2016-04-23', 2);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id_message` int(11) NOT NULL,
  `mail_exp` varchar(30) COLLATE utf8_bin NOT NULL,
  `mail_dest` varchar(30) COLLATE utf8_bin NOT NULL,
  `contenu` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id_message`, `mail_exp`, `mail_dest`, `contenu`, `date`) VALUES
(1, 'couteau07@yahoo.fr', 'lola@gmail.com', 'salut ca va?', '2016-04-16 12:20:00'),
(2, 'lola@gmail.com', 'couteau07@yahoo.fr', 'oui et toi?', '2016-04-16 14:00:00'),
(3, 'couteau07@yahoo.fr', 'lola@gmail.com', 'cool!!', '2016-04-16 18:00:00'),
(4, 'couteau07@yahoo.fr', 'lola@gmail.com', 'tu viens ce soir?', '2016-04-17 15:03:25'),
(5, 'couteau07@yahoo.fr', 'lola@gmail.com', 'aie', '2016-04-17 15:41:56'),
(6, 'couteau07@yahoo.fr', 'lola@gmail.com', 'RÃ©ponds!!', '2016-04-17 15:42:56'),
(7, 'couteau07@yahoo.fr', 'lola@gmail.com', 'bonjour', '2016-04-17 22:24:14'),
(8, 'couteau07@yahoo.fr', 'lola@gmail.com', 'bonjour', '2016-04-17 22:24:22'),
(9, 'couteau07@yahoo.fr', 'lola@gmail.com', 'oh tu rÃ©ponds pas', '2016-04-17 22:26:21'),
(10, 'couteau07@yahoo.fr', 'lola@gmail.com', 'allez', '2016-04-17 22:26:28'),
(11, 'lola@gmail.com', 'couteau07@yahoo.fr', 'oh calme toi!!', '2016-04-17 22:27:26'),
(12, 'couteau07@yahoo.fr', 'lola@gmail.com', 'tais toi', '2016-04-18 01:35:16'),
(13, 'faustine@gmail.com', 'couteau07@outlook.com', 'salut ca va?', '2016-04-18 02:10:05'),
(14, 'faustine@gmail.com', 'ivan@gmail.com', 'bonjour, comment allez vous?', '2016-04-18 02:10:28'),
(15, 'couteau07@yahoo.fr', 'lola@gmail.com', 'nique ta mÃ©re', '2016-04-18 21:10:03'),
(16, 'couteau07@yahoo.fr', 'ivan@gmail.com', 'salut ivan', '2016-04-21 11:10:41'),
(17, 'troll@yahoo.fr', 'couteau07@outlook.com', 'salut toi', '2016-04-23 18:50:11'),
(18, 'couteau07@yahoo.fr', 'ivan@gmail.com', 'comment vas tu?\n', '2016-04-24 13:15:53');

-- --------------------------------------------------------

--
-- Structure de la table `mot_clef`
--

CREATE TABLE `mot_clef` (
  `idmc` int(11) NOT NULL,
  `mot` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `mot_clef`
--

INSERT INTO `mot_clef` (`idmc`, `mot`) VALUES
(1, 'declarartion'),
(2, 'haine'),
(3, 'test'),
(4, 'mot'),
(5, 'clef'),
(6, 'c++'),
(7, 'programmation'),
(8, 'apprnedre'),
(9, 'apprendre'),
(10, 'bla'),
(11, 'bim'),
(12, 'hello'),
(13, 'prog'),
(14, 'programmer'),
(15, 'avec'),
(16, 'eclipse'),
(17, '\r\n'),
(18, 'lola'),
(19, 'la'),
(20, 'pluq'),
(21, 'bonne'),
(22, ''),
(23, 'yes'),
(24, 'technote'),
(25, 'maj'),
(26, 'javascript'),
(29, 'connecter'),
(30, 'son'),
(31, 'pc'),
(32, 'au'),
(33, 'rÃ¨seau'),
(34, 'de'),
(35, 'fac'),
(36, 'regex'),
(37, 'supprime'),
(38, 'question'),
(39, 'savoir'),
(40, 'mots'),
(41, 'clefs'),
(46, 'hahah'),
(47, 'css'),
(48, 'bio-informatique'),
(49, 'biologie'),
(50, 'gros'),
(51, 'dormeur'),
(52, 'plus'),
(53, 'possible'),
(54, 'rÃ©veil'),
(55, 'Ã©diteur'),
(56, 'en'),
(57, 'bonjour'),
(58, 'humour'),
(59, 'salut'),
(60, 'tout'),
(61, 'le'),
(62, 'monde'),
(63, 'vulgaritÃ©'),
(64, 'interdite'),
(65, 'yoooo'),
(66, 'ddd'),
(67, 'dddddd'),
(68, 'amamam'),
(69, 'dlkdlkdlklk'),
(70, 'blabla'),
(71, 'prÃ©sentation'),
(72, 'php');

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE `question` (
  `IDQ` int(11) NOT NULL,
  `contenu` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `mail_membre` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `date_creation` datetime NOT NULL,
  `statut` enum('resolue','non resolue') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`IDQ`, `contenu`, `mail_membre`, `titre`, `date_creation`, `statut`) VALUES
(1, 'Bonjour commment t?l?charge t-on une musique?', 'couteau07@yahoo.fr', 'telechargement illegal', '2016-02-22 00:00:00', 'resolue'),
(2, ',shnsjhndxml', 'couteau07@yahoo.fr', 'kqdscujkjdx', '2016-02-27 15:51:06', 'non resolue'),
(3, 'salut est ce qu''on peut faire des bébés en sucant un mec?\r\nParce que j''ai sucé mon mec et il avait pas mis de capotes!\r\nMerci de répondre vite!', 'membre_supprime', 'salut', '2016-03-04 12:42:36', 'resolue'),
(5, 'contenu', 'couteau07@yahoo.fr', 'titre', '2016-03-31 00:12:30', 'non resolue'),
(6, 'je viens de rentrer des mots clefs', 'couteau07@yahoo.fr', 'mots clefs ', '2016-04-10 19:30:11', 'resolue'),
(10, 'dkdj', 'couteau07@yahoo.fr', 'hahah', '2016-04-10 21:48:59', 'non resolue'),
(11, 'Bonjour,\r\nConnaissez vous un moyen de se rÃ©veiller instantanÃ©ment quand on le dÃ©sire?\r\n', 'ivan@gmail.com', 'rÃ©veil', '2016-04-18 02:13:18', 'resolue'),
(12, 'tets mots clef', 'couteau07@yahoo.fr', 'bonjour', '2016-04-23 15:18:54', 'resolue'),
(13, 'salut je suis vulgaire !!! peut on me supprimer?', 'troll@yahoo.fr', 'yoooo', '2016-04-23 18:49:48', 'resolue'),
(18, 'sksjksj', 'couteau07@yahoo.fr', 'salut', '2016-04-23 19:37:40', ''),
(19, 'blablablaba...', 'couteau07@yahoo.fr', 'blabla', '2016-04-23 19:43:05', 'non resolue'),
(20, 'Quels programmes faut_il installer pour programmer en php?', 'couteau07@yahoo.fr', 'programmer en php', '2016-04-24 20:19:56', 'non resolue');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE `reponse` (
  `IDR` int(11) NOT NULL,
  `id_question` int(11) NOT NULL,
  `message` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `mail_membre` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `reponse`
--

INSERT INTO `reponse` (`IDR`, `id_question`, `message`, `mail_membre`, `date`) VALUES
(1, 3, 'lol', 'couteau07@yahoo.fr', '2016-03-08 00:00:00'),
(2, 2, 'blabalbalbala', 'couteau07@yahoo.fr', '2016-03-08 00:00:00'),
(3, 2, 'ldkldkdldk', 'couteau07@yahoo.fr', '2016-03-08 00:00:00'),
(4, 2, 'slkslkslks', 'couteau07@yahoo.fr', '2016-03-08 00:00:00'),
(13, 3, '[code]<?php\r\nheader(code.php);\r\n?>[/code]', 'couteau07@yahoo.fr', '2016-03-11 00:00:00'),
(14, 1, 'blabla', 'lola@gmail.com', '2016-04-05 00:00:00'),
(15, 14, 'kjkjkj', 'lola@gmail.com', '2016-04-05 00:00:00'),
(16, 5, 'haha', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(17, 5, 'haha', 'couteau07@yahoo.fr', '2016-04-08 00:00:00'),
(18, 8, 'blablab', 'couteau07@yahoo.fr', '2016-04-10 00:00:00'),
(19, 11, 'Tu devrais essayer les rÃ©veils Ã  dÃ©charges Ã©lÃ©ctrique!! oO', 'couteau07@yahoo.fr', '2016-04-18 02:17:06'),
(20, 15, 'mmmmmmmmmmmm', 'couteau07@yahoo.fr', '2016-04-23 18:58:20');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `idrole` int(11) NOT NULL,
  `nom` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`idrole`, `nom`) VALUES
(1, 'visiteur'),
(2, 'membre'),
(3, 'administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `technote`
--

CREATE TABLE `technote` (
  `ID` int(11) NOT NULL,
  `mail_` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `titre` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `contenu` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `technote`
--

INSERT INTO `technote` (`ID`, `mail_`, `titre`, `contenu`, `date_creation`) VALUES
(5, 'couteau07@yahoo.fr', 'connecter son pc au rÃ¨seau de la fac', 'blablablablablab...\r\n\r\n\r\nBLAbblba\r\n\r\nAurevoir et merci.', '2016-02-20 00:00:00'),
(8, 'membre_supprime', 'regex', 'Voici comment on dÃ©clare une variable:\r\n[code]int n; \r\nn=5;\r\n[/code]\r\n', '2016-03-11 10:47:48'),
(9, 'lola@gmail.com', 'prÃ©sentation', 'bonjour je m''appelle lola bigaÂ§\r\nJe vais vous montrer comment on fait une tarte au comtÃ©! \r\n:)', '2016-03-11 11:37:48'),
(32, 'couteau07@yahoo.fr', 'MAJ', 'Introsuire un systÃ©me de page\r\n\r\ninlcure footer.php\r\n\r\ngÃ©rer si l''utilisateur se trompe d''adresse quand il veut Ã©crire un message!\r\n\r\nfinaliser les droits!(envoyer message en Ã©tant dÃ©connÃ©cter n''est pas normal)', '2016-03-24 11:34:58'),
(35, 'couteau07@yahoo.fr', 'css maj', 'parametres \r\nprofil\r\n\r\n', '2016-04-17 15:51:25'),
(36, 'faustine@gmail.com', 'bio-informatique', 'bonjour, je vais vous parler de la bioi''informatique...\r\n....\r\n....\r\n/.../\r\n...\r\n', '2016-04-18 02:11:25'),
(37, 'remi@yahoo.fr', 'salut tout le monde', 'bonjour je suis nouveau sur ce site et je vais vous racontez des blagues!', '2016-04-23 18:46:10');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`codeAct`);

--
-- Index pour la table `caracterise`
--
ALTER TABLE `caracterise`
  ADD KEY `idmc` (`idmc`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`IDC`);

--
-- Index pour la table `droits`
--
ALTER TABLE `droits`
  ADD KEY `idrole` (`idrole`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`mail`),
  ADD KEY `idr` (`idr`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_message`);

--
-- Index pour la table `mot_clef`
--
ALTER TABLE `mot_clef`
  ADD PRIMARY KEY (`idmc`);

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`IDQ`);

--
-- Index pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD PRIMARY KEY (`IDR`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`idrole`);

--
-- Index pour la table `technote`
--
ALTER TABLE `technote`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `IDC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `mot_clef`
--
ALTER TABLE `mot_clef`
  MODIFY `idmc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `IDQ` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `reponse`
--
ALTER TABLE `reponse`
  MODIFY `IDR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `technote`
--
ALTER TABLE `technote`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `caracterise`
--
ALTER TABLE `caracterise`
  ADD CONSTRAINT `caracterise_ibfk_1` FOREIGN KEY (`idmc`) REFERENCES `mot_clef` (`idmc`);

--
-- Contraintes pour la table `droits`
--
ALTER TABLE `droits`
  ADD CONSTRAINT `droits_ibfk_1` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `membre_ibfk_1` FOREIGN KEY (`idr`) REFERENCES `role` (`idrole`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
