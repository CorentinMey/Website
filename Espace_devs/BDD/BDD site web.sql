CREATE TABLE `UTILISATEUR` (
  `ID_User` integer PRIMARY KEY,
  `date_naissance` date,
  `prenom` varchar(255),
  `nom` varchar(255),
  `genre` varchar(255),
  `mail` varchar(255),
  `antecedents` varchar(255),
  `is_bannis` bool,
  `is_admin` bool
);

CREATE TABLE `ENTREPRISE` (
  `siret` integer PRIMARY KEY,
  `ville` varchar(255)
);

CREATE TABLE `RESULTAT` (
  `ID_essai` integer,
  `ID_patient` integer,
  `is_accepte` bool,
  `is_patient_exclus` bool,
  `phase` integer,
  `traitement` varchar(255),
  `dose` float,
  `effet_secondaire` varchar(255),
  `evolution_symptome` varchar(255),
  PRIMARY KEY (`ID_essai`, `ID_patient`)
);

CREATE TABLE `ESSAI` (
  `ID_essai` integer PRIMARY KEY,
  `ID_phase` integer,
  `ID_entreprise_ref` integer,
  `date_debut` date,
  `date_fin` date,
  `description` varchar(255),
  `molecule_test` varchar(255),
  `dosage_test` float,
  `molecule_ref` varchar(255),
  `dosage_ref` float,
  `placebo_nom` varchar(255),
  `a_debute` bool
);

CREATE TABLE `ESSAI_MEDECIN` (
  `ID_medecin` integer,
  `ID_essai` integer,
  `is_accepte` bool,
  `est_de_company` bool,
  PRIMARY KEY (`ID_medecin`, `ID_essai`)
);

CREATE TABLE `MEDECIN` (
  `numero_ordre` integer PRIMARY KEY,
  `domaine` varchar(255),
  `hopital` varchar(255)
);

CREATE TABLE `PHASE` (
  `ID_essai` integer,
  `ID_phase` integer,
  `date_debut` date,
  `date_fin_prevue` date,
  `nombre_patients` int,
  PRIMARY KEY (`ID_essai`, `ID_phase`)
);

ALTER TABLE `PHASE` ADD FOREIGN KEY (`ID_phase`) REFERENCES `ESSAI` (`ID_phase`);

ALTER TABLE `PHASE` ADD FOREIGN KEY (`ID_essai`) REFERENCES `ESSAI` (`ID_essai`);

ALTER TABLE `MEDECIN` ADD FOREIGN KEY (`numero_ordre`) REFERENCES `UTILISATEUR` (`ID_User`);

ALTER TABLE `ESSAI` ADD FOREIGN KEY (`ID_essai`) REFERENCES `RESULTAT` (`ID_essai`);

ALTER TABLE `ESSAI_MEDECIN` ADD FOREIGN KEY (`ID_essai`) REFERENCES `ESSAI` (`ID_essai`);

ALTER TABLE `ENTREPRISE` ADD FOREIGN KEY (`siret`) REFERENCES `UTILISATEUR` (`ID_User`);

ALTER TABLE `ESSAI_MEDECIN` ADD FOREIGN KEY (`ID_medecin`) REFERENCES `MEDECIN` (`numero_ordre`);

ALTER TABLE `RESULTAT` ADD FOREIGN KEY (`ID_patient`) REFERENCES `UTILISATEUR` (`ID_User`);

ALTER TABLE `ESSAI` ADD FOREIGN KEY (`ID_entreprise_ref`) REFERENCES `ENTREPRISE` (`siret`);

ALTER TABLE `PHASE` ADD FOREIGN KEY (`ID_essai`) REFERENCES `RESULTAT` (`ID_essai`);

ALTER TABLE `ESSAI_MEDECIN` ADD FOREIGN KEY (`ID_essai`) REFERENCES `RESULTAT` (`ID_essai`);
