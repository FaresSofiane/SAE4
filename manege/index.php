<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}

?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/manege/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/font/Source_Sans_Pro/font.css">
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
           <?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM"){echo '<li><a href="../admin">Admin</a></li>';}?>
            <li><a href="../index.php">Accueil</a></li>
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

<div class="recherche">
    <h1>Rechercher les manéges , boutiques</h1>

<form action="index.php" method="GET">
        <input type="text" placeholder="Recherche" name="recherche" class="input-text"><br>
    <div class="input-radio">
        <input type="radio" id="manege" name="parc" value="Manege" class="input-radio"
               checked>
        <label for="manege">Manege</label>

        <input type="radio" id="boutique" name="parc" value="Boutique" class="input-radio">
        <label for="boutique">Boutique</label>
    </div>
        <button type="submit"><i class="">Rechercher</i></button>

</form>
</div>

<br>
<div class="resultat">
    <?php



    require '../connex.inc.php';
    require_once '../myparam.inc.php';
    if (isset($_GET['parc']) || isset($_GET['recherche'])){

        $category = $_GET['parc'];
        $recherche = $_GET['recherche'];
        $idcom = connex(MYBASE, "../myparam");
        if ($category=="Manege") {
            $requete = "SELECT * FROM Manege WHERE Nom_manege LIKE '%$recherche%' or Description LIKE '%$recherche%'";
            $resultat = mysqli_query($idcom, $requete);
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>Nom du manège</th>';
            echo '<th>Description</th>';
            echo '<th>nombre min de client</th>';
            echo '</tr><thead><tbody>';
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                echo '<tr>';
                echo '<td><a href="info_manege.php?id=' . $ligne['Id_manege'] . '">' . $ligne['Nom_manege'] . '</a></td>';

                echo '<td>' . $ligne['Description'] . '</td>';
                echo '<td>' . $ligne['Taille_min_client'] . '</td>';
                echo '</tr>';
            }
            echo '<tbody></table>';
        } else {
            $requete = "SELECT * FROM Boutique WHERE Nom_boutique LIKE '%$recherche%' or Emplacement LIKE '%$recherche%'";
            $resultat = mysqli_query($idcom, $requete);
            echo '<table>';
            echo '<tr>';
            echo '<th>Nom de la boutique</th>';
            echo '<th>Description</th>';
            echo "<th>Chiffre d'affaire</th>";
            echo '<th>Nombre quotidien de clients</th>';
            echo '</tr>';
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                echo '<tr>';
                echo '<td><a href="info_boutique.php?id=' . $ligne['Id_boutique'] . '">' . $ligne['Nom_boutique'] . '</a></td>';

                echo '<td>' . $ligne['Emplacement'] . '</td>';
                echo '<td>' . $ligne['Chiffre_affaires'] . '</td>';
                echo '<td>' . $ligne['Nb_clients_quotidiens'] . '</td>';

                echo '</tr>';
            }
            echo '</table>';
        }
        mysqli_close($idcom);

    }

    ?>

</div>

</body>

</html>
