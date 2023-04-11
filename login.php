<?php

Include("connex.inc.php");
Include("myparam.inc.php");

$idcom = connex(MYBASE, "myparam");
session_start();

function chercherRole($numero_ss) {

    Include("myparam.inc.php") ;;
    $conn=connex(MYBASE, "myparam") ;

    $sql = "SELECT * FROM directeur WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "directeur";
    }

    $sql = "SELECT * FROM cm WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "cm";
    }

    $sql = "SELECT * FROM technicien WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "technicien";
    }

    $sql = "SELECT * FROM responsable WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "responsable";
    }

    $sql = "SELECT * FROM employe WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "employe";
    }

}


if (isset($_POST['submit'])) {

    $ssn = $_POST['ssn'];
    $password = $_POST['password'];
    $hashed_password = hash('sha256', $password);

    $stmt = mysqli_prepare($idcom, "SELECT * FROM personnel WHERE Numero_SS = ? AND Mot_de_passe = ?");
    mysqli_stmt_bind_param($stmt, "ss", $ssn, $hashed_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['id'] = session_id();
        $_SESSION['ssn'] = $ssn;

        echo 'Vous êtes connecté.';
        $row = mysqli_fetch_assoc($result);
        $_SESSION['nom_utilisateur'] = $row["Nom"];
        $_SESSION['nom'] = $row["Nom"];
        $_SESSION['prenom'] = $row["Prenom"];
        $_SESSION['numero_ss'] = $row["Numero_SS"];
        $_SESSION['role'] = chercherRole($row["Numero_SS"]);

        if ($_SESSION['role'] == "cm") {
            $stmt = mysqli_prepare($idcom, "SELECT id_cm FROM cm WHERE Numero_SS = ?");
            mysqli_stmt_bind_param($stmt, "s", $row["Numero_SS"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['id_cm'] = $row["id_cm"];
        }

        if ($_SESSION['role'] == "responsable") {
            $stmt = mysqli_prepare($idcom, "SELECT * FROM responsable WHERE Numero_SS = ?");
            mysqli_stmt_bind_param($stmt, "s", $row["Numero_SS"]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $_SESSION['id_boutique'] = $row["id_boutique"];
            $_SESSION['id_responsable'] = $row["Id_responsable"];
            echo $_SESSION['id_boutique'];
            echo $_SESSION['id_responsable'];
        }
        header('Location: dashboard.php');
    } else {
        echo 'Le numéro de sécurité sociale ou le mot de passe est incorrect.';
    }
}
mysqli_close($idcom);
?>
