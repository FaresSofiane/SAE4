<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}
if ($_SESSION["role"] != "Directeur" && $_SESSION["role"] != "CM") {
    header("Location: ../index.php");
    exit();
}
Include("../../connex.inc.php") ;
$conn=connex("sae4", "../../param.wamp") ;



?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/admin/styles.css">
    <link rel="stylesheet" type="text/css" href="../../assets/font/Source_Sans_Pro/font.css">
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
<?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM"){echo '<li><a href="admin">Admin</a></li>';}?>            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="../../manege">Manege</a></li>
            <li class="dropdown">
                <a href="#"><?php echo $_SESSION["nom_utilisateur"]?></a>
                <div class="dropdown-content">
                    <a href="../../compte">Mon compte</a>
                    <a href="../../deconnection.php" class="deconnexion-btn">Déconnexion</a>

                </div>
            </li>
        </ul>
    </nav>
</div>
<h1 class="title">Liste Boutique</h1>
<br>
<a href="ajouter.php" style="display: inline-block; padding: 10px 20px; background-color: #ffffff; border: 1px solid #000000;">Ajouter une boutique</a>
<div class="resultat">

<?php
$sql = "SELECT * FROM Boutique";
$result = $conn->query($sql);

// Affichage des manèges dans un tableau
echo "<table><thead><tr><th>Id boutique</th><th>Nom boutique</th><th>Emplacement</th><th>Chiffres d'affaires</th><th>Nombres de clients quotidiens</th><th>Modifier</th><th>Supprimer</th></tr><thead><tbody>";
while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["Id_boutique"]. "</td><td>" . $row["Nom_boutique"]. "</td><td>" . $row["Emplacement"]. "</td><td>" . $row["Chiffre_affaires"]. "</td><td>" . $row["Nb_clients_quotidiens"]. "</td>";
    echo "<td><a href='modifier.php?id_boutique=".$row["Id_boutique"]."'>Modifier</a></td>";
    echo "<td><a href='supprimer.php?id_boutique=".$row["Id_boutique"]."'>Supprimer</a></td>";

}
echo "</tbody></table>";

// Fermeture de la connexion à la base de données
$conn->close();
echo "</div>";
if (isset($_SESSION["Message"])){
    echo $_SESSION["Message"];
    unset($_SESSION["Message"]);
}

?>



</div>

</body>
</html>