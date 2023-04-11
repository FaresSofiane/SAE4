<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../connex.inc.php';
require_once '../myparam.inc.php';
$idcom = connex(MYBASE, "../myparam");

$ss = $_SESSION['numero_ss'];
$ancienmotdepasse = $_POST['oldpassword'];
$nouveaumotdepasse = $_POST['newpassword'];
$nouveaumotdepasse2 = $_POST['confirmpassword'];

if (empty($ss) || empty($ancienmotdepasse) || empty($nouveaumotdepasse) || empty($nouveaumotdepasse2)) {
    $error_message = "Tous les champs sont requis.";
} else {
    if ($nouveaumotdepasse !== $nouveaumotdepasse2) {
        $error_message = "Le nouveau mot de passe ne correspond pas à la confirmation.";
    } else {
        $hashed_password = hash('sha256', $nouveaumotdepasse);
        $hashed_password2 = hash('sha256', $ancienmotdepasse);
        $sql_check = "SELECT Mot_de_passe FROM personnel WHERE Numero_SS = ? AND Mot_de_passe = ?";
        $stmt = mysqli_prepare($idcom, $sql_check);
        mysqli_stmt_bind_param($stmt, "ss", $ss, $hashed_password2);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $sql_update = "UPDATE personnel SET Mot_de_passe = ? WHERE Numero_SS = ? AND Mot_de_passe = ?";
            $stmt = mysqli_prepare($idcom, $sql_update);
            mysqli_stmt_bind_param($stmt, "sss", $hashed_password, $ss, $hashed_password2);
            mysqli_stmt_execute($stmt);

            if (mysqli_affected_rows($idcom) > 0) {
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
mysqli_close($idcom);
header("Location: index.php");
?>
