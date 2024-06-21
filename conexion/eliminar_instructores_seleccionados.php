<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Obtener los IDs de instructores a eliminar desde la solicitud POST
$ids = isset($_POST['ids']) ? $_POST['ids'] : '';

// Validar que se recibieron los IDs de instructores
if (!empty($ids)) {
    // Convertir la cadena de IDs separados por coma en un array
    $idsArray = explode(',', $ids);

    // Construir los marcadores de posición para la consulta de contar módulos asignados
    $placeholders = implode(',', array_fill(0, count($idsArray), '?'));

    // Consulta para contar los módulos asignados a los instructores
    $sqlVerificarModulos = "SELECT COUNT(*) as total FROM examenes_asignados WHERE id_instructor IN ($placeholders)";

    // Preparar la sentencia
    $stmtVerificarModulos = $conn->prepare($sqlVerificarModulos);

    if ($stmtVerificarModulos === false) {
        die('Error al preparar la consulta SQL para verificar módulos asignados: ' . $conn->error);
    }

    // Vincular los parámetros
    $types = str_repeat('i', count($idsArray)); // 'i' para IDs de tipo entero
    $stmtVerificarModulos->bind_param($types, ...$idsArray);

    // Ejecutar la consulta
    if (!$stmtVerificarModulos->execute()) {
        die('Error al ejecutar la consulta SQL para verificar módulos asignados: ' . $stmtVerificarModulos->error);
    }

    // Obtener el resultado
    $resultModulos = $stmtVerificarModulos->get_result();
    $rowModulos = $resultModulos->fetch_assoc();
    $totalModulos = $rowModulos['total'];

    // Cerrar la sentencia de contar módulos asignados
    $stmtVerificarModulos->close();

    if ($totalModulos > 0) {
        echo 'No se puede eliminar el/los instructor(es) seleccionado(s) porque tienen módulos asignados.';
    } else {
        // Preparar la consulta para eliminar los instructores
        $placeholdersDelete = rtrim(str_repeat('?,', count($idsArray)), ',');
        $sqlEliminarInstructores = "DELETE FROM instructores WHERE identificacion IN ($placeholdersDelete)";

        // Preparar la sentencia
        $stmtEliminarInstructores = $conn->prepare($sqlEliminarInstructores);

        if ($stmtEliminarInstructores === false) {
            die('Error al preparar la consulta SQL para eliminar instructores: ' . $conn->error);
        }

        // Vincular los parámetros para la eliminación de instructores
        $stmtEliminarInstructores->bind_param($types, ...$idsArray);

        // Ejecutar la consulta para eliminar los instructores
        if ($stmtEliminarInstructores->execute()) {
            // Ahora procedemos a eliminar el usuario asociado a cada instructor
            $sqlEliminarUsuarios = "DELETE FROM usuarios WHERE nombre_usuario IN (SELECT identificacion FROM instructores WHERE identificacion IN ($placeholdersDelete))";

            // Preparar la sentencia para eliminar usuarios
            $stmtEliminarUsuarios = $conn->prepare($sqlEliminarUsuarios);

            if ($stmtEliminarUsuarios === false) {
                die('Error al preparar la consulta SQL para eliminar usuarios: ' . $conn->error);
            }

            // Vincular los parámetros para la eliminación de usuarios
            $stmtEliminarUsuarios->bind_param($types, ...$idsArray);

            // Ejecutar la consulta para eliminar usuarios
            if ($stmtEliminarUsuarios->execute()) {
                echo 'Instructores y usuarios asociados eliminados correctamente.';
            } else {
                echo 'Hubo un problema al eliminar los usuarios asociados: ' . $stmtEliminarUsuarios->error;
            }

            // Cerrar la sentencia de eliminar usuarios
            $stmtEliminarUsuarios->close();
        } else {
            echo 'Hubo un problema al eliminar los instructores: ' . $stmtEliminarInstructores->error;
        }

        // Cerrar la sentencia de eliminar instructores
        $stmtEliminarInstructores->close();
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo 'No se recibieron los IDs de instructores a eliminar.';
}
?>
