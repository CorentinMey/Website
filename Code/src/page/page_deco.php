<?php
include_once("../back_php/Patient.php");
//include les autres pages aussi
session_start();

//faire de même pour chaque profil utilisateur
if (isset($_SESSION["patient"])) {
    $patient = $_SESSION["patient"];
    $patient->Deconnect();
}
header("Location: page_accueil.php");
?>