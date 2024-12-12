<?php
include_once("../src/back_php/Medecin.php");
include_once("../src/back_php/Securite.php");
include_once("../src/back_php/Affichage_medecin.php");
include_once("../src/back_php/Query.php");
session_start();

$bdd = new Query("siteweb");
$query_jacques = "SELECT * FROM utilisateur JOIN medecin ON utilisateur.ID_User = medecin.numero_ordre WHERE prenom = 'Jeanne';";
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
$id_medecin = $res['ID_User'];
$numero_ordre = $res['numero_ordre'];
$domaine = $res['domaine'];
$hopital = $res['hopital'];


$medecin = new Medecin($mdp, $mail, $id_medecin, $nom, $is_bannis, $is_admin, $prenom, $naissance, $sexe, $antecedents, $origine, $numero_ordre, $domaine, $hopital);


?>

<DOCTYPE html>
<html>
<head>
    <title>Test medecin</title>
    <charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../src/CSS/page_patient.css">
</head>


<body>
    <h1>Test de la classe medecin</h1>
<h3>==============================================================================================================================================================</h3>
    <h3>Test des accesseurs et de constructeur</h3>
    <?php
    echo "Nom : " . $medecin->getLast_name() . "<br>";
    echo "Prénom : " . $medecin->getFirst_name() . "<br>";
    echo "Genre : " . $medecin->getGender() . "<br>";
    echo "Date de naissance : " . $medecin->getBirthdate() . "<br>";
    echo "Email : " . $medecin->getEmail() . "<br>";
    echo "Antécédents : " . $medecin->getAntecedent() . "<br>";
    echo "Est banni : " . ($medecin->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $medecin->getMdp() . "<br>";
    echo "Origines : " . $medecin->getOrigins() . "<br>";
    echo "Est admin : " . ($medecin->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $medecin->getIduser() . "<br>";
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test des mutateurs</h3>
    <?php
    $medecin->setOrigins("France");
    $medecin->setIs_banned(true);
    $medecin->setIs_admin(true);
    $medecin->setMdp("nouveau_mdp");
    $medecin->setAntecedent("Nouveaux antécédents");
    $medecin->setBirthdate("2000-01-01");
    $medecin->setEmail("test@ttest.com");
    $medecin->setFirst_name("Jean");
    $medecin->setLast_name("Dupont");
    $medecin->setGender("M");
    $medecin->setIduser(11111111);
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test des accesseurs après mutation</h3>
        <?php
            echo "Nom : " . $medecin->getLast_name() . "<br>";
            echo "Prénom : " . $medecin->getFirst_name() . "<br>";
            echo "Genre : " . $medecin->getGender() . "<br>";
            echo "Date de naissance : " . $medecin->getBirthdate() . "<br>";
            echo "Email : " . $medecin->getEmail() . "<br>";
            echo "Antécédents : " . $medecin->getAntecedent() . "<br>";
            echo "Est banni : " . ($medecin->getIs_banned() ? 'Oui' : 'Non') . "<br>";
            echo "Mot de passe : " . $medecin->getMdp() . "<br>";
            echo "Origines : " . $medecin->getOrigins() . "<br>";
            echo "Est admin : " . ($medecin->getIs_admin() ? 'Oui' : 'Non') . "<br>";
            echo "ID utilisateur : " . $medecin->getIduser() . "<br>";
        ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test inscription d'un medecin</h3>
    <?php
    $medecin->Inscription($bdd, [
        "nom" => "DuponD",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@test.com",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2000-01-01",
        "numero_ordre" => "1000156",
        "domaine" => "chirurgie",
        "hopital" => "Hautepierre"
        ]);
    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3> Test inscription avec un mineur</h3>
    <?php
    $medecin->Inscription($bdd, [
        "nom" => "DuponD",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@test.com2",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2010-01-01",
        "numero_ordre" => "1000155",
        "domaine" => "chirurgie",
        "hopital" => "Hautepierre"
        ]);

    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];
    ?>
    
<h3>==============================================================================================================================================================</h3>
    <h3> Test si le mail existe déjà dans la BDD</h3>
    <?php
    $medecin->Inscription($bdd, [
        "nom" => "DuponD",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@test.com",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2000-01-01",
        "numero_ordre" => "1000153",
        "domaine" => "chirurgie",
        "hopital" => "Hautepierre"
        ]);

    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3> Test injection de code SQL</h3>

    <?php
    $medecin->Inscription($bdd, [
        "nom" => "SELECT * FROM utilisateur",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@ttest.com",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2000-01-01",
        "numero_ordre" => "1000154",
        "domaine" => "chirurgie",
        "hopital" => "Hautepierre"
        ]);

    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];

    ?>


<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction ChangeInfo_patient</h3>
    <p>Impossible à tester depuis un fichier tier. La fonction a été testée depuis l'interface elle même.</p>

<h3>==============================================================================================================================================================</h3>   
    <h3> Test de la fonction AffichageTableauInfoPerso</h3>
    <?php
        $medecin->AffichageTableau();
    ?>

<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction AfficheEssais</h3>
    <p>Il n'est pas nécessaire de tester la fonction avec $bdd défaillant car la classe Query empêche toutes execution de code si son output n'est pas conforme</p>
    <p>Si l'utilisateur ne participe à aucun essai</p>
    <?php
        $patien3 = new Medecin(mdp : "1234", email : "jeanne.turpin@hospital.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        $bdd = new Query("siteweb");
        $patien3->AfficheEssais($bdd);
    ?>
    <p>Si l'utilisateur participe à des essais</p>
    <?php
    $medecin->AfficheEssais($bdd);
        
    ?>

<h3>==============================================================================================================================================================</h3>
    <h3> Test de la fonctionNombreNotif()</h3>
    <?php
        echo "Nombre de notification de jeanne turpin : ".$medecin->getEmail()." : ". $medecin->NombreNotif($bdd)."<br>";
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction Rejoindre (medecin qui demande à participer à un essai)</h3>
    <?php
        $medecin->Rejoindre($bdd, 5);
    ?>

    <?php

    ?>

    <p>Les autres méthodes de Médecin ont été testées depuis l'interface web. Elles ne sont pas faites pour être testées dans un fichier tier</p>

    <h3>==============================================================================================================================================================</h3>
    <h2> Test de la fonction Connexion</h2>
    <h3>Ici le medecin se connecte grâce à son mail et à son mdp et obtient ses infos au complet</h3>
    <?php
    $medecin2 = new Medecin(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
    $medecin2->Connexion($medecin2->getEmail(), $medecin2->getMdp(), $bdd);
    // Affiche le medecin avec ses nouvelles infos au complet
    echo "Nom : " . $medecin2->getLast_name() . "<br>";
    echo "Prénom : " . $medecin2->getFirst_name() . "<br>";
    echo "Genre : " . $medecin2->getGender() . "<br>";
    echo "Date de naissance : " . $medecin2->getBirthdate() . "<br>";
    echo "Email : " . $medecin2->getEmail() . "<br>";
    echo "Antécédents : " . $medecin2->getAntecedent() . "<br>";
    echo "Est banni : " . ($medecin2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $medecin2->getMdp() . "<br>";
    echo "Origines : " . $medecin2->getOrigins() . "<br>";
    echo "Est admin : " . ($medecin2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $medecin2->getIduser() . "<br>";
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3> Test de la fonction connexion avec un mail qui n'existe pas</h3>
    <?php
    $bdd = new Query("siteweb");
    $medecin2->Connexion("zefzefzef", "1234", $bdd);
    // Affiche le medecin avec ses nouvelles infos au complet
    echo "Nom : " . $medecin2->getLast_name() . "<br>";
    echo "Prénom : " . $medecin2->getFirst_name() . "<br>";
    echo "Genre : " . $medecin2->getGender() . "<br>";
    echo "Date de naissance : " . $medecin2->getBirthdate() . "<br>";
    echo "Email : " . $medecin2->getEmail() . "<br>";
    echo "Antécédents : " . $medecin2->getAntecedent() . "<br>";
    echo "Est banni : " . ($medecin2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $medecin2->getMdp() . "<br>";
    echo "Origines : " . $medecin2->getOrigins() . "<br>";
    echo "Est admin : " . ($medecin2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $medecin2->getIduser() . "<br>";
    ?>



<h3>==============================================================================================================================================================</h3>
    <h3> Test de la GetInfoEssai</h3>
    
</html>


</body>