<html>
    <head>
    <title>Parc d'attraction</title>
        <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
        <link rel="stylesheet" type="text/css" href="assets/font/Source_Sans_Pro/font.css">
    </head>
<body>

<?php
session_start();

if (session_status() === PHP_SESSION_ACTIVE)
{
    header('Location: dashboard.php');

}else{
    echo '<div id="login">

        <form action="login.php" method="post" name="login_form" class="login-form">
            <a class="input-text"> Numéro de sécurité sociale</a>
            <input type="text" name="ssn" required/>
            <br>
            <a class="input-text"> Mot de passe</a>
            <input type="password" name="password" id="password" required/>
            <br>
            <input type="submit" name="submit" value="Connexion" class="login-button" />
        </form>
    </div>';
        }
?>


</body>
</html>

