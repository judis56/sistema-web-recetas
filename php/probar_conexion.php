<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("conexiones.php");

$result = $conn->query("SELECT * FROM usuarios");

if (!$result) {
    echo "❌ Error en la consulta: " . $conn->error;
} else {
    while ($row = $result->fetch_assoc()) {
        echo "✔️ Usuario: " . $row['nombre'] . " (" . $row['correo'] . ")<br>";
    }
}
?>
