<!DOCTYPE html>

<head>

    <title>Page de connexion</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="CSS/page_login.css">

</head>

<body>

    <div id = "global">
        <!-- division pour le fond de la page de connexion -->
        <div id = "fond">
            <img src="Ressources/Images/background_login_signin.png" alt="fond">
        </div id = "fond">

        <!-- Division pour les attributs de la page devant le fond de l'arrière plan -->
        <div id = "premier_plan">
            <!-- division pour le fond du 1er plan -->
            <div id = fond_1er_plan>
                <img src="Ressources/Images/foreground_login_signin.png" alt="fond_1er_plan">
            </div id = fond_1er_plan>
        </div id = "premier_plan">

        <!-- division pour les élements du 1er plan (logo et formulaire) -->
        <div id = "element_1er_plan">
            <!-- division pour le logo du 1er plan -->
            <div id = "logo">
                <img src="Ressources/Images/logo_login_signin.png" alt="logo">
            </div id = "logo">

            <!-- division pour le formulaire de connexion -->
            <div id = "interface_connexion">
                <div id = "title"> <h1>Login</h1> </div>

                <!-- Division pour contenir les champs à remplir pour la connexion -->
                <div id="texte_connexion">
                    <div class="champs">
                        <form action="" method="post">
                            <label for="identifiant">Email</label>
                            <input type="text" id="identifiant" name="identifiant" required>
                            <br><br>
                            <label for="mdp">Password</label>
                            <input type="password" id="mdp" name="mdp" required>
                            <a href="#" id="forgot_password">Forgot your password?</a>
                            <br><br>
                            <div id="bouton_de_confirmation">
                                <button type="submit" class="button">Connect</button>
                                <button type="button" class="button">Back</button>
                            </div id = "bouton_de_confirmation" >
                        </form>
                    </div class = 'champs'>
                </div id="texte_connexion">

            </div id = "interface_connexion">

        </div id = "element_1er_plan">

    </div id = "global">



</body>

<?php
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
?>