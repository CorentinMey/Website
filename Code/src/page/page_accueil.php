<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_accueil.css">
    
</head>
<body>
    <div id = "en-tete">
        <img src = "../Ressources/Images/logo_medexplorer.png" alt = "logo_med_explorer" id = logo_page>
        <div id = "en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>
    
    <div id="bandeau_top">
        <h1>WELCOME</h1>
        <form method="POST" action="accueil.php" id="bande_welcome">
            <button type="submit" class="button" name="show_list_user" value="1" id="button1">SIGN IN</button>
            <button type="submit" class="button" name="show_list_doc" value="1" id="button2">LOG IN </button>
        </form>
    </div>
    <div id="ma_div">
        <!-- <img src="../Ressources/Images/background_accueil.png" alt="fond" id="fond"> -->
        <h1 id="bienvenue">Welcome on <br>MedExplorer</h1>
        <h2 id="ready" >ready to start your trial ?</h2>
    </div>
    <br>
    <br>
    <br>    

    <div>
        <h1 id="why">Why choosing us ?<br></h1>
    <div id="grid_container">
        <div class="box">Box 1</div>
        <div class="box">Box 2</div>
        <div class="box">Box 3</div>
        <div class="box">Box 4</div>
    </div>
    </div>

<?php

?>
<body>