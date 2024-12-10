<?php

include_once("Query.php");
include_once("Affichage_gen.php");
include_once("Patient.php");
include_once("Medecin.php");
// include_once("Entreprise.php");



/**
 * Vérifie le type de compte d'un utilisateur.
 *
 * @param int $id_user L'identifiant de l'utilisateur.
 * @param Query $bdd L'objet de connexion à la base de données.
 * @return string Le type de compte de l'utilisateur : "medecin", "entreprise" ou "patient".
 * @throws InvalidArgumentException Si les types des arguments ne sont pas corrects.
 */
function VerifyAccountType($mail_user, $bdd)
{
    if (!is_string($mail_user)) 
        throw new InvalidArgumentException('Le mail de l\'utilisateur doit être une chaine de caractères.');
    if (!($bdd instanceof Query))
        throw new InvalidArgumentException('L\'objet de connexion doit être une instance de Query.');

    $data = ["mail_user" => $mail_user];
    $query_medecin = "SELECT numero_ordre FROM medecin JOIN utilisateur ON medecin.numero_ordre = utilisateur.ID_User
	                        WHERE mail = :mail_user;";
    $query_entreprise = "SELECT siret FROM entreprise JOIN utilisateur ON entreprise.siret = utilisateur.ID_User
                            WHERE mail = :mail_user;";
    $query_admin = "SELECT ID_User FROM utilisateur 
                            WHERE mail = :mail_user AND is_admin = 1;";

    $res_medecin = $bdd->getResults($query_medecin, $data);
    $res_entreprise = $bdd->getResults($query_entreprise, $data);
    $res_admin = $bdd->getResults($query_admin, $data);

    if ($res_medecin != [])
        $out = "medecin";
    else if ($res_entreprise != []) 
        $out = "entreprise";
    else if ($res_admin != [])
        $out = "admin";
    else
        $out = "patient";
    return $out;
}

/**
 * Vérifie si un utilisateur a rempli tous les formulaires d'inscription.
 *
 * @param array $user_data Les données du patient à vérifier avant d'envoyer les infos à la bdd
 * 
 */
function checkFormFields($fields) {
    foreach ($fields as $field) {
        if (empty($_POST[$field]))
            return false;
    }
    return true;
}

/**
 * Vérifie la force du mot de passe.
 *
 * @param string $password Le mot de passe à vérifier.
 * @return bool True si le mot de passe est valide, False sinon.
 */
function validatePassword($password) {
    // Vérifie la longueur minimale
    if (strlen($password) < 8) {
        return false;
    }

    // Vérifie la présence d'au moins un chiffre
    if (!preg_match('/\d/', $password)) {
        return false;
    }

    // Vérifie la présence d'au moins un caractère spécial
    if (!preg_match('/[^a-zA-Z\d]/', $password)) {
        return false;
    }

    return true;
}

/** Vérifie si le mot de passe et sa confirmation sont identiques ET s'il est assez puissant.
 * 
 */
function checkPassword($password, $password_confirm) {
    if ($password != $password_confirm)
        return false;
    return validatePassword($password);
}

// Fonction pour inscrire un nouveau patient
function registerNewPatient() {

    $required_fields = ["Nom", "prénom", "identifiant", "genre", "origin", "medical", "mdp", "mdp2", "date_naissance"];
    
    // Vérifie que tous les champs requis sont remplis
    if (checkFormFields($required_fields)) {
        
        // Vérifie que les mots de passe correspondent
        if (checkPassword($_POST["mdp"], $_POST["mdp2"])) {
            
            $bdd = new Query("siteweb");
            // Créer un tableau avec les informations du patient
            $bdd_dict = [
                "nom" => $_POST["Nom"],
                "prenom" => $_POST["prénom"],
                "genre" => $_POST["genre"],
                "origine" => $_POST["origin"],
                "antecedents" => $_POST["medical"],
                "mail" => $_POST["identifiant"],
                "mdp" => $_POST["mdp"],
                "date_naissance" => $_POST["date_naissance"]
            ];
            // Appeler la fonction d'inscription
            $patient = new Patient($_POST["mdp"], $_POST["identifiant"]);
            $patient->Inscription($bdd, $bdd_dict);
            // Vérifier le résultat
            if ($_SESSION["result"] == 1) {
                // Succès : redirection vers la page d'accueil
                header("Location: page_accueil.php");
                exit;
            } else {
                // Échec : afficher le message d'erreur
                AfficherErreur($_SESSION["result"]);
                exit;
            }
        } else {
            // Les mots de passe ne correspondent pas
            AfficherErreur("Passwords do not match or are not strong enough");
        }
    } else {
        // Des champs obligatoires sont manquants
        AfficherErreur("Please fill all the fields");
    }
    session_abort(); //Fermer la session après inscription
}


// Fonction pour inscrire un nouveau médecin
function registerNewDoctor() {
    $required_fields = ["identifiant", "mdp", "mdp2", "num_ordre", "hopital", "specialite"];
    if (checkFormFields($required_fields)) {
        if (checkPassword($_POST["mdp"], $_POST["mdp2"])) {
            $bdd = new Query("siteweb");
            // Créer un nouvel objet médecin
            $doctor = new Medecin($_POST["mdp"], $_POST["identifiant"]);
            $bdd_dict = [
                "mail" => $_POST["identifiant"],
                "mdp" => $_POST["mdp"],
                "numero_ordre" => $_POST["num_ordre"],
                "hopital" => $_POST["hopital"],
                "specialite" => $_POST["specialite"]
            ];
            // Inscrire le médecin
            $doctor->Inscription($bdd, $bdd_dict);

            if ($_SESSION["result"] == 1) {
                // Succès : redirection vers la page d'accueil
                header("Location: page_accueil.php");
                exit;
            } else {
                // Échec : afficher le message d'erreur
                AfficherErreur($_SESSION["result"]);
                exit;
            }
        } else {
            AfficherErreur("Passwords do not match");
        }
    } else {
        AfficherErreur("Please fill all the fields");
    }
}

// Fonction pour inscrire une nouvelle entreprise
function registerNewCompany() {
    $required_fields = ["identifiant", "mdp", "mdp2", "nom_entreprise", "siret", "ville"];
    if (checkFormFields($required_fields)) {
        if (checkPassword($_POST["mdp"], $_POST["mdp2"])) {
            $bdd = new Query("siteweb");
            // Créer un nouvel objet entreprise
            $company = new Entreprise($_POST["mdp"], $_POST["identifiant"]);
            $bdd_dict = [
                "mail" => $_POST["identifiant"],
                "mdp" => $_POST["mdp"],
                "siret" => $_POST["siret"],
                "nom_entreprise" => $_POST["nom_entreprise"],
                "ville" => $_POST["ville"]
            ];
            // Inscrire l'entreprise
            $company->Inscription($bdd, $bdd_dict);
            
            if ($_SESSION["result"] == 1) {
                // Succès : redirection vers la page d'accueil
                header("Location: page_accueil.php");
                exit;
            } else {
                // Échec : afficher le message d'erreur
                AfficherErreur($_SESSION["result"]);
                exit;
            }
            
        } else {
            AfficherErreur("Passwords do not match");
        }
    } else {
        AfficherErreur("Please fill all the fields");
    }
}


?>