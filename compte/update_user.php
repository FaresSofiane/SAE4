<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../connex.inc.php';
require_once '../param.wamp.inc.php';

if(isset($_POST['ss']) && isset($_POST['nom']) && isset($_POST['prenom'])) {
    // Vérifier si les champs requis sont remplis
    $ss = htmlspecialchars($_POST['ss']); // Échapper les caractères spéciaux
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $idcom = connex(MYBASE, "../param.wamp");

    // Mettre à jour les informations de l'utilisateur
    $requete = "UPDATE Personnel SET Numero_SS='$ss', Nom='$nom', Prenom='$prenom' WHERE Numero_SS='$_SESSION[numero_ss]'";
    $resultat = mysqli_query($idcom, $requete);

    if($resultat) {
        // Mettre à jour les variables de session
        $_SESSION['numero_ss'] = $ss;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['$error_message'] =  "Informations mises à jour avec succès.";
    } else {
        $_SESSION['$error_message'] = "Erreur lors de la mise à jour des informations.";
    }

    mysqli_close($idcom); // Fermer la connexion à la base de données
}
header("Location: index.php");

?>
