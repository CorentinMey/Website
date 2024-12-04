<?php
include_once("../back_php/Query.php"); 
$query = new Query('siteweb');

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Fonction pour afficher le profil du médecin
    function afficherProfilentreprise($query, $id_user) {
        // Récupérer tous les résultats, mais nous n'attendons qu'un seul résultat
        $entreprise = $query->getResultsAll(
            "SELECT u.nom, u.prenom, u.mail, e.siret, e.ville
             FROM utilisateur u
             JOIN entreprise e ON u.ID_User = e.siret
             WHERE u.ID_User = ?",
            [$id_user]
        );
        
        
        
        // Vérifier si un résultat a été trouvé
        if (!empty($entreprise)) {
            // Accéder au premier élément du tableau
            echo '<!DOCTYPE html>';
            echo '<html lang="fr">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
            echo '<title>Profil Entreprise</title>';
            echo '<link rel="stylesheet" type="text/css" href="../CSS/global.css">';
            echo '<link rel="stylesheet" type="text/css" href="../CSS/page_admin.css">';
            echo '</head>';
            echo '<body>';
            echo '<img src="../Ressources/Images/taiwan.jpg" alt="fond" id="fond">';
        
            $entreprise = $entreprise[0];

            // Afficher les informations de l'entreprise
            echo '<div class="content-wrapper2">';
            echo "<h2>Company : " . htmlspecialchars($entreprise['nom']) . " " . htmlspecialchars($entreprise['prenom']) . "</h2>";
            echo '<p><strong>Nom:</strong> ' . htmlspecialchars($entreprise['nom']) . '</p>';
            echo '<p><strong>Prénom:</strong> ' . htmlspecialchars($entreprise['prenom']) . '</p>';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($entreprise['mail']) . '</p>';
            echo '<p><strong>Siret:</strong> ' . htmlspecialchars($entreprise['siret']) . '</p>';
            echo '<p><strong>Ville:</strong> ' . htmlspecialchars($entreprise['ville']) . '</p>';
        } else {
            echo "<p>Company not found.</p>";
        }
        
    }

    
    

    // Appeler la fonction pour afficher le profil du médecin
    afficherProfilentreprise($query, $id_user);
} else {
    echo "<p>No company selected.</p>";
}
?>
