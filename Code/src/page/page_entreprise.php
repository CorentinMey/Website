<?php
include_once("../back_php/Query.php");
include_once("../back_php/Utilisateur.php");
include_once("../back_php/Entreprise.php");

session_start();

if (!isset($_SESSION["entreprise"])) {
    header("Location: page_login.php");
    exit;
}

$entreprise = $_SESSION["entreprise"];
$bdd = new Query("siteweb");
?>

<!DOCTYPE html>

<head>

    <title>Page de l'entreprise</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_entreprise.css">
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
            <!-- mettre logo dans une balise ref a pour rediriger -->
            <a href="page_test.php">
                <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
                <div id="tooltip">Disconnect</div>
            </a>
        </div>
    </div>

    <img src = "../Ressources/Images/image_banderolle.webp" alt = "banderolle" id = "banderolle_img">

 
    <h2 class = "title">Options</h2>
 
    <form action = "" method = "post" id = "redirect_buttons">
        <!-- <div id = "redirect_buttons"> -->
            <button class = "button" id = "button_patient" name = "Action" value = "ViewMine">
                My clinical trials
                <?php  // si j'ai des notifications, j'affiche le rond de notification
                $nb_notif = $entreprise->NombreNotif($bdd);
                if ($nb_notif > 0)
                    echo "<span class='notification'>".htmlspecialchars($nb_notif)."</span>" // <!-- Ajoute le rond de notification -->
                ?>
            </button>
            <button class = "button" id = "button_patient" name = "Action" value = "ViewNew">New studies</button>
            <button class = "button" id = "button_patient" name = "Action" value = "ViewInfo">My information</button>
        <!-- </div> -->
    </form>



</body>