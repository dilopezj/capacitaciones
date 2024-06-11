<?php
include 'conexion.php';

// Iniciar sesión
session_start();
// Verifica si se recibieron los datos del estudiante y la asistencia
if (isset($_POST['studentId']) && isset($_POST['attendance']) && isset($_POST['testId'])) {

    // Escapa los datos para evitar inyección SQL
    $studentId = $_POST['studentId'];
    $testId = $_POST['testId'];
    $attendance = $_POST['attendance'];

    // Prepara la consulta SQL para actualizar la asistencia del estudiante en la base de datos
    $sql = "UPDATE examenes_asignados SET  asistencia = $attendance , fecha_asistencia = CURRENT_TIMESTAMP WHERE id_estudiante = $studentId and id_examen = $testId ";
    $conn->query($sql);
   
    echo "Asistencia actualizada correctamente";   

    // Cierra la conexión
    $conn->close();
} else {
    // Si no se recibieron los datos adecuados, muestra un mensaje de error
    echo "Error: Falta información de estudiante o asistencia";
}
?>
