<?php
function chercherRole($numero_ss) {



    $conn=connex(MYBASE, "../../myparam") ;

    $sql = "SELECT * FROM Directeur WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Directeur";
    }

    $sql = "SELECT * FROM CM WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "CM";
    }

    $sql = "SELECT * FROM Technicien WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Technicien";
    }

    $sql = "SELECT * FROM Responsable WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Responsable";
    }

    $sql = "SELECT * FROM Employe WHERE Numero_SS = '$numero_ss'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "Employe";
    }


}
?>