<?php
include_once("Patient.php");
include_once("Medecin.php");
function AfficherErreur($message, $id = "") {
    echo '<div class="error-message", id='.$id.'>' . htmlspecialchars($message) . '</div>';
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
 * Fonction pour afficher la barre de recherche
 * @param $search_query : chaîne de caractères à afficher dans la barre de recherche
 */
function AfficherBarreRecherche($search_query) {
    $search_query = $search_query ? htmlspecialchars($search_query) : ""; // si la recherche est vide, on met une chaîne vide
    echo '<div class="search-container">';
        echo '<form action="" method="post">'; // Formulaire qui soumet à la même page
            echo '<div class="search">';
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">';
                echo '<input type="hidden" name="form_type" value="search_form">'; // utile pour savoir quel formulaire a été soumis
                echo '<input class="search__input" type="text" name="search_query" placeholder="Search" value = "'.htmlspecialchars($search_query).'">';
                echo '<button type="submit" id="search__button" class="fa fa-search"></button>';
            echo '</div>';
        echo '</form>';
    echo '</div>';   
}

/**
 * Fonction pour afficher les essais cliniques qui ne sont pas encore démarrés (1 essai = 1 ligne)
 * @param $essai : dictionnaire contenant les informations sur l'essai
 * @param $medecins : dictionnaire contenant les médecins référents
 * @param $id_essai : id de l'essai
 */
function Affichage_content_essai_pas_demarre($essai, $medecins, $id_essai) {
    echo "<div class='box_essai'>";
        echo "<div class='essai_title'>".htmlspecialchars($essai["titre"])."</div>"; // Affiche le titre de l'essai
            echo "<p id='essai_description'><b>Description :</b> <br>".htmlspecialchars($essai["description"])."</p>"; // Affiche la description de l'essai
            echo "<p><b>Expected start date : </b>".htmlspecialchars($essai["date_debut"])."</p>"; // Affiche la date de début de l'essai
            echo "<p><b>Referent doctors : </b>";
            Affiche_medecin($medecins); // Affiche les médecins référents
            echo "</p>";
            echo "<p><b>Company: </b>".htmlspecialchars($essai["nom"])."</p>"; // Affiche le nom de l'entreprise
            echo "<p>Phase ".htmlspecialchars($essai["ID_phase"])."</p>"; // Affiche la phase de l'essai
            // Colonne pour le bouton "Join"
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='id_essai' value='".htmlspecialchars($id_essai)."'>";
            echo "<input type='hidden' name='Action' value='join_trial'>";
            echo "<button type='submit' class='button' id='button_join'>Join</button>"; // Bouton pour s'inscrire
            echo "</form>";
    echo "</div>";
}




/**
 * Cette fonction vise à afficher les essais cliniques qui ne sont pas encore démarrés et qui sont encore dans leur phase de recrutement
 * Elle est commune aux médecins et aux patients
 * @param $bdd : objet de connexion à la base de données
 * @param $user : objet utilisateur (patient ou médecin)
 */
function AfficherEssaisPasDemarré($bdd, $user, $search_query = "") {
    if ($user instanceof Patient) {
        // Requête pour obtenir les essais qui n'ont pas encore démarré et auxquels le patient n'a pas postulé
        $query_essai = "SELECT  ID_essai, nom, description, titre, date_debut, ID_phase
                        FROM essai
                        JOIN utilisateur ON essai.ID_entreprise_ref = utilisateur.ID_User
                        WHERE a_debute = 0 AND ID_essai #// On récupère les essais qui n'ont pas encore démarré
                        NOT IN  
                        (SELECT ID_essai FROM resultat WHERE ID_patient = :id_patient);"; // enlève les essais où le patient a déjà postulé (empĉhe aussi qu'un patient postule à plusieurs phases)
    } else {
        // Requête pour obtenir les essais qui n'ont pas encore démarré et auxquels le médecin n'a pas postulé
        #// TODO : à modifier pour les médecins
    }
    // Requête pour obtenir les médecins référents pour chaque essai
    $query_medecins = "SELECT nom
                        FROM utilisateur
                        JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User
                        WHERE ID_essai = :id AND is_accepte = 1;";

    $essais = $bdd->getResultsAll($query_essai, ["id_patient" => $user->getIduser()]); // On récupère les essais

    if (empty($essais)){
        AfficherErreur("No clinical trials available, please come back later.");
    } else {
        echo "<div id='new_essais_container'>";
            echo "<div id='title_essai_part'>"; // div pour manipuler le titre plus facilement
                echo "<h2 class = 'title'>New clinical trials</h2>";
            echo "</div>";
        AfficherBarreRecherche($search_query); // Affiche la barre de recherche
        echo "<div id='new_essais'>"; // cadre pour les essais
        foreach ($essais as $essai) {
            $id_essai = $essai['ID_essai'];
            $medecins = $bdd->getResultsAll($query_medecins, array(":id" => $id_essai)); // On récupère les médecins
                    Affichage_content_essai_pas_demarre($essai, $medecins, $id_essai); // Affiche les essais
        }
        echo "</div>";
        echo "</div>";
                
    }
}

/**
 * FOnction pour gérer l'affichage des essais en fonction de ce qui a été rentrée dans la barre de recherche
 * @param Query $bdd: objet de connexion à la base de données
 * @param User $user: objet utilisateur (patient ou médecin)
 * @param string $search_query: chaîne de caractères à rechercher
 */
function AfficherEssaisRecherche($bdd, $user, $search_query) {
    // Requête pour rechercher dans le titre, la phase, la description ou les médecins associés
    $query = "SELECT DISTINCT e.ID_essai, u.nom, e.description, e.titre, e.date_debut, e.ID_phase
                FROM essai e
                JOIN utilisateur u ON e.ID_entreprise_ref = u.ID_User
                LEFT JOIN essai_medecin em ON e.ID_essai = em.ID_essai
                LEFT JOIN utilisateur m ON em.ID_medecin = m.ID_User
                WHERE 
                    (u.nom LIKE :search OR
                    e.titre LIKE :search OR
                    e.description LIKE :search OR
                    e.ID_phase LIKE :search OR
                    m.nom LIKE :search)
                    AND e.a_debute = 0
                    AND e.ID_essai NOT IN (SELECT ID_essai FROM resultat WHERE ID_patient = :id_patient)
    ";

    $params = [
        ':search' => '%' . $search_query . '%',
        ':id_patient' => $user->getIduser()
    ];

    $essais = $bdd->getResultsAll($query, $params);

    if (empty($essais)) {
        AfficherErreur("Error none of clinical trials correponds.");
        AfficherEssaisPasDemarré($bdd, $user);
    } else {
        echo "<div id='new_essais_container'>";
        echo "<div id='title_essai_part'>"; // div pour manipuler le titre plus facilement
            echo "<h2 class = 'title'>New clinical trials</h2>";
            echo "</div>";
        AfficherBarreRecherche($search_query); // Affiche la barre de recherche
        echo "<div id='new_essais'>"; // cadre bleu pour les essais
        foreach ($essais as $essai) {
            $id_essai = $essai['ID_essai'];
            // Récupérer les médecins référents pour chaque essai
            $query_medecins = "SELECT nom FROM utilisateur 
                                JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                                WHERE ID_essai = :id AND is_accepte = 1;";
            $medecins = $bdd->getResultsAll($query_medecins, array(":id" => $id_essai));
            Affichage_content_essai_pas_demarre($essai, $medecins, $id_essai);
        }
        echo "</div>";
        echo "</div>";
    }
}


?>