<?php
include("conexiones.php");

$data = json_decode(file_get_contents("php://input"), true);

$titulo = $conn->real_escape_string($data['titulo']);
$descripcion = $conn->real_escape_string($data['descripcion']);
$ingredientes = $conn->real_escape_string($data['ingredientes']);
$instrucciones = $conn->real_escape_string($data['instrucciones']);


$sql = "INSERT INTO recetas (titulo, descripcion, ingredientes, instrucciones)
        VALUES ('$titulo', '$descripcion', '$ingredientes', '$instrucciones')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => $conn->error]);
}
?>
