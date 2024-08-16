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
(SELECT ri.porcentaje_empresa FROM respuestas_instructor ri WHERE ri.id_estudiante = ea.id_estudiante AND ri.id_modulo = ea.id_modulo AND ri.id_examen = ea.id_examen) porcentaje_empresa,
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
    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ListExamModulos" >Descargar Formato Evaluacion</button></div>
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
                                <?php if( $filaExamenes["porcentaje_final"] != null && $filaExamenes["porcentaje_empresa"] != null ){ ?>
                                 <td><?php echo "Apreciación:" .  $filaExamenes["porcentaje_final"]. "% <br>Verificación Empresa:" .  $filaExamenes["porcentaje_empresa"] ."%" ?></td>
                                <?php } else { ?>
                                 <td></td>
                                <?php } ?>
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
                                <td><?php echo  $filaExamenes["porcentaje"] != null ? round($filaExamenes["porcentaje"], 2) : $filaExamenes["porcentaje"]  ?></td>
                                <td><?php echo $filaExamenes["fecha_realizacion"] ?></td>
                            <?php } ?>
                            <td><?php echo $filaExamenes["departamento"] . "/" . $filaExamenes["municipio"] ?></td>
                            <td>
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
                                <?php
                                if ($_SESSION['id_perfil'] != 2) {
                                    if ($filaExamenes["fecha_realizacion"] == "" || $filaExamenes["fecha_realizacion"] == null) {
                                        ?>
                                        <button type="button" class="btn btn-raised btn-sm btn-danger" onclick="document.getElementById('fileInput_<?php echo $id_estudiante_ ?>_<?php echo $id_modulo_ ?>').click();">Cargar excel</button>
                                        <input type="file" id="fileInput_<?php echo $id_estudiante_ ?>_<?php echo $id_modulo_ ?>" style="display: none;" accept=".xlsx" onchange="cargarExcel_evaluador('<?php echo $id_estudiante_ ?>', '<?php echo $id_modulo_ ?>', this)">
                                    <?php }
                                }
                                ?>
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

<!-- MODAL Crear Examen -->
<div class="modal fade" id="ListExamModulos" tabindex="-1" role="dialog" aria-labelledby="ListExamModulos" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Formato Evaliaciones en Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioEstudiante" method="post" style="width: 100%;">
                    <fieldset>
                        <legend><i class="far fa-user"></i> &nbsp;Selecciona una evaluación a exportar:</legend>
                        <div class="container-fluid">
                            <div class="row">
                                 <?php
                $sqlExamen = "SELECT e.id_examen, e.nombre_examen, e.descripcion, m.id_modulo, m.nombre nombre_modulo, e.tipo_examen
                                FROM examenes e
                                JOIN modulos m ON e.id_modulo = m.id_modulo
                                WHERE  CURRENT_DATE() <= e.fecha_vigencia  AND e.activo and  e.tipo_examen = 'FINAL'
                                ORDER BY m.nombre;";
                $resultadoExamen = $conn->query($sqlExamen);
                ?>
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>
                                <?php
                                if ($resultadoExamen->num_rows > 0) {
                                    // Mostrar las preguntas
                                    while ($filaExamen = $resultadoExamen->fetch_assoc()) {
                                        $temp_examen =$filaExamen['id_examen'];
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $filaExamen["nombre_modulo"] ?></td>
                                            <td><?php echo $filaExamen["nombre_examen"] ?> : <?php echo $filaExamen["descripcion"] ?></td>
                                            <td><?php echo  $filaExamen["tipo_examen"] == "FINAL" ? "EXAMEN DE CAMPO" : $filaExamen["tipo_examen"]   ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" id="btn_exportar-examen" onclick="exportarExamen('<?php echo $temp_examen ?>')"><i class="fas fa-file"></i></button>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>                                
                            </tbody>
                        </table>
                    </div>
                </div>
                                
                            </div>
                        </div>
                        <!-- Agrega los demás campos del formulario aquí -->
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


