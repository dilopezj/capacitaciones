<?php
include 'conexion.php';
session_start();

// Verificar si se recibió el ID del estudiante
if (isset($_POST['idEstudiante'])) {
    // Procesar el formulario de guardar
    $estudiante_id = $_POST['idEstudiante'];
    $idCurso = $_POST['idCurso'];
  
    // Consulta SQL para obtener los exámenes asignados que cumplen las condiciones
    $sqlSelect = "SELECT ea.id_examen,
                  (SELECT re.fecha_realizacion FROM respuestas_estudiantes re WHERE re.id_estudiante = ea.id_estudiante AND re.id_moludo = ea.id_modulo AND re.id_examen = ea.id_examen) fecha_realizado
                  FROM examenes_asignados ea
                  INNER JOIN examenes e ON ea.id_examen = e.id_examen
                  WHERE ea.id_modulo = $idCurso 
                  AND ea.id_estudiante = $estudiante_id";

    $result = $conn->query($sqlSelect);

    if ($result->num_rows > 0) {
        $cont = 0;
        $contError = 0;
        $todosSinRealizar = true; // Bandera para verificar si todos los exámenes están sin realizar

        // Recorrer los resultados obtenidos
        while ($row = $result->fetch_assoc()) {
            $id_examen = $row["id_examen"];
            $fecha_realizado = $row["fecha_realizado"];

            // Verificar si el examen tiene fecha_realizado diferente de NULL o vacío
            if (!empty($fecha_realizado)) {
                $todosSinRealizar = false;
                break; // No es necesario continuar verificando
            }
        }

        // Si todos los exámenes están sin realizar, proceder a eliminar
        if ($todosSinRealizar) {
            // Eliminar todos los registros de examenes_asignados para el estudiante y curso
            $sqlDelete = "DELETE FROM examenes_asignados 
                          WHERE id_modulo = $idCurso 
                          AND id_estudiante = $estudiante_id";

            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->execute();

            if ($stmtDelete->affected_rows > 0) {
                echo "Se eliminaron todos los registros correctamente.";
            } else {
                echo "No se encontraron registros para eliminar o ocurrió un error.";
            }

            $stmtDelete->close();
        } else {
            echo "No se pueden eliminar los registros porque al menos un examen ha sido realizado.";
        }
    } else {
        echo "No se encontraron exámenes asignados para el curso y estudiante proporcionados.";
    }
}

$conn->close();
?>
