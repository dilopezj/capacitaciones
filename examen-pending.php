<?php
session_start();

include 'conexion/conexion.php';

$id_estudiante = 0;
if ($_SESSION['id_perfil'] == 2) {
    $id_estudiante = $_SESSION['identificacion'];
}

$id_modulo = 0;
if (isset($_GET['m'])) {
    $id_modulo = $_GET['m'];
}

$sqlExamenes = "SELECT 
CONCAT(es.id_estudiante,' ', es.nombre,' ', es.apellido) nmb_estudiante,
ea.id_asignacion,
e.tipo_examen,
ea.fecha_programado,
e.id_examen,
e.nombre_examen,
e.descripcion, ea.asistencia,
m.nombre  nombre_modulo,
ea.salon,(select s.descripcion from salones s where s.id = ea.salon )salon_desc,
CONCAT(i.nombres,' ', i.apellidos) nmb_instructor,
COALESCE((select er.porcentaje from respuestas_estudiantes er where er.id_estudiante = ea.id_estudiante AND er.id_moludo = ea.id_modulo and er.id_examen = ea.id_examen ),'')porcentaje,
COALESCE((select DATE_FORMAT(er.fecha_realizacion, '%Y-%m-%d')  from respuestas_estudiantes er where er.id_estudiante = ea.id_estudiante AND er.id_moludo = ea.id_modulo and er.id_examen = ea.id_examen ),'')fecha_realizacion    
FROM examenes_asignados ea
JOIN examenes e ON ea.id_modulo = e.id_modulo AND  ea.id_examen = e.id_examen
JOIN estudiantes es ON ea.id_estudiante = es.id_estudiante
JOIN modulos m ON e.id_modulo = m.id_modulo
JOIN instructores i ON i.identificacion = ea.id_instructor";
if ($id_estudiante != 0) {
    $sqlExamenes .= " WHERE  ea.id_estudiante = $id_estudiante ";
    if ($id_modulo != 0) {
        $sqlExamenes .= " AND  ea.id_modulo = $id_modulo ";
    }
} else {
    if ($id_modulo != 0) {
        $sqlExamenes .= " WHERE  ea.id_modulo = $id_modulo ";
    }
}


$resultadoExamenes = $conn->query($sqlExamenes);
?>
<!--CONTENT-->
<div class="container-fluid">
    <div class="table-responsive">
        <fieldset>
            <table id="example" class="display table table-dark table-sm" style="width:100%">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>MODULO</th>
                        <th>EXAMEN</th>
                        <th>TIPO</th>
                        <th>DESCRIPCION</th>
                        <?php if ($id_estudiante == 0) { ?>
                            <th>ESTUDIANTE</th>
                        <?php } ?>
                        <th>PUNTUACIÓN</th>
                        <th>FECHA PROGRAMADO</th>
                        <th>SALON</th>
                        <th>INSTRUCTOR</th>
                        <th>FECHA REALIZADO</th>
                        <th>REALIZAR EXAMEN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultadoExamenes->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($filaExamenes = $resultadoExamenes->fetch_assoc()) {
                            ?>
                            <tr class="text-center">
                                <td><?php echo $filaExamenes["nombre_modulo"] ?></td>
                                <td><?php echo $filaExamenes["nombre_examen"] ?></td>
                                <td><?php echo $filaExamenes["tipo_examen"] == "FINAL" ? "EXAMEN DE CAMPO" : $filaExamenes["tipo_examen"] ?>
                                </td>
                                <td><?php echo $filaExamenes["descripcion"] ?></td>
                                <?php if ($id_estudiante == 0) { ?>
                                    <td><?php echo $filaExamenes["nmb_estudiante"] ?></td>
                                <?php } ?>
                                <td><?php
                                if (is_numeric($filaExamenes["porcentaje"])) {
                                       echo $rounded_num = round($filaExamenes["porcentaje"], 2); // Round to 2 decimal places
                                        // Further processing with $rounded_num
                                    } else {
                                        echo $filaExamenes["porcentaje"];
                                    }
                                   ?></td>
                                <td><?php echo $filaExamenes["fecha_programado"] ?></td>
                                <td><?php echo $filaExamenes["salon_desc"] ?></td>
                                <td><?php echo $filaExamenes["nmb_instructor"] ?></td>
                                <td><?php echo $filaExamenes["fecha_realizacion"] ?></td>
                                <td>
                                    <?php
                                    if ($filaExamenes["tipo_examen"] != 'FINAL') {
                                        if ($filaExamenes["asistencia"] != 0 && $filaExamenes["asistencia"] !=  null) {
                                            if ($id_estudiante != 0) {
                                                if ($filaExamenes["fecha_realizacion"] == "" || $filaExamenes["fecha_realizacion"] == null) {
                                                    $idExamen = $filaExamenes["id_examen"];
                                                    $titleExamen = $filaExamenes["nombre_modulo"] . " <br> " . $filaExamenes["nombre_examen"];
                                                    ?>
                                                    <button type="button" class="btn btn-raised btn-sm btn-sura"
                                                            onclick="realizarExamen('<?php echo $idExamen ?>', '<?php echo $titleExamen ?>')"
                                                            title="<?php echo $filaExamenes["nombre_examen"] ?>">Realizar Examen</button>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "No hay evaluaciones disponibles en este momento.";
                    }
                    ?>
                </tbody>
            </table>
        </fieldset>
    </div>
</div>