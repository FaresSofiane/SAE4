<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../connex.inc.php';
require_once '../param.wamp.inc.php';
$idcom = connex(MYBASE, "../param.wamp");

// Récupérer les données du formulaire
$ss = $_SESSION['numero_ss'];
$ancienmotdepasse = $_POST['oldpassword'];
$nouveaumotdepasse = $_POST['newpassword'];
$nouveaumotdepasse2 = $_POST['confirmpassword'];

// Vérifier que les champs ne sont pas vides
if (empty($ss) || empty($ancienmotdepasse) || empty($nouveaumotdepasse) || empty($nouveaumotdepasse2)) {
    $error_message = "Tous les champs sont requis.";
} else {

    // Vérifier que le nouveau mot de passe correspond à la confirmation
    if ($nouveaumotdepasse !== $nouveaumotdepasse2) {
        $error_message = "Le nouveau mot de passe ne correspond pas à la confirmation.";
    } else {

        // Vérifier que l'ancien mot de passe est correct
        $sql_check ="SELECT Mot_de_passe FROM Personnel WHERE Numero_SS = '$ss' AND Mot_de_passe = '$ancienmotdepasse'";
        $result = mysqli_query($idcom, $sql_check);

        if (mysqli_num_rows($result) > 0) {

            // Mettre à jour le mot de passe de l'utilisateur
            $sql_update= "UPDATE Personnel SET Mot_de_passe = '$nouveaumotdepasse' WHERE Numero_SS = '$ss' AND Mot_de_passe = '$ancienmotdepasse'";
            $result = mysqli_query($idcom, $sql_update);

            // Rediriger vers index.php si la mise à jour a été effectuée avec succès
            if ($result) {
                $error_message = "Le mot de passe a été mis à jour avec succès.";
            } else {
                $error_message = "La mise à jour du mot de passe a échoué.";
            }

        } else {
            $error_message = "Le mot de passe actuel est incorrect.";
        }
    }
}
$_SESSION['error_message'] = $error_message;
mysqli_close($idcom); // Fermer la connexion à la base de données
header("Location: index.php");

?>
