<?php
session_start();

include 'conexion.php';

require './../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["archivo"])) {
    $archivo = $_FILES["archivo"];
    $mensaje = [];

    if ($archivo["error"] == UPLOAD_ERR_OK) {
        $nombreTemporal = $archivo["tmp_name"];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($nombreTemporal);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $regional_index = array_search('REGIONAL', $rows[0]);
        $ciudad_index = array_search('CIUDAD', $rows[0]);
        $tdoc_index = array_search('TIPO DOCUMENTO', $rows[0]);
        $doc_index = array_search('N. DOCUMENTO', $rows[0]);
        $nombres_index = array_search('NOMBRES', $rows[0]);
        $apellidos_index = array_search('APELLIDOS', $rows[0]);
        $activo_index = array_search('ACTIVO', $rows[0]);

        if ($regional_index === false || $tdoc_index === false || $doc_index === false
            || $nombres_index === false || $apellidos_index === false 
            || $activo_index === false || $ciudad_index === false) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO instructores (regional, ciudad, tipo_identificacion, identificacion, nombres, apellidos, activo) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            echo "Error al preparar la consulta: " . $conn->error;
            exit();
        }

        $stmt->bind_param("ssssssi", $regional, $ciudad, $tipo_documento, $documento, $nombres, $apellidos, $activo);

        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$regional_index] != null && $rows[$i][$tdoc_index] != null && $rows[$i][$doc_index] != null
                && $rows[$i][$nombres_index] != null && $rows[$i][$apellidos_index] != null && $rows[$i][$ciudad_index] != null) {
                $documento = $rows[$i][$doc_index];

                // Verificar si el instructor ya existe en la base de datos
                $stmtVerificar = $conn->prepare("SELECT nombres FROM instructores WHERE identificacion = ?");
                $stmtVerificar->bind_param("s", $documento);
                $stmtVerificar->execute();
                $stmtVerificar->store_result();

                if ($stmtVerificar->num_rows > 0) {
                    $mensaje[$i] = ["El instructor con documento $documento ya existe en la base de datos. Se omitió la inserción."];
                    $stmtVerificar->close();
                    continue; // Saltar a la siguiente iteración del bucle
                }

                // Si el instructor no existe, proceder con la inserción
                $regional = $rows[$i][$regional_index];
                $ciudad = $rows[$i][$ciudad_index];
                $tipo_documento = $rows[$i][$tdoc_index];
                $nombres = $rows[$i][$nombres_index];
                $apellidos = $rows[$i][$apellidos_index];
                $activo = $rows[$i][$activo_index];

                $contrasena_hash = hash('sha256', $documento);

                if ($stmt->execute()) {
                    $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                        VALUES (?, ?, ?, 3, ?, ?)";
                    $stmtUsuario = $conn->prepare($sqlUsuario);
                    $correo = ""; // Define el correo aquí
                    $stmtUsuario->bind_param("ssssi", $documento, $correo, $contrasena_hash, $documento, $activo);
                    
                    if (!$stmtUsuario->execute()) {
                        $mensaje[$i] = ["Error al insertar usuario: " . $stmtUsuario->error];
                    } else {
                        $mensaje[$i] = ["$documento creado exitosamente"];
                    }
                    $stmtUsuario->close();
                } else {
                    $mensaje[$i] = ["Error al insertar instructor: " . $stmt->error];
                }
            }
        }
        echo json_encode($mensaje);

        $stmt->close();
        $conn->close();
    } else {
        echo "Ocurrió un error al cargar el archivo.";
    }
}
?>
