<?php
session_start();

include 'conexion/conexion.php';

 $id_estudiante = 0;
if($_SESSION['estudiante'] != 0){
    $id_estudiante = $_SESSION['estudiante'];
}

$sqlExamenes = "SELECT 
     ea.id_asignacion,
    ea.tipo_examen,
    ea.fecha_asignacion,
    e.id_examen,
    e.nombre_examen,
    e.descripcion,
    m.nombre  nombre_modulo,
    NVL((select er.porcentaje from respuestas_estudiantes er where er.id_estudiante = ea.id_estudiante and er.id_examen = ea.id_examen ),'')porcentaje,
    NVL((select er.fecha_realizacion  from respuestas_estudiantes er where er.id_estudiante = ea.id_estudiante and er.id_examen = ea.id_examen ),'')fecha_realizacion    
FROM examenes_asignados ea
JOIN examenes e ON ea.id_examen = e.id_examen
JOIN estudiantes es ON ea.id_estudiante = es.id_estudiante
JOIN modulos m ON e.id_modulo = m.id_modulo " ;
if($id_estudiante != 0){
  $sqlExamenes .= " WHERE ea.id_estudiante = $id_estudiante; "; 
}


$resultadoExamenes = $conn->query($sqlExamenes);
?>
<!--CONTENT-->
<div class="container-fluid">
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>EXAMEN</th>
                    <th>MODULO</th>                                      
                    <th>PUNTUACIÓN</th>
                    <th>FECHA HABILITADO</th>  
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
                        <tr class="text-center" >
                            <td><?php echo $filaExamenes["nombre_examen"] ?></td>
                            <td><?php echo $filaExamenes["nombre_modulo"] ?></td>
                            <td><?php echo $filaExamenes["porcentaje"] ?></td>
                            <td><?php echo $filaExamenes["fecha_asignacion"]  ?></td>
                            <td><?php echo $filaExamenes["fecha_realizacion"]  ?></td>
                            <td> 
                              <?php if( $filaExamenes["fecha_realizacion"] == "" || $filaExamenes["fecha_realizacion"] == null) { ?>
                                <button type="button" class="btn btn-raised btn-sm btn-sura" onclick="realizarExamen('<?php echo $filaExamenes["id_examen"] ?>', '<?php echo $filaExamenes["nombre_examen"] ?>')" title="<?php echo $filaExamenes["nombre_examen"] ?>" >Realizar Examen</button>
                              <?php } ?>
                            </td>
                        </tr>  
                        <?php
                    }
                } else {
                    echo "No hay examenes disponibles en este momento.";
                }
                ?>      
            </tbody>
        </table>
    </div>    
</div>