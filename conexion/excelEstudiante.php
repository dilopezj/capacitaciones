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
        $nit_index = array_search('NIT EMPRESA', $rows[0]);
        $tdoc_index = array_search('TIPO DOCUMENTO', $rows[0]);
        $doc_index = array_search('N. DOCUMENTO', $rows[0]);
        $nombres_index = array_search('NOMBRES', $rows[0]);
        $apellidos_index = array_search('APELLIDOS', $rows[0]);
        $genero_index = array_search('GENERO', $rows[0]);
        $cargo_index = array_search('CARGO', $rows[0]);

        $telefono_index = array_search('CELULAR', $rows[0]);
        $correo_index = array_search('CORREO', $rows[0]);
        $modulos_index = array_search('ID MODULO A ASIGNAR', $rows[0]);

        // Si la cabecera no contiene los nombres de las columnas esperadas, muestra un error
        if (
            $nit_index === false || $tdoc_index === false || $doc_index === false
            || $nombres_index === false || $apellidos_index === false || $genero_index === false
            || $cargo_index === false || $telefono_index === false || $correo_index === false
            || $modulos_index === false
        ) {
            echo "El archivo no tiene el formato esperado.";
            exit();
        }

        // Iterar sobre las filas (comenzando desde la segunda fila ya que la primera es la cabecera)
        for ($i = 1; $i < count($rows); $i++) {
            if (
                $rows[$i][$nit_index] != null && $rows[$i][$tdoc_index] != null && $rows[$i][$doc_index] != null
                && $rows[$i][$nombres_index] != null && $rows[$i][$apellidos_index] != null && $rows[$i][$correo_index] != null
            ) {
                // Procesar los datos de cada fila
                $empresa = $rows[$i][$nit_index];
                $tipo_documento = $rows[$i][$tdoc_index];
                $documento = $rows[$i][$doc_index];
                $nombres = $rows[$i][$nombres_index];
                $apellidos = $rows[$i][$apellidos_index];
                $genero = $rows[$i][$genero_index];
                $cargo = $rows[$i][$cargo_index];

                $celular = $rows[$i][$telefono_index];
                $correo = $rows[$i][$correo_index];
                $modulos = trim($rows[$i][$modulos_index]);

                // Realiza la inserción en la base de datos
                $sql = "INSERT INTO estudiantes (id_empresa, tipo_identificacion, id_estudiante, nombre, apellido, genero, cargo, celular, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issssssss", $empresa, $tipo_documento, $documento, $nombres, $apellidos, $genero, $cargo, $celular, $correo);
                // Ejecuta la consulta
                if ($stmt->execute()) {
                    $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres

                    $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                        VALUES ('$documento','$correo','$contrasena_hash',2,'$documento',1)";
                    $conn->query($sqlUsuario);

                    if ($modulos != null && $modulos != "") {
                        $arrayModulos = explode(";", $modulos);
                        /**** */
                        foreach ($arrayModulos as $idCurso) {
                            $sqlSelect = "Select e.id_examen from examenes e where e.id_modulo = $idCurso ";
                            $result = $conn->query($sqlSelect);

                            // Verificar si se encontraron cursos asignados
                            if ($result->num_rows > 0) {

                                $sqlSelectEx = "Select ea.id_examen from examenes_asignados ea where ea.id_modulo = $idCurso and ea.id_estudiante =  $documento";
                                $resultEx = $conn->query($sqlSelectEx);
                                // Verificar si se encontraron cursos asignados
                                if ($resultEx->num_rows > 0) {
                                    echo "Este curso ya ha sido asignado al estudiante.";
                                } else {
                                    // Mostrar los cursos asignados en formato de lista HTML
                                    while ($row = $result->fetch_assoc()) {
                                        $id_examen = $row["id_examen"];
                                        $sqlAsignarExamen = "INSERT INTO examenes_asignados (id_estudiante,id_modulo, id_examen, fecha_asignacion) VALUES ($documento,$idCurso, $id_examen, NOW())";

                                        if ($conn->query($sqlAsignarExamen) === TRUE) {
                                            $mensaje[$i] = ["$documento creado exitosamente y curso $idCurso asignado"];
                                        } else {
                                            $mensaje[$i] = ["Error: " . $sqlAsignarExamen . "<br>" . $conn->error];
                                        }

                                    }
                                }
                            }
                        }
                        /*** */
                    } else {
                        $mensaje[$i] = ["$documento creado exitosamente"];
                    }
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