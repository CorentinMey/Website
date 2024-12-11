<?php
include_once("Patient.php");
include_once("Medecin.php");
function AfficherErreur($message) {
    echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
}

/**
 * Fonction qui affiche une notification avec ou sans bouton pour la fermer
 * @param $message : message à afficher
 * @param $id_essai : id de l'essai concerné si besoin
 * @param $action : action à effectuer si besoin
 * @param $close_notif : booléen pour savoir si on affiche un bouton pour fermer la notification ou non
 */
function AfficherInfo($message, $id_essai, $action, $close_notif = true) {
    echo '<div class="info-message" id="notif">';
        echo htmlspecialchars($message);
        // ajout button pour fermer la notification
        if ($close_notif) {
        echo "<form action='' method='post'>";
            echo '<input type="hidden" name="id_essai" value="'.htmlspecialchars($id_essai).'">'; // utiles pour gérer les actions en fonction des id des essais
            echo '<input type="hidden" name="Action" value="'.htmlspecialchars($action).'">';
            echo '<button type="submit" class="button" id="close_notif">X</button>';
        echo "</form>";
        }
    echo '</div>';
}

/**
 * FOnction qui afficher une boite de confirmation
 * @param $message : message à afficher
 * @param $id_essai : id de l'essai concerné
 * @param $action : tableau contenant les actions à effectuer (exemple : oui, non)
 */
function AfficherConfirmation($message, $id_essai, $action) {
    echo '<div class="confirm-message" id ="confirmation_side_effect">';
    echo htmlspecialchars($message);
    // ajout bouton pour confirmer les choix
    echo "<form action='' method='post'>";
        echo '<input type="hidden" name="id_essai" value="'.htmlspecialchars($id_essai).'">'; // utiles pour gérer les actions en fonction des id des essais
        echo '<button type="submit" class="button" id="side_effect_buttons" name="Action" value="'.htmlspecialchars($action[0]).'">Yes</button>';
        echo '<button type="submit" class="button" id="side_effect_buttons" name="Action" value="'.htmlspecialchars($action[1]).'">No</button>';
    echo "</form>";
    echo '</div>';
}

/**
 * FOnction pour afficher les médecins référents d'un essai clinique
 * @param $medecins : liste contenant les médecins référents
 */
function Affiche_medecin($medecins){
    $cpt = 0;
    foreach($medecins as $medecin){ // affiche les médecins référents
        if ($cpt == 0){
            echo $medecin["nom"];
            $cpt++;
        } else
            echo ', '.$medecin["nom"];
    }
}

/**
 * Fonction pour afficher les essais cliniques qui ne sont pas encore démarrés (1 essai = 1 ligne)
 * @param $essai : dictionnaire contenant les informations sur l'essai
 * @param $medecins : dictionnaire contenant les médecins référents
 * @param $id_essai : id de l'essai
 */
function Affichage_content_essai_pas_demarre($essai, $medecins, $id_essai, $is_med) {
    echo '<tr>';
        echo '<td>' . htmlspecialchars($essai["nom"]) . '</td>'; // Affiche le nom de l'entreprise
        echo '<td>Phase ' . htmlspecialchars($essai["ID_phase"]) . '</td>'; // Affiche la phase de l'essai
        echo '<td>' . htmlspecialchars($essai["description"]) . '</td>'; // Affiche la description de l'essai
        echo '<td>' . htmlspecialchars($essai["date_debut"]) . '</td>'; // Affiche la date de début de l'essai
        echo '<td>';
            Affiche_medecin($medecins); // Affiche les médecins référents
        echo '</td>';
        if ($is_med == 1){
            echo '<td>' . htmlspecialchars($essai["mail"]) . '</td>'; // Affiche la description de l'essai
            echo '<td>' . htmlspecialchars($essai["molecule_test"]) . '</td>'; // Affiche la date de début de l'essai
            echo '<td>' . htmlspecialchars($essai["molecule_ref"]) . '</td>'; // Affiche la description de l'essai   
        }
        // Colonne pour le bouton "Join"
        echo '<td>';
            echo '<form action="" method="post">';
                echo '<input type="hidden" name="id_essai" value="' . htmlspecialchars($id_essai) . '">';
                echo '<input type="hidden" name="Action" value="join_trial">';
                echo '<button type="submit" class="button">Join</button>'; // Bouton pour s'inscrire à l'essai
            echo '</form>';
        echo '</td>';
    echo '</tr>';
}


/**
 * Cette fonction vise à afficher les essais cliniques qui ne sont pas encore démarrés et qui sont encore dans leur phase de recrutement
 * Elle est commune aux médecins et aux patients
 * @param $bdd : objet de connexion à la base de données
 * @param $user : objet utilisateur (patient ou médecin)
 */
function AfficherEssaisPasDemarré($bdd, $user){
    if ($user instanceof Patient) {
        // Requête pour obtenir les essais qui n'ont pas encore démarré et auxquels le patient n'a pas postulé
        $query_essai = "SELECT  ID_essai, nom, description, date_debut, ID_phase
                        FROM essai
                        JOIN utilisateur ON essai.ID_entreprise_ref = utilisateur.ID_User
                        WHERE a_debute = 0 AND ID_essai #// On récupère les essais qui n'ont pas encore démarré
                        NOT IN  
                        (SELECT ID_essai FROM resultat WHERE ID_patient = :id_patient);"; // enlève les essais où le patient a déjà postulé (empĉhe aussi qu'un patient postule à plusieurs phases)
    $is_med = 0; //On mémorise que c'est un patient qui a demandé à voir les essais non démarrés
    } else {
        // Requête pour obtenir les essais qui n'ont pas encore démarré et auxquels le médecin n'a pas postulé
        $query_essai = "SELECT  ID_essai, nom, description, date_debut, ID_phase, mail, molecule_test, molecule_ref
                        FROM essai
                        JOIN utilisateur ON essai.ID_entreprise_ref = utilisateur.ID_User
                        WHERE a_debute = 0 AND ID_essai #// On récupère les essais qui n'ont pas encore démarré
                        NOT IN 
                        (SELECT ID_essai FROM essai_medecin WHERE ID_medecin = :id_patient);"; // enlève les essais où le médecin a déjà postulé 
        $is_med = 1; //On mémorise que c'est un médecin qui a demandé à voir les essais non démarrés
    }
    // Requête pour obtenir les médecins référents pour chaque essai
    $query_medecins = "SELECT nom
                       FROM utilisateur
                       JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User
                       WHERE ID_essai = :id AND (is_accepte = 1 OR est_de_company = 1);";

    $essais = $bdd->getResultsAll($query_essai, ["id_patient" => $user->getIduser()]); // On récupère les essais

    if (empty($essais)){
        AfficherErreur("No clinical trials available, please come back later.");
    } else {
        echo "<div id ='tableau_inscription_essai'>";
        echo '<h2 class="title">New studies</h2>';
        echo '<table class="styled-table" id="table_essai" >';
        echo '<thead>';
            echo '<th>Company</th>';
            echo '<th>Phase</th>';
            echo '<th>Description</th>';
            echo '<th>Start Date</th>';
            echo '<th>Doctors</th>';
            if ($is_med == 1){
                echo '<th>Mail Company</th>';
                echo '<th>Test Molecule</th>';
                echo '<th>Reference Molecule</th>';    
            }
            echo '<th>Action</th>'; // Colonne pour le bouton "Join"
        echo '</thead>';

        foreach ($essais as $essai) {
            $id_essai = $essai['ID_essai'];
            $medecins = $bdd->getResultsAll($query_medecins, array(":id" => $id_essai)); // On récupère les médecins
            Affichage_content_essai_pas_demarre($essai, $medecins, $id_essai, $is_med);
        }

        echo '</table>';
        echo '</div>';
    }
}


?>