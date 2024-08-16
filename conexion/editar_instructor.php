<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para actualizar datos del instructor en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $idInstructor = $_POST['idInstructorE'];
    $regional = $_POST['regionalE'];
    $ciudad = $_POST['ciudadE'];
    $tipo_documento = $_POST['tipo_documentoE'];
    $documento = $_POST['documentoE'];
    $nombres = $_POST['nombresE'];
    $apellidos = $_POST['apellidosE'];
    
    // Preparar la consulta SQL para actualizar el instructor
    $sqlInstructor = "UPDATE instructores SET regional = ?, ciudad = ?, tipo_identificacion = ?, nombres = ?, apellidos = ? WHERE identificacion = ?";
    
    // Preparar la sentencia
    $stmt = $conn->prepare($sqlInstructor);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die('Error al preparar la consulta SQL: ' . $conn->error);
    }
    
    // Vincular los parámetros
    $stmt->bind_param("sssssi", $regional, $ciudad, $tipo_documento, $nombres, $apellidos, $idInstructor);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Instructor actualizado exitosamente";
    } else {
        echo "Error al actualizar el instructor: " . $stmt->error;
    }
    
    // Cerrar la sentencia
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
