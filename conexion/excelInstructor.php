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
        $regional_index = array_search('REGIONAL', $rows[0]);
        $ciudad_index = array_search('CIUDAD', $rows[0]);
        $tdoc_index = array_search('TIPO DOCUMENTO', $rows[0]);
        $doc_index = array_search('N. DOCUMENTO', $rows[0]);
        $nombres_index = array_search('NOMBRES', $rows[0]);
        $apellidos_index = array_search('APELLIDOS', $rows[0]);        
        $activo_index = array_search('ACTIVO', $rows[0]);
        $genero_index = array_search('GENERO',1);


        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $regional_index === false || $tdoc_index === false || $doc_index === false
            || $nombres_index === false || $apellidos_index === false 
            || $activo_index === false || $ciudad_index === false
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if (
                $rows[$i][$regional_index] != null && $rows[$i][$tdoc_index] != null && $rows[$i][$doc_index] != null
                && $rows[$i][$nombres_index] != null && $rows[$i][$apellidos_index] != null && $rows[$i][$ciudad_index] != null
            ) {
                // Procesar los datos de cada fila
                $regional = $rows[$i][$regional_index];
                $ciudad = $rows[$i][$ciudad_index];
                $tipo_documento = $rows[$i][$tdoc_index];
                $documento = $rows[$i][$doc_index];
                $nombres = $rows[$i][$nombres_index];
                $apellidos = $rows[$i][$apellidos_index];                
                $activo = $rows[$i][$activo_index];
                $genero = 1;

                $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO instructores (regional, ciudad, tipo_identificacion, identificacion, nombres, apellidos, genero,activo) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssi", $regional, $ciudad, $tipo_documento, $documento, $nombres, $apellidos, $genero, $activo);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                        VALUES ('$documento','$correo','$contrasena_hash',3,'$documento',$activo)";
                    $conn->query($sqlUsuario);

                    $mensaje[$i] = ["$documento creado exitosamente"];

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