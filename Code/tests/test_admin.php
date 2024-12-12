
<?php
include_once("../src/back_php/status.php");
include_once("../src/back_php/Affichage_admin.php");
include_once("../src/back_php/Query.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tests Admin</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../src/CSS/page_admin.css">
</head>
<body>
    <h1>Tests d'affichage des differentes listes et profils</h1>
    <h2> fonctionnalités attendues <h2>
    <h3>==============================================================================================================================================================</h3>


    <?php

    $query = new Query('siteweb');

    echo "Cette fonction est censée afficher une liste de 2 utilisateurs au format adpaté(premiers sortis par la requete sql)<br>";
    echo "si il n'y a pas d'utilisateurs dans la bdd actuelle, elle retourne -No users are in the database right now.-";
    afficherListeUtilisateurs($query, null, 2);
    echo '<h3>==============================================================================================================================================================</h3>';
    echo "Cette fonction est censée afficher une liste de 2 médecins au format adpaté(premiers sortis par la requete sql)<br>";
    echo "si il n'y a pas de medecins dans la bdd actuelle, elle retourne -No doctors are in the database right now.-";
    afficherListeMedecins($query,null, 2);
    echo '<h3>==============================================================================================================================================================</h3>';
    echo "Cette fonction est censée afficher une liste de 2 Entreprises au format adpaté(premiers sortis par la requete sql)<br>";
    echo "si il n'y a pas d'entreprises dans la bdd actuelle, elle retourne -No companies are in the database right now.-";
    afficherListeEntreprises($query, null, 2);
    echo '<h3>==============================================================================================================================================================</h3>';
    echo "Cette fonction est censée afficher une liste de 2 Essais cliniques au format adapté (premiers sortis par la requete sql) <br> ";
    echo "si il n'y a pas d'essais cliniques dans la bdd actuelle, elle retourne -No clinical assays are in the database right now.-";
    afficherListeEssaisCliniques($query, 2);
    echo '<h3>==============================================================================================================================================================</h3>';
    echo "Cette fonction est censée afficher une liste de 2 Pending confirmations au format adapté (premiers sortis par la requete sql)<br>";
    echo "si il n'y a pas de confirmations en attente dans la bdd actuelle, elle retourne -No Pending confirmations-";
    afficherConfirmationsEnAttente($query, null, 2);
    
    echo '<h3>==============================================================================================================================================================</h3>';
    // le mail de l'admin utilisé pour le test : 
    $mailtest="giles.bernot@mail.com";
    echo 'Cette fonction est censée afficher le profil de l\'admin qui utilise la session';
    afficherInfoAdmin($query,$mailtest);

    ?>

    <h1>Tests des modifications de la bdd</h1>

    <h3>==============================================================================================================================================================</h3>

    <?php

    $Id_User_test=10;

    $sqlUnbanUser_test = "UPDATE utilisateur SET is_bannis = 0 WHERE ID_User = :Id_User_test";
    $query->UpdateLines($sqlUnbanUser_test,["Id_User_test"=>$Id_User_test]);

    $sqlGetstatus_test = "SELECT is_bannis FROM utilisateur WHERE ID_User = :Id_User_test";
    echo 'Afin de tester nos modifications de bdd, nous allons modifier son status is_bannis au préalable:<br>';
    echo '0 correspond à not ban <br>';
    echo '1 correspond à is ban <br>';
    echo '2 correspond à waiting for confirmation <br>';
    echo '<br>';
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    echo '<h3>==============================================================================================================================================================</h3>';
    // ban un user depuis la liste utilisateur donc contexte utilisateur
    banUser($Id_User_test, $query, 'users_mode',2);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    echo '<h3>==============================================================================================================================================================</h3>';

    unbanUser($Id_User_test, $query, 'users_mode',2);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    echo '<h3>==============================================================================================================================================================</h3>';
    echo '<h3> changement du status is_bannis pour 2 soit waiting for confirmation <h3>';
    $sqlUnbanUser_test = "UPDATE utilisateur SET is_bannis = 2 WHERE ID_User = :Id_User_test";
    $query->UpdateLines($sqlUnbanUser_test,["Id_User_test"=>$Id_User_test]);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    acceptUser($Id_User_test, $query, 2);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    echo '<h3>==============================================================================================================================================================</h3>';
    echo '<h3> changement du status is_bannis pour 2 soit waiting for confirmation <h3>';
    $query->UpdateLines($sqlUnbanUser_test,["Id_User_test"=>$Id_User_test]);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    rejectUser($Id_User_test, $query, 2);
    echo 'current status de is_bannis : ';
    print_r($query->getResults($sqlGetstatus_test,["Id_User_test"=>$Id_User_test]));
    echo '<h3>==============================================================================================================================================================</h3>';
    ?>
<?php

echo '<h3>==============================================================================================================================================================</h3>';
echo '<h3>==============================================================================================================================================================</h3>';
echo '<h3>==============================================================================================================================================================</h3>';
echo '<h1>Mauvaises utilisations</h1>';
// Tests des mauvaises utilisations pour les affichages de listes

// Fonction afficherListeUtilisateurs
echo '<h3>test 1:  Query null.<h3>';
afficherListeUtilisateurs(null, null, 2); // Query null.
echo '<h3>test 2:  Nombre négatif d\'utilisateurs.<h3>';
afficherListeUtilisateurs($query, null, -1); // Nombre négatif d'utilisateurs.
echo '<h3>test 3:  Nombre invalide (string).<h3>';
afficherListeUtilisateurs($query, null, 'abc'); // Nombre invalide (string).
echo '<h3>test 4:  Mauvais type pour $query.<h3>';
afficherListeUtilisateurs(new stdClass(), null, 2); // Mauvais type pour $query.
echo '<br>';

echo 'les arguments $limit et $message peuvent être null, seule la query est indispensable<br>';
echo 'limit sert uniquement à éviter un affichage trop important dans les tests des fonctions (mettre donc un seul arguement fonctionne bien et affiche la liste entière et sans message<br>';
echo 'les autres fonctions d\'affichage fonctionnent de la même manière';

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction afficherListeMedecins
echo '<h3>test 5:  Message random<h3>';
afficherListeMedecins($query, 'wrong_message', 2); // Mauvaise table dans la requête.
echo '<h3>test 6:  limite de 0: demander 0 medecins<h3>';
afficherListeMedecins($query, null, 0); // Demander zéro médecin.
echo '<h3>0 médecins apparaissent. logique. <h3>';

echo '<h3>==============================================================================================================================================================</h3>';

echo '<h3>test 7:  nb d\entreprises null, donc ça affiche toutes les entreprises<h3>';
// Fonction afficherListeEntreprises
//afficherListeEntreprises($query, null, null); // Nombre d'entreprises null. si les quotes sont enlevés cela affichera toute la liste

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction afficherListeEssaisCliniques
echo '<h3>test 8:  query nulle<h3>';
afficherListeEssaisCliniques(null, 2); // Query null.
echo '<h3>test 9: nb dessays cliniques négatif<h3>';
afficherListeEssaisCliniques($query, -5); // Nombre d'essais cliniques négatif.

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction afficherConfirmationsEnAttente
echo '<h3>test 10:  demandes de confirmations négatif<h3>';
afficherConfirmationsEnAttente($query, null, -10); // Nombre négatif.
echo '<h3>test 11:  limite de 0: demander 0 confirmations<h3>';
afficherConfirmationsEnAttente($query, null, 0); // Pas d'éléments à afficher.
echo '<h3>0 demandes apparaissent. logique. <h3>';

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction afficherInfoAdmin
echo '<h3>test 12: pas email fourni <h3>';
afficherInfoAdmin($query, null); // Pas d'e-mail fourni.
echo '<h3>test 13: mauvais email fourni <h3>';
afficherInfoAdmin($query, 'not_an_email'); // Format d'e-mail incorrect.

echo '<h3>==============================================================================================================================================================</h3>';

// Tests des mauvaises utilisations pour les modifications de la base de données
echo '<h3>Mauvaise usage pour modification</h3>';
$Id_User_test = 10;

// Fonction banUser
echo '<h3>test 14: pas d\'user fourni <h3>';
banUser(null, $query, 'users_mode', 2); // ID utilisateur null.
echo '<h3>test 15: ID utilisateur négatif. <h3>';
banUser(-1, $query, 'users_mode', 2); // ID utilisateur négatif.
echo '<h3>test 16: ID utilisateur invalide. <h3>';
banUser('string_instead_of_int', $query, 'users_mode', 2); // ID utilisateur invalide.
echo '<h3>test 17: mode invalide <h3>';
banUser($Id_User_test, $query, 'wrong_mode', 2); // Mode non valide.
echo '<h3>test 18: mode vide carrément <h3>';
banUser($Id_User_test, $query, '', 2); // Mode vide.

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction unbanUser
echo '<h3>test 19: pas d\'user fourni <h3>';
unbanUser(null, $query, 'users_mode', 2); // ID utilisateur null.
echo '<h3>test 20: ID utilisateur négatif. <h3>';
unbanUser(-1, $query, 'users_mode', 2); // ID utilisateur négatif.
echo '<h3>test 21: ID utilisateur invalide. <h3>';
unbanUser('string_instead_of_int', $query, 'users_mode', 2); // ID utilisateur invalide.
echo '<h3>test 22: mode invalide <h3>';
unbanUser($Id_User_test, $query, 'wrong_mode', 2); // Mode non valide.
echo '<h3>test 23: mode vide carrément <h3>';
unbanUser($Id_User_test, $query, '', 2); // Mode vide.

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction acceptUser similaire à unban et ban dans les inputs
echo '<h3>ici les accepts users et reject sont similaire à ban et uban on va donc se concentrer sur limite qu\'on a pas testé avant<br> <h3>';
echo '<h3>test 24: limite négative <h3>';
acceptUser($Id_User_test, $query, -1); // limite invalide négative.
echo '<h3>test 25: string pour limite <h3>';
acceptUser($Id_User_test, $query, 'invalid_status'); // limite en texte.

echo '<h3>==============================================================================================================================================================</h3>';

// Fonction rejectUser
echo '<h3>test 26: limite négative <h3>';
rejectUser($Id_User_test, $query, -1); // limite invalide négative
echo '<h3>test 27: string pour limite <h3>';
rejectUser($Id_User_test, $query, 'invalid_status'); // limite en texte.
echo '<h3>==============================================================================================================================================================</h3>';

echo '<h2><br>voila, tous les tests ont réussi, impressionant. <br> si vous avez d\'autres suggestions n\'hesitez pas à écrire à simon2suggestion@gmail.com</h2>';
?>


</body>
</html>