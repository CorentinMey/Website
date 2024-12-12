<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_medecin.php");
include_once("../back_php/Medecin.php");
session_start();

if (!isset($_SESSION["medecin"])) {
    header("Location: page_login.php");
    exit;
} 

$medecin = $_SESSION["medecin"];
$bdd = new Query("siteweb");

// code pour gérer les boutons de deconnexion, de redirection vers l'historique et de retour à la page d'accueil du patient
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Action'])) {
        switch ($_POST['Action']) {
            case 'Disconnect':
                // Déconnecte l'utilisateur
                header("Location: page_deco.php");
                exit;
            case 'RevenirAccueil':
                header("Location: page_medecin.php");
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

    <title>Page du médecin</title>
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

 
    <h2 class = "title">Options</h2>
 
    <form action = "" method = "post" id = "redirect_buttons">
        <!-- <div id = "redirect_buttons"> -->
            <button class = "button" id = "button_medecin" name = "Action" value = "ViewMine">
                My clinical trials
                <?php  // si j'ai des notifications, j'affiche le rond de notification
                $nb_notif = $medecin->NombreNotif($bdd);
                if ($nb_notif > 0)
                    echo "<span class='notification'>".htmlspecialchars($nb_notif)."</span>" // <!-- Ajoute le rond de notification -->
                ?>
            </button>
            <button class = "button" id = "button_medecin" name = "Action" value = "ViewNew">New studies</button>
            <button class = "button" id = "button_medecin" name = "Action" value = "ViewInfo">My information</button>
        <!-- </div> -->
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Action"])) { // Si un bouton a été cliqué
        switch ($_POST['Action']) {
            case "ViewMine":
                if ($nb_notif > 0)
                    $medecin->AfficheNotif($bdd); // affiche les notifications
                    $medecin->AfficheEssais($bdd);
                    break;
            case "ViewNew":
                AfficherEssaisPasDemarré($bdd, $medecin, 1);
                break;
            case "ViewInfo":
                $medecin->AffichageTableau($bdd);
                break;
        }
    } else
        $medecin->AffichageTableau($bdd);
    ?>

    <?php // balise php pour gérer les boutons de notifs
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_essai']) && isset($_POST['Action'])) {
            $id_essai = $_POST['id_essai'];
            $action = $_POST['Action'];
    
            switch ($action) {
                case "join_trial":
                    $medecin->Rejoindre($bdd, $id_essai); // met à jour la BDD pour rejoindre l'essai
                    AfficherEssaisPasDemarré($bdd, $medecin);
                    break;
                case "cross": // cas ou on ferme une notif qui n'interragit pas avec la BDD
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
            
            }
        }
    }

    ?>

</body>