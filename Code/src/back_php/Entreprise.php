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
        // Appeler la méthode de la classe parent
        parent::Connexion($email, $password, $bdd); 
    
        // Vérifier si l'ID de l'utilisateur est bien défini après la connexion parent
        if (!$this->iduser) {
            AfficherErreur("Utilisateur non trouvé.");
            return;
        }
    
        $data = ["id_user" => $this->iduser];
        $query = "
            SELECT u.date_naissance, u.prenom, u.nom, u.genre, u.mail, u.antecedents, 
                   u.is_bannis, u.is_admin, e.siret, e.ville
            FROM utilisateur u
            LEFT JOIN entreprise e ON u.ID_User = e.siret
            WHERE u.ID_User = :id_user;
        ";
    
        // Exécution de la requête SQL
        $res = $bdd->getResults($query, $data);
    
        // Vérifier si des données ont été récupérées
        if ($res) {
            // Remplir les attributs de l'objet avec les données récupérées
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
        } else {
            AfficherErreur("Erreur lors de la récupération des informations de l'utilisateur.");
        }
    
        $bdd->closeBD(); // Fermer la connexion à la base de données
    }
    

    public function Inscription($bdd, $dict_information) {
        // Appeler la méthode Inscription de la classe Utilisateur
        parent::Inscription($bdd, $dict_information);
        // Vérifier si l'inscription de l'utilisateur a réussi
        if ($_SESSION["result"] === 1) {
            // Insérer les informations spécifiques au patient
            $this->insererCompany($dict_information);
        }
    }
    
    private function insererCompany($dict_information) {
        try {
            // Récupérer l'email de l'utilisateur ajouté
            $bdd = new Query("siteweb");
            $mail = $dict_information['mail'];
            $ville = $_SESSION['ville'];
    
            // Récupérer l'ID de l'utilisateur ajouté
            $querySelect = "SELECT ID_User FROM utilisateur WHERE mail = :mail";
            $result = $bdd->getResults($querySelect, [':mail' => $mail]);
    
            if ($result) {
                $idUser = $result['ID_User'];
    
                // Ajouter le SIRET et la ville à la table entreprise
                $queryInsert = "INSERT INTO entreprise (siret, ville) VALUES (:siret, :ville)";
                $argsInsert = [
                    ':siret' => $idUser,
                    ':ville' => $ville
                ];
                $bdd->insertLine($queryInsert, $argsInsert);
  
            } else {
                AfficherErreur("Utilisateur non trouvé avec l'email fourni.");
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            AfficherErreur("Erreur lors de l'insertion de l'entreprise : " . $e->getMessage());
            throw $e;
        }
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
            
            // Appel de la méthode `insertLine` pour exécuter l'insertion
            $bdd->insertLine($sql, [
                ':id_medecin' => $id_medecin,
                ':id_essai' => $id_essai,
                ':is_accepte' => false,  // valeur booléenne
                ':est_de_company' => true // valeur booléenne
            ]);
    
            echo "<p>Le médecin a été notifié de votre demande !</p>";
        } catch (PDOException $e) {
            // Gestion des erreurs avec un message d'erreur dans les logs
            AfficherErreur("Erreur lors de l'insertion dans ESSAI_MEDECIN : " . $e->getMessage());
            AfficherErreur("<p>Une erreur est survenue lors de la demande. Veuillez réessayer plus tard");
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
            // Requête pour insérer un nouvel essai clinique sans spécifier l'ID auto-incrémenté
            $sqlEssai = "
                INSERT INTO ESSAI (
                    ID_entreprise_ref, ID_phase, date_debut, date_fin,
                    description, molecule_test, dosage_test, molecule_ref, dosage_ref,
                    placebo_nom, a_debute
                ) VALUES (
                    :id_entreprise_ref, :ID_phase, :date_debut, :date_fin,
                    :description, :molecule_test, :dosage_test, :molecule_ref, :dosage_ref,
                    :placebo_nom, :a_debute
                );
            ";
    
            // Préparation des paramètres pour la table ESSAI
            $paramsEssai = [
                ':id_entreprise_ref' => $this->iduser,
                ':ID_phase' => $data['ID_phase'],
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
            $bdd->insertLine($sqlEssai, $paramsEssai);
    
            // Récupération de l'ID du dernier essai inséré
            $idEssai = $bdd->getLastInsertId();
    
            // Vérifiez si l'ID de l'essai est valide (il ne doit pas être 0 ou vide)
            if (empty($idEssai)) {
                throw new Exception("Erreur: L'ID de l'essai est invalide.");
            }
    
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
    
        } catch (PDOException $e) {
            // Message d'erreur en cas de problème
            echo '<p style="color: red;">Erreur lors de la création de l\'essai: ' . htmlspecialchars($e->getMessage()) . '</p>';
        } catch (Exception $e) {
            // Gestion des erreurs supplémentaires
            echo '<p style="color: red;">' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    

    function startPhase($bdd, $idEssai, $id_phase) {
        try {
            // Requête pour mettre à jour la colonne a_debute
            $query = "UPDATE ESSAI 
                      SET a_debute = :aDebute 
                      WHERE ID_essai = :idEssai AND ID_phase = :idPhase"; 
    
            // Exécution de la requête avec les paramètres
            $bdd->UpdateLines($query, [
                ':aDebute' => true,
                ':idEssai' => $idEssai,  // Correction : vous devez assigner la valeur à :idEssai
                ':idPhase' => $id_phase   // Correction : vous devez utiliser :idPhase
            ]);
    
            // Message de confirmation
            echo '<p style="color: green;">Votre essai a bien commencé.</p>';
        } catch (PDOException $e) {
            // Message d'erreur en cas de problème
            echo '<p style="color: red;">Erreur lors du démarrage de l\'essai : ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    

public function acceptMedecin($bdd, $idMedecin, $idEssai) {
    try {
        $queryUpdate = "UPDATE essai_medecin SET is_accepte = 1 WHERE ID_medecin = :idMedecin AND ID_essai = :idEssai";
        $argsUpdate = [
            ':idMedecin' => $idMedecin,
            ':idEssai' => $idEssai
        ];
        $bdd->UpdateLines($queryUpdate, $argsUpdate);
    } catch (Exception $e) {
        // Gérer les erreurs
        AfficherErreur("Erreur lors de l'acceptation du médecin : " . $e->getMessage());
        throw $e;
    }
}
}
?>
