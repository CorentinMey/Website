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

        // Ajout de la clé 'is_bannis' avec la valeur associée 2 pour préciser que le médecin n'est pas encore accepté
        $med_dict['is_bannis'] = 2;

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
        echo '</div>';
    }

    /**
     * Méthode pour afficher les informations d'un patient dans un tableau.
     * 
     */
    public function AffichageTableau_patient($bdd, $id_user){

        $query = "SELECT DISTINCT nom, prenom, genre, date_naissance, antecedents, mail, traitement, dose, effet_secondaire, evolution_symptome, is_patient_exclus FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_User = :id;";
        $res1 = $bdd->getResultsAll($query, ["id" => $id_user]);
        if ($res1 == []) {
            AfficherErreur("No informations found. Please contact the admin.");
            return;
        }
        //Tableau pour les infos générales
        $res_f=$res1[0]; // récupère les données du patients
        // Stocker les données du patient dans la session
        $_SESSION['patient_infos'] = $res_f;
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
                        echo '<th>Banned ?</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td>'.$res_f["nom"].'</td>';
                        echo '<td>'.$res_f["prenom"].'</td>';  
                        echo '<td>'.$res_f["date_naissance"].'</td>';
                        echo '<td>'.$res_f["genre"].'</td>';
                        echo '<td>'.$res_f["mail"].'</td>';
                        echo '<td>'.$res_f["antecedents"].'</td>';
                        if ($res_f["is_patient_exclus"]==0){
                            echo '<td>No</td>';
                        }
                        else{
                            echo '<td>Yes</td>';
                        }
                        
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
                        echo '<td>'.$res_f["traitement"].'</td>';
                        echo '<td>'.$res_f["dose"].'</td>';
                        echo '<td>'.$res_f["effet_secondaire"].'</td>';
                        echo '<td>'.$res_f["evolution_symptome"].'</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
            echo '<a href="page_modif_medecin.php" id = "edit_option">Edit infos</a>';
        echo '</div>';
    
}

    /**
     * Méthode qui affiche un résumé des essais cliniques auxquels le médecin participe.
     */
    public function AfficheEssais($bdd){
        $query = "SELECT ID_essai, date_debut, date_fin, description, ID_phase, is_accepte, est_de_company, a_debute FROM medecin JOIN utilisateur ON 
                    medecin.numero_ordre = utilisateur.ID_User NATURAL JOIN essai_medecin NATURAL JOIN essai
                        WHERE numero_ordre = :id
                        AND essai_medecin.ID_medecin = :id";
        // requete pour avoir le nom de l'entreprise
        $query2 = "SELECT nom, a_debute FROM utilisateur JOIN essai ON essai.ID_entreprise_ref = utilisateur.ID_User 
                        WHERE ID_essai = :id;";
        // requete pour avoir le nom des medecins referents
        $query3 = "SELECT nom FROM utilisateur JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                        WHERE ID_essai = :id 
                        AND is_bannis = 0;"; 
    
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
            if($essai["a_debute"]!=1 | $essai["is_accepte"]==1){ // On n'affiche que les essais qui sont soit ceux du médecin, soit ceux qui n'ont pas encore débuté (n'affiche pas ceux qui ont commencé et où le médecin n'a pas été sélectionné)
                Affichage_content_essai_med($entreprise, $essai, $medecins, $id_essai);
            }
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
                        dosage_test, molecule_ref, dosage_ref, placebo_nom, is_accepte, est_de_company FROM medecin JOIN utilisateur ON 
                    medecin.numero_ordre = utilisateur.ID_User NATURAL JOIN essai_medecin NATURAL JOIN essai
                        WHERE ID_essai = :id;";
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
        
    
        $essai = $res[0]; // stock les lignes du tableau
        if($essai["est_de_company"]==1){ //On vérifie si la demande vient du médecin ou de l'entreprise
            $demande = 1; 
        }
        else{
            $demande = 0;
        }


        #Affichage d'un premier tableau
        Affichage_entete_tableau_essai_med2($demande, $essai["is_accepte"]); // affiche l'en-tête du tableau

        $id_essai = $essai["ID_essai"];
        $ID_User = $this->iduser; //Récupération de l'ID du médecin
        $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
        $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
        Affichage_content_essai_med2($entreprise, $essai, $medecins, $id_essai, $ID_User);        
                 
        echo '</tbody>'; // fermeture du tableau et de la div qui le contient
        echo '</table>';
        echo '</div>';

}

    /**
     * Méthode qui affiche les participants d'un essai clinique auquel le médecin est en attente de participation.
     */
    public function AfficherParticipants($bdd, $id_essai){
        // requete pour avoir les patients admis
        $query = "SELECT ID_User, nom, prenom, genre, date_naissance, antecedents, is_accepte, a_debute FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND is_accepte != 0
                        AND is_bannis = 0;";
        // requete pour avoir les patients en attente d'admission
        $query2 = "SELECT ID_User, nom, prenom, genre, date_naissance, antecedents, is_accepte FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND is_accepte = 0
                        AND is_bannis = 0;";
    
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
        Affichage_entete_tableau_participants(1); // affiche l'en-tête du tableau
    
        foreach($res as $participant){ // affiche les lignes du tableau
            Affichage_content_participants($participant, $id_essai);
            $en_cours = $participant["a_debute"];
        }         
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';

        if($en_cours==0){ //On vérifie si l'essai a débuté, sinon on supprime l'affichage des patients en attente de rejoindre l'essai
            // Affichage des patients en attente
            Affichage_entete_tableau_patients(0); // affiche l'en-tête du tableau
    
            foreach($res2 as $participant){ // affiche les lignes du tableau
                Affichage_content_participants($participant, $id_essai);
        }     
        }
            
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';
        
}

// Fonction pour accepter un utilisateur dans un essai
public function AccepterPatient($bdd, $id_essai, $id_patient){
    $query = "UPDATE resultat SET is_accepte = 1 WHERE ID_patient = :id AND ID_essai = :id_essai;";
    $bdd->updateLines($query, ["id" => $id_patient, "id_essai" => $id_essai]);
    try {
        $bdd->updateLines($query, ["id" => $id_patient, "id_essai" => $id_essai]);
        return "Le patient a été accepté avec succès.";
    } catch (Exception $e) {
        return AfficherErreur("Erreur lors de l'acceptation du patient : " . $e->getMessage());
    }
}

// Fonction pour supprimer un utilisateur d'un essai
public function SupprimerPatient($bdd, $id_essai, $id_patient) {
    $query = "DELETE FROM resultat WHERE ID_patient = :id AND ID_essai = :id_essai;";
    try {
        $bdd->updateLines($query, ["id" => $id_patient, "id_essai" => $id_essai]);
        return "Le patient a été supprimé avec succès.";
    } catch (Exception $e) {
        return AfficherErreur("Erreur lors de la suppression du patient : " . $e->getMessage());
    }
}

// Fonction pour accepter un utilisateur dans un essai
public function AccepterDemande($bdd, $id_essai, $id_med){
    $query = "UPDATE essai_medecin SET is_accepte = 1 WHERE ID_medecin = :id AND ID_essai = :id_essai;";
    $bdd->updateLines($query, ["id" => $id_med, "id_essai" => $id_essai]);
    try {
        $bdd->updateLines($query, ["id" => $id_med, "id_essai" => $id_essai]);
        return "Vous avez rejoint l'essai avec succès.";
    } catch (Exception $e) {
        return AfficherErreur("Erreur lors de l'acceptation de l'essai : " . $e->getMessage());
    }
}

// Fonction pour supprimer un utilisateur d'un essai
public function SupprimerDemande($bdd, $id_essai, $id_med) {
    $query = "DELETE FROM essai_medecin WHERE ID_medecin = :id AND ID_essai = :id_essai;";
    try {
        $bdd->updateLines($query, ["id" => $id_med, "id_essai" => $id_essai]);
        return "L'essai a été refusé avec succès.";
    } catch (Exception $e) {
        return AfficherErreur("Erreur lors du refus de l'essai : " . $e->getMessage());
    }
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
                  genre = :genre,
                  antecedents = :antecedents
              WHERE ID_User = :id_user";

    $query2 = "UPDATE resultat 
               SET traitement = :traitement,
                   dose = :dose,
                   effet_secondaire = :effet_secondaire,
                   evolution_symptome = :evolution_symptome,
                   is_patient_exclus = :exclu
               WHERE ID_patient = :id_user
               AND ID_essai = :id_essai";

    // Préparer les paramètres pour les requêtes
    $params_user = [
        ':prenom' => $data['prenom'],
        ':nom' => $data['nom'],
        ':genre' => $data['genre'],
        ':antecedents' => $data['antecedents'],
        ':id_user' => $id_user
    ];

    $params_resultat = [
        ':traitement' => $data['traitement'],
        ':dose' => $data['dose'],
        ':effet_secondaire' => $data['effet_secondaire'],
        ':evolution_symptome' => $data['evolution_symptome'],
        ':exclu' => $data['ban_user'],
        ':id_user' => $id_user,
        ':id_essai' => $id_essai
    ];

    // Exécution des requêtes
    try {
        $bdd->updateLines($query, $params_user);
        $bdd->updateLines($query2, $params_resultat);
    } catch (Exception $e) {
        AfficherErreur("Erreur lors de la mise à jour des informations : " . $e->getMessage());
    }
}

    /**
     * Fonction qui renvoie les résultats généraux d'un essai clinique
    */
    public function AffichageResultats($bdd, $id_essai){
        // requete pour avoir les patients admis
        $query = "SELECT ID_User, ID_phase, genre, date_naissance, antecedents, traitement, dose, effet_secondaire, evolution_symptome FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_essai = :id 
                        AND a_debute = 2
                        AND is_accepte != 0
                        # TODO faire la distinction selon les phases de l'essai 
                        AND phase_res = 1;"; // On ne prend que les résultats si l'essai est fini
    
        $res = $bdd->getResultsAll($query, ["id" => $id_essai]);
        if ($res == []) {
            AfficherErreur("The results are not available yet. Please wait until the end of the trial.");
            return;
        }

        //Affichage des resultats
        Affichage_entete_tableau_resultats(); // affiche l'en-tête du tableau
        
        //Initialisation d'une variable compteur
        $x=1;
        foreach($res as $result){ // affiche les lignes du tableau
            Affichage_content_resultats($result, $id_essai, $x);
            $x= $x + 1;
            $phase_actuel = $result["ID_phase"];
        }         
            
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';
            
            afficherGraphiques($bdd, $id_essai, $phase_actuel);

    }



    /**
     * Fonction qui renvoie le nombre de notifications à voir du médecin
     * Notif : Si le médecin a reçu des demandes de participation de la part des entreprises
     * Si le médecin a été accepté dans un essai
     * @param $bdd : base de données
     * @return int : nombre de notifications
     */
    public function NombreNotif($bdd){
        $query_notif1_cpt = "SELECT COUNT(*) FROM essai_medecin NATURAL JOIN essai
                            WHERE ID_medecin = :id
                            AND a_debute = 0  # L'essai ne doit pas encore avoir commencé
                            AND is_accepte = 0 # Le patient ne doit pas encore avoir accepté
                            AND est_de_company = 1;"; # On vérifie que l'entreprise a demandé au patient    
        $query_notif2 = "SELECT COUNT(*) FROM essai_medecin NATURAL JOIN essai WHERE ID_medecin = :id AND a_debute = 0 AND is_accepte = 1 AND est_de_company = 0;"; // notif si on a été accepté

        $res1 = $bdd->getResults($query_notif1_cpt, ["id" => $this->getIduser()])["COUNT(*)"]; // compte le nombre de demande qu'on a fait au medecin
        $res2 = $bdd->getResults($query_notif2, ["id" => $this->getIduser()])["COUNT(*)"];
        $total = $res1 + $res2;
        return $total;
    }

    public function AfficheNotif($bdd){
        $query_notif1 = "SELECT ID_essai, nom FROM essai_medecin NATURAL JOIN essai 
                            JOIN entreprise ON essai.id_entreprise_ref = entreprise.siret
                            JOIN utilisateur ON entreprise.siret = utilisateur.ID_User
                            WHERE ID_medecin = :id 
                            AND a_debute = 0 # L'essai ne doit pas encore avoir commencé
                            AND is_accepte = 0 # Le patient ne doit pas encore avoir accepté
                            AND est_de_company = 1;"; # On vérifie que l'entreprise a demandé au patient    
                            

        $query_notif2 = "SELECT ID_essai, nom FROM essai_medecin NATURAL JOIN essai 
                            JOIN entreprise ON essai.id_entreprise_ref = entreprise.siret
                            JOIN utilisateur ON entreprise.siret = utilisateur.ID_User
                            WHERE ID_medecin = :id 
                            AND a_debute = 0 # L'essai ne doit pas encore avoir commencé
                            AND is_accepte = 1 # Le patient doit avoir été accepté
                            AND est_de_company = 0;"; # On vérifie que l'entreprise a demandé au patient    
                            
        
        // récupération des informations des essais
        $res1 = $bdd->getResultsAll($query_notif1, ["id" => $this->getIduser()]);
        $res2 = $bdd->getResultsAll($query_notif2, ["id" => $this->getIduser()]);
        //affichage des notifications
        foreach($res1 as $notif_essai){
            AfficherInfo("The company named : ".htmlspecialchars($notif_essai["nom"])." wants you to join the trial number ".htmlspecialchars($notif_essai["ID_essai"]).". Please give your feedback.", 
                        $notif_essai["ID_essai"], 
                        "cross", False); 
        }
        foreach($res2 as $notif_accepte){
            AfficherInfo("You have been accepted in the trial number ".htmlspecialchars($notif_accepte["ID_essai"])." by the company : ".htmlspecialchars($notif_accepte["nom"]).". This message will automatically disappear when the trial will begin.", 
                        $notif_accepte["ID_essai"], 
                        "cross", False);
        }

    }   
    


    /**
     * Méthode pour rejoindre un essai clinique pour un médecin
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     */
    public function Rejoindre($bdd, $id_essai){
        $query = "INSERT INTO essai_medecin (ID_medecin, ID_essai, is_accepte, est_de_company) 
                  VALUES (:id, :id_essai, 0, 0);";

        $bdd->insertLine($query, ["id" => $this->getIduser(), "id_essai" => $id_essai]);
        AfficherInfo("You have successfully joined the trial", $id_essai, "cross_inscription"); // affiche une notification pour confirmer l'inscription
    }

}
?>