<!DOCTYPE html>

<head>

    <title>Page de connexion</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="CSS/index.css">

</head>

<body>

    <!-- division pour le fond de la page de connexion -->
    <img src="Ressources/Images/background_login_signin.png" alt="fond" id="fond">

    <!-- division pour le fond du 1er plan -->

    <!-- division pour les élements du 1er plan (logo et formulaire) -->
    <div id="element_1er_plan">
        <!-- division pour le logo du 1er plan -->
        <img src="Ressources/Images/logo_login_signin.png" alt="logo" id="logo">

        <!-- division pour le formulaire de connexion -->
        <div id = "interface_connexion">
            <h1 id="title">Login</h1>

            <!-- Division pour contenir les champs à remplir pour la connexion -->
            <form action="" method="post">

                <div class="input_info">
                    <label for="identifiant">Email</label>
                    <input type="text" id="identifiant" name="identifiant" required/>
                </div>

                <div class="input_info">
                    <label for="mdp">Password</label>
                    <input type="password" id="mdp" name="mdp" required/>
                    <a href="#" id="forgot_password">Forgot your password?</a>
                </div>

                <div class="buttons">
                    <button type="submit">Connect</button>
                    <button type="button">Back</button>
                </div>
            </form>

        </div>

    </div>

</body>
 
<!--<?php
session_start();

if (isset($_POST["identifiant"]) && isset($_POST["mail"]) && isset($_POST["password"])) {

    // Inclure le fichier de connexion à la base de données
    include("back_php/Query.php");
    $bdd = new Query("php_td3");

    // Préparer la requête de sélection
    # TODO : Changer le nom de la table quand la BDD sera chargée et remplie
    $query = "SELECT * FROM personnesSondees WHERE identifiant = :id AND email = :mail;";
    $data = [
        "id" => $_POST["identifiant"],
        "mail" => $_POST["mail"]
    ];
    // Exécuter la requête
    $res = $bdd->getResults($bdd, $query, $data);

    // Vérifier si la requête renvoie un résultat
    if ($user = $res->fetch()) {
        // Vérifier le mot de passe (password_varify test si les hash sont identiques ou non)
        if (password_verify($_POST["password"], $user["passwd"])) {
            // Si le mot de passe est correct, continuer le traitement
            $_SESSION["identifiant"] = $_POST["identifiant"];
            $_SESSION["idperso"] = $user["idpers"];
            if ($user["admin"] == 0)
                header("Location: login_survey.php");
            else
                header("Location: admin_page.php");
            exit(); // Assurez-vous de terminer le script après la redirection
        } else  // Si le mot de passe est incorrect, afficher un message d'erreur
            echo "Mot de passe incorrect.";
    } else // Si aucun résultat n'est trouvé, afficher un message d'erreur
        echo "Identifiant ou email incorrect.";

    // Fermer le curseur
    $bdd->closeStatement($res);
    $bdd->closeBD($bdd);
}
session_abort();
?> -->
