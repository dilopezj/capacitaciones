<?php
session_start();

include 'conexion/conexion.php';

$sqlUsuarios = "SELECT u.id_usuario, u.nombre_usuario, u.correo_usuario, u.id_perfil, p.nombre_perfil, u.estudiante,
COALESCE((SELECT CONCAT(e.nombre,' ', e.apellido) nmb_estudiante FROM estudiantes e WHERE e.id_estudiante = u.estudiante),'') nmb_estudiante 
FROM usuarios u JOIN perfiles p ON p.id_perfil = u.id_perfil 
ORDER BY u.id_usuario ;" ;

$resultadoUsuarios = $conn->query($sqlUsuarios);
?>
<!--CONTENT-->
<div class="container-fluid">
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i class="fas fa-plus"></i> &nbsp; Crear Usuarios</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i class="fas fa-file"></i> &nbsp; Cargar Excel</button>
            </p>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>#</th>
                    <th>USUARIO</th>                    
                    <th>CORREO</th>
                    <th>PERFIL</th>
                    <th>ESTUDIANTE</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($resultadoUsuarios->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaUsuarios = $resultadoUsuarios->fetch_assoc()) {
                        ?>
                <tr class="text-center">
                    <td><?php echo $filaUsuarios["id_usuario"] ?></td>
                    <td><?php echo $filaUsuarios["nombre_usuario"] ?></td>
                    <td><?php echo $filaUsuarios["correo_usuario"] ?></td>
                    <td><?php echo $filaUsuarios["nombre_perfil"] ?></td>
                    <td><?php echo $filaUsuarios["nmb_estudiante"] ?></td>
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
                    echo "No hay Usuarios disponibles en este momento.";
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
                <h5 class="modal-title" id="Modalcrear">Crear Usuarios</h5>
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



