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
        $nit_index = array_search('NIT', $rows[0]);
        $empresa_index = array_search('EMPRESA', $rows[0]);
        $regional_index = array_search('REGIONAL', $rows[0]);
        $ciudad_index = array_search('CIUDAD', $rows[0]);
        $direccion_index = array_search('DIRECCION', $rows[0]);
        $telefono_index = array_search('TELEFONO', $rows[0]);
        $nmb_contacto_index = array_search('NOMBRE CONTACTO', $rows[0]);
        $apl_contacto_index = array_search('APELLIDO CONTACTO', $rows[0]);
        $correo_contacto_index = array_search('CORREO CONTACTO', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $nit_index === false || $empresa_index === false || $regional_index === false
            || $ciudad_index === false || $direccion_index === false || $telefono_index === false
            || $nmb_contacto_index === false || $apl_contacto_index === false || $correo_contacto_index === false
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$nit_index] != null) {
                // Procesar los datos de cada fila
                $nit = $rows[$i][$nit_index];
                $nmb_empresa = $rows[$i][$empresa_index];
                $regional = $rows[$i][$regional_index];
                $ciudad = $rows[$i][$ciudad_index];
                $direccion = $rows[$i][$direccion_index];
                $telefono = $rows[$i][$telefono_index];
                $nmb_contacto = $rows[$i][$nmb_contacto_index];
                $apl_contacto = $rows[$i][$apl_contacto_index];
                $correo = $rows[$i][$correo_contacto_index];

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO empresas (nit, nombre, regional, ciudad, direccion, telefono, nmb_contacto, apl_contacto, correo_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? )";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issssssss", $nit, $nmb_empresa, $regional, $ciudad, $direccion, $telefono, $nmb_contacto, $apl_contacto, $correo);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$nmb_empresa creada exitosamente"];
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