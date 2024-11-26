-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 21 nov. 2024 à 10:31
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `siteweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `siret` bigint(11) NOT NULL,
  `ville` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`siret`, `ville`) VALUES
(12345678901234, 'Nice'),
(23456789012345, 'Paris'),
(34567890123456, 'Lyon'),
(45678901234567, 'Marseille'),
(56789012345678, 'Toulouse');

-- --------------------------------------------------------

--
-- Structure de la table `essai`
--

CREATE TABLE `essai` (
  `ID_essai` int(11) NOT NULL,
  `ID_phase` int(11) DEFAULT NULL,
  `ID_entreprise_ref` bigint(11) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `molecule_test` varchar(255) DEFAULT NULL,
  `dosage_test` float DEFAULT NULL,
  `molecule_ref` varchar(255) DEFAULT NULL,
  `dosage_ref` float DEFAULT NULL,
  `placebo_nom` varchar(255) DEFAULT NULL,
  `a_debute` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `essai`
--

INSERT INTO `essai` (`ID_essai`, `ID_phase`, `ID_entreprise_ref`, `date_debut`, `date_fin`, `description`, `molecule_test`, `dosage_test`, `molecule_ref`, `dosage_ref`, `placebo_nom`, `a_debute`) VALUES
(1, 1, 12345678901234, '2023-01-01', '2023-12-31', 'Essai sur un traitement anti-cancéreux.', 'Molécule_X', 100, 'Molécule_Y', 50, 'Placebo_A', 1),
(2, 2, 23456789012345, '2022-06-01', '2024-05-30', 'Test de traitement pour le diabète.', 'Molécule_D', 75, 'Molécule_C', 30, 'Placebo_B', 1),
(3, 3, 34567890123456, '2024-01-15', '2025-01-14', 'Étude sur les maladies respiratoires.', 'Molécule_R', 60, 'Molécule_T', 40, 'Placebo_C', 0),
(4, 4, 45678901234567, '2021-09-01', '2023-08-31', 'Recherche sur la douleur chronique.', 'Molécule_P', 90, 'Molécule_O', 45, 'Placebo_D', 1),
(5, 5, 56789012345678, '2023-03-01', '2024-02-29', 'Traitement expérimental pour la migraine.', 'Molécule_M', 50, 'Molécule_N', 25, 'Placebo_E', 0);

-- --------------------------------------------------------

--
-- Structure de la table `essai_medecin`
--

CREATE TABLE `essai_medecin` (
  `ID_medecin` int(11) NOT NULL,
  `ID_essai` int(11) NOT NULL,
  `is_accepte` tinyint(1) DEFAULT NULL,
  `est_de_company` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `essai_medecin`
--

INSERT INTO `essai_medecin` (`ID_medecin`, `ID_essai`, `is_accepte`, `est_de_company`) VALUES
(1, 1, NULL, NULL),
(1, 5, NULL, NULL),
(2, 1, NULL, NULL),
(2, 2, NULL, NULL),
(3, 2, NULL, NULL),
(3, 3, NULL, NULL),
(4, 3, NULL, NULL),
(4, 4, NULL, NULL),
(5, 4, NULL, NULL),
(5, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `numero_ordre` int(11) NOT NULL,
  `domaine` varchar(255) DEFAULT NULL,
  `hopital` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medecin`
--

INSERT INTO `medecin` (`numero_ordre`, `domaine`, `hopital`) VALUES
(1, 'Cardiologie', 'Hôpital Pasteur'),
(2, 'Endocrinologie', 'Hôpital de la Timone'),
(3, 'Pneumologie', 'Hôpital Européen'),
(4, 'Immunologie', 'Hôpital Necker'),
(5, 'Allergologie', 'Hôpital Bichat-Claude Bernard');

-- --------------------------------------------------------

--
-- Structure de la table `phase`
--

CREATE TABLE `phase` (
  `ID_essai` int(11) NOT NULL,
  `ID_phase` int(11) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin_prevue` date DEFAULT NULL,
  `nombre_patients` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `phase`
--

INSERT INTO `phase` (`ID_essai`, `ID_phase`, `date_debut`, `date_fin_prevue`, `nombre_patients`) VALUES
(1, 1, '2024-01-01', '2024-06-30', 50),
(1, 2, '2024-07-01', '2025-06-30', 100),
(2, 1, '2023-03-01', '2023-08-31', 30),
(2, 2, '2023-09-01', '2024-08-31', 80),
(3, 1, '2024-02-15', '2024-08-15', 40),
(3, 3, '2024-09-01', '2025-08-31', 120),
(4, 1, '2023-05-01', '2023-11-30', 20),
(4, 4, '2024-01-01', '2025-12-31', 150),
(5, 1, '2022-11-01', '2023-04-30', 25),
(5, 2, '2023-05-01', '2024-04-30', 90);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE `resultat` (
  `ID_essai` int(11) NOT NULL,
  `ID_patient` int(11) NOT NULL,
  `is_accepte` tinyint(1) DEFAULT NULL,
  `is_patient_exclus` tinyint(1) DEFAULT NULL,
  `phase` int(11) DEFAULT NULL,
  `traitement` varchar(255) DEFAULT NULL,
  `dose` float DEFAULT NULL,
  `effet_secondaire` varchar(255) DEFAULT NULL,
  `evolution_symptome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `resultat`
--

INSERT INTO `resultat` (`ID_essai`, `ID_patient`, `is_accepte`, `is_patient_exclus`, `phase`, `traitement`, `dose`, `effet_secondaire`, `evolution_symptome`) VALUES
(1, 1, 1, 0, 1, 'Traitement_A', 50, 'Nausées', 'Amélioration'),
(1, 2, 1, 0, 1, 'Traitement_A', 50, 'Fatigue', 'Stable'),
(1, 3, 1, 0, 1, 'Traitement_A', 50, 'Maux de tête', 'Aucune évolution'),
(1, 4, 1, 0, 1, 'Traitement_A', 50, 'Aucun', 'Amélioration significative'),
(2, 5, 1, 0, 2, 'Traitement_B', 100, 'Aucun', 'Amélioration'),
(2, 6, 1, 0, 2, 'Traitement_B', 100, 'Vertiges', 'Stable'),
(2, 7, 1, 0, 2, 'Traitement_B', 100, 'Fatigue', 'Aucune évolution'),
(2, 8, 1, 0, 2, 'Traitement_B', 100, 'Aucun', 'Dégradation'),
(3, 9, 1, 0, 3, 'Traitement_C', 75, 'Fatigue', 'Stable'),
(3, 10, 1, 0, 3, 'Traitement_C', 75, 'Aucun', 'Amélioration'),
(3, 11, 1, 0, 3, 'Traitement_C', 75, 'Aucun', 'Aucune évolution'),
(4, 12, 1, 0, 1, 'Traitement_D', 200, 'Fatigue', 'Amélioration significative'),
(4, 13, 1, 0, 1, 'Traitement_D', 200, 'Vertiges', 'Stable'),
(4, 14, 1, 0, 1, 'Traitement_D', 200, 'Aucun', 'Amélioration'),
(4, 15, 1, 0, 1, 'Traitement_D', 200, 'Nausées', 'Stable'),
(5, 16, 1, 0, 2, 'Traitement_E', 60, 'Aucun', 'Stable'),
(5, 17, 1, 0, 2, 'Traitement_E', 60, 'Vertiges', 'Dégradation'),
(5, 18, 1, 0, 2, 'Traitement_E', 60, 'Fatigue', 'Amélioration'),
(5, 19, 1, 0, 2, 'Traitement_E', 60, 'Aucun', 'Stable');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID_User` int(11) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `antecedents` varchar(255) DEFAULT NULL,
  `is_bannis` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `mdp` varchar(255) NOT NULL,
  `origine` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_User`, `date_naissance`, `prenom`, `nom`, `genre`, `mail`, `antecedents`, `is_bannis`, `is_admin`, `mdp`, `origine`) VALUES
(1, '1990-05-12', 'Alice', 'Dupont', 'F', 'alice.dupont@mail.com', 'Hypertension', 0, 1, '$2y$10$F6SMeliyyZi2bdLgOC5XJem3lMxErdJmEWkGbcn3o1ohCQJmykpEK', 'Europe'),
(2, '1985-09-20', 'Bob', 'Martin', 'M', 'bob.martin@mail.com', 'Diabète type 2', 0, 0, '1234', 'North America'),
(3, '2000-11-15', 'Charlie', 'Lemoine', 'M', 'charlie.lemoine@mail.com', 'Asthme', 0, 0, '$2y$10$ZmcThLvodtNibt8F.GaW8ut6l26aMe38bNiwX5ny595SIjkQ/.OxW', 'Europe'),
(4, '1975-03-30', 'Diane', 'Roux', 'F', 'diane.roux@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$xwVM8UzJkn.kTuSoftD2TuYjrx8Jcgi/Y86Q6TQiZ1iSNNVBe2C4e', 'South America'),
(5, '1998-01-22', 'Eva', 'Garnier', 'F', 'eva.garnier@mail.com', 'Aucun', 0, 0, '$2y$10$wDr56/zHY5DznIDYTrGuAeK1wVfx8nYMNkKWoNDgFVXIazRHyyGwC', 'Oceania'),
(6, '1965-07-07', 'François', 'Durand', 'M', 'francois.durand@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$Lr8iPHS.VvBfPWRvdHNkCe5yFu9MnFB240y4MPm8ch7ATTGiAb4Zu', 'Africa'),
(7, '1992-02-18', 'Gabrielle', 'Morel', 'F', 'gabrielle.morel@mail.com', 'Aucun', 0, 0, '$2y$10$v0pYWhRqUBYsPsOXrtaxP.0bwxfUimbU0hhu77Gd0Jjzb3Qcnho4a', 'Asia'),
(8, '1980-12-01', 'Hugo', 'Bernard', 'M', 'hugo.bernard@mail.com', 'Hypertension', 0, 0, '$2y$10$C1lJpqrx4FJ579YwA9uDP.BVySrDZc8odhCwm0FBXdC7K/8jvgRvC', 'Europe'),
(9, '1995-06-30', 'Isabelle', 'Fontaine', 'F', 'isabelle.fontaine@mail.com', 'Aucun', 0, 0, '$2y$10$5R77UGEv3UJAZD7yEiawo.jK.9FSjESdFYMf6Dnb4FTSSiPbdpbHe', 'North America'),
(10, '1972-04-25', 'Jacques', 'Perrin', 'M', 'jacques.perrin@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$Fb5WaT96AZ/ZPViRx4Y60eq/ETeUmZ4QKnZvnqSo3t.p6rkq/CR5W', 'Africa'),
(11, '1987-10-05', 'Katherine', 'Dupuis', 'F', 'katherine.dupuis@mail.com', 'Aucun', 0, 0, '$2y$10$B/1Jcp3txzQ88OJqzUV2VOTWMclKXeSDWJe59Ax2fgcL20cJJTXWC', 'Asia'),
(12, '1993-12-22', 'Louis', 'Chavez', 'M', 'louis.chavez@mail.com', 'Asthme', 0, 0, '$2y$10$Zd.wu3FUVtvMBYs8BsK0Wu1h2K5r5Rn0Q6P4WMuqD1x.rPOVVTAMi', 'Asia'),
(13, '1997-01-10', 'Maya', 'Girod', 'F', 'maya.girod@mail.com', 'Aucun', 0, 0, '$2y$10$ofL7Z93RjCimDu7T8ZvXJOwKnPCfeJ6gnyqzmkXGMqYEsVy8B3dZ2', 'Asia'),
(14, '1996-11-30', 'Nicolas', 'Lemoine', 'M', 'nicolas.lemoine@mail.com', 'Diabète type 1', 0, 0, '$2y$10$/uga/.3chXjQuoOLyIRdQupvssoUgfmbTQmJFvE7TVUyAgLgvHAam', 'Asia'),
(15, '1982-04-14', 'Olivier', 'Boucher', 'M', 'olivier.boucher@mail.com', 'Aucun', 0, 0, '$2y$10$ozXhAeVlFd5.dhbcFL/q/OOSY4yi0P.PID5BiOqmEjBDx9Wn7rjRG', 'Africa'),
(16, '1999-03-25', 'Pauline', 'Simard', 'F', 'pauline.simard@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$jGrP9aqWNRe6h6vosxbuqeHQnV1dE0y7fXU2pftv.hQ4C9sxEtqiO', 'Africa'),
(17, '1989-01-17', 'Quentin', 'Mercier', 'M', 'quentin.mercier@mail.com', 'Hypertension', 0, 0, '$2y$10$Lqlku5WlduUJXHZRtjDBQOe0CypuYu2AkNEEWMWW.gVViDH1JWZve', 'Africa'),
(18, '1994-07-08', 'Rachel', 'Vidal', 'F', 'rachel.vidal@mail.com', 'Aucun', 0, 0, '$2y$10$P.C0FfY5CQ4iZUkPYSi.QOBDRAVc5Z66TezQt0wWFEvo.dlRR7ZC6', 'Africa'),
(19, '1981-12-30', 'Sébastien', 'Robert', 'M', 'sebastien.robert@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$rpLUwgO6wBKMPVMkVxscnuPd401ooFKrY4vSfe1K6myUfZ0dzn26S', 'Africa'),
(20, '1991-02-03', 'Thomas', 'Leclerc', 'M', 'thomas.leclerc@mail.com', 'Aucun', 0, 0, '$2y$10$yhbo7ueIsygDoh6QJ7zVx.bBvaC71syosZtkn3qRKC7nG5Xsg1//2', 'Africa'),
(21, '2001-04-18', 'Ursula', 'Renard', 'F', 'ursula.renard@mail.com', 'Diabète type 2', 0, 0, '$2y$10$egTIOCOQ3QCgeE.82.AxeOt4yGXLFJXPakB0IpKyeXgSeFV55HT76', 'Oceania'),
(22, '1994-10-25', 'Victor', 'Chastel', 'M', 'victor.chastel@mail.com', 'Aucun', 0, 0, '$2y$10$JVtTxU0lgBQivFaqptfRju3IZ2rTA4HEaKIhjcOfJar3hUpzmEjLS', 'Oceania'),
(23, '1984-08-12', 'Wendy', 'Charrier', 'F', 'wendy.charrier@mail.com', 'Hypertension', 0, 0, '$2y$10$BWkmCq9WK7idJnIElyKV8Oh8gr7dDLl8Z81CvUe60rKZ192oGTc6G', 'Oceania'),
(24, '1992-02-06', 'Xavier', 'Fournier', 'M', 'xavier.fournier@mail.com', 'Aucun', 0, 0, '$2y$10$B1LJPoAgDwSTxTZlYWe.uOpymjzXwXcTNCaImnj5q8wmdKZS996o2', 'Europe'),
(25, '1986-11-10', 'Yasmine', 'Berrier', 'F', 'yasmine.berrier@mail.com', 'Asthme', 0, 0, '$2y$10$FxDXjQoGyHC03w5I8AK57eDcwPUXp30KAPN5P4LH4firXZqAlOZAW', 'Europe'),
(26, '1993-03-14', 'Zacharie', 'Pires', 'M', 'zacharie.pires@mail.com', 'Aucun', 0, 0, '$2y$10$RccfGRrWR27adJgZQgkya.3MXu4l6gGim9T1pFGpDHoxRAgNcpgme', 'Europe'),
(27, '1988-04-04', 'Alexis', 'Martinez', 'M', 'alexis.martinez@mail.com', 'Aucun', 0, 0, '$2y$10$tq/7cChmbrmqoheubLIZTOssfEHxWCYHyd/5fsLXH1ShXmW3UCB7W', 'Europe'),
(28, '1983-09-09', 'Barbara', 'Vermot', 'F', 'barbara.vermot@mail.com', 'Diabète type 2', 0, 0, '$2y$10$kkDbeuz9dwh6gecSrJoiEeZbuEJxAX1cc9k62SGHKIqoGSkFJmzFq', 'Europe'),
(29, '1985-06-17', 'Catherine', 'Rossi', 'F', 'catherine.rossi@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$zJ3He6yMYLCgJxKc8EvtwumulgcpmrMBm0I2CBMooTFpgtJMgg1nW', 'Europe'),
(30, '1990-07-11', 'David', 'Lefevre', 'M', 'david.lefevre@mail.com', 'Aucun', 0, 0, '$2y$10$1cghFxWcRLbPFnSjvvN.aOwju2X7pTrCHTdenyk1KLikdZk6s7.bK', 'Europe');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`siret`);

--
-- Index pour la table `essai`
--
ALTER TABLE `essai`
  ADD PRIMARY KEY (`ID_essai`);

--
-- Index pour la table `essai_medecin`
--
ALTER TABLE `essai_medecin`
  ADD PRIMARY KEY (`ID_medecin`,`ID_essai`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`numero_ordre`);

--
-- Index pour la table `phase`
--
ALTER TABLE `phase`
  ADD PRIMARY KEY (`ID_essai`,`ID_phase`);

--
-- Index pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD PRIMARY KEY (`ID_essai`,`ID_patient`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID_User`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `essai`
--
ALTER TABLE `essai`
  MODIFY `ID_essai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
