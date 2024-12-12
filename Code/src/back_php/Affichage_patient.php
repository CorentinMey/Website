<?php
include_once("Patient.php");
include_once("Affichage_gen.php"); // pour la fonction Affiche_medecin


/**
 * Fonction pour tester les arguments de la fonction Affichage_content_essai
 */
function Test_Argument($entreprise, $essai, $medecins){
    if (!is_array($entreprise) || !isset($entreprise['nom']) || !isset($entreprise['a_debute'])) {
        AfficherErreur("Beware, there is a problem with the argument type entreprise", E_USER_WARNING);
        return false;
    }

    // Vérification de $essai
    elseif (!is_array($essai) || !isset($essai['phase_res']) || !isset($essai['description']) || !array_key_exists('effet_secondaire', $essai)) {
        AfficherErreur("Beware, there is a problem with the argument type essai", E_USER_WARNING);
        return false;
    }

    // Vérification de $medecins
    elseif (!is_array($medecins)) {
        AfficherErreur("Beware, there is a problem with the argument type medecin", E_USER_WARNING);
        return false;
    }
    foreach ($medecins as $medecin) {
        if (!is_array($medecin) || !isset($medecin['nom'])) {
            AfficherErreur("Beware, there is a problem with the argument type medecin", E_USER_WARNING);
            return false;
        }
    }
    return true;

}




/**
 * Affiche l'en tête du tableau pour les essais cliniques du patients
 */
function Affichage_entete_tableau_essai(){
    echo "<div id = 'titre_container'>";
        echo "<div id ='titre_my_information'>";
            echo '<h2 class="title">My Clinical Trials</h2>';
        echo '</div>';
    echo '</div>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Company</th>';
                        echo '<th>Phase</th>';
                        echo '<th>Description</th>';
                        echo '<th>Referent Doctors</th>';
                        echo "<th>Give results</th>";
                        echo '<th>Action</th>'; // Colonne pour se désinscrire
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }


/**
 * Fonction qui affiche les lignes du tableau contenant les informations des essais cliniques auxquels le patient participe
 * N'affiche pas la ligne d'en tête
 * @param Array $entreprise : informations sur l'entreprise
 * @param Array $essai : informations sur l'essai
 * @param Array $medecins : informations sur les médecins référents
 * @param Int $id_essai : id de l'essai
 */
function Affichage_content_essai($entreprise, $essai, $medecins, $id_essai){
        $test = Test_Argument($entreprise, $essai, $medecins); // test les arguments
        if(!$test) 
            return; // si un argument est invalide, on arrête la fonction
        echo '<tr>';
            echo '<td>'.$entreprise["nom"].'</td>'; // affiche le contenu des colonnes simples
            echo '<td>Phase '.$essai["phase_res"].'</td>';
            echo '<td style="text-align :left;">'.$essai["description"].'</td>';
            echo '<td>';
            Affiche_medecin($medecins); // affiche les médecins référents
            echo '</td>';
            if (($entreprise["a_debute"] == 2) || (!empty($essai["effet_secondaire"]))){ // si la phase de l'essai est terminée et en attente des résultats
                if (!empty($essai["effet_secondaire"])) // si le patient a déjà donné ses résultats
                    echo '<td>Thanks for your feedback</td>';
                // affiche un menu déroulant pour choisir les effets secondaires avec un bouton pour valider
                else{
                    echo '<td>';
                    echo '<form action= "" method="post">';
                        echo '<input type="hidden" name="id_essai" value="'.$id_essai.'">';
                        echo '<input type="hidden" name="Action" value="submit_side_effects">'; // données cachées pour mettre à jour la BDD
                        echo '<select name="side_effects" id = "reponse_essai">'; // menu déroulant pour les effets secondaires
                            echo '<option value="None">None</option>';
                            echo '<option value="Fatigue">Fatigue</option>';
                            echo '<option value="Nausea">Nausea</option>';
                            echo '<option value="Dizziness">Dizziness</option>';
                            echo '<option value="Pain">Pain</option>';
                        echo '</select>';
                        echo '<button type="submit" class="button">Submit</button>'; // bouton pour valider
                    echo '</form>';
                echo '</td>';
                }
            } else // si l'essai est en cours
                echo '<td>Not yet over</td>';
            // ajout du bouton pour se désinscrire
            if (!empty($essai["effet_secondaire"])) // si le patient a déjà donné ses résultats
                echo '<td>Thanks for your feedback : ' . $essai["effet_secondaire"] .'</td>';
            else{
                echo '<td>';
                echo '<form action="" method="post">';
                    echo '<input type="hidden" name="id_essai" value="'.$id_essai.'">';
                    echo '<input type="hidden" name="Action" value="unsubscribe">';
                    echo '<button type="submit" class="button">Unsubscribe</button>';
                echo '</form>';
            echo '</td>';        
        }
        echo '</tr>';
}

// ========================================================================================================
// Fonctions qui gère les actions des patients sur sa page et affiche le contenu adéquat
// ========================================================================================================

/**
 * Fonction générique pour gérer la fermture des notification sur l'onglet "My clinicals trials" du patient
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $nb_notif : nombre de notifications en attente
 */
function UpdateNotification($bdd, $patient, $nb_notif){
    if (!($bdd instanceof Query) || !($patient instanceof Patient)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    if ($nb_notif > 0) 
        $patient->AfficheNotif($bdd); // Affiche les notifications restantes
    $patient->AfficheEssais($bdd);
}

/**
 * Fonction qui gère l'affichage après avoir appuyé sur le bouton Join trial
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $id_essai : id de l'essai
 */
function handleJoinTrial($bdd, $patient, $id_essai) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    AfficherConfirmation("Are you sure you want to join this trial?", $id_essai, ["confirm_join", "cancel_join"]); // $id_essai sert de marqueur pour la fonction suivante ici
    // Récupère la recherche depuis la session
    $search_query = isset($_SESSION['search_query']) ? $_SESSION['search_query'] : "";
    if ($search_query !== "") { // affiche les résultats de la recherche précédente ou non
        AfficherEssaisRecherche($bdd, $patient, $search_query);
    } else {
        AfficherEssaisPasDemarré($bdd, $patient);
    }
}

/**
 * Fonction qui gère l'affichage après avoir confirmé la participation
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $id_essai : id de l'essai
 */
function handleConfirmJoin($bdd, $patient, $id_essai) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    $patient->Rejoindre($bdd, $id_essai); // Met à jour la BDD pour rejoindre l'essai
    // Récupère la recherche depuis la session
    $search_query = isset($_SESSION['search_query']) ? $_SESSION['search_query'] : "";
    if ($search_query !== "") {
        AfficherEssaisRecherche($bdd, $patient, $search_query);
    } else {
        AfficherEssaisPasDemarré($bdd, $patient);
    }
}

/**
 * Fonction qui gère l'affichage après avoir annulé la participation
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 */
function handleCancelJoin($bdd, $patient) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    // Récupère la recherche depuis la session
    $search_query = isset($_SESSION['search_query']) ? $_SESSION['search_query'] : "";
    if ($search_query !== "") {
        AfficherEssaisRecherche($bdd, $patient, $search_query);
    } else {
        AfficherEssaisPasDemarré($bdd, $patient);
    }
}


/**
 * Fonction qui gère l'affichage après avoir appuyer sur un bouton Submit pour donner les effets secondaires
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $id_essai : id de l'essai
 * @param Int $nb_notif : nombre de notifications en attente
 */
function handleSubmitSideEffects($bdd, $patient, $id_essai, $nb_notif) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)|| !is_int($nb_notif)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
     // Stocke les effets secondaires dans la session
    isset($_POST["side_effects"]) ? $side_effects = $_POST["side_effects"] : $side_effects = "Error";
    $_SESSION["side-effects"] = $side_effects;
    AfficherConfirmation(
        "Are you sure you want to submit these side effects: " . htmlspecialchars($side_effects) . " ?",
        $id_essai,
        ["yes", "no"]
    ); // Affiche une confirmation pour valider les effets secondaires
    UpdateNotification($bdd, $patient, $nb_notif); // Réaffiche les notifications et les essais
}

/**
 * Fonction qui gère l'affichage après avoir cliquer sur le bouton de désistement d'un essai
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $id_essai : id de l'essai
 * @param Int $nb_notif : nombre de notifications en attente
 */
function handleUnsubscribe($bdd, $patient, $id_essai, $nb_notif) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)|| !is_int($nb_notif)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    AfficherConfirmation(
        "Are you sure you want to unsubscribe from this trial?",
        $id_essai,
        ["confirm_unsubscribe", "cancel_unsubscribe"]
    );
    UpdateNotification($bdd, $patient, $nb_notif);
}

/**
 * Fonction qui gère l'affichage après avoir appuyer sur un bouton Confirm après avoir appuyer sur le bouton unsubscribe
 * @param Query $bdd  : objet de connexion à la base de données
 * @param Patient $patient : objet patient
 * @param Int $id_essai : id de l'essai
 * @param Int $nb_notif : nombre de notifications en attente
 */
function handleConfirmUnsubscribe($bdd, $patient, $id_essai, $nb_notif) {
    if (!($bdd instanceof Query) || !($patient instanceof Patient)  || !is_int($nb_notif)) {
        AfficherErreur("Beware, there is a problem with the argument type", E_USER_WARNING);
        return;
    }
    $test = $patient->QuitEssai($bdd, $id_essai); // Désinscrire le patient
    if (!$test)
        AfficherInfo("You have successfully unsubscribed from this trial", $id_essai, "cross");
    UpdateNotification($bdd, $patient, $nb_notif);
}

?>