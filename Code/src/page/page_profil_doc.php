<?php
include_once("../back_php/Query.php"); 
$query = new Query('siteweb');

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Fonction pour afficher le profil du médecin
    function afficherProfilMedecin($query, $id_user) {
        // Récupérer tous les résultats, mais nous n'attendons qu'un seul résultat
        $doctor = $query->getResultsAll(
            "SELECT u.nom, u.prenom, u.mail, u.genre, m.domaine, m.hopital
             FROM utilisateur u
             JOIN medecin m ON u.ID_User = m.numero_ordre
             WHERE u.ID_User = ?",
            [$id_user]
        );
        
        // Vérifier si un résultat a été trouvé
        if (!empty($doctor)) {
            // Prendre le premier résultat (dans ce cas, ce devrait être le seul)
            $doctor = $doctor[0];

            echo '<!DOCTYPE html>';
            echo '<html lang="fr">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>Profil Médecin</title>';
            echo '<link rel="stylesheet" type="text/css" href="../CSS/global.css">';
            echo '<link rel="stylesheet" type="text/css" href="../CSS/page_admin.css">';
            echo '</head>';
            echo '<body>';
            echo '<img src="../Ressources/Images/taiwan.jpg" alt="fond" id="fond">';
            
            echo '<div class="content-wrapper2">';
            echo "<h2>Profile of Dr. " . htmlspecialchars($doctor['prenom']) . " " . htmlspecialchars($doctor['nom']) . "</h2>";
            echo '<p><strong>Nom:</strong> ' . htmlspecialchars($doctor['nom']) . '</p>';
            echo '<p><strong>Prénom:</strong> ' . htmlspecialchars($doctor['prenom']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($doctor['mail']) . '</p>';
            echo '<p><strong>Genre:</strong> ' . htmlspecialchars($doctor['genre']) . '</p>';
            echo '<p><strong>Hôpital:</strong> ' . htmlspecialchars($doctor['hopital']) . '</p>';
            echo '<p><strong>Domaine:</strong> ' . htmlspecialchars($doctor['domaine']) . '</p>';
            echo '</div>';
        } else {
            echo "<p>Doctor not found.</p>";
        }
    }

    
    

    // Appeler la fonction pour afficher le profil du médecin
    afficherProfilMedecin($query, $id_user);
} else {
    echo "<p>No doctor selected.</p>";
}
?>
