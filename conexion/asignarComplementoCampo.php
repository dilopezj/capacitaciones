<?php

include 'conexion.php';

session_start();

// Verificar si se recibió el ID del estudiante
if (isset($_POST['idEstudiante'])) {
    // Procesar el formulario de guardar
    $estudiante_id = $_POST['idEstudiante'];
    $idCurso = $_POST['idCurso'];

    $instructor = isset($_POST['instructor']) ? $_POST['instructor'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

    $sqlSelect = "Select e.id_examen from examenes e where e.id_modulo = $idCurso and tipo_examen in ('FINAL')";
    $result = $conn->query($sqlSelect);

    // Verificar si se encontraron cursos asignados
    if ($result->num_rows > 0) {

        $sqlSelectEx = "Select ea.id_examen from examenes_asignados ea where ea.id_modulo = $idCurso and ea.id_estudiante =  $estudiante_id";
        $resultEx = $conn->query($sqlSelectEx);
        // Verificar si se encontraron cursos asignados
        if ($resultEx->num_rows > 0) {
            $cont=0;
            $contError=0;
            $contSinData=0;
            // Mostrar los cursos asignados en formato de lista HTML
            while ($row = $result->fetch_assoc()) {
                $id_examen = $row["id_examen"];
                $queryTemp = '';
                $sqlAsignarExamen = "UPDATE examenes_asignados SET  ";
                $setValues = array();

                // Variables opcionales que pueden ser enviadas o no               
                if (isset($instructor) && $instructor != "") {
                    $setValues[] = " id_instructor = '" . $instructor . "'";
                }
                if (isset($fecha) && $fecha != '') {
                    $setValues[] = " fecha_programado = '" . $fecha . "'";
                }

                // Agrega más variables según sea necesario
                // Si se enviaron variables, construye la parte SET de la consulta SQL
                if (!empty($setValues)) {
                    $sqlAsignarExamen .= implode(", ", $setValues);

                    // Agrega la condición WHERE si es necesario
                    $sqlAsignarExamen .= " WHERE id_modulo = $idCurso AND id_estudiante =  $estudiante_id AND id_examen = $id_examen ";

                    $stmt = $conn->prepare($sqlAsignarExamen);
                    $stmt->execute();

                    // Verifica si la consulta se ejecutó correctamente
                    if ($stmt->affected_rows > 0) {
                           $cont++;                        
                    } else {
                        $contError++;                        
                    }                    
                     // Cerrar la declaración y la conexión
                     $stmt->close();
                } else {
                    $contSinData++;
                }                
            }
            
            if($cont > 0 ){
               echo "Actualización exitosa";
            }
            if($contSinData > 0 ){
               echo "Debe seleccionar instructor y/o fecha.";
            }
            if($contError > 0 ){
               echo "No se realizó ninguna actualización";
            }
        }
    }
}

$conn->close();
?>