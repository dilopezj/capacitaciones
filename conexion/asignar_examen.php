<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Inicializa la variable $examenes como un array vacío
$examenes = [];

// Obtiene la cadena JSON del cuerpo de la solicitud
$json_data = file_get_contents('php://input');

// Decodifica la cadena JSON en un array asociativo de PHP
$data = json_decode($json_data, true);

// Verifica si 'estudiante' está presente en el array decodificado
if (isset($data['estudiante'])) {
    $id_estudiante = $data['estudiante'];
} else {
    // Si 'estudiante' no está presente, establece un valor predeterminado
    $id_estudiante = 0;
}

if (isset($data['examenId'])) {
    $examenId = $data['examenId'];
} else {
    // Si 'estudiante' no está presente, establece un valor predeterminado
    $examenId = 0;
}

// Consulta para verificar si el examen seleccionado ya está asignado al estudiante
$sqlVerificarAsignacion = "SELECT COUNT(*) AS cantidad_asignaciones FROM examenes_asignados WHERE id_estudiante = $id_estudiante AND id_examen = $examenId";

$resultadoVerificarAsignacion = $conn->query($sqlVerificarAsignacion);

if ($resultadoVerificarAsignacion) {
    // Obtiene el resultado de la consulta
    $filaVerificarAsignacion = $resultadoVerificarAsignacion->fetch_assoc();

    // Verifica si el examen ya está asignado al estudiante
    if ($filaVerificarAsignacion['cantidad_asignaciones'] == 0) {
        // El examen no está asignado, entonces lo asignamos
        $sqlAsignarExamen = "INSERT INTO examenes_asignados (id_estudiante, id_examen, fecha_asignacion) VALUES ($id_estudiante, $examenId, NOW())";

        if ($conn->query($sqlAsignarExamen) === TRUE) {
            // Realiza la consulta a la base de datos para obtener los exámenes asignados al estudiante
            $sqlExamenes = "SELECT ea.id_asignacion, ea.tipo_examen, ea.fecha_asignacion, e.id_examen, e.nombre_examen, e.descripcion, m.nombre AS nombre_modulo   
                            FROM examenes_asignados ea
                            JOIN examenes e ON ea.id_examen = e.id_examen
                            JOIN estudiantes es ON ea.id_estudiante = es.id_estudiante
                            JOIN modulos m ON e.id_modulo = m.id_modulo ";

            // Si el id_estudiante no es 0, añade una condición a la consulta SQL
            if ($id_estudiante != 0) {
                $sqlExamenes .= " WHERE ea.id_estudiante = $id_estudiante";
            }

            // Realiza la consulta
            $resultadoExamenes = $conn->query($sqlExamenes);

            // Verifica si hay resultados
            if ($resultadoExamenes) {
                // Verifica si hay al menos una fila de resultados
                if ($resultadoExamenes->num_rows > 0) {
                    // Itera sobre cada fila de resultados
                    while ($filaExamen = $resultadoExamenes->fetch_assoc()) {
                        // Añade cada fila de resultado al array $examenes
                        $examenes[] = $filaExamen;
                    }
                } else {
                    // Si no hay resultados, establece $examenes como un array vacío
                    $examenes = [];
                }
            } else {
                // Si hay un error en la consulta, muestra el mensaje de error (esto es útil para depuración)
                echo "Error al ejecutar la consulta: " . $conn->error;
            }

            // Convierte el array $examenes a formato JSON y lo imprime
            echo json_encode(['success' => true, 'error' => $conn->error, 'examenes' => json_encode($examenes)]); 
        } else {
            // Hubo un error al asignar el examen
            echo json_encode(['success' => false, 'error' => $conn->error, 'examenes' => []]);
        }
    } else {
        // El examen ya está asignado al estudiante
        echo json_encode(['success' => false, 'message' => 'El examen ya está asignado al estudiante.', 'examenes' => []]);
    }
} else {
    // Hubo un error al ejecutar la consulta
    echo json_encode(['success' => false, 'error' => $conn->error, 'examenes' => []]);
}

// Cierra la conexión a la base de datos
$conn->close();

?>