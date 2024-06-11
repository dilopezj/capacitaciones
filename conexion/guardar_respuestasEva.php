<?php
include 'conexion.php';

// Iniciar sesión
session_start();

$id_usuario = $_SESSION['id_usuario'];
$identificacion = $_SESSION['identificacion'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde JavaScript
    $respuestas = explode(',', $_POST['respuestas']);
    $preguntas = explode(',', $_POST['preguntas']);
    $estudianteId = $_POST['estudianteId']; // ID del estudiante
    $moduloId = $_POST['moduloId']; // ID del modulo

    // Aquí puedes realizar el procesamiento de las respuestas
    $cantidad_respuestas_total = 0;
    $cantidad_respuestas_buenas = 0;

    foreach ($respuestas as $dato) {
        // Separar cada elemento usando ':' como delimitador
        $elemento = explode(':', $dato);
        $id_pregunta = $elemento[0]; // Obtener el ID de la pregunta
        $id_respuesta = $elemento[1]; // Obtener el ID de la respuesta

        // Consulta para verificar si la respuesta es correcta
        $sql = "SELECT correcta FROM respuestas WHERE id_pregunta = $id_pregunta AND id_respuesta = $id_respuesta";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            // Obtener el resultado de la consulta
            $fila = $resultado->fetch_assoc();
            $correcta = $fila['correcta'];
            $cantidad_respuestas_buenas += $correcta; 
            $cantidad_respuestas_total++;
        }
    }

    // Calcular el porcentaje de respuestas correctas
    if ($cantidad_respuestas_total == 0) {
        $porcentaje_correctas = 0;
    } else {
        if ($cantidad_respuestas_buenas == 0) {
            $porcentaje_correctas = 0;
        } else {
            $porcentaje_correctas = round(($cantidad_respuestas_buenas / $cantidad_respuestas_total),2);
        }
    }

    $sqlModulos = "SELECT ex.id_examen
                   FROM modulos m,examenes ex  WHERE m.id_modulo = ex.id_modulo and ex.tipo_examen = 'final' and  m.id_modulo= $moduloId ";

    $resultadoModulos = $conn->query($sqlModulos);
    if ($resultadoModulos->num_rows > 0) {
        // Mostrar las preguntas
        while ($filaModulos = $resultadoModulos->fetch_assoc()) {
            $examenId = $filaModulos["id_examen"];
            // Insertar datos en la tabla respuestas_evaluador
            $sql_insert = "INSERT INTO `respuestas_instructor`(`id_estudiante`, `id_modulo`, `id_examen`, `total_preguntas`, `porcentaje`, `fecha_realizado`,`id_instructor` ) 
                                                   VALUES ($estudianteId,$moduloId,$examenId,$cantidad_respuestas_total,$porcentaje_correctas,CURRENT_TIMESTAMP,$identificacion)";
            $conn->query($sql_insert); // Ejecutar la consulta de inserción

            // Aquí puedes guardar el puntaje o calificación en la base de datos si es necesario
            // Por ejemplo, podrías insertar el puntaje en una tabla 'calificaciones' asociado al estudiante y examen

            // Crear una respuesta para enviar de vuelta a JavaScript
            $respuesta = "Respuestas procesadas. Ponderado de apreciación (1 al 10): $porcentaje_correctas%";
            echo $respuesta;

        }
    }

    // Cerrar conexión
    $conn->close();
}
?>