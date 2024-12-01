<?php

include_once("../back_php/Affichage_gen.php");

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
        // Ajouter les clés is_bannis et is_admin avec valeur 0 au dictionnaire
        $dict_information['is_bannis'] = 0;
        $dict_information['is_admin'] = 0;
    
        // Vérifier si la clé "mdp" existe dans les informations
        if (isset($dict_information['mdp'])) {
            // Hacher le mot de passe avant l'insertion
            $dict_information['mdp'] = password_hash($dict_information['mdp'], PASSWORD_BCRYPT);
        }

        // Vérifier si la clé "date_naissance" existe dans les informations
        if (isset($dict_information['date_naissance'])) {
            // Convertir la date de naissance en objet DateTime
            $date_naissance = new DateTime($dict_information['date_naissance']);
            $date_actuelle = new DateTime();
            
            // Calculer la différence en années
            $age = $date_actuelle->diff($date_naissance)->y;
        
            if ($age < 18) {
                $_SESSION["result"] = "Erreur lors de l'inscription. L'âge minimum requis est de 18 ans";
                return;
            }
        }

        // Vérifier si la clé "mail" existe dans les informations
        if (isset($dict_information['mail'])) {
            // Vérifier si le mail est déjà utilisé, sinon retourner une erreur
            $new_mail = $dict_information['mail'];
            // Construire la requête pour chercher le mail dans la bdd
            $query = 'SELECT mail FROM utilisateur WHERE mail = :email';
        
            try {
                $query_res = $bdd->getResults($query, array("email" => $new_mail)); // Récupérer le premier résultat et l'email est unique
                
                        // Si le résultat n'est pas vide, le mail est déjà utilisé
                if (!empty($query_res)) {
                    $_SESSION["result"] = "Erreur lors de l'inscription. Cette adresse mail est déjà utilisée";
                    return;
                }
            } catch (PDOException $e) {
                // Gérer une erreur éventuelle lors de la requête
                $_SESSION["result"] = "Erreur lors de la vérification de l'adresse mail : " . $e->getMessage();
                return;
            }
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
            $_SESSION["result"] = 1;
            return;
        } catch (PDOException $e) {
            // Gérer les erreurs
            $_SESSION["result"] = "Erreur lors de l'inscription : " . $e->getMessage();
            return;
        }
    }
    
    

    public function Connexion($email, $password, $bdd) {
        // Construire la requête pour récupérer le mot de passe
        $query = 'SELECT mdp, ID_User FROM utilisateur WHERE mail = :email';
    
        try {
            $query_res = $bdd->getResults($query, array("email" => $email)); // Récupérer le premier résultat et l'email est unique
            
            if (empty($query_res)) { // Si aucun utilisateur n'est trouvé
                include_once("../back_php/Affichage_gen.php");
                afficherErreur("Identifiants incorrects.");
                exit();
                return false;
            } elseif (!password_verify($password, $query_res["mdp"])) { // Si le mot de passe ne correspond pas
                include_once("../back_php/Affichage_gen.php");
                afficherErreur("Mot de passe incorrect pour l'email $email");
                exit();
                return false;
            } else { // définir les attributs de l'objet pour les classes filles
                $this->setMdp($query_res["mdp"]);
                $this->setIduser($query_res["ID_User"]);
            }
            //Over-ride de cette fonction chez chaque utilisateur ensuite pour pouvoir les rediriger vers leur page d'accueil utilisateur respective
            //Pour l'instant redirection vers une page test
            //Rajouter un session start, récuperer toutes les infos de l'utilisateur en demandant à la BDD, puis transmettre ces infos à la page suivante avec un $_session["utilisateur"] (peut-être à faire dans la page login)

        } catch (PDOException $e) {
            // Gérer les erreurs
            echo "Erreur lors de la connexion : " . $e->getMessage();
            return false;
        }
    }


    public function Deconnect(){
        //Fonction pour se déconnecter du site
        session_destroy ();
        //header("Location: page_deco.php");
    }

}   

?>