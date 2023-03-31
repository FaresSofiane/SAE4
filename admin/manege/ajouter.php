<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur") {
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
            <?php if ($_SESSION["role"]=="Directeur"){echo '<li><a href="../">Admin</a></li>';}?>

            <li><a href="../../index.php">Accueil</a></li>
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
<h1 class="title">Ajout Manege</h1>
<br>
<div class="ajouter">
<?php

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_manege = $_POST["nom_manege"];
    $description = $_POST["description"];
    $taille_min_client = $_POST["taille_min_client"];
    $id_cm = $_POST["id_cm"];
    $id_zone = $_POST["id_zone"];
    // Préparer la requête SQL pour insérer un nouveau manège
    $sql = "INSERT INTO Manege (Nom_manege, Description, Taille_min_client, Id_cm, Id_zone) VALUES ('$nom_manege', '$description', '$taille_min_client', '$id_cm', '$id_zone')";

    // Exécuter la requête SQL
    if (mysqli_query($conn, $sql)) {
        $_SESSION["Message"] = "Le manège a été ajouté avec succès.";
    } else {
        $_SESSION["Message"] = "Erreur lors de l'ajout du manege : " . $sql . "<br>" . mysqli_error($conn);
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
        Nom du manège: <input type="text" name="nom_manege"><br><br>
        Description: <input type="text" name="description"><br><br>
        Taille minimale pour le client: <input type="number" name="taille_min_client"><br><br>
        CM:
        <select name="id_cm">
            <?php
            // Afficher les options pour les CM disponibles
            while ($row = mysqli_fetch_assoc($result_cm)) {
                echo '<option value="' . $row["Id_cm"] . '">' . $row["Numero_SS"] . ' - ' . $row["Famille"] . '</option>';
            }
            ?>
        </select>
        <br><br>
        Zone:
        <select name="id_zone">
            <?php
            // Afficher les options pour les zones disponibles
            while ($row = mysqli_fetch_assoc($result_zones)) {
                echo '<option value="' . $row["Id_zone"] . '">' . $row["Nom_zone"] . '</option>';
            }
            ?>
        </select>
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