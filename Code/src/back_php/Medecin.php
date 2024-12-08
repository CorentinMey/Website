<?php
include_once("Utilisateur.php");
include_once("Securite.php");
include_once("Affichage_medecin.php");
include_once("Affichage_gen.php");
class Medecin extends Utilisateur{

    protected $speciality;
    protected $order_number;
    protected $hospital;

    public function __construct($mdp, $email, $iduser = null, $last_name = null, $is_banned = null, $is_admin = null, $first_name = null, $birthdate = null, $gender = null, $antecedent = null, $origins = null, $speciality =null, $order_number =null, $hospital = null) {
        // Appeler le constructeur de la classe parent (Utilisateur)
        parent::__construct($iduser, $mdp, $email, $last_name, $is_banned, $is_admin, $first_name, $birthdate, $gender, $antecedent, $origins);

        // Initialiser les propriétés spécifiques au médecin
        $this->speciality = $speciality;
        $this->order_number = $order_number;
        $this->hospital = $hospital;
    }


    public function Inscription($bdd, $dict_information)
    {
        parent::Inscription($bdd, $dict_information); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
        //Liste des clés spécifiques au médecin
        $med_keys = ["numero_ordre", "domaine", "hopital"];

        // Filtrer le tableau pour récupérer les clés spécifiques au médecin
        $med_dict = array_filter(
            $dict_information,
            function ($key) use ($med_keys) {
                return in_array($key, $med_keys); // Inclut les clés présentes dans $med_keys
            },
            ARRAY_FILTER_USE_KEY
        );

        // Extraire les colonnes et leurs valeurs
        $med_columns = array_keys($med_dict); // Récupère les noms des colonnes
        $med_values = array_values($med_dict); // Récupère les valeurs à insérer
    
        // Générer la partie de la requête SQL
        $med_column_names = implode(", ", $med_columns); // Concatène les noms des colonnes avec des virgules
        $med_placeholders = implode(", ", array_fill(0, count($med_columns), "?")); // Génère un placeholder "?" pour chaque colonne

        // Construire la requête d'insertion
        $query = "INSERT INTO medecin ($med_column_names) VALUES ($med_placeholders)";
    
        try {
            // Préparer la requête
            $res = $bdd->getConnection()->prepare($query);
    
            // Exécuter la requête avec les valeurs
            $res->execute($med_values);
            
            // Retourner le résultat ou true pour indiquer que tout s'est bien passé
            $_SESSION["result"] = 1;
            return;
        } catch (PDOException $e) {
            // Gérer les erreurs
            $_SESSION["result"] = "Erreur lors de l'inscription : " . $e->getMessage();
            return;
        }
    }
    
    public function Connexion($email, $password, $bdd)
    {
        parent::Connexion($email, $password, $bdd); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
    
        $data = ["id_user" => $this->iduser];
        // Récupérer les informations spécifiques à l'utilisateur
        $query = "SELECT * FROM utilisateur WHERE ID_User = :id_user;";
        $res = $bdd->getResults($query, $data);
        if ($res != []){
            $this->birthdate = $res["date_naissance"];
            $this->first_name = $res["prenom"];
            $this->last_name = $res["nom"];
            $this->is_banned = $res["is_bannis"];
            $this->is_admin = $res["is_admin"];
            $this->gender = $res["genre"];
        }

        // Récupérer les informations spécifiques au médecin
        $query = "SELECT * FROM medecin WHERE numero_ordre = :id_user;";
        $res = $bdd->getResults($query, $data);
        if ($res != []){
            $this->speciality = $res["numero_ordre"];
            $this->order_number = $res["domaine"];
            $this->hospital = $res["hopital"];
        }
        $bdd->closeBD();
    }

    public function Deconnect()
    {
        parent::Deconnect(); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
    }

    /**
     * Méthode pour afficher les informations du médecin dans un tableau.
     * 
     */
    public function AffichageTableau(){
        echo '<h2 class = "title">My Information</h2>';
        echo '<div id = "personnal_data">';
            echo '<table class = "styled-table" id = "table_patient">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>First name</th>';
                        echo '<th>Family name</th>';
                        echo '<th>Birthday</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Email</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td>'.$this->getFirst_name().'</td>';
                        echo '<td>'.$this->getLast_name().'</td>';
                        echo '<td>'.$this->getBirthdate().'</td>';
                        echo '<td>'.$this->getGender()."</td>";
                        echo '<td>'.$this->getEmail().'</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
            echo '<a href="page_signin.php" id = "edit_option">Edit my infos</a>';
        echo '</div>';
    }

    /**
     * Méthode pour afficher les informations d'un patient dans un tableau.
     * 
     */
    public function AffichageTableau_patient($bdd, $id_user){

        $query = "SELECT nom, prenom, genre, date_naissance, antecedents, mail, traitement, dose, effet_secondaire, evolution_symptome FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_User = :id;";
        $res1 = $bdd->getResultsAll($query, ["id" => $id_user]);
        if ($res1 == []) {
            AfficherErreur("No informations found. Please contact the admin.");
            return;
        }
        //Tableau pour les infos générales
        foreach($res1 as $res){ // affiche les lignes du tableau
        // Stocker les données du patient dans la session
        $_SESSION['patient_infos'] = $res;
        echo '<h2 class = "title">Informations about the patient</h2>';
        echo '<div id = "personnal_data">';
            echo '<table class = "styled-table" id = "table_patient">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>First name</th>';
                        echo '<th>Family name</th>';
                        echo '<th>Birthday</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Email</th>';
                        echo '<th>Medical background</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td>'.$res["nom"].'</td>';
                        echo '<td>'.$res["prenom"].'</td>';  
                        echo '<td>'.$res["date_naissance"].'</td>';
                        echo '<td>'.$res["genre"].'</td>';
                        echo '<td>'.$res["mail"].'</td>';
                        echo '<td>'.$res["antecedents"].'</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
            echo '<a href="page_modif_medecin.php" id = "edit_option">Edit infos</a>';
        echo '</div>';
        // Tableau pour le traitement
        echo '<h2 class = "title">Informations about the treatment</h2>';
        echo '<div id = "personnal_data">';
            echo '<table class = "styled-table" id = "table_patient">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Treatment</th>';
                        echo '<th>Dose</th>';
                        echo '<th>Side effect</th>';
                        echo '<th>Symptoms evolution</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td>'.$res["traitement"].'</td>';
                        echo '<td>'.$res["dose"].'</td>';
                        echo '<td>'.$res["effet_secondaire"].'</td>';
                        echo '<td>'.$res["evolution_symptome"].'</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
            echo '<a href="page_modif_medecin.php" id = "edit_option">Edit infos</a>';
        echo '</div>';
    }
}

    /**
     * Méthode qui affiche un résumé des essais cliniques auxquels le médecin participe.
     */
    public function AfficheEssais($bdd){
        $query = "SELECT ID_essai, date_debut, date_fin, description, ID_phase, is_accepte FROM medecin JOIN utilisateur ON 
                    medecin.numero_ordre = utilisateur.ID_User NATURAL JOIN essai_medecin NATURAL JOIN essai
                        WHERE numero_ordre = :id
                        AND essai_medecin.ID_medecin = :id";
        // requete pour avoir le nom de l'entreprise
        $query2 = "SELECT nom, a_debute FROM utilisateur JOIN essai ON essai.ID_entreprise_ref = utilisateur.ID_User 
                        WHERE ID_essai = :id;";
        // requete pour avoir le nom des medecins referents
        $query3 = "SELECT nom FROM utilisateur JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                        WHERE ID_essai = :id;"; 
    
        $res = $bdd->getResultsAll($query, ["id" => $this->getIduser()]);
        if ($res == []) {
            AfficherErreur("No clinical trials found yet. Please subscribe to some trials.");
            return;
        }
        Affichage_entete_tableau_essai_med(); // affiche l'en-tête du tableau
    
        foreach($res as $essai){ // affiche les lignes du tableau
            $id_essai = $essai["ID_essai"];
            $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
            $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
            Affichage_content_essai_med($entreprise, $essai, $medecins, $id_essai);
        }         
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';
}

    /**
     * Méthode qui affiche l'ensemble des infos d'un essai clinique auquel le médecin participe. Nécessite de rentrer en argument l'ID de l'essai concerné.
     */
    public function AfficheEssais_full($bdd, $essai_id){
        $query = "SELECT DISTINCT ID_essai, date_debut, date_fin, description, ID_phase, molecule_test, 
                        dosage_test, molecule_ref, dosage_ref, placebo_nom, is_accepte FROM medecin JOIN utilisateur ON 
                    medecin.numero_ordre = utilisateur.ID_User NATURAL JOIN essai_medecin NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND is_accepte != 0;";
        // requete pour avoir le nom de l'entreprise
        $query2 = "SELECT nom, a_debute FROM utilisateur JOIN essai ON essai.ID_entreprise_ref = utilisateur.ID_User 
                        WHERE ID_essai = :id;";
        // requete pour avoir le nom des medecins referents
        $query3 = "SELECT nom FROM utilisateur JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                        WHERE ID_essai = :id;"; 
    
        $res = $bdd->getResultsAll($query, ["id" => $essai_id]);
        if ($res == []) {
            AfficherErreur("No clinical trials found yet. Please subscribe to some trials.");
            return;
        }
        #Affichage d'un premier tableau
        Affichage_entete_tableau_essai_med2(); // affiche l'en-tête du tableau
    
        foreach($res as $essai){ // affiche les lignes du tableau
            $id_essai = $essai["ID_essai"];
            $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
            $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
            Affichage_content_essai_med2($entreprise, $essai, $medecins, $id_essai);
        }         
                 
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';

}

    /**
     * Méthode qui affiche les participants d'un essai clinique auquel le médecin est en attente de participation.
     */
    public function AfficherParticipants($bdd, $id_essai){
        // requete pour avoir les patients admis
        $query = "SELECT ID_User, nom, prenom, genre, date_naissance, antecedents, is_accepte FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND is_accepte != 0;";
        // requete pour avoir les patients en attente d'admission
        $query2 = "SELECT ID_User, nom, prenom FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND is_accepte = 0;";
    
        $res = $bdd->getResultsAll($query, ["id" => $id_essai]);
        if ($res == []) {
            AfficherErreur("No participants found yet. Please accept some patients.");
            return;
        }

        $res2 = $bdd->getResultsAll($query2, ["id" => $id_essai]);
        if ($res == []) {
            AfficherErreur("No patients found yet. Please wait until patients ask to join the trial.");
            return;
        }
        //Affichage des patients admis
        Affichage_entete_tableau_participants(); // affiche l'en-tête du tableau
    
        foreach($res as $participant){ // affiche les lignes du tableau
            Affichage_content_participants($participant, $id_essai);
        }         
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';

        // Affichage des patients en attente
        Affichage_entete_tableau_patients(); // affiche l'en-tête du tableau
    
        foreach($res2 as $participant){ // affiche les lignes du tableau
            Affichage_content_participants($participant, $id_essai);
        }         
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';
        
}

/**
 * Méthode pour mettre à jour les informations du patient dans la base de données.
 * @param object $bdd L'objet de connexion à la base de données
 * @param int $id_user L'identifiant unique de l'utilisateur
 * @param array $data Les informations du formulaire à mettre à jour
 */
public function ChangeInfo_patient($bdd, $id_user, $id_essai, $data) {
    $query = "UPDATE utilisateur 
              SET prenom = :prenom,
                  nom = :nom,
                  mail = :email,
                  genre = :genre,
                  antecedents = :antecedents
              WHERE ID_User = :id_user";

    $query2 = "UPDATE resultat 
               SET traitement = :traitement,
                   dose = :dose,
                   effet_secondaire = :effet_secondaire,
                   evolution_symptome = :evolution_symptome
               WHERE ID_patient = :id_user
               AND ID_essai = :id_essai";

    // Préparer les paramètres pour les requêtes
    $params_user = [
        ':prenom' => $data['prenom'],
        ':nom' => $data['nom'],
        ':email' => $data['email'],
        ':genre' => $data['genre'],
        ':antecedents' => $data['antecedents'],
        ':id_user' => $id_user
    ];

    $params_resultat = [
        ':traitement' => $data['traitement'],
        ':dose' => $data['dose'],
        ':effet_secondaire' => $data['effet_secondaire'],
        ':evolution_symptome' => $data['evolution_symptome'],
        ':id_user' => $id_user,
        ':id_essai' => $id_essai
    ];

    // Exécution des requêtes
    try {
        $bdd->updateLines($query, $params_user);
        $bdd->updateLines($query2, $params_resultat);
    } catch (Exception $e) {
        echo "Erreur lors de la mise à jour des informations : " . $e->getMessage();
    }
}



    /**
     * Fonction qui renvoie le nombre de notifications à voir du patient
     * Notif : Si un essai que le patient a rejoint a été terminé, il doit donner des résultats
     *         Si un essai le patient a été exclus d'un essai
     *         Si un essai le patient a été accepté dans un essai
     * @param $bdd : base de données
     * @return int : nombre de notifications
     */
    public function NombreNotif($bdd){
        $query_notif1_cpt = "SELECT COUNT(*) FROM resultat NATURAL JOIN essai # notif si on a des résultats à donner
                             WHERE ID_patient = :id 
                             AND a_debute = 2 
                             AND is_patient_exclus = 0
                             AND is_accepte != 0    
                             AND effet_secondaire IS NULL;"; // vérifie si le patient n'a pas déjà donné ses résultats
        $query_notif2 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_patient_exclus = 1;"; // notif si on a été exclus
        $query_notif3 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_accepte = 1;"; // notif si on a été accepté

        $res1 = $bdd->getResults($query_notif1_cpt, ["id" => $this->getIduser()])["COUNT(*)"]; // compte le nombre d'essais terminés
        $res2 = $bdd->getResults($query_notif2, ["id" => $this->getIduser()])["COUNT(*)"];
        $res3 = $bdd->getResults($query_notif3, ["id" => $this->getIduser()])["COUNT(*)"];
        $total = $res1 + $res2 + $res3;
        return $total;
    }

    public function AfficheNotif($bdd){
        $query_notif1 = "SELECT ID_essai, description  FROM resultat NATURAL JOIN essai 
                            WHERE ID_patient = :id 
                            AND a_debute = 2
                            AND effet_secondaire IS NULL;";

        $query_notif2 = "SELECT ID_essai, description FROM resultat NATURAL JOIN essai
                             WHERE ID_patient = :id AND is_patient_exclus = 1;";
        $query_notif3 = "SELECT ID_essai, description FROM resultat NATURAL JOIN essai
                             WHERE ID_patient = :id AND is_accepte = 1;";
        // récupération des informations des essais
        $res1 = $bdd->getResultsAll($query_notif1, ["id" => $this->getIduser()]);
        $res2 = $bdd->getResultsAll($query_notif2, ["id" => $this->getIduser()]);
        $res3 = $bdd->getResultsAll($query_notif3, ["id" => $this->getIduser()]);
        //affichage des notifications
        foreach($res1 as $notif_essai_fini){
            AfficherInfo("The trial number : ".htmlspecialchars($notif_essai_fini["ID_essai"])." has ended with the description : ".htmlspecialchars($notif_essai_fini["description"])." Please give your feedback.", 
                        $notif_essai_fini["ID_essai"], 
                        "cross",
                        false); // ici cette notification ne peut pas disparaitre tant que l'utilisateur n'a pas donné ses résultats
        }
        foreach($res2 as $notif_exclu){
            AfficherInfo("You have been excluded from the trial number : ".htmlspecialchars($notif_exclu["ID_essai"])." with the description : ".htmlspecialchars($notif_exclu["description"]), 
                        $notif_exclu["ID_essai"],  
                        "exclude");
        }
        foreach($res3 as $notif_accepte){
            AfficherInfo("You have been accepted in the trial number : ".htmlspecialchars($notif_accepte["ID_essai"])." with the description : ".htmlspecialchars($notif_accepte["description"]), 
                        $notif_accepte["ID_essai"], 
                        "accept");
        }

    }   
    /**
     * Méthode pour mettre à jour la BDD si un patient est exclus d'un essai (passe en statut "a vu l'exclusion")
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     */
    public function ReadNotifExclusion($bdd, $id_essai){
        $query = "UPDATE resultat SET is_patient_exclus = 2 WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $bdd->updateLines($query, ["id" => $this->getIduser(), "id_essai" => $id_essai]);
    }

    /**
     * Méthode pour mettre à jour la BDD si un patient est accepté dans un essai (passe en statut "a vu l'acceptation")
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     */
    public function ReadNotifAcceptation($bdd, $id_essai){
        $query = "UPDATE resultat SET is_accepte = 2 WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $bdd->updateLines($query, ["id" => $this->getIduser(), "id_essai" => $id_essai]);  
    }

    /**
     * Méthode pour mettre à jour la BDD si un patient donne ses résultats
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     * @param $value : valeur des effets secondaires
     */
    public function FillSideEffects($bdd, $value, $id_essai){
        $query = "UPDATE resultat SET effet_secondaire = :side_effects WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $bdd->updateLines($query, ["side_effects" => $value, "id" => $this->getIduser(), "id_essai" => $id_essai]);
    }

    public function QuitEssai($bdd, $id_essai){
        $query = "UPDATE resultat SET is_patient_exclus = 3 WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $bdd->updateLines($query, ["id" => $this->getIduser(), "id_essai" => $id_essai]);
    }

    /**
     * Méthode pour rejoindre un essai clinique pour un patient
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     */
    public function Rejoindre($bdd, $id_essai){
        $query_phase = "SELECT ID_phase FROM essai WHERE ID_essai = :id_essai;";
        $query = "INSERT INTO resultat (ID_patient, ID_essai, is_accepte, is_patient_exclus, phase) 
                  VALUES (:id, :id_essai, 0, 0, :phase);";

        $res = $bdd->getResults($query_phase, ["id_essai" => $id_essai]);
        $bdd->insertLine($query, ["id" => $this->getIduser(), "id_essai" => $id_essai, "phase" => $res["ID_phase"]]);
        AfficherInfo("You have successfully joined the trial", $id_essai, "cross_inscription"); // affiche une notification pour confirmer l'inscription
    }

}
?>