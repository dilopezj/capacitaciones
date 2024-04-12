<?php
session_start();

include 'conexion/conexion.php';

$sqlExamenes = "SELECT  e.id_examen, e.nombre_examen, e.descripcion, m.nombre  nombre_modulo, m.id_modulo, e.tipo_examen, e.fecha_vigencia, e.activo
FROM examenes e 
JOIN modulos m ON e.id_modulo = m.id_modulo  order by m.nombre , e.nombre_examen " ;

$resultadoExamenes = $conn->query($sqlExamenes);
?>
<!--CONTENT-->
<div class="container-fluid">
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i class="fas fa-plus"></i> &nbsp; Crear Examenes</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i class="fas fa-file"></i> &nbsp; Cargar Excel</button>
            </p>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>MODULO</th>
                    <th>EXAMEN</th>
                    <th>DESCRIPCIÓN</th>
                    <th>TIPO</th>
                    <th>ESTADO</th>
                    <th>VIGENCIA</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($resultadoExamenes->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaExamenes = $resultadoExamenes->fetch_assoc()) {
                        ?>
                <tr class="text-center">
                    <td><?php echo $filaExamenes["id_examen"] ?></td>
                    <td><?php echo $filaExamenes["nombre_modulo"] ?></td>
                    <td><?php echo $filaExamenes["nombre_examen"] ?></td>
                    <td><?php echo $filaExamenes["descripcion"] ?></td>
                    <td><?php echo $filaExamenes["tipo_examen"] ?></td>
                    <td><?php echo $filaExamenes["activo"] == 1 ? 'Activo' : 'Inactivo' ?></td>
                    <td><?php echo $filaExamenes["fecha_vigencia"] ?></td>
                    <td>
                        <form action="">
                            <button type="button" class="btn btn-warning">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
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

<!-- MODAL Crear Examen -->
<div class="modal fade" id="Modalcrear" tabindex="-1" role="dialog" aria-labelledby="Modalcrear" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Crear Examen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">               
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL Cargar Archivos -->
<div class="modal fade" id="ModalArchivo" tabindex="-1" role="dialog" aria-labelledby="ModalArchivo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalArchivo">Cargar Archivo Excel/Csv</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



