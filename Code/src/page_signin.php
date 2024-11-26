<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Page d'inscription</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="CSS/global.css">
    <link rel="stylesheet" type="text/css" href="CSS/page_signin.css">
</head>

<body>
    <!-- division pour le fond de la page de connexion -->
    <img src="Ressources/Images/background_login_signin.png" alt="fond" id="fond">

    <!-- division pour le fond du 1er plan -->
    <!-- division pour les éléments du 1er plan (logo et formulaire) -->
    <div id="element_1er_plan">

        <!-- division pour le formulaire de connexion -->
        <div id="interface_connexion">
            <h1 id="title">Signin</h1>

            <!-- Division pour contenir les champs à remplir pour la connexion -->
            <form action="" method="post">
                <div class="input_info">
                    <label for="Nom">Family name</label>
                    <input type="text" id="Nom" name="Nom" required/>
                </div>

                <div class="input_info">
                    <label for="prénom">First name</label>
                    <input type="text" id="prénom" name="prénom" required/>
                </div>

                <div class="input_info">
                    <label for="genre">Gender</label>
                    <select id="genre" name="genre" class="deroulant" required>
                        <option value="">Select your gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="input_info">
                    <label for="origin">Origins</label>
                    <select id="origin" name="origin" class="deroulant" required>
                        <option value="">Select the account</option>
                        <option value="Europe">Europe</option>
                        <option value="North America">North America</option>
                        <option value="South America">South America</option>
                        <option value="Africa">Africa</option>
                        <option value="Asia">Asia</option>
                        <option value="Oceania">Oceania</option>
                    </select>
                </div>

                <div class="input_info">
                    <label for="account_type">Account</label>
                    <select id="account_type" name="account_type" class="deroulant" required>
                        <option value="">Select the account</option>
                        <option value="Patient">Patient</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Company">Company</option>
                    </select>
                </div>

                <div class="input_info">
                    <label for="identifiant">Email</label>
                    <input type="text" id="identifiant" name="identifiant" required/>
                </div>

                <div class="input_info">
                    <label for="mdp">Password</label>
                    <input type="password" id="mdp" name="mdp" required/>
                </div>

                <div class="input_info">
                    <label for="mdp2">Confirm password</label>
                    <input type="password" id="mdp2" name="mdp2" required/>
                </div>


                <div class="buttons">
                    <button type="submit">Connect</button>
                    <button type="button">Back</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>