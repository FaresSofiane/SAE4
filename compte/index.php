<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../index.php");
    exit();
}


Include("../connex.inc.php") ;
Include("../myparam.inc.php");
$conn=connex(MYBASE, "../myparam") ;


?>



<html>
<head>

    <title>Parc d'attraction</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/compte/styles.css">
    <link rel="stylesheet" type="text/css" href="assets/font/Source_Sans_Pro/font.css">
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
            <?php if ($_SESSION["role"]=="directeur" || $_SESSION['role'] =="cm" || $_SESSION['role'] == "responsable"){echo '<li><a href="../admin">Admin</a></li>';}?>

            <li><a href="../index.php">Accueil</a></li>
            <li><a href="#">Vente</a></li>
            <li><a href="../manege">Manege</a></li>
            <li class="dropdown">
                <a href="#"><?php echo $_SESSION["nom_utilisateur"]?></a>
                <div class="dropdown-content">
                    <a href="index.php">Mon compte</a>
                    <a href="../deconnection.php" class="deconnexion-btn">Déconnexion</a>

                </div>
            </li>
        </ul>
    </nav>
</div>

<div class="bloc">
    <div class="container">
        <div class="Haut">
            <h1>Mon Compte</h1>
            <center><a><?php echo 'Bonjour '.$_SESSION["prenom"].' '.$_SESSION["nom"].' !' ?></a></center>
        </div>
        <div class="Bas">
            <div class="bouton">
                <div class="bouton-center">
                    <br>
                    <br><br>
                    <br>
                    <br>


                <a id="password_btn">Mot de passe</a>
                <br>
                <a id="user_btn">Mes informations personnelles</a>
                    <?php
                    if (isset($_SESSION["error_message"]) && !empty($_SESSION["error_message"])) {
                        echo $_SESSION["error_message"];

                        $_SESSION["error_message"] = "";
                    }?>
                    </div>
            </div>
            <div class="input">
                <div class="password">
                    <h1>Mot de passe</h1>
                    <br>
                    <a>Pour changer votre mot de passe, merci de saisir votre ancien mot de passe et le nouveau mot de passe puis de confirmer</a>

                    <br>
                    <form action="update_password.php" method="post">
                        <input type="password" name="oldpassword" placeholder="Ancien mot de passe" required>
                        <br>
                        <input type="password" name="newpassword" placeholder="Nouveau mot de passe" required>
                        <br>
                        <input type="password" name="confirmpassword" placeholder="Confirmer le mot de passe" required>
                        <br>
                        <input class="bouton" type="submit" value="Changer le mot de passe">
                    </form>

                </div>
                <div class="user">
                    <h1>Mes informations personnelles</h1>
                    <?php echo $_SESSION["id_boutique"]?>

                    <br>
                    <a>Vous pouvez modifier vos informations personnelles ici</a>
                    <br>
                    <form action="update_user.php" method="post">
                        <input type="text" name="ss" placeholder="Numéro de sécurité sociale" value="<?php echo $_SESSION["numero_ss"]?>" pattern="[0-9-]+" required>
                        <br>
                        <input type="text" name="nom" placeholder="Nom" value="<?php echo $_SESSION["nom"]?>" required>
                        <br>
                        <input type="text" name="prenom" placeholder="Prénom" value="<?php echo $_SESSION["prenom"]?>" required>
                        <br>
                        <input class="bouton" type="submit" value="Modifier">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>



</html>

<script>


    const passwordBtn = document.getElementById("password_btn");
    const userBtn = document.getElementById("user_btn");
    const passwordDiv = document.querySelector(".password");
    const userDiv = document.querySelector(".user");

    userDiv.style.display = "none";

    passwordBtn.addEventListener("click", () => {
        passwordDiv.style.display = "inline-block";
        userDiv.style.display = "none";
    });

    userBtn.addEventListener("click", () => {
        passwordDiv.style.display = "none";
        userDiv.style.display = "inline-block";
    });
</script>

