<?php
include_once("Query.php");
include_once("Affichage_gen.php");

/**
 * Fonction pour récupérer tous les essais cliniques terminés
 * @param $bdd : Query
 */
function getEssaiTermine($bdd){
    $query = "SELECT * FROM phase";
    $rows = $bdd->getResultsAll($query, []);
    if ($rows == []){
        AfficherErreur("No clinical trials available in the Database");
        exit;
    } // si il n'y a pas d'essais cliniques dans la base de données
    else
        return $rows;
}

/**
 * Cette fonction vise à afficher les essais cliniques qui ne sont pas encore démarrés et qui sont encore dans leur phase de recrutement
 * Elle est commune aux médecins et aux patients
 * @param $bdd : objet de connexion à la base de données
 * @param $param : dictionnaire contenant les paramètres de l'essai concerné par l'historique [id_essai, id_phase]
 */
function AfficherEssaisFinis($bdd, $param) {
    // Requête pour avoir les infos sur les essais avec au moins une phase terminée
    $query_essai = "SELECT nom, description, titre FROM essai 
                        JOIN utilisateur ON essai.ID_entreprise_ref = utilisateur.ID_User
                            WHERE ID_essai = :id_essai;";

    // Requête pour obtenir les médecins référents pour chaque essai
    $query_medecins = "SELECT nom
                        FROM utilisateur
                        JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User
                        WHERE ID_essai = :id AND is_accepte = 1;"; // TODO voir s'il faut retirer ce OR ou non (avec OR is_from_company = 1)

    $essai = $bdd->getResults($query_essai, ["id_essai" => $param["ID_essai"]]); // On récupère l"essai en question
    if (empty($essai)){
        AfficherErreur("No data for this trial ".htmlspecialchars($param["ID_essai"]).".");
    } else {
        $essai = $essai + $param; // On ajoute les paramètres de l'essai
        $medecins = $bdd->getResultsAll($query_medecins, array(":id" => $param["ID_essai"])); // On récupère les médecins
        Affichage_content_essais_finis($essai, $medecins, $param["ID_phase"]); // Affiche les essais
    }
}


/**
 * Fonction pour afficher les essais cliniques qui ne sont pas encore démarrés (1 essai = 1 ligne)
 * @param $essai : dictionnaire contenant les informations sur l'essai
 * @param $medecins : dictionnaire contenant les médecins référents
 * @param $id_essai : id de l'essai
 */
function Affichage_content_essais_finis($essai, $medecins, $id_phase) {
    echo "<div class='box_essai'>";
        echo "<div class='essai_title'><b>".htmlspecialchars($essai["titre"])."</b></div>"; // Affiche le titre de l'essai
        echo "<p id='essai_description'><b>Description :</b> <br>".htmlspecialchars($essai["description"])."</p>"; // Affiche la description de l'essai
        echo "<p><b>Concerned Phase : </b>".htmlspecialchars($id_phase)."</p>"; // Affiche la phase de l'essai
        echo "<p><b>Phase start date : </b>".htmlspecialchars($essai["date_debut"])."</p>"; // Affiche la date de début de l'essai
        echo "<p><b>Phase end date : </b>".htmlspecialchars($essai["date_fin_prevue"])."</p>"; // Affiche la date de fin de l'essai
        echo "<p><b>Referent doctors : </b>";
            Affiche_medecin($medecins); // Affiche les médecins référents
        echo "</p>";
        echo "<p><b>Company: </b>".htmlspecialchars($essai["nom"])."</p>"; // Affiche le nom de l'entreprise
    echo "</div>";
}

/**
 * Fonction pour afficher les essais cliniques qui ne sont pas encore démarrés (1 essai = 1 ligne) en fonction de la recherche
 * @param Query $bdd : objet de connexion à la base de données
 * @param array $param : dictionnaire contenant les paramètres de l'essai concerné par l'historique [id_essai, id_phase]
 * @param string $search_query : chaîne de caractères à rechercher
 */
function AfficherEssaisFinisRecherche($bdd, $param, $search_query, $cpt) {
    // Requête pour rechercher dans le titre, la phase, la description ou les médecins associés
    $query = "SELECT DISTINCT u.nom, e.description, e.titre
                FROM essai e
                JOIN utilisateur u ON e.ID_entreprise_ref = u.ID_User
                LEFT JOIN essai_medecin em ON e.ID_essai = em.ID_essai
                LEFT JOIN utilisateur m ON em.ID_medecin = m.ID_User
                WHERE 
                    (u.nom LIKE :search OR
                    e.titre LIKE :search OR
                    e.description LIKE :search OR
                    e.ID_phase LIKE :search OR
                    m.nom LIKE :search) AND
                    e.ID_essai = :id_essai;";
    $params = [
        ':search' => '%' . $search_query . '%',
        ':id_essai' => $param["ID_essai"]
    ];

    $essais = $bdd->getResults($query, $params);
    if (empty($essais)) {
        return $cpt+1;
    } else {
        $essai = $essais + $param;
        // Récupérer les médecins référents pour chaque essai
        $query_medecins = "SELECT nom FROM utilisateur 
                            JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                            WHERE ID_essai = :id AND is_accepte = 1;";
        $medecins = $bdd->getResultsAll($query_medecins, array(":id" => $essai["ID_essai"]));
        Affichage_content_essais_finis($essai, $medecins, $essai["ID_essai"]);
        return $cpt;
    }
}





?>