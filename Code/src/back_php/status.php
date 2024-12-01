
<?php
// Fonction pour bannir un utilisateur
function banUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userId";
    $query->UpdateLines($sql, [':userId' => $userId]);
}

// Fonction pour débannir un utilisateur
function unbanUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userId";
    $query->UpdateLines($sql, [':userId' => $userId]);
}

// Fonction pour accepter une demande (passage de 2 à 0)
function acceptUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :userId AND is_bannis = 2";
    $query->UpdateLines($sql, [':userId' => $userId]);
}

// Fonction pour rejeter une demande (passage de 2 à 1)
function rejectUser($userId, $query) {
    $sql = "UPDATE utilisateur SET is_bannis = 1 WHERE ID_User = :userId AND is_bannis = 2";
    $query->UpdateLines($sql, [':userId' => $userId]);
}

?>
