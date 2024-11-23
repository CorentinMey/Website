<?php

include_once("Query.php");



/**
 * Vérifie le type de compte d'un utilisateur.
 *
 * @param int $id_user L'identifiant de l'utilisateur.
 * @param Query $bdd L'objet de connexion à la base de données.
 * @return string Le type de compte de l'utilisateur : "medecin", "entreprise" ou "patient".
 * @throws InvalidArgumentException Si les types des arguments ne sont pas corrects.
 */
function VerifyAccountType($id_user, $bdd)
{
    if (!is_int($id_user)) 
        throw new InvalidArgumentException('L\'identifiant de l\'utilisateur doit être un entier.');
    if (!($bdd instanceof Query))
        throw new InvalidArgumentException('L\'objet de connexion doit être une instance de Query.');

    $data = ["id_user" => $id_user];
    $query_medecin = "SELECT numero_ordre FROM medecin 
	                        WHERE numero_ordre = :id_user;";
    $query_entreprise = "SELECT siret FROM entreprise 
                            WHERE siret = :id_user;";
    $query_admin = "SELECT ID_User FROM utilisateur 
                        WHERE ID_User = :id_user AND is_admin = 1;";

    $res_medecin = $bdd->getResults($query_medecin, $data);
    $res_entreprise = $bdd->getResults($query_entreprise, $data);

    if ($res_medecin->rowCount() > 0)
        $out = "medecin";
    else if ($res_entreprise->rowCount() > 0) 
        $out = "entreprise";
    else if ($bdd->getResults($query_admin, $data)->rowCount() > 0)
        $out = "admin";
    else
        $out = "patient";
    $bdd->closeStatement($res_medecin);
    $bdd->closeStatement($res_entreprise);
    return $out;
}

?>