<?php
include("conexiones.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM recetas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Receta eliminada correctamente";
    } else {
        echo "Error al eliminar";
    }
} else {
    echo "ID no proporcionado";
}
?>
