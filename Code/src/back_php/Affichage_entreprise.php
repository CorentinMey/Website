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

?>
</body>
</html>