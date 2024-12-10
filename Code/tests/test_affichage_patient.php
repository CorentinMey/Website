<!DOCTYPE html>

<html>
<head>
    <title>Test Affichage patient</title>
    <charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
</head>

<body>

<h3>Tests unitaires des fonctions Affichage_patient</h3>
    <h4>Test de Affichage_entete_tableau_essai</h4>
    <?php
    include_once("../src/back_php/Affichage_patient.php");
    Affichage_entete_tableau_essai();
    ?>

<h4>Test de Affichage_content_essai </h4>
    <?php
        $entreprise = [
            "nom" => "PharmaCorp",
            "a_debute" => 2 // L'essai est terminé et en attente des résultats
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>
<!-- test avec un autre statut d'essai clinique -->
    <?php
        $entreprise = [
            "nom" => "PharmaCorp2",
            "a_debute" => 1 // L'essai est terminé et en attente des résultats
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>

<?php // test avec un argument invalide affiche la pop up avant le
        $entreprise = [
            "nom" => "PharmaCorp2",
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>






</body>