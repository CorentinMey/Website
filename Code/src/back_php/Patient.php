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

    public function Connexion($email, $password, $bdd)
    {
        parent::Connexion($email, $password, $bdd); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
        
        if ($this->mdp == $password){ // this.mdp est défini dans la classe Utilisateur dans sa méthode Connexion
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
    }

    public function ChangeInfo($bdd){
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
}

?>