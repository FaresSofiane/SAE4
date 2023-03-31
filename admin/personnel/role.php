<?php
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
?>