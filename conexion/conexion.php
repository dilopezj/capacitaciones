<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$servername = "localhost"; // Cambiar al nombre de tu servidor MySQL si es diferente
$username = "sigpetai_evaluador"; // Cambiar al nombre de usuario de tu base de datos
$password = "C4p4c1t4c10n3s2024"; // Cambiar a la contraseña de tu base de datos
$database = "sigpetai_eva"; // Cambiar al nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
