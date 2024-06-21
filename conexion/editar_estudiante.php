<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para actualizar datos del estudiante en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $idEstudiante = $_POST['idEstudianteE'];
    $empresa = $_POST['empresaE'];
    $tipo_documento = $_POST['tipo_documentoE'];
    $documento = $_POST['documentoE'];
    $nombres = $_POST['nombresE'];
    $apellidos = $_POST['apellidosE'];
    $genero = $_POST['generoE'];
    $cargo = $_POST['cargoE'];
    $celular = $_POST['celularE'];
    $correo = $_POST['correoE'];

    // Preparar la consulta SQL para actualizar el estudiante
    $sqlEstudiante = "UPDATE estudiantes SET id_empresa = ?, tipo_identificacion = ?, id_estudiante = ?, nombre = ?, apellido = ?, genero = ?, cargo = ?, celular = ?, correo = ? WHERE id_estudiante = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sqlEstudiante);

    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt === false) {
        die('Error al preparar la consulta SQL: ' . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("issssssssi", $empresa, $tipo_documento, $documento, $nombres, $apellidos, $genero, $cargo, $celular, $correo, $idEstudiante);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Estudiante actualizado exitosamente";
    } else {
        echo "Error al actualizar el estudiante: " . $stmt->error;
    }

    // Cerrar la sentencia
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
