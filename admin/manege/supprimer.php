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


$id_manege = $_GET['id_manege'];

$sql = "DELETE FROM manege WHERE Id_manege = $id_manege";

if (mysqli_query($conn, $sql)) {
    $_SESSION["Message"] = "Le manège a été supprimé avec succès.";
} else {
    $_SESSION["Message"] = "Erreur lors de la suppression du manège: " . mysqli_error($conn);
}

header("Location: index.php");
mysqli_close($conn);
?>