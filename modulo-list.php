<?php
session_start();

include 'conexion/conexion.php';

$sqlModulos = "SELECT m.nombre  nombre_modulo, m.id_modulo, m.fecha_vigencia
FROM modulos m order by m.nombre" ;

$resultadoModulos = $conn->query($sqlModulos);
?>
<!--CONTENT-->
<div class="container-fluid">
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i class="fas fa-plus"></i> &nbsp; Crear Modulos</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i class="fas fa-file"></i> &nbsp; Cargar Excel</button>
            </p>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>MODULO</th>                    
                    <th>VIGENCIA</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($resultadoModulos->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaModulos = $resultadoModulos->fetch_assoc()) {
                        ?>
                <tr class="text-center">
                    <td><?php echo $filaModulos["id_modulo"] ?></td>
                    <td><?php echo $filaModulos["nombre_modulo"] ?></td>
                    <td><?php echo $filaModulos["fecha_vigencia"] ?></td>
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
                    echo "No hay modulos disponibles en este momento.";
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
                <h5 class="modal-title" id="Modalcrear">Crear Modulos</h5>
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



