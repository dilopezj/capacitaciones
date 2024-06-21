<?php

include 'conexion.php';
session_start();

// Verificar si se recibió el ID del estudiante
if (isset($_POST['idEstudiante'])) {
    // Procesar el formulario de guardar
    $estudiante_id = $_POST['idEstudiante'];
    $idCurso = $_POST['idCurso'];

    $salon = isset($_POST['salon']) ? $_POST['salon'] : '';
    $instructor = isset($_POST['instructor']) ? $_POST['instructor'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

    // Validar que el campo salon sea obligatorio
    if (empty($salon)) {
        echo "El campo 'salon' es obligatorio.";
        exit;
    }

    // Consulta SQL para obtener los exámenes asignados con fecha de realización
    $sqlSelect = "SELECT ea.id_examen,
                        (SELECT re.fecha_realizacion 
                         FROM respuestas_estudiantes re 
                         WHERE re.id_estudiante = ea.id_estudiante 
                           AND re.id_moludo = ea.id_modulo 
                           AND re.id_examen = ea.id_examen) as fecha_realizado
                  FROM examenes_asignados ea
                  INNER JOIN examenes e ON ea.id_examen = e.id_examen
                  WHERE ea.id_modulo = $idCurso 
                    AND ea.id_estudiante = $estudiante_id";

    $result = $conn->query($sqlSelect);

    // Verificar si se encontraron exámenes asignados
    if ($result->num_rows > 0) {
        $cont = 0;
        $contError = 0;
        $contSinData = 0;
        $fechaRealizado = 0;

        // Recorrer los resultados obtenidos
        while ($row = $result->fetch_assoc()) {
            $id_examen = $row["id_examen"];
            $fecha_realizado = $row["fecha_realizado"];

            // Verificar si el examen no ha sido realizado (fecha_realizado es NULL o vacía)
            if (empty($fecha_realizado)) {
                // Construir la consulta para actualizar el examen asignado
                $sqlAsignarExamen = "UPDATE examenes_asignados SET ";

                $setValues = array();

                // Verificar y agregar variables opcionales según sean enviadas
                if (isset($salon) && $salon != "") {
                    $setValues[] = " salon = '$salon'";
                }
                if (isset($instructor) && $instructor != "") {
                    $setValues[] = " id_instructor = '$instructor'";
                }
                if (isset($fecha) && $fecha != '') {
                    $setValues[] = " fecha_programado = '$fecha'";
                }

                // Si se enviaron variables, construir la parte SET de la consulta SQL
                if (!empty($setValues)) {
                    $sqlAsignarExamen .= implode(", ", $setValues);

                    // Agregar la condición WHERE para actualizar el examen asignado específico
                    $sqlAsignarExamen .= " WHERE id_modulo = $idCurso 
                                           AND id_estudiante = $estudiante_id 
                                           AND id_examen = $id_examen";

                    // Preparar y ejecutar la consulta de actualización
                    $stmt = $conn->prepare($sqlAsignarExamen);
                    $stmt->execute();

                    // Verificar si la consulta se ejecutó correctamente
                    if ($stmt->affected_rows > 0) {
                        $cont++;
                    } else {
                        $contError++;
                    }

                    $stmt->close();
                } else {
                    $contSinData++;
                }
            } else {
                // Si el examen ya ha sido realizado, aumentar el contador de errores
                $fechaRealizado++;
            }
        }

        if ($fechaRealizado > 0) {
            echo "No se puede modificar este curso";
        } else {
            // Mostrar mensajes según el resultado de las actualizaciones
            if ($cont > 0) {
                echo "Actualización exitosa";
            }
            if ($contSinData > 0) {
                echo "Debe seleccionar instructor y/o fecha.";
            }
            if ($contError > 0) {
                echo "No se realizó ninguna actualización";
            }
        }
    } else {
        echo "No se encontraron exámenes asignados para el curso y estudiante proporcionados.";
    }
}

$conn->close();
?>
