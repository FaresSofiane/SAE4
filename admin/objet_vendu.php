<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur") {
    header("Location: ../index.php");
    exit();
}

Include("../connex.inc.php") ;
$conn=connex("sae4", "../param.wamp") ;

?>

<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin/quiarepare.css">

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
            <?php if ($_SESSION["role"]=="Directeur"){echo '<li><a href="./">Admin</a></li>';}?>

            <li><a href="../dashboard.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="../manege">Manege</a></li>
            <li class="dropdown">
                <a href="#"><?php echo $_SESSION["nom_utilisateur"]?></a>
                <div class="dropdown-content">
                    <a href="../compte">Mon compte</a>
                    <a href="../deconnection.php" class="deconnexion-btn">Déconnexion</a>

                </div>
            </li>
        </ul>
    </nav>
</div>
<h1 class="title">Objet vendu par tout les magasins</h1>
<div class="resultat">

    <?php


    $req= "SELECT Nom_objet
FROM Objet
WHERE En_stock > 0 AND NOT EXISTS (
        SELECT Id_boutique
        FROM Boutique
        WHERE NOT EXISTS (
                SELECT Id_objet
                FROM Vente v
                WHERE v.Id_boutique = Boutique.Id_boutique AND v.Id_objet = Objet.Id_objet
            )
    )";

    $resultat = mysqli_query($conn, $req);

    echo '<table>';
    echo '<thead><tr>';
    echo "<th>Nom de l'objet</th>";

    echo '</tr><thead><tbody>';
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        echo '<tr>';
        echo '<td>'. $ligne["Nom_objet"] . '</td>';

        echo '</tr>';
    }
    echo '<tbody></table>';

    ?>
</div>
</body>
</html>