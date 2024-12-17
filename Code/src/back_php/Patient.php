<?php
include_once("Utilisateur.php");
include_once("Securite.php");
include_once("Affichage_patient.php");
include_once("Affichage_gen.php");
class Patient extends Utilisateur{

    public function __construct($mdp,
                                $email,
                                $iduser = null, 
                                $last_name = null, 
                                $is_banned = null, 
                                $is_admin = null, 
                                $first_name = null, 
                                $birthdate = null, 
                                $gender = null, 
                                $antecedent = null,
                                $origins = null) 
    {
        parent::__construct($iduser, 
                            $mdp, 
                            $email, 
                            $last_name, 
                            $is_banned, 
                            $is_admin, 
                            $first_name, 
                            $birthdate, 
                            $gender, 
                            $antecedent,
                            $origins);
    }

    public function Inscription($bdd, $dict_information) {
        // Appeler la méthode Inscription de la classe Utilisateur
        parent::Inscription($bdd, $dict_information);

        // Vérifier si l'inscription de l'utilisateur a réussi
        if ($_SESSION["result"] === 1) {
            // Insérer les informations spécifiques au patient
            $this->insererPatient($bdd, $dict_information);
        }
    }

    private function insererPatient($bdd) {
        $id_patient = $this->getLastIdPatient();
        echo $id_patient;
        $query = "UPDATE utilisateur SET ID_User = :id_patient WHERE mail = :mail_user;";
        $params = [":id_patient" => $id_patient, ":mail_user" => $this->getEmail()];
        $bdd->updateLines($query, $params);
    }


    public function getLastIdPatient() {
        $filename = __DIR__."/id_patient_count.txt";
        $id_patient = 0;
        // Lire le nombre à partir du fichier
        if (file_exists($filename)) {
            $fh = fopen($filename, "r");
            if ($fh) {
                $id_patient = (int)fread($fh, filesize($filename));
                fclose($fh);
            }
        } else {
            AfficherErreur("Error while attributing an ID to the patient");
            exit();
        }        
        // Incrémenter le nombre
        $id_patient += 1;
        $fh = fopen($filename, "w");
        if ($fh) {
            fwrite($fh, $id_patient);
            fclose($fh);
        }
        return $id_patient;
    }

    /**
     * Méthode pour se connecter à un compte patient depuis la page de connexion
     * @param $email : email du patient
     * @param $password : mot de passe du patient
     * @param $bdd : base de données
     */
    public function Connexion($email, $password, $bdd)
    {
        parent::Connexion($email, $password, $bdd); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
    
        $data = ["id_user" => $this->iduser];
        $query = "SELECT * FROM utilisateur WHERE ID_User = :id_user;";
        $res = $bdd->getResults($query, $data);
        if ($res != []){
            $this->birthdate = $res["date_naissance"];
            $this->first_name = $res["prenom"];
            $this->last_name = $res["nom"];
            $this->is_banned = $res["is_bannis"];
            $this->is_admin = $res["is_admin"];
            $this->gender = $res["genre"];
            $this->antecedent = $res["antecedents"];
            $this->origins = $res["origine"];
        }  else {
            AfficherErreur("Error while collecting user's info.");
            exit();
        }
    }

    public function Deconnect()
    {
        parent::Deconnect(); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
    }

    /**
     * Méthode pour mettre à jour les informations du patient dans la base de données.
     * @param $bdd : base de données
     */
    private function ChangeInfo($bdd){
        $query = "UPDATE utilisateur SET prenom = :first_name, 
                                     nom = :last_name, 
                                     mail = :email, 
                                     genre = :gender, 
                                     origine = :origins, 
                                     antecedents = :antecedent, 
                                     mdp = :mdp
                            WHERE ID_User = :iduser";
        $params = [
            ':first_name' => $this->first_name,
            ':last_name' => $this->last_name,
            ':email' => $this->email,
            ':gender' => $this->gender,
            ':origins' => $this->origins,
            ':antecedent' => $this->antecedent,
            ':iduser' => $this->iduser,
            ':mdp' => $this->mdp,
        ];
        $bdd->updateLines($query, $params);
    }

    /**
     * Méthode pour mettre à jour les informations du patient existant
     */
    public function updatePatientInfo() {
        
    $required_fields = ["Nom", "prénom", "identifiant", "genre", "origin", "medical", "mdp", "mdp2"];
    if (checkFormFields($required_fields)) { // Vérifie si tous les champs sont remplis
        if (checkPassword($_POST["mdp"], $_POST["mdp2"])) { // Vérifie si les mots de passe correspondent
            $bdd = new Query("siteweb");
            // Mettre à jour les informations du patient
            $this->setFirst_name($_POST["Nom"]);
            $this->setLast_name($_POST["prénom"]);
            $this->setEmail($_POST["identifiant"]);
            $this->setGender($_POST["genre"]);
            $this->setOrigins($_POST["origin"]);
            $this->setAntecedent($_POST["medical"]);
            $this->setMdp(password_hash($_POST["mdp"], PASSWORD_BCRYPT));
            $this->setBirthdate($_POST["date_naissance"]);
            // Mettre à jour la base de données
            $this->ChangeInfo($bdd);
            // Mettre à jour l'objet en session
            $_SESSION["patient"] = $this;
            // Rediriger vers la page du patient
            header("Location: page_patient.php");
            exit;
        } else {
            AfficherErreur("Passwords do not match or are not strong enough");
        }
    } else {
        AfficherErreur("Please fill all the fields");
    }
}

    /**
     * Méthode pour afficher les informations du patient dans un tableau.
     * 
     */
    public function AffichageTableauInfoPerso(){
        echo "<div id = 'titre_container'>";
            echo "<div id = 'titre_my_information'>";
                echo '<h2 class = "title">My Information</h2>';
            echo '</div>';
        echo '</div>';
        echo '<div id = "personnal_data">';
            echo '<table class = "styled-table" id = "table_patient">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>First name</th>';
                        echo '<th>Family name</th>';
                        echo '<th>Birthday</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Origins</th>';
                        echo '<th>Email</th>';
                        echo '<th>Medical history</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                    echo '<tr>';
                        echo '<td>'.$this->getFirst_name().'</td>';
                        echo '<td>'.$this->getLast_name().'</td>';
                        echo '<td>'.$this->getBirthdate().'</td>';
                        echo '<td>'.$this->getGender()."</td>";
                        echo '<td>'.$this->getOrigins().'</td>';
                        echo '<td>'.$this->getEmail().'</td>';
                        echo '<td>'.$this->getAntecedent().'</td>';
                    echo '</tr>';
                echo '</tbody>';
            echo '</table>';
            echo '<div id = "edit_container">';
                echo '<a href="page_signin.php" id = "edit_option">Edit my infos</a>';
            echo '</div>';
        echo '</div>';
    }

    /**
     * Méthode pour récupérer les informations des essais cliniques auxquels le patient participe. pour bien diviser les tâches
     * @param $bdd : base de données
     */
    private function GetInfoEssai($bdd){
        if ($this->getIduser() == null){
            AfficherErreur("Error while collecting user's info.");
            exit();
        } elseif (!($bdd instanceof Query)){
            AfficherErreur("Error while collecting user's info.");
            exit();
        }

        $query = "SELECT ID_essai, date_debut, date_fin, description, phase_res, effet_secondaire FROM resultat JOIN utilisateur ON 
        resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
            WHERE ID_patient = :id 
            AND is_patient_exclus = 0 AND
            is_accepte !=0;";
   
        $res = $bdd->getResultsAll($query, ["id" => $this->getIduser()]);
        return $res;
    }
    /**
     * Pour le fichier test_patient.php
     */
    public function getGetInfoEssai($bdd){
        return $this->GetInfoEssai($bdd);
    }

    /**
     * Méthode qui affiche les essais cliniques auxquels le patient participe.
     * @param $bdd : base de données
     */
    public function AfficheEssais($bdd){
        // requete pour avoir le nom de l'entreprise
        $query2 = "SELECT nom, a_debute FROM utilisateur JOIN essai ON essai.ID_entreprise_ref = utilisateur.ID_User 
        WHERE ID_essai = :id;";
        // requete pour avoir le nom des medecins referents
        $query3 = "SELECT nom FROM utilisateur JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                    WHERE ID_essai = :id;"; // pas besoin de vérifier si le médecin a accepté car on ne peut pas être accepté dans un essai sans médecin et qu'un médecin ne peut pas se rajouter après le début de l'essai

        $res = $this->GetInfoEssai($bdd); // récupère les informations des essais
        if ($res == []) {
            AfficherErreur("No clinical trials found yet. Please subscribe to some trials.");
            echo "<br>"; // met un peu d'espace pour la lisibilité
            echo "<br>";
            echo "<br>";
            echo "<br>";
            return;
        }
        Affichage_entete_tableau_essai(); // affiche l'en-tête du tableau
        foreach($res as $essai){ // affiche les lignes du tableau
            $id_essai = $essai["ID_essai"];
            $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
            $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
            Affichage_content_essai($entreprise, $essai, $medecins, $id_essai); // remplis la ligne du tableau
        }         
            echo '</tbody>'; // fermeture du tableau et de la div qui le contient
            echo '</table>';
            echo '</div>';
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
                             AND ID_phase = phase_res # Ce AND permet de ne demander au patient ses impressions que pour lors de la phase de cloture de sa phase d'expérimentation
                             AND effet_secondaire IS NULL;"; // vérifie si le patient n'a pas déjà donné ses résultats
        $query_notif2 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_patient_exclus = 1;"; // notif si on a été exclus
        $query_notif3 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_accepte = 1;"; // notif si on a été accepté

        $res1 = $bdd->getResults($query_notif1_cpt, ["id" => $this->getIduser()])["COUNT(*)"]; // compte le nombre d'essais terminés
        $res2 = $bdd->getResults($query_notif2, ["id" => $this->getIduser()])["COUNT(*)"];
        $res3 = $bdd->getResults($query_notif3, ["id" => $this->getIduser()])["COUNT(*)"];
        $total = $res1 + $res2 + $res3;
        return $total;
    }


    /**
     * Méthode pour afficher les notifications du patient en fonction de leurs types
     * @param $bdd : base de données
     */
    public function AfficheNotif($bdd){
        $query_notif1 = "SELECT ID_essai, description  FROM resultat NATURAL JOIN essai 
                            WHERE ID_patient = :id 
                            AND a_debute = 2
                            AND ID_phase = phase_res
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

    /**
     * Méthode pour quitter un essai clinique pour un patient
     */
    public function QuitEssai($bdd, $id_essai){
        $query_preli = "SELECT * FROM essai WHERE ID_essai = :id_essai;";
        $res = $bdd->getResults($query_preli, ["id_essai" => $id_essai]);
        if ($res === []){
            AfficherErreur("Error while quitting the trial. Trial unkown.");
            return 1;
        }
        $query = "UPDATE resultat SET is_patient_exclus = 3 WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $bdd->updateLines($query, ["id" => $this->getIduser(), "id_essai" => $id_essai]);
        return 0;
    }

    /**
     * Fonction pour attribuer aléatoirement un traitement au patient qui s'est inscrit à un essai
     * @param $test : nom de la molécule testée
     * @param $test_dose : dose de la molécule testée
     * @param $ref : nom de la molécule de référence
     * @param $ref_dose : dose de la molécule de référence
     * @param $placebo : nom du placebo
     * @return array : tableau contenant le traitement et la dose attribuée
     */
    private function AttributeTreatment($test, $ref, $test_dose, $ref_dose, $placebo){
        $rand = rand(0, 2);
        if ($rand == 0)
            return [$test, $test_dose];
        else if ($rand == 1)
            return [$ref, $ref_dose];
        else
            return [$placebo, 0.0];
    }

    public function getAttributeTreatment($test, $ref, $test_dose, $ref_dose, $placebo){ // fonction pour le fichier de test
        return $this->AttributeTreatment($test, $ref, $test_dose, $ref_dose, $placebo);
    }

    /**
     * Méthode pour rejoindre un essai clinique pour un patient
     * @param $bdd : base de données
     * @param $id_essai : id de l'essai
     */
    public function Rejoindre($bdd, $id_essai){
        $query_phase = "SELECT ID_phase, molecule_test, molecule_ref, dosage_test, dosage_ref, placebo_nom FROM essai WHERE ID_essai = :id_essai;";
        //test si le patient est déjà inscrit à l'essai
        $query_patient = "SELECT * FROM resultat WHERE ID_patient = :id AND ID_essai = :id_essai;";
        $query = "INSERT INTO resultat (ID_patient, ID_essai, is_accepte, is_patient_exclus, phase_res, traitement, dose) 
                  VALUES (:id, :id_essai, 0, 0, :phase_res, :traitement, :dose);";

        $res = $bdd->getResults($query_phase, ["id_essai" => $id_essai]);
        if ($res === []){
            AfficherErreur("Error while joining the trial. Trial unkown.");
            return;
        } elseif ($bdd->getResults($query_patient, ["id" => $this->getIduser(), "id_essai" => $id_essai]) != []){
            AfficherErreur("You are already in this trial.");
            return;
        }
        $attribution = $this->AttributeTreatment($res["molecule_test"], $res["molecule_ref"], $res["dosage_test"], $res["dosage_ref"], $res["placebo_nom"]);
        $bdd->insertLine($query, ["id" => $this->getIduser(),
                                "id_essai" => $id_essai, 
                                "phase_res" => $res["ID_phase"],
                                "traitement" => $attribution[0],
                                "dose" => $attribution[1]]);
        AfficherInfo("You have successfully joined the trial. Please now wait for the doctor confirmation.", $id_essai, "cross_inscription"); // affiche une notification pour confirmer l'inscription
    }
}
?>