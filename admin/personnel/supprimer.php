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

Include("role.php") ;

$role = chercherRole($_GET["id"]);
$id = $_GET["id"];

// Suppression du personnel dans la table correspondante
switch ($role) {
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

}
if (!mysqli_query($conn, $requete)) {
    die('Erreur de suppression : ' . mysqli_error($conn));
}

// Suppression du personnel dans la table Personnel
if (!mysqli_query($conn, "DELETE FROM Personnel WHERE Numero_SS = '$id'")) {
    die('Erreur de suppression : ' . mysqli_error($conn));
}
// Redirection vers la page de gestion des manèges
$_SESSION["Message"] = "Le personnel a été supprimé avec succès.";
header("Location: index.php");
// Fermeture de la connexion à la base de données
mysqli_close($conn);
?>