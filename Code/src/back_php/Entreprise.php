<?php
include_once("Utilisateur.php");

class Entreprise extends Utilisateur {

    protected $siret;
    protected $ville;

    public function __construct(
        $mdp,
        $email,
        $iduser = null,
        $last_name = null,
        $is_banned = null,
        $is_admin = null,
        $first_name = null,
        $birthdate = null,
        $gender = null,
        $antecedent = null,
        $siret = null,
        $ville = null
    ) {
        parent::__construct(
            $iduser,
            $mdp,
            $email,
            $last_name,
            $is_banned,
            $is_admin,
            $first_name,
            $birthdate,
            $gender,
            $antecedent
        );
        $this->siret = $siret;
        $this->ville = $ville;
    }

    // Getter pour SIRET
    public function getSiret() {
        return $this->siret;
    }

    // Setter pour SIRET
    public function setSiret($siret) {
        $this->siret = $siret;
    }

    // Getter pour Ville
    public function getVille() {
        return $this->ville;
    }

    // Setter pour Ville
    public function setVille($ville) {
        $this->ville = $ville;
    }

    // Surcharge de la méthode Connexion
    public function Connexion($email, $password, $bdd) {
        parent::Connexion($email, $password, $bdd); // Appelle la méthode de la classe parent

        if ($this->mdp == $password) { // Vérifie si le mot de passe est correct
            $data = ["id_user" => $this->iduser];
            $query = "SELECT * FROM utilisateur WHERE ID_User = :id_user;";
            $res = $bdd->getResults($query, $data);

            if ($res != []) {
                $this->birthdate = $res["date_naissance"];
                $this->first_name = $res["prenom"];
                $this->last_name = $res["nom"];
                $this->is_banned = $res["is_bannis"];
                $this->is_admin = $res["is_admin"];
                $this->gender = $res["genre"];
                $this->antecedent = $res["antecedents"];
                $this->siret = $res["siret"];
                $this->ville = $res["ville"];
            }
            $bdd->closeBD();
        }
    }

   
}
?>
