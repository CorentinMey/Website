<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_entreprise.css">
</head>
<body>

</body>
<?php
include_once("Query.php");
include_once("Entreprise.php");
session_start();

$entreprise = $_SESSION["entreprise"];
$siret = $entreprise->getSiret();
$bdd = new Query("siteweb");

function afficherListeEssais($bdd, $siret) {
    echo '<div class="Tableau">';
    echo "<h2>Liste of my assay</h2>";

    // Requête pour récupérer les essais cliniques associés à un SIRET donné
    $query = "SELECT 
            essai.description, 
            utilisateur.prenom AS nom_entreprise, 
            essai.date_debut, 
            essai.date_fin, 
            essai.molecule_test, 
            essai.dosage_test
        FROM essai 
        INNER JOIN entreprise ON entreprise.siret = essai.ID_entreprise_ref 
        INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret
        WHERE entreprise.siret = :siret";

    $res =$bdd->getResultsAll($query, [":siret" => $siret]);
    

    // Vérifie si des essais ont été trouvés
    if (!empty($res)) {
        echo "<ul>";
        foreach ($res as $essai) {
            echo '<div class="box_list">';
            echo '    <div class="assay-info">';
            echo '        <p><strong>Description :</strong> ' . htmlspecialchars($essai['description']) . '</p>';
            echo '        <p><strong>Nom de l\'entreprise :</strong> ' . htmlspecialchars($essai['nom_entreprise']) . '</p>';
            echo '        <p><strong>Date de début :</strong> ' . htmlspecialchars($essai['date_debut']) . '</p>';
            echo '        <p><strong>Date de fin :</strong> ' . htmlspecialchars($essai['date_fin']) . '</p>';
            echo '        <p><strong>Molécule testée :</strong> ' . htmlspecialchars($essai['molecule_test']) . '</p>';
            echo '        <p><strong>Dosage testé :</strong> ' . htmlspecialchars($essai['dosage_test']) . '</p>';
            echo '    </div>';
            echo '    <div class="assay-actions">';
            echo '        <h3>Voir les détails</h3>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun essai trouvé pour cette entreprise.</p>";
    }

    echo '</div>';
}

function afficherFormulaireNouvellePhase() {
    echo '<div class="content-wrapper">';
    echo "<h2>Créer un nouvel essai clinique</h2>";
    echo '<form method="POST" action="" class="form-nouvelle-phase">';
    
    // Champs du formulaire
    echo '<label for="id_essai">ID Essai:</label>';
    echo '<input type="number" id="id_essai" name="id_essai" required><br>';
    
    echo '<label for="date_debut">Date de début:</label>';
    echo '<input type="date" id="date_debut" name="date_debut" required><br>';
    
    echo '<label for="date_fin">Date de fin:</label>';
    echo '<input type="date" id="date_fin" name="date_fin"><br>';
    
    echo '<label for="description">Description:</label>';
    echo '<textarea id="description" name="description" rows="4" required></textarea><br>';
    
    echo '<label for="molecule_test">Molécule testée:</label>';
    echo '<input type="text" id="molecule_test" name="molecule_test" required><br>';
    
    echo '<label for="dosage_test">Dosage testé:</label>';
    echo '<input type="text" id="dosage_test" name="dosage_test" required><br>';
    
    echo '<label for="molecule_ref">Molécule de référence:</label>';
    echo '<input type="text" id="molecule_ref" name="molecule_ref"><br>';
    
    echo '<label for="dosage_ref">Dosage de référence:</label>';
    echo '<input type="text" id="dosage_ref" name="dosage_ref"><br>';
    
    echo '<label for="placebo_nom">Nom du placebo:</label>';
    echo '<input type="text" id="placebo_nom" name="placebo_nom"><br>';
    
    echo '<button type="submit" name="createPhase">Créer un essai</button>';
    echo '</form>';
    echo '</div>';
}

/**
 * Redirige vers la page par défaut de l'entreprise.
 */
function revenirPageParDefaut() {
    // Construit l'URL de base de la page actuelle sans les paramètres
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    // Redirige vers l'URL
    header("Location: $url");
    exit; // Stoppe l'exécution du script pour éviter des comportements inattendus
}



?>
</body>
</html>