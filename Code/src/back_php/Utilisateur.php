<?php

include_once("Affichage_gen.php");

class Utilisateur {
    protected $iduser;
    protected $mdp;
    protected $birthdate;
    protected $first_name;
    protected $last_name;
    protected $gender;
    protected $email;
    protected $antecedent;
    protected $is_banned;
    protected $is_admin;
    protected $origins;

    public function __construct($iduser, $mdp, $email, $last_name, $is_banned, $is_admin, $first_name = null, $birthdate = null, $gender = null, $antecedent = null, $origins = null){
        $this->iduser = $iduser;
        $this->mdp = $mdp;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->last_name = $last_name;
        $this->is_banned = $is_banned;
        $this->is_admin = $is_admin;
        $this->first_name = $first_name;
        $this->gender = $gender;
        $this->antecedent = $antecedent;
        $this->origins = $origins;
    }

    # accesseurs et mutateurs de la classe
    public function getOrigins(){
        return $this->origins;
    }

    public function setOrigins($origins){
        $this->origins = $origins;
    }

    public function getIduser(){
        return $this->iduser;
    }

    public function setIduser($iduser){
        $this->iduser = $iduser;
    }

    public function getBirthdate(){
        return $this->birthdate;
    }

    public function setBirthdate($birthdate){
        $this->birthdate = $birthdate;
    }

    public function getFirst_name(){
        return $this->first_name;
    }

    public function setFirst_name($first_name){
        $this->first_name = $first_name;
    }

    public function getLast_name(){
        return $this->last_name;
    }

    public function setLast_name($last_name){
        $this->last_name = $last_name;
    }

    public function getGender(){
        return $this->gender;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getAntecedent(){
        return $this->antecedent;
    }

    public function setAntecedent($antecedent){
        $this->antecedent = $antecedent;
    }

    public function getIs_banned(){
        return $this->is_banned;
    }

    public function setIs_banned($is_banned){
        $this->is_banned = $is_banned;
    }

    public function getIs_admin(){
        return $this->is_admin;
    }

    public function setIs_admin($is_admin){
        $this->is_admin = $is_admin;
    }

    public function getMdp(){
        return $this->mdp;
    }

    public function setMdp($mdp){
        $this->mdp = $mdp;
    }

    # Méthodes de classe
    public function Inscription($bdd, $dict_information) {
        // Ajouter les valeurs par défaut
        $this->ajouterValeursParDefaut($dict_information);
        
        // Hasher le mot de passe
        $this->hasherPassword($dict_information);
        
        // Vérifier l'âge
        if (!$this->verifierAge($dict_information)) {
            return;
        }
        
        // Vérifier l'unicité de l'email
        if (isset($dict_information['mail']) && !$this->verifierEmailUnique($bdd, $dict_information['mail'])) {
            return;
        }
        
        // Insérer l'utilisateur dans la base de données
        $this->insererUtilisateur($bdd, $dict_information);
    }

    /**
     * Ajoute les valeurs par défaut pour l'admin et le statut de ban
     * L'utilisateur n'est pas admin et n'est pas banni par défaut
     */
    protected function ajouterValeursParDefaut(&$dict_information) {
        $dict_information['is_bannis'] = 0;
        $dict_information['is_admin'] = 0;
    }

    protected function hasherPassword(&$dict_information) {
        if (isset($dict_information['mdp'])) {
            $dict_information['mdp'] = password_hash($dict_information['mdp'], PASSWORD_BCRYPT);
        }
    }

    protected function verifierAge(&$dict_information) {
        if (isset($dict_information['date_naissance'])) {
            $date_naissance = new DateTime($dict_information['date_naissance']);
            $date_actuelle = new DateTime();
            $age = $date_actuelle->diff($date_naissance)->y;

            if ($age < 18) {
                $_SESSION["result"] = "Erreur lors de l'inscription. L'âge minimum requis est de 18 ans";
                return false;
            }
        }
        return true;
    }

    protected function verifierEmailUnique($bdd, $email) {
        if (isset($email)) {
            $query = 'SELECT mail FROM utilisateur WHERE mail = :email';
            
            try {
                $query_res = $bdd->getResults($query, array("email" => $email));
                if (!empty($query_res)) {
                    $_SESSION["result"] = "Erreur lors de l'inscription. Cette adresse mail est déjà utilisée";
                    return false;
                }
            } catch (PDOException $e) {
                $_SESSION["result"] = "Erreur lors de la vérification de l'adresse mail : " . $e->getMessage();
                return false;
            }
        }
        return true;
    }

    protected function insererUtilisateur($bdd, $dict_information) {
        $columns = array_keys($dict_information);
        $values = array_values($dict_information);

        $column_names = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($columns), "?"));

        $query = "INSERT INTO utilisateur ($column_names) VALUES ($placeholders)";
        echo $query;
        try {
            $res = $bdd->getConnection()->prepare($query);
            $res->execute($values);
            $_SESSION["result"] = 1;
            return true;
        } catch (PDOException $e) {
            $_SESSION["result"] = "Erreur lors de l'inscription : " . $e->getMessage();
            echo $_SESSION["result"];
            return false;
        }
    }   
    

    public function Connexion($email, $password, $bdd) {
        // Construire la requête pour récupérer le mot de passe
        $query = 'SELECT mdp, ID_User FROM utilisateur WHERE mail = :email';
    
        try {
            $query_res = $bdd->getResults($query, array("email" => $email)); // Récupérer le premier résultat et l'email est unique
            
            if (empty($query_res)) { // Si aucun utilisateur n'est trouvé
                afficherErreur("Unknown identifiers.");
                exit();
                return false;
            } elseif (!password_verify($password, $query_res["mdp"])) { // Si le mot de passe ne correspond pas
                afficherErreur("Incorect password for ".htmlspecialchars($email));
                exit();
                return false;
            } else { // définir les attributs de l'objet pour les classes filles
                $this->setMdp($query_res["mdp"]);
                $this->setIduser($query_res["ID_User"]);
            }

  
        } catch (PDOException $e) {
            // Gérer les erreurs
            AfficherErreur("Erreur lors de la connexion : " . $e->getMessage());
            return false;
        }
    }


    public function Deconnect(){
        //Fonction pour se déconnecter du site
        session_destroy ();
    }

}   

?>