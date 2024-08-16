<?php
// Include the database connection
include 'conexion.php';

// Verificar si se recibió el ID del salón
if (isset($_POST['salon_id'])) {
    // Obtener el ID del salón enviado desde la solicitud AJAX
    $salon_id = $_POST['salon_id'];

    // Consulta SQL para obtener la capacidad del salón
    $sql = "SELECT capacidad FROM salones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $salon_id);
    $stmt->execute();
    $stmt->bind_result($capacidad);
    $stmt->fetch();

    // Liberar los resultados de la consulta anterior
    $stmt->free_result();

    // Consulta SQL para contar la cantidad de exámenes asignados al salón
    $sql_cantidad = "SELECT COUNT(*) AS cantidad FROM examenes_asignados WHERE salon = ?";
    $stmt_cantidad = $conn->prepare($sql_cantidad);
    $stmt_cantidad->bind_param("i", $salon_id);
    $stmt_cantidad->execute();
    $stmt_cantidad->bind_result($cantidad);
    $stmt_cantidad->fetch();

    // Comparar la cantidad con la capacidad del salón
    $msn = ($cantidad >= $capacidad) ? true : false;

    // Crear un array asociativo con la capacidad del salón y el mensaje
    $response = array('capacidad' => $cantidad, 'msn' => $msn);

    // Devolver la respuesta como JSON
    echo json_encode($response);

    // Cerrar las declaraciones
    $stmt->close();
    $stmt_cantidad->close();
} else {
    // Si no se recibió el ID del salón, devolver un mensaje de error
    echo json_encode(array('error' => 'No se proporcionó el ID del salón.'));
}
?>