<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur" && $_SESSION["role"] != "CM" && $_SESSION["role"] != "Responsable") {
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
            <?php if ($_SESSION["role"]=="Directeur" || $_SESSION['role'] =="CM"){echo '<li><a href="../index.php">Admin</a></li>';}?>            <li><a href="../../index.php">Accueil</a></li>
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
<h1 class="title">Modifier un personnel</h1>

<?php


Include("role.php") ;


if (isset($_POST['modifier'])) {
    echo "Modification du personnel";

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $role = $_POST['role'];
    echo $id;
    echo $nom;
    echo $prenom;
    echo $role;

    $requete = "UPDATE Personnel SET Numero_SS='$id', Nom='$nom', Prenom='$prenom' WHERE Numero_SS='$id'";
    mysqli_query($conn, $requete);

    if ($role != chercherRole($id)){
        echo chercherRole($id) ;
    switch (chercherRole($id)) {

        case 'CM':
            $requete = "DELETE FROM CM WHERE Numero_SS = '$id'";
            break;
        case 'Technicien':
            $requete = "DELETE FROM Technicien WHERE Numero_SS = '$id'";
            break;
        case 'Employe':
            $requete = "DELETE FROM Employe WHERE Numero_SS = '$id'";
            break;
        case 'Responsable':
            $requete = "DELETE FROM Responsable WHERE Numero_SS = '$id'";
            break;
        default:
            break;
    }
    switch ($role) {
        case 'CM':
            $requete = "INSERT INTO CM (Numero_SS) VALUES ('$id') ON DUPLICATE KEY UPDATE Numero_SS='$id'";
            break;
        case 'Technicien':
            $requete = "INSERT INTO Technicien (Numero_SS) VALUES ('$id') ON DUPLICATE KEY UPDATE Numero_SS='$id'";
            break;
        case 'Employe':
            $requete = "INSERT INTO Employe (Numero_SS) VALUES ('$id') ON DUPLICATE KEY UPDATE Numero_SS='$id'";
            break;
        case 'Responsable':
            $requete = "INSERT INTO Responsable (Numero_SS) VALUES ('$id') ON DUPLICATE KEY UPDATE Numero_SS='$id'";
            break;
        default:
            $requete = "INSERT INTO Technicien (Numero_SS) VALUES ('$id') ON DUPLICATE KEY UPDATE Numero_SS='$id'";

            break;
    }
    mysqli_query($conn, $requete);
}
    $_SESSION["Message"] = "Le personnel a été modifié avec succès.";
    header("Location: index.php");
    exit();
}else{
        $id = $_GET['id'];
        $requete = "SELECT * FROM Personnel WHERE Numero_SS = '$id'";
        $resultat = mysqli_query($conn, $requete);
        $donnees = mysqli_fetch_assoc($resultat);
    }

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Modifier un personnel</title>
    </head>
<body>

    <h1>Modifier un personnel</h1>
    <?php echo $id;?>
    <?php echo chercherRole($id);?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="id" value="<?php echo $donnees['Numero_SS']; ?>">
    Nom : <input type="text" name="nom" value="<?php echo $donnees['Nom']; ?>"><br><br>
    Prénom : <input type="text" name="prenom" value="<?php echo $donnees['Prenom']; ?>"><br><br>
    Rôle :
    <select name="role">
    <option value="cm"<?php if (chercherRole($id)=="CM") { echo ' selected'; } ?>>CM</option>
    <option value="technicien"<?php if (chercherRole($id)=="Technicien") { echo ' selected'; }
    ?>>Technicien</option>
    <option value="employe"<?php if (chercherRole($id)=="employe" || chercherRole($id)=="Unknown") { echo ' selected'; } ?>>Employé</option>
    <option value="responsable"<?php if (chercherRole($id)=="Responsable") { echo ' selected'; } ?>>Responsable</option>
    </select><br><br>
    <input type="submit" name="modifier" value="Modifier">
</form>
