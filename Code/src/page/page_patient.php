<?php
session_start();
$patient = $_SESSION["user"];
?>

<!DOCTYPE html>

<head>

    <title>Page du patient</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/page_patient.css">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">


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
            <a href="page_test.php">
                <img id="logo_account" src="../Ressources/Images/account.png" alt="Account Logo">
                <div id="tooltip">Disconnect</div>
            </a>
        </div>
    </div>

    <img src = "../Ressources/Images/image_banderolle.webp" alt = "banderolle" id = "banderolle_img">

 
    <h2 class = "title">Options</h2>
 

    <div id = "redirect_buttons">
        <button class = "button" id = "button_patient">My clinical trials</button>
        <button class = "button" id = "button_patient">New studies</button>
    </div>

    <h2 class = "title">My clinical trials</h2>
    
    <div id = "personnal_data">
        <!-- debut du tableau -->
         <table class = "styled-table" id = "table_patient">
            <thead>
                <tr>
                    <th>First name</th>
                    <th>Family name</th>
                    <th>Gender</th>
                    <th>Origins</th>
                    <th>Email</th>
                    <th>Medical history</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>John</td>
                    <td>Doe</td>
                    <td>Hélicoptère</td>
                    <td>France</td>
                    <td>test@test.mail.com</td>
                    <td>None</td>
                </tr>
            </tbody>
         </table>
         <a href="page_test.php" id = "edit_option">Edit</a>
    </div>
</body>