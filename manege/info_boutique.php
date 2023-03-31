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
require_once '../param.wamp.inc.php';
$conn = connex(MYBASE, "../param.wamp");

$stock = "SELECT b.Nom_boutique, o.Nom_objet, o.Prix, o.En_stock
FROM Boutique b
         INNER JOIN Vente v ON b.Id_boutique = v.Id_boutique
         INNER JOIN Objet o ON v.Id_objet = o.Id_objet where b.id_boutique = $id";

$vente = "SELECT Vente.*, Objet.Nom_objet AS nom_objet
FROM Vente
         INNER JOIN Objet ON Vente.Id_objet = Objet.Id_objet
WHERE Vente.Id_boutique = $id";

$nom_responsable = "SELECT r.Id_responsable, p.Nom, p.Prenom
FROM Responsable r
         INNER JOIN Personnel p ON r.Numero_SS = p.Numero_SS
         INNER JOIN Boutique b ON r.id_boutique = b.Id_boutique where b.id_boutique = $id";

$info = "select * from Boutique where Id_boutique = $id";


$resultat_stock = mysqli_query($conn, $stock);
$resultat_vente = mysqli_query($conn, $vente);
$resultat_nomrp = mysqli_query($conn, $nom_responsable);
$resultat_bout = mysqli_query($conn, $info);

if ($resultat_bout ->num_rows > 0) {
    while($row = $resultat_bout->fetch_assoc()) {

        echo "Nom de la boutique: " . $row["Nom_boutique"] . "<br>";
        echo "Emplacement: " . $row["Emplacement"] . "<br>";
        echo "Chiffre d'affaire" . $row["Chiffre_affaires"] . "<br>";
        echo "Clients quotidiens" . $row["Nb_clients_quotidiens"] . "<br>";

    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_nomrp->num_rows > 0) {
    while($row = $resultat_nomrp->fetch_assoc()) {

        echo "Nom et prénom du CM qui le gère: " . $row["Nom"] . " " . $row["Prenom"] . "<br>";

    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_vente->num_rows > 0) {
    echo "<h1>Objets vendus:</h1> <br>";
    echo "<center><table border='1'>";
    echo "<tr><th>Date</th><th>Prix</th><th>Quantité</th><th>Nom_objet</th></tr>";

    while($row = $resultat_vente->fetch_assoc()) {
        echo "<tr><td>" . $row["Date_vente"] . "</td><td>" . $row["Prix_unitaire"] . "</td><td>" . $row["Quantite"] . "</td><td>" . $row["nom_objet"] . "</td></tr>";
    }
    echo "</table></center>";
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_stock ->num_rows > 0) {
    echo "<h1>Objets en stock:</h1> <br>";
    echo "<center><table border='1'>";
    echo "<tr><th>Nom_boutique</th><th>Nom_objet</th><th>Prix</th><th>En_stock</th></tr>";

    while($row = $resultat_stock->fetch_assoc()) {
        echo "<tr><td>" . $row["Nom_boutique"] . "</td><td>" . $row["Nom_objet"] . "</td><td>" . $row["Prix"] . "</td><td>" . $row["En_stock"] . "</td></tr>";
    }
    echo "</table></center>";
} else {
    echo "Aucun résultat trouvé.";
}



?>
</div>
<p>Cliquez sur le bouton ci-dessous pour revenir à la page précédente :</p>
<button onclick="history.go(-1)">Retour</button>

</body>
</html>

