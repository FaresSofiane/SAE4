<?php

$id = $_GET['id'];

require '../connex.inc.php';
require_once '../param.wamp.inc.php';
$conn = connex(MYBASE, "../param.wamp");

$nom_cm = "SELECT p.Nom, p.Prenom
FROM Personnel p
         JOIN CM c ON c.Numero_SS = p.Numero_SS
         JOIN Manege m ON m.Id_cm = c.id_cm
WHERE m.Id_manege = $id;
";

$reparation = "SELECT r.Date_reparation, t.Numero_SS
FROM Reparation r
JOIN Technicien t ON t.id_technicien = r.Id_technicien
WHERE r.Id_manege = $id
";

$zone="SELECT z.Nom_zone
FROM Zone z
JOIN Manege m ON m.Id_zone = z.Id_zone
WHERE m.Id_manege = $id";

$manege="select * from Manege where Id_manege = $id";

$resultat_cm = mysqli_query($conn, $nom_cm);
$resultat_reparation = mysqli_query($conn, $reparation);
$resultat_zone = mysqli_query($conn, $zone);
$resultat_man = mysqli_query($conn, $manege);

if ($resultat_cm->num_rows > 0) {
    while($row = $resultat_cm->fetch_assoc()) {

        echo "Nom et prénom du CM qui le gère: " . $row["Nom"] . " " . $row["Prenom"] . "<br>";

    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_reparation->num_rows > 0) {
    while($row = $resultat_reparation->fetch_assoc()) {

        echo "Date de la réparation: " . $row["Date_reparation"] . "<br>";
        echo "Numéro de sécurité sociale du technicien: " . $row["Numero_SS"] . "<br>";

    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_zone ->num_rows > 0) {
    while($row = $resultat_zone->fetch_assoc()) {

        echo "Zone: " . $row["Nom_zone"] . "<br>";

    }
} else {
    echo "Aucun résultat trouvé.";
}

if ($resultat_man ->num_rows > 0) {
    while ($row = $resultat_man->fetch_assoc()) {

        echo "Nom du manège: " . $row["Nom_manege"] . "<br>";
        echo "Nombre de places: " . $row["Description"] . "<br>";
        echo "Hauteur: " . $row["Taille_min_client"] . "<br>";



    }
}


?>