<?php
include_once("Utilisateur.php");
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
                $this->setMdp($_POST["mdp"]);
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
    
}

?>