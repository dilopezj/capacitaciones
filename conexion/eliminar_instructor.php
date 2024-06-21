<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID del instructor a eliminar desde la solicitud POST
$idInstructor = isset($_POST['idInstructor']) ? $_POST['idInstructor'] : '';

if (!empty($idInstructor)) {
    // Verificar si el instructor tiene módulos asignados
    $sqlCheckModulos = "SELECT COUNT(*) as total_modulos FROM examenes_asignados WHERE id_instructor = ?";
    $stmtCheckModulos = $conn->prepare($sqlCheckModulos);
    $stmtCheckModulos->bind_param('i', $idInstructor);
    $stmtCheckModulos->execute();
    $resultCheckModulos = $stmtCheckModulos->get_result();
    $rowCheckModulos = $resultCheckModulos->fetch_assoc();
    $totalModulos = $rowCheckModulos['total_modulos'];

    if ($totalModulos > 0) {
        echo 'No se puede eliminar el instructor porque tiene módulos asignados.';
    } else {
        // Obtener el ID del usuario asociado al instructor (suponiendo que hay una relación en la base de datos)
        $sqlSelectUsuario = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?";
        $stmtSelectUsuario = $conn->prepare($sqlSelectUsuario);
        $stmtSelectUsuario->bind_param('i', $idInstructor);
        $stmtSelectUsuario->execute();
        $resultSelectUsuario = $stmtSelectUsuario->get_result();

        if ($resultSelectUsuario->num_rows > 0) {
            $rowUsuario = $resultSelectUsuario->fetch_assoc();
            $idUsuario = $rowUsuario['id_usuario'];

            // Eliminar el usuario asociado al instructor
            $sqlEliminarUsuario = "DELETE FROM usuarios WHERE nombre_usuario = ?";
            $stmtEliminarUsuario = $conn->prepare($sqlEliminarUsuario);
            $stmtEliminarUsuario->bind_param('i', $idInstructor);
            $stmtEliminarUsuario->execute();

            // Verificar si se eliminó correctamente el usuario
            if ($stmtEliminarUsuario->affected_rows > 0) {
                // Eliminar el instructor
                $sqlEliminarInstructor = "DELETE FROM instructores WHERE identificacion = ?";
                $stmtEliminarInstructor = $conn->prepare($sqlEliminarInstructor);
                $stmtEliminarInstructor->bind_param('i', $idInstructor);
                $stmtEliminarInstructor->execute();

                // Verificar si se eliminó correctamente el instructor
                if ($stmtEliminarInstructor->affected_rows > 0) {
                    echo 'Instructor eliminado correctamente.';
                } else {
                    echo 'Error al eliminar el instructor.';
                }
                $stmtEliminarUsuario->close(); // Cerrar $stmtEliminarUsuario aquí
            } else {
                echo 'Error al eliminar el usuario asociado al instructor.';
            }
        } else {
            // Si no hay usuario asociado, simplemente eliminar el instructor
            $sqlEliminarInstructor = "DELETE FROM instructores WHERE identificacion = ?";
            $stmtEliminarInstructor = $conn->prepare($sqlEliminarInstructor);
            $stmtEliminarInstructor->bind_param('i', $idInstructor);
            $stmtEliminarInstructor->execute();

            // Verificar si se eliminó correctamente el instructor
            if ($stmtEliminarInstructor->affected_rows > 0) {
                echo 'Instructor eliminado correctamente.';
            } else {
                echo 'Error al eliminar el instructor.';
            }
        }
    }
} else {
    echo 'No se recibió el ID del instructor a eliminar.';
}

// Cerrar las conexiones y liberar recursos
$stmtCheckModulos->close();
$stmtSelectUsuario->close();
$conn->close();
?>
