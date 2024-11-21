<?php

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


    public function __construct($iduser, $mdp, $birthdate = null, $first_name = null, $last_name, $gender = null, $email, $antecedent = null, $is_banned, $is_admin) {
        $this->iduser = $iduser;
        $this->mdp = $mdp;
        $this->birthdate = $birthdate;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->gender = $gender;
        $this->email = $email;
        $this->antecedent = $antecedent;
        $this->is_banned = $is_banned;
        $this->is_admin = $is_admin;
    }

    # accesseurs et mutateurs de la classe
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
        $this->$gender = $gender;
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

    protected function Inscription($dict_information, $bdd){
        // Extraire les colonnes et leurs valeurs
        $columns = array_keys($dict_information); // Récupère les noms des colonnes
        $values = array_values($dict_information); // Récupère les valeurs à insérer

        // Générer la partie de la requête SQL
        $column_names = implode(", ", $columns); // Concatène les noms des colonnes avec des virgules
        $placeholders = implode(", ", array_fill(0, count($columns), "?")); // Génère un placeholder "?" pour chaque colonne

        // Construire la requête d'insertion
        $query = "INSERT INTO utilisateur ($column_names) VALUES ($placeholders)";

        try {
            // Préparer la requête
            $res = $bdd->prepare($query);

            // Exécuter la requête avec les valeurs
            $res->execute($values);

            // Retourner le résultat ou true pour indiquer que tout s'est bien passé
            return true;
        } catch (PDOException $e) {
            // Gérer les erreurs
            echo "Erreur lors de l'inscription : " . $e->getMessage();
            return false;
        }
    }

    protected function Connexion($email, $password, $bdd){

        // Construire la requête d'insertion
        $query = 'SELECT mdp FROM utilisateur WHERE mail=(:n1)';

        try {
            // Préparer la requête
            $res = $bdd->prepare($query);

            // Lier les paramètres pour la requête
            $res->bindParam(':n1', $email);

            // Exécuter la requête avec les valeurs
            $res->execute();

            // Récupérer le mot de passe associé à l'identifiant
            $mdp = $res->fetch(PDO::FETCH_ASSOC);

            $res-> closeCursor();


        } catch (PDOException $e) {
            // Gérer les erreurs
            echo "Erreur lors de l'inscription : " . $e->getMessage();
            return false;
        }

        if ($mdp["mdp"] == $password){
            return "Vous êtes bien connecté";
            $acces=TRUE;
        }
        else
        {
            $acces=FALSE;
        }

        if($acces==TRUE){

            #Over-ride de cette fonction chez chaque utilisateur ensuite pour pouvoir les rediriger vers leur page d'accueil utilisateur respective
            #Pour l'instant redirection vers une page

            ?>

            <!DOCTYPE html>
                <html>
                <head>
                <meta http-equiv="refresh" content="3; URL=/src/page_test.php">
                </head>
                </html>
            
            <?php

        }
        else{

            echo "Mot de passe erroné, veuillez réessayer.";

        }
                }
    }





?>