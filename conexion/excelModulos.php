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
        $curso_index = array_search('CURSO', $rows[0]);
        $vigencia_index = array_search('VIGENCIA', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $id_index === false || $curso_index === false || $vigencia_index === false ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$id_index] != null &&$rows[$i][$curso_index] != null && $rows[$i][$vigencia_index] != null) {
                // Procesar los datos de cada fila
                $id = $rows[$i][$id_index];
                $curso = $rows[$i][$curso_index];
                $vigencia = $rows[$i][$vigencia_index];

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO modulos (id_modulo,nombre, fecha_vigencia) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iss",$id ,$curso, $vigencia);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$nombre creado exitosamente"];
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