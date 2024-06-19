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
        $nombre_index = array_search('NOMBRE USUARIO', $rows[0]);
        $correo_index = array_search('CORREO', $rows[0]);
        $pass_index = array_search('CONTRASEÑA', $rows[0]);
        $perfil_index = array_search('PERFIL', $rows[0]);
        $identificacion_index = array_search('IDENTIFICACION', $rows[0]);
        $activo_index = array_search('ACTIVO', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $nombre_index === false || $correo_index === false || $pass_index === false
            || $perfil_index === false || $identificacion_index === false || $activo_index === false
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if ($rows[$i][$nombre_index] != null) {
                // Procesar los datos de cada fila
                $usuario = $rows[$i][$nombre_index];
                $correo = $rows[$i][$correo_index];
                $pass = $rows[$i][$pass_index];
                $identificacion = $rows[$i][$identificacion_index];

                // Asigna el perfil según el valor del campo 'PERFIL' del archivo Excel
                switch ($rows[$i][$perfil_index]) {
                    case 'ESTUDIANTE':
                        $perfil = 2;
                        break;
                    case 'INSTRUCTOR':
                        $perfil = 3;
                        break;
                    case 'ANALISTA':
                        $perfil = 4;
                        //$identificacion = 0; // ¿Esto es intencional?
                        break;
                    default:
                        $perfil = 0; // Perfil desconocido, maneja este caso según tus necesidades
                }

                $activo = $rows[$i][$activo_index];

                $contrasena_hash = hash('sha256', $pass); // Puedes utilizar otro algoritmo de hash si prefieres


                // Realiza la inserción en la base de datos
                $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sqlUsuario);
                $stmt->bind_param("ssssss", $usuario, $correo, $contrasena_hash, $perfil, $identificacion, $activo);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $mensaje[$i] = ["$usuario creado exitosamente"];
                } else {
                    $mensaje[$i] = ["Error: " . $sqlUsuario . "<br>" . $conn->error];
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