<DOCTYPE html>
<html>
<head>
    <title>Test Patient</title>
    <charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">
</head>

<body>
<h4>Pour ces tests il a été decidé de comparer les sorties html des fonctions. Leurs rendus a été défini dans les fichiers web donc il serait compliqué de le faire ici<br>De Plus, ces fonctions prennent des arguments génériques qui ne peuvent pas causer de dommanges s'ils sont mal définis</h4>

<?php
include_once("../src/back_php/Securite.php");
include_once("../src/back_php/Query.php");
/**
 * Fonction de test pour AfficherErreur
 */
function testAfficherErreur() {
    ob_start();
    AfficherErreur("Ceci est une erreur.", "error1");
    $output = ob_get_clean();
    
    $expected = '<div class="error-message" id=error1>Ceci est une erreur.</div>';
    echo "Test AfficherErreur:<br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($output) . "<br>";
    echo ($output === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

/**
 * Fonction de test pour AfficherInfo
 */
function testAfficherInfo() {
    ob_start();
    AfficherInfo("Information importante.", "info1", "action1", true);
    $output = ob_get_clean();
    
    $expected = '<div class="info-message" id="notif">Information importante.<form action=\'\' method=\'post\'><input type="hidden" name="id_essai" value="info1"><input type="hidden" name="Action" value="action1"><button type="submit" class="button" id="close_notif">X</button></form></div>';
    echo "Test AfficherInfo:<br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($output) . "<br>";
    echo ($output === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

/**
 * Fonction de test pour AfficherConfirmation
 */
function testAfficherConfirmation() {
    $action = ["oui", "non"];
    ob_start();
    AfficherConfirmation("Êtes-vous sûr ?", "conf1", $action);
    $output = ob_get_clean();
    
    $expected = '<div class="confirm-message" id ="confirmation_side_effect">Êtes-vous sûr ?<form action=\'\' method=\'post\'><input type="hidden" name="id_essai" value="conf1"><button type="submit" class="button" id="side_effect_buttons" name="Action" value="oui">Yes</button><button type="submit" class="button" id="side_effect_buttons" name="Action" value="non">No</button></form></div>';
    echo "Test AfficherConfirmation:<br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($output) . "<br>";
    echo ($output === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

/**
 * Fonction de test pour Affiche_medecin
 */
function testAffiche_medecin() {
    ob_start();
    Affiche_medecin([
        ["nom" => "Dr. Smith"],
        ["nom" => "Dr. Doe"]
    ]);
    $output = ob_get_clean();
    
    $expected = 'Dr. Smith, Dr. Doe';
    echo "Test Affiche_medecin:<br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($output) . "<br>";
    echo ($output === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

/**
 * Fonction de test pour AfficherBarreRecherche
 */
function testAfficherBarreRecherche() {
    ob_start();
    AfficherBarreRecherche("recherche");
    $output = ob_get_clean();
    
    $expected = '<div class="search-container"><form action="" method="post"><div class="search"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"><input type="hidden" name="form_type" value="search_form"><input class="search__input" type="text" name="search_query" placeholder="Search" value="recherche"><button type="submit" id="search__button" class="fa fa-search"></button></div></form></div>';
    echo "Test AfficherBarreRecherche:<br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($output) . "<br>";
    echo ($output === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

// Appel des fonctions de test
echo "<h2>Tests unitaires pour Affichage_gen.php</h2><br>";
testAfficherErreur();
testAfficherInfo();
testAfficherConfirmation();
testAffiche_medecin();
testAfficherBarreRecherche();

?>

</body>