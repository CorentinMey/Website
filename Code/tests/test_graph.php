<?php
include_once("../src/back_php/Query.php");
include_once("../src/back_php/graph.php");
include_once("../src/back_php/Query.php");
$bdd = new Query("siteweb2");
?>

<DOCTYPE html>

<html>
    <head>
        <title>Test graph</title>
    </head>



    <body>
        <h1>test de la fonction histogramme</h1>
        <h3>test si toutes les données sont bien définies</h3>
        <?php 
            $id_essai = 1; 
            $nb_phase = 1; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3>test si les données sont vides</h3>
        <?php 
            $id_essai = null; 
            $nb_phase = null; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3>test si les nombres sont des strings</h3>
        <?php 
            $id_essai = "1"; 
            $nb_phase = "1"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3>test si on met n'importe quoi</h3>
        <?php 
            $id_essai = "BIMB >>>>>>> PB"; 
            $nb_phase = "un"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3>test injection de code</h3>
        <?php 
            $id_essai = "DROP TABLE essai";
            $nb_phase = "DROP TABLE essai";
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3> test si on met des commentaire php // </h3>
        <?php 
            $id_essai = "//85"; 
            $nb_phase = "//1"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3> test si on met des commentaire php /* */ </h3>
        <?php 
            $id_essai = "/*85*/"; 
            $nb_phase = "/*1*/"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3> test si on met des commentaire html </h3>
        <?php 
            $id_essai = "<!--85-->"; 
            $nb_phase = "<!--1-->"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3> test si on met des balises html </h3>
        <?php 
            $id_essai = "<h1>85</h1>"; 
            $nb_phase = "<h1>1</h1>"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>

        <h3>tes avec des commentaire SQL</h3>
        <?php 
            $id_essai = "--85"; 
            $nb_phase = "--1"; 
            $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
            Histogramme($datahisto);
        ?>


        <h1> Test de la fonction barplot</h1>
        <h3>test si toutes les données sont bien définies</h3>
        
        <?php 
            $id_essai = 1;
            $nb_phase = 1;
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3>test si les données sont vides</h3>
        <?php 
            $id_essai = null;
            $nb_phase = null;
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3>test si les nombres sont des strings</h3>
        <?php 
            $id_essai = "1";
            $nb_phase = "1";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3>test si on met n'importe quoi</h3>
        <?php 
            $id_essai = "BIMB >>>>>>> PB";
            $nb_phase = "un";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3>test injection de code</h3>
        <?php 
            $id_essai = "DROP TABLE essai";
            $nb_phase = "DROP TABLE essai";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3> test si on met des commentaire php // </h3>
        <?php 
            $id_essai = "//85";
            $nb_phase = "//1";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3> test si on met des commentaire php /* */ </h3>
        <?php 
            $id_essai = "/*85*/";
            $nb_phase = "/*1*/";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3> test si on met des commentaire html </h3>
        <?php 
            $id_essai = "<!--85-->";
            $nb_phase = "<!--1-->";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3> test si on met des balises html </h3>
        <?php 
            $id_essai = "<h1>85</h1>";
            $nb_phase = "<h1>1</h1>";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h3>tes avec des commentaire SQL</h3>
        <?php 
            $id_essai = "--85";
            $nb_phase = "--1";
            // Récupérer les données pour le barplot groupé
            $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
            // Extraire les catégories et les données
            $categories = $barplotData['categories'];
            $data = $barplotData['data'];
            // Générer le barplot groupé
            barplot($data, $categories, "Evolution des Symptomes par Groupe", "Symptomes", "Nombre de Personnes");
        ?>

        <h1> Tes de la fonction Boxplot</h1>
        <h3>test si toutes les données sont bien définies</h3>
        <?php 
            $id_essai = 1;
            $nb_phase = 1;
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3>test si les données sont vides</h3>
        <?php 
            $id_essai = null;
            $nb_phase = null;
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3>test si les nombres sont des strings</h3>
        <?php 
            $id_essai = "1";
            $nb_phase = "1";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");

        ?>

        <h3>test si on met n'importe quoi</h3>
        <?php 
            $id_essai = "BIMB >>>>>>> PB";
            $nb_phase = "un";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3>test injection de code</h3>
        <?php 
            $id_essai = "DROP TABLE essai";
            $nb_phase = "DROP TABLE essai";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3> test si on met des commentaire php // </h3>

        <?php 
            $id_essai = "//85";
            $nb_phase = "//1";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3> test si on met des commentaire php /* */ </h3>
        <?php 
            $id_essai = "/*85*/";
            $nb_phase = "/*1*/";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3> test si on met des commentaire html </h3>
        <?php 
            $id_essai = "<!--85-->";
            $nb_phase = "<!--1-->";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3> test si on met des balises html </h3>
        <?php 
            $id_essai = "<h1>85</h1>";
            $nb_phase = "<h1>1</h1>";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>

        <h3>tes avec des commentaire SQL</h3>
        <?php 
            $id_essai = "--85";
            $nb_phase = "--1";
            // Récupérer les données pour le boxplot
            $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
            $categories = array_keys($boxplotData);
            // ajouter 2 catégories fantomes pour les données manquantes
            array_push($categories, " ");
            array_unshift($categories, " ");
            $boxplot_dict = TransformDataBoxPlot($boxplotData);
            // Générer le boxplot
            boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements");
        ?>


        <h3>tes avec des commentaire SQL</h3>
                <?php 
                    $id_essai = "--85";
                    $nb_phase = "--1";
                    // Récupérer les données pour le boxplot
                    $boxplotData = getDataBoxPlotSideEffect($bdd, $id_essai, $nb_phase);
                    $categories = array_keys($boxplotData);
                    // ajouter 2 catégories fantomes pour les données manquantes
                    array_push($categories, " ");
                    array_unshift($categories, " ");
                    $boxplot_dict = TransformDataBoxPlot($boxplotData);
                    // Générer le boxplot
                    boxplot($boxplot_dict, $categories, "Boxplot des traitements", "Traitements", "red");
                ?>
    </body>