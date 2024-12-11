<!DOCTYPE html>

<html>
<head>
    <title>Test Affichage patient</title>
    <charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../src/CSS/page_patient.css">
</head>

<body>

<h3>Tests unitaires des fonctions Affichage_patient</h3>
    <h4>Test de Affichage_entete_tableau_essai</h4>
    <?php
    include_once("../src/back_php/Affichage_patient.php");
    include_once("../src/back_php/Patient.php");

    Affichage_entete_tableau_essai();
    ?>
<h3>==============================================================================================================================================================</h3>
<h4>Test de Affichage_content_essai </h4>
    <?php
        $entreprise = [
            "nom" => "PharmaCorp",
            "a_debute" => 2 // L'essai est terminé et en attente des résultats
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>
<!-- test avec un autre statut d'essai clinique -->
    <?php
        $entreprise = [
            "nom" => "PharmaCorp2",
            "a_debute" => 1 // L'essai est terminé et en attente des résultats
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>

<?php // test avec un argument invalide affiche la pop up avant le
        $entreprise = [
            "nom" => "PharmaCorp2",
        ];

        $essai = [
            "phase_res" => "3",
            "description" => "Essai clinique sur le nouveau médicament.",
            "effet_secondaire" => "" // Le patient n'a pas encore donné ses résultats
        ];

        $medecins = [
            ["nom" => "Dr. Dupont"],
            ["nom" => "Dr. Martin"]
        ];

        $id_essai = 1;

        Affichage_content_essai($entreprise, $essai, $medecins, $id_essai);
    ?>
    </tbody> 
    </table>
    </div>
    
    <h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction UpdateNotification</h3>
    <p>Test avec un patient avec au moins 1 notif</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        UpdateNotification($bdd, $patien3, 1);
    ?>

    <p>Test avec un patient sans notif et sans essais à son actif</p>
    <?php
        $patient = new Patient(mdp : "1234", email : "david.lefevre@mail.com");
        $patient->Connexion($patient->getEmail(), $patient->getMdp(), $bdd);
        UpdateNotification($bdd, $patient, 1);
    ?>
    <p>Test avec des arguments incorrects</p>
    <?php
        UpdateNotification("bdd", $patient, 1);
    ?> 

<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction handleJoinTrial</h3>
    <p>Test avec un patient correct</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleJoinTrial($bdd, $patien3, 1);
    ?>

  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleJoinTrial("bdd", $patient, 1);
    ?> 




<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction handleCancelJoin</h3>
    <p>Test avec un patient correct. le résultat attendu est de recharger la page des essais disponible</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleCancelJoin($bdd, $patien3);
    ?>

  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleCancelJoin("bdd", $patient);
    ?> 

<h3>==============================================================================================================================================================</h3>

<h3>Test de la fonction handleSubmitSideEffects</h3>
    <p>Test avec un patient correct, affiche error pour l'effet secondaire car il n'a pas été défini au préalable ici</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleSubmitSideEffects($bdd, $patien3, 1, 1);
    ?>

  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleSubmitSideEffects("bdd", $patient,1,1);
    ?> 



<h3>==============================================================================================================================================================</h3>

<h3>Test de la fonction handleUnsubscribe</h3>
    <p>Test avec un patient correct,</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleUnsubscribe($bdd, $patien3, 1, 1);
    ?>

  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleUnsubscribe("bdd", $patient,1,1);
    ?> 


<h3>==============================================================================================================================================================</h3>

<h3>Test de la fonction handleConfirmUnsubscribe</h3>
    <p>Test avec un patient correct,</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleConfirmUnsubscribe($bdd, $patien3, 1, 1);
    ?>

    <p>Test avec un id essais inexistants</p>
        <?php
            handleConfirmUnsubscribe($bdd, $patien3,654646,1);
        ?> 


  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleConfirmUnsubscribe("bdd", $patien3,1,1);
    ?> 



<h3>==============================================================================================================================================================</h3>
    <h3>Test de la fonction handleConfirmJoin</h3>
    <p>Test avec un patient correct</p>
    <?php
        $bdd = new Query("siteweb");
        $patien3 = new Patient(mdp : "1234", email : "brigitte-suzanne.santos@mail.com");
        $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
        handleConfirmJoin($bdd, $patien3, 1);
    ?>

    <p>Test si le patient est déjà inscrit à un essai de ce type</p>
    <?php
        $patient = new Patient(mdp : "1234", email : "bob.martin@mail.com");
        $patient->Connexion($patient->getEmail(), $patient->getMdp(), $bdd);
        handleConfirmJoin($bdd, $patient, 1);
    ?>

  
    <p>Test avec des arguments incorrects</p>
    <?php
        handleConfirmJoin("bdd", $patient, 1);
    ?> 
</body>