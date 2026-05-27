<?php
include("conexiones.php");
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Decodificar la entrada
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->usuario) || empty($data->usuario)) {
    echo json_encode(["error" => "Falta el campo 'usuario'."]);
    exit;
}

$correo = $data->usuario;

// Verifica que la conexión exista
if (!$conexion) {
    echo json_encode(["error" => "No hay conexión a la base de datos"]);
    exit;
}

$sql = "SELECT recetas.* 
        FROM recetas 
        INNER JOIN favoritos ON recetas.id = favoritos.id_receta 
        WHERE favoritos.nombre = ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error al preparar la consulta SQL", "detalles" => $conexion->error]);
    exit;
}

$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

$favoritos = [];
while ($row = $resultado->fetch_assoc()) {
    $favoritos[] = $row;
}

echo json_encode($favoritos);
?>

