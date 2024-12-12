<!DOCTYPE html>

<head>

    <title>Test de la sécurité</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../src/CSS/global.css">

</head>

<body>

<?php
include("../src/back_php/Securite.php");
include_once("../src/back_php/Patient.php");
include_once("../src/back_php/Utilisateur.php");
include_once("../src/back_php/Securite.php");
include_once("../src/back_php/Affichage_patient.php");
session_start();
$bdd = new Query("siteweb");

/**
 * Fonction générique pour tester VerifyAccountType.
 *
 * @param mixed $id_user L'identifiant de l'utilisateur à tester.
 * @param Query $bdd L'objet de connexion à la base de données.
 * @param string $expected Le type de compte attendu.
 */
function testVerifyAccountType($id_user, $bdd, $expected) {
    echo "Test avec id_user = " . var_export($id_user, true) . "<br>";
    try { // bloc try car Verify AccountType peut lancer une exception si l'id ou la BDD ne sont pas du bon type
        $result = VerifyAccountType($id_user, $bdd);
        echo "Sortie de la fonction: " . $result . "<br>";
        if ($result === $expected)
            echo "Résultat attendu: " . $expected . " - Test réussi<br>"; // cas où la requete fonctionne et renvoie le bon type
        else
            echo "Résultat attendu: " . $expected . " - Test échoué<br>"; // cas où la requete fonctionne mais ne renvoie pas le bon type
    } catch (Exception $e) {
        echo "Exception reçue: " . $e->getMessage() . "<br>";
        if ($expected === "Exception")
            echo "Résultat attendu: Exception - Test réussi<br>";
        else
            echo "Résultat attendu: " . $expected . " - Test échoué<br>";
    }
    echo "<br>";
}

// Test de la fonction VerifyAccountType
echo "<h2>Test de la fonction VerifyAccountType</h2><br><br>";

// Test 1 : l'id donné est un médecin
testVerifyAccountType("margot.hardy@hospital.com", $bdd, "medecin");

// Test 2 : l'id donné est un patient
testVerifyAccountType("brigitte-suzanne.santos@mail.com", $bdd, "patient");

// Test 3 : l'id donné est une entreprise
testVerifyAccountType("entreprise_70@mail.com", $bdd, "entreprise");

// Test 4 : l'id donné est un admin
testVerifyAccountType("gilles.bernot@mail.com", $bdd, "admin");

// Test 5 : l'id donné n'est pas un entier
testVerifyAccountType(2, $bdd, "Exception");

//Test6 : la bdd n'est pas du type query
testVerifyAccountType("tete", "test", "Exception");



/**
 * Fonction de test pour checkFormFields
 */
function testCheckFormFields($test_case, $expected) {
    // Définir les champs requis
    $required_fields = ["Nom", "prénom", "identifiant", "genre", "origin", "medical", "mdp", "mdp2", "date_naissance"];
    
    // Simuler $_POST
    $_POST = $test_case;
    
    // Appeler la fonction à tester
    $result = checkFormFields($required_fields);
    
    // Afficher les résultats
    echo "Test CheckFormFields avec les données : " . json_encode($test_case) . "<br>";
    echo "Résultat attendu : " . ($expected ? "true" : "false") . "<br>";
    echo "Résultat obtenu : " . ($result ? "true" : "false") . "<br>";
    echo ($result === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

// Cas de Test pour checkFormFields
echo "<h2>Tests de la fonction checkFormFields</h2>";

// Test 1 : Tous les champs sont remplis
$test_case1 = [
    "Nom" => "Dupont",
    "prénom" => "Alice",
    "identifiant" => "alice.dupont@mail.com",
    "genre" => "F",
    "origin" => "France",
    "medical" => "Aucun",
    "mdp" => "password123",
    "mdp2" => "password123",
    "date_naissance" => "1990-01-01"
];
testCheckFormFields($test_case1, true);

// Test 2 : Un champ manquant
$test_case2 = [
    "Nom" => "Dupont",
    "prénom" => "Alice",
    // "identifiant" => "alice.dupont@mail.com", // Manquant
    "genre" => "F",
    "origin" => "France",
    "medical" => "Aucun",
    "mdp" => "password123",
    "mdp2" => "password123",
    "date_naissance" => "1990-01-01"
];
testCheckFormFields($test_case2, false);

// Test 3 : Un champ vide
$test_case3 = [
    "Nom" => "Dupont",
    "prénom" => "Alice",
    "identifiant" => "", // Vide
    "genre" => "F",
    "origin" => "France",
    "medical" => "Aucun",
    "mdp" => "password123",
    "mdp2" => "password123",
    "date_naissance" => "1990-01-01"
];
testCheckFormFields($test_case3, false);

// Test 4 : Tous les champs vides
$test_case4 = [
    "Nom" => "",
    "prénom" => "",
    "identifiant" => "",
    "genre" => "",
    "origin" => "",
    "medical" => "",
    "mdp" => "",
    "mdp2" => "",
    "date_naissance" => ""
];
testCheckFormFields($test_case4, false);


/**
 * Fonction de test pour checkPassword
 */
function testCheckPassword($password, $password_confirm, $expected) {
    // Appeler la fonction à tester
    $result = checkPassword($password, $password_confirm);
    
    // Afficher les résultats
    echo "Test CheckPassword avec password = '$password' et password_confirm = '$password_confirm'<br>";
    echo "Résultat attendu : " . ($expected ? "true" : "false") . "<br>";
    echo "Résultat obtenu : " . ($result ? "true" : "false") . "<br>";
    echo ($result === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

// Cas de Test pour checkPassword
echo "<h2>Tests de la fonction checkPassword</h2>";

// Test 1 : Mots de passe identiques
testCheckPassword("password123@@", "password123@@", true);

// Test 2 : Mots de passe différents
testCheckPassword("password123", "password456", false);

// Test 3 : Mots de passe vides et identiques
testCheckPassword("", "", false);

// Test 4 : Un mot de passe vide, l'autre non
testCheckPassword("password123", "", false);

// Test 5 : Mots de passe avec caractères spéciaux
testCheckPassword("p@ssw0rd!", "p@ssw0rd!", true);

// Test 6 : Mots de passe avec majuscules et minuscules
testCheckPassword("Password123", "password123", false);


/**
 * Fonction de test pour validatePassword
 */
function testValidatePassword($password, $expected) {
    $result = validatePassword($password);
    
    echo "Test ValidatePassword avec le mot de passe : '$password'<br>";
    echo "Résultat attendu : " . ($expected ? "true" : "false") . "<br>";
    echo "Résultat obtenu : " . ($result ? "true" : "false") . "<br>";
    echo ($result === $expected) ? "Test réussi<br><br>" : "Test échoué<br><br>";
}

// Cas de Test pour validatePassword
echo "<h2>Tests de la fonction validatePassword</h2>";

// Test 1 : Mot de passe valide
testValidatePassword("Password123!", true);

// Test 2 : Moins de 8 caractères
testValidatePassword("Pass1!", false);

// Test 3 : Pas de chiffre
testValidatePassword("Password!", false);

// Test 4 : Pas de caractère spécial
testValidatePassword("Password123", false);

// Test 5 : Tous les critères remplis
testValidatePassword("A1b2C3d4!", true);

// Test 6 : Caractères spéciaux multiples
testValidatePassword("P@ssw0rd#2024", true);


/**
 * Fonction de test pour registerNewPatient
 * 
 * Note : Cette fonction modifie $_POST et appelle registerNewPatient.
 * Les redirections et les sorties directes feront que certains tests s'arrêteront.
 * Pour une gestion plus avancée, considérez l'utilisation de PHPUnit ou d'un autre framework de test.
 */

// Cas de Test pour registerNewPatient
echo "<h2>Tests de la fonction registerNewPatient</h2>";
echo "Le test devrait retourner un warning stipulant que la redirection vers la page accueil.php n'est pas possible depuis le fichier de test.<br><br>";
echo "Cependant la fonction dépend des formulaire de la page login donc elle a été testée manuellement sur l'interface.<br><br>";


?>


</body>