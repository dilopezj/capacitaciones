<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la biblioteca PhpSpreadsheet
require './../vendor/autoload.php';

include 'conexion.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Consulta SQL para obtener los datos
$sql = "SELECT 
        m.id_modulo,
        m.nombre AS nombre_modulo,
        e.id_examen,
        e.nombre_examen AS nombre_examen,
        re.total_preguntas AS total_respuestas,
        SUM(re.respuestas_incorrectas) AS total_incorrectas,
       round((re.respuestas_incorrectas / re.total_preguntas) * 100 ,2) AS porcentaje_incorrectas
    FROM 
        respuestas_estudiantes re
    JOIN 
        modulos m ON re.id_moludo = m.id_modulo
    JOIN 
        examenes e ON re.id_examen = e.id_examen
    GROUP BY 
        m.id_modulo, e.id_examen
    ORDER BY 
        m.id_modulo, e.id_examen;";

$result = $conn->query($sql);

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configurar los encabezados
$sheet->setCellValue('A1', 'ID M¨®dulo');
$sheet->setCellValue('B1', 'Nombre M¨®dulo');
$sheet->setCellValue('C1', 'ID Examen');
$sheet->setCellValue('D1', 'Nombre Examen');
$sheet->setCellValue('E1', 'Total Respuestas');
$sheet->setCellValue('F1', 'Total Incorrectas');
$sheet->setCellValue('G1', 'Porcentaje Incorrectas');

// Llenar los datos
$row = 2; // Comenzar en la segunda fila
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id_modulo']);
    $sheet->setCellValue('B' . $row, $data['nombre_modulo']);
    $sheet->setCellValue('C' . $row, $data['id_examen']);
    $sheet->setCellValue('D' . $row, $data['nombre_examen']);
    $sheet->setCellValue('E' . $row, $data['total_respuestas']);
    $sheet->setCellValue('F' . $row, $data['total_incorrectas']);
    $sheet->setCellValue('G' . $row, $data['porcentaje_incorrectas']);
    $row++;
}

// Configura el nombre del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte01.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Cerrar la conexi¨®n a la base de datos
$conn->close();
exit();
?>
