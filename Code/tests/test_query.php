<?php   
include_once("../src/back_php/Query.php");

$bdd = new Query("siteweb");


// Test de la fonction select (getResults) sans argument spécifique
$query1 = "SELECT * FROM `utilisateur` LIMIT 1";
$res = $bdd->getResults($query1, []);
echo "Test de la fonction select (getResults) sans argument spécifique : <br> ";
echo "Requête : ".$query1."<br>";
echo "Nom utilisateur : ".$res["nom"]."<br>";


$query2 = "SELECT * FROM `utilisateur`";
$res = $bdd->getResultsAll($query2, []);
echo "<br>Test de la fonction select (getResultsAll) sans argument spécifique : <br> ";
echo "Requête : ".$query2."<br>";
echo "Nombre d'utilisateurs : ".count($res)."<br>";
echo "Nom utilisateur 1 : ".$res[0]["nom"]."<br>";
echo "Nom utilisateur 2 : ".$res[1]["nom"]."<br>";


$query2 = "SELECT * FROM `utilisateur` WHERE `nom` = ?";
$res = $bdd->getResultsAll($query2, ["admin"]);
echo "<br>Test de la fonction select (getResultsAll) avec argument spécifique qui ressort un résultat vide: <br> ";
echo "Requête : ".$query2."<br>";
echo "nom = admin";
echo "Nombre d'utilisateurs : ".count($res)."<br>";

$query2 = "SELECT * FROM `utilisateur` WHERE `nom` = ?";
$res = $bdd->getResultsAll($query2, ["Dupont"]);
echo "<br>Test de la fonction select (getResultsAll) avec argument spécifique qui ressort un résultat non vide: <br> ";
echo "Requête : ".$query2."<br>";
echo "nom = Bernot<br>";
echo "Nombre d'utilisateurs : ".count($res)."<br>";


$query2 = "SELECT * FROM `utilisateur` WHERE `nom` = :nom AND `prenom` = :prenom";
$res = $bdd->getResultsAll($query2, ["nom" => "Dupont", "prenom" => "Alice"]);
echo "<br>Test de la fonction select (getResultsAll) avec plusieurs arguments spécifiques qui ressort un résultat non vide: <br> ";
echo "Requête : ".$query2."<br>";
echo "nom = Dupont, prenom = Alice<br>";
echo "Nombre d'utilisateurs : ".count($res)."<br>";

// test de la fonction update
$query3 = "UPDATE `utilisateur` SET `nom` = :nom WHERE `nom` = :nom2";
echo "<br>Test de la fonction update : <br> ";
echo "Requête : ".$query3."<br>";
echo "nom = Bernot, nom2 = BERNOT<br>";
echo "Nom avant mise à jour ".$bdd->getResults($query1, [])["nom"]."<br>";
$bdd->UpdateLines($query3, ["nom" => "BERNOT", "nom2" => "Bernot"]);
echo "Nom après mise à jour ".$bdd->getResults($query1, [])["nom"]."<br>";

// test de la fonction insert
$query4 = "INSERT INTO `utilisateur` (`nom`, `prenom`, `mail`, `mdp`, `genre`) VALUES (:nom, :prenom, :mail, :mdp, :genre)";
echo "<br>Test de la fonction insert : <br> ";
echo "Requête : ".$query4."<br>";
echo "nom = Dupont, prenom = Alice, mail =test33@test.com, mdp = test, genre = F<br>";
$bdd->insertLine($query4, ["nom" => "Dupont", "prenom" => "Alice", "mail" => "test@test.com", "mdp" => "test", "genre" => "F"]);

$query_del = "DELETE FROM `utilisateur` WHERE `mail` = :mail";
$bdd->deleteLines($query_del, ["mail" => "test33@test.com"]);

// test de récupérer un calcul de SQL (COUNT(*))
$query5 = "SELECT COUNT(*) FROM resultat NATURAL JOIN essai
             WHERE ID_patient = 10 AND a_debute = 2;";
$res = $bdd->getResults($query5, []);
echo "<br>Test de la fonction select (getResults) avec un calcul SQL : <br> ";
echo "Requête : ".$query5."<br>";
echo "Nombre de résultats : ".$res["COUNT(*)"]."<br>";

$bdd->closeBD();


?>