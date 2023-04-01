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
<h1 class="title">Ajout boutique</h1>
<br>
<div class="ajouter">
<?php

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_boutique = $_POST['nom_boutique'];
    $emplacement = $_POST['emplacement'];
    $chiffreaffaire = $_POST['chiffreaffaire'];
    $clientquotidien = $_POST['clientquotidien'];
    // Préparer la requête SQL pour insérer un nouveau manège
    $sql = "INSERT INTO boutique (Nom_boutique, Emplacement, Chiffre_affaires, Nb_clients_quotidiens) VALUES ('$nom_boutique', '$emplacement', '$chiffreaffaire', '$clientquotidien')";

    // Exécuter la requête SQL
    if (mysqli_query($conn, $sql)) {
        $_SESSION["Message"] = "Le manège a été ajouté avec succès.";
    } else {
        $_SESSION["Message"] = "Erreur lors de l'ajout du boutique : " . $sql . "<br>" . mysqli_error($conn);
    }
    header("Location: index.php");
}

// Récupérer les zones disponibles
$sql_zones = "SELECT * FROM Zone";
$result_zones = mysqli_query($conn, $sql_zones);

// Récupérer les CM disponibles
$sql_cm = "SELECT * FROM CM";
$result_cm = mysqli_query($conn, $sql_cm);

?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Nom de la boutique: <input type="text" name="nom_boutique"><br><br>
        Emplacement: <select name="emplacement">
            <?php
            // Afficher les options pour les CM disponibles
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
// Fermer la connexion à la base de données
mysqli_close($conn);
?>