
<?php

include_once("Affichage_admin.php");
// Fonction pour bannir un utilisateur
function banUser($userId, $query, $context) {
    $sql = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userId";
    $query->UpdateLines($sql, [':userId' => $userId]);

    $message = "User banned successfully! (get in jail loser)";

    if ($context === 'company_mode') {
        afficherListeEntreprises($query,$message);
    } elseif ($context === 'doctor_mode') {
        afficherListeMedecins($query, $message);
    } elseif ($context === 'users_mode'){
        afficherListeUtilisateurs($query, $message);
    } else {
        header("Location: ../page/page_admin.php");
    }
    exit;
}

// Fonction pour débannir un utilisateur
function unbanUser($userId, $query, $context) {
    $sql = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userId";
    $query->UpdateLines($sql, [':userId' => $userId]);

    $message = "A user HAS been set free !! (uban)";

    if ($context === 'company_mode') {
        afficherListeEntreprises($query, $message);
    } elseif ($context === 'doctor_mode') {
        afficherListeMedecins($query, $message);
    } elseif ($context === 'users_mode'){
        afficherListeUtilisateurs($query, $message);
    } else {
        header("Location: ../page/page_admin.php");
    }
    exit;
}

// Fonction pour accepter une demande (passage de 2 à 0)
function acceptUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userId AND is_bannis = 2";
    $query->UpdateLines($sql, [':userId' => $userId]);

    $message = "You accepted a inquiry";
    afficherConfirmationsEnAttente($query, $message);
    exit;
}

// Fonction pour rejeter une demande (passage de 2 à 1)
function rejectUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userId AND is_bannis = 2";
    $query->UpdateLines($sql, [':userId' => $userId]);

    $message = "Refused ?? i guess its deserved";
    afficherConfirmationsEnAttente($query, $message);
    exit;
}

?>
