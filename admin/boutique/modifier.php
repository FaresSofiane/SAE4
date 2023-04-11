<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] != "directeur" && $_SESSION["role"] != "responsable") {
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
        <link rel="stylesheet" type="text/css" href="../../assets/css/admin/table.css">
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
            <?php if ($_SESSION["role"]=="directeur" || $_SESSION['role'] =="responsable"){echo '<li><a href="admin">Admin</a></li>';}?>            <li><a href="../../index.php">Accueil</a></li>

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


<?php
if (isset($_POST['id_boutique'])) {
    $id_boutique = $_POST['id_boutique'];
    $nom_boutique = $_POST['nom_boutique'];
    $emplacement = $_POST['Emplacement'];
    $chiffreaffaire = $_POST['chiffreaffaire'];
    $clientquotidien = $_POST['clientquotidien'];


    $sql = "UPDATE boutique SET Nom_boutique='$nom_boutique', Emplacement='$emplacement',  Chiffre_affaires='$chiffreaffaire', Nb_clients_quotidiens='$clientquotidien' WHERE Id_boutique='$id_boutique'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION["Message"] = "La boutique a été modifié avec succès";
    } else {
        $_SESSION["Message"] = "Erreur lors de la modification de la boutique: " . mysqli_error($conn);
    }

    header("Location: index.php");
    mysqli_close($conn);
} else {
    $id_boutique = $_GET['id_boutique'];

    $sql = "SELECT * FROM boutique WHERE Id_boutique='$id_boutique'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $nom_boutique = $row['Nom_boutique'];
    $emplacement = $row['Emplacement'];
    $chiffreaffaire = $row['Chiffre_affaires'];
    $clientquotidien = $row['Nb_clients_quotidiens'];


}

    ?>

    <?php

    $sql_zones = "SELECT * FROM zone";
    $result_zones = mysqli_query($conn, $sql_zones);

    $sql_cm = "SELECT * FROM cm";
    $result_cm = mysqli_query($conn, $sql_cm);

    ?>
<div class="modifier">
    <h2>Modifier une boutique</h2>
    <form action="modifier.php" method="post">
        <input type="hidden" name="id_boutique" value="<?php echo $id_boutique ?>">
        <label for="id_boutique">Nom de la boutique:</label>
        <input type="text" name="nom_boutique" value="<?php echo $nom_boutique ?>">
        <br>
        <label for="Emplacement">Emplacement:</label>
        <select name="Emplacement">
            <?php
            while ($row = mysqli_fetch_assoc($result_zones)) {
                echo '<option value="' . $row["Id_zone"] . '">' . $row["Nom_zone"] . '</option>';

            }
            ?>
        </select>
        <br>
        <label for="ChiffreAffaire">Chiffre d'affaire:</label>
        <input type="number" name="chiffreaffaire" value="<?php echo $chiffreaffaire ?>">
        <br>
        <label for="clientquotidien">Nombre de clients quotidiens</label>
        <input type="number" name="clientquotidien" value="<?php echo $clientquotidien ?>">

        <br>

            <br>
        <input type="submit" value="Modifier">
    </form>
</div>
</body>
</html>

