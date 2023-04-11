<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] != "directeur" && $_SESSION["role"] != "cm" && $_SESSION["role"] != "responsable") {
    header("Location: ../index.php");
    exit();
}
Include("../../connex.inc.php") ;
Include("../../myparam.inc.php");
$conn=connex(MYBASE, "../../myparam") ;

Include("role.php") ;

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
<?php if ($_SESSION["role"]=="directeur" || $_SESSION['role'] =="cm"){echo '<li><a href="../index.php">Admin</a></li>';}?>            <li><a href="../../index.php">Accueil</a></li>
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
<h1 class="title">Liste personnel</h1>
<br>
<?php if ($_SESSION["role"] == "directeur"){
    echo '<a href="ajouter.php" style="display: inline-block; padding: 10px 20px; background-color: #ffffff; border: 1px solid #000000;">Ajouter un personnel</a>';
}?>

<div class="resultat">

    <?php
    $sql = "SELECT * FROM personnel";
    $result = $conn->query($sql);
    if ($_SESSION["role"] == "directeur") {
        echo "<table><thead><tr><th>Numéro de sécurité sociale</th><th>Nom</th><th>Prenom</th><th>Role</th><th>Modifier</th><th>Supprimer</th></tr><thead><tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Numero_SS"]. "</td><td>" . $row["Nom"]. "</td><td>" . $row["Prenom"]. "</td>";
            echo "<td>" . chercherRole($row["Numero_SS"]) . "</td>";
            if ($row["Numero_SS"] != $_SESSION["numero_ss"]) {
                echo "<td><a href='modifier.php?id=" . $row["Numero_SS"] . "'>Modifier</a></td>";
                echo "<td><a href='supprimer.php?id=" . $row["Numero_SS"] . "'>Supprimer</a></td>";
            } else {
                echo "<td>bloqué</td><td>bloqué</td>";
            }
        }
    } else {
        echo "<table><thead><tr><th>Numéro de sécurité sociale</th><th>Nom</th><th>Prenom</th><th>Role</th></tr><thead><tbody>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["Numero_SS"]. "</td><td>" . $row["Nom"]. "</td><td>" . $row["Prenom"]. "</td>";
            echo "<td>" . chercherRole($row["Numero_SS"]) . "</td>";

        }
    }

    echo "</tbody></table>";

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