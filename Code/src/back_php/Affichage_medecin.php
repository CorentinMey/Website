<?php
include_once("Medecin.php");
include_once("Affichage_gen.php"); // pour la fonction Affiche_medecin
include_once("graph.php");

/**
 * Affiche l'en tête du tableau pour les essais cliniques du médecin
 */
function Affichage_entete_tableau_essai_med(){

    echo '<h2 class="title">My Clinical Trials</h2>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Company</th>';
                        echo '<th>Phase</th>';
                        echo '<th>Description</th>';
                        echo '<th>Start Date</th>';
                        echo '<th>End Date</th>';
                        echo '<th>Referent Doctors</th>';
                        echo "<th>Status</th>";
                        echo '<th>Action</th>'; // Colonne pour voir l'ensemble du tableau
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }


/**
 * Affiche l'en tête du tableau 2 pour les essais cliniques du medecins
 */
function Affichage_entete_tableau_essai_med2($demande, $acces){

    echo '<h2 class="title">General informations about the trial</h2>';
        echo '<div id="full_info">';
            echo '<table class="styled-table" id="table_full">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Company</th>';
                        echo '<th>Phase</th>';
                        echo '<th>Description</th>';
                        echo '<th>Start Date</th>';
                        echo '<th>End Date</th>';
                        echo '<th>Test Molecule</th>'; 
                        echo '<th>Test Dosage</th>';
                        echo '<th>Reference Molecule</th>';
                        echo '<th>Reference Dosage</th>';
                        echo '<th>Placebo name</th>';
                        echo '<th>Referent Doctors</th>';
                        if ($demande == 1 && $acces!=1){
                            echo "<th>Accept to supervise the trial ?</th>";
                        }
                        else{
                            echo "<th>Status</th>";
                        }
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }

function Affichage_content_essai_med($entreprise, $essai, $medecins, $id_essai){
        echo '<tr>';
            echo '<td>'.$entreprise["nom"].'</td>'; // affiche le contenu des colonnes simples
            echo '<td>Phase '.$essai["ID_phase"].'</td>';
            echo '<td>'.$essai["description"].'</td>';
            echo '<td>'.$essai["date_debut"].'</td>';
            echo '<td>'.$essai["date_fin"].'</td>';
            echo '<td>';
            Affiche_medecin($medecins); // affiche les médecins référents
            echo '</td>';
            if ($essai["is_accepte"]!=0){ // si le docteur est accepté en tant que référent de l'essai
                echo '<td>In charge of the trial</td>';
            } else { // si le docteur n'est pas encore accepté ou si c'est une demande de l'entreprise
                if ($essai["est_de_company"]==1){
                    echo '<td>The company is asking you to supervise the trial</td>';
                }
                else{
                    echo '<td>Not yet accepted</td>';
                }
            } 
                
            // ajout du bouton pour voir l'ensemble des informations de l'essai clinique
            echo '<td>';
            echo '<form action="page_essai_medecin.php" method="post">'; // Redirection vers page_essai_medecin.php
            echo '<input type="hidden" name="id_essai" value="' . $id_essai . '">';
            echo '<input type="hidden" name="is_accepte" value="' . $essai["is_accepte"] . '">';
            echo '<input type="hidden" name="Action" value="Information">'; // Ajout du champ Action
            echo '<button type="submit" class="button">View more</button>';
            echo '</form>';
            echo '</td>';
        echo '</tr>';
}

function Affichage_content_essai_med2($entreprise, $essai, $medecins, $id_essai, $ID_User){
    echo '<tr>';
        echo '<td>'.$entreprise["nom"].'</td>'; // affiche le contenu des colonnes simples
        echo '<td>Phase '.$essai["ID_phase"].'</td>';
        echo '<td>'.$essai["description"].'</td>';
        echo '<td>'.$essai["date_debut"].'</td>';
        echo '<td>'.$essai["date_fin"].'</td>';
        echo '<td>'.$essai["molecule_test"].'</td>';
        echo '<td>'.$essai["dosage_test"].'</td>';
        echo '<td>'.$essai["molecule_ref"].'</td>';
        echo '<td>'.$essai["dosage_ref"].'</td>';
        echo '<td>'.$essai["placebo_nom"].'</td>';
        echo '<td>';
        Affiche_medecin($medecins); // affiche les médecins référents
        echo '</td>';
        if ($essai["is_accepte"]!=0){ // si le docteur est accepté en tant que référent de l'essai
            echo '<td>In charge of the trial</td>';
        } else { // si le médecin n'est pas encore référent de l'essai
            if($essai["est_de_company"]==1){ //Si la demande vient du entreprise, possibilité d'accepter la demande et de rejoindre l'essai
                echo '<td>';
                echo '<form action="page_essai_medecin.php" method="post">'; // Redirection vers la même page
                echo '<input type="hidden" name="id_user" value="' . $ID_User . '">';
                echo '<input type="hidden" name="id_essai" value="' . $id_essai . '">';
                echo '<input type="hidden" name="Action" value="accept">'; // Champ Action pour préciser l'action
                echo '<button type="submit" name="accepter" value="1" class="button">Oui</button>'; // Bouton Oui
                echo '<button type="submit" name="accepter" value="0" class="button">Non</button>'; // Bouton Non
                echo '</form>';
                echo '</td>';
            }
            else{  // Si la demande vient du médecin, attente de la réponse entreprise
                echo '<td>Not yet accepted</td>';
            }
        }            
    echo '</tr>';
}
    

/**
 * Affiche l'en tête du tableau pour les participants
 */
function Affichage_entete_tableau_participants(){

    echo '<h2 class="title">General informations about the participants</h2>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Name</th>';
                        echo '<th>Surname</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Birthdate</th>';
                        echo '<th>Medical Background</th>';
                        echo '<th>Modify infos/View treatment</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }

/**
 * Affiche l'en tête du tableau pour les patients en attente
 */
function Affichage_entete_tableau_patients($acces){

    echo '<h2 class="title">General informations about the patients who want to join the trial</h2>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Name</th>';
                        echo '<th>Surname</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Birthdate</th>';
                        echo '<th>Medical Background</th>';
                        if ($acces==1){
                            echo '<th>Modify infos/View treatment</th>';
                        }
                        else{
                        echo '<th>Accept/Reject the patient</th>';
                        }
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }


function Affichage_content_participants($participants, $id_essai){
    $ID_User = $participants["ID_User"];
    echo '<tr>';
        echo '<td>'.$participants["prenom"].'</td>'; // affiche le contenu des colonnes simples
        echo '<td>'.$participants["nom"].'</td>';
        echo '<td>'.$participants["genre"].'</td>';
        echo '<td>'.$participants["date_naissance"].'</td>';
        echo '<td>'.$participants["antecedents"].'</td>';
        echo '</td>';
        if ($participants["is_accepte"]!=0){ // si le patient est accepté dans l'essai
            echo '<td>';
            echo '<form action="page_patient_medecin.php" method="post">'; // Redirection vers page_essai_medecin.php
            echo '<input type="hidden" name="id_user" value="' . $ID_User . '">';
            echo '<input type="hidden" name="id_essai" value="' . $id_essai . '">';
            echo '<input type="hidden" name="Action" value="Information">'; // Ajout du champ Action
            echo '<button type="submit" class="button">View more</button>';
            echo '</form>';
            echo '</td>';
        } else { // si le patient n'est pas admis
            echo '<td>';
            echo '<form action="page_essai_medecin.php" method="post">'; // Redirection vers la même page
            echo '<input type="hidden" name="id_user" value="' . $ID_User . '">';
            echo '<input type="hidden" name="id_essai" value="' . $id_essai . '">';
            echo '<input type="hidden" name="Action" value="accept">'; // Champ Action pour préciser l'action
            echo '<button type="submit" name="decision" value="1" class="button">Oui</button>'; // Bouton Oui
            echo '<button type="submit" name="decision" value="0" class="button">Non</button>'; // Bouton Non
            echo '</form>';
            echo '</td>';
        } 
           
    echo '</tr>';
}


/**
 * Affiche l'en tête du tableau pour les résultats
 */
function Affichage_entete_tableau_resultats(){

    echo '<h2 class="title">General results of the trial for each individual</h2>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th>Individual number</th>';
                        echo '<th>Results phase</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Birthdate</th>';
                        echo '<th>Medical Background</th>';
                        echo '<th>Treatment</th>';
                        echo '<th>Dose</th>';
                        echo '<th>Side effects</th>';
                        echo '<th>Symptoms evolution</th>';
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }

// Affiche le contenu des résultats
function Affichage_content_resultats($results, $id_essai, $count){
    echo '<tr>';
        echo '<td>'.$count.'</td>';
        echo '<td>'.$results["phase_res"].'</td>';
        echo '<td>'.$results["genre"].'</td>'; // affiche le contenu des colonnes simples
        echo '<td>'.$results["date_naissance"].'</td>';
        echo '<td>'.$results["antecedents"].'</td>';
        echo '<td>'.$results["traitement"].'</td>';
        echo '<td>'.$results["dose"].'</td>';
        echo '<td>'.$results["effet_secondaire"].'</td>';
        echo '<td>'.$results["evolution_symptome"].'</td>';
        echo '</td>';    
    echo '</tr>';
}

/**
 * Fonction pour afficher les différents graphique srécapitulant les informations des essais cliniques
 * @param Query $bdd : base de données
 * @param $id_essai : id de l'essai clinique
 * @param $nb_phase : numéro de phases de l'essai clinique
 */
function afficherGraphiques($bdd, $id_essai, $nb_phase, $barplot_traitement_title = "", $histogram_title = "", $boxplot_sideeffect_title = "", $boxplot_traitement_title = "") {

    echo '<div class="graphique-container">';

        // Histogramme de la distribution des âges
        echo '<h2 class="title">Histogram of the age distribution</h2>';
        $datahisto = getDataHistogram($bdd, $id_essai, $nb_phase);
        Histogramme($datahisto, title : $histogram_title);

        // BoxPlot de la distribution des âges selon les traitements
        echo "<h2 class='title'>BoxPlot of the age distribution according to treatments</h2>";
        $boxplotData = getDataBoxPlotTraitement($bdd, $id_essai, $nb_phase);
        $categories = array_keys($boxplotData);
        // Ajouter des catégories fantômes pour les données manquantes
        array_push($categories, " ");
        array_unshift($categories, " ");
        $boxplot_dict = TransformDataBoxPlot($boxplotData);
        // Générer le boxplot
        boxplot($boxplot_dict, $categories, title : $boxplot_traitement_title);

        // BoxPlot de la distribution des âges selon les effets secondaires
        echo "<h2 class='title'>BoxPlot of the age distribution according to side effects</h2>";
        $boxplotData = getDataBoxPlotSideEffect($bdd, $id_essai, $nb_phase);
        $categories = array_keys($boxplotData);
        array_push($categories, " ");
        array_unshift($categories, " ");
        $boxplot_dict = TransformDataBoxPlot($boxplotData);
        boxplot($boxplot_dict, $categories, title : $boxplot_sideeffect_title, color :"red");

        // Barplot de l'évolution de la santé selon le traitement
        echo "<h2 class='title'>Barplot of the health evolution according to the treatment</h2>";
        $barplotData = getDataBarplot($bdd, $id_essai, $nb_phase);
        $categories = $barplotData['categories'];
        $data = $barplotData['data'];
        barplot($data, $categories, $barplot_traitement_title, "Health evolution", "Number of patients");

    echo '</div>';
}

?>