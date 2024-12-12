<?php
session_start();
?>
<!DOCTYPE html>

<head>

    <title>Page de connexion</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_login.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">


</head>

<body>
        <!-- division pour le fond de la page de connexion -->


        <!-- division pour les élements du 1er plan (logo et formulaire) -->
        <div id = "global_container">
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
                            <input type="text" id="mail" name="mail"/>
                        </div>

                        <div class="input_info">
                            <label for="mdp">Password</label>
                            <input type="password" id="mdp" name="mdp"/>
                            <a href="https://youtu.be/dQw4w9WgXcQ?si=AQVw_SuShPwkfGUW" id="forgot_password">Forgot your password?</a>
                        </div>

                        <div class="buttons">
                            <button class = button name="Action" id = "button_login" value="login" type="submit">Connect</button>
                            <button class = button name="Action" id = "button_login" value="back" type="submit">Back</button>
                        </div>

                    <?php
                    # Inclure la classe utilisateur pour pouvoir connecter un utilisateur
                    require_once('../back_php/Patient.php');
                    include_once("../back_php/Query.php");
                    include_once("../back_php/Securite.php");
                    include_once("../back_php/Entreprise.php");

                        
                        # Est-ce que l'utilisateur a remplie le formulaire ?
                        if (isset($_POST["mail"]) && isset($_POST["mdp"])) {
                            // Inclure le fichier de connexion à la base de données
                            $bdd = new Query("siteweb");
                            $account_type = VerifyAccountType($_POST["mail"], $bdd);
                            switch ($_POST["Action"]){
                                case 'back':
                                    header("Location: page_accueil.php");
                                    break;
                                
                                case 'login':
                                    if (checkStatus($_POST["mail"], $bdd) == false) {
                                        header("Location: page_ban.html");
                                        exit;
                                    }
        
                                    if ($account_type == "medecin") {
                                        if ($account_type == "medecin") {
                                            $user = new Medecin(
                                                iduser: null,
                                                mdp: $_POST["mdp"],
                                                email: $_POST["mail"],
                                                last_name: null,
                                                is_banned: null,
                                                is_admin: null,
                                                first_name: null,
                                                birthdate: null,
                                                gender: null,
                                                antecedent: null,
                                                origins: null
                                            );
                                            $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                            $_SESSION["medecin"] = $user;
                                            header("Location: page_medecin.php");
                                        }
                                    } else if ($account_type == "entreprise") {
                                        $user = new Entreprise(
                                            iduser: null,
                                            mdp: $_POST["mdp"],
                                            email: $_POST["mail"],
                                            last_name: null,
                                            is_banned: null,
                                            is_admin: null,
                                            first_name: null,
                                            birthdate: null,
                                            gender: null,
                                            antecedent: null,
                                            origins: null,
                                            ville : null,
                                            siret : null
                                            );
                                        $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                        $_SESSION["entreprise"] = $user;
                                        header("Location: page_entreprise.php");
                                        exit;
                                    } else if ($account_type == "admin") {
                                        $user = new Patient(
                                            iduser: null,
                                            mdp: $_POST["mdp"],
                                            email: $_POST["mail"],
                                            last_name: null,
                                            is_banned: null,
                                            is_admin: 1,
                                            first_name: null,
                                            birthdate: null,
                                            gender: null,
                                            antecedent: null,
                                            origins: null
                                        );
                                        $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                        $_SESSION["admin"] = $user;
                                        header("Location: page_admin.php");

                                    } else if ($account_type == "patient") {
                                        $user = new Patient(
                                            iduser: null,
                                            mdp: $_POST["mdp"],
                                            email: $_POST["mail"],
                                            last_name: null,
                                            is_banned: null,
                                            is_admin: null,
                                            first_name: null,
                                            birthdate: null,
                                            gender: null,
                                            antecedent: null,
                                            origins: null
                                        );
                                        $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                        $_SESSION["patient"] = $user;
                                        header("Location: page_patient.php");
                                    } else
                                        echo "Error while loging in. User not found.";
                                    break;
                            }

                            if ($account_type == "medecin") {
                                $user = new Medecin(
                                    iduser: null,
                                    mdp: $_POST["mdp"],
                                    email: $_POST["mail"],
                                    last_name: null,
                                    is_banned: null,
                                    is_admin: null,
                                    first_name: null,
                                    birthdate: null,
                                    gender: null,
                                    antecedent: null,
                                    origins: null
                                );
                                $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                $_SESSION["medecin"] = $user;
                                header("Location: page_medecin.php");
                            } else if ($account_type == "entreprise") {
                                $user = new Entreprise(
                                    iduser: null,
                                    mdp: $_POST["mdp"],
                                    email: $_POST["mail"],
                                    last_name: null,
                                    is_banned: null,
                                    is_admin: null,
                                    first_name: null,
                                    birthdate: null,
                                    gender: null,
                                    antecedent: null,
                                    origins: null,
                                    ville : null,
                                    siret : null
                                    );
                                $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                $_SESSION["entreprise"] = $user;
                                header("Location: page_entreprise.php");
                                exit;
                            } else if ($account_type == "admin") {
                                header("Location: page_admin.php");
                                exit;
                            } else if ($account_type == "patient") {
                                $user = new Patient(
                                    iduser: null,
                                    mdp: $_POST["mdp"],
                                    email: $_POST["mail"],
                                    last_name: null,
                                    is_banned: null,
                                    is_admin: null,
                                    first_name: null,
                                    birthdate: null,
                                    gender: null,
                                    antecedent: null,
                                    origins: null
                                );
                                $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd);
                                $_SESSION["patient"] = $user;
                                header("Location: page_patient.php");
                            } else
                                echo "Erreur lors de la connexion : type de compte inconnu";
                            // $bdd_connect = $bdd->getConnection();
                            // $user = new Utilisateur(iduser:"temporary", mdp:$_POST["mdp"],email:$_POST["mail"],last_name:"temporary",is_banned:0,is_admin:0);
                            // // Appeler la fonction connexion de la classe utilisateur
                            // $result = $user->Connexion($_POST["mail"], $_POST["mdp"], $bdd_connect);
                        }
                    ?>

                    </form>

                </div>
            </div>
</body>
 
