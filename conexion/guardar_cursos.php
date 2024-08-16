<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// Recibir datos del formulario utilizando FormData
$curso = $_POST['curso'];
$vigencia = $_POST['vigencia'];

// Consulta SQL para insertar datos en la tabla de modulos
$sql = "INSERT INTO modulos (nombre, fecha_vigencia) VALUES (?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);

// Vincular parámetros
$stmt->bind_param("ss", $curso, $vigencia);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Nuevo curso creado exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
