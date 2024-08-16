<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la biblioteca PhpSpreadsheet
require './../vendor/autoload.php';
include 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_examen']) && is_numeric($_POST['id_examen'])) {
        $id_examen = intval($_POST['id_examen']); // Asegurarse de que sea un entero

        // Consulta SQL para obtener los datos del examen y el módulo
        $sql_info = "SELECT e.id_examen, e.nombre_examen, m.id_modulo, m.nombre nombre_modulo, e.tipo_examen  
                     FROM examenes e 
                     JOIN modulos m ON e.id_modulo = m.id_modulo 
                     WHERE e.id_examen = ?";
        
        if ($stmt_info = $conn->prepare($sql_info)) {
            $stmt_info->bind_param("i", $id_examen);
            $stmt_info->execute();
            $result_info = $stmt_info->get_result();

            if ($examen_info = $result_info->fetch_assoc()) {
                $stmt_info->close();
            } else {
                echo "Examen no encontrado.";
                http_response_code(404); // No encontrado
                exit();
            }
        } else {
            echo "Error al preparar la consulta de información del examen: " . $conn->error;
            http_response_code(500); // Error interno del servidor
            exit();
        }

        // Consulta SQL para obtener las preguntas, respuestas y criterios
        $sql = "SELECT p.id_pregunta, p.texto_pregunta AS pregunta, r.id_respuesta, r.texto_respuesta AS respuesta, c.descripcion AS criterio, c.texto texto_criterio,
                p.id_criterio
                FROM preguntas p 
                JOIN respuestas r ON p.id_pregunta = r.id_pregunta 
                JOIN criterios c ON c.id_criterios = p.id_criterio
                WHERE p.id_examen = ? 
                ORDER BY p.id_pregunta, r.id_respuesta";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id_examen);
            $stmt->execute();
            $result = $stmt->get_result();

            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Añadir el nombre del módulo y del examen
            $sheet->setCellValue('B1', 'ID Módulo');
            $sheet->setCellValue('A1', $examen_info['id_modulo']);
            $sheet->setCellValue('B2', 'Nombre del Módulo');
            $sheet->setCellValue('C2', $examen_info['nombre_modulo']);
            $sheet->setCellValue('B3', 'Nombre del Examen');
            $sheet->setCellValue('C3', $examen_info['nombre_examen'] .' '. $examen_info['tipo_examen']);
            $sheet->setCellValue('B4', 'Tipo de Examen');
            $sheet->setCellValue('A4', $examen_info['tipo_examen']);
            $sheet->setCellValue('C4', $examen_info['tipo_examen']);
            $sheet->setCellValue('B5', 'Identificación Estudiante:');

            // Iniciar las preguntas desde la fila 7
            $row = 7;
            $current_question_id = null;
            $question_counter = 1;
            $response_counter = 1;
            $current_criterio_id = null;

            while ($data = $result->fetch_assoc()) {
                if ($data['id_pregunta'] !== $current_question_id) {
                    $current_question_id = $data['id_pregunta'];

                    if ($data['criterio'] !== $current_criterio_id) {
                        $current_criterio_id = $data['criterio'];
                        // Añadir el criterio
                        $sheet->setCellValue('A' . $row, $data['id_criterio']);
                        $sheet->setCellValue('B' . $row, 'Criterio');
                        $sheet->setCellValue('C' . $row, $data['criterio']);
                        $sheet->setCellValue('D' . $row, $data['texto_criterio']);
                        $row++;
                    }

                    // Añadir la pregunta y su criterio
                    $sheet->setCellValue('A' . $row, $data['id_pregunta']);
                    $sheet->setCellValue('B' . $row, 'Pregunta ' . $question_counter . ':');
                    $sheet->setCellValue('C' . $row, $data['pregunta']);
                    $sheet->setCellValue('D' . $row, 'Coloca 1 o 0 según la respuesta que quieras colocar');
                    $row++;

                    $question_counter++;
                    $response_counter = 1; // Reiniciar el contador de respuestas para cada pregunta
                }

                // Añadir las respuestas y una celda para la respuesta del estudiante
                $sheet->setCellValue('A' . $row, $data['id_respuesta']);
                $sheet->setCellValue('B' . $row, 'Respuesta ' . $response_counter . ':');
                $sheet->setCellValue('C' . $row, $data['respuesta']);
                $sheet->setCellValue('D' . $row, ''); // Celda para que el estudiante responda
                $row++;
                $response_counter++;
            }

            // Configurar el nombre del archivo
            $filename = 'examen_' . $id_examen . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Escribir el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');

            // Cerrar la conexión a la base de datos
            $conn->close();
            exit();
        } else {
            echo "Error al preparar la consulta de preguntas: " . $conn->error;
            http_response_code(500); // Error interno del servidor
        }
    } else {
        echo "El campo id_examen es requerido y debe ser un número.";
        http_response_code(400); // Solicitud incorrecta
    }
} else {
    echo "Método de solicitud no permitido.";
    http_response_code(405); // Método no permitido
}
?>
