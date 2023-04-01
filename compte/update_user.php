<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../connex.inc.php';
require_once '../myparam.inc.php';

if(isset($_POST['ss']) && isset($_POST['nom']) && isset($_POST['prenom'])) {
    $ss = htmlspecialchars($_POST['ss']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $idcom = connex(MYBASE, "../myparam");

    // Protection contre l'injection SQL
    $ss = mysqli_real_escape_string($idcom, $ss);
    $nom = mysqli_real_escape_string($idcom, $nom);
    $prenom = mysqli_real_escape_string($idcom, $prenom);

    $requete = "UPDATE Personnel SET Numero_SS='$ss', Nom='$nom', Prenom='$prenom' WHERE Numero_SS='" . $_SESSION['numero_ss'] . "'";
    $resultat = mysqli_query($idcom, $requete);

    if($resultat) {
        $_SESSION['numero_ss'] = $ss;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['$error_message'] =  "Informations mises à jour avec succès.";
    } else {
        $_SESSION['$error_message'] = "Erreur lors de la mise à jour des informations.";
    }

    mysqli_close($idcom);
}
header("Location: index.php");
?>