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

    // Créer une instance de la classe Query pour se connecter à la base de données
    $query = new Query('siteweb');
    // Vérifie si un bouton a été cliqué
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Affichage uniquement du contenu ciblé
        echo '<div class="LALIST">';
        if (isset($_POST['show_list_user'])) {
                echo "<h2>User List</h2>";

            // Récupérer la liste des utilisateurs depuis la base de données
            $users = $query->getResultsAll("SELECT prenom, nom, genre FROM utilisateur", []);  // Remplace 'users' par le nom de ta table

            if (!empty($users)) {
                echo "<ul>";
                foreach ($users as $user) {
                    echo "<li><strong>prenom:</strong> " . htmlspecialchars($user['prenom']) . 
                        " - <strong>Name:</strong> " . htmlspecialchars($user['nom']) . 
                        " - <strong>gender:</strong> " . htmlspecialchars($user['genre']) . "</li>";
                }
                echo "</ul>";
        } else {
            echo "<p>No users found.</p>";}
        } elseif (isset($_POST['show_list_doc'])) {
            echo "<h2>Doctor List</h2>";
            // Récupérer la liste des utilisateurs depuis la base de données
            $doctors = $query->getResultsAll(
                "SELECT nom, prenom, genre FROM utilisateur INNER JOIN medecin WHERE ID_USER = numero_ordre",
                []
            );

            if (!empty($doctors)) {
                echo "<ul>";
                foreach ($doctors as $doctor) {
                    echo "<li><strong>prenom:</strong> " . htmlspecialchars($doctor['prenom']) . 
                        " - <strong>Name:</strong> " . htmlspecialchars($doctor['nom']) . 
                        " - <strong>gender:</strong> " . htmlspecialchars($doctor['genre']) . "</li>";
                }
                echo "</ul>";
        } else {
            echo "<p>No users found.</p>";}
        } elseif (isset($_POST['show_list_company'])) {
            echo "<h2>Company List</h2>";
        } elseif (isset($_POST['show_list_clinical'])) {
            echo "<h2>Clinical Assay List</h2>";
        } elseif (isset($_POST['show_list_confirmation'])) {
            echo "<h2>Pending Confirmations</h2>";
        }
        echo '</div>';
    } else {
        // Affiche la page normale si aucun bouton n'a été cliqué
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
                Required Confirmation for Inscription
                <span class="pastille" id="notifCount">0</span>
            </button>
        </form>
    <?php
    }
    ?>
</body>
</html>