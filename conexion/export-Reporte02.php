<?php

include 'conexion.php';

session_start();

// Incluir la biblioteca PhpSpreadsheet
require './../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Consulta SQL para obtener los datos
$sql = "SELECT 
    m.id_modulo,
    m.nombre AS nombre_modulo,
    e.id_examen,
    e.nombre_examen AS nombre_examen,
    p.id_pregunta,
    p.texto_pregunta AS pregunta_label,
    e.tipo_examen,
    COUNT(re.id_pregunta) AS total_preguntas_incorrectas,
    (select s.descripcion from salones s where s.id = ea.salon) salon,
    COALESCE((select CONCAT(i.nombres, ' ', i.apellidos) from instructores i where i.identificacion = ea.id_instructor),'') instructor
FROM 
    resp_estudiante_detalle re
JOIN 
    modulos m ON re.id_modulo = m.id_modulo
JOIN 
    examenes e ON re.id_examen = e.id_examen
JOIN 
    preguntas p ON re.id_pregunta = p.id_pregunta
JOIN 
    examenes_asignados ea ON re.id_modulo = ea.id_modulo and re.id_examen = ea.id_examen
WHERE 
    re.correcta = 0  -- Filtrar solo las respuestas incorrectas
GROUP BY 
    m.id_modulo, e.id_examen, p.id_pregunta,e.tipo_examen,ea.salon, ea.id_instructor
ORDER BY 
    m.id_modulo, e.id_examen,e.tipo_examen, total_preguntas_incorrectas,ea.salon, ea.id_instructor DESC;";

$result = $conn->query($sql);

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Establecer encabezados de columnas
$sheet->setCellValue('A1', 'ID Módulo');
$sheet->setCellValue('B1', 'Nombre Módulo');
$sheet->setCellValue('C1', 'ID Examen');
$sheet->setCellValue('D1', 'Nombre Examen');
$sheet->setCellValue('E1', 'ID Pregunta');
$sheet->setCellValue('F1', 'Pregunta');
$sheet->setCellValue('G1', 'Tipo Examen');
$sheet->setCellValue('H1', 'Total Preguntas Incorrectas');
$sheet->setCellValue('I1', 'Salon');
$sheet->setCellValue('J1', 'Instructor');

// Llenar los datos desde la consulta
$row = 2;
while ($data = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['id_modulo']);
    $sheet->setCellValue('B' . $row, $data['nombre_modulo']);
    $sheet->setCellValue('C' . $row, $data['id_examen']);
    $sheet->setCellValue('D' . $row, $data['nombre_examen']);
    $sheet->setCellValue('E' . $row, $data['id_pregunta']);
    $sheet->setCellValue('F' . $row, $data['pregunta_label']);
    $sheet->setCellValue('G' . $row, $data['tipo_examen']);
    $sheet->setCellValue('H' . $row, $data['total_preguntas_incorrectas']);
    $sheet->setCellValue('I' . $row, $data['salon']);
    $sheet->setCellValue('J' . $row, $data['instructor']);
    $row++;
}

// Configura el nombre del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte02.xlsx"');
header('Cache-Control: max-age=0');

// Escribir el archivo Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Cerrar la conexión a la base de datos
$conn->close();
exit();
?>
