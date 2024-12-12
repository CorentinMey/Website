<?php
include_once("../back_php/Query.php");
include_once("../back_php/Patient.php");
include_once("../back_php/Affichage_historique.php");
include_once("../back_php/Affichage_gen.php");
session_start();

if (!isset($_SESSION["patient"]) && !isset($_SESSION["entreprise"]) && !isset($_SESSION["medecin"])) { // si quelqu'un essaie d'accéder à la page sans être connecté on le redirige vers la page de connexion
    header("Location: page_login.php");
    exit;
} 


$bdd = new Query("siteweb");
$search_query = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_query"])) {
    $search_query = $_POST["search_query"];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Action'])) {
        switch ($_POST['Action']) {
            case 'Disconnect':
                // Déconnecte l'utilisateur
                header("Location: page_deco.php");
                exit;
            case 'RevenirAccueil':
                if (isset($_SESSION["patient"])) {
                    header("Location: page_patient.php");
                    exit;
                } elseif (isset($_SESSION["entreprise"])) {
                    header("Location: page_entreprise.php");
                    exit;
                } elseif (isset($_SESSION["medecin"])) {
                    header("Location: page_medecin.php");
                    exit;
                }
                break;
            case 'Historic':
                // Redirige vers la page de l'historique
                header("Location: page_historique.php");
                exit;
        }
    }
}

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
            <img id = "logo_historic" src = "../Ressources/Images/logo_historic.png" alt = "Historic button">
            <div id="dropdown_menu_hist">
                <form method="post" action="">
                    <!-- Bouton de déconnexion -->
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
                </form>
            </div>
        </div>
    </div>

    <img src = "../Ressources/Images/test_banderolle.webp" alt = "banderolle" id = "banderolle_img">

    <!-- Div pour les barre de rechecrhe et les essais cliniques -->
    <div id = "essai_conteneur">
        <div id='title_essai_part'>
            <h2 class = 'title'>Clinical trials completed</h2>
        </div>

        <?= AfficherBarreRecherche($search_query);?>
        <!-- Affiche tous les essais cloiniques avec des phases terminées -->
        <div id='new_essais'>
            <?php
                $essais = getEssaiTermine($bdd);
                $filtred_trail = 0;
                if (!empty($search_query)) { // Si une recherche a été effectuée
                    foreach($essais as $essai){
                        $filtred_trail = AfficherEssaisFinisRecherche($bdd, $essai, $_POST["search_query"], $filtred_trail); // Affiche les essais qui correspondent à la recherche
                    } $filtred_trail === count($essais) ? AfficherErreur("No clinical trials found", "not_found") : null;
                } else {
                    foreach($essais as $essai){
                        AfficherEssaisFinis($bdd, $essai);
                    }
                }
            ?>
            
        </div>
    </div>

</body>