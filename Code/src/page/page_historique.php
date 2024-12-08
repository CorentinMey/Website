<?php
include_once("../back_php/Query.php");
include_once("../back_php/Patient.php");
include_once("../back_php/Affichage_historique.php");
include_once("../back_php/Affichage_gen.php");
session_start();

// TODO ajouter les autres utilisateurs
// if (!isset($_SESSION["patient"])) { // si quelqu'un essaie d'accéder à la page sans être connecté on le redirige vers la page de connexion
//     header("Location: page_login.php");
//     exit;
// }

$bdd = new Query("siteweb");
$search_query = "";
?>


<!DOCTYPE html>

<head>
    <title>Page de l'historique</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_historique.css">
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
            <a href = "page_deco.php">
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

    <img src = "../Ressources/Images/test_banderolle.webp" alt = "banderolle" id = "banderolle_img">


    <div id = "essai_conteneur">
        <div id='title_essai_part'>
            <h2 class = 'title'>Clinical trials completed</h2>
        </div>

        <?= AfficherBarreRecherche($search_query);?>
        <div id='new_essais'>
            <?php
                $essais = getEssaiTermine($bdd);
                foreach($essais as $essai){
                    AfficherEssaisFinis($bdd, $essai);
                }

            ?>
            
        </div>
    </div>

</body>