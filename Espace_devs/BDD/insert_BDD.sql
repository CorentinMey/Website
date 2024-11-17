-- Insertion des utilisateurs
INSERT INTO `UTILISATEUR` (`ID_User`, `date_naissance`, `prenom`, `nom`, `genre`, `mail`, `antecedents`, `is_bannis`, `is_admin`) VALUES
(1, '1980-01-01', 'Alice', 'Dupont', 'F', 'alice.dupont@example.com', 'None', 0, 1),
(2, '1990-02-02', 'Bob', 'Martin', 'M', 'bob.martin@example.com', 'Asthma', 0, 0),
(3, '1985-03-03', 'Charlie', 'Durand', 'M', 'charlie.durand@example.com', 'Diabetes', 0, 0);

-- Insertion des entreprises
INSERT INTO `ENTREPRISE` (`ID_entreprise`, `siret`, `ville`) VALUES
(1, 12345678901234, 'Paris'),
(2, 98765432109876, 'Lyon');

-- Insertion des essais
INSERT INTO `ESSAI` (`ID_essai`, `ID_phase`, `ID_entreprise_ref`, `date_debut`, `date_fin`, `description`, `nombre_patient_ideal`, `molecule_test`, `dosage_test`, `molecule_ref`, `dosage_ref`, `placebo_nom`, `is_debute`) VALUES
(1, 1, 1, '2023-01-01', '2023-12-31', 'Essai clinique sur la molécule X', 100, 'Molécule X', 50.0, 'Molécule Y', 50.0, 'Placebo A', 1),
(2, 2, 2, '2023-02-01', '2023-11-30', 'Essai clinique sur la molécule Z', 150, 'Molécule Z', 75.0, 'Molécule W', 75.0, 'Placebo B', 0);

-- Insertion des résultats
INSERT INTO `RESULTAT` (`ID_essai`, `ID_patient`, `is_accepte`, `is_patient_exclus`, `traitement`, `dose`, `effet_secondaire`, `evolution_symptome`) VALUES
(1, 2, 1, 0, 'Molécule X', 50.0, 'Nausea', 'Improvement'),
(2, 3, 1, 0, 'Molécule Z', 75.0, 'Headache', 'Stable');

-- Insertion des médecins
INSERT INTO `MEDECIN` (`ID_medecin`, `domaine`, `hopital`, `numero_ordre`) VALUES
(1, 'Cardiologie', 'Hôpital A', 12345),
(2, 'Neurologie', 'Hôpital B', 67890);

-- Insertion des essais-médecins
INSERT INTO `ESSAI_MEDECIN` (`ID_medecin`, `ID_essai`, `is_accepte`, `is_from_company`) VALUES
(1, 1, 1, 0),
(2, 2, 1, 1);

-- Insertion des phases
INSERT INTO `PHASE` (`ID_essai`, `ID_phase`, `date_debut`, `date_fin_prevue`, `nombre_patients`) VALUES
(1, 1, '2023-01-01', '2023-06-30', 50),
(2, 2, '2023-02-01', '2023-08-31', 75);