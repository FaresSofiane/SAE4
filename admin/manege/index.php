<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}
if ($_SESSION["role"] != "directeur" && $_SESSION["role"] != "cm") {
    header("Location: ../index.php");
    exit();
}
Include("../../connex.inc.php") ;
Include("../../myparam.inc.php");
$conn=connex(MYBASE, "../../myparam") ;



?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/admin/styles.css">
    <link rel="stylesheet" type="text/css" href="../../assets/font/Source_Sans_Pro/font.css">
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
<?php if ($_SESSION["role"]=="directeur" || $_SESSION['role'] =="cm"){echo '<li><a href="admin">Admin</a></li>';}?>            <li><a href="../../index.php">Accueil</a></li>
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
<h1 class="title">Liste Manege</h1>
<br>
<a href="ajouter.php" style="display: inline-block; padding: 10px 20px; background-color: #ffffff; border: 1px solid #000000;">Ajouter un manège</a>
<div class="resultat">

<?php
if ($_SESSION["role"] == "directeur") {
    $sql = "SELECT * FROM manege";
    $result = $conn->query($sql);
    echo "<table><thead><tr><th>Id manège</th><th>Nom manège</th><th>Description</th><th>Taille min. client</th><th>Id cm</th><th>Id zone</th><th>Modifier</th><th>Supprimer</th><th>maintenance</th><th>Date de la maintenance</th></tr><thead><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["Id_manege"]. "</td><td>" . $row["Nom_manege"]. "</td><td>" . $row["Description"]. "</td><td>" . $row["Taille_min_client"]. "</td><td>" . $row["Id_cm"]. "</td><td>" . $row["Id_zone"]. "</td>";
        echo "<td><a href='modifier.php?id_manege=".$row["Id_manege"]."'>Modifier</a></td>";
        echo "<td><a href='supprimer.php?id_manege=".$row["Id_manege"]."'>Supprimer</a></td>";
        $sqm = "SELECT Id_maintenance ,Date_maintenance FROM maintenance WHERE Id_manege = '" . $row["Id_manege"]. "'";
        $resultm = $conn->query($sqm);

        if ($resultm->num_rows > 0) {
            $resultm = $resultm->fetch_assoc();
            echo "<td><a href='fin_maintenance.php?id_manege=".$row["Id_manege"]."'>Fin maintenance</a></td>";
            echo '<td><a>' . $resultm['Date_maintenance'] . '</a></td>';

        } else {
            echo "<td><a href='maintenance.php?id_manege=".$row["Id_manege"]."'>maintenance</a></td>";
            echo "<td><a>Pas de maintenance</a></td>";

    }

    }
    echo "</tbody></table>";
} else {
    $sql = "SELECT * FROM manege WHERE Id_cm = '" . $_SESSION["id_cm"]. "'";
        $result = $conn->query($sql);
    echo "<table><thead><tr><th>Id manège</th><th>Nom manège</th><th>Description</th><th>Taille min. client</th><th>Id cm</th><th>Id zone</th><th>Modifier</th><th>Supprimer</th><th>maintenance</th><th>Date de la maintenance</th></tr><thead><tbody>";
while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["Id_manege"]. "</td><td>" . $row["Nom_manege"]. "</td><td>" . $row["Description"]. "</td><td>" . $row["Taille_min_client"]. "</td><td>" . $row["Id_cm"]. "</td><td>" . $row["Id_zone"]. "</td>";
    echo "<td><a href='modifier.php?id_manege=".$row["Id_manege"]."'>Modifier</a></td>";
    echo "<td><a href='supprimer.php?id_manege=".$row["Id_manege"]."'>Supprimer</a></td>";
    $sqm = "SELECT Id_maintenance ,Date_maintenance FROM maintenance WHERE Id_manege = '" . $row["Id_manege"]. "'";
    $resultm = $conn->query($sqm);

    if ($resultm->num_rows > 0) {
        $resultm = $resultm->fetch_assoc();
        echo "<td><a href='fin_maintenance.php?id_manege=".$row["Id_manege"]."'>Fin maintenance</a></td>";
        echo '<td><a>' . $resultm['Date_maintenance'] . '</a></td>';

    } else {
        echo "<td><a href='maintenance.php?id_manege=".$row["Id_manege"]."'>maintenance</a></td>";
        echo "<td><a>Pas de maintenance</a></td>";

    }


}
echo "</tbody></table>";
}


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