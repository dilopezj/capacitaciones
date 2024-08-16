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
    $criterios = explode(',', $_POST['criterios']);
    $estudianteId = $_POST['estudianteId']; // ID del estudiante
    $moduloId = $_POST['moduloId']; // ID del modulo

    // Inicializar contadores para cada grupo de criterios
    $grupo1_totales = 0;
    $grupo1_correctos = 0;
    $grupo2_totales = 0;
    $grupo2_correctos = 0;

    foreach ($respuestas as $dato) {
        // Separar cada elemento usando ':' como delimitador
        $elemento = explode(':', $dato);
        $id_pregunta = $elemento[0]; // Obtener el ID de la pregunta
        $id_respuesta = $elemento[1]; // Obtener el ID de la respuesta
        $criterio = $criterios[array_search($id_pregunta, $preguntas)]; // Obtener el criterio correspondiente

        // Consulta para verificar si la respuesta es correcta
        $sql = "SELECT correcta FROM respuestas WHERE id_pregunta = $id_pregunta AND id_respuesta = $id_respuesta";
        $resultado = $conn->query($sql);

        if ($resultado->num_rows > 0) {
            // Obtener el resultado de la consulta
            $fila = $resultado->fetch_assoc();
            $correcta = $fila['correcta'];

            if ($criterio == 1 || $criterio == 2) {
                $grupo1_correctos += $correcta;
                $grupo1_totales++;
            } elseif ($criterio == 3) {
                $grupo2_correctos += $correcta;
                $grupo2_totales++;
            }
        }
    }

    // Calcular los porcentajes para cada grupo de criterios
    $porcentaje_grupo1 = ($grupo1_totales == 0) ? 0 : round(($grupo1_correctos / $grupo1_totales) * 100, 2);
    $porcentaje_grupo2 = ($grupo2_totales == 0) ? 0 : round(($grupo2_correctos / $grupo2_totales) * 100, 2);

    $sqlModulos = "SELECT ex.id_examen
                   FROM modulos m, examenes ex  
                   WHERE m.id_modulo = ex.id_modulo AND ex.tipo_examen = 'final' AND m.id_modulo = $moduloId";

    $resultadoModulos = $conn->query($sqlModulos);
    if ($resultadoModulos->num_rows > 0) {
        // Mostrar las preguntas
        while ($filaModulos = $resultadoModulos->fetch_assoc()) {
            $examenId = $filaModulos["id_examen"];
            // Insertar datos en la tabla respuestas_instructor
            $sql_insert = "INSERT INTO `respuestas_instructor`(`id_estudiante`, `id_modulo`, `id_examen`, `total_preguntas`, `porcentaje`, `porcentaje_empresa`, `fecha_realizado`, `id_instructor`)
                           VALUES ($estudianteId, $moduloId, $examenId, $grupo1_totales + $grupo2_totales, $porcentaje_grupo1, $porcentaje_grupo2, CURRENT_TIMESTAMP, $identificacion)";
            $conn->query($sql_insert); // Ejecutar la consulta de inserción

            // Aquí puedes guardar los porcentajes por grupo de criterios en la base de datos si es necesario
            // Por ejemplo, podrías insertar los porcentajes en una tabla 'calificaciones' asociada al estudiante y examen

            // Crear una respuesta para enviar de vuelta a JavaScript
            $respuesta = "Respuestas procesadas. \n Ponderado de apreciación: $porcentaje_grupo1%. \n Porcentaje VERIFICACIÓN EMPRESA : $porcentaje_grupo2%";
            echo $respuesta;
        }
    }

    // Cerrar conexión
    $conn->close();
}
?>
