
<?php
// ce fichier contient les commandes d'actions utilisées par l'admin : ban/uban/accept/reject.

// appelle des fichiers necessaires
include_once("Affichage_admin.php");




/**
 * Méthode pour bannir un utilisateur
 * @param int $userID L'identifiant unique de l'utilisateur
 * @param object $query L'objet de connexion à la base de données
 * @param string $context Les informations du formulaire pour la redirection
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour bannir un utilisateur
function banUser($userID, $query, $context, $limit=null) {
    try {
        // Validation des paramètres et utilisation des exceptions
        if (!is_int($userID) || $userID <= 0) {
            throw new Exception("Invalid user ID: must be a positive integer.");
        }
        if (is_null($query)) {
            throw new Exception("Query object cannot be null.");
        }
        if (!in_array($context, ['company_mode', 'doctor_mode', 'users_mode'], true)) {
            throw new Exception("Invalid context: must be one of 'company_mode', 'doctor_mode', or 'users_mode'.");
        }
        if (!is_null($limit) && (!is_int($limit) || $limit <= 0)) {
            throw new Exception("The limit must be a positive integer or null.");
        }
    // Requête pour récupérer le nom de l'utilisateur
    $sqlGetName = "SELECT nom FROM utilisateur WHERE ID_User = :userID";
    $result = $query->getResults($sqlGetName, [':userID' => $userID]);
    if ($result) {
        $nom_user = $result['nom'];
        // Mise à jour pour bannir l'utilisateur
        $sqlBanUser = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userID";
        $query->UpdateLines($sqlBanUser, [':userID' => $userID]);
        // Message avec le nom de l'utilisateur
        $message = "User '$nom_user' banned successfully! (Get in jail, loser)";
    } else {
        // Si l'utilisateur n'existe pas, retour d'un message d'erreur
        $message = "Error: User not found with ID $userID.";
    }
    // Redirection ou appel de la fonction d'affichage en fonction du mode
    if ($context === 'company_mode') {
        afficherListeEntreprises($query, $message, $limit);
    } elseif ($context === 'doctor_mode') {
        afficherListeMedecins($query, $message, $limit);
    } elseif ($context === 'users_mode') {
        afficherListeUtilisateurs($query, $message, $limit);
    } else {
        header("Location: ../page/page_admin.php");
    }

    } catch (Exception $e) {
        // Gestion des exceptions
        echo "Error: " . $e->getMessage();
    }
}

/**
 * fonction pour débannir un utilisateur
 * @param int $userID L'identifiant unique de l'utilisateur
 * @param object $query L'objet de connexion à la base de données
 * @param string $context Les informations du formulaire pour la redirection
 * @param int $limit combien de display dans la redirection de la liste
 */
function unbanUser($userID, $query, $context, $limit=null) {
    try {
        // Validation des paramètres et utilisation des exceptions
        if (!is_int($userID) || $userID <= 0) {
            throw new Exception("Invalid user ID: must be a positive integer.");
        }
        if (is_null($query)) {
            throw new Exception("Query object cannot be null.");
        }
        if (!in_array($context, ['company_mode', 'doctor_mode', 'users_mode'], true)) {
            throw new Exception("Invalid context: must be one of 'company_mode', 'doctor_mode', or 'users_mode'.");
        }
        if (!is_null($limit) && (!is_int($limit) || $limit <= 0)) {
            throw new Exception("The limit must be a positive integer or null.");
        }
    // Requête pour récupérer le nom de l'utilisateur
    $sqlGetName = "SELECT nom FROM utilisateur WHERE ID_User = :userID";
    $result = $query->getResults($sqlGetName, [':userID' => $userID]);
    if ($result) {
        $nom_user = $result['nom'];
        // Mise à jour pour débannir l'utilisateur
        $sqlUnbanUser = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userID";
        $query->UpdateLines($sqlUnbanUser, [':userID' => $userID]);
        // Message avec le nom de l'utilisateur
        $message = "User '$nom_user' has been set free! (Unbanned)";
    } else {
        // Si l'utilisateur n'existe pas, retour d'un message d'erreur
        $message = "Error: User not found with ID $userID.";
    }
    // Redirection ou appel de la fonction d'affichage en fonction du mode
    if ($context === 'company_mode') {
        afficherListeEntreprises($query, $message, $limit);
    } elseif ($context === 'doctor_mode') {
        afficherListeMedecins($query, $message, $limit);
    } elseif ($context === 'users_mode') {
        afficherListeUtilisateurs($query, $message, $limit);
    } else {
        header("Location: ../page/page_admin.php");
    }
    } catch (Exception $e) {
        // Gestion des exceptions
        echo "Error: " . $e->getMessage();
    }
}


/**
 * fonction pour accepter un utilisateur
 * @param int $userID L'identifiant unique de l'utilisateur
 * @param object $query L'objet de connexion à la base de données
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour accepter une demande (passage de 2 à 0)
function acceptUser($userID, $query, $limit=null) {
    try {
        // Validation des paramètres  et utilisation des exceptions
        if (!is_int($userID) || $userID <= 0) {
            throw new Exception("Invalid user ID: must be a positive integer.");
        }
        if (is_null($query)) {
            throw new Exception("Query object cannot be null.");
        }
        if (!is_null($limit) && (!is_int($limit) || $limit <= 0)) {
            throw new Exception("The limit must be a positive integer or null.");
        }
    $sqlGetName = "SELECT nom FROM utilisateur WHERE ID_User = :userID";
    $result = $query->getResults($sqlGetName, [':userID' => $userID]);
    if ($result) {
        $nom_user = $result['nom'];
        // Mise à jour pour débannir l'utilisateur
        $sqlacceptuser = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userID AND is_bannis =2";
        $query->UpdateLines($sqlacceptuser, [':userID' => $userID]);
        // Message avec le nom de l'utilisateur
        $message = "User '$nom_user' has been accepted as a permanent member ";
    } else {
        // Si l'utilisateur n'existe pas, retour d'un message d'erreur
        $message = "Error: User not found with ID $userID.";
    }
    afficherConfirmationsEnAttente($query, $message, $limit);
    } catch (Exception $e) {
        // Gestion des exceptions
        echo "Error: " . $e->getMessage();
    }
    
}

/**
 * fonction pour refuser un utilisateur
 * @param int $userID L'identifiant unique de l'utilisateur
 * @param object $query L'objet de connexion à la base de données
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour rejeter une demande (passage de 2 à 1)
function rejectUser($userID, $query, $limit=null) {
    try {
        // Validation des paramètres  et utilisation des exceptions
        if (!is_int($userID) || $userID <= 0) {
            throw new Exception("Invalid user ID: must be a positive integer.");
        }
        if (is_null($query)) {
            throw new Exception("Query object cannot be null.");
        }
        if (!is_null($limit) && (!is_int($limit) || $limit <= 0)) {
            throw new Exception("The limit must be a positive integer or null.");
        }
    $sqlGetName = "SELECT nom FROM utilisateur WHERE ID_User = :userID";
    $result = $query->getResults($sqlGetName, [':userID' => $userID]);
    if ($result) {
        $nom_user = $result['nom'];
        // Mise à jour pour débannir l'utilisateur
        $sqlrejectuser = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userID AND is_bannis = 2";
        $query->UpdateLines($sqlrejectuser, [':userID' => $userID]);
        // Message avec le nom de l'utilisateur
        $message = "Refused '$nom_user' ?? i guess it is deserved";
    } else {
        // Si l'utilisateur n'existe pas, retour d'un message d'erreur
        $message = "Error: User not found with ID $userID.";
    }
    afficherConfirmationsEnAttente($query, $message);
    } catch (Exception $e) {
        // Gestion des exceptions
        echo "Error: " . $e->getMessage();
    }
}

?>
