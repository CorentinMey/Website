<?php
include_once("../src/back_php/Patient.php");
include_once("../src/back_php/Securite.php");
include_once("../src/back_php/Affichage_patient.php");
include_once("../src/back_php/Query.php");
session_start();

$bdd = new Query("siteweb");
$query_jacques = "SELECT * FROM utilisateur WHERE prenom = 'Jacques';";
$res = $bdd->getResults($query_jacques, []);
$nom = $res['nom'];
$prenom = $res['prenom'];
$sexe = $res['genre'];
$naissance = $res['date_naissance'];
$mail = $res['mail'];
$antecedents = $res['antecedents'];
$is_bannis = $res['is_bannis'];
$mdp = $res['mdp'];
$origine = $res['origine'];
$is_admin = $res['is_admin'];
$id_patient = $res['ID_User'];


$patient = new Patient($mdp, $mail, $id_patient, $nom, $is_bannis, $is_admin, $prenom, $naissance, $sexe, $antecedents, $origine);


?>

<DOCTYPE html>
<html>
<head>
    <title>Test Patient</title>
    <charset="utf-8">
</head>


<body>
    <h1>Test de la classe Patient</h1>
    <h3>Test des accesseurs et de constructeur</h3>
    <?php
    echo "Nom : " . $patient->getLast_name() . "<br>";
    echo "Prénom : " . $patient->getFirst_name() . "<br>";
    echo "Genre : " . $patient->getGender() . "<br>";
    echo "Date de naissance : " . $patient->getBirthdate() . "<br>";
    echo "Email : " . $patient->getEmail() . "<br>";
    echo "Antécédents : " . $patient->getAntecedent() . "<br>";
    echo "Est banni : " . ($patient->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $patient->getMdp() . "<br>";
    echo "Origines : " . $patient->getOrigins() . "<br>";
    echo "Est admin : " . ($patient->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $patient->getIduser() . "<br>";
    ?>

    <h3>Test des mutateurs</h3>
    <?php
    $patient->setOrigins("France");
    $patient->setIs_banned(true);
    $patient->setIs_admin(true);
    $patient->setMdp("nouveau_mdp");
    $patient->setAntecedent("Nouveaux antécédents");
    $patient->setBirthdate("2000-01-01");
    $patient->setEmail("test@test.com");
    $patient->setFirst_name("Jean");
    $patient->setLast_name("Dupont");
    $patient->setGender("M");
    $patient->setIduser(11111111);
    ?>

    <h3>Test des accesseurs après mutation</h3>
        <?php
            echo "Nom : " . $patient->getLast_name() . "<br>";
            echo "Prénom : " . $patient->getFirst_name() . "<br>";
            echo "Genre : " . $patient->getGender() . "<br>";
            echo "Date de naissance : " . $patient->getBirthdate() . "<br>";
            echo "Email : " . $patient->getEmail() . "<br>";
            echo "Antécédents : " . $patient->getAntecedent() . "<br>";
            echo "Est banni : " . ($patient->getIs_banned() ? 'Oui' : 'Non') . "<br>";
            echo "Mot de passe : " . $patient->getMdp() . "<br>";
            echo "Origines : " . $patient->getOrigins() . "<br>";
            echo "Est admin : " . ($patient->getIs_admin() ? 'Oui' : 'Non') . "<br>";
            echo "ID utilisateur : " . $patient->getIduser() . "<br>";
        ?>
    
    <h3>Test inscription d'un patient</h3>
    <?php
    $patient->Inscription($bdd, [
        "nom" => "DuponD",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@test.com",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2000-01-01"
        ]);
    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];
    ?>

    <h3> Test inscription avec un mineur</h3>
    <?php
    $patient->Inscription($bdd, [
        "nom" => "DuponD",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@test.com2",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2010-01-01"
        ]);

    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];
    ?>


</body>