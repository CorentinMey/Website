<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_patient.php");
include_once("../back_php/Patient.php");
session_start();

if (!isset($_SESSION["patient"])) { // si quelqu'un essaie d'accéder à la page sans être connecté on le redirige vers la page de connexion
    header("Location: page_login.php");
    exit;
}

$patient = $_SESSION["patient"];
$bdd = new Query("siteweb");

// code pour gérer les boutons de daconexion, de redirection vers l'historique et de retour à la page d'accueil du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Action'])) {
        switch ($_POST['Action']) {
            case 'Disconnect':
                // Déconnecte l'utilisateur
                header("Location: page_deco.php");
                exit;
            case 'RevenirAccueil':
                header("Location: page_patient.php");
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
    <title>Page du patient</title>
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

 
    <h2 class = "title" id ="titre_bouton_principal">Options</h2>

    <?php // code pour mettre à jour le numéro des notifications, doit être placé avant l'affichage des boutons
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_essai']) && isset($_POST['Action'])) {
            switch ($_POST["Action"]){
                case "yes": // cas ou on valide les effets secondaires
                    $patient->FillSideEffects($bdd, $_SESSION["side-effects"], $_POST['id_essai']); // met à jour la BDD avec les effets secondaires
                    break;
                case "exclude": // cas où l'on ferme une notification d'exclusion
                    $patient->ReadNotifExclusion($bdd, $_POST['id_essai']); // met à jour la BDD et recharge la page pour enlever la notification
                    break;
                case "accept": // cas où l'on ferme une notification d'acceptation de participation à un essai clinique
                    $patient->ReadNotifAcceptation($bdd, $_POST['id_essai']); // met à jour la BDD et recharge la page pour enlever la notification
                    break;
            }
        }
    }
    ?> 
    <!-- Code dédié au placement des boutons pour gérer les menus de la page -->
    <form action = "" method = "post" id = "redirect_buttons">
            <button class = "button" id = "button_patient" name = "Action" value = "ViewMine">
                My clinical trials
                <?php  // si j'ai des notifications, j'affiche le rond de notification
                $nb_notif = $patient->NombreNotif($bdd);
                if ($nb_notif > 0)
                    echo "<span class='notification'>".htmlspecialchars($nb_notif)."</span>" // <!-- Ajoute le rond de notification -->
                ?>
            </button>
            <button class = "button" id = "button_patient" name = "Action" value = "ViewNew">New studies</button>
            <button class = "button" id = "button_patient" name = "Action" value = "ViewInfo">My information</button>
    </form>

    <!-- ===================================================================================================================== -->
                        <!-- Suite de balises php pour gérer les entrées de l'utilisateur -->
    <!-- ===================================================================================================================== -->

    <?php // balise php pour gérer les différentes menus de la page patient
    $current_view = "default"; // variable pour savoir quelle vue afficher
    $action_handled = false; // variable pour savoir si une action a été effectuée

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Action"])) { // Si un bouton a été cliqué
        switch ($_POST['Action']) {
            case "ViewMine": // si on clique sur le bouton pour voir les essais cliniques en cours du patient
                $current_view = "ViewMine";
                UpdateNotification($bdd, $patient, $nb_notif);
                break;
            case "ViewNew": // si on clique sur le bouton pour voir les nouveaux essais cliniques disponibles
                $current_view = "ViewNew";
                break;
            case "ViewInfo": // si on clique sur le bouton pour voir les informations personnelles
                $current_view = "ViewInfo";
                $patient->AffichageTableauInfoPerso($bdd);
            break;
        }
    } 
    ?>
    <?php // code pour gérer les actions des boutons de la page patient
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_essai']) && isset($_POST['Action'])) {
            $id_essai = $_POST['id_essai'];
            $action = $_POST['Action'];
    
            switch ($action) {
                case "join_trial": // si on appuie sur le bouton pour rejoindre une essai clinique
                    handleJoinTrial($bdd, $patient, $id_essai);
                    $action_handled = true; // empĉhe la balise php suivante de s'éxécuter pour éviter d'afficher en doublon
                    break;
                case "confirm_join": // si on appuie sur le bouton pour confirmer la participation à un essai clinique
                    handleConfirmJoin($bdd, $patient, $id_essai);
                    $action_handled = true;
                    break;
                case "cancel_join": // si on appuie sur le bouton pour annuler la participation à un essai clinique
                    handleCancelJoin($bdd, $patient);
                    $action_handled = true;
                    break;
                case "submit_side_effects": // si on appuie sur le bouton pour soumettre les effets secondaires à la fin d'un essai clinique
                    handleSubmitSideEffects($bdd, $patient, $id_essai, $nb_notif);
                    $action_handled = true;
                    break;
                case "unsubscribe": // si on appuie sur le bouton pour quitter un essai clinique
                    handleUnsubscribe($bdd, $patient, $id_essai, $nb_notif);
                    $action_handled = true;
                    break;
                case "confirm_unsubscribe":
                    handleConfirmUnsubscribe($bdd, $patient, $id_essai, $nb_notif);
                    $action_handled = true;
                    break;

                case "exclude": // cas où l'on ferme une notification d'exclusion
                case "accept": // cas où l'on ferme une notification d'acceptation de participation à un essai clinique
                case "yes": // si on appuie sur le bouton pour confirmer les effets secondaires
                case "no": // si on appuie sur le bouton pour annuler la soumission des effets secondaires
                case "cancel_unsubscribe": // si on appuie sur le bouton pour annuler la désinscription
                case "cross": // cas où on ferme une notification qui n'interagit pas avec la BDD
                    UpdateNotification($bdd, $patient, $nb_notif);
                    $action_handled = true;
                    break;
                }
            }
        }
    ?>
    <?php 
        // Gère la barre de recherche seulement si aucune autre action n'a été traitée
        if (!$action_handled) {
            if ($current_view == "ViewNew" || $current_view == "default") {
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_query"])) { // Si une recherche a été effectuée
                    $search_query = $_POST["search_query"];
                    $_SESSION["search_query"] = $search_query; // Stocke la recherche en session
                    $search_query = htmlspecialchars($search_query);
                    AfficherEssaisRecherche($bdd, $patient, $search_query);
                } else {
                    // Vérifie si une recherche est stockée en session
                    if (isset($_SESSION["search_query"]) && $_SESSION["search_query"] !== "") {
                        $search_query = htmlspecialchars($_SESSION["search_query"]);
                        AfficherEssaisRecherche($bdd, $patient, $search_query);
                    } else
                        AfficherEssaisPasDemarré($bdd, $patient);
                }
            }
        }
    ?>

</body>