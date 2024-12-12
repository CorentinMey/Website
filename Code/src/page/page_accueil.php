<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome page</title>
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_accueil.css">
    
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['inscription'])) {
        header('Location: page_signin.php');
        exit();
    } elseif (isset($_POST['connexion'])) {
        header('Location: page_login.php');
        exit();
    }
}
?>

<body>
    <div id = "en-tete">
        <img src = "../Ressources/Images/logo_medexplorer.png" alt = "logo_med_explorer" id = logo_page>
        <div id = "en-tete_text">
            <h1>MedExplorer</h1>
            <h2>A new way for health research</h2>
        </div>
    </div>
    
    <div id="bandeau_top">
        <form method="POST" action="page_accueil.php" id="bande_welcome">
            <button type="submit" class="button" name="inscription" value="1" id="button">SIGN IN</button>
            <button type="submit" class="button" name="connexion" value="1" id="button">LOG IN</button>
        </form>
    </div>

    <div id="fond">
        <img src = "../Ressources/Images/background_accueil.png" alt = "fond_accueil" id = "fond_image">
        <div id = "texte_fond">
            <h1 id="bienvenue">Welcome on <br>MedExplorer</h1>
            <h2 id="ready" >Ready to start your trial ?</h2>
        </div>
    </div>
 

    <div id = "conteneur_tableau">
        <h1 id="why">Why choosing us ?<br></h1>
        <div id="grid_container">
            <div class="box" id="box1">
                <img src = "../Ressources/Images/box1_image.png"   alt = "Box1 image">
                <p>Participative platform</p>
                <br>
                <p>Patients are considered as an integral part in our process</p>
            </div>

            <div class="box" id="box2">
                <img src = "../Ressources/Images/box2_image.png"   alt = "Box2 image">
                <p>Communicative platform</p>
                <br>
                <p>Interact and contact profesionnal for your study</p>
            </div>
            <div class="box" id="box3">
                <img src = "../Ressources/Images/box3_image.png"   alt = "Box2 image">
                <p>Professionnal analysis</p>
                <br>
                <p>Get quality raw data visualisation</p>
            </div>
            <div class="box" id="box4">
                <img src = "../Ressources/Images/box4_image.png"   alt = "Box2 image">
                <p>Participate to medical progress</p>
                <br>
                <p>Stimulate medicine research and give shape to the future healthcare</p>
            </div>
        </div>
    </div>

    <div id="logo_bas">
        <img src = "../Ressources/Images/logo_medexplorer.png" alt = "logo_med_explorer" id = logo_page_bas>
        <div id = "text_bas">
            <h1>MedExplorer</h1>
            <h2>Serving your health, at the heart of research</h2>
        </div>



    </div>

<br>
<br>
<br>
<br>
<br>
<br>

<body>