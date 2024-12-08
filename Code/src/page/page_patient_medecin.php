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


// Si le médecin a modifié les informations du patient
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ban_user'])) { // Vérification de s'il y a eu demande de modification des informations
        // Récupération des informations du formulaire
        $form_data = [
            'prenom' => $_POST['prenom'],
            'nom' => $_POST['nom'],
            'email' => $_POST['identifiant'],
            'genre' => $_POST['genre'],
            'date_naissance' => $_POST['date_naissance'],
            'antecedents' => $_POST['antecedents'],
            'traitement' => $_POST['traitement'],
            'dose' => $_POST['dose'],
            'effet_secondaire' => $_POST['effet_secondaire'],
            'evolution_symptome' => $_POST['evolution_symptome'],
            'ban_user' => $_POST['ban_user']
        ];
        $medecin->ChangeInfo_patient($bdd, $_SESSION["ID_User"], $_SESSION["ID_essai"], $form_data);
    }
}

?>

<!DOCTYPE html>

<head>

    <title>Page visualisation des patients par le médecin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_patient.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">


</head>


<body>
    <div id = "en-tete">
        <img src = "../Ressources/Images/logo_medexplorer.png" alt = "logo_med_explorer" id = logo_page>
        <div id = "en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>

    <div id="banderolle">
        
        <!-- div pour le log de l'historique et son bouton -->
        <div id = "logo_container_hist">
            <a href = "page_test.php">
                <img id = "logo_historic" src = "../Ressources/Images/logo_historic.png" alt = "Historic button">
                <div id = "tooltip_hist">Historic</div>
            </a>
        </div>

        <!-- titre de la banderolle -->
        <h1 id="title">My account</h1>

        <!-- div pour le logo de deconnexion et son bouton -->
        <div id="logo_container">
            <!-- mettre logo dans une balsie ref a pour rediriger -->
            <a href="page_deco.php">
                <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
                <div id="tooltip">Disconnect</div>
            </a>
        </div>
    </div>

    <img src = "../Ressources/Images/image_banderolle.webp" alt = "banderolle" id = "banderolle_img">

 

    <?php
    
    $medecin->AffichageTableau_patient($bdd, $_SESSION["ID_User"]);

    ?>


</body>