<?php
// Paramètres de connexion à la base de données
require 'connex.inc.php';
require_once 'param.wamp.inc.php';
$conn = connex(MYBASE, "param.wamp");




// Requête pour récupérer les informations sur les manèges
$sql = "SELECT Manege.Nom_manege, CM.Nom, CM.Prenom, Zone.Nom_zone, COUNT(Reparation.Id_reparation) AS nb_reparations
        FROM Manege
        INNER JOIN CM ON Manege.Id_cm = CM.id_cm
        INNER JOIN Zone ON Manege.Id_zone = Zone.Id_zone
        LEFT JOIN Reparation ON Manege.Id_manege = Reparation.Id_manege
        GROUP BY Manege.Id_manege";

$result = $conn->query($sql);

// Affichage des informations sur les manèges
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Nom du manège: " . $row["Nom_manege"] . "<br>";
        echo "Nom et prénom du CM qui le gère: " . $row["Nom"] . " " . $row["Prenom"] . "<br>";
        echo "Zone: " . $row["Nom_zone"] . "<br>";
        echo "Nombre de réparations effectuées: " . $row["nb_reparations"] . "<br><br>";
    }
} else {
    echo "Aucun résultat trouvé.";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
