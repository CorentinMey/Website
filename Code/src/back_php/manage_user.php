<?php
// Inclure les fonctions d'action
include 'status.php';
include_once("Query.php");

// Vérifier si une action a été envoyée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['LID'])) {
    $userId = $_POST['LID'];  // ID de l'utilisateur envoyé par le formulaire
    $action = $_POST['action'];  // L'action choisie (ban, unban, etc.)

    // Connexion à la base de données (ajuste selon ta configuration)
    $dbConnection = new Query('siteweb');
    

    // Appeler la fonction appropriée en fonction de l'action
    switch ($action) {
        case 'ban':
            // Appeler la fonction ban
            banUser($userId, $dbConnection);
            break;

        case 'unban':
            // Appeler la fonction unban
            unbanUser($userId, $dbConnection);
            break;

        case 'accept':
            // Appeler la fonction accept
            acceptUser($userId, $dbConnection);
            break;

        case 'reject':
            // Appeler la fonction reject
            rejectUser($userId, $dbConnection);
            break;
    }

    // Rediriger ou afficher un message après l'action
    header("Location: ../page/page_admin.php");  // Redirection pour éviter la soumission multiple
    exit();
}
else{
    echo "jai pas les données du post";
}
?>