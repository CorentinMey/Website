<DOCTYPE html>
<html>
<head>
    <title>Test Affichage gen</title>
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

/**
 * Fonction générique pour afficher un message de résultat de test
 */
function afficherResultatTest($test_name, $expected, $actual) {
    echo "<strong>$test_name:</strong><br>";
    echo "Résultat attendu : " . htmlspecialchars($expected) . "<br>";
    echo "Résultat obtenu : " . htmlspecialchars($actual) . "<br>";
    echo ($actual === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

/**
 * Permet de tester la fonction pour générer une requête de recherche
 */
function testGenerateSearchQuery() {
    // Cas de Test 1 : Requête de recherche simple
    $search_query = "Clinique";
    $id_patient = 1;
    $expected_query = "SELECT DISTINCT e.ID_essai, u.nom, e.description, e.titre, e.date_debut, e.ID_phase
                FROM essai e
                JOIN utilisateur u ON e.ID_entreprise_ref = u.ID_User
                LEFT JOIN essai_medecin em ON e.ID_essai = em.ID_essai
                LEFT JOIN utilisateur m ON em.ID_medecin = m.ID_User
                WHERE 
                    (u.nom LIKE :search OR
                    e.titre LIKE :search OR
                    e.description LIKE :search OR
                    e.ID_phase LIKE :search OR
                    m.nom LIKE :search)
                    AND e.a_debute = 0
                    AND e.ID_essai NOT IN (SELECT ID_essai FROM resultat WHERE ID_patient = :id_patient)";
    $expected_params = [
        ':search' => '%Clinique%',
        ':id_patient' => 1
    ];

    $result = generateSearchQuery($search_query, $id_patient);
    $actual_query = $result['query'];
    $actual_params = $result['params'];

    // Comparer et afficher le résultat
    afficherResultatTest("generateSearchQuery - Cas 1", $expected_query, $actual_query);
    afficherResultatTest("generateSearchQuery - Cas 1 Params", json_encode($expected_params), json_encode($actual_params));

    // Cas de Test 2 : Requête de recherche avec caractères spéciaux
    $search_query = "Test@123";
    $id_patient = 2;
    $expected_params = [
        ':search' => '%Test@123%',
        ':id_patient' => 2
    ];

    $result = generateSearchQuery($search_query, $id_patient);
    $actual_query = $result['query'];
    $actual_params = $result['params'];

    // Comparer et afficher le résultat
    afficherResultatTest("generateSearchQuery - Cas 2", $expected_query, $actual_query);
    afficherResultatTest("generateSearchQuery - Cas 2 Params", json_encode($expected_params), json_encode($actual_params));

    // Cas de Test 3 : Requête de recherche vide
    $search_query = "";
    $id_patient = 3;
    $expected_params = [
        ':search' => '%%',
        ':id_patient' => 3
    ];

    $result = generateSearchQuery($search_query, $id_patient);
    $actual_query = $result['query'];
    $actual_params = $result['params'];

    // Comparer et afficher le résultat
    afficherResultatTest("generateSearchQuery - Cas 3", $expected_query, $actual_query);
    afficherResultatTest("generateSearchQuery - Cas 3 Params", json_encode($expected_params), json_encode($actual_params));
}

// Appel des fonctions de test
echo "<h2>Tests unitaires pour Afficher_erreur</h2><br>";
testAfficherErreur();
echo "<h3>==============================================================================================================================================================</h3>";
echo "<h2>Tests unitaires pour AfficherInfo</h2><br>";
testAfficherInfo();
echo "<h3>==============================================================================================================================================================</h3>";
echo "<h2>Tests unitaires pour AfficherConfirmation</h2><br>";
testAfficherConfirmation();
echo "<h3>==============================================================================================================================================================</h3>";
echo "<h2>Tests unitaires pour Affiche_medecin</h2><br>";
testAffiche_medecin();
echo "<h3>==============================================================================================================================================================</h3>";
echo "<h2>Tests unitaires pour AfficherBarreRecherche</h2><br>";
testAfficherBarreRecherche();



echo "<h1>==============================================================================================================================================================</h1>";
echo "<h2> Tests unitaires pour AfficherEssaisPAsdemarré (et Affichage_content_essai_pas_demarre)</h2><br>";
    echo "<h3> Si tous les arguments sont bons, la fonction devrait afficher les essais non démarrés</h3>";
    $bdd = new Query("siteweb");
    $patien3 = new Patient(mdp : "123456789!@", email : "brigitte-suzanne.santos@mail.com");
    $patien3->Connexion($patien3->getEmail(), $patien3->getMdp(), $bdd);
    AfficherEssaisPasDemarré($bdd, $patien3);

    echo "<h3> Si un argument est manquant, la fonction devrait afficher un message d'erreur</h3>";
    AfficherEssaisPasDemarré($bdd,75);

echo "<h3>==============================================================================================================================================================</h3>";
echo '<h2>Tests unitaires AfficherEssaisRecherche (idem avant mais avec un paramètre de recherche)</h2>;';
    echo "<h3> Si tous les arguments sont bons, la fonction devrait afficher les essais recherchés</h3>";
    echo "Mot recherché : 5<br>";
    AfficherEssaisRecherche($bdd, $patien3, "5");

    // test avec un mot sans sens
    echo "Mot recherché : 75<br>";
    AfficherEssaisRecherche($bdd, $patien3, 75);

    // test avec les mauvais arguments
    echo "Mot recherché : 75<br>";
    AfficherEssaisRecherche($bdd, 75, 75);


// Appel des tests
echo "<h3>==============================================================================================================================================================</h3>";
echo "<h2>Tests unitaires pour GenerateSearchQuery.php</h2><br>";
testGenerateSearchQuery();

?>

</body>