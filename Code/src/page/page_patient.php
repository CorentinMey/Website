<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_patient.php");
include_once("../back_php/Patient.php");
session_start();

if (!isset($_SESSION["patient"])) {
    header("Location: page_login.php");
    exit;
}

$patient = $_SESSION["patient"];
$bdd = new Query("siteweb");
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
 
    <form action = "" method = "post" id = "redirect_buttons">
        <!-- <div id = "redirect_buttons"> -->
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
        <!-- </div> -->
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Action"])) { // Si un bouton a été cliqué
        switch ($_POST['Action']) {
            case "ViewMine":
                if ($nb_notif > 0)
                    $patient->AfficheNotif($bdd); // affiche les notifications
                $patient->AfficheEssais($bdd);
                break;
            case "ViewNew":
                echo "test new";
                break;
            case "ViewInfo":
                $patient->AffichageTableau($bdd);
                break;
        }
    } else
        $patient->AffichageTableau($bdd);
    ?>

    <?php // balsie php pour gérer les boutons de notifs
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_essai']) && isset($_POST['Action'])) {
            $id_essai = $_POST['id_essai'];
            $action = $_POST['Action'];
    
            switch ($action) {
                case "give_feedback":
                    // Appeler la fonction pour donner des résultats
                    // Exemple : $patient->GiveFeedback($bdd, $id_essai);
                    break;
                case "exclude":
                    $patient->ReadNotifExclusion($bdd, $id_essai); // met à jour la BDD et recharge la page pour enlever la notification
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // affiche les notifications
                    $patient->AfficheEssais($bdd);
                    break;
                case "accept":
                    $patient->ReadNotifAcceptation($bdd, $id_essai); // idem
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    break;
                case "submit_side_effects":
                    AfficherConfirmation("Are you sure you want to submit these side effects?", 
                                        $id_essai, 
                                        ["yes", "no"]); // affiche une confirmation pour valider les effets secondaires
                    $_SESSION["side-effects"] = $_POST["side_effects"]; // stocke les effets secondaires dans la session
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    break;
                case "yes":
                    $patient->FillSideEffects($bdd, $_SESSION["side-effects"], $id_essai); // met à jour la BDD avec les effets secondaires
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    break;
                case "no":
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    break;
                case "unsubscribe":
                    AfficherConfirmation("Are you sure you want to unsubscribe from this trial?",
                                         $id_essai, ["confirm_unsubscribe", "cancel_unsubscribe"]);
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    exit;

                case "confirm_unsubscribe":
                    // L'utilisateur a confirmé la désinscription
                    // Appeler la fonction pour se désinscrire
                    $patient->UnsubscribeFromTrial($bdd, $id_essai);
                    $_SESSION['message'] = "You have successfully unsubscribed from the trial.";
                    // Rediriger vers la même page pour éviter la resoumission du formulaire
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
            
                case "cancel_unsubscribe":
                    if ($nb_notif > 0)
                        $patient->AfficheNotif($bdd); // idem
                    $patient->AfficheEssais($bdd);
                    break;
            }
        }
    }

    ?>

</body>