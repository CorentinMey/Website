<!DOCTYPE html>

<head>

    <title>Page de connexion</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_login.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">


</head>

<body>
        <!-- division pour le fond de la page de connexion -->
        <img src="../Ressources/Images/background_login_signin.png" alt="fond" id="fond">

        <!-- division pour le fond du 1er plan -->

        <!-- division pour les élements du 1er plan (logo et formulaire) -->
        <div id="element_1er_plan">
            <!-- division pour le logo du 1er plan -->
            <img src="../Ressources/Images/logo_login_signin.png" alt="logo" id="logo">

            <!-- division pour le formulaire de connexion -->
            <div id = "interface_connexion">
                <h1 id="title">Login</h1>

                <!-- Division pour contenir les champs à remplir pour la connexion -->
                <form action="" method="post">

                    <div class="input_info">
                        <label for="mail">Email</label>
                        <input type="text" id="mail" name="mail" required/>
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

                    <?php
                        session_start();

                        # Inclure la classe utilisateur pour pouvoir connecter un utilisateur
                        require_once('../back_php/Utilisateur.php');
                        include_once("../back_php/Query.php");
                        
                        # Est-ce que l'utilisateur a remplie le formulaire ?
                        if (isset($_POST["mail"]) && isset($_POST["mdp"])) {
                            // Inclure le fichier de connexion à la base de données
                            $bdd = new Query("siteweb");
                            $bdd_connect = $bdd->getConnection();
                            $user = new Utilisateur(iduser:"temporary", mdp:$_POST["mdp"],email:$_POST["mail"],last_name:"temporary",is_banned:0,is_admin:0);
                            // Appeler la fonction connexion de la classe utilisateur
                            $result = $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd_connect);
                        }
                        session_abort();
                    ?>

                </form>

            </div>
</body>
 