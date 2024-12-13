<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_entreprise.css">
</head>
<body>

</body>
<?php
include_once("Query.php");
include_once("Entreprise.php");
session_start();

$entreprise = $_SESSION["entreprise"];
$siret = $entreprise->getSiret();
$bdd = new Query("siteweb");

function afficherListeEssais($bdd, $siret) {
    echo '<div class="Tableau">';
    echo "<h2>Liste of my assay</h2>";

    // Requête pour récupérer les essais cliniques associés à un SIRET donné
    $query = "SELECT 
            essai.description, 
            utilisateur.nom AS nom_entreprise, 
            essai.date_debut, 
            essai.date_fin, 
            essai.molecule_test, 
            essai.dosage_test, 
            essai.a_debute, 
            essai.ID_phase,
            essai.ID_essai
        FROM essai 
        INNER JOIN entreprise ON entreprise.siret = essai.ID_entreprise_ref 
        INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret
        WHERE entreprise.siret = :siret";

    $res = $bdd->getResultsAll($query, [":siret" => $siret]);

    // Diviser les essais en trois groupes : démarrés, non démarrés, terminés
    $essaisDemarres = [];
    $essaisNonDemarres = [];
    $essaisTermines = [];
    $today = date('Y-m-d'); // Date actuelle

    foreach ($res as $essai) {
        if ($essai['a_debute'] == 1) {  // Essai démarré
            $essaisDemarres[] = $essai; // Ajouter à la liste des essais démarrés
        } elseif ($essai['a_debute'] == 0) {  // Essai non démarré
            $essaisNonDemarres[] = $essai; // Ajouter à la liste des essais non démarrés
        } elseif ($essai['a_debute'] == 2) {  // Essai terminé
            $essaisTermines[] = $essai; // Ajouter à la liste des essais terminés
        }
    }

    // Fonction pour récupérer les médecins référents
    function getMedecinsReferents($bdd, $idEssai) {
        $queryMedecins = "SELECT utilisateur.nom, utilisateur.prenom 
                          FROM essai_medecin 
                          INNER JOIN utilisateur ON utilisateur.ID_User = essai_medecin.ID_medecin
                          WHERE essai_medecin.ID_essai = :idEssai AND essai_medecin.is_accepte = 1";
        return $bdd->getResultsAll($queryMedecins, [":idEssai" => $idEssai]);
    }

    // Fonction pour afficher les essais
    function afficherEssais($titre, $essais, $bdd) {
        echo "<h3>{$titre}</h3>";
        if (!empty($essais)) {
            foreach ($essais as $essai) {
                $medecins = getMedecinsReferents($bdd, $essai['ID_essai']);
                echo '<div class="box_list">';
                echo '    <div class="assay-info">';
                echo '        <p><strong>Description :</strong> ' . htmlspecialchars($essai['description']) . '</p>';
                echo '        <p><strong>Phase :</strong> ' . $essai['ID_phase'] . '</p>';
                echo '        <p><strong>Nom de l\'entreprise :</strong> ' . htmlspecialchars($essai['nom_entreprise']) . '</p>';
                echo '        <p><strong>Date de début :</strong> ' . htmlspecialchars($essai['date_debut']) . '</p>';
                echo '        <p><strong>Date de fin :</strong> ' . htmlspecialchars($essai['date_fin']) . '</p>';
                echo '        <p><strong>Molécule testée :</strong> ' . htmlspecialchars($essai['molecule_test']) . '</p>';
                echo '        <p><strong>Dosage testé :</strong> ' . htmlspecialchars($essai['dosage_test']) . '</p>';
                echo '        <p><strong>Médecins référents :</strong> ';
                if (!empty($medecins)) {
                    foreach ($medecins as $medecin) {
                        echo htmlspecialchars($medecin['prenom']) . ' ' . htmlspecialchars($medecin['nom']) . ', ';
                    }
                    echo '</p>';
                } else {
                    echo 'Aucun médecin référent</p>';
                }
                echo '    </div>';

                // Formulaire pour "Voir les détails"
                echo '    <div class="assay-actions">';
                echo '        <form method="POST" action="">';
                echo '            <input type="hidden" name="idEssai" value="' . $essai['ID_essai'] . '">';
                echo '            <button type="submit" name="VoirDétail" class="buttonVoirdetail">Voir les détails</button>';
                echo '        </form>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo "<p>Aucun essai trouvé dans cette catégorie.</p>";
        }
    }

    // Affichage des essais
    afficherEssais('Essais démarrés', $essaisDemarres, $bdd);
    afficherEssais('Essais non démarrés', $essaisNonDemarres, $bdd);
    afficherEssais('Essais terminés', $essaisTermines, $bdd);

    // Traitement des détails si un formulaire est soumis

    echo '</div>';
}

function afficherFormulaireNouvellePhase() {
    echo '<div class="content-wrapper">';
    echo "<h2>Créer un nouvel essai clinique</h2>";
    echo '<form method="POST" action="" class="form-nouvelle-phase">';
    
    // Champs du formulaire
    
    echo '<label for="date_debut">Date de début prévue:</label>';
    echo '<input type="date" id="date_debut" name="date_debut" required><br>';
    
    echo '<label for="date_fin">Date de fin prévue:</label>';
    echo '<input type="date" id="date_fin" name="date_fin"><br>';
    
    echo '<label for="description">Description:</label>';
    echo '<textarea id="description" name="description" rows="4" required></textarea><br>';
    
    echo '<label for="molecule_test">Molécule testée:</label>';
    echo '<input type="text" id="molecule_test" name="molecule_test" required><br>';
    
    echo '<label for="dosage_test">Dosage testé:</label>';
    echo '<input type="text" id="dosage_test" name="dosage_test" required><br>';
    
    echo '<label for="molecule_ref">Molécule de référence:</label>';
    echo '<input type="text" id="molecule_ref" name="molecule_ref"><br>';
    
    echo '<label for="dosage_ref">Dosage de référence:</label>';
    echo '<input type="text" id="dosage_ref" name="dosage_ref"><br>';
    
    echo '<label for="placebo_nom">Nom du placebo:</label>';
    echo '<input type="text" id="placebo_nom" name="placebo_nom"><br>';

    echo '<label for="placebo_nom">Nombre patient ideal:</label>';
    echo '<input type="text" id="placebo_nom" name="nombre_patient_ideal"><br>';
    
    echo '<button type="submit" name="createPhase">Créer un essai</button>';
    echo '</form>';
    echo '</div>';
}

/**
 * Redirige vers la page par défaut de l'entreprise.
 */
function revenirPageParDefaut() {
    // Construit l'URL de base de la page actuelle sans les paramètres
    $url = strtok($_SERVER["REQUEST_URI"], '?');
    // Redirige vers l'URL
    header("Location: $url");
    exit; // Stoppe l'exécution du script pour éviter des comportements inattendus
}

function afficherDetailsEssai($bdd, $idEssai) {
    // Requête pour récupérer les détails de l'essai spécifique
    $query = "SELECT 
                essai.ID_essai,
                essai.description, 
                essai.date_debut, 
                essai.date_fin, 
                essai.molecule_test, 
                essai.dosage_test, 
                essai.molecule_ref, 
                essai.dosage_ref, 
                essai.placebo_nom, 
                essai.a_debute, 
                essai.ID_phase,
                utilisateur.nom AS nom_entreprise
              FROM essai
              INNER JOIN entreprise ON entreprise.siret = essai.ID_entreprise_ref
              INNER JOIN utilisateur ON utilisateur.ID_User = entreprise.siret
              WHERE essai.ID_essai = :idEssai";

    $essai = $bdd->getResults($query, [":idEssai" => $idEssai]);

    if ($essai) {
        // Affichage des détails
        echo '<div class="details">';
        echo '<h2>Détails de l\'essai</h2>';

        // Autres détails de l'essai
        echo '<p><strong>Description :</strong> ' . htmlspecialchars($essai['description']) . '</p>';
        echo '<p><strong>Phase actuelle :</strong> ' . htmlspecialchars($essai['ID_phase']) . '</p>';
        echo '<p><strong>Nom de l\'entreprise :</strong> ' . htmlspecialchars($essai['nom_entreprise']) . '</p>';
        echo '<p><strong>Date de début :</strong> ' . htmlspecialchars($essai['date_debut']) . '</p>';
        echo '<p><strong>Date de fin :</strong> ' . htmlspecialchars($essai['date_fin']) . '</p>';
        echo '<p><strong>Molécule testée :</strong> ' . htmlspecialchars($essai['molecule_test']) . '</p>';
        echo '<p><strong>Dosage testé :</strong> ' . htmlspecialchars($essai['dosage_test']) . '</p>';
        echo '<p><strong>Molécule de référence :</strong> ' . htmlspecialchars($essai['molecule_ref']) . '</p>';
        echo '<p><strong>Dosage de référence :</strong> ' . htmlspecialchars($essai['dosage_ref']) . '</p>';
        echo '<p><strong>Nom du placebo :</strong> ' .$essai['placebo_nom'] . '</p>';
        echo '<p><strong>Statut :</strong> ' . ($essai['a_debute'] ? 'En cours' : 'Non démarré') . '</p>';

        // Récupération et affichage du nombre de patients actuels pour la phase
        $queryPhase = "SELECT nombre_patients 
                       FROM phase 
                       WHERE ID_essai = :idEssai AND ID_phase = :idPhase";
        $phase = $bdd->getResults($queryPhase, [
            ":idEssai" => $essai['ID_essai'],
            ":idPhase" => $essai['ID_phase']
        ]);

        echo '<p><strong>Nombre de patients actuels :</strong> ';
        echo $phase ? htmlspecialchars($phase['nombre_patients']) : 'Non renseigné';
        echo '</p>';

        // Récupération et affichage des médecins référents
        $queryMedecins = "SELECT utilisateur.nom, utilisateur.prenom 
                          FROM essai_medecin 
                          INNER JOIN utilisateur ON utilisateur.ID_User = essai_medecin.ID_medecin
                          WHERE essai_medecin.ID_essai = :idEssai AND essai_medecin.is_accepte = 1";

        $medecins = $bdd->getResultsAll($queryMedecins, [":idEssai" => $idEssai]);

        echo '<p><strong>Médecins référents :</strong> ';
        if (!empty($medecins)) {
            foreach ($medecins as $medecin) {
                echo htmlspecialchars($medecin['prenom']) . ' ' . htmlspecialchars($medecin['nom']) . ', ';
            }
            echo '</p>';
        } else {
            echo 'Aucun médecin référent</p>';
        }

        // Récupération et affichage des médecins en attente
        $queryMedecinsAttente = "SELECT utilisateur.ID_User, utilisateur.nom, utilisateur.prenom 
                                 FROM essai_medecin 
                                 INNER JOIN utilisateur ON utilisateur.ID_User = essai_medecin.ID_medecin
                                 WHERE essai_medecin.ID_essai = :idEssai AND essai_medecin.is_accepte = 0 AND essai_medecin.est_de_company = 0";

        $medecinsAttente = $bdd->getResultsAll($queryMedecinsAttente, [":idEssai" => $idEssai]);

        echo '<p><strong>Médecins en attente :</strong> ';
        if (!empty($medecinsAttente)) {
            echo '<ul>';
            foreach ($medecinsAttente as $medecin) {
                echo '<li>' . htmlspecialchars($medecin['prenom']) . ' ' . htmlspecialchars($medecin['nom']);
                echo ' <form method="POST" action="">';
                echo '     <input type="hidden" name="idMedecin" value="' . htmlspecialchars($medecin['ID_User']) . '">';
                echo '     <input type="hidden" name="idEssai" value="' . htmlspecialchars($idEssai) . '">';
                echo '     <button type="submit" name="AccepterMedecin" class="buttonAccepter">Accepter</button>';
                echo ' </form>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo 'Aucun médecin en attente</p>';
        }

        // Conteneur commun pour les boutons
        echo '<div class="button-container">';

        // Bouton pour revenir à la liste des essais
        echo '<div class="assay-actions">';
        echo '    <form method="POST" action="">';
        echo '        <button type="submit" name="Action" value="SeeEssai" class="buttonVoirdetail">Retour à la liste des essais</button>';
        echo '    </form>';
        echo '</div>';

        // Vérification des conditions avant d'afficher les boutons conditionnels
        if ($essai['a_debute']==0) {
            if (!empty($medecins)) {
                if ($phase['nombre_patients'] && $phase['nombre_patients'] > 0) {
                    // Bouton pour démarrer l'essai
                    echo '<div class="assay-actions">';
                    echo '    <form method="POST" action="">';
                    echo '        <input type="hidden" name="idEssai" value="' . $essai['ID_essai'] . '">';
                    echo '        <input type="hidden" name="idphase" value="' . $essai['ID_essai'] . '">';
                    echo '        <button type="submit" name="StartEssai" class="buttonVoirdetail">Démarrer la phase</button>';
                    echo '    </form>';
                    echo '</div>';
                } else {
                    // Message d'avertissement si le nombre de patients est nul
                    echo '<div class="assay-actions">';
                    echo '<p style="color: red;">Impossible de démarrer la phase : aucun patient enregistré.</p>';
                    echo '</div>';
                }
            }
        }
                // Bouton pour demander un médecin
                echo '<div class="assay-actions">';
                echo '    <form method="POST" action="">';
                echo '        <input type="hidden" name="idEssai" value="' . htmlspecialchars($essai['ID_essai']) . '">';
                echo '        <button type="submit" name="DemanderMedecin" class="buttonVoirdetail">Demander un médecin</button>';
                echo '    </form>';
                echo '</div>';
            

        // Bouton pour changer de phase si l'essai est terminé (a_debute = 2) et la phase est < 4
        if ($essai['a_debute'] == 2 && $essai['ID_phase'] < 4 ) {
            echo '<div class="assay-actions">';
            echo '    <form method="POST" action="">';
            echo '        <input type="hidden" name="idEssai" value="' . htmlspecialchars($essai['ID_essai']) . '">';
            echo '        <input type="hidden" name="idphase" value="' . htmlspecialchars($essai['ID_phase']) . '">';
            echo '        <button type="submit" name="ChangePhase" class="buttonVoirdetail">Changer de phase</button>';
            echo '    </form>';
            echo '</div>';
        }
        //Bouton pour terminer une phase
        if ($essai['a_debute'] == 1){
            echo '<div class="assay-actions">';
            echo '    <form method="POST" action="">';
            echo '        <input type="hidden" name="idEssai" value="' . htmlspecialchars($essai['ID_essai']) . '">';
            echo'         <input type="hidden" name="idphase" value="' . htmlspecialchars($essai['ID_phase']) . '">';
            echo '       <button type="submit" name="TerminerPhase" class="buttonVoirdetail">Terminer phase</button>';
            echo '    </form>';
            echo '</div>';
        }
        echo '</div>'; // Fin du conteneur
    } else {
        echo '<p>Essai introuvable.</p>';
    }
}

function afficherFormulaireChoixMedecin($bdd, $idEssai) {
    // Requête pour récupérer les médecins avec leurs spécialités
    $query = "SELECT 
                MEDECIN.numero_ordre, 
                MEDECIN.domaine, 
                MEDECIN.hopital, 
                UTILISATEUR.prenom, 
                UTILISATEUR.nom 
              FROM MEDECIN 
              INNER JOIN UTILISATEUR ON MEDECIN.numero_ordre = UTILISATEUR.ID_User
              WHERE UTILISATEUR.is_bannis = 0"; // Exclure les médecins bannis

    // Appel de la méthode getResultsAll avec un tableau vide comme deuxième argument
    $medecins = $bdd->getResultsAll($query, []);

    // Vérifie s'il y a des médecins à afficher
    if (!empty($medecins)) {
        echo '<div class="form-container">';
        echo '<h2>Choisir un médecin</h2>';
        echo '<form method="POST" action="" class="form-nouvelle-phase">';

        // Champ caché pour transmettre l'ID de l'essai
        echo '<input type="hidden" name="idEssai" value="' . htmlspecialchars($idEssai) . '">';

        // Liste déroulante pour choisir un médecin
        echo '<label for="medecin">Médecin :</label>';
        echo '<select id="medecin" name="numero_ordre" required>';

        // Ajoute une option pour chaque médecin
        foreach ($medecins as $medecin) {
            $fullName = htmlspecialchars($medecin['prenom'] . ' ' . $medecin['nom']);
            $domaine = htmlspecialchars($medecin['domaine']);
            $hopital = htmlspecialchars($medecin['hopital']);
            echo '<option value="' . htmlspecialchars($medecin['numero_ordre']) . '">';
            echo $fullName . ' - ' . $domaine . ' (' . $hopital . ')';
            echo '</option>';
        }

        echo '</select>';

        // Bouton de soumission
        echo '<div class="assay-actions">';
        echo '<button type="submit" name="RequeteMedecin" class="RequeteMedecin">Assigner le médecin</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';
    } else {
        // Message si aucun médecin n'est disponible
        echo '<p>Aucun médecin disponible.</p>';
    }
}

function recupererDescriptionEtMolecule($bdd, $idEssai) {
    $query = "SELECT description, molecule_test 
              FROM essai 
              WHERE ID_essai = :idEssai";
    return $bdd->getResults($query, [":idEssai" => $idEssai]);
}



function afficherFormulaireChangerPhase($idEssai, $description, $moleculeTest, $idphase) {
    echo '<div class="content-wrapper">';
    echo "<h2>Créer une nouvelle phase</h2>";
    echo '<form method="POST" action="" class="form-nouvelle-phase">';
    
    // Affichage des champs non modifiables
    echo '<p><strong>Description actuelle :</strong> ' . htmlspecialchars($description) . '</p>';
    echo '<p><strong>Molécule testée :</strong> ' . htmlspecialchars($moleculeTest) . '</p>';
    
    // Champ caché pour l'ID de l'essai
    echo '<input type="hidden" name="id_essai" value="' . htmlspecialchars($idEssai) . '">';
    echo '<input type="hidden" name="description" value="' . htmlspecialchars($description) . '">';
    echo '<input type="hidden" name="molecule_test" value="' . htmlspecialchars($moleculeTest) . '">';
    echo '<input type="hidden" name="IDphase" value="' . $idphase + 1 . '">';
    
    // Champs à remplir pour la nouvelle phase
    echo '<label for="date_debut">Date de début prévue:</label>';
    echo '<input type="date" id="date_debut" name="date_debut" required><br>';
    
    echo '<label for="date_fin">Date de fin prévue:</label>';
    echo '<input type="date" id="date_fin" name="date_fin"><br>';
    
    echo '<label for="dosage_test">Dosage testé:</label>';
    echo '<input type="text" id="dosage_test" name="dosage_test" required><br>';
    
    echo '<label for="molecule_ref">Molécule de référence:</label>';
    echo '<input type="text" id="molecule_ref" name="molecule_ref"><br>';
    
    echo '<label for="dosage_ref">Dosage de référence:</label>';
    echo '<input type="text" id="dosage_ref" name="dosage_ref"><br>';
    
    echo '<label for="placebo_nom">Nom du placebo:</label>';
    echo '<input type="text" id="placebo_nom" name="placebo_nom"><br>';

    echo '<label for="nombre_patient_ideal">Nombre de patients idéal:</label>';
    echo '<input type="text" id="nombre_patient_ideal" name="nombre_patient_ideal"><br>';
    
    // Bouton pour soumettre le formulaire
    echo '<button type="submit" name="createPhase2">Créer la nouvelle phase</button>';
    echo '</form>';
    echo '</div>';
}

?>
</body>
</html>