<?php
include_once("Patient.php");
// indique type de  $ patient

function AffichageTableau($patient){
    echo '<h2 class = "title">My clinical trials</h2>';
    echo '<div id = "personnal_data">';
        echo '<table class = "styled-table" id = "table_patient">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>First name</th>';
                    echo '<th>Family name</th>';
                    echo '<th>Gender</th>';
                    echo '<th>Origins</th>';
                    echo '<th>Email</th>';
                    echo '<th>Medical history</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                echo '<tr>';
                    echo '<td>'.$patient->getFirst_name().'</td>';
                    echo '<td>'.$patient->getLast_name().'</td>';
                    echo '<td>'.$patient->getGender()."</td>";
                    echo '<td>'.$patient->getOrigins().'</td>';
                    echo '<td>'.$patient->getEmail().'</td>';
                    echo '<td>'.$patient->getAntecedent().'</td>';
                echo '</tr>';
            echo '</tbody>';
        echo '</table>';
        echo '<a href="page_test.php" id = "edit_option">Edit</a>';
    echo '</div>';
}
?>