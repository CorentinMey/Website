<?php
include_once("../back_php/Query.php");
include_once("../back_php/Affichage_medecin.php");
include_once("../back_php/Medecin.php");
include_once("../back_php/Patient.php");
session_start();

if (!isset($_SESSION["medecin"])) {
    header("Location: page_login.php");
    exit;
}


$medecin = $_SESSION["medecin"];
$bdd = new Query("siteweb");

if (isset($_SESSION['patient_infos'])) {
    $patient_infos = $_SESSION['patient_infos'];
} else {
    echo '<p>No patient data found. Please go back and try again.</p>';
}

?>

<!DOCTYPE html>

<head>

    <title>Page visualisation des patients par le médecin</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_signin.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_patient.css">


</head>


<body>
    <div id = "en-tete">
        <img src = "../Ressources/Images/logo_medexplorer.png" alt = "logo_med_explorer" id = logo_page>
        <div id = "en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>

    <div id="banderolle">
        
        <!-- div pour le log de l'historique et son bouton -->
        <div id = "logo_container_hist">
            <a href = "page_test.php">
                <img id = "logo_historic" src = "../Ressources/Images/logo_historic.png" alt = "Historic button">
                <div id = "tooltip_hist">Historic</div>
            </a>
        </div>

        <!-- titre de la banderolle -->
        <h1 id="title">My account</h1>

        <!-- div pour le logo de deconnexion et son bouton -->
        <div id="logo_container">
            <!-- mettre logo dans une balsie ref a pour rediriger -->
            <a href="page_deco.php">
                <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
                <div id="tooltip">Disconnect</div>
            </a>
        </div>
    </div>

    <img src = "../Ressources/Images/image_banderolle.webp" alt = "banderolle" id = "banderolle_img">

 
    <h2 class = "title">Modification form</h2>
    
    <!-- Division pour contenir les champs à remplir pour la modification -->
    <form action="page_patient_medecin.php" method="post"> <!-- Redirection vers la page cible -->

    <!-- Nom -->
    <div class="input_info">
        <label for="nom">First Name</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($patient_infos['nom']); ?>" />
    </div>

    <!-- Prénom -->
    <div class="input_info">
        <label for="prenom">Last Name</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($patient_infos['prenom']); ?>" />
    </div>

    <!-- Genre -->
    <div class="input_info">
        <label for="genre">Gender</label>
        <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($patient_infos['genre']); ?>" />
    </div>

    <!-- Date de naissance -->
    <div class="input_info">
        <label for="date_naissance">Birthdate</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($patient_infos['date_naissance']); ?>" />
    </div>

    <!-- Antécédents médicaux -->
    <div class="input_info">
        <label for="antecedents">Medical Background</label>
        <textarea id="antecedents" name="antecedents"><?php echo htmlspecialchars($patient_infos['antecedents']); ?></textarea>
    </div>

    <!-- Traitement -->
    <div class="input_info">
        <label for="traitement">Treatment</label>
        <input type="text" id="traitement" name="traitement" value="<?php echo htmlspecialchars($patient_infos['traitement']); ?>" />
    </div>

    <!-- Dose -->
    <div class="input_info">
        <label for="dose">Dose</label>
        <input type="text" id="dose" name="dose" value="<?php echo htmlspecialchars($patient_infos['dose']); ?>" />
    </div>

    <!-- Effet secondaire -->
    <div class="input_info">
        <label for="effet_secondaire">Side Effect</label>
        <input type="text" id="effet_secondaire" name="effet_secondaire" value="<?php echo htmlspecialchars($patient_infos['effet_secondaire']); ?>" />
    </div>

    <!-- Évolution des symptômes -->
    <div class="input_info">
        <label for="evolution_symptome">Symptoms Evolution</label>
        <textarea id="evolution_symptome" name="evolution_symptome"><?php echo htmlspecialchars($patient_infos['evolution_symptome']); ?></textarea>
    </div>

    <div class="input_info">
    <label>Ban this user from this trial?</label>
    <div>
        <input type="radio" id="ban_oui" name="ban_user" value="1">
        <label for="ban_oui">Yes</label>
    </div>
    <div>
        <input type="radio" id="ban_non" name="ban_user" value="0" checked>
        <label for="ban_non">No</label>
    </div>

    <!-- Boutons -->
    <div class="buttons">
        <button type="submit" name="action" value="confirm">Confirm</button>
        <button type="submit" name="action" value="back">Back</button>
    </div>
</form>



</body>