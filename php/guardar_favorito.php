<?php
header("Content-Type: application/json");

// Obtener los datos enviados por el cliente
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["nombre"];
$id_receta = $data["id_receta"];

// Conectar a la base de datos
include("conexiones.php");

// Verificar si ya existe este favorito
$consulta = "SELECT * FROM favoritos WHERE nombre = ? AND id_receta = ?";
$stmt = $conn->prepare($consulta);
$stmt->bind_param("si", $nombre, $id_receta);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode(["status" => "existe"]);
} else {
    // Insertar nuevo favorito
    $insertar = "INSERT INTO favoritos (nombre, id_receta) VALUES (?, ?)";
    $stmt = $conn->prepare($insertar);
    $stmt->bind_param("si", $nombre, $id_receta);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "ok"]);
    } else {
        echo json_encode(["status" => "error", "mensaje" => $conn->error]);
    }
}

$conn->close();
?>

