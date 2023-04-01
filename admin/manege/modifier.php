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
        <link rel="stylesheet" type="text/css" href="../../assets/css/admin/table.css">
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
           <?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM"){echo '<li><a href="../../admin">Admin</a></li>';}?>
            <li><a href="../../dashboard.php">Accueil</a></li>
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
// Vérifier si le formulaire a été soumis
if (isset($_POST['id_manege'])) {
    // Récupérer les valeurs du formulaire
    $id_manege = $_POST['id_manege'];
    $nom_manege = $_POST['nom_manege'];
    $description = $_POST['description'];
    $taille_min_client = $_POST['taille_min_client'];
    $id_cm = $_POST['id_cm'];
    $id_zone = $_POST['id_zone'];

    // Requête de mise à jour du manège
    $sql = "UPDATE Manege SET Nom_manege='$nom_manege', Description='$description', Taille_min_client='$taille_min_client', Id_cm='$id_cm', Id_zone='$id_zone' WHERE Id_manege='$id_manege'";

    // Exécuter la requête de mise à jour du manège
    if (mysqli_query($conn, $sql)) {
        $_SESSION["Message"] = "Le manège a été modifié avec succès";
    } else {
        $_SESSION["Message"] = "Erreur lors de la modification du manège: " . mysqli_error($conn);
    }

    // Rediriger vers la page d'accueil
    header("Location: index.php");
    mysqli_close($conn);
} else {
    // Récupérer l'ID du manège
    $id_manege = $_GET['id_manege'];

    // Requête pour récupérer les informations du manège
    $sql = "SELECT * FROM Manege WHERE Id_manege='$id_manege'";
    $result = mysqli_query($conn, $sql);

    // Récupérer les informations du manège
    $row = mysqli_fetch_assoc($result);
    $nom_manege = $row['Nom_manege'];
    $description = $row['Description'];
    $taille_min_client = $row['Taille_min_client'];
    $id_cm = $row['Id_cm'];
    $id_zone = $row['Id_zone'];

    // Requête pour récupérer les zones disponibles
if ($_SESSION["role"] == "Directeur") {
// Récupérer les CM disponibles
    $sql_cm = "SELECT * FROM CM";
    $result_cm = mysqli_query($conn, $sql_cm);

} else {
    $sql_cm = "SELECT * FROM CM WHERE Numero_SS = '" . $_SESSION["numero_ss"] . "'";
    $result_cm = mysqli_query($conn, $sql_cm);
}

}
$sql_zones = "SELECT * FROM Zone";
$result_zones = mysqli_query($conn, $sql_zones);
 ?>
<div class="modifier">
    <h2>Modifier un manège</h2>
    <form action="modifier.php" method="post">
        <input type="hidden" name="id_manege" value="<?php echo $id_manege ?>">
        <label for="nom_manege">Nom du manège:</label>
        <input type="text" name="nom_manege" value="<?php echo $nom_manege ?>">
        <br>
        <label for="description">Description:</label>
        <textarea name="description"><?php echo $description ?></textarea>
        <br>
        <label for="taille_min_client">Taille minimale du client:</label>
        <input type="number" name="taille_min_client" value="<?php echo $taille_min_client ?>">
        <br>
        <label for="id_cm">ID du CM:</label>
        <select name="id_cm">
            <?php
            // Afficher les options pour les CM disponibles
            while ($row = mysqli_fetch_assoc($result_cm)) {
                echo '<option value="' . $row["Id_cm"] . '">' . $row["Numero_SS"] . ' - ' . $row["Famille"] . '</option>';
            }
            ?>
        </select>
        <br>
        <label for="id_zone">ID de la zone:</label>
        <select name="id_zone">
            <?php
            // Afficher les options pour les zones disponibles
            while ($row = mysqli_fetch_assoc($result_zones)) {
                echo '<option value="' . $row["Id_zone"] . '">' . $row["Nom_zone"] . '</option>';
            }
            ?>
        </select>
            <br>
        <input type="submit" value="Modifier">
    </form>
</div>
</body>
</html>

