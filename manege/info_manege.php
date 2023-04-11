<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/manege/styles.css">


    <link rel="stylesheet" type="text/css" href="../assets/font/Source_Sans_Pro/font.css">
            <meta charset="UTF-8">
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
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../vente">Vente</a></li>
            <li><a href="index.php">Manege</a></li>
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
<div class="info">
<?php

$id = $_GET['id'];

require '../connex.inc.php';
require_once '../myparam.inc.php';
$conn = connex(MYBASE, "../myparam");

$nom_cm = "SELECT p.Nom, p.Prenom
FROM personnel p
         JOIN cm c ON c.Numero_SS = p.Numero_SS
         JOIN Manege m ON m.Id_cm = c.id_cm
WHERE m.Id_manege = $id;
";

$reparation = "SELECT r.Date_reparation, t.Numero_SS
FROM reparation r
JOIN technicien t ON t.id_technicien = r.Id_technicien
WHERE r.Id_manege = $id
";

$zone="SELECT z.Nom_zone
FROM zone z
JOIN Manege m ON m.Id_zone = z.Id_zone
WHERE m.Id_manege = $id";

$manege="select * from manege where Id_manege = $id";

$resultat_cm = mysqli_query($conn, $nom_cm);
$resultat_reparation = mysqli_query($conn, $reparation);
$resultat_zone = mysqli_query($conn, $zone);
$resultat_man = mysqli_query($conn, $manege);

if ($resultat_cm->num_rows > 0) {
    while($row = $resultat_cm->fetch_assoc()) {

        echo "<h1>Nom et prénom du cm qui le gère: " . $row["Nom"] . " " . $row["Prenom"] . "<h1><br>";

    }
} else {
    echo "<h1>Aucun cm trouvé.</h1>";
}

if ($resultat_reparation->num_rows > 0) {


    echo "<h1>reparation</h1>";
    echo "<table>";
        echo "<tr>";
            echo "<th>Date de la réparation</th>";
            echo "<th>Numéro de sécurité sociale du technicien</th>";
        echo "</tr>";
        while ($row = $resultat_reparation->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Date_reparation"] . "</td>";
            echo "<td>" . $row["Numero_SS"] . "</td>";
            echo "</tr>";
        }

} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_zone ->num_rows > 0) {
    while($row = $resultat_zone->fetch_assoc()) {

        echo "<h1>zone: " . $row["Nom_zone"] . "</h1><br>";

    }
} else {
    echo "<h1>Aucun réparation trouvé.</h1>> \n";
}

if ($resultat_man ->num_rows > 0) {
    while ($row = $resultat_man->fetch_assoc()) {

        echo "<h1>Nom du manège: " . $row["Nom_manege"] . "</h1><br>";
        echo "<h1>Nombre de places: " . $row["Description"] . "</h1><br>";
        echo "<h1>Hauteur: " . $row["Taille_min_client"] . "</h1><br>";



    }
}


?>
</div>
<p>Cliquez sur le bouton ci-dessous pour revenir à la page précédente :</p>
<button onclick="history.go(-1)">Retour</button>

</body>
</html>
