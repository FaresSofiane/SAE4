<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] != "directeur") {
    header("Location: ../index.php");
    exit();
}
Include("../../connex.inc.php") ;
Include("../../myparam.inc.php");
$conn=connex(MYBASE, "../../myparam") ;

Include("role.php") ;

$role = chercherRole($_GET["id"]);
$id = $_GET["id"];

switch ($role) {
    case 'cm':
        $requete = "DELETE FROM cm WHERE Numero_SS = '$id'";
        break;
    case 'technicien':
        $requete = "DELETE FROM technicien WHERE Numero_SS = '$id'";
        break;
    case 'employe':
        $requete = "DELETE FROM employe WHERE Numero_SS = '$id'";
        break;
    case 'responsable':
        $requete = "DELETE FROM responsable WHERE Numero_SS = '$id'";
        break;

}
if (!mysqli_query($conn, $requete)) {
    die('Erreur de suppression : ' . mysqli_error($conn));
}

if (!mysqli_query($conn, "DELETE FROM personnel WHERE Numero_SS = '$id'")) {
    die('Erreur de suppression : ' . mysqli_error($conn));
}
$_SESSION["Message"] = "Le personnel a été supprimé avec succès.";
header("Location: index.php");
mysqli_close($conn);
?>