<?php
include("../src/back_php/Securite.php");
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
echo "Test de la fonction VerifyAccountType<br><br>";

// Test 1 : l'id donné est un médecin
testVerifyAccountType(1, $bdd, "medecin");

// Test 2 : l'id donné est un patient
testVerifyAccountType(10, $bdd, "patient");

// Test 3 : l'id donné est une entreprise
testVerifyAccountType(12345678901234, $bdd, "entreprise");

// Test 4 : l'id donné est un admin
testVerifyAccountType(25, $bdd, "admin");

// Test 5 : l'id donné n'est pas un entier
testVerifyAccountType("test", $bdd, "Exception");

//Test6 : la bdd n'est pas du type query
testVerifyAccountType(1, "test", "Exception");

?>