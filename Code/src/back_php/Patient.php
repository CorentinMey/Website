<?php
include_once("Utilisateur.php");
include_once("Securite.php");
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

        // Fonction pour mettre à jour les informations du patient existant
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
        echo '<h2 class = "title">My clinical trials</h2>';
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
        $query2 = "SELECT nom FROM utilisateur JOIN essai ON essai.ID_entreprise_ref = utilisateur.ID_User 
                        WHERE ID_essai = :id;";
        // requete pour avoir le nom des medecins referents
        $query3 = "SELECT nom FROM utilisateur JOIN essai_medecin ON essai_medecin.ID_medecin = utilisateur.ID_User 
                        WHERE ID_essai = :id;";

        $res = $bdd->getResultsAll($query, ["id" => $this->getIduser()]);
        if ($res != [])
            AfficherErreur("No clinical trials found yet. Please subscribe to some trials.");
        
        foreach($res as $essai){
            $id_essai = $essai["ID_essai"];
            $entreprise = $bdd->getResults($query2, ["id" => $id_essai]);
            $medecins = $bdd->getResultsAll($query3, ["id" => $id_essai]);
            echo '<div class = "essai">';
                echo '<h2>'.$entreprise["nom"].'</h2>';
                echo '<p>Phase '.$essai["phase_res"].'</p>';
                echo '<p>'.$essai["description"].'</p>';
                echo '<p>Start date: '.$essai["date_debut"].'</p>';
                echo '<p>End date: '.$essai["date_fin"].'</p>';
                echo '<p>Referent doctors: ';
                foreach($medecins as $medecin){
                    echo $medecin["nom"].', ';
                }
                echo '</p>';
        }
    }

}

?>