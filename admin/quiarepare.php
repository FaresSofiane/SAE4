<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur" && $_SESSION["role"] != "CM" && $_SESSION["role"] != "Responsable") {
    header("Location: ../index.php");
    exit();
}

Include("../connex.inc.php") ;
$conn=connex("sae4", "../param.wamp") ;

?>

<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/admin/quiarepare.css">

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
            <?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM" || $_SESSION['role'] == "Responsable"){echo '<li><a href="admin">Admin</a></li>';}?>

            <li><a href="../dashboard.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="../manege">Manege</a></li>
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
<h1 class="title">Qui a réparé ?</h1>
<div class="resultat">
<form action="quiarepare.php" method="post">
    <?php
    $sql = "SELECT * FROM Manege";
    $result = $conn->query($sql);
?>
    <select name="id_manege">
    <?php
    // Afficher les options pour les CM disponibles
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row["Id_manege"] . '">' . $row["Nom_manege"].'</option>';
    }
    ?>
    </select>
    <label for="date">Choisir une date :</label>
    <input type="date" id="date" name="date" required>
    <button type="submit">Envoyer</button>
</form>

<?php

if (isset($_POST["id_manege"])) {
    $id_manege = $_POST["id_manege"];

    $date = $_POST["date"];


    $requete = "SELECT Personnel.Nom, Personnel.Prenom
FROM Personnel
         INNER JOIN Technicien ON Personnel.Numero_SS = Technicien.Numero_SS
         INNER JOIN maintenance ON Technicien.id_technicien = Maintenance.Id_technicien
         INNER JOIN Manege ON Maintenance.Id_manege = Manege.Id_manege
WHERE Manege.Id_manege = $id_manege AND Maintenance.Date_maintenance = '$date';";

$resultat = mysqli_query($conn, $requete);

    echo '<table>';
    echo '<thead><tr>';
    echo '<th>Nom</th>';
    echo '<th>Prenom</th>';
    echo '</tr><thead><tbody>';
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        echo '<tr>';
        echo '<td>'. $ligne['Nom'] . '</td>';

        echo '<td>' . $ligne['Prenom'] . '</td>';
        echo '</tr>';
    }
    echo '<tbody></table>';

}

?>
</div>
</body>
</html>
