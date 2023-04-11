<?php
function chercherRole($numero_ss) {



    $conn=connex(MYBASE, "../../myparam") ;

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
?>