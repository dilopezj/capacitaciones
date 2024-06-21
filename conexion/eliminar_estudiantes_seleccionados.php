<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Obtener los IDs de estudiantes a eliminar desde la solicitud POST
$ids = isset($_POST['ids']) ? $_POST['ids'] : '';

// Validar que se recibieron los IDs de estudiantes
if (!empty($ids)) {
    // Convertir la cadena de IDs separados por coma en un array
    $idsArray = explode(',', $ids);

    // Construir los marcadores de posición para la consulta de contar exámenes asignados
    $placeholders = implode(',', array_fill(0, count($idsArray), '?'));

    // Consulta para contar los exámenes asignados a los estudiantes
    $sqlVerificarExamenes = "SELECT COUNT(*) as total FROM examenes_asignados WHERE id_estudiante IN ($placeholders)";

    // Preparar la sentencia
    $stmtVerificarExamenes = $conn->prepare($sqlVerificarExamenes);

    if ($stmtVerificarExamenes === false) {
        die('Error al preparar la consulta SQL para verificar exámenes asignados: ' . $conn->error);
    }

    // Vincular los parámetros
    $types = str_repeat('i', count($idsArray)); // 'i' para IDs de tipo entero
    $stmtVerificarExamenes->bind_param($types, ...$idsArray);

    // Ejecutar la consulta
    if (!$stmtVerificarExamenes->execute()) {
        die('Error al ejecutar la consulta SQL para verificar exámenes asignados: ' . $stmtVerificarExamenes->error);
    }

    // Obtener el resultado
    $resultExamenes = $stmtVerificarExamenes->get_result();
    $rowExamenes = $resultExamenes->fetch_assoc();
    $totalExamenes = $rowExamenes['total'];

    // Cerrar la sentencia de contar exámenes asignados
    $stmtVerificarExamenes->close();

    if ($totalExamenes > 0) {
        echo 'No se puede eliminar el/los estudiante(s) seleccionado(s) porque tienen exámenes asignados.';
    } else {
        // Preparar la consulta para eliminar los estudiantes
        $placeholdersDelete = rtrim(str_repeat('?,', count($idsArray)), ',');
        $sqlEliminarEstudiantes = "DELETE FROM estudiantes WHERE id_estudiante IN ($placeholdersDelete)";

        // Preparar la sentencia
        $stmtEliminarEstudiantes = $conn->prepare($sqlEliminarEstudiantes);

        if ($stmtEliminarEstudiantes === false) {
            die('Error al preparar la consulta SQL para eliminar estudiantes: ' . $conn->error);
        }

        // Vincular los parámetros para la eliminación de estudiantes
        $stmtEliminarEstudiantes->bind_param($types, ...$idsArray);

        // Ejecutar la consulta para eliminar los estudiantes
        if ($stmtEliminarEstudiantes->execute()) {
            // Ahora procedemos a eliminar el usuario asociado a cada estudiante
            $sqlEliminarUsuarios = "DELETE FROM usuarios WHERE nombre_usuario IN (SELECT id_estudiante FROM estudiantes WHERE id_estudiante IN ($placeholdersDelete))";

            // Preparar la sentencia para eliminar usuarios
            $stmtEliminarUsuarios = $conn->prepare($sqlEliminarUsuarios);

            if ($stmtEliminarUsuarios === false) {
                die('Error al preparar la consulta SQL para eliminar usuarios: ' . $conn->error);
            }

            // Vincular los parámetros para la eliminación de usuarios
            $stmtEliminarUsuarios->bind_param($types, ...$idsArray);

            // Ejecutar la consulta para eliminar usuarios
            if ($stmtEliminarUsuarios->execute()) {
                echo 'Estudiantes y usuarios asociados eliminados correctamente.';
            } else {
                echo 'Hubo un problema al eliminar los usuarios asociados: ' . $stmtEliminarUsuarios->error;
            }

            // Cerrar la sentencia de eliminar usuarios
            $stmtEliminarUsuarios->close();
        } else {
            echo 'Hubo un problema al eliminar los estudiantes: ' . $stmtEliminarEstudiantes->error;
        }

        // Cerrar la sentencia de eliminar estudiantes
        $stmtEliminarEstudiantes->close();
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo 'No se recibieron los IDs de estudiantes a eliminar.';
}
?>
