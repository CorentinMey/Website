

<?php
include_once("Utilisateur.php");
include_once("Securite.php");

class Admin extends Utilisateur {
    
    public function __construct($iduser,
                                $mdp,
                                $email,
                                $last_name,
                                $is_banned,
                                $is_admin = 1, // Un admin a toujours is_admin = 1
                                $first_name = null, 
                                $birthdate = null, 
                                $gender = null, 
                                $antecedent = null, 
                                $origins = null) 
    {
        // Appel du constructeur parent avec l'attribut is_admin = 1
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

        public function Inscription($bdd, $dict_information)
    {
        parent::Inscription($bdd, $dict_information); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
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
        $res = $bdd->getResultsAll($query, $data);
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

    public function Deconnect()
    {
        parent::Deconnect(); // reprend la fonction jusqu'à la création de l'objet utilisateur spécifique
            
    }
}
?>