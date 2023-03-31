<?php
function connex($base,$param)
{

    include_once($param.".inc.php");
    $idcom = mysqli_connect(MYHOST, MYUSER, MYPASS, $base);
    if (!$idcom) {
        echo "<script type='text/javascript'>";
        echo "alert('Connexion Impossible à la base $base')</script>";
    }
    else {
        mysqli_set_charset($idcom, "utf8mb4");
        echo "<script type='text/javascript'>";
        echo "alert('Connexion réussie à la base $base')</script>";
    }
    return $idcom;
}

?>
