<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// Recibir datos del formulario utilizando FormData
$curso = $_POST['curso'];
$examen = $_POST['examen'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];
$vigencia = $_POST['vigencia'];

// Valor para el campo activo
$activo = 1;

// Consulta SQL para insertar datos en la tabla de examenes
$sql = "INSERT INTO examenes (nombre_examen, id_modulo, descripcion, tipo_examen, fecha_vigencia, activo) VALUES (?, ?, ?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);

// Vincular parámetros
$stmt->bind_param("sssssi", $examen, $curso, $descripcion, $tipo, $vigencia, $activo);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Nueva evaluación creada exitosamente";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
