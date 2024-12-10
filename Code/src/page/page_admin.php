<?php
// Vérification si l'admin est connecté
require_once('../back_php/Admin.php');
session_start();
var_dump($_SESSION);
exit; 

if (!isset($_SESSION["admin"])) {
    header("Location: page_login.php");
    exit;
}

// Récupérer l'objet Admin depuis la session
$admin = $_SESSION["admin"];

// Exemple d'affichage des informations de l'admin
echo "<p>Welcome, " . $admin->getFirstName() . " " . $admin->getLastName() . "</p>";
echo "<p>Email: " . $admin->getEmail() . "</p>";
?>
<?php
/*if(!isset($_SESSION["admin"])){
    header("Location: page_login.php");
}*/
?>
<!-- page_admin.php -->
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
    <!-- Image de fond -->
    <img src="../Ressources/Images/background_admin2.jpg" alt="fond" id="fond">

    <?php
    include_once("../back_php/Query.php");
    include_once("../back_php/status.php");
    include_once("../back_php/Affichage_admin.php");
    include_once("../back_php/Patient.php");

    // Créer une instance de la classe Query pour se connecter à la base de données
    $patient = $_SESSION["patient"];
    $query = new Query('siteweb');
    
    // Récupérer le nombre de demandes avec is_bannis = 2 
    $newsql = "SELECT COUNT(*) AS count_waiting FROM utilisateur WHERE is_bannis = 2";
    $result_compte = $query->getResults($newsql, []);
    $count = $result_compte['count_waiting'];

    // Vérifie si un bouton a été cliqué
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<div class="LALIST">';

        if (isset($_POST['show_list_user'])) {
            afficherListeUtilisateurs($query);
        } elseif (isset($_POST['show_list_doc'])) {
            afficherListeMedecins($query);
        } elseif (isset($_POST['show_list_company'])) {
            afficherListeEntreprises($query);
        } elseif (isset($_POST['show_list_clinical'])) {
            afficherListeEssaisCliniques($query);
        } elseif (isset($_POST['show_list_confirmation'])) {
            afficherConfirmationsEnAttente($query);
        }

        echo '</div>';
    } else {
    ?>

    <div id="bandeau_top">
        <h1>Admin Page</h1>
        <img src="../Ressources/Images/profil.png" alt="profil" id="profil" onclick="AffichageTableauInfoPerso()">
    </div>

    <!-- Formulaire contenant les boutons -->
    <form method="POST" action="page_admin.php" id="content_button">
        <button type="submit" class="btn" name="show_list_user" value="1" id="button1">User List</button>
        <button type="submit" class="btn" name="show_list_doc" value="1" id="button2">Doc List</button>
        <button type="submit" class="btn" name="show_list_company" value="1" id="button3">Company List</button>
        <button type="submit" class="btn" name="show_list_clinical" value="1" id="button4">Clinical Assay List</button>
        <button type="submit" class="btn" name="show_list_confirmation" value="1" id="confirmation">
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
