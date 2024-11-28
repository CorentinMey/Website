<?php
include_once("Utilisateur.php");
include_once("Securite.php");
include_once("Affichage_patient.php");
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

    public function Inscription($dict_information, $bdd)
    {
        parent::Inscription($dict_information, $bdd); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
    }
    
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
        }    
        $bdd->closeBD();
    }

    /**
     * Méthode pour mettre à jour les informations du patient dans la base de données.
     */
    private function ChangeInfo($bdd){
        $query = "UPDATE utilisateur SET prenom = :first_name, 
                                     nom = :last_name, 
                                     mail = :email, 
                                     genre = :gender, 
                                     origine = :origins, 
                                     antecedents = :antecedent, 
                                     mdp = :mdp #TODO hasher le mot de passe avant
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
            // Mettre à jour la base de données
            $this->ChangeInfo($bdd);
            // Mettre à jour l'objet en session
            $_SESSION["patient"] = $this;
            // Rediriger vers la page du patient
            header("Location: page_patient.php");
            exit;
        } else {
            AfficherErreur("Passwords do not match");
        }
    } else {
        AfficherErreur("Please fill all the fields");
    }
}

    /**
     * Méthode pour afficher les informations du patient dans un tableau.
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
            echo '<a href="page_signin.php" id = "edit_option">Edit my infos</a>';
        echo '</div>';
    }

    /**
     * Méthode qui affiche les essais cliniques auxquels le patient participe.
     */
    public function AfficheEssais($bdd){
        $query = "SELECT ID_essai, date_debut, date_fin, description, phase_res FROM resultat JOIN utilisateur ON 
                    resultat.ID_patient = utilisateur.ID_User NATURAL JOIN essai
                        WHERE ID_patient = :id;";
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
        Affichage_entete_tableau_essai(); // affiche l'en-tête du tableau
    
        foreach($res as $essai){ // affiche les lignes du tableau
            $id_essai = $essai["ID_essai"];
            $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
            $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
            Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
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
        $query_notif1_cpt = "SELECT COUNT(*) FROM resultat NATURAL JOIN essai
                             WHERE ID_patient = :id 
                             AND a_debute = 2 
                             AND ID_phase = phase_res # Ce AND permet de ne demander au patient ses impressions que pour lors de la phase de cloture de sa phase d'expérimentation
                             AND effet_secondaire IS NULL;"; // vérifie si le patient n'a pas déjà donné ses résultats
        $query_notif2 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_patient_exclus = 1;";
        $query_notif3 = "SELECT COUNT(*) FROM resultat WHERE ID_patient = :id AND is_accepte = 1;";

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
            AfficherInfo("The trial number : ".htmlspecialchars($notif_essai_fini["ID_essai"])." has ended with the description : ".htmlspecialchars($notif_essai_fini["description"])." Please give your feedback.");
        }
        foreach($res2 as $notif_exclu){
            AfficherInfo("You have been excluded from the trial number : ".htmlspecialchars($notif_exclu["ID_essai"])." with the description : ".htmlspecialchars($notif_exclu["description"]));
        }
        foreach($res3 as $notif_accepte){
            AfficherInfo("You have been accepted in the trial number : ".htmlspecialchars($notif_accepte["ID_essai"])." with the description : ".htmlspecialchars($notif_accepte["description"]));
        }

    }   

}
?>