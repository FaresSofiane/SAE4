<?php

Include("connex.inc.php") ;
$idcom=connex("sae4", "param.wamp") ;

function chercherRole($numero_ss) {


    $conn=connex("sae4", "param.wamp") ;

    // Check if the personnel is a Directeur
    $sql = "SELECT * FROM Directeur WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Directeur";
    }

    // Check if the personnel is a CM
    $sql = "SELECT * FROM CM WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "CM";
    }

    // Check if the personnel is a Technicien
    $sql = "SELECT * FROM Technicien WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Technicien";
    }

    // Check if the personnel is a Responsable
    $sql = "SELECT * FROM Responsable WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Responsable";
    }

    // Check if the personnel is an Employe
    $sql = "SELECT * FROM Employe WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Employe";
    }

    // If the personnel is not found in any of the tables, return "Unknown"
    return "Unknown";
}

session_start();

if (isset($_POST['submit'])) {

    $ssn = $_POST['ssn'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM Personnel WHERE Numero_SS = '$ssn' AND Mot_de_passe = '$password'";

    $result = mysqli_query($idcom, $sql);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['id'] = session_id();
        $_SESSION['ssn'] = $ssn;
        // Génère une clé de session unique


        echo 'Vous êtes connecté.';
        $row = mysqli_fetch_assoc($result);
        $_SESSION['nom_utilisateur'] = $row["Nom"];
        $_SESSION['nom'] = $row["Nom"];
        $_SESSION['prenom'] = $row["Prenom"];
        $_SESSION['numero_ss'] = $row["Numero_SS"];
        $_SESSION['role'] = chercherRole($row["Numero_SS"]);
        if ($_SESSION['role'] == "CM") {
            $sql = "SELECT id_cm FROM CM WHERE Numero_SS = '".$row["Numero_SS"]."'";
        $result = mysqli_query($idcom, $sql);
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id_cm'] = $row["id_cm"];
        }

        if ($_SESSION['role'] == "Responsable") {
            $sql = "SELECT * FROM Responsable WHERE Numero_SS = '".$row["Numero_SS"]."'";
            $result = mysqli_query($idcom, $sql);
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
mysqli_close($idcom); // Fermer la connexion à la base de données
?>