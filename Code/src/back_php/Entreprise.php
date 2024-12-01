<?php

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
        $origins = null,
        $ville = null,
        $siret = null
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
            $antecedent,
            $ville,
            $siret
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

    public function Connexion($email, $password, $bdd)
    {
        parent::Connexion($email, $password, $bdd); // Appelle la méthode de la classe parent pour les bases
    
        $data = ["id_user" => $this->iduser];
        $query = "
            SELECT u.date_naissance, u.prenom, u.nom, u.genre, u.mail, u.antecedents, 
                   u.is_bannis, u.is_admin, e.siret, e.ville
            FROM utilisateur u
            LEFT JOIN entreprise e ON u.ID_User = e.siret
            WHERE u.ID_User = :id_user;
        ";
        $res = $bdd->getResults($query, $data);
    
        if ($res != []) {
            $this->birthdate = $res["date_naissance"];
            $this->first_name = $res["prenom"];
            $this->last_name = $res["nom"];
            $this->is_banned = $res["is_bannis"];
            $this->is_admin = $res["is_admin"];
            $this->gender = $res["genre"];
            $this->antecedent = $res["antecedents"];
            $this->email = $res["mail"];
    
            $this->siret = $res["siret"];
            $this->ville = $res["ville"];
        }
    
        $bdd->closeBD();
    }
    
    /**
     * Ajoute une demande de médecin à un essai clinique dans la base de données.
     *
     * @param int $id_medecin L'ID du médecin.
     * @param PDO $bdd L'objet PDO pour la base de données.
     * @param int $id_essai L'ID de l'essai.
     */
    public function DemandMedecin($id_medecin, $bdd, $id_essai) {
        try {
            $sql = "INSERT INTO ESSAI_MEDECIN (ID_medecin, ID_essai, is_accepte, est_de_company) 
                    VALUES (:id_medecin, :id_essai, :is_accepte, :est_de_company)";
            
            $stmt = $bdd->prepare($sql);
            
            $stmt->bindValue(':id_medecin', $id_medecin, PDO::PARAM_INT);
            $stmt->bindValue(':id_essai', $id_essai, PDO::PARAM_INT);
            $stmt->bindValue(':is_accepte', false, PDO::PARAM_BOOL);
            $stmt->bindValue(':est_de_company', false, PDO::PARAM_BOOL);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion dans ESSAI_MEDECIN : " . $e->getMessage());
        }
    }

    /**
     * Ajoute un nouvel essai clinique avec une phase choisie ou par défaut.
     *
     * @param PDO $bdd L'objet PDO pour interagir avec la base de données.
     * @param array $data Les données de l'essai clinique.
     * @param int $id_phase L'ID de la phase. Défaut : 1.
     */
    public function NewPhase($bdd, $data, $id_phase = 1) {
        try {
            $sql = "
                INSERT INTO ESSAI (
                    ID_essai, ID_phase, ID_entreprise_ref, date_debut, date_fin,
                    description, molecule_test, dosage_test, molecule_ref, dosage_ref,
                    placebo_nom, a_debute
                ) VALUES (
                    :id_essai, :id_phase, :id_entreprise_ref, :date_debut, :date_fin,
                    :description, :molecule_test, :dosage_test, :molecule_ref, :dosage_ref,
                    :placebo_nom, :a_debute
                );
            ";
            $stmt = $bdd->prepare($sql);

            // Binding des paramètres avec les valeurs par défaut ou personnalisées
            $stmt->bindValue(':id_essai', $data['id_essai'], PDO::PARAM_INT);
            $stmt->bindValue(':id_phase', $id_phase, PDO::PARAM_INT); // Phase personnalisée ou par défaut
            $stmt->bindValue(':id_entreprise_ref', $this->iduser, PDO::PARAM_INT);
            $stmt->bindValue(':date_debut', $data['date_debut'], PDO::PARAM_);
            $stmt->bindValue(':date_fin', $data['date_fin'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindValue(':molecule_test', $data['molecule_test'], PDO::PARAM_STR);
            $stmt->bindValue(':dosage_test', $data['dosage_test'], PDO::PARAM_STR);
            $stmt->bindValue(':molecule_ref', $data['molecule_ref'], PDO::PARAM_STR);
            $stmt->bindValue(':dosage_ref', $data['dosage_ref'], PDO::PARAM_STR);
            $stmt->bindValue(':placebo_nom', $data['placebo_nom'], PDO::PARAM_STR);
            $stmt->bindValue(':a_debute', false, PDO::PARAM_BOOL); // a_debute par défaut à false

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création d'un essai avec phase : " . $e->getMessage());
        }
    }
    public function TerminerPhase($bdd, $id_essai) {
        try {
            // Récupérer la date et l'heure actuelles au format SQL
            $date_fin = date('Y-m-d H:i:s');
            
            // Préparer la requête de mise à jour
            $sql = "
                UPDATE ESSAI
                SET date_fin = :date_fin
                WHERE ID_essai = :id_essai
            ";
    
            $stmt = $bdd->prepare($sql);
            
            // Lier les valeurs
            $stmt->bindValue(':date_fin', $date_fin, PDO::PARAM_STR);
            $stmt->bindValue(':id_essai', $id_essai, PDO::PARAM_INT);
    
            // Exécuter la requête
            $stmt->execute();
        } catch (PDOException $e) {
            // Gestion des erreurs
            error_log("Erreur lors de la mise à jour de la date de fin : " . $e->getMessage());
        }
    }
    
}

?>
