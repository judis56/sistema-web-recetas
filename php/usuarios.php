<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("conexiones.php");

header('Content-Type: application/json');

$sql = "SELECT nombre, correo, celular FROM usuarios";
$resultado = $conn->query($sql);

$usuarios = [];

while ($fila = $resultado->fetch_assoc()) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);
?>
