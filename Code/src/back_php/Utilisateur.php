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

    public function __construct($iduser, $mdp, $email, $last_name, $is_banned, $is_admin, $first_name = null, $birthdate = null, $gender = null, $antecedent = null) {
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

    public function Connexion($email, $password, $bdd) {
        // Construire la requête pour récupérer le mot de passe
        $query = 'SELECT mdp, ID_User FROM utilisateur WHERE mail = :email';
    
        try {
            $query_res = $bdd->getResults($query, array("email" => $email)); // Récupérer le premier résultat et l'email est unique
            
            if (empty($query_res)) { // Si aucun utilisateur n'est trouvé
                include_once("../back_php/Affichage_gen.php");
                afficherErreur("Aucun utilisateur trouvé pour l'email $email");
                exit();
                return false;
            } elseif ($query_res["mdp"] != $password) { // Si le mot de passe ne correspond pas
                include_once("../back_php/Affichage_gen.php");
                afficherErreur("Mot de passe incorrect pour l'email $email");
                exit();
                return false;
            } else{ // définir les attributs de l'objet pour les classes filles
                $this->setMdp($query_res["mdp"]);
                $this->setIduser($query_res["ID_User"]);
            }
        } catch (PDOException $e) {
            // Gérer les erreurs
            echo "Erreur lors de la connexion : " . $e->getMessage();
            return false;
        }
    }
}



?>