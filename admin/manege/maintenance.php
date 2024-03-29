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
    <script>
        const navLinks = document.querySelectorAll('nav ul li a');
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
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
            <?php if ($_SESSION["role"] == "directeur" || $_SESSION['role'] == "cm") {
                echo '<li><a href="../../admin">Admin</a></li>';
            } ?>
            <li><a href="../../index.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="../../manege">Manege</a></li>
            <li class="dropdown">
                <a href="#"><?php echo $_SESSION["nom_utilisateur"] ?></a>
                <div class="dropdown-content">
                    <a href="../../compte">Mon compte</a>
                    <a href="../../deconnection.php" class="deconnexion-btn">Déconnexion</a>
                </div>
            </li>
        </ul>
    </nav>
</div>

<br>

<div class="ajouter">
    <?php


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_manege = $_POST["id_manege"];
        $id_technicien = $_POST["id_tec"];
        $date = $_POST["date"];

        $sql = "INSERT INTO maintenance (Id_manege, Id_technicien, Date_maintenance) VALUES ('$id_manege', '$id_technicien', '$date')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION["Message"] = "La maintenance a été ajoutée avec succès";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["Message"] = "Erreur lors de l'ajout de la maintenance : " . mysqli_error($conn);
        }
    }

    $id_manege = $_GET["id_manege"];
    $sql = "SELECT * FROM manege WHERE Id_manege = '".$id_manege."'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $nom_manege = $row['Nom_manege'];

    ?>


    <h2>Ajouter une maintenance pour le manège <?php echo $nom_manege; ?></h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Nom du manège: <a><?php echo $nom_manege;?></a><br><br>
        Id du manège: <input type="number" name="id_manege" value="<?php echo $id_manege;?>" readonly><br><br>
        <label for="id_tec">ID du technicien:</label>
        <select name="id_tec">
            <?php
            $sql_tec = "SELECT * FROM technicien WHERE id_atelier = (SELECT id_atelier FROM manege WHERE id_manege = '".$id_manege."')";
            $result_tec = mysqli_query($conn, $sql_tec);
            while ($r = mysqli_fetch_assoc($result_tec)) {
                echo '<option value="' . $r["id_technicien"] . '">' . $r["Numero_SS"] . '</option>';
            }
            ?>
        </select>
        <br><br>
        Date de la maintenance: <input type="date" name="date" required><br><br>
        <input type="submit" value="Ajouter">
    </form>
    </body>
    </html>

    <?php
    mysqli_close($conn);
    ?>
