<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur") {
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
                <?php if ($_SESSION["role"]=="Directeur"){echo '<li><a href="./">Admin</a></li>';}?>

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
    <form action="piece_utilise.php" method="post">
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

        <button type="submit">Envoyer</button>
    </form>

<?php

if (isset($_POST["id_manege"])) {
    $id = $_POST["id_manege"];

    $req = "SELECT pd.Nom_piece
FROM sae4.Pieces_detachees pd
         JOIN sae4.Maintenance m ON m.Id_maintenance = (SELECT MAX(Id_maintenance) FROM sae4.Maintenance WHERE Id_manege = (SELECT Id_manege FROM sae4.Manege WHERE Id_manege = '$id'))
         JOIN sae4.Pieces_detachees p ON p.Id_piece = pd.Id_piece AND p.Id_atelier = m.id_technicien";

$resultat = mysqli_query($conn, $req);

    echo '<table>';
    echo '<thead><tr>';
    echo '<th>Nom de la piece</th>';
    echo '</tr><thead><tbody>';
    while ($ligne = mysqli_fetch_assoc($resultat)) {
        echo '<tr>';
        echo '<td>'. $ligne['Nom_piece'] . '</td>';
        echo '</tr>';
    }
    echo '<tbody></table>';

}
?>
    </div>
    </body>
    </html>
