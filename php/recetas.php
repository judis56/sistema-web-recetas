
<?php
include("conexiones.php");

$result = $conn->query("SELECT * FROM recetas");

$recetas = array();
while ($row = $result->fetch_assoc()) {
    $recetas[] = $row;
}

echo json_encode($recetas);
?>
