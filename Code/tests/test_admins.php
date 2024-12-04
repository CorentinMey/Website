<?php
include("../src/back_php/Query.php");
include("../src/back_php/Affichage_admin.php");

/**
 * Fonction pour tester l'affichage des différentes listes administratives.
 *
 * @param string $button_name Nom du bouton testé.
 * @param Query $bdd Objet de connexion à la base de données.
 * @param string $expected Résultat attendu (chaîne contenant les données ou une exception attendue).
 */
function testAffichageAdmin($button_name, $bdd, $expected) {
    echo "Test avec bouton = " . var_export($button_name, true) . "<br>";
    try {
        // Simuler une action POST
        $_POST[$button_name] = 1;
        
        // Appeler la fonction d'affichage correspondante
        ob_start(); // Démarre la capture du contenu
        switch ($button_name) {
            case 'show_list_user':
                afficherListeUtilisateurs($bdd);
                break;
            case 'show_list_doc':
                afficherListeMedecins($bdd);
                break;
            case 'show_list_company':
                afficherListeEntreprises($bdd);
                break;
            case 'show_list_clinical':
                afficherListeEssaisCliniques($bdd);
                break;
            case 'show_list_confirmation':
                afficherConfirmationsEnAttente($bdd);
                break;
            default:
                throw new Exception("Bouton inconnu: $button_name");
        }
        $output = ob_get_clean(); // Récupère et nettoie la capture
        
        // Vérification du résultat
        echo "Sortie de la fonction:<br>" . htmlentities($output) . "<br>";
        if (strpos($output, $expected) !== false)
            echo "Résultat attendu: \"" . htmlentities($expected) . "\" - Test réussi<br>";
        else
            echo "Résultat attendu: \"" . htmlentities($expected) . "\" - Test échoué<br>";
    } catch (Exception $e) {
        ob_end_clean(); // Annule la capture en cas d'exception
        echo "Exception reçue: " . $e->getMessage() . "<br>";
        if ($expected === "Exception")
            echo "Résultat attendu: Exception - Test réussi<br>";
        else
            echo "Résultat attendu: \"" . htmlentities($expected) . "\" - Test échoué<br>";
    }
    echo "<br>";
}

// Connexion à la base de données
$bdd = new Query("siteweb");

echo "Test des fonctionnalités de la page Admin<br><br>";

// Test 1 : Afficher la liste des utilisateurs
testAffichageAdmin("show_list_user", $bdd, "Utilisateur");

// Test 2 : Afficher la liste des médecins
testAffichageAdmin("show_list_doc", $bdd, "Médecin");

// Test 3 : Afficher la liste des entreprises
testAffichageAdmin("show_list_company", $bdd, "Entreprise");

// Test 4 : Afficher la liste des essais cliniques
testAffichageAdmin("show_list_clinical", $bdd, "Essai clinique");

// Test 5 : Afficher les confirmations en attente
testAffichageAdmin("show_list_confirmation", $bdd, "Confirmation");

// Test 6 : Bouton inconnu
testAffichageAdmin("unknown_button", $bdd, "Exception");

// Test 7 : Base de données non valide
testAffichageAdmin("show_list_user", "invalid_db", "Exception");
?>

<?php
include("../src/back_php/Query.php");
include("../src/back_php/status.php");

/**
 * Fonction générique pour tester les actions admin : ban, unban, reject, accept.
 *
 * @param string $action Action à tester (ban, unban, reject, accept).
 * @param int $id_user ID de l'utilisateur cible.
 * @param Query $bdd Instance de connexion à la base de données.
 * @param string $expected Résultat attendu.
 */
function testActionAdmin($action, $id_user, $bdd, $expected) {
    echo "Test de l'action = " . var_export($action, true) . " avec id_user = " . var_export($id_user, true) . "<br>";
    try {
        $result = false;
        switch ($action) {
            case 'ban':
                $result = banUser($id_user, $bdd);
                break;
            case 'unban':
                $result = unbanUser($id_user, $bdd);
                break;
            case 'reject':
                $result = rejectUser($id_user, $bdd);
                break;
            case 'accept':
                $result = acceptUser($id_user, $bdd);
                break;
            default:
                throw new Exception("Action inconnue: $action");
        }

        // Vérification du résultat
        if ($result === $expected) {
            echo "Résultat attendu: " . var_export($expected, true) . " - Test réussi<br>";
        } else {
            echo "Résultat attendu: " . var_export($expected, true) . " - Test échoué<br>";
        }
    } catch (Exception $e) {
        echo "Exception reçue: " . $e->getMessage() . "<br>";
        if ($expected === "Exception") {
            echo "Résultat attendu: Exception - Test réussi<br>";
        } else {
            echo "Résultat attendu: " . var_export($expected, true) . " - Test échoué<br>";
        }
    }
    echo "<br>";
}

// Connexion à la base de données
$bdd = new Query("siteweb");

echo "Test des actions admin (ban, unban, reject, accept)<br><br>";

// Test 1 : Bannir un utilisateur
testActionAdmin("ban", 1, $bdd, true); // Remplace "1" par l'ID d'un utilisateur valide dans ta base

// Test 2 : Débannir un utilisateur
testActionAdmin("unban", 2, $bdd, true); // Remplace "2" par l'ID d'un utilisateur banni

// Test 3 : Rejeter un utilisateur
testActionAdmin("reject", 3, $bdd, true); // Remplace "3" par l'ID d'un utilisateur en attente

// Test 4 : Accepter un utilisateur
testActionAdmin("accept", 4, $bdd, true); // Remplace "4" par l'ID d'un utilisateur en attente

// Test 5 : Action avec un ID d'utilisateur invalide
testActionAdmin("ban", 9999, $bdd, false); // "9999" : ID d'utilisateur non existant

// Test 6 : Action inconnue
testActionAdmin("unknown", 1, $bdd, "Exception");

// Test 7 : Base de données invalide
testActionAdmin("ban", 1, "invalid_db", "Exception");
?>
