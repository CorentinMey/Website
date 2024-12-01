<!-- page_admin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_admin.css">
</head>
<style>
</style>
<body>
    <!-- Image de fond -->
    <img src="../Ressources/Images/background_admin2.jpg" alt="fond" id="fond">

    <?php
    include_once("../back_php/Query.php");
    include_once("../back_php/status.php");

    // Créer une instance de la classe Query pour se connecter à la base de données
    $query = new Query('siteweb');
    // Vérifie si un bouton a été cliqué
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Affichage uniquement du contenu ciblé
        echo '<div class="LALIST">';
        if (isset($_POST['show_list_user'])) {
            echo '<div class="content-wrapper">';
            echo "<h2>USER LIST</h2>";


            // Récupérer la liste des utilisateurs depuis la base de données
            $users = $query->getResultsAll("SELECT ID_User, prenom, nom, genre, is_bannis FROM utilisateur", []);  // Remplace 'users' par le nom de ta table

            if (!empty($users)) {
                echo "<ul>";
                foreach ($users as $user) {
                    $boxClass = $user['is_bannis'] == 1 ? 'box_list banned' : 'box_list';

                    echo '<div class="' . htmlspecialchars($boxClass) . '">';
                    echo '    <div class="user-info">';
                    echo '        <p><strong>Nom:</strong> ' . htmlspecialchars($user['nom']) . '</p>';
                    echo '        <p><strong>Prénom:</strong> ' . htmlspecialchars($user['prenom']) . '</p>';
                    echo '        <p><strong>Genre:</strong> ' . htmlspecialchars($user['genre']) . '</p>';
                    /*echo '        <p>LE ID ' . htmlspecialchars($user['ID_User']). '</p>';*/
                    echo '    </div>';
                    echo '    <div class="user-actions">';
                    echo '        <h3>view profile</h3>';
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
        } elseif (isset($_POST['show_list_doc'])) {
            echo '<div class="content-wrapper">';
            echo "<h2>DOCTOR LIST</h2>";
            // Récupérer la liste des docteurs depuis la base de données
            $doctors = $query->getResultsAll(
                "SELECT ID_User, nom, prenom, genre, mail, is_bannis FROM utilisateur INNER JOIN medecin WHERE ID_USER = numero_ordre",
                []
            );

            if (!empty($doctors)) {
                echo "<ul>";
                foreach ($doctors as $doctor) {
                    // Ajoute une classe CSS en fonction de l'état `is_bannis`
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
        } elseif (isset($_POST['show_list_company'])) {
            echo '<div class="content-wrapper">';
            echo "<h2>Company List</h2>";
            $companies = $query->getResultsAll(
                "SELECT ID_User, siret, ville, is_bannis FROM entreprise INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret ",[]
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

        } elseif (isset($_POST['show_list_clinical'])) {
            echo '<div class="content-wrapper">';
            echo "<h2>Clinical Assay List</h2>";
            $assays = $query->getResultsAll(
                "SELECT essai.description, utilisateur.nom AS nom_entreprise FROM essai INNER JOIN utilisateur ON utilisateur.ID_User = essai.ID_entreprise_ref;",[]
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
        } elseif (isset($_POST['show_list_confirmation'])) {
            echo '<div class="content-wrapper">';
            echo "<h2>Pending Confirmations</h2>";
            $pendingConfirmations = $query->getResultsAll(
                "SELECT ID_User, prenom, nom, mail, genre 
                 FROM utilisateur 
                 WHERE is_bannis = 2",
                []
            );
            
            // Vérification et affichage des résultats
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
        }
        echo '</div>';
    } else {
        // Affiche la page normale si aucun bouton n'a été cliqué

    // Récupérer le nombre de demandes avec is_bannis = 2 
    $newsql = "SELECT COUNT(*) AS count_waiting FROM utilisateur WHERE is_bannis = 2";
    $result_compte = $query->getResults($newsql, []);

    // Récupérer le nombre de demandes de confirmation
    $count = $result_compte['count_waiting'];
        
    ?>
        <div id="bandeau_top">
            <h1>Admin page</h1>
            <img src="../Ressources/Images/profil.png" alt="profil" id="profil">
        </div>

        <!-- Formulaire contenant les boutons -->
        <form method="POST" action="page_admin.php" class="button-container" id="content_button">
            <button type="submit" class="btn" name="show_list_user" value="1" id="button1">User List</button>
            <button type="submit" class="btn" name="show_list_doc" value="1" id="button2">Doc List</button>
            <button type="submit" class="btn" name="show_list_company" value="1" id="button3">Company List</button>
            <button type="submit" class="btn" name="show_list_clinical" value="1" id="button4">Clinical Assay List</button>
            <button type="submit" class="confirmation" name="show_list_confirmation" value="1" id="button5">
                <!-- Afficher la pastille seulement si count > 0 -->
                <?php if ($count > 0): ?>
                    <span class="pastille" id="notifCount"><?php echo $count; ?></span>
                <?php else: ?>
                    <span class="pastille" id="notifCount" style="display:none;">0</span>
                <?php endif; ?>
                Required Confirmation for Inscription
            </button>
        </form>
    <?php
    }
    ?>
</body>
</html>