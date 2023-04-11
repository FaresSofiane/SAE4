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


$id_boutique = $_GET['id_boutique'];

$sql = "DELETE FROM boutique WHERE Id_boutique = '$id_boutique'";

if (mysqli_query($conn, $sql)) {
    $_SESSION["Message"] = "La boutique a été supprimé avec succès.";
} else {
    $_SESSION["Message"] = "Erreur lors de la suppression de la boutique: " . mysqli_error($conn);
}

header("Location: index.php");
mysqli_close($conn);
?>