<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_medecin.php");
include_once("../back_php/Medecin.php");
include_once("../back_php/Patient.php");
session_start();

if (!isset($_SESSION["medecin"])) {
    header("Location: page_login.php");
    exit;
}


$medecin = $_SESSION["medecin"];
$bdd = new Query("siteweb");

//Récupérer l'ID user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_user']) && isset($_POST['id_essai'])) { // Vérification correcte
        $_SESSION["ID_User"] = $_POST['id_user'];
        $_SESSION["ID_essai"] = $_POST['id_essai'];
}
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Action'])) {
        switch ($_POST['Action']) {
            case 'Disconnect':
                // Déconnecte l'utilisateur
                header("Location: page_deco.php");
                exit;
            case 'RevenirAccueil':
                header("Location: page_medecin.php");
                exit;
            case 'Historic':
                // Redirige vers la page de l'historique
                header("Location: page_historique.php");
                exit;
            case 'RetourPrecedent':
                // Retourne à la page précédente
                $referer = $_SERVER['HTTP_REFERER'] ?? 'page_medecin.php'; // Si aucun referer, retourne à l'accueil
                header("Location: $referer");
                exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Page du médecin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_patient.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_medecin.css">

</head>

<body>
    <div id="en-tete">
        <img src="../Ressources/Images/logo_medexplorer.png" alt="logo_med_explorer" id="logo_page">
        <div id="en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>

    <div id="banderolle">
        <!-- div pour le log de l'historique et son bouton -->
        <div id="logo_container_hist">
            <img id="logo_historic" src="../Ressources/Images/logo_historic.png" alt="Historic button">
            <div id="dropdown_menu_hist">
                <form method="post" action="">
                    <!-- Bouton de l'historique -->
                    <button class="dropdown_button" name="Action" value="Historic">Historic</button>
                </form>
            </div>
        </div>
        <!-- titre de la banderolle -->
        <h1 id="title">My account</h1>

        <!-- div pour le logo de deconnexion et son bouton -->
        <div id="logo_container">
            <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
            <div id="dropdown_menu">
                <form method="post" action="">
                    <!-- Bouton de déconnexion -->
                    <button class="dropdown_button" name="Action" value="Disconnect">Disconnect</button>
                    <!-- Bouton Home -->
                    <button class="dropdown_button" name="Action" value="RevenirAccueil">Home</button>
                    <!-- Bouton Retour à la page précédente -->
                    <button class="dropdown_button" name="Action" value="RetourPrecedent">Back</button>
                </form>
            </div>
        </div>
    </div>

    <img src="../Ressources/Images/test_banderolle.webp" alt="banderolle" id="banderolle_img">
</body>

</html>


 

    <?php
    // Si le médecin a modifié les informations du patient
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['ban_user'])) { // Vérification de s'il y a eu demande de modification des informations
            // Convertion de ban_user en numérique
            $ban_user = $_POST['ban_user'];
            if ($ban_user == "1") {
                $ban = 1;
            } else {
                $ban = 0;
            }
            // Récupération des informations du formulaire
            $form_data = [
                'prenom' => $_POST['prenom'],
                'nom' => $_POST['nom'],
                'genre' => $_POST['genre'],
                'date_naissance' => $_POST['date_naissance'],
                'antecedents' => $_POST['antecedents'],
                'traitement' => $_POST['traitement'],
                'dose' => $_POST['dose'],
                'effet_secondaire' => $_POST['effet_secondaire'],
                'evolution_symptome' => $_POST['evolution_symptome'],
                'ban_user' => $ban
            ];
            $medecin->ChangeInfo_patient($bdd, $_SESSION["ID_User"], $_SESSION["ID_essai"], $form_data);
            AfficherInfo("Modifications on user " . $_POST['prenom'] . " " . $_POST['nom'] . " have been saved.", 0, 0, False);
            if ($_POST["ban_user"]==1){
                AfficherInfo("User " . $_POST['prenom'] . " " . $_POST['nom'] . " have been excluded from the trial.", 0, 0, False);
        }
    }
}

    
    $medecin->AffichageTableau_patient($bdd, $_SESSION["ID_User"]);

    ?>


</body>