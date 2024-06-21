<?php
include 'conexion.php';
session_start();

// Verificar si se recibió el ID del estudiante y el ID del curso
if (isset($_POST['idEstudiante']) && isset($_POST['idCurso'])) {
    // Procesar el formulario de guardar
    $estudiante_id = $_POST['idEstudiante'];
    $idCurso = $_POST['idCurso'];

    // Verificar si el ID del curso no está vacío
    if ($idCurso != "" && $idCurso != null) {
        $sqlSelect = "SELECT e.id_examen FROM examenes e WHERE e.id_modulo = $idCurso ";
        $result = $conn->query($sqlSelect);

        // Verificar si se encontraron cursos asignados
        if ($result->num_rows > 0) {
            $sqlSelectEx = "SELECT ea.id_examen FROM examenes_asignados ea WHERE ea.id_modulo = $idCurso AND ea.id_estudiante =  $estudiante_id";
            $resultEx = $conn->query($sqlSelectEx);

            // Verificar si el curso ya ha sido asignado al estudiante
            if ($resultEx->num_rows > 0) {
                echo "Este curso ya ha sido asignado al estudiante.";
            } else {
                // Mostrar los cursos asignados en formato de lista HTML
                $cont = 0;
                while ($row = $result->fetch_assoc()) {
                    $id_examen = $row["id_examen"];
                    $sqlAsignarExamen = "INSERT INTO examenes_asignados (id_estudiante,id_modulo, id_examen, fecha_asignacion) VALUES ($estudiante_id,$idCurso, $id_examen, NOW())";

                    if ($conn->query($sqlAsignarExamen) === TRUE) {
                        $cont++;
                    } else {
                        echo "Error al guardar los datos: " . $conn->error . " /n ";
                    }
                }
                if ($cont > 0) {
                    echo "Curso asignado correctamente.";
                }
            }
        } else {
            echo "No se encontraron exámenes asociados a este curso.";
        }
    } else {
        echo "Error: Debe seleccionar un curso.";
    }
} else {
    echo "Error: No se recibieron datos del formulario.";
}

// Cerrar la conexión
$conn->close();
?>
