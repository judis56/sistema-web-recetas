<?php
// Cambia este correo al tuyo real
$to = "rmi78590@gmail.com";

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = strip_tags(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    // Validaciones básicas
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor completa correctamente todos los campos.";
        exit;
    }

    // Asunto y cuerpo del correo
    $subject = "Nuevo mensaje de contacto de $name";
    $body = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";

    $headers = "From: $name <$email>";

    // Intenta enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        echo "Tu mensaje ha sido enviado con éxito.";
    } else {
        http_response_code(500);
        echo "No se pudo enviar el mensaje. Intenta de nuevo más tarde.";
    }
} else {
    http_response_code(403);
    echo "Error en el envío del formulario.";
}
?>
