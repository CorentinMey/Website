<?php
// Inclure la classe Query
include_once("Query.php");

// Fonction pour afficher la liste des utilisateurs
/**
 * @param Query $query
 */
function afficherListeUtilisateurs($query) {
    $users = $query->getResultsAll("SELECT ID_User, prenom, nom, genre, is_bannis FROM utilisateur", []);
    echo '<div class="content-wrapper">';
    echo "<h2>USER LIST</h2>";
    if (!empty($users)) {
        echo "<ul>";
        foreach ($users as $user) {
            $boxClass = $user['is_bannis'] == 1 ? 'box_list banned' : 'box_list';
            echo '<div class="' . htmlspecialchars($boxClass) . '">';
            echo '    <div class="user-info">';
            echo '        <p><strong>Nom:</strong> ' . htmlspecialchars($user['nom']) . '</p>';
            echo '        <p><strong>Prénom:</strong> ' . htmlspecialchars($user['prenom']) . '</p>';
            echo '        <p><strong>Genre:</strong> ' . htmlspecialchars($user['genre']) . '</p>';
            echo '    </div>';
            echo '    <div class="user-actions">';
            echo '        <h3>View Profile</h3>';
            echo '    </div>';
            echo '    <div class="user-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '              <input type="hidden" name="LID" value="' . htmlspecialchars($user['ID_User']) . '">';
            echo '            <button type="submit" name="action" value="ban" class="ban-btn">Ban</button>';
            echo '            <button type="submit" name="action" value="unban" class="unban-btn">Unban</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No users found.</p>";
    }
}

// Fonction pour afficher la liste des médecins
function afficherListeMedecins($query) {
    echo '<div class="content-wrapper">';
    echo "<h2>DOCTOR LIST</h2>";
    $doctors = $query->getResultsAll(
        "SELECT ID_User, nom, prenom, genre, mail, is_bannis FROM utilisateur INNER JOIN medecin WHERE ID_USER = numero_ordre",
        []
    );
    if (!empty($doctors)) {
        echo "<ul>";
        foreach ($doctors as $doctor) {
            $boxClass = $doctor['is_bannis'] == 1 ? 'box_list banned' : 'box_list';
            echo '<div class="' . htmlspecialchars($boxClass) . '">';
            echo '    <div class="user-info">';
            echo '        <p><strong>Nom:</strong> ' . htmlspecialchars($doctor['nom']) . '</p>';
            echo '        <p><strong>Prénom:</strong> ' . htmlspecialchars($doctor['prenom']) . '</p>';
            echo '        <p><strong>Genre:</strong> ' . htmlspecialchars($doctor['genre']) . '</p>';
            echo '        <p><strong>Email:</strong> ' . htmlspecialchars($doctor['mail']) . '</p>';
            echo '    </div>';
            echo '    <div class="user-actions">';
            echo '        <h3>View Profile</h3>';
            echo '    </div>';
            echo '    <div class="user-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '              <input type="hidden" name="LID" value="' . htmlspecialchars($doctor['ID_User']) . '">';
            echo '            <button type="submit" name="action" value="ban" class="ban-btn">Ban</button>';
            echo '            <button type="submit" name="action" value="unban" class="unban-btn">Unban</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No doctors found.</p>";
    }
}

// Fonction pour afficher la liste des entreprises
function afficherListeEntreprises($query) {
    echo '<div class="content-wrapper">';
    echo "<h2>Company List</h2>";
    $companies = $query->getResultsAll(
        "SELECT ID_User, siret, ville, is_bannis FROM entreprise INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret", []
    );
    if (!empty($companies)) {
        echo "<ul>";
        foreach ($companies as $company) {
            $boxClass = $company['is_bannis'] == 1 ? 'box_list banned' : 'box_list';
            echo '<div class="' . htmlspecialchars($boxClass) . '">';
            echo '    <div class="company-info">';
            echo '        <p><strong>Siret:</strong> ' . htmlspecialchars($company['siret']) . '</p>';
            echo '        <p><strong>Ville:</strong> ' . htmlspecialchars($company['ville']) . '</p>';
            echo '    </div>';
            echo '    <div class="company-actions">';
            echo '        <h3>View details</h3>';
            echo '    </div>';
            echo '    <div class="company-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '              <input type="hidden" name="LID" value="' . htmlspecialchars($company['ID_User']) . '">';
            echo '            <button type="submit" name="action" value="ban" class="ban-btn">Ban</button>';
            echo '            <button type="submit" name="action" value="unban" class="unban-btn">Unban</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No companies found.</p>";
    }
}

// Fonction pour afficher la liste des essais cliniques
function afficherListeEssaisCliniques($query) {
    echo '<div class="content-wrapper">';
    echo "<h2>Clinical Assay List</h2>";
    $assays = $query->getResultsAll(
        "SELECT essai.description, utilisateur.nom AS nom_entreprise FROM essai INNER JOIN utilisateur ON utilisateur.ID_User = essai.ID_entreprise_ref;", []
    );
    if (!empty($assays)) {
        echo "<ul>";
        foreach ($assays as $assay) {
            echo '<div class="box_list">';
            echo '    <div class="assay-info">';
            echo '        <p><strong>Description:</strong> ' . htmlspecialchars($assay['description']) . '</p>';
            echo '        <p><strong>Nom de l\'entreprise:</strong> ' . htmlspecialchars($assay['nom_entreprise']) . '</p>';
            echo '    </div>';
            echo '    <div class="assay-actions">';
            echo '        <h3>View Details</h3>';
            echo '    </div>';
            echo '    <div class="assay-controls">';
            echo '        <form method="POST" action="manage_assay.php" class="action-form">';
            echo '            <input type="hidden" name="description" value="' . htmlspecialchars($assay['description']) . '">';
            echo '            <button type="submit" name="action" value="edit" class="edit-btn">Edit</button>';
            echo '            <button type="submit" name="action" value="delete" class="delete-btn">Delete</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No assays found.</p>";
    }
}

// Fonction pour afficher les confirmations en attente
function afficherConfirmationsEnAttente($query) {
    echo '<div class="content-wrapper">';
    echo "<h2>Pending Confirmations</h2>";
    $pendingConfirmations = $query->getResultsAll(
        "SELECT ID_User, prenom, nom, mail, genre FROM utilisateur WHERE is_bannis = 2", []
    );
    if (!empty($pendingConfirmations)) {
        echo "<ul>";
        foreach ($pendingConfirmations as $user) {
            echo '<div class="box_list">';
            echo '    <div class="user-info">';
            echo '        <p><strong>Prénom:</strong> ' . htmlspecialchars($user['prenom']) . '</p>';
            echo '        <p><strong>Nom:</strong> ' . htmlspecialchars($user['nom']) . '</p>';
            echo '        <p><strong>Email:</strong> ' . htmlspecialchars($user['mail']) . '</p>';
            echo '        <p><strong>Genre:</strong> ' . htmlspecialchars($user['genre']) . '</p>';
            echo '    </div>';
            echo '    <div class="user-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '            <input type="hidden" name="LID" value="' . htmlspecialchars($user['ID_User']) . '">';
            echo '            <button type="submit" name="action" value="accept" class="approve-btn">Approve</button>';
            echo '            <button type="submit" name="action" value="reject" class="reject-btn">Reject</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No pending confirmations.</p>";
    }
    echo '</div>';
}
?>
