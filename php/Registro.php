<?php 
header("Content-Type: text/html;charset=utf-8");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$data = file_get_contents("php://input"); 
$objData = json_decode($data);

// Convertir nombre y apellidos a mayúsculas
$objData->nombre = strtoupper($objData->nombre); 
$objData->ap = strtoupper($objData->ap); 
$objData->am = strtoupper($objData->am);
/*$objData->nombre= "Judith";
$objData->ap= "Enriquez";
$objData->am= "Aguirre";
$objData->correo= "ruiz6812@hotmail.com";*/
include("conexiones.php"); 

// Establezco la zona horaria
date_default_timezone_set('America/Monterrey');

// Generar una clave única de 4 dígitos
do {
  $clave = substr(str_shuffle("0123456789"), 0, 4);
  $result = $conn->query("SELECT * FROM usuarios WHERE clave='$clave'");
  $reg = $result->num_rows;
  $salir = ($reg > 0) ? 0 : 1;
} while ($salir < 1);

// Verificar que el correo no exista ya
$result = $conn->query("SELECT * FROM usuarios WHERE correo='".$objData->correo."'");
$reg = $result->num_rows;

if ($reg > 0) {
  echo 0;
} else {
  // Insertar nuevo usuario
  $sql = "INSERT INTO usuarios (nombre, ap, am, clave, correo, celular) 
          VALUES ('$objData->nombre', '$objData->ap', '$objData->am', '$clave', '$objData->correo', '$objData->celular')";
  $conn->query($sql);

  // ✅ Enviar correo de confirmación
  try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rmi78590@gmail.com';            // ✅ TU GMAIL
    $mail->Password   = 'iwrr mfma uiol xvkm';       // ✅ CONTRASEÑA DE APLICACIÓN
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('rmi78590@gmail.com', 'Hotcakes App');
    $mail->addAddress($objData->correo); // destinatario

   $mail->isHTML(true);
$mail->Subject = '🎉 ¡Bienvenid@ a Hotcakes App! Tu registro fue exitoso 🥞';

$mail->Body = '
<html>
<head>
  <style>
    body {
      background-color: #fffaf3;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      color: #4b2e2e;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background-color: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .header {
      background-color: #ffcc99;
      padding: 30px 20px;
      text-align: center;
    }
    .header img {
      width: 120px;
      border-radius: 12px;
      margin-bottom: 10px;
    }
    .header h1 {
      margin: 0;
      font-size: 28px;
      color: #8b4513;
    }
    .content {
      padding: 25px 30px;
    }
    .content h2 {
      font-size: 22px;
      color: #d2691e;
    }
    .content p {
      font-size: 16px;
      line-height: 1.6;
    }
    .highlight-box {
      background-color: #fff3e0;
      border: 1px dashed #ffa726;
      border-radius: 10px;
      padding: 15px;
      margin: 20px 0;
    }
    .button {
      display: inline-block;
      padding: 12px 24px;
      background-color: #ff9800;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      text-decoration: none;
      margin-top: 20px;
    }
    .footer {
      text-align: center;
      padding: 20px;
      font-size: 14px;
      color: #a0522d;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="https://i.postimg.cc/W4NVpp2f/Pancake-Delicious-vector-for-National-Pancake-Day-Premium-Vector.jpg" alt="Hotcakes Logo" />
      <h1>¡Gracias por registrarte! 🥞</h1>
    </div>
    <div class="content">
      <h2>Hola '.$objData->nombre.' '.$objData->ap.' '.$objData->am.',</h2>
      <p>Estamos emocionados de tenerte con nosotros en <strong>Hotcakes App</strong>, la plataforma donde la experiencia digital se mezcla con el sabor más dulce. 🍯</p>

      <div class="highlight-box">
        <p><strong>🎁 Tus datos de acceso:</strong></p>
        <p><b>👤 Usuario:</b> '.$objData->correo.'</p>
        <p><b>🔐 Contraseña:</b> '.$clave.'</p>
      </div>

      <p>Desde ahora podrás hacer tus pedidos, explorar nuestros deliciosos productos y recibir promociones exclusivas directamente desde tu app favorita. ¡No olvides iniciar sesión y comenzar a saborear la experiencia!</p>

      <p style="text-align: center;">
        <a class="button" href="https://tusitio.com/login" target="_blank">Iniciar sesión ahora</a>
      </p>
    </div>
    <div class="footer">
      🥞 Hecho con cariño por el equipo de Hotcakes App <br/>
      ¡Cada día es mejor con un poco de sirope!
    </div>
  </div>
</body>
</html>';


    $mail->AltBody = 'This is a plain-text message body';
    $mail->send();
    echo 1;
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "mensaje" => "No se pudo enviar el correo. ".$mail->ErrorInfo]);
  }
}

$conn->close();
?>
