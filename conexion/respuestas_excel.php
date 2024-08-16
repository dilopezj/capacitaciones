<?php
session_start();

include 'conexion.php';

require './../vendor/autoload.php';

// Verificar si se envió el formulario de carga de archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivoRespuestas"])) {
    // Obtener la información del archivo
    $archivo = $_FILES["archivoRespuestas"];
    $mensaje = [];

    // Verificar si no hubo errores en la carga del archivo
    if ($archivo["error"] == UPLOAD_ERR_OK) {
        // Procesar el archivo
        $nombreTemporal = $archivo["tmp_name"];

        // Cargar el archivo Excel usando PhpSpreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($nombreTemporal);

        // Obtener la primera hoja del archivo
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener las filas de la hoja
        $rows = $sheet->toArray();

        // Obtener el índice de cada columna
        $id_index = array_search('ID RESPUESTA', $rows[0]);
        $id_curso_index = array_search('ID CURSO', $rows[0]);
        $curso_index = array_search('CURSO', $rows[0]);
        $id_eva_index = array_search('ID EVALUACION', $rows[0]);
        $evaluacion_index = array_search('EVALUACION', $rows[0]);
        $id_preguntas_index = array_search('ID PREGUNTAS', $rows[0]);
        $orden_p_index = array_search('ORDEN PREGUNTAS', $rows[0]);
        $orden_r_index = array_search('ORDEN RESPUESTAS', $rows[0]);
        $respuestas_index = array_search('RESPUESTAS', $rows[0]);
        $correcta_index = array_search('CORRECTA', $rows[0]);
        $url_index = array_search('URL IMAGEN', $rows[0]);
        

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $id_index === false || $id_curso_index === false || $curso_index === false
            || $id_eva_index === false || $evaluacion_index === false || $id_preguntas_index === false
            || $orden_p_index === false || $orden_r_index === false || $respuestas_index === false
            || $correcta_index === false || $url_index === false           
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$id_index] != null && $rows[$i][$id_curso_index] != null && $rows[$i][$id_eva_index] != null
            && $rows[$i][$id_preguntas_index] != null) {
                // Procesar los datos de cada fila
                $id = $rows[$i][$id_index];
                $id_curso = $rows[$i][$id_curso_index];
                $curso = $rows[$i][$curso_index];
                $id_examen = $rows[$i][$id_eva_index];
                $examen = $rows[$i][$evaluacion_index];
                $id_pregunta = $rows[$i][$id_preguntas_index];
                $orden_p = $rows[$i][$orden_p_index];
                $orden_r = $rows[$i][$orden_r_index];
                $respuestas = $rows[$i][$respuestas_index];
                $url = $rows[$i][$url_index];
                $correcta = $rows[$i][$correcta_index];

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO respuestas (id_respuesta, id_pregunta, id_modulo, id_examen,texto_respuesta,correcta, imagen_url, orden) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
               // Preparar la declaración
                $stmt = $conn->prepare($sql);
                // Vincular parámetros
                $stmt->bind_param("iiiisisi", $id,$id_pregunta ,$id_curso, $id_examen, $respuestas,$correcta, $url, $orden_r);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$respuestas creado exitosamente"];
                } else {
                    $mensaje[$i] = ["Error: " . $sql . "<br>" . $conn->error];
                }
            }
        }
        echo json_encode($mensaje);

        // Cierra la declaración preparada y la conexión
        $stmt->close();
        $conn->close();
    } else {
        // Maneja el caso en que ocurra un error al cargar el archivo
        echo "Ocurrió un error al cargar el archivo.";
    }
}
?>