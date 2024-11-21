<?php
function hashPassword($password) {
    // Générer le hash bcrypt pour le mot de passe passé en entrée
    return password_hash($password, PASSWORD_BCRYPT);
}
?>
