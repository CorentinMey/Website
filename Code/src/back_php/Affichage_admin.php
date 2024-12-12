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

/**
 * fonction qui permet d'afficher une liste des utilisateurs
 * @param object $query L'objet de connexion à la base de données
 * @param string $message message qui sera affiché en cas d'une action
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour afficher la liste des utilisateurs
function afficherListeUtilisateurs($query, $message = null,  $limit = null) {

    try {
        if (is_null($query)) {
            throw new Exception("The query object is null. Please provide a valid query object.");
        }

        if (!is_null($limit)) {
            if (!is_int($limit) || $limit <= 0) {
                throw new Exception("The limit must be a positive integer.");
            }
        }

        if (!method_exists($query, 'getResultsAll')) {
            throw new Exception("The query object does not support the required method 'getResultsAll'.");
        }

    $sql = "SELECT ID_User, prenom, mail, nom, genre, is_bannis FROM utilisateur WHERE is_bannis!=2 AND is_admin=0";
    $params = [];
    //$users = $query->getResultsAll("SELECT ID_User, prenom, mail, nom, genre, is_bannis FROM utilisateur WHERE is_bannis!=2 AND is_admin=0", []);

    if ($limit !== null) {
        $limit = (int)$limit; // Forcer la conversion en entier
        $sql .= " LIMIT " . $limit; // Concaténation sécurisée
    }

    $users = $query->getResultsAll($sql, []);
    
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
        echo "<p>No users are in the database right now.</p>";
    }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}
/**
 * fonction qui permet d'afficher une liste des médecins
 * @param object $query L'objet de connexion à la base de données
 * @param string $message message qui sera affiché en cas d'une action
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour afficher la liste des médecins:
function afficherListeMedecins($query, $message = null,  $limit = null) {
    try {
        if (is_null($query)) {
            throw new Exception("The query object is null. Please provide a valid query object.");
        }

        if (!is_null($limit)) {
            if (!is_int($limit) || $limit <= 0) {
                throw new Exception("The limit must be a positive integer.");
            }
        }

        if (!method_exists($query, 'getResultsAll')) {
            throw new Exception("The query object does not support the required method 'getResultsAll'.");
        }

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
    $sql = "SELECT ID_User, nom, prenom, genre, mail, is_bannis, hopital, domaine FROM utilisateur INNER JOIN medecin WHERE ID_USER = numero_ordre AND is_bannis!=2";
    $params = [];
    //$users = $query->getResultsAll("SELECT ID_User, prenom, mail, nom, genre, is_bannis FROM utilisateur WHERE is_bannis!=2 AND is_admin=0", []);

    if ($limit !== null) {
        $limit = (int)$limit; // Forcer la conversion en entier
        $sql .= " LIMIT " . $limit; // Concaténation sécurisée
    }

    $doctors = $query->getResultsAll($sql, []);
    // Affichage de la liste des médecins

    
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
        echo "<p>No doctors are in the database right now.</p>";
    }

    // Si un ID est passé dans l'URL, afficher le profil du médecin
    if (isset($_GET['id'])) {
        afficherProfilMedecin($query, $_GET['id']);
    }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

/**
 * fonction qui permet d'afficher une liste des entreprises
 * @param object $query L'objet de connexion à la base de données
 * @param string $message message qui sera affiché en cas d'une action
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour afficher la liste des entreprises:
function afficherListeEntreprises($query, $message=null,  $limit = null) {
    try {
        if (is_null($query)) {
            throw new Exception("The query object is null. Please provide a valid query object.");
        }

        if (!is_null($limit)) {
            if (!is_int($limit) || $limit <= 0) {
                throw new Exception("The limit must be a positive integer.");
            }
        }

        if (!method_exists($query, 'getResultsAll')) {
            throw new Exception("The query object does not support the required method 'getResultsAll'.");
        }
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

    $sql = "SELECT utilisateur.ID_User, entreprise.siret, entreprise.ville, utilisateur.is_bannis 
        FROM entreprise 
        INNER JOIN utilisateur 
        ON utilisateur.ID_User = entreprise.siret WHERE utilisateur.is_bannis != 2";
    $params = [];

    if ($limit !== null) {
        $limit = (int)$limit; // Forcer la conversion en entier
        $sql .= " LIMIT " . $limit; // Concaténation sécurisée
    }

    $companies = $query->getResultsAll($sql, []);

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
        echo "<p>No compagnies are in the database right now.</p>";
    }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}


/**
 * fonction qui permet d'afficher une liste des essais cliniques
 * @param object $query L'objet de connexion à la base de données
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour afficher la liste des essais cliniques:
function afficherListeEssaisCliniques($query,  $limit = null) {
    try {
        if (is_null($query)) {
            throw new Exception("The query object is null. Please provide a valid query object.");
        }

        if (!is_null($limit)) {
            if (!is_int($limit) || $limit <= 0) {
                throw new Exception("The limit must be a positive integer.");
            }
        }

        if (!method_exists($query, 'getResultsAll')) {
            throw new Exception("The query object does not support the required method 'getResultsAll'.");
        }

    echo '<div class="content-wrapper">';
    echo '<div class="titles">';
    echo '<div class="back-btn2-container">';
    echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2">Home</button></a>';
    echo '</div>';
    echo "<h2>CLINICAL ASSAY LIST</h2>";
    echo '</div>';
    
    $sql = "SELECT essai.description, essai.date_debut, essai.date_fin, molecule_test, molecule_ref, a_debute, utilisateur.nom AS nom_entreprise FROM essai INNER JOIN utilisateur ON utilisateur.ID_User = essai.ID_entreprise_ref";
    $params = [];

    if ($limit !== null) {
        $limit = (int)$limit; // Forcer la conversion en entier
        $sql .= " LIMIT " . $limit; // Concaténation sécurisée
    }
    $assays = $query->getResultsAll($sql, []);

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
        echo "<p>No clinical assays are in the database right now.</p>";
    }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

/**
 * fonction qui permet d'afficher une liste des demande de confirmations de account
 * @param object $query L'objet de connexion à la base de données
 * @param string $message message qui sera affiché en cas d'une action
 * @param int $limit combien de display dans la redirection de la liste
 */
// Fonction pour afficher les confirmations en attente:
function afficherConfirmationsEnAttente($query, $message=null,  $limit = null) {
    try {
        if (is_null($query)) {
            throw new Exception("The query object is null. Please provide a valid query object.");
        }

        if (!is_null($limit)) {
            if (!is_int($limit) || $limit <= 0) {
                throw new Exception("The limit must be a positive integer.");
            }
        }

        if (!method_exists($query, 'getResultsAll')) {
            throw new Exception("The query object does not support the required method 'getResultsAll'.");
        }
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

    $sql = "SELECT ID_User, prenom, nom, mail, genre FROM utilisateur WHERE is_bannis = 2";
    $params = [];

    if ($limit !== null) {
        $limit = (int)$limit; // Forcer la conversion en entier
        $sql .= " LIMIT " . $limit; // Concaténation sécurisée
    }

    $pendingConfirmations = $query->getResultsAll($sql, []);
    
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
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

/**
 * fonction qui permet d'afficher une liste des médecins
 * @param object $query L'objet de connexion à la base de données
 * @param string $mail le mail de l'admin qui utilise le site
 */
// Fonction pour afficher le profil de l'admin qui utilise le site:
function afficherInfoAdmin($query, $mail) {
    
    if ($query === null) {
        throw new Exception("The query object is null. Please provide a valid query object.");
    }
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
        echo '   <div class="back-btn2-container" id="home_profil_box">';
        echo '<a href="/PROJET_SITH_WEB/Website/Code/src/page/page_admin.php"><button class="back-btn2" id="bouton_home_in_profile">Home</button></a>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<p>Aucun admin trouvé avec ces informations. Le post des informations ne fonctionne pas ou la session ne se lance pas correctement</p>';
    }
}


?>


</body>
</html>
