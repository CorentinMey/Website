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
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID_User`, `date_naissance`, `prenom`, `nom`, `genre`, `mail`, `antecedents`, `is_bannis`, `is_admin`, `mdp`) VALUES
(1, '1990-05-12', 'Alice', 'Dupont', 'F', 'alice.dupont@mail.com', 'Hypertension', 0, 1, '$2y$10$VjMpyS9Kbgj6ysnV2NSiQeUOUlXBvKfM0J.ylFq7xwpqP0oZ5w5jq'),
(2, '1985-09-20', 'Bob', 'Martin', 'M', 'bob.martin@mail.com', 'Diabète type 2', 0, 0, '1234'),
(3, '2000-11-15', 'Charlie', 'Lemoine', 'M', 'charlie.lemoine@mail.com', 'Asthme', 0, 0, '$2y$10$kt5RzkgZl7ykvZ0MCgop3O92HIfG61qLfR7IIMZlrYmjQEOO19Nei'),
(4, '1975-03-30', 'Diane', 'Roux', 'F', 'diane.roux@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$PV2OZGV7y6tBmlA7mHDk7gK8G2Mv9yPbItZYzLEQxMy95wmT2yVtm'),
(5, '1998-01-22', 'Eva', 'Garnier', 'F', 'eva.garnier@mail.com', 'Aucun', 0, 0, '$2y$10$Alje8E9Z9w.LFJlwB2SIdyODuB1ygt8xKq8Pv5FTT7Phhx0QZMnzi'),
(6, '1965-07-07', 'François', 'Durand', 'M', 'francois.durand@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$YP6YfTkzZtLwYcv1bHgq7S7f6ylnmfKhka9EFbE2bg2Khld5cbB92'),
(7, '1992-02-18', 'Gabrielle', 'Morel', 'F', 'gabrielle.morel@mail.com', 'Aucun', 0, 0, '$2y$10$34HgqxgsO0VgT2DqT6kpyQCOkXtAl.A9FhKLwPYuZ7F6zKHdF4FjC'),
(8, '1980-12-01', 'Hugo', 'Bernard', 'M', 'hugo.bernard@mail.com', 'Hypertension', 0, 0, '$2y$10$HTaO8ps1xqM9m2YOx76OT8Dce9WpyyKBV6hr2tQlBzHOKeqxFzH5e'),
(9, '1995-06-30', 'Isabelle', 'Fontaine', 'F', 'isabelle.fontaine@mail.com', 'Aucun', 0, 0, '$2y$10$8kNKtiVQay9.jmjXeU3CpwOxhxVVSpG5VA.k8UNNmY1.mHkz6IOnY'),
(10, '1972-04-25', 'Jacques', 'Perrin', 'M', 'jacques.perrin@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$FLMQrn.EG2XsZnT3OwLO3ehv4KxKPldzjw7IrM7k8ifmZftM12X8C'),
(11, '1987-10-05', 'Katherine', 'Dupuis', 'F', 'katherine.dupuis@mail.com', 'Aucun', 0, 0, '$2y$10$RrC1sXhZEqCucN6lCwi1FmRrs14Y4U8M7o9Hqbh8XnDngyStcqzIS'),
(12, '1993-12-22', 'Louis', 'Chavez', 'M', 'louis.chavez@mail.com', 'Asthme', 0, 0, '$2y$10$OgYH.Goy1D2nkLa7ubcuAOmTo7.HZQ1zm9c.jvOl8FuRP.gUM5tEy'),
(13, '1997-01-10', 'Maya', 'Girod', 'F', 'maya.girod@mail.com', 'Aucun', 0, 0, '$2y$10$kGbqdcGF.CAcXOfSHWBORq4xz7cL0YlhfRmQJlNwI9wLXHfM0d5Vu'),
(14, '1996-11-30', 'Nicolas', 'Lemoine', 'M', 'nicolas.lemoine@mail.com', 'Diabète type 1', 0, 0, '$2y$10$pdU95KYszrsykkV9Xh8z.y2sTpKnw7qSByvg.h6t67rPbK8rV1V2O'),
(15, '1982-04-14', 'Olivier', 'Boucher', 'M', 'olivier.boucher@mail.com', 'Aucun', 0, 0, '$2y$10$Kb6wOxg9zfrVjIxmGVRJTOIBU0MzyVddBwI6GbPbN89BGXyQ9eLxe'),
(16, '1999-03-25', 'Pauline', 'Simard', 'F', 'pauline.simard@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$L1rjIZtn5t6uE4gZzLE9aiK0dZltFjwMNNbgyEOZj8ojdbIzfRmNm'),
(17, '1989-01-17', 'Quentin', 'Mercier', 'M', 'quentin.mercier@mail.com', 'Hypertension', 0, 0, '$2y$10$XrO8v5Fq8sAxU8h8u2G60MO1FJ63STz5.tgFwxPka8OqCvRh0muTS'),
(18, '1994-07-08', 'Rachel', 'Vidal', 'F', 'rachel.vidal@mail.com', 'Aucun', 0, 0, '$2y$10$6AeEKrbPfCbJYihgS6i9a0n63H3y3v4ErXyB8DANy4kaDTnQZyVjC'),
(19, '1981-12-30', 'Sébastien', 'Robert', 'M', 'sebastien.robert@mail.com', 'Antécédent cardiaque', 0, 0, '$2y$10$ULy8o9dUGVYySKrgQ0EdgPbYlJ60R6TmIKVrve9YWyWfxFje19km6'),
(20, '1991-02-03', 'Thomas', 'Leclerc', 'M', 'thomas.leclerc@mail.com', 'Aucun', 0, 0, '$2y$10$BXQJr9YhFiPy0hRZbk6T/oGgVxTSJfvc0I7esZXlsU8gCrHgoZG7u'),
(21, '2001-04-18', 'Ursula', 'Renard', 'F', 'ursula.renard@mail.com', 'Diabète type 2', 0, 0, '$2y$10$Fw5rBv5ObDPqjsXw8Hq/9F0p1PfuJXBzmWwsoR6R9Xy5lLV0F74dy'),
(22, '1994-10-25', 'Victor', 'Chastel', 'M', 'victor.chastel@mail.com', 'Aucun', 0, 0, '$2y$10$lg0gBz67QAGZlToDlTh7a.pS5sSVNugjt4VOBGzPrBWrn0KMjbGxe'),
(23, '1984-08-12', 'Wendy', 'Charrier', 'F', 'wendy.charrier@mail.com', 'Hypertension', 0, 0, '$2y$10$ejBhmxFhTQUtM9S2B1A0dWIuAfzm06ev5P8k1Yh7u8Rkw5vgw6BCi'),
(24, '1992-02-06', 'Xavier', 'Fournier', 'M', 'xavier.fournier@mail.com', 'Aucun', 0, 0, '$2y$10$wfgFDCXg1Vmnx61u6K6FPG5jTdpB96F6vR9l0qDJUQdVAy5eHTBdu'),
(25, '1986-11-10', 'Yasmine', 'Berrier', 'F', 'yasmine.berrier@mail.com', 'Asthme', 0, 0, '$2y$10$Z3gKvJ5gdyIObFnPflL5Re55UEmeCGYYaW1J2Q0rfm7mi0tJ9q7gK'),
(26, '1993-03-14', 'Zacharie', 'Pires', 'M', 'zacharie.pires@mail.com', 'Aucun', 0, 0, '$2y$10$NwE5WzEBlC.kvRpvm0iHtVtbKhV2i9JxKvTe7X.lUzG2l5F0gq6Re'),
(27, '1988-04-04', 'Alexis', 'Martinez', 'M', 'alexis.martinez@mail.com', 'Aucun', 0, 0, '$2y$10$ErbsQsyekttK.k3mKHwxtdFz2d1uJfiTz3V7ME0kG2cz2xfJ9BX7u'),
(28, '1983-09-09', 'Barbara', 'Vermot', 'F', 'barbara.vermot@mail.com', 'Diabète type 2', 0, 0, '$2y$10$5PqjbJ6OYh8lZ84G6USbsJ75kd9SZmGe4H2bA1eLwfiSQZNS7YYgO'),
(29, '1985-06-17', 'Catherine', 'Rossi', 'F', 'catherine.rossi@mail.com', 'Allergies alimentaires', 0, 0, '$2y$10$O.tgyE9mvb5OB0t6E5Szd6mm2ysjmy9C.oRZG4NLC0pqdC1FX5Qka'),
(30, '1990-07-11', 'David', 'Lefevre', 'M', 'david.lefevre@mail.com', 'Aucun', 0, 0, '$2y$10$FyGo7H9heqvg8fjH1zFkFhrE9LDBmZPmmyUwQKbsw/oZn7b8yLaP6');

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
