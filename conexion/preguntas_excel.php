<?php
session_start();

include 'conexion.php';

require './../vendor/autoload.php';

// Verificar si se envió el formulario de carga de archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivoPreguntas"])) {
    // Obtener la información del archivo
    $archivo = $_FILES["archivoPreguntas"];
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
        $id_index = array_search('ID PREGUNTA', $rows[0]);
        $id_curso_index = array_search('ID CURSO', $rows[0]);
        $curso_index = array_search('CURSO', $rows[0]);
        $id_eva_index = array_search('ID EVALUACION', $rows[0]);
        $evaluacion_index = array_search('EVALUACION', $rows[0]);
        $tipo_index = array_search('TIPO  EVALUACION', $rows[0]);
        $orden_index = array_search('ORDEN', $rows[0]);
        $preguntas_index = array_search('PREGUNTAS', $rows[0]);
        $url_index = array_search('URL IMAGEN', $rows[0]);
        $criterio_index = array_search('CRITERIO', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $id_index === false || $id_curso_index === false || $curso_index === false
            || $id_eva_index === false || $evaluacion_index === false || $tipo_index === false
            || $orden_index === false || $preguntas_index === false || $url_index === false
            || $criterio_index === false           
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$id_index] != null && $rows[$i][$id_curso_index] != null && $rows[$i][$id_eva_index] != null) {
                // Procesar los datos de cada fila
                $id = $rows[$i][$id_index];
                $id_curso = $rows[$i][$id_curso_index];
                $curso = $rows[$i][$curso_index];
                $id_examen = $rows[$i][$id_eva_index];
                $examen = $rows[$i][$evaluacion_index];
                $tipo = $rows[$i][$tipo_index];
                $orden = $rows[$i][$orden_index];
                $pregunta = $rows[$i][$preguntas_index];
                $url = $rows[$i][$url_index];
                $criterio = $rows[$i][$criterio_index];

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO preguntas (id_pregunta, id_modulo, id_examen, texto_pregunta, imagen_url, id_criterio, orden) VALUES (?, ?, ?, ?, ?, ?, ?)";
               // Preparar la declaración
                $stmt = $conn->prepare($sql);
                // Vincular parámetros
                $stmt->bind_param("iiissii", $id ,$id_curso, $id_examen, $pregunta, $url, $criterio, $orden);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$pregunta creado exitosamente"];
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