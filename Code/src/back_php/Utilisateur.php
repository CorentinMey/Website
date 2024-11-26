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

    public function Inscription($bdd, $dict_information) {
        // Ajouter les clés is_bannis et is_admin avec valeur 0 au dictionnaire
        $dict_information['is_bannis'] = 0;
        $dict_information['is_admin'] = 0;
    
        // Vérifier si la clé "mdp" existe dans les informations
        if (isset($dict_information['mdp'])) {
            // Hacher le mot de passe avant l'insertion
            $dict_information['mdp'] = password_hash($dict_information['mdp'], PASSWORD_DEFAULT);
        }
    
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
            $res = $bdd->getConnection()->prepare($query);
    
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
        $query = 'SELECT mdp FROM utilisateur WHERE mail = :email';
    
        try {
            // Préparer la requête
            $res = $bdd->prepare($query);
    
            // Lier les paramètres pour la requête
            $res->bindParam(':email', $email, PDO::PARAM_STR);
    
            // Exécuter la requête
            $res->execute();
    
            // Récupérer le mot de passe haché associé à l'email
            $mdp = $res->fetch(PDO::FETCH_ASSOC);
    
            // Si aucun utilisateur trouvé
            if (!$mdp) {
                include_once("../back_php/Affichage_gen.php");
                afficherErreur("Identifiants incorrects.");
                exit();
                return false;
            }
    
            // Comparer le mot de passe
            if (password_verify($password, $mdp["mdp"])) {
            //if ($mdp["mdp"] == $password){
                // Connexion réussie

                #Over-ride de cette fonction chez chaque utilisateur ensuite pour pouvoir les rediriger vers leur page d'accueil utilisateur respective
                #Pour l'instant redirection vers une page test
                #Rajouter un session start, récuperer toutes les infos de l'utilisateur en demandant à la BDD, puis transmettre ces infos à la page suivante avec un $_session["utilisateur"] (peut-être à faire dans la page login)

                header("Location: page_test.php");
                exit; // Assurez-vous d'arrêter le script après une redirection
            } 
            else {
                // Mot de passe incorrect
                echo "Mot de passe erroné, veuillez réessayer.";
            }
    
            // Fermer la requête
            $res->closeCursor();
        } catch (PDOException $e) {
            // Gérer les erreurs
            echo "Erreur lors de la connexion : " . $e->getMessage();
            return false;
        }
    }


            
                }



?>