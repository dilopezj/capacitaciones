<?php
// Inicia la sesión si no está iniciada aún
session_start();

// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID del estudiante a eliminar desde la solicitud POST
$idEstudiante = isset($_POST['idEstudiante']) ? $_POST['idEstudiante'] : '';

if (!empty($idEstudiante)) {
    // Verificar si el estudiante tiene exámenes asignados
    $sqlCheckExamenes = "SELECT COUNT(*) as total_examenes FROM examenes_asignados WHERE id_estudiante = ?";
    $stmtCheckExamenes = $conn->prepare($sqlCheckExamenes);
    $stmtCheckExamenes->bind_param('i', $idEstudiante);
    $stmtCheckExamenes->execute();
    $resultCheckExamenes = $stmtCheckExamenes->get_result();
    $rowCheckExamenes = $resultCheckExamenes->fetch_assoc();
    $totalExamenes = $rowCheckExamenes['total_examenes'];

    if ($totalExamenes > 0) {
        echo 'No se puede eliminar el estudiante porque tiene exámenes asignados.';
    } else {
        // Obtener el ID del usuario asociado al estudiante (suponiendo que hay una relación en la base de datos)
        $sqlSelectUsuario = "SELECT id_usuario FROM usuarios WHERE identificacion = ?";
        $stmtSelectUsuario = $conn->prepare($sqlSelectUsuario);
        $stmtSelectUsuario->bind_param('i', $idEstudiante);
        $stmtSelectUsuario->execute();
        $resultSelectUsuario = $stmtSelectUsuario->get_result();

        if ($resultSelectUsuario->num_rows > 0) {
            $rowUsuario = $resultSelectUsuario->fetch_assoc();
            $idUsuario = $rowUsuario['id_usuario'];

            // Eliminar el usuario asociado al estudiante
            $sqlEliminarUsuario = "DELETE FROM usuarios WHERE identificacion = ?";
            $stmtEliminarUsuario = $conn->prepare($sqlEliminarUsuario);
            $stmtEliminarUsuario->bind_param('i', $idEstudiante);
            $stmtEliminarUsuario->execute();

            // Verificar si se eliminó correctamente el usuario
            if ($stmtEliminarUsuario->affected_rows > 0) {
                // Eliminar el estudiante
                $sqlEliminarEstudiante = "DELETE FROM estudiantes WHERE id_estudiante = ?";
                $stmtEliminarEstudiante = $conn->prepare($sqlEliminarEstudiante);
                $stmtEliminarEstudiante->bind_param('i', $idEstudiante);
                $stmtEliminarEstudiante->execute();

                // Verificar si se eliminó correctamente el estudiante
                if ($stmtEliminarEstudiante->affected_rows > 0) {
                    echo 'Estudiante eliminado correctamente.';
                } else {
                    echo 'Error al eliminar el estudiante.';
                }
                $stmtEliminarUsuario->close(); // Cerrar $stmtEliminarUsuario aquí
            } else {
                echo 'Error al eliminar el usuario asociado al estudiante.';
            }
            $stmtSelectUsuario->close();
        } else {
            // Si no hay usuario asociado, simplemente eliminar el estudiante
            $sqlEliminarEstudiante = "DELETE FROM estudiantes WHERE id_estudiante = ?";
            $stmtEliminarEstudiante = $conn->prepare($sqlEliminarEstudiante);
            $stmtEliminarEstudiante->bind_param('i', $idEstudiante);
            $stmtEliminarEstudiante->execute();

            // Verificar si se eliminó correctamente el estudiante
            if ($stmtEliminarEstudiante->affected_rows > 0) {
                echo 'Estudiante eliminado correctamente.';
            } else {
                echo 'Error al eliminar el estudiante.';
            }
        }
    }
} else {
    echo 'No se recibió el ID del estudiante a eliminar.';
}

// Cerrar las conexiones y liberar recursos
$stmtCheckExamenes->close();

$conn->close();
?>
