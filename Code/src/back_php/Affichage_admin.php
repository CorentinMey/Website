<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_admin_responsive.css">
</head>
<body>
<img src="../Ressources/Images/background_admin2.jpg" alt="fond" id="fond">
<?php
// Inclure la classe Query
include_once("Query.php");

// Fonction pour afficher la liste des utilisateurs
/**
 * @param Query $query
 */
function afficherListeUtilisateurs($query, $message = null) {
    $users = $query->getResultsAll("SELECT ID_User, prenom, mail, nom, genre, is_bannis FROM utilisateur WHERE is_bannis!=2 AND is_admin=0", []);
    
    echo '<div class="content-wrapper">';
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>USER LIST</h2>";
    echo '</div>';
    if ($message) {
        echo '<div class="information_move">' . htmlspecialchars($message) . '</div>';
    }
    if (!empty($users)) {
        echo "<ul>";
        foreach ($users as $user) {
            $boxClass = $user['is_bannis'] == 1 ? 'box_list banned' : 'box_list';
            echo '<div class="' . htmlspecialchars($boxClass) . '">';
            echo '    <div class="user-info">';
            echo '        <p><strong>Nom:</strong> ' . htmlspecialchars($user['nom']) . '</p>';
            echo '        <p><strong>Prénom:</strong> ' . htmlspecialchars($user['prenom']) . '</p>';
            echo '        <p><strong>Genre:</strong> ' . htmlspecialchars($user['genre']) . '</p>';
            echo '        <p><strong>e-mail:</strong> ' . htmlspecialchars($user['mail']) . '</p>';
            echo '    </div>';
            echo '    <div class="user-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '            <input type="hidden" name="LID" value="' . htmlspecialchars($user['ID_User']) . '">';
            echo '            <input type="hidden" name="context" value="users_mode">';
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
function afficherListeMedecins($query, $message = null) {
    echo '<div class="content-wrapper">';
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>DOCTOR LIST</h2>";
    echo '</div>';
    if ($message) {
        echo '<div class="information_move">' . htmlspecialchars($message) . '</div>';
    }

    // Affichage de la liste des médecins
    $doctors = $query->getResultsAll(
        "SELECT ID_User, nom, prenom, genre, mail, is_bannis, hopital, domaine FROM utilisateur INNER JOIN medecin WHERE ID_USER = numero_ordre AND is_bannis!=2",
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
            // Lien pour voir le profil
            echo '        <a href="../page/page_profil_doc.php?id=' . htmlspecialchars($doctor['ID_User']) . '" class="view-details">View Profile</a>';
            echo '    </div>';
            echo '    <div class="user-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '            <input type="hidden" name="LID" value="' . htmlspecialchars($doctor['ID_User']) . '">';
            echo '            <input type="hidden" name="context" value="doctor_mode">';
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

    // Si un ID est passé dans l'URL, afficher le profil du médecin
    if (isset($_GET['id'])) {
        afficherProfilMedecin($query, $_GET['id']);
    }
}

// Fonction pour afficher la liste des entreprises
function afficherListeEntreprises($query, $message=null) {
    echo '<div class="content-wrapper">';
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>COMPANY LIST</h2>";
    echo '</div>';
    if ($message) {
        echo '<div class="information_move">' . htmlspecialchars($message) . '</div>';
    }
    $companies = $query->getResultsAll(
        "SELECT ID_User, siret, ville, is_bannis FROM entreprise INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret AND is_bannis!=2", []
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
            echo '        <a href="../page/page_profil_ent.php?id=' . htmlspecialchars($company['ID_User']) . '" class="view-details">View details</a>';
            echo '    </div>';
            echo '    <div class="company-controls">';
            echo '        <form method="POST" action="../back_php/manage_user.php" class="action-form">';
            echo '            <input type="hidden" name="LID" value="' . htmlspecialchars($company['ID_User']) . '">';
            echo '            <input type="hidden" name="context" value="company_mode">';
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
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>CLINICAL ASSAY LIST</h2>";
    echo '</div>';
    
    $assays = $query->getResultsAll(
        "SELECT essai.description, essai.date_debut, essai.date_fin, molecule_test, molecule_ref, a_debute, utilisateur.nom AS nom_entreprise FROM essai INNER JOIN utilisateur ON utilisateur.ID_User = essai.ID_entreprise_ref", []
    );
    if (!empty($assays)) {
        echo "<ul>";
        foreach ($assays as $assay) {
            echo '<div class="box_list">';
            echo '    <div class="assay-info">';
            echo '        <p><strong>Description:</strong> ' . htmlspecialchars($assay['description']) . '</p>';
            echo '        <p><strong>Company name:</strong> ' . htmlspecialchars($assay['nom_entreprise']) . '</p>';
            echo '        <p><strong>Start:</strong> '. htmlspecialchars($assay['date_debut']) . '</p>';
            echo '        <p><strong>End:</strong> '. htmlspecialchars($assay['date_fin']) . '</p>';
            echo '        <p><strong>Test molecule:</strong> '. htmlspecialchars($assay['molecule_test']) . '</p>';
            echo '        <p><strong>Reference molecule:</strong> '. htmlspecialchars($assay['molecule_ref']) . '</p>';
            echo '        <p><strong>Has it started yet ?:</strong> '. ($assay['a_debute'] == 1 ? 'Yes' : 'No') . '</p>';
            echo '</div>';
            echo '</div>';
        }
        echo "</ul>";
    } else {
        echo "<p>No assays found.</p>";
    }
}

// Fonction pour afficher les confirmations en attente
function afficherConfirmationsEnAttente($query, $message=null) {
    echo '<div class="content-wrapper">';
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>Pending Confirmations</h2>";
    echo '</div>';
    if ($message) {
        echo '<div class="information_move">' . htmlspecialchars($message) . '</div>';
    }
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
        echo "<h4>No pending confirmations.</h4>";
    }
    echo '</div>';
}

function afficherInfoAdmin($mail, $query) {
    // Requête SQL pour récupérer les informations de l'admin
    $sql = "SELECT nom, prenom, mail, genre, date_naissance, origine FROM utilisateur WHERE mail = :mail";
    $params = [
        ':mail' => $mail,
    ];

    // Exécution de la requête
    $result = $query->getResults($sql, $params);

    if ($result) {
        // Afficher les informations de l'admin
        echo '<div id="profile">';
        echo '<img src="../Ressources/Images/profile_fond.jpg" alt="fond" id="fond">';
        echo '<h2>Your profile :</h2>';
        echo '<p><strong>Name :</strong> ' . htmlspecialchars($result['nom']) . '</p>';
        echo '<p><strong>First name :</strong> ' . htmlspecialchars($result['prenom']) . '</p>';
        echo '<p><strong>Mail :</strong> ' . htmlspecialchars($result['mail']) . '</p>';
        echo '<p><strong>Gender :</strong> ' . htmlspecialchars($result['genre']) . '</p>';
        echo '<p><strong>Birthdate :</strong> ' . htmlspecialchars($result['date_naissance']) . '</p>';
        echo '<p><strong>Origin :</strong> ' . htmlspecialchars($result['origine']) . '</p>';
        echo '<h3>Unfortunately, Admin information cannot be changed</h3>';
        echo '</div>';
    } else {
        echo '<p>Aucun admin trouvé avec ces informations.</p>';
    }
}


?>


</body>
</html>
