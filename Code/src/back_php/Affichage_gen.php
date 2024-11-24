<?php
session_start();

function AfficherErreur($message) {
    echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
}


?>