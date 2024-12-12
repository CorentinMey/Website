<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_medecin.php");
include_once("../back_php/Medecin.php");
session_start();

if (!isset($_SESSION["medecin"])) {
    header("Location: page_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_essai'])) { // Vérification correcte
        $_SESSION["id_essai"] = $_POST['id_essai'];
}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['is_accepte'])) { // Vérification correcte
        $_SESSION["is_accepte"] = $_POST['is_accepte'];
}
}


$medecin = $_SESSION["medecin"];
$bdd = new Query("siteweb");


?>

<!DOCTYPE html>

<head>

    <title>Page des essais clinique du médecin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_patient.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_medecin.css">
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

 
    <h2 class = "title">Options</h2>

    <?php 
    // Code pour accepter ou refuser un patient dans un essai
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['decision'])) {
        $decision = (int)$_POST['decision']; // Récupère la décision (1 pour Oui, 0 pour Non)
        $id_user = $_POST['id_user'] ?? null; // Récupère l'ID utilisateur
        $id_essai = $_POST['id_essai'] ?? null; // Récupère l'ID essai

        if ($decision === 1) {
            // Code pour "Oui"
            $medecin->AccepterPatient($bdd,$id_essai,$id_user);
            AfficherInfo("L'utilisateur $id_user a été accepté dans l'essai $id_essai.", 0, 0, False);
        } elseif ($decision === 0) {
            // Code pour "Non"
            $medecin->SupprimerPatient($bdd,$id_essai,$id_user);
            AfficherInfo("L'utilisateur $id_user a été refusé de l'essai $id_essai.", 0, 0, False);
        }
    }
}

    // Code pour accepter ou refuser une demande de superviser un essai
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['accepter'])) {
            $decision = (int)$_POST['accepter']; // Récupère la décision (1 pour Oui, 0 pour Non)
            $id_user = $_POST['id_user'] ?? null; // Récupère l'ID utilisateur
            $id_essai = $_POST['id_essai'] ?? null; // Récupère l'ID essai
    
            if ($decision === 1) {
                // Code pour "Oui"
                $medecin->AccepterDemande($bdd,$id_essai,$id_user);
                AfficherInfo("Vous avez bien accepté la demande de superviser l'essai $id_essai.", 0, 0, False);
            } elseif ($decision === 0) {
                // Code pour "Non"
                $medecin->SupprimerDemande($bdd,$id_essai,$id_user);
                AfficherInfo("Vous avez bien refusé la demande de superviser l'essai $id_essai.", 0, 0, False);
            }
        }
    }

    
    
    

    ?>
 
    <form action = "" method = "post" id = "redirect_buttons">
        <!-- <div id = "redirect_buttons"> -->
            <button class = "button" id = "button_medecin" name = "Action" value = "Informations">
                Detailed informations about the trial
            </button>
            <button class = "button" id = "button_medecin" name = "Action" value = "Participants">Participants</button>
            <button class = "button" id = "button_medecin" name = "Action" value = "Results">Results and graphs</button>
        <!-- </div> -->
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Action"]) && isset($_SESSION["id_essai"]) && isset($_SESSION["is_accepte"])) { // Si un bouton a été cliqué et que les données ont bien été transmises
        switch ($_POST['Action']) {
            case "Informations":
                $medecin->AfficheEssais_full($bdd, $_SESSION["id_essai"]);
                break;
            case "Participants":
                if ($_SESSION["is_accepte"]==1){
                    $medecin->AfficherParticipants($bdd, $_SESSION["id_essai"]);
                }
                else{
                    AfficherErreur("Vous n'êtes pas encore responsable de cet essai, les données patients ne sont donc pas accessibles");
                }
                break;
            case "Results":
                $medecin->AffichageResultats($bdd, $_SESSION["id_essai"]);
                break;
        }
    } else {
        $medecin->AffichageTableau($bdd);
    }
                

    ?>

</body>