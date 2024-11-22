<?php

class Entreprise extends Utilisateur {
    protected $siret;
    protected $ville;

    public function __construct($iduser, $mdp, $birthdate = null, $first_name = null, $last_name, $gender = null, $email, $antecedent = null, $is_banned, $is_admin, $siret, $ville) {
        // Appeler le constructeur de la classe parent (Utilisateur)
        parent::__construct($iduser, $mdp, $birthdate, $first_name, $last_name, $gender, $email, $antecedent, $is_banned, $is_admin);

        // Initialiser les propriétés spécifiques à l'entreprise
        $this->siret = $siret;
        $this->ville = $ville;
    }

    # Accesseurs et mutateurs pour les nouvelles propriétés
    public function getSiret() {
        return $this->siret;
    }

    public function setSiret($siret) {
        $this->siret = $siret;
    }

    public function getVille() {
        return $this->ville;
    }

    public function setVille($ville) {
        $this->ville = $ville;
    }

    # Méthode d'inscription dans la base de données
    public function Inscription($dict_information, $bdd) {
        // Ajouter 'siret' et 'ville' à la liste des informations de l'entreprise
        $dict_information['siret'] = $this->getSiret();
        $dict_information['ville'] = $this->getVille();

        // Extraire les colonnes et leurs valeurs
        $columns = array_keys($dict_information); // Récupère les noms des colonnes
        $values = array_values($dict_information); // Récupère les valeurs à insérer

        // Générer la partie de la requête SQL
        $column_names = implode(", ", $columns); // Concatène les noms des colonnes avec des virgules
        $placeholders = implode(", ", array_fill(0, count($columns), "?")); // Génère un placeholder "?" pour chaque colonne

        // Construire la requête d'insertion
        $query = "INSERT INTO ENTREPRISE ($column_names) VALUES ($placeholders)";

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
}

?>
