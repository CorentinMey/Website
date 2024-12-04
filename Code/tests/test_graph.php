<?php
include_once("../src/back_php/Query.php");
include_once("../src/back_php/graph.php");
include_once("../src/back_php/Query.php");

$bdd = new Query("siteweb");

//Test de la fonction histogramme

// $data = getDataHistogram($bdd, 1, 1);
// Histogramme($data, "Age histogram", "Age slices", "Number of people", "skyblue", 10);

// test si toutes les données sont bien définies
// $ages = array(10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60);
// Histogramme($ages, "Age histogram", "Age slices", "Number of people", "skyblue", 10);

// test si les données sont vides
// renvoie une image d'erreur
// $ages = array();

// test si les nombres sont des strings
// Fonctionne parfaitement car les strings sont convertis en int
// $ages = array("10", "15", "20", "25", "30", "35", "40", "45", "50", "55", "60");
// Histogramme($ages, "Age histogram", "Age slices", "Number of people", "skyblue", 10);

// test si on a des float
// fonctionne parfaitement car les float sont regroupés dans les classes
// $ages = array(10.5, 15.5, 20.5, 25.5, 30.5, 35.5, 40.5, 45.5, 50.5, 55.5, 60.5);
// Histogramme($ages, "Age histogram", "Age slices", "Number of people", "skyblue", 10);

// test si on a des phrases
// renvoie une image d'erreur
// $ages = array("dix", "quinze", "vingt", "vingt-cinq", "trente", "trente-cinq", "quarante", "quarante-cinq", "cinquante", "cinquante-cinq", "soixante");
// Histogramme($ages, "Age histogram", "Age slices", "Number of people", "skyblue", 10);



// Identifiants de l'essai clinique et de la phase
$id_essai = 1;
$nb_phase = 1;

// Récupérer les données pour le barplot groupé
$barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);

// Extraire les catégories et les données
$categories = $barplotData['categories'];
$groups = $barplotData['traitements'];
$data = $barplotData['data'];

// Générer le barplot groupé
echo barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");




?>