<?php
include_once("Patient.php");
/**
 * Affiche l'en tête du tableau pour les essais cliniques du patients
 */
function Affichage_entete_tableau_essai(){

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
                        echo "<th>Give results</th>";
                        echo '<th>Action</th>'; // Colonne pour se désinscrire
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

    }

/**
 * FOnction pour afficher les médecins référents d'un essai clinique
 */
function Affiche_medecin($medecins){
    $cpt = 0;
    foreach($medecins as $medecin){ // affiche les médecins référents
        if ($cpt == 0){
            echo $medecin["nom"];
            $cpt++;
        } else
            echo ', '.$medecin["nom"];
    }
}

function Affichage_content_essai($entreprise, $essai, $medecins, $id_essai){
        echo '<tr>';
            echo '<td>'.$entreprise["nom"].'</td>'; // affiche le contenu des colonnes simples
            echo '<td>Phase '.$essai["phase_res"].'</td>';
            echo '<td>'.$essai["description"].'</td>';
            echo '<td>'.$essai["date_debut"].'</td>';
            echo '<td>'.$essai["date_fin"].'</td>';
            echo '<td>';
            Affiche_medecin($medecins); // affiche les médecins référents
            echo '</td>';
            if ($entreprise["a_debute"] == 2){ // si la phase de l'essai est terminée et en attente des résultats
                // affiche un menu déroulant pour choisir les effets secondaires avec un bouton pour valider
                echo '<td>';
                    echo '<form action= "" method="post">';
                        echo '<input type="hidden" name="id_essai" value="'.$id_essai.'">';
                        echo '<select name="side_effects" id = "reponse_essai">'; // menu déroulant pour les effets secondaires
                            echo '<option value="None">None</option>';
                            echo '<option value="Fatigue">Fatigue</option>';
                            echo '<option value="Nausea">Nausea</option>';
                            echo '<option value="Dizziness">Dizziness</option>';
                            echo '<option value="Pain">Pain</option>';
                        echo '</select>';
                        echo '<button type="submit" class="button">Submit</button>'; // bouton pour valider
                    echo '</form>';
                echo '</td>';
            } else // si l'essai est en cours
                echo '<td>Not yet over</td>';
            
            echo '<td><a href="desinscrire.php?id_essai='.$id_essai.'" class="button">Unsubscribe</a></td>'; // Lien pour se désinscrire
        echo '</tr>';
}
    

?>