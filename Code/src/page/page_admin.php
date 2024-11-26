<!DOCTYPE html>

<head>

    <title>Page de connexion</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../CSS/global.css">
    <link rel="stylesheet" type="text/css" href="../CSS/page_admin.css">

</head>

<body>
    <img src="../Ressources/Images/background_admin2.jpg" alt="fond" id="fond">
    <div id="bandeau_top">
        <h1>Admin page</h1>
        <img src="../Ressources/Images/profil.png" alt="profil" id="profil">


    </div>
    <div class="button-container" id="content_button">
        <button class="btn" id="button1" onclick="window.location.href='page1.html';">User List</button>
        <button class="btn" id="button2" onclick="window.location.href='page2.html';">Doc List</button>
        <button class="btn" id="button3" onclick="window.location.href='page2.html';">Company List</button>
        <button class="btn" id="button4" onclick="window.location.href='page2.html';">Clinical assay List</button>
        <button class="confirmation" id="button5" onclick="window.location.href='page2.html';">Required Confirmation for inscription
            <span class="pastille" id="notifCount">0</span>
        </button>
        
</body>
