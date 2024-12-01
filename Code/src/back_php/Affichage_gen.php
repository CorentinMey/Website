<?php

function AfficherErreur($message) {
    echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
}

function AfficherInfo($message, $id_essai, $action) {
    echo '<div class="info-message" id="notif">';
        echo htmlspecialchars($message);
        // ajout button pour fermer la notification
        echo "<form action='' method='post'>";
            echo '<input type="hidden" name="id_essai" value="'.htmlspecialchars($id_essai).'">'; // utiles pour gérer les actions en fonction des id des essais
            echo '<input type="hidden" name="Action" value="'.htmlspecialchars($action).'">';
            echo '<button type="submit" class="button" id="close_notif">X</button>';
        echo "</form>";
    echo '</div>';
}

/**
 * FOnction qui afficher une boite de confirmation
 * @param $message : message à afficher
 * @param $id_essai : id de l'essai concerné
 * @param $action : tableau contenant les actions à effectuer (oui, non)
 */
function AfficherConfirmation($message, $id_essai, $action) {
    echo '<div class="confirm-message">';
    echo htmlspecialchars($message);
    // ajout bouton pour confirmer les choix
    echo "<form action='' method='post'>";
        echo '<input type="hidden" name="id_essai" value="'.htmlspecialchars($id_essai).'">'; // utiles pour gérer les actions en fonction des id des essais
        echo '<button type="submit" class="button" name="Action" value="'.htmlspecialchars($action[0]).'">Yes</button>';
        echo '<button type="submit" class="button" name="Action" value="'.htmlspecialchars($action[1]).'">No</button>';
    echo "</form>";
    echo '</div>';
}

?>