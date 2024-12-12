<?php
// Inclure les fichiers contenant les fonctions nécessaires
include 'status.php'; // Inclut les fonctions liées au statut des utilisateurs
include_once("Query.php"); // Inclut la classe Query pour interagir avec la base de données

// Vérifier si une requête POST a été envoyée avec les paramètres nécessaires
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['LID'])) {
    // Récupération de l'ID utilisateur envoyé via le formulaire
    $userId = $_POST['LID'];  
    $userId = (int) $userId; // Conversion en entier pour garantir un traitement sécurisé

    // Récupération de l'action choisie (ban, unban, etc.)
    $action = $_POST['action'];  
    // Récupération du contexte supplémentaire (optionnel)
    $context = isset($_POST['context']) ? $_POST['context'] : null;

    // Initialisation de la connexion à la base de données
    // Assure-toi que le paramètre 'siteweb' correspond au nom correct de ta base de données
    $dbConnection = new Query('siteweb');
    
    // Exécuter l'action appropriée en fonction de la valeur de $action
    switch ($action) {
        case 'ban':
            // Appeler la fonction pour bannir l'utilisateur
            banUser($userId, $dbConnection, $context, $limit = null);
            break;

        case 'unban':
            // Appeler la fonction pour débannir l'utilisateur
            unbanUser($userId, $dbConnection, $context, $limit = null);
            break;

        case 'accept':
            // Appeler la fonction pour accepter l'utilisateur
            acceptUser($userId, $dbConnection);
            break;

        case 'reject':
            // Appeler la fonction pour rejeter l'utilisateur
            rejectUser($userId, $dbConnection);
            break;
    }

    // Après avoir exécuté l'action, tu peux rediriger l'utilisateur ou afficher un message
    // La redirection permet d'éviter une soumission multiple en actualisant la page
    //header("Location: ../page/page_admin.php");  // Décommenter pour rediriger vers une page spécifique
    exit();
} else {
    // Affiche un message si les données POST nécessaires ne sont pas disponibles
    echo "jai pas les données du post";
}
?>
