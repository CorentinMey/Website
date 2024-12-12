<?php
include_once("../back_php/Query.php");
include_once("../back_php/Utilisateur.php");
include_once("../back_php/Entreprise.php");
include_once("../back_php/Affichage_entreprise.php");

// Vérifie si l'entreprise est connectée
if (!isset($_SESSION["entreprise"])) {
    header("Location: page_login.php");
    exit;
}

$entreprise = $_SESSION["entreprise"];
$name = $entreprise->getLast_name();
$siret = $entreprise->getSiret(); // Récupère le SIRET
$bdd = new Query("siteweb");

?>

<!DOCTYPE html>

<head>
    <title>Page de l'entreprise</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_entreprise.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
</head>

<body>
    <div id="en-tete">
        <img src="../Ressources/Images/logo_medexplorer.png" alt="logo_med_explorer" id="logo_page">
        <div id="en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>

    <div id="banderolle">
    <!-- div pour le logo de l'historique et son bouton -->
    <div id = "logo_container_hist">
            <img id = "logo_historic" src = "../Ressources/Images/logo_historic.png" alt = "Historic button">
            <div id="dropdown_menu_hist">
                <form method="post" action="">
                    <!-- Bouton de déconnexion -->
                    <button class="dropdown_button" name="Action" value="Historic">Historic</button>
                </form>
            </div>
        </div>


    <!-- titre de la banderolle -->
    <h1 id="title"><?= "Welcome " . htmlspecialchars($name); ?></h1>

    <!-- div pour le logo de déconnexion et son bouton -->
    <!-- Conteneur du logo et menu déroulant pour l'account -->
    <div id="logo_container">
        <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
        <div id="dropdown_menu">
            <form method="post" action="">
                <!-- Bouton de déconnexion -->
                <button class="dropdown_button" name="Action" value="Disconnect">Disconnect</button>
                <!-- Bouton Home -->
                <button class="dropdown_button" name="Action" value="RevenirAccueil">Home</button>
            </form>
        </div>
    </div>
</div>



    <img src="../Ressources/Images/test_banderolle.webp" alt="banderolle" id="banderolle_img">

    <?php
    // Instancie la connexion à la base de données
    $query = new Query('siteweb');


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['Action'])) {
            switch ($_POST['Action']) {
                case 'Disconnect':
                    // Déconnecte l'utilisateur
                    session_destroy();
                    header("Location: page_login.php");
                    exit;
                case 'RevenirAccueil':
                    // Retourne à la page d'accueil
                    revenirPageParDefaut();
                    break;
                case 'Historic':
                    // Redirige vers la page de l'historique
                    header("Location: page_historique.php");
                    exit;
            }
        }
    }
    

    // Vérifie si une action a été demandée
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<div class="LALIST">';

        if (isset($_POST['Action']) && $_POST['Action'] === 'SeeEssai') {
            // Afficher les essais cliniques
            afficherListeEssais($bdd, $siret);
        } elseif (isset($_POST['Action']) && $_POST['Action'] === 'CreateStudy') {
            // Afficher le formulaire de création
            afficherFormulaireNouvellePhase();
        } elseif (isset($_POST['createPhase'])) {
            // Traiter le formulaire de création d'un essai clinique
            $data = [
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'] ?? null,
                'description' => $_POST['description'],
                'molecule_test' => $_POST['molecule_test'],
                'dosage_test' => $_POST['dosage_test'],
                'molecule_ref' => $_POST['molecule_ref'] ?? null,
                'dosage_ref' => $_POST['dosage_ref'] ?? null,
                'placebo_nom' => $_POST['placebo_nom'] ?? null,
            ];

            $entreprise->NewPhase($bdd, $data);
            echo "<p>Nouvel essai clinique créé avec succès !</p>";
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['VoirDétail'])) {
            $idEssai = $_POST['idEssai'];
            afficherDetailsEssai($bdd, $idEssai); // Affiche les détails de l'essai sélectionné
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DemanderMedecin'])) {
            $idEssai = $_POST['idEssai'];
            afficherFormulaireChoixMedecin($bdd, $idEssai);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['RequeteMedecin']))  {
            $idEssai = $_POST['idEssai'];
            $id_medecin = $_POST['numero_ordre'];
            $entreprise->DemandMedecin($id_medecin, $bdd, $idEssai);
        } elseif (isset($_POST['StartEssai'])) {
            $idEssai = $_POST['idEssai']; // ID de l'essai envoyé via un champ caché
            $idphase = $_POST['idphase']; // ID de la phase envoyé via un champ caché
            $entreprise->startPhase($bdd, $idEssai,$idphase);
        } elseif (isset($_POST['TerminerPhase'])) {
            $idEssai = $_POST['idEssai']; // ID de l'essai envoyé via un champ caché
            $idphase = $_POST['idphase']; // ID de la phase envoyé via un champ caché
            $entreprise->terminerPhase($bdd, $idEssai,$idphase);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ChangePhase'])) {
        
            // Récupérer la description et la molécule testée
            $result = recupererDescriptionEtMolecule($bdd, $_POST['idEssai']);
        
            if ($result) {
                // Afficher le formulaire de création de la nouvelle phase
                afficherFormulaireChangerPhase(
                    $_POST['idEssai'],
                    $result['description'],
                    $result['molecule_test'],
                    $_POST['idphase']
                );
            } else {
                echo "<p>Erreur : Essai introuvable.</p>";
            }
        } elseif (isset($_POST['createPhase2'])) {

            $data = [
                'ID_phase'=> $_POST['IDphase'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'] ?? null,
                'description' => $_POST['description'],
                'molecule_test' => $_POST['molecule_test'],
                'dosage_test' => $_POST['dosage_test'],
                'molecule_ref' => $_POST['molecule_ref'] ?? null,
                'dosage_ref' => $_POST['dosage_ref'] ?? null,
                'placebo_nom' => $_POST['placebo_nom'] ?? null,
                'nombre_patient_ideal' => $_POST['nombre_patient_ideal'] ?? null
            ];
        
            // Appeler la méthode pour ajouter une nouvelle phase
            $entreprise->NewPhase($bdd, $data,$_POST['IDphase']);
            echo "<p>Nouvelle phase créée avec succès ! </p>";
        } elseif (isset($_POST['AccepterMedecin'])) {

            $idMedecin = $_POST['idMedecin'];
            $idEssai = $_POST['idEssai'];
        
            // Appeler la méthode pour accepter un médecin
            try {
                $entreprise->acceptMedecin($bdd, $idMedecin, $idEssai);
                echo "<p>Médecin accepté avec succès !</p>";
            } catch (Exception $e) {
                echo "<p>Erreur lors de l'acceptation du médecin : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        
        
        echo '</div>';
    }   else {
    ?>

        <h2 class = "title" id ="titre_bouton_principal">Options</h2>

        <form action="" method="post" id="redirect_buttons">
            <button class="button" id="button_enterprise" name="Action" value="SeeEssai">My studies</button>
            <button class="button" id="button_enterprise" name="Action" value="CreateStudy">Create a new study</button>
        </form>

    <?php
    }
    ?>


</body>