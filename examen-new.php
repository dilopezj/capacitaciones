<?php
session_start();

include 'conexion/conexion.php';
?>


<!--CONTENT-->
<div class="container-fluid">
    <div class="container-fluid form-neon">
        <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR ESTUDIANTE - EXAMEN</p>
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalEstudiante"><i class="fas fa-user-plus"></i> &nbsp; Seleccionar estudiante</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalExamen"><i class="fas fa-file"></i> &nbsp; Seleccionar Examen</button>
            </p>
            <div id="estudiante">
                <span class="roboto-medium">ESTUDIANTE:</span> 
                <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un estudiante</span>
                <!-- <form action="" style="display: inline-block !important;">
                     Carlos Alfaro
                     <button type="button" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                 </form> -->
            </div>
            <input type="hidden" id="id_estudent" value="0" />
            <div class="table-responsive">
                <table class="table table-dark table-sm" id="examen">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>ID</th>
                            <th>MODULO</th>
                            <th>EXAMEN</th>
                            <th>DESCRIPCION</th>
                            <th>FECHA ASIGNACION</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
            </div>
            <p class="text-center" style="margin-top: 40px;">
                <button type="reset" class="btn btn-raised btn-secondary btn-sm" onclick="limpiar()">
                    <i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR
                </button>               
            </p>
        </div>
    </div>
</div>


<!-- MODAL ESTUDIANTE -->
<div class="modal fade" id="ModalEstudiante" tabindex="-1" role="dialog" aria-labelledby="ModalEstudiante" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEstudiante">Seleccionar Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">               
                <?php
                $sqlEstudiantes = "SELECT `e`.`id_estudiante`, `e`.`nombre`, `e`.`apellido`, `e`.`id_empresa` , `s`.`nombre` `nmb_empresa`  FROM `estudiantes` `e` ,`empresas` `s` WHERE `e`.`id_empresa`= `s`.`nit` order by `e`.`apellido`;  ";
                $resultadoEstud = $conn->query($sqlEstudiantes);
                ?>
                <div class="container-fluid">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>
                                <?php
                                if ($resultadoEstud->num_rows > 0) {
                                    // Mostrar las preguntas
                                    while ($filaEstud = $resultadoEstud->fetch_assoc()) {
                                        $temp_nmb =  $filaEstud['nombre'] .' '. $filaEstud['apellido'] ;
                                        $temp_id = $filaEstud['id_estudiante'];
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $filaEstud["id_estudiante"] ?> - <?php echo $filaEstud["nombre"] . " " . $filaEstud["apellido"] ?>
                                                <br><?php echo $filaEstud["id_empresa"] . " " . $filaEstud["nmb_empresa"] ?></td>
                                            <td>
                                                <form action="">
                                                    <button id="btn_seleccionar_estudiante" onclick="asignarEstudiante('<?php echo $temp_id ?>','<?php echo $temp_nmb ?>')" type="button" class="btn btn-primary"><i class="fas fa-user-plus"></i></button>
                                                </form>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL Examenes -->
<div class="modal fade" id="ModalExamen" tabindex="-1" role="dialog" aria-labelledby="ModalExamen" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalExamen">Asignar Examen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $sqlExamen = "SELECT e.id_examen, e.nombre_examen, e.descripcion, m.id_modulo, m.nombre nombre_modulo, e.tipo_examen
                                FROM examenes e
                                JOIN modulos m ON e.id_modulo = m.id_modulo
                                WHERE  CURRENT_DATE() <= e.fecha_vigencia  AND e.activo
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
                                            <td>MODULO: <?php echo $filaExamen["nombre_modulo"] ?></td>
                                            <td><?php echo $filaExamen["nombre_examen"] ?> : <?php echo $filaExamen["descripcion"] ?></td>
                                            <td>Tipo: <?php echo $filaExamen["tipo_examen"] ?></td>
                                            <td>
                                                <form action="">
                                                <button type="button" class="btn btn-primary" id="btn_asignar-examen" onclick="asignarExamen('<?php echo  $temp_examen ?>')" ><i class="fas fa-file"></i></button>
                                                </form>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



