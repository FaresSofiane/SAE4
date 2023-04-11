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
<h1 class="title">Ajouter un personnel</h1>
<div class="ajouter">
<?php

if (isset($_POST['modifier'])) {
    echo "Modification du personnel";

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];
    if ($role == "cm") {
        $famille = $_POST['cm-famille'];
    }
    if ($role == "technicien") {
        $atelier = $_POST['atelier'];
    }
    if ($role == "responsable") {
        $id_boutique = $_POST['boutique'];
    }

    $requete = "INSERT INTO personnel (Numero_SS, Nom, Prenom, Mot_de_passe)
VALUES ('$id', '$nom', '$prenom', '1234')
";
    mysqli_query($conn, $requete);

        switch ($role) {
    case 'cm':
        $requete = "INSERT INTO cm (Numero_SS, Famille) VALUES ('$id', '$famille') ON DUPLICATE KEY UPDATE Famille='$famille'";
        break;
    case 'technicien':
        $requete = "INSERT INTO technicien (Numero_SS, Id_atelier) VALUES ('$id', '$atelier')";
        break;
    case 'employe':
        $requete = "INSERT INTO employe (Id_employe, Numero_SS) VALUES ('$id', '$id')";
        break;
    case 'responsable':
        $requete = "INSERT INTO responsable (Numero_SS, id_boutique) VALUES ('$id', '$id_boutique')";
        break;
    default:
        $requete = "INSERT INTO technicien (Numero_SS, Id_atelier) VALUES ('$id', 1)";
        break;
}
echo $requete;
mysqli_query($conn, $requete);

 $_SESSION["Message"] = "Le personnel a été modifié avec succès.";
        echo $_SESSION["Message"];
    exit();
}

?>




<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Numéro de Sécurité Sociale<input type="text" name="id" required><br>
    Nom : <input type="text" name="nom" required><br><br>
    Prénom : <input type="text" name="prenom" required><br><br>
    Rôle :
    <select name="role" onchange="showboutiqueSelect(this)">
        <option value="cm">cm</option>
        <option value="technicien">technicien</option>
        <option value="employe">Employé</option>
        <option value="responsable">responsable</option>
    </select>
    <div id="boutique-select" style="display:none">
        <select name="boutique">
            <?php
            $sql_cm = "SELECT * FROM boutique where Id_boutique not in (select Id_boutique from responsable)";
            $result_boutique = mysqli_query($conn, $sql_cm);
            while ($row = mysqli_fetch_assoc($result_boutique)) {
                echo '<option value="' .$row["Id_boutique"]. '">' . $row["Nom_boutique"] . '</option>';
            }
            ?>
        </select>
    </div>
    <div id="techni-select" style="display:none">
        <select name="atelier">
            <?php
            $sql_t = "SELECT * FROM atelier";
            $result_t = mysqli_query($conn, $sql_t);
            while ($row = mysqli_fetch_assoc($result_t)) {
                echo '<option value="' .$row["Id_atelier"]. '">' . $row["Id_atelier"] . '</option>';
            }
            ?>
        </select>
    </div>
    <div id="cm-select" style="display:block">
        Famille :<input type="text" name="cm-famille"><br><br>
    </div>


    <script>
        function showboutiqueSelect(selectElement) {
            var boutiqueSelect = document.getElementById("boutique-select");
            if (selectElement.value === "responsable") {
                boutiqueSelect.style.display = "block";
            } else {
                boutiqueSelect.style.display = "none";
            }

            var tecSelect = document.getElementById("techni-select");
            if (selectElement.value === "technicien") {
                tecSelect.style.display = "block";
            } else {
                tecSelect.style.display = "none";
            }

            var cmSelect = document.getElementById("cm-select");
            if (selectElement.value === "cm") {
                cmSelect.style.display = "block";
                cmSelect.required = true;
            } else {
                cmSelect.required = false;
                cmSelect.style.display = "none";
            }

        }
    </script>
    <input type="submit" name="modifier" value="Modifier">
</form>
</div>