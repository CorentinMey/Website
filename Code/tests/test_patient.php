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
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../src/CSS/page_patient.css">
</head>


<body>
    <h1>Test de la classe Patient</h1>
<h3>==============================================================================================================================================================</h3>
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
<h3>==============================================================================================================================================================</h3>
    <h3>Test des mutateurs</h3>
    <?php
    $patient->setOrigins("France");
    $patient->setIs_banned(true);
    $patient->setIs_admin(true);
    $patient->setMdp("nouveau_mdp");
    $patient->setAntecedent("Nouveaux antécédents");
    $patient->setBirthdate("2000-01-01");
    $patient->setEmail("test@ttest.com");
    $patient->setFirst_name("Jean");
    $patient->setLast_name("Dupont");
    $patient->setGender("M");
    $patient->setIduser(11111111);
    ?>
<h3>==============================================================================================================================================================</h3>
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
<h3>==============================================================================================================================================================</h3>
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
<h3>==============================================================================================================================================================</h3>
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
    
<h3>==============================================================================================================================================================</h3>
    <h3> Test si le mail existe déjà dans la BDD</h3>
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
<h3>==============================================================================================================================================================</h3>
    <h3> Test injection de code SQL</h3>

    <?php
    $patient->Inscription($bdd, [
        "nom" => "SELECT * FROM utilisateur",
        "prenom" => "Jean",
        "genre" => "M",
        "origine" => "France",
        "antecedents" => "Aucun",
        "mail" => "test@ttest.com",
        "mdp" => "nouveau_mdp",
        "date_naissance" => "2000-01-01"
        ]);

    if ($_SESSION["result"] == 1)
        echo "Inscription réussie ".$_SESSION["result"];
    else
        echo "Inscription échouée ".$_SESSION["result"];

    ?>
<h3>==============================================================================================================================================================</h3>
    <h2> Test de la fonction getLastIdPatient</h2>
    <?php
    echo "ID du dernier patient inscrit : " . $patient->getLastIdPatient();
    ?>



<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction ChangeInfo / updatePatientInfo</h3>
    <p>Impossible à tester depuis un fichier tier. La fonction a été testée depuis l'interface elle même.</p>

<h3>==============================================================================================================================================================</h3>   
    <h3> Test de la fonction AffichageTableauInfoPerso</h3>
    <?php
        $patient->AffichageTableauInfoPerso();
    ?>

<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction AfficheEssais</h3>
    <p>Il n'est pas nécessaire de tester la fonction avec $bdd défaillant car la classe Query empêche toutes execution de code si son output n'est pas conforme</p>
    <p>Si l'utilisateur ne participe à aucun essai</p>
    <?php
        $patient->AfficheEssais($bdd);
    ?>
    <p>Si l'utilisateur participe à des essais</p>
    <?php
        $patien3 = new Patient(mdp : "1234", email : "jacques.perrin@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        $patien3->AfficheEssais($bdd);
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3> Test de la fonctionNombreNotif()</h3>
    <?php
        echo "Nombre de notifications : ".$patient->getEmail()." : ". $patient->NombreNotif($bdd)."<br>";
        echo "Nombre de notifications : ".$patien3->getEmail()." : ". $patien3->NombreNotif($bdd)."<br>";
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction AfficheNotif</h3>
    <?php
        $patient->AfficheNotif($bdd);
        $patien3->AfficheNotif($bdd);
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction Rejoindre (patient qui demande à participer à un essai)</h3>
    <?php
        $patient->Rejoindre($bdd, 4);
    ?>

    <?php
    // supression de la BDD pour ne pas surcharger
    $query = "DELETE FROM resultat WHERE ID_Essai = 4 AND ID_patient = 11111111;";
    $bdd->deleteLines($query, []);

    ?>

    <p>Les autres méthodes de Patient ont été testées depuis l'interface web. Elles ne sont pas faites pour être testées dans un fichier tier</p>

<h3>==============================================================================================================================================================</h3>
    <h3> Test de la fonction AttributeTreatment</h3>
    <p> test de la fonction qui attribut un traitement à un patient</p>
    <?php
    print_r($patien3->getAttributeTreatment("Bernosaurus", "Cometosaure", "500VIGEANT/mL", "1000GRATALOUP/mL", "GUZZI"));
    ?>

    <h3>==============================================================================================================================================================</h3>
    <h2> Test de la fonction Connexion</h2>
    <h3>Ici le patient se connect grâce à son mail et à son mdp et obtient ses infos au complet</h3>
    <?php
    $patient2 = new Patient(mdp : "1234", email : "bigboss@gmail.com");
    $patient2->Connexion($patient2->getEmail(), $patient2->getMdp(), $bdd);
    // Affiche le patient avec ses nouvelles infos au complet
    echo "Nom : " . $patient2->getLast_name() . "<br>";
    echo "Prénom : " . $patient2->getFirst_name() . "<br>";
    echo "Genre : " . $patient2->getGender() . "<br>";
    echo "Date de naissance : " . $patient2->getBirthdate() . "<br>";
    echo "Email : " . $patient2->getEmail() . "<br>";
    echo "Antécédents : " . $patient2->getAntecedent() . "<br>";
    echo "Est banni : " . ($patient2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $patient2->getMdp() . "<br>";
    echo "Origines : " . $patient2->getOrigins() . "<br>";
    echo "Est admin : " . ($patient2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $patient2->getIduser() . "<br>";
    ?>
<h3>==============================================================================================================================================================</h3>
    <h3> Test de la fonction connexion avec un mail qui n'existe pas</h3>
    <p>Le fichier va arreter de s'éxécuter par sécurité. Il faut commenter le code ligne 249-266 pour voir la suite. Cela sera aussi le cas pour les test suivants</p>
    <?php
    $patient2->Connexion("zefzefzef", "1234", $bdd);
    // Affiche le patient avec ses nouvelles infos au complet
    echo "Nom : " . $patient2->getLast_name() . "<br>";
    echo "Prénom : " . $patient2->getFirst_name() . "<br>";
    echo "Genre : " . $patient2->getGender() . "<br>";
    echo "Date de naissance : " . $patient2->getBirthdate() . "<br>";
    echo "Email : " . $patient2->getEmail() . "<br>";
    echo "Antécédents : " . $patient2->getAntecedent() . "<br>";
    echo "Est banni : " . ($patient2->getIs_banned() ? 'Oui' : 'Non') . "<br>";
    echo "Mot de passe : " . $patient2->getMdp() . "<br>";
    echo "Origines : " . $patient2->getOrigins() . "<br>";
    echo "Est admin : " . ($patient2->getIs_admin() ? 'Oui' : 'Non') . "<br>";
    echo "ID utilisateur : " . $patient2->getIduser() . "<br>";
    ?>



<h3>==============================================================================================================================================================</h3>
    <h3> Test de la GetInfoEssai</h3>
    <p>Si l'argument est bon :</p>
        <?php
            $res = $patien3->getGetInfoEssai($bdd);
            print_r($res);
        ?>
    <p>Si les arguments sont faux :</p>
        <?php
            $res = $patien3->getGetInfoEssai(1);
            print_r($res);
        ?>
</html>


</body>