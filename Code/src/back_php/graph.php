<?php
include_once('jpgraph/jpgraph.php');
include_once('jpgraph/jpgraph_bar.php');
include_once("Affichage_gen.php");

/**
 * Affiche un graphique en barres groupées
 * @param array $data Données à afficher
 * @param array $categories Catégories de données
 * @param string $title Titre du graphique
 * @param string $xlabel Libellé de l'axe X
 * @param string $ylabel Libellé de l'axe Y
 */
function barplot($data, $categories, $title = "Bar Plot Groupé", $xlabel = "Catégories", $ylabel = "Nombre de personnes") {
    // Nombre de groupes
    $groupNames = array_keys($data);
    // $groupCount = count($groupNames);

    // // Nombre de catégories
    // $categoryCount = count($categories);

    // Création du graphique
    $graph = new Graph(800, 500, 'auto');    
    $graph->SetScale("textlin");
    $graph->SetShadow();
    $graph->img->SetMargin(40, 150, 20, 40); // Augmenter la marge droite pour la légende
    $graph->xaxis->SetTickLabels($categories);
    $graph->xaxis->SetTitle($xlabel, "center");
    $graph->xaxis->SetTitleMargin(50);
    $graph->xaxis->title->SetFont(FF_FONT1, FS_NORMAL);
    $graph->xaxis->SetLabelAngle(45);
    $graph->yaxis->SetTitle($ylabel, 'middle');
    $graph->yaxis->title->SetFont(FF_FONT1, FS_NORMAL);
    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT1, FS_BOLD);

    // Créer les bar plots pour chaque groupe
    $barPlots = [];
    $colors = ["orange", "blue", "red"]; // Couleurs pour chaque groupe
    foreach ($groupNames as $index => $group) {
        $bplot = new BarPlot($data[$group]);
        $bplot->SetFillColor($colors[$index % count($colors)]);
        $bplot->SetLegend($group);
        // Afficher les valeurs au-dessus des barres
        $bplot->value->Show();
        $bplot->value->SetFormat('%d'); // Format des valeurs
        $bplot->value->SetColor("black");
        $bplot->value->SetFont(FF_FONT1, FS_BOLD);
        $barPlots[] = $bplot;
    }

    // Créer le plot groupé
    $gbplot = new GroupBarPlot($barPlots);
    $graph->Add($gbplot);

    // Configurer la légende
    $graph->legend->SetPos(0.05, 0.5, 'right', 'center'); // Positionnement à droite
    $graph->legend->SetColumns(1); // Disposer les légendes en une seule colonne

    // Afficher le graphique
    $graph->Stroke();
}

/**
 * Fonction pour regrouper les âges par tranche d'âge
 *  */ 
function GroupData($ages, $interval = 5) {
    $ageGroups = array();
    foreach ($ages as $age) {
        if (!is_numeric($age)) { // vérifie si age est int ou float
            throw new InvalidArgumentException("L'âge '{$age}' n'est pas numérique.");
        }
        $group = floor($age / $interval) * $interval;
        if (isset($ageGroups[$group])) {
            $ageGroups[$group]++;
        } else {
            $ageGroups[$group] = 1;
        }
    }
    // Trier les groupes par clé (tranche d'âge)
    ksort($ageGroups);
    return $ageGroups;
}



/**
 * Affiche un histogramme en fonctions des données
 * @param array $data Données à afficher
 * @param string $title Titre du graphique
 * @param string $xlabel Libellé de l'axe X
 * @param string $ylabel Libellé de l'axe Y
 * @param string $color Couleur des barres
 * @param int $bin Taille des tranches d'âge
 */
function Histogramme($data, $title = "Age histogram", $xlabel = "Age slices", $ylabel = "Number of people", $color = "skyblue", $bin = 10) {
    // Créer le graphique
    $graph = new Graph(800, 500);
    $graph->SetScale('textint');
    $graph->SetMarginColor('white');
    $graph->SetFrame(false);
    try { // try lors de l'analyse des données
        // Regrouper les âges par tranches indiqué par $bin
        $ageGroups = GroupData($data, $bin);
        // Préparer les données pour le graphique
        $dataY = array_values($ageGroups); // Valeurs (nombre de personnes)
        $dataX = array_map(function($v) use ($bin) { return $v . '-' . ($v + $bin); }, array_keys($ageGroups)); // Libellés des tranches d'âge
        $bplot = new BarPlot($dataY);
        $bplot->SetFillColor($color);
        $bplot->SetColor('blue');
    } catch (Exception $e) {
        AfficherErreur("Impossible de générer l'histogramme : " . $e->getMessage());
        return;
    }
    // Configurer le titre du graphique
    $graph->title->Set($title);
    $graph->title->SetFont(FF_FONT1, FS_BOLD);
    // Configurer l'axe X
    $graph->xaxis->SetTickLabels($dataX);
    $graph->xaxis->SetTitle($xlabel, "center");
    $graph->xaxis->SetTitleMargin(50);
    $graph->xaxis->title->SetFont(FF_FONT1, FS_NORMAL);
    $graph->xaxis->SetLabelAngle(45);
    // Configurer l'axe Y
    $graph->yaxis->SetTitle($ylabel, 'middle');
    $graph->yaxis->title->SetFont(FF_FONT1, FS_NORMAL);
    // Créer le plot de barres
    // Afficher les valeurs au-dessus des barres
    $bplot->value->Show();
    $bplot->value->SetFormat('%d');
    $bplot->value->SetFont(FF_FONT1, FS_NORMAL);
    // Ajouter le plot au graphique
    $graph->Add($bplot);
    // Afficher le graphique
    $graph->Stroke();
}

/**
 * Fonction pour récupérer les données de l'histogramme depuis la BDD
 * @param Query $bdd Objet Query pour les requêtes SQL
 * @param int $id_essai ID de l'essai clinique
 * @param int $nb_phase Numéro de la phase de l'essai clinique
 * @return array Tableau des âges des patients (à utiliser directement dans la fonction histogramme)
 */
function getDataHistogram($bdd, $id_essai, $nb_phase){
    $query = "SELECT date_naissance FROM `resultat` 
                    JOIN utilisateur ON utilisateur.ID_User = resultat.ID_patient
                        WHERE ID_essai = :id_essai AND phase_res = :nb_phase;";

    $param = ["id_essai" => $id_essai, "nb_phase" => $nb_phase];
    $data = $bdd->getResultsAll($query, $param);
    foreach ($data as $row) {
        $date_naissance = date_create($row['date_naissance']);
        $age = date_diff($date_naissance, date_create('today'))->y;
        $ages[] = $age;
    }
    return $ages;
}


/**
 * Exécute la requête SQL pour récupérer les données du barplot
 * @param Query $bdd Instance de la classe Query
 * @param int $id_essai Identifiant de l'essai clinique
 * @param int $nb_phase Numéro de la phase de l'essai clinique
 * @return array Résultats de la requête
 */
function executeBarPlotQuery($bdd, $id_essai, $nb_phase){
    $query =  "
        SELECT evolution_symptome, traitement, COUNT(*) AS nombre_personnes
        FROM `resultat`
        JOIN utilisateur ON utilisateur.ID_User = resultat.ID_patient
        WHERE ID_essai = :id_essai AND phase_res = :id_phase
        GROUP BY evolution_symptome, traitement
        ORDER BY evolution_symptome, traitement;
    ";
    
    $param = ["id_essai" => $id_essai, "id_phase" => $nb_phase];
    return $bdd->getResultsAll($query, $param);
}

/**
 * Traite les résultats de la requête pour structurer les données du barplot
 * @param array $results Résultats de la requête SQL
 * @return array Tableau structuré avec 'categories', 'traitements' et 'data'
 */
function processBarPlotData($results){
    // Initialiser le tableau des données
    $data = [];
    $traitements = []; // Types de traitement possibles
    $categories = [];

    // Parcourir les résultats
    foreach ($results as $row) {
        $category = $row['evolution_symptome'];
        $traitement = $row['traitement'];
        $count = (int)$row['nombre_personnes']; // Cast en int pour éviter les erreurs

        // Ajouter la catégorie si elle n'existe pas déjà
        if (!in_array($category, $categories)) {
            $categories[] = $category;
        }

        // Ajouter le traitement si il n'existe pas déjà
        if (!in_array($traitement, $traitements)) {
            $traitements[] = $traitement;
        }

        // Assigner le comptage au traitement et à la catégorie correspondante
        $data[$traitement][$category] = $count;
    }

    // Assurer que chaque groupe a une valeur pour chaque catégorie
    foreach ($traitements as $traitement) {
        foreach ($categories as $category) {
            if (!isset($data[$traitement][$category])) {
                $data[$traitement][$category] = 0;
            }
        }
    }

    // Préparer les données finales
    $finalData = [];
    foreach ($traitements as $traitement) {
        $finalData[$traitement] = [];
        foreach ($categories as $category) {
            $finalData[$traitement][] = $data[$traitement][$category];
        }
    }

    return [
        'categories' => $categories,
        'traitements' => $traitements,
        'data' => $finalData
    ];
}

/**
 * Récupère les données pour le barplot groupé depuis la BDD
 * @param Query $bdd Instance de la classe Query pour interagir avec la base de données
 * @param int $id_essai Identifiant de l'essai clinique
 * @param int $nb_phase Numéro de la phase de l'essai clinique
 * @return array Tableau associatif avec les catégories et les valeurs pour chaque groupe
 */
function getDataBarPlot($bdd, $id_essai, $nb_phase){
    $results = executeBarPlotQuery($bdd, $id_essai, $nb_phase);
    $processedData = processBarPlotData($results);

    return $processedData;
}

?>