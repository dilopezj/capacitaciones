<?php
include 'conexion.php';

// Iniciar sesión
session_start();

$id_usuario = $_SESSION['id_usuario'];
$identificacion = $_SESSION['identificacion'];

require 'PHPExcel/Classes/PHPExcel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se ha subido el archivo Excel
    if (isset($_FILES['archivo_excel']['tmp_name'])) {
        $archivo = $_FILES['archivo_excel']['tmp_name'];

        // Carga el archivo Excel
        $excelReader = PHPExcel_IOFactory::createReaderForFile($archivo);
        $excelObj = $excelReader->load($archivo);
        $hoja = $excelObj->getSheet(0);

        $highestRow = $hoja->getHighestRow();

        // Variables para la inserción
        $estudianteId = $_POST['estudianteId']; // ID del estudiante
        $moduloId = $_POST['moduloId']; // ID del módulo

        // Inicializar contadores para cada grupo de criterios
        $grupo1_totales = 0;
        $grupo1_correctos = 0;
        $grupo2_totales = 0;
        $grupo2_correctos = 0;

        // Arrays para almacenar IDs y criterios
        $preguntas = [];
        $respuestas = [];
        $criterios = [];

       for ($row = 6; $row <= $highestRow; $row++) {
            $id_pregunta = $hoja->getCell('A'.$row)->getValue(); // ID de la pregunta o criterio
            $texto = $hoja->getCell('B'.$row)->getValue(); // Texto de la pregunta o criterio
            $respondida = $hoja->getCell('D'.$row)->getValue(); // Columna de la respuesta
        
            // Verificar si es un criterio
            if (strpos($texto, 'Criterio') !== false) {
                $criterio = ($row < $highestRow / 2) ? 1 : 3; // Puedes ajustar la lógica del criterio aquí
                $preguntas[] = $id_pregunta;
                $criterios[] = $criterio;
            }
        
            // Verificar si es una pregunta
            elseif (strpos($texto, 'Pregunta') !== false) {
                $criterio = ($row < $highestRow / 2) ? 1 : 3; // Puedes ajustar la lógica del criterio aquí
                $preguntas[] = $id_pregunta;
                $criterios[] = $criterio;
            }
        
            // Verificar si la respuesta está marcada
            if (!is_null($respondida) && $respondida == 1) {
                $respuestas[] = "$id_pregunta:$respondida";
            }
        }


        // Procesar las respuestas y validar los resultados
        foreach ($respuestas as $dato) {
            $elemento = explode(':', $dato);
            $id_pregunta = $elemento[0];
            $id_respuesta = $elemento[1];
            $criterio = $criterios[array_search($id_pregunta, $preguntas)];

            $sql = "SELECT correcta FROM respuestas WHERE id_pregunta = $id_pregunta AND id_respuesta = $id_respuesta";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
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
            while ($filaModulos = $resultadoModulos->fetch_assoc()) {
                $examenId = $filaModulos["id_examen"];

                // Insertar datos en la tabla respuestas_instructor
                $sql_insert = "INSERT INTO `respuestas_instructor`(`id_estudiante`, `id_modulo`, `id_examen`, `total_preguntas`, `porcentaje`, `porcentaje_empresa`, `fecha_realizado`, `id_instructor`)
                               VALUES ($estudianteId, $moduloId, $examenId, $grupo1_totales + $grupo2_totales, $porcentaje_grupo1, $porcentaje_grupo2, CURRENT_TIMESTAMP, $identificacion)";
                $conn->query($sql_insert);

                // Crear una respuesta para enviar de vuelta a JavaScript
                $respuesta = "Respuestas procesadas. \n Ponderado de apreciación: $porcentaje_grupo1%. \n Porcentaje VERIFICACIÓN EMPRESA : $porcentaje_grupo2%";
                echo $respuesta;
            }
        }
    } else {
        echo "No se ha subido ningún archivo.";
    }

    // Cerrar conexión
    $conn->close();
}
?>
