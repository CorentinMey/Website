<?php
include_once("../src/back_php/Query.php");
include_once("../src/back_php/Utilisateur.php");
include_once("../src/back_php/Entreprise.php");
include_once("../src/back_php/Affichage_entreprise.php");
include_once("../src/back_php/Securite.php");

$bdd = new Query("siteweb");

// Récupération des informations utilisateur
$query_utilisateur = "SELECT * FROM utilisateur WHERE mail = :mail";
$res = $bdd->getResults($query_utilisateur, [':mail' => 'entreprise_60@mail.com']);
$nom = $res['nom'];
$mail = $res['mail'];
$is_bannis = $res['is_bannis'];
$mdp = $res['mdp'];
$is_admin = $res['is_admin'];
$id_user = $res['ID_User'];


// Récupération des informations entreprise
$query_entreprise = "SELECT * FROM entreprise WHERE siret = :siret";
$res2 = $bdd->getResults($query_entreprise, [':siret' => $id_user]);
$ville = $res2["ville"];
$siret = $res2["siret"]; 

$entreprise = new Entreprise(
    $mdp,         // Mot de passe
    $mail,        // Email
    $id_user,     // ID utilisateur
    $nom,         // Nom
    $is_bannis,   // Est banni
    $is_admin,    // Est admin
    null,         // Prénom (non utilisé)
    null,         // Date de naissance (non utilisé)
    null,         // Genre (non utilisé)
    null,         // Antécédents (non utilisé)
    null,         // Origines (non utilisé)
    $ville,       // Ville
    $siret        // SIRET
);


?>

<DOCTYPE html>
<html>
<head>
    <title>Test Entreprise</title>
    <charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../src/CSS/page_entreprise.css">
</head>

<body>
    <h1>Test de la classe Entreprise</h1>
<h3>==============================================================================================================================================================</h3>
    <h3>Test des accesseurs et de constructeur</h3>
    <?php
    echo "Nom de l'entreprise : " . $entreprise->getLast_name() . "<br>";
    echo "Email : " . $entreprise->getEmail() . "<br>";
    echo "Est banni : " . ($entreprise->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $entreprise->getMdp() . "<br>";
    echo "Est admin : " . ($entreprise->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "Ville : " . $entreprise->getVille() . "<br>";
    echo "ID utilisateur : " . $entreprise->getIdUser() . "<br>";
    echo "Siret: " . $entreprise->getSiret() . "<br>";
    ?>

    <h3>==============================================================================================================================================================</h3>
    <h3>Test des mutateurs</h3>

<?php

// Modification des attributs via les mutateurs
$entreprise->setLast_name("Nouvelle Entreprise");
$entreprise->setEmail("nouvelle_entreprise@mail.com");
$entreprise->setIs_banned(true);
$entreprise->setMdp("nouveau_mdp");
$entreprise->setIs_admin(true);
$entreprise->setVille("Nouvelle Ville");
$entreprise->setSiret("12345678901234");

?>

<h3>==============================================================================================================================================================</h3>
    <h3>Test des accesseurs après mutation</h3>
        <?php
// Vérification des nouvelles valeurs
echo "Nom de l'entreprise (modifié) : " . $entreprise->getLast_name() . "<br>";
echo "Email (modifié) : " . $entreprise->getEmail() . "<br>";
echo "Est banni (modifié) : " . ($entreprise->getIs_banned() ? 'Oui' : 'Non') . "<br>";
echo "Mot de passe (modifié) : " . $entreprise->getMdp() . "<br>";
echo "Est admin (modifié) : " . ($entreprise->getIs_admin() ? 'Oui' : 'Non') . "<br>";
echo "Ville (modifiée) : " . $entreprise->getVille() . "<br>";
echo "Siret (modifié) : " . $entreprise->getSiret() . "<br>";
  ?>

<h3>==============================================================================================================================================================</h3>

<h3>Test inscription d'une entreprise</h3>
<?php

// Appel de la méthode Inscription avec des informations fictives
$entreprise->Inscription($bdd, [
    "ID_User" => 11111111111,
    "nom" => "Entreprise Test",
    "prenom" => null, // Pas pertinent pour une entreprise
    "genre" => null, // Pas pertinent pour une entreprise
    "origine" => null, // Pas pertinent pour une entreprise
    "antecedents" => null, // Pas pertinent pour une entreprise
    "mail" => "entreprise_test@test.com",
    "mdp" => "motdepasse123",
    "date_naissance" => null, // Pas pertinent pour une entreprise
]);

// Vérification de l'inscription 
if ($_SESSION["result"] == 1) {
    echo "Inscription réussie !<br>";
} else {
    echo "Inscription échouée. Erreur : " . $_SESSION["result"]. "<br>";
}

echo "Faire une seule fois, au bout de 2 fois, adresse mail déjà utilisée"
?>
<h3>==============================================================================================================================================================</h3>
<h3> Test injection de code SQL</h3>

<?php
// Appel de la méthode Inscription avec des informations fictives

$entreprise->Inscription($bdd, [
    "ID_User" => 1111112222222,
    "nom" => "SELECT * FROM entreprise",
    "prenom" => null, // Pas pertinent pour une entreprise
    "genre" => null, // Pas pertinent pour une entreprise
    "origine" => null, // Pas pertinent pour une entreprise
    "antecedents" => null, // Pas pertinent pour une entreprise
    "mail" => "requeteSQLtest@test.com",
    "mdp" => "motdepasse123",
    "date_naissance" => null, // Pas pertinent pour une entreprise
]);

// Vérification de l'inscription 
if ($_SESSION["result"] == 1) {
    echo "Inscription réussie !<br>";
} else {
    echo "Inscription échouée. Erreur : " . $_SESSION["result"]. "<br>";
}
?>

<h3>==============================================================================================================================================================</h3>
<h3> Test de la fonction connexion</h3>

<?php
 $entreprise2= new Entreprise(mdp : "1234", email : "entreprise_60@mail.com");
 $entreprise2->Connexion($entreprise2->getEmail(), $entreprise2->getMdp(), $bdd);

echo "Nom de l'entreprise : " . $entreprise2->getLast_name() . "<br>";
echo "Email : " . $entreprise2->getEmail() . "<br>";
echo "Est banni : " . ($entreprise2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
echo "Mot de passe : " . $entreprise2->getMdp() . "<br>";
echo "Est admin : " . ($entreprise2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
echo "Ville  : " . $entreprise2->getVille() . "<br>";
echo "Siret : " . $entreprise2->getSiret() . "<br>";

?>

<h3> Test de la fonction connexion avec un mail qui existe pas</h3>
<p>Le fichier va arreter de s'éxécuter par sécurité. Il faut commenter le code ligne 172-188 pour voir la suite.<p>

<?php
//  $bdd = new Query("siteweb");
//  $entreprise2->Connexion("kjfbdghjsdfgkjsgkjdfjghkjfngkjhnfdnkjg", "kjgnkjndf", $bdd);

// echo "Nom de l'entreprise : " . $entreprise2->getLast_name() . "<br>";
// echo "Email : " . $entreprise2->getEmail() . "<br>";
// echo "Est banni : " . ($entreprise2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
// echo "Mot de passe : " . $entreprise2->getMdp() . "<br>";
// echo "Est admin : " . ($entreprise2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
// echo "Ville  : " . $entreprise2->getVille() . "<br>";
// echo "Siret : " . $entreprise2->getSiret() . "<br>";

?>

<h3>Test de la fonction DemandMedecin</h3>

<?php
// $bdd = new Query("siteweb");
// // Simuler les données pour l'essai et le médecin
// $id_medecin = 100001;
// $id_essai = 2;

// // Appeler la méthode DemandMedecin

// $entreprise->DemandMedecin($id_medecin, $bdd, $id_essai);

// // Vous pouvez vérifier si l'insertion a bien été réalisée dans la base de données en effectuant une requête de test, par exemple :
// $query_check = "SELECT * FROM ESSAI_MEDECIN WHERE ID_medecin = :id_medecin AND ID_essai = :id_essai";
// $res_check = $bdd->getResults($query_check, [':id_medecin' => $id_medecin, ':id_essai' => $id_essai]);

// if ($res_check) {
//     echo "<p>Insertion réussie : un lien a été ajouté entre le médecin et l'essai.</p>";
// } else {
//     echo "<p>Insertion échouée : aucun lien trouvé entre le médecin et l'essai dans la base de données.</p>";
// }
// ?>

<h3>Test de la fonction NewPhase</h3>
<?php
echo "Pour l'instant testée a travers l'interface web car probleme avec l'autoincrémentation mais ça marche"
// // Simuler des données pour un nouvel essai clinique
// $dataEssai = [
//     'ID_phase' => 2, // Phase 1 (par défaut)
//     'date_debut' => '2024-01-01',
//     'date_fin' => '2024-12-31',
//     'description' => 'Essai clinique sur la molécule X.',
//     'molecule_test' => 'Molécule X',
//     'dosage_test' => '50mg',
//     'molecule_ref' => 'dfgiushdrhugdjf',
//     'dosage_ref' => '50mg',
//     'placebo_nom' => 'Placebo',
// ];

// $bdd = new Query("siteweb");

// // Appeler la méthode NewPhase avec les données simulées
// $entreprise->NewPhase($bdd, $dataEssai);

// // Vérifier si l'insertion a bien eu lieu dans la base de données pour l'ESSAI
// $query_check_essai = "SELECT * FROM ESSAI WHERE description = :description AND molecule_test = :molecule_test";
// $res_check_essai = $bdd->getResults($query_check_essai, [
//     ':description' => $dataEssai['description'],
//     ':molecule_test' => $dataEssai['molecule_test'],
// ]);

// if ($res_check_essai) {
//     echo "<p>Insertion réussie de l'essai clinique dans la table ESSAI.</p>";

//     // Vérifier l'insertion dans la table PHASE
//     $idEssai = $bdd->getLastInsertId();
//     $query_check_phase = "SELECT * FROM PHASE WHERE ID_essai = :id_essai";
//     $res_check_phase = $bdd->getResults($query_check_phase, [':id_essai' => $idEssai]);

//     if ($res_check_phase) {
//         echo "<p>Insertion réussie de la phase dans la table PHASE.</p>";
//     } else {
//         echo "<p>Insertion échouée dans la table PHASE.</p>";
//     }
// } else {
//     echo "<p>Insertion échouée dans la table ESSAI.</p>";
// }
?>

<h3>Test de la fonction startPhase</h3>

<?php
// Créer un objet Query pour interagir avec la base de données
$bdd = new Query("siteweb"); 

$idEssai = 1;  // Changez cette valeur avec un ID d'essai valide de votre base de données
$id_phase = 1;
// Appeler la fonction startPhase pour démarrer l'essai
$entreprise->startPhase($bdd, $idEssai,$id_phase);

// Vérifier si la colonne a_debute a bien été mise à jour dans la base de données
$query = "SELECT a_debute FROM ESSAI WHERE ID_essai = :idEssai AND ID_phase = :idPhase"; 
$res = $bdd->getResults($query, [':idEssai' => $idEssai, ':idPhase'=> $id_phase]);

?>


<h3>Test de l'acceptation d'un médecin pour un essai clinique</h3>
<?php
// Création d'un objet pour tester la méthode acceptMedecin
$idMedecin = 100004; // ID d'un médecin valide (existe dans la base de données)
$idEssai = 4;   // ID d'un essai clinique valide (existe dans la base de données)

// Appel de la méthode acceptMedecin
$entreprise->acceptMedecin($bdd, $idMedecin, $idEssai);

// Vérification de la mise à jour dans la base de données
$queryCheck = "SELECT is_accepte FROM essai_medecin WHERE ID_medecin = :idMedecin AND ID_essai = :idEssai";
$result = $bdd->getResults($queryCheck, [':idMedecin' => $idMedecin, ':idEssai' => $idEssai]);

if ($result && $result['is_accepte'] == 1) {
    echo "<p style='color: green;'>Le médecin a bien été accepté pour l'essai clinique.</p>";
} else {
    echo "<p style='color: red;'>Erreur : le médecin n'a pas été accepté pour l'essai clinique.</p>";
}
?>
