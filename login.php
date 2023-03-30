<?php

Include("connex.inc.php") ;
$idcom=connex("sae4", "param.wamp") ;


session_start();

if (isset($_POST['submit'])) {

    $ssn = $_POST['ssn'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM Personnel WHERE Numero_SS = '$ssn' AND Mot_de_passe = '$password'";

    $result = mysqli_query($idcom, $sql);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['id'] = session_id();
        $_SESSION['ssn'] = $ssn;
        // Génère une clé de session unique


        echo 'Vous êtes connecté.';
        $row = mysqli_fetch_assoc($result);

  $_SESSION['nom_utilisateur'] = $row["Nom"];
        header('Location: dashboard.php');

    } else {


        echo 'Le numéro de sécurité sociale ou le mot de passe est incorrect.';
    }
}

?>