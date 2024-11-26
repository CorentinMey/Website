<?php
ob_start(); // Active le tampon de sortie
include_once("../back_php/Patient.php");
include_once("../back_php/Query.php");
session_start();
if (isset($_SESSION["patient"])) { // vérifie si le patient souhaite changer ses infos depuis la page d'inscription
    $patient = $_SESSION["patient"];
    $first_name = $patient->getFirst_name();
    $last_name = $patient->getLast_name();
    $email = $patient->getEmail();
    $gender = $patient->getGender();
    $origins = $patient->getOrigins();
    $antecedent = $patient->getAntecedent();
    $date_naissance = $patient->getBirthdate();
    $bdd = new Query("siteweb");
    // Récupérez les autres informations nécessaires
} else { // si l'utilisateur souhaite seulement s'inscrire
    $first_name = "";
    $last_name = "";
    $email = "";
    $gender = "Select your gender";
    $origins = "Select your origins";
    $antecedent = "";
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
                        echo '<input type="text" id="Nom" name="Nom" value="' . $last_name . '"/>';
                    echo '</div>';

                    echo '<div class="input_info">';
                        echo '<label for="prénom">First name</label>';
                        echo '<input type="text" id="prénom" name="prénom" value="' . $first_name . '" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                        echo '<label for="genre">Gender</label>';
                        echo '<select id="genre" name="genre" class="deroulant" >';
                            echo '<option value='.htmlspecialchars($gender).'>'.$gender.'</option>';
                            echo '<option value="male">Male</option>';
                            echo '<option value="female">Female</option>';
                        echo '</select>';
                    echo '</div>';

                }

                ?>

                <?php
                    // vérifie si le compte est un patient
                    if (isset($_SESSION["patient"]) || (isset($_POST["account_type"]) && $_POST["account_type"] == "Patient")) {
                        // Affiche le champ pour les origines
                        echo '<div class="input_info">';
                        echo '<label for="origin">Origins</label>';
                        echo '<select id="origin" name="origin" class="deroulant">';
                            echo '<option value="">Select your origins</option>';
                            echo '<option value="Europe"' . (($origins == "Europe") ? ' selected' : '') . '>Europe</option>';
                            echo '<option value="North America"' . (($origins == "North America") ? ' selected' : '') . '>North America</option>';
                            echo '<option value="South America"' . (($origins == "South America") ? ' selected' : '') . '>South America</option>';
                            echo '<option value="Africa"' . (($origins == "Africa") ? ' selected' : '') . '>Africa</option>';
                            echo '<option value="Asia"' . (($origins == "Asia") ? ' selected' : '') . '>Asia</option>';
                            echo '<option value="Oceania"' . (($origins == "Oceania") ? ' selected' : '') . '>Oceania</option>';
                        echo '</select>';
                        echo '</div>';
                    
                        // Affiche le champ pour les antécédents médicaux
                        echo '<div class="input_info">';
                        echo '<label for="medical">Medical History</label>';
                        echo '<select id="medical" name="medical" class="deroulant">';
                            echo '<option value="">Select your medical history</option>';
                            echo '<option value="None"' . (($antecedent == "None") ? ' selected' : '') . '>None</option>';
                            echo '<option value="Hypertension"' . (($antecedent == "Hypertension") ? ' selected' : '') . '>Hypertension</option>';
                            echo '<option value="Type 2 diabetes"' . (($antecedent == "Type 2 diabetes") ? ' selected' : '') . '>Type 2 diabetes</option>';
                            echo '<option value="Type 1 diabetes"' . (($antecedent == "Type 1 diabetes") ? ' selected' : '') . '>Type 1 diabetes</option>';
                            echo '<option value="Asthma"' . (($antecedent == "Asthma") ? ' selected' : '') . '>Asthma</option>';
                            echo '<option value="Cardiac history"' . (($antecedent == "Cardiac history") ? ' selected' : '') . '>Cardiac history</option>';
                            echo '<option value="Food allergy"' . (($antecedent == "Food allergy") ? ' selected' : '') . '>Food allergy</option>';
                        echo '</select>';
                        echo '</div>';
                    }
                ?>
                <?php

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["account_type"]) && $_POST["account_type"] == "Doctor") {
                    // info du numéro d'ordre du médecin, de son hopital d'exercice, de sa spécialité
                    echo '<div class="input_info">';
                    echo '<label for="num_ordre">Order number</label>';
                    echo '<input type="text" id="num_ordre" name="num_ordre" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="hopital">Hospital</label>';
                    echo '<input type="text" id="hopital" name="hopital" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="specialite">Speciality</label>';
                    echo '<input type="text" id="specialite" name="specialite" />';
                    echo '</div>';
                }

                ?>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["account_type"]) && $_POST["account_type"] == "Company") {
                    // info du nom de l'entreprise, le siret et la ville
                    echo '<div class="input_info">';
                    echo '<label for="nom_entreprise">Company name</label>';
                    echo '<input type="text" id="nom_entreprise" name="nom_entreprise" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="siret">Siret</label>';
                    echo '<input type="text" id="siret" name="siret" />';
                    echo '</div>';

                    echo '<div class="input_info">';
                    echo '<label for="ville">City</label>';
                    echo '<input type="text" id="ville" name="ville" />';
                    echo '</div>';
                }
                ?>

                <div class="input_info">
                    <label for="identifiant">Email</label>
                    <input type="text" id="identifiant" name="identifiant" value= "<?php echo $email?>"/>

                </div>

                <div class = "input_info">
                    <label for="date_naissance">Birthdate</label>
                    <input type="date" id="date_naissance" name="date_naissance" value = "<?php echo $date_naissance?>"/>
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
                // Code php pour vérifier les entrées de l'utilisateur

                    include_once("../back_php/Securite.php");
                    include_once("../back_php/Affichage_gen.php");
                    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si un bouton a été cliqué
                        if (isset($_POST['action'])) {
                            $action = $_POST['action']; // Détermine l'action à effectuer

                        if ($action == 'confirm' && isset($_SESSION["patient"])) {
                            // Mise à jour des informations du patient existant
                            $patient->updatePatientInfo();
                        } elseif ($action == 'back' && isset($_SESSION["patient"])) {
                            // Retour à la page patient sans mise à jour
                            header("Location: page_patient.php");
                            exit;
                        } elseif ($action == "back" && !isset($_SESSION["patient"])) {
                            // Retour à la page d'accueil
                            header("Location: page_accueil.php");
                            exit;
                        } else {
                            // Inscription d'un nouvel utilisateur en fonction du type de compte
                            if (isset($_POST["account_type"])) {
                                switch ($_POST["account_type"]) {
                                    case "Patient":
                                        registerNewPatient();
                                        break;
                                    case "Doctor":
                                        registerNewDoctor();
                                        break;
                                    case "Company":
                                        registerNewCompany();
                                        break;
                                    default:
                                        AfficherErreur("Invalid account type selected.");
                                }
                            } else
                                AfficherErreur("Please select an account type.");
                            }
                        }
                    }
                ?>
            </form>
        </div>
    </div>
</body>
</html>
<?php
ob_end_flush(); // Envoie la sortie tamponnée
?>

