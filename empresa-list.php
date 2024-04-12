<?php
session_start();

include 'conexion/conexion.php';

$sqlEmpresas = "SELECT  em.nit, em.nombre empresa, em.departamento, em.municipio_ciudad 
FROM empresas em  
ORDER BY empresa;" ;

$resultadoEmpresas = $conn->query($sqlEmpresas);
?>
<!--CONTENT-->
<div class="container-fluid">
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i class="fas fa-plus"></i> &nbsp; Agregar Empresa</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i class="fas fa-file"></i> &nbsp; Cargar Excel</button>
            </p>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>EMPRESA</th>                    
                    <th>DEPARTAMENTO</th>
                    <th>MUNICIPIO / CIUDAD</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($resultadoEmpresas->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaEmpresas = $resultadoEmpresas->fetch_assoc()) {
                        ?>
                <tr class="text-center">
                    <td><?php echo $filaEmpresas["nit"] ?></td>
                    <td><?php echo $filaEmpresas["empresa"] ?></td>
                    <td><?php echo $filaEmpresas["departamento"] ?></td>
                    <td><?php echo $filaEmpresas["municipio_ciudad"] ?></td>
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
                    echo "No hay Empresas disponibles en este momento.";
                }
                ?>      
                
            </tbody>
        </table>
    </div>    
</div>

<!-- MODAL Crear Empresa -->
<div class="modal fade" id="Modalcrear" tabindex="-1" role="dialog" aria-labelledby="Modalcrear" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Crear Empresas</h5>
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



