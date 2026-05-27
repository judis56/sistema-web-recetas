<?php
header("Content-Type: text/html; charset=UTF-8");

$user = 'root';
$password = '';
$db = 'hotcakes';
$host = 'localhost';
$port = '3306';

$conn = new mysqli($host, $user, $password, $db, $port);

// Verifica si hay error de conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Fijar codificación de caracteres
$conn->set_charset("utf8");
?>
