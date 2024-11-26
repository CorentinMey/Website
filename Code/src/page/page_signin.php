<?php
include_once("../back_php/Patient.php");
include_once("../back_php/Query.php");
session_start();
if (isset($_SESSION["patient"])) {
    $patient = $_SESSION["patient"];
    $first_name = $patient->getFirst_name();
    $last_name = $patient->getLast_name();
    $email = $patient->getEmail();
    $gender = $patient->getGender();
    $origins = $patient->getOrigins();
    $antecedent = $patient->getAntecedent();
    $bdd = new Query("siteweb");
    // Récupérez les autres informations nécessaires
} else {
    $first_name = "";
    $last_name = "";
    $email = "";
    $gender = "Select your gender";
    $origins = "Select your origins";
    $antecedent = "";
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        // Pour débogage
        // echo "Action: $action";

        if ($action == 'confirm' && isset($_SESSION["patient"])) {
            $bdd = new Query("siteweb");

            // Mettre à jour les informations du patient
            $patient->setFirst_name($_POST["Nom"]);
            $patient->setLast_name($_POST["prénom"]);
            $patient->setEmail($_POST["identifiant"]);
            $patient->setGender($_POST["genre"]);
            $patient->setOrigins($_POST["origin"]);
            $patient->setAntecedent($_POST["antecedent"]);
            $patient->setMdp($_POST["mdp"]);

            // Mettre à jour la base de données
            $patient->ChangeInfo($bdd);

            // Mettre à jour l'objet en session
            $_SESSION["patient"] = $patient;

            // Rediriger vers la page du patient
            header("Location: page_patient.php");
            exit;
        } elseif ($action == 'back' && isset($_SESSION["patient"])) {
            // Rediriger vers la page du patient sans mettre à jour
            header("Location: page_patient.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Page d'inscription</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_signin.css">
</head>

<body>
    <!-- division pour le fond de la page de connexion -->
    <img src="../Ressources/Images/background_login_signin.png" alt="fond" id="fond">

    <!-- division pour le fond du 1er plan -->
    <!-- division pour les éléments du 1er plan (logo et formulaire) -->
    <div id="element_1er_plan">

        <!-- division pour le formulaire de connexion -->
        <div id="interface_connexion">
            <?php if (isset($_SESSION["patient"])): ?>
                <h1 id="title">Edit my informations</h1>
            <?php else: ?>
                <h1 id="title">Signin</h1>
            <?php endif; ?>

            <!-- Division pour contenir les champs à remplir pour la connexion -->
            <form action="" method="post">
                <!-- Vérifie si l'utilisateur édite ses informations ou est en train de s'inscrire -->
                <?php if (!isset($_SESSION["patient"])): ?> 
                    <div class="input_info">
                        <label for="account_type">Account</label>
                        <!-- Permet d'avoir un formulaire dynamique avec onchange -->
                        <select id="account_type" name="account_type" class="deroulant" required onchange="this.form.submit()"> 
                        <select id="account_type" name="account_type" class="deroulant"  onchange="this.form.submit()"> 
                            <option value="">Select the account</option>
                            <option value="Patient" <?php echo (isset($_POST['account_type']) && $_POST['account_type'] == 'Patient') ? 'selected' : ''; ?>>Patient</option>
                            <option value="Doctor" <?php echo (isset($_POST['account_type']) && $_POST['account_type'] == 'Doctor') ? 'selected' : ''; ?>>Doctor</option>
                            <option value="Company" <?php echo (isset($_POST['account_type']) && $_POST['account_type'] == 'Company') ? 'selected' : ''; ?>>Company</option>
                        </select>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="account_type" value="Patient">
                <?php endif; ?>

                <?php
                // pour le médecin et le patient afficher infos sur le nom de famille, le prénom et le sexe

                if (isset($_SESSION["patient"]) || (isset($_POST["account_type"]) && ($_POST["account_type"] == "Patient" || $_POST["account_type"] == "Doctor"))) {
                    // Affiche le champ pour le nom de famille, le prénom et le sexe
                    echo '<div class="input_info">';
                        echo '<label for="Nom">Family name</label>';
                        echo '<input type="text" id="Nom" name="Nom" value="' . $last_name . '" required/>';
                        echo '<input type="text" id="Nom" name="Nom" value="' . $last_name . '"/>';
                    echo '</div>';

                    echo '<div class="input_info">';
                        echo '<label for="prénom">First name</label>';
                        echo '<input type="text" id="prénom" name="prénom" value="' . $first_name . '" required/>';
                        echo '<input type="text" id="prénom" name="prénom" value="' . $first_name . '" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                        echo '<label for="genre">Gender</label>';
                        echo '<select id="genre" name="genre" class="deroulant" required>';
                        echo '<select id="genre" name="genre" class="deroulant" >';
                            echo '<option value="">'.$gender.'</option>';
                            echo '<option value="male">Male</option>';
                            echo '<option value="female">Female</option>';
                        echo '</select>';
                    echo '</div>';

                }

                ?>

                <?php
                    // vérifie si le compte est un patient
                    if (isset($_SESSION["patient"]) || (isset($_POST["account_type"]) && $_POST["account_type"] == "Patient")) {
                        echo '<div class="input_info">';
                            echo '<label for="origin">Origins</label>';
                                echo '<select id="origin" name="origin" class="deroulant" required>';
                                echo '<select id="origin" name="origin" class="deroulant" >';
                                    echo '<option value="">'. $origins . '</option>';
                                    echo '<option value="Europe">Europe</option>';
                                    echo '<option value="North America">North America</option>';
                                    echo '<option value="South America">South America</option>';
                                    echo '<option value="Africa">Africa</option>';
                                    echo '<option value="Asia">Asia</option>';
                                    echo '<option value="Oceania">Oceania</option>';
                                echo '</select>';
                        echo '</div>';
    
                        // Affiche le champ pour les antécédents
                        echo '<div class="input_info">';
                            echo '<label for="medical">Medical History</label>';
                                echo '<select id="medical" name="medical" class="deroulant" required>';
                                echo '<select id="medical" name="medical" class="deroulant" >';
                                    echo '<option value="">'. $antecedent  .'</option>';
                                    echo '<option value="None">None</option>';
                                    echo '<option value="Hypertension">Hypertension</option>';
                                    echo '<option value="Type 2 diabetes">Type 2 diabetes</option>';
                                    echo '<option value="Type 1 diabetes">Type 1 diabetes</option>';
                                    echo '<option value="Asthma">Asthma</option>';
                                    echo '<option value="Cardiac history">Cardiac history</option>';
                                    echo '<option value="Food allergy">Food allergy</option>';
                                echo '</select>';
                        echo '</div>';
                }?>

                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["account_type"]) && $_POST["account_type"] == "Doctor") {
                    // info du numéro d'ordre du médecin, de son hopital d'exercice, de sa spécialité
                    echo '<div class="input_info">';
                    echo '<label for="num_ordre">Order number</label>';
                    echo '<input type="text" id="num_ordre" name="num_ordre" required/>';
                    echo '<input type="text" id="num_ordre" name="num_ordre" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="hopital">Hospital</label>';
                    echo '<input type="text" id="hopital" name="hopital" required/>';
                    echo '<input type="text" id="hopital" name="hopital" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="specialite">Speciality</label>';
                    echo '<input type="text" id="specialite" name="specialite" required/>';
                    echo '<input type="text" id="specialite" name="specialite" />';
                    echo '</div>';
                }

                ?>

                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["account_type"]) && $_POST["account_type"] == "Company") {
                    // info du nom de l'entreprise, le siret et la ville
                    echo '<div class="input_info">';
                    echo '<label for="nom_entreprise">Company name</label>';
                    echo '<input type="text" id="nom_entreprise" name="nom_entreprise" required/>';
                    echo '<input type="text" id="nom_entreprise" name="nom_entreprise" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="siret">Siret</label>';
                    echo '<input type="text" id="siret" name="siret" required/>';
                    echo '<input type="text" id="siret" name="siret" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="ville">City</label>';
                    echo '<input type="text" id="ville" name="ville" required/>';
                    echo '<input type="text" id="ville" name="ville" />';
                    echo '</div>';
                }

                ?>

                <div class="input_info">
                    <label for="identifiant">Email</label>
                    <input type="text" id="identifiant" name="identifiant" value= <?php echo $email?> required/>
                    <input type="text" id="identifiant" name="identifiant" value= <?php echo $email?> />

                </div>

                <div class="input_info">
                    <label for="mdp">Password</label>
                    <input type="password" id="mdp" name="mdp" />
                </div>

                <div class="input_info">
                    <label for="mdp2">Confirm password</label>
                    <input type="password" id="mdp2" name="mdp2" />
                </div>

                <div class="buttons">
                    <button type="submit" name="action" value="confirm">Confirm</button>
                    <button type="submit" name="action" value="back">Back</button>
                </div>
                <?php
                    include_once("../back_php/Securite.php");
                    include_once("../back_php/Affichage_gen.php");
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['action'])) {
                            $action = $_POST['action']; // pour savoir si on doit confirmer ou revenir en arrière

                            if ($action == 'confirm' && isset($_SESSION["patient"])) {
                                $required_fields_edit_patient = ["Nom", "prénom", "identifiant", "genre", "origin", "antecedent", "mdp", "mdp2"];
                                if (checkFormFields($required_fields_edit_patient)){
                                    $bdd = new Query("siteweb");
                                    // Mettre à jour les informations du patient
                                    $patient->setFirst_name($_POST["Nom"]);
                                    $patient->setLast_name($_POST["prénom"]);
                                    $patient->setEmail($_POST["identifiant"]);
                                    $patient->setGender($_POST["genre"]);
                                    $patient->setOrigins($_POST["origin"]);
                                    $patient->setAntecedent($_POST["antecedent"]);
                                    $patient->setMdp($_POST["mdp"]);
                                    // Mettre à jour la base de données
                                    $patient->ChangeInfo($bdd);
                                    // Mettre à jour l'objet en session
                                    $_SESSION["patient"] = $patient;
                                    // Rediriger vers la page du patient
                                    header("Location: page_patient.php");
                                    exit;
                                } else 
                                    AfficherErreur("Please fill all the fields");

                            } elseif ($action == 'back' && isset($_SESSION["patient"])) {
                                // Rediriger vers la page du patient sans mettre à jour
                                header("Location: page_patient.php");
                                exit;
                            }
                        }
                    }
                ?>
            </form>
        </div>
    </div>
</body>
</html>
