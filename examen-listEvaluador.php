<?php
session_start();

include 'conexion/conexion.php';

$id_identificacion = 0;
if ($_SESSION['id_perfil'] != 2) {
    $id_identificacion = $_SESSION['identificacion'];
}

$sqlExamenes = " SELECT em.nit, em.nombre empresa, 
m.id_modulo, m.nombre curso,
  e.id_examen, e.nombre_examen evaluacion, e.tipo_examen, 
es.id_estudiante, CONCAT(es.nombre,' ',es.apellido ) estudiante,
ea.fecha_programado, ea.salon, ea.asistencia, ea.fecha_asistencia,
(SELECT re.porcentaje FROM respuestas_estudiantes re WHERE re.id_estudiante = ea.id_estudiante AND re.id_moludo = ea.id_modulo AND re.id_examen = ea.id_examen) porcentaje,
(SELECT re.fecha_realizacion FROM respuestas_estudiantes re WHERE re.id_estudiante = ea.id_estudiante AND re.id_moludo = ea.id_modulo AND re.id_examen = ea.id_examen) fecha_realizacion,
(SELECT ri.porcentaje FROM respuestas_instructor ri WHERE ri.id_estudiante = ea.id_estudiante AND ri.id_modulo = ea.id_modulo AND ri.id_examen = ea.id_examen) porcentaje_final,
(SELECT ri.fecha_realizado FROM respuestas_instructor ri WHERE ri.id_estudiante = ea.id_estudiante AND ri.id_modulo = ea.id_modulo AND ri.id_examen = ea.id_examen) fecha_realizacion_final,
  em.regional, em.ciudad,
(select d.departamento from departamentos d where d.id_departamento = em.regional)departamento,
(select mu.municipio from municipios mu where mu.id_municipio = em.ciudad and mu.departamento_id = em.regional)municipio,
(select s.descripcion from salones s where s.id = ea.salon )salon_desc
FROM modulos m 
JOIN examenes e ON e.id_modulo = m.id_modulo
JOIN examenes_asignados ea ON ea.id_modulo = m.id_modulo AND ea.id_examen = e.id_examen
JOIN estudiantes es ON es.id_estudiante = ea.id_estudiante
JOIN empresas em ON em.nit = es.id_empresa ";

if ($id_identificacion != 0) {
    $sqlExamenes .= " WHERE ea.id_instructor = $id_identificacion ";
}
$sqlExamenes .= " ORDER BY em.nombre, m.nombre, e.nombre_examen, estudiante ; ";

$resultadoExamenes = $conn->query($sqlExamenes);
?>
<!--CONTENT-->
<div class="container-fluid">
    <div><button type="button" class="btn btn-raised btn-sm btn-success" >Descargar Formato Evaluacion</button></div>
    <div class="table-responsive">
        <table  id="example" class="display table table-dark table-sm" style="width:auto">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>EMPRESA</th>
                    <th>CURSO</th>
                    <th>EVALUACIÓN</th>
                    <th>TIPO EXAMEN</th>
                    <th>IDENTIFICACIÓN</th>
                    <th>ESTUDIANTE</th>
                    <th>FECHA PROGRAMACIÓN</th>
                    <th>SALON</th>
                    <th>ASISTENCIA</th>
                    <th>FECHA ASISTENCIA</th>
                    <th>PUNTUACIÓN</th>
                    <th>FECHA REALIZADO</th>
                    <th>REGIONAL/CIUDAD</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultadoExamenes->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaExamenes = $resultadoExamenes->fetch_assoc()) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo $filaExamenes["empresa"] ?></td>
                            <td><?php echo $filaExamenes["curso"] ?></td>
                            <td><?php echo $filaExamenes["evaluacion"] ?></td>
                            <td><?php echo $filaExamenes["tipo_examen"] == "FINAL" ? "EXAMEN DE CAMPO" : $filaExamenes["tipo_examen"] ?></td>
                            <td><?php echo $filaExamenes["id_estudiante"] ?></td>
                            <td><?php echo $filaExamenes["estudiante"] ?></td>
                            <td><?php echo $filaExamenes["fecha_programado"] ?></td>                                                      
                            <?php if ($filaExamenes["tipo_examen"] == 'FINAL') { ?>
                                <td>N/A</td>  
                                <td>N/A</td>
                                <td>N/A</td>
                                <td><?php echo $filaExamenes["porcentaje_final"] ?></td>
                                <td><?php echo $filaExamenes["fecha_realizacion_final"] ?></td>
                            <?php } else { ?>
                                <td><?php echo $filaExamenes["salon_desc"] ?></td>  
                                <td>
                                    <?php if (!empty($filaExamenes["fecha_realizacion"])) : ?>
                                        <input type="checkbox" onchange="checkAsistencia(this)" class="attendanceCheckbox" data-test-id='<?php echo $filaExamenes["id_examen"] ?>' data-student-id='<?php echo $filaExamenes["id_estudiante"] ?>' <?php echo ($filaExamenes["asistencia"] == 1) ? "checked" : "" ?> disabled>
                                    <?php else : ?>
                                        <input type="checkbox" onchange="checkAsistencia(this)" class="attendanceCheckbox" data-test-id='<?php echo $filaExamenes["id_examen"] ?>' data-student-id='<?php echo $filaExamenes["id_estudiante"] ?>' <?php echo ($filaExamenes["asistencia"] == 1) ? "checked" : "" ?>>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $filaExamenes["fecha_asistencia"] ?></td>
                                <td><?php echo $filaExamenes["porcentaje"] ?></td>
                                <td><?php echo $filaExamenes["fecha_realizacion"] ?></td>
                            <?php } ?>
                            <td><?php echo $filaExamenes["departamento"] . "/" . $filaExamenes["municipio"] ?></td>
                            <td>
                                <form action="">
                                    <?php
                                    if ($_SESSION['id_perfil'] == 3) {
                                        if ($filaExamenes["tipo_examen"] == 'FINAL' && $filaExamenes["fecha_realizacion_final"] == '') {
                                            $id_estudiante_ = $filaExamenes["id_estudiante"];
                                            $id_modulo_ = $filaExamenes["id_modulo"];
                                            ?>
                                            <button type="button" class="btn btn-raised btn-sm btn-sura"
                                                    onclick="realizarExamen_evaluador('<?php echo $id_estudiante_ ?>', '<?php echo $id_modulo_ ?>')">Realizar</button>
                                                <?php }
                                            }
                                            ?>
                                    <?php if ($_SESSION['id_perfil'] != 2) {
                                        if ($filaExamenes["fecha_realizacion"] == "" || $filaExamenes["fecha_realizacion"] == null) {
                                            ?>
                                            <button type="button" class="btn btn-raised btn-sm btn-danger">Cargar excel</button>
                            <?php }
                        }
                        ?>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "No hay examenes realizados en este momento.";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
