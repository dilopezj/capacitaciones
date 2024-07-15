<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($_POST['date'], $_POST['studentId'], $_POST['moduleId'])) {
    $date = $_POST['date'];
    $studentId = $_POST['studentId'];
    $moduleId = $_POST['moduleId'];

    // Validate the input here (e.g., check date format)
    if (!DateTime::createFromFormat('Y-m-d', $date)) {
        echo json_encode(['error' => 'Invalid date format']);
        exit;
    }

    // SQL query to check for conflicting dates
    $sql = "SELECT COUNT(*) as count
            FROM examenes_asignados ea
            JOIN examenes e ON ea.id_examen = e.id_examen
            WHERE ea.id_estudiante = ? AND ea.fecha_programado = ? AND ea.id_modulo != ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $studentId, $date, $moduleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Determine if there is a conflict
    $hasConflict = $row['count'] > 0;

    // Return JSON response
    echo json_encode(['conflict' => $hasConflict]);
} else {
    echo json_encode(['conflict' => false]); // Default to no conflict if input is invalid
}

$conn->close();
?>
