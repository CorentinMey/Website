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
    public function DemandMedecin($id_medecin, Query $bdd, $id_essai) {
        try {
            // Requête SQL pour insérer un lien entre un essai et un médecin
            $sql = "INSERT INTO ESSAI_MEDECIN (ID_medecin, ID_essai, is_accepte, est_de_company) 
                    VALUES (:id_medecin, :id_essai, :is_accepte, :est_de_company)";
            
            // Appel de la méthode `insertLines` pour exécuter l'insertion
            $bdd->insertLines($sql, [
                ':id_medecin' => $id_medecin,
                ':id_essai' => $id_essai,
                ':is_accepte' => false,  // valeur booléenne
                ':est_de_company' => true // valeur booléenne
            ]);
    
            echo "<p>Le médecin a été notifié de votre demande !</p>";
        } catch (PDOException $e) {
            // Gestion des erreurs avec un message d'erreur dans les logs
            error_log("Erreur lors de l'insertion dans ESSAI_MEDECIN : " . $e->getMessage());
            echo "<p>Une erreur est survenue lors de la demande. Veuillez réessayer plus tard.</p>";
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
        // Début d'une transaction pour garantir la cohérence des insertions
        $bdd->beginTransaction();

        // Requête pour insérer un nouvel essai clinique
        $sqlEssai = "
            INSERT INTO ESSAI (
                ID_entreprise_ref, date_debut, date_fin,
                description, molecule_test, dosage_test, molecule_ref, dosage_ref,
                placebo_nom, a_debute, nombre_patient_ideal
            ) VALUES (
                :id_entreprise_ref, :date_debut, :date_fin,
                :description, :molecule_test, :dosage_test, :molecule_ref, :dosage_ref,
                :placebo_nom, :a_debute, :nombre_patient_ideal
            );
        ";

        // Préparation des paramètres pour la table ESSAI
        $paramsEssai = [
            ':id_entreprise_ref' => $this->iduser,
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':description' => $data['description'],
            ':molecule_test' => $data['molecule_test'],
            ':dosage_test' => $data['dosage_test'],
            ':molecule_ref' => $data['molecule_ref'],
            ':dosage_ref' => $data['dosage_ref'],
            ':placebo_nom' => $data['placebo_nom'],
            ':a_debute' => false, // Par défaut, l'essai n'a pas commencé
        ];

        // Exécution de l'insertion pour ESSAI
        $bdd->insertLines($sqlEssai, $paramsEssai);

        // Récupération de l'ID du dernier essai inséré
        $idEssai = $bdd->lastInsertId();

        // Requête pour insérer une phase initiale
        $sqlPhase = "
            INSERT INTO PHASE (
                ID_essai, ID_phase, date_debut, date_fin_prevue, nombre_patients
            ) VALUES (
                :id_essai, :id_phase, :date_debut, :date_fin_prevue, :nombre_patients
            );
        ";

        // Préparation des paramètres pour la table PHASE
        $paramsPhase = [
            ':id_essai' => $idEssai,
            ':id_phase' => $id_phase, // Phase par défaut (1)
            ':date_debut' => $data['date_debut'],
            ':date_fin_prevue' => $data['date_fin'],
            ':nombre_patients' => 0, // Nombre de patients initialisé à 0
        ];

        // Exécution de l'insertion pour PHASE
        $bdd->insertLines($sqlPhase, $paramsPhase);

        // Validation de la transaction
        $bdd->commit();

    } catch (PDOException $e) {
        // Annulation de la transaction en cas d'erreur
        $bdd->rollBack();
        error_log("Erreur lors de la création d'un essai avec phase : " . $e->getMessage());
    }
}
}
?>
