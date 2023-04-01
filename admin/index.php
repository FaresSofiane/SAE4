<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur" && $_SESSION["role"] != "CM" && $_SESSION["role"] != "Responsable") {
    header("Location: ../index.php");
    exit();
}




?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin/table.css">
    <link rel="stylesheet" type="text/css" href="../assets/font/Source_Sans_Pro/font.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>const navLinks = document.querySelectorAll('nav ul li a');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
    <meta charset="UTF-8">
</head>
<body>
<div class="header">
    <h1>Starlight Park</h1>
    <nav>
        <ul>
           <?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM" || $_SESSION['role'] == "Responsable"){echo '<li><a href="admin">Admin</a></li>';}?>
            <li><a href="../dashboard.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="manege">Manege</a></li>
            <li class="dropdown">
                <a href="#"><?php echo $_SESSION["nom_utilisateur"]?></a>
                <div class="dropdown-content">
                    <a href="compte">Mon compte</a>
                    <a href="../deconnection.php" class="deconnexion-btn">Déconnexion</a>

                </div>
            </li>
        </ul>
    </nav>
</div>

<div class="Bouton-redirect">

    <?php

    if ($_SESSION['role'] == "Directeur" || $_SESSION['role'] == "CM") {
        ?>
    <button onclick="window.location.href='manege'">manège</button>
    <?php
    }
    if ($_SESSION['role'] == "Directeur" || $_SESSION['role'] == "Responsable") {

        ?>


    <button  onclick="window.location.href='boutique'">Boutique</button>
        <?php }
    if ($_SESSION["role"] == "Directeur" || $_SESSION["role"] == "CM" || $_SESSION["role"] == "Responsable") {
        ?>
        <button onclick="window.location.href='personnel'">Personnel</button>
        <button onclick="window.location.href='quiarepare.php'">Qui a réparé ?</button>
        <button onclick="window.location.href='chiffre_affaire_objet.php'">Chiffres d'affaires objet unitaire</button>
        <button onclick="window.location.href='piece_utilise.php'">Piece utilise lors de la derniere maintenance</button>
        <button onclick="window.location.href='objet_vendu.php'">Objet vendu par tout les magasins</button>';*
        <?php
    }

    ?>





</div>
</body>
</html>