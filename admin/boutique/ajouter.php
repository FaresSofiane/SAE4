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
<h1 class="title">Ajout boutique</h1>
<br>
<div class="ajouter">
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_boutique = $_POST['nom_boutique'];
    $emplacement = $_POST['emplacement'];
    $chiffreaffaire = $_POST['chiffreaffaire'];
    $clientquotidien = $_POST['clientquotidien'];
    $sql = "INSERT INTO boutique (Nom_boutique, Emplacement, Chiffre_affaires, Nb_clients_quotidiens) VALUES ('$nom_boutique', '$emplacement', '$chiffreaffaire', '$clientquotidien')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION["Message"] = "Le manège a été ajouté avec succès.";
    } else {
        $_SESSION["Message"] = "Erreur lors de l'ajout du boutique : " . $sql . "<br>" . mysqli_error($conn);
    }
    header("Location: index.php");
}

$sql_zones = "SELECT * FROM zone";
$result_zones = mysqli_query($conn, $sql_zones);

$sql_cm = "SELECT * FROM cm";
$result_cm = mysqli_query($conn, $sql_cm);

?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Nom de la boutique: <input type="text" name="nom_boutique"><br><br>
        Emplacement: <select name="emplacement">
            <?php
            while ($row = mysqli_fetch_assoc($result_zones)) {
                echo '<option value="' . $row["Id_zone"] . '">' . $row["Nom_zone"] . '</option>';

            }
            ?>
        </select><br><br>
        Chiffre d'affaire: <input type="number" name="chiffreaffaire"><br><br>
        Nombre de clients quotidiens: <input type="number" name="clientquotidien"><br>
        <br><br>
        <input type="submit" value="Ajouter">
    </form>
    </body>
    </html>

    </div>
<?php
mysqli_close($conn);
?>