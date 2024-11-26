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
function VerifyAccountType($mail_user, $bdd)
{
    if (!is_string($mail_user)) 
        throw new InvalidArgumentException('Le mail de l\'utilisateur doit être une chaine de caractères.');
    if (!($bdd instanceof Query))
        throw new InvalidArgumentException('L\'objet de connexion doit être une instance de Query.');

    $data = ["mail_user" => $mail_user];
    $query_medecin = "SELECT numero_ordre FROM medecin JOIN utilisateur ON medecin.numero_ordre = utilisateur.ID_User
	                        WHERE mail = :mail_user;";
    $query_entreprise = "SELECT siret FROM entreprise JOIN utilisateur ON entreprise.siret = utilisateur.ID_User
                            WHERE mail = :mail_user;";
    $query_admin = "SELECT ID_User FROM utilisateur 
                            WHERE mail = :mail_user AND is_admin = 1;";

    $res_medecin = $bdd->getResults($query_medecin, $data);
    $res_entreprise = $bdd->getResults($query_entreprise, $data);
    $res_admin = $bdd->getResults($query_admin, $data);

    if ($res_medecin != [])
        $out = "medecin";
    else if ($res_entreprise != []) 
        $out = "entreprise";
    else if ($res_admin != [])
        $out = "admin";
    else
        $out = "patient";
    return $out;
}

/**
 * Vérifie si un utilisateur est banni.
 *
 * @param array $user_data Les données du patient à vérifier avant d'envoyer les infos à la bdd
 * 
 */
function checkFormFields($fields) {
    foreach ($fields as $field) {
        if (empty($_POST[$field]))
            return false;
    }
    return true;
}

?>