<?php
$servername = "localhost"; // Cambiar al nombre de tu servidor MySQL si es diferente
$username = "root"; // Cambiar al nombre de usuario de tu base de datos
$password = ""; // Cambiar a la contraseña de tu base de datos
$database = "sigpetai_eva"; // Cambiar al nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>
