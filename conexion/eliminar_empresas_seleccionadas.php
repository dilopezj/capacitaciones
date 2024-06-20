<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Obtener los NITs a eliminar desde la solicitud POST
$ids = isset($_POST['ids']) ? $_POST['ids'] : '';

// Validar que se recibieron los NITs
if (!empty($ids)) {
    // Convertir la cadena de NITs separados por coma en un array
    $idsArray = explode(',', $ids);

    // Construir los marcadores de posición para la consulta de contar estudiantes
    $placeholders = implode(',', array_fill(0, count($idsArray), '?'));

    // Consulta para contar los estudiantes asociados a las empresas
    $sqlVerificarEstudiantes = "SELECT COUNT(*) as total FROM estudiantes WHERE id_empresa IN ($placeholders)";

    // Preparar la sentencia
    $stmtVerificar = $conn->prepare($sqlVerificarEstudiantes);

    if ($stmtVerificar === false) {
        die('Error al preparar la consulta SQL: ' . $conn->error);
    }

    // Vincular los parámetros
    $types = str_repeat('s', count($idsArray));
    $stmtVerificar->bind_param($types, ...$idsArray);

    // Ejecutar la consulta
    if (!$stmtVerificar->execute()) {
        die('Error al ejecutar la consulta SQL: ' . $stmtVerificar->error);
    }

    // Obtener el resultado
    $result = $stmtVerificar->get_result();
    $row = $result->fetch_assoc();
    $totalEstudiantes = $row['total'];

    // Cerrar la sentencia de contar estudiantes
    $stmtVerificar->close();

    if ($totalEstudiantes > 0) {
        echo 'No se puede eliminar la(s) empresa(s) seleccionada(s) porque tiene(n) estudiantes asociados.';
    } else {
        // Preparar la consulta para eliminar las empresas
        $placeholdersDelete = rtrim(str_repeat('?,', count($idsArray)), ',');
        $sqlEliminar = "DELETE FROM empresas WHERE nit IN ($placeholdersDelete)";

        // Preparar la sentencia
        $stmtEliminar = $conn->prepare($sqlEliminar);

        if ($stmtEliminar === false) {
            die('Error al preparar la consulta SQL para eliminar: ' . $conn->error);
        }

        // Vincular los parámetros para la eliminación
        $stmtEliminar->bind_param($types, ...$idsArray);

        // Ejecutar la consulta para eliminar las empresas
        if ($stmtEliminar->execute()) {
            echo 'Empresas eliminadas correctamente.';
        } else {
            echo 'Hubo un problema al eliminar las empresas: ' . $stmtEliminar->error;
        }

        // Cerrar la sentencia de eliminar
        $stmtEliminar->close();
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo 'No se recibieron los NITs a eliminar.';
}
?>
