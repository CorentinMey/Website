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

    
    
    
    // code pour mettre à jour le numéro des notifications
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['id_essai']) && isset($_POST['Action'])) {
            switch ($_POST["Action"]){
                case "yes":
                    $medecin->FillSideEffects($bdd, $_SESSION["side-effects"], $_POST['id_essai']); // met à jour la BDD avec les effets secondaires
                    break;
                case "exclude":
                    $medecin->ReadNotifExclusion($bdd, $_POST['id_essai']); // met à jour la BDD et recharge la page pour enlever la notification
                    break;
                case "accept":
                    $medecin->ReadNotifAcceptation($bdd, $_POST['id_essai']); // met à jour la BDD et recharge la page pour enlever la notification
                    break;
            }

        }

    }

    ?>
 
    <form action = "" method = "post" id = "redirect_buttons">
        <!-- <div id = "redirect_buttons"> -->
            <button class = "button" id = "button_medecin" name = "Action" value = "Informations">
                Detailed informations about the trial
                <?php  // si j'ai des notifications, j'affiche le rond de notification
                $nb_notif = $medecin->NombreNotif($bdd);
                if ($nb_notif > 0)
                    echo "<span class='notification'>".htmlspecialchars($nb_notif)."</span>" // <!-- Ajoute le rond de notification -->
                ?>
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

                case "cross_inscription":
                    AfficherEssaisPasDemarré($bdd, $medecin);
                    break;
                    
                case "exclude":
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // affiche les notifications
                    $medecin->AfficheEssais($bdd);
                    break;
                case "accept":
                    $medecin->ReadNotifAcceptation($bdd, $id_essai); // idem
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
                case "submit_side_effects":
                    $_SESSION["side-effects"] = $_POST["side_effects"]; // stocke les effets secondaires dans la session
                    AfficherConfirmation("Are you sure you want to submit these side effects : ".htmlspecialchars($_POST["side_effects"]). " ?", 
                                        $id_essai, 
                                        ["yes", "no"]); // affiche une confirmation pour valider les effets secondaires
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
                case "yes":
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
                case "no":
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
                case "unsubscribe":
                    AfficherConfirmation("Are you sure you want to unsubscribe from this trial?",
                                         $id_essai, ["confirm_unsubscribe", "cancel_unsubscribe"]);
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;

                case "confirm_unsubscribe":
                    // L'utilisateur a confirmé la désinscription
                    // Appeler la fonction pour se désinscrire
                    $medecin->QuitEssai($bdd, $id_essai);
                    AfficherInfo("You have successfully unsubscribed from this trial", $id_essai, "cross");
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
                    break;
            
                case "cancel_unsubscribe":
                    if ($nb_notif > 0)
                        $medecin->AfficheNotif($bdd); // idem
                    $medecin->AfficheEssais($bdd);
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