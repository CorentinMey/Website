<?php
include_once("Medecin.php");
include_once("Affichage_gen.php"); // pour la fonction Affiche_medecin
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
function Affichage_entete_tableau_essai_med2(){

    echo '<h2 class="title">General informations about the trial</h2>';
        echo '<div id="essai_clinique">';
            echo '<table class="styled-table" id="table_essai">';
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
                        echo "<th>Status</th>";
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

function Affichage_content_essai_med2($entreprise, $essai, $medecins, $id_essai){
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
        } else // si l'essai est en cours
            echo '<td>Not yet accepted</td>';
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

?>