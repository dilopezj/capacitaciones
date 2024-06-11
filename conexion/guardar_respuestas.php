<?php
include 'conexion.php';

// Iniciar sesión
session_start();

$id_estudiante = $_SESSION['identificacion'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde JavaScript
    $respuestas = explode(',', $_POST['respuestas']);
    $preguntas = explode(',', $_POST['preguntas']);
    $examenId = $_POST['examenId']; // ID del examen
    $moduloId = $_POST['moduloId']; // ID del modulo

    // Aquí puedes realizar el procesamiento de las respuestas
    $cantidad_respuestas_total = 0;
    $cantidad_respuestas_buenas = 0;
    $cantidad_respuestas_malas = 0;
    
    foreach ($respuestas as $dato) {
        // Separar cada elemento usando ':' como delimitador
        $elemento = explode(':', $dato);
        $id_pregunta = $elemento[0]; // Obtener el ID de la pregunta
        $id_respuesta = $elemento[1]; // Obtener el ID de la respuesta

        // Consulta para verificar si la respuesta es correcta
        $sql = "SELECT correcta
                FROM respuestas WHERE id_pregunta = $id_pregunta AND id_respuesta = $id_respuesta";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            // Obtener el resultado de la consulta
            $fila = $resultado->fetch_assoc();
            $correcta = $fila['correcta'];
             // Insertar datos en la tabla respuestas_estudiantes_destalle
             $sql_insertDetalle = "INSERT INTO resp_estudiante_detalle  (id_modulo,id_examen,id_estudiante,id_pregunta, id_respuesta,correcta) 
                                   VALUES ($moduloId ,$examenId,$id_estudiante,$id_pregunta,$id_respuesta,$correcta)";
             $conn->query($sql_insertDetalle); // Ejecutar la consulta de inserción
            // Lógica de validación y conteo de respuestas
            if ($correcta == 1) {
                $cantidad_respuestas_buenas++;
            } else {
                $cantidad_respuestas_malas++;
            }
            $cantidad_respuestas_total++;
        }
    }

    // Calcular el porcentaje de respuestas correctas
    if($cantidad_respuestas_total == 0){
       $porcentaje_correctas = 0; 
    }else{
        if($cantidad_respuestas_buenas == 0){
           $porcentaje_correctas = 0;
        }else{
            $porcentaje_correctas = ($cantidad_respuestas_buenas / $cantidad_respuestas_total) * 100;
        }    
    }

    
    
     // Insertar datos en la tabla respuestas_estudiantes
        $sql_insert = "INSERT INTO `respuestas_estudiantes`(`id_estudiante`,`id_moludo`, `id_examen`, `respuestas_correctas`, `respuestas_incorrectas`, `total_preguntas`, `porcentaje`, `fecha_realizacion`) 
                                                   VALUES ($id_estudiante,$moduloId ,$examenId,$cantidad_respuestas_buenas,$cantidad_respuestas_malas,$cantidad_respuestas_total,$porcentaje_correctas,CURRENT_TIMESTAMP)";
        $conn->query($sql_insert); // Ejecutar la consulta de inserción

    // Aquí puedes guardar el puntaje o calificación en la base de datos si es necesario
    // Por ejemplo, podrías insertar el puntaje en una tabla 'calificaciones' asociado al estudiante y examen

    // Crear una respuesta para enviar de vuelta a JavaScript
    $respuesta = "Respuestas procesadas. Porcentaje de respuestas correctas: $porcentaje_correctas%";
    echo $respuesta;

    // Cerrar conexión
    $conn->close();
}
?>
