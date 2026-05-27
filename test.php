<?php
$to = "d20ce082@cenidet.tecnm.mx"; // Cambia por tu correo
$subject = "Prueba de la función mail()";
$message = "Este es un correo de prueba enviado directamente con la función mail() en PHP.";
$headers = "From: neohotcake@hotcakes.x10.mx";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ La función mail() está habilitada y el mensaje fue enviado.";
} else {
    echo "❌ La función mail() no está habilitada o falló el envío.";
}
?>

