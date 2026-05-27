<?php
include("conexiones.php");

if (isset($_GET['correo'])) {
    $correo = $_GET['correo'];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);

    if ($stmt->execute()) {
        echo "Usuario eliminado correctamente";
    } else {
        echo "Error al eliminar";
    }
} else {
    echo "Correo no proporcionado";
}
?>
