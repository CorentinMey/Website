<?php

function AfficherErreur($message) {
    echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
}

function AfficherInfo($message) {
    echo '<div class="info-message">' . htmlspecialchars($message) . '</div>';
}


?>