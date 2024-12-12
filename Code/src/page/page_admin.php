<?php
include_once("../back_php/Utilisateur.php");

session_start(); // Démarrer la session

if (!isset($_SESSION["admin"])) {
    header("Location: page_login.php");
    exit;
}
if (isset($_SESSION["admin"])) {
    $user = $_SESSION["admin"];
    // Récupérer les informations de l'utilisateur
    $mail_entre = $user->getEmail(); 
    $mdp_entre = $user->getMdp();
} else {
    echo "Post didnt send User informations";
}

// Ensuite, tu vérifies si les variables existent dans la session et les assignes
//if (isset($_SESSION['mail_entre']) && isset($_SESSION['mdp_entre'])) {
    //$mail_entre = $_SESSION['mail_entre'];
    //$mdp_entre = $_SESSION['mdp_entre'];
//} else {
    // Si les variables ne sont pas dans la session, tu peux les récupérer via GET, par exemple
    //$mail_entre = $_GET['mail'];
    //$mdp_entre = $_GET['mdp'];

    // Et les stocker dans la session pour les utiliser sur les prochaines pages
    //$_SESSION['mail_entre'] = $mail_entre;
    //$_SESSION['mdp_entre'] = $mdp_entre;
//}
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
    <!-- Div contenant l'image de fond -->
    <?php
    include_once("../back_php/Query.php");
    include_once("../back_php/status.php");
    include_once("../back_php/Affichage_admin.php");
    include_once("../back_php/Patient.php");
    

    // Créer une instance de la classe Query pour se connecter à la base de données
    $query = new Query('siteweb');
    
    // Récupérer le nombre de demandes avec is_bannis = 2 
    $newsql = "SELECT COUNT(*) AS count_waiting FROM utilisateur WHERE is_bannis = 2";
    $result_compte = $query->getResults($newsql, []);
    $count = $result_compte['count_waiting'];

    $whoisit = "SELECT nom, prenom, mail, genre, mdp FROM utilisateur WHERE mail = :mail";
    $params = [
        ':mail' => $mail_entre,
    ];
    $whoisadmin = $query->getResultsAll($whoisit, $params);
    $nom_admin = $whoisadmin[0]['nom']; 
    $prenom_admin = $whoisadmin[0]['prenom'];


    // Vérifie si un bouton a été cliqué
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['show_list_user'])) {
            echo '<div class="LALIST">';
            afficherListeUtilisateurs($query);
            echo '</div>';
        } elseif (isset($_POST['show_list_doc'])) {
            echo '<div class="LALIST">';
            afficherListeMedecins($query);
            echo '</div>';
        } elseif (isset($_POST['show_list_company'])) {
            echo '<div class="LALIST">';
            afficherListeEntreprises($query);
            echo '</div>';
        } elseif (isset($_POST['show_list_clinical'])) {
            echo '<div class="LALIST">';
            afficherListeEssaisCliniques($query);
            echo '</div>';
        } elseif (isset($_POST['show_list_confirmation'])) {
            echo '<div class="LALIST">';
            afficherConfirmationsEnAttente($query);
            echo '</div>';
        } elseif(isset($_POST['profile_admin'])){
            echo '<div class="LALIST">';
            afficherInfoAdmin($query,$mail_entre);
            echo '</div>';
        } elseif(isset($_POST['deco'])){
            $_SESSION = array();
            header("Location: /PROJET_SITH_WEB/Website/Code/src/page/page_accueil.php");
            session_destroy();
            exit;
        }
    } else {
    ?>

    <div id="bandeau_top">
        <h1>Admin Page of: <?php echo htmlspecialchars($prenom_admin . ' ' . $nom_admin); ?></h1>


        <!-- Formulaire qui sera soumis quand l'image est cliquée -->
        <form method="POST" action="page_admin.php">
            <div id= div_ensemble>
                <div id=hovered>
                    <button type="submit" name="profile_admin" id="profile_admin_button">
                        <img src="../Ressources/Images/profil.png" alt="Profil" id="profil">
                    </button>
                </div>
                <button class="dropdown_button" name="deco" value="Disconnect">Disconnect</button>
            </div>
        </form>

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
