<?php

session_start();

if (!isset($_SESSION["nom_utilisateur"])) {
    header("Location: ../../index.php");
    exit();
}

if ($_SESSION["role"] != "Directeur") {
    header("Location: ../index.php");
    exit();
}
Include("../../connex.inc.php") ;
$conn=connex("sae4", "../../param.wamp") ;


// Récupération de l'ID du manège à supprimer
$id_manege = $_GET['id_manege'];

// Requête SQL pour supprimer le manège correspondant à l'ID
$sql = "DELETE FROM Manege WHERE Id_manege = $id_manege";

if (mysqli_query($conn, $sql)) {
    $_SESSION["Message"] = "Le manège a été supprimé avec succès.";
} else {
    $_SESSION["Message"] = "Erreur lors de la suppression du manège: " . mysqli_error($conn);
}

// Redirection vers la page de gestion des manèges
header("Location: index.php");
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>