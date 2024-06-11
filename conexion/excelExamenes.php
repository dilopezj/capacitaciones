<?php
session_start();

include 'conexion.php';

require './../vendor/autoload.php';

// Verificar si se envió el formulario de carga de archivo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    // Obtener la información del archivo
    $archivo = $_FILES["archivo"];
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
        $id_index = array_search('ID', $rows[0]);
        $examen_index = array_search('EVALUACIONES', $rows[0]);
        $descripcion_index = array_search('DESCRIPCION', $rows[0]);
        $id_modulo_index = array_search('ID CURSO', $rows[0]);
        $curso_index = array_search('CURSO', $rows[0]);
        $tipo_index = array_search('TIPO', $rows[0]);
        $estado_index = array_search('ESTADO', $rows[0]);
        $vigencia_index = array_search('VIGENCIA', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $id_index === false || $examen_index === false || $descripcion_index === false
            || $id_modulo_index === false || $curso_index === false || $tipo_index === false
            || $estado_index === false || $vigencia_index === false
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$id_index] != null && $rows[$i][$examen_index] != null && $rows[$i][$id_modulo_index] != null) {
                // Procesar los datos de cada fila
                $id = $rows[$i][$id_index];
                $examen = $rows[$i][$examen_index];
                $descripcion = $rows[$i][$descripcion_index];
                $curso = $rows[$i][$id_modulo_index];
                $nmb_curso = $rows[$i][$curso_index];
                $tipo = $rows[$i][$tipo_index];
                $activo = $rows[$i][$estado_index];
                $vigencia = $rows[$i][$vigencia_index];

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO examenes (id_examen, nombre_examen, id_modulo, descripcion, tipo_examen, fecha_vigencia, activo) VALUES (?, ?, ?, ?, ?, ?)";

                // Preparar la declaración
                $stmt = $conn->prepare($sql);
                // Vincular parámetros
                $stmt->bind_param("isisssi", $id ,$examen, $curso, $descripcion, $tipo, $vigencia, $activo);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$examen creado exitosamente"];
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