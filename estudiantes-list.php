<?php
session_start();

include 'conexion/conexion.php';

$sqlEstudiantes = "SELECT e.id_estudiante, e.nombre, e.apellido, e.id_empresa, em.nombre empresa, em.departamento, em.municipio_ciudad, e.tipo_identificacion, e.celular,
e.fecha_nac, e.edad, e.direccion 
FROM estudiantes e JOIN empresas em ON em.nit = e.id_empresa 
ORDER BY e.apellido;" ;

$resultadoEstudiantes = $conn->query($sqlEstudiantes);
?>
<!--CONTENT-->
<div class="container-fluid">
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i class="fas fa-plus"></i> &nbsp; Crear Estudiantes</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i class="fas fa-file"></i> &nbsp; Cargar Excel</button>
            </p>
    <div class="table-responsive">
        <table class="table table-dark table-sm">
            <thead>
                <tr class="text-center roboto-medium">
                    <th>DOCUMENTO IDENTIDAD</th>
                    <th>TIPO DOCUMENTO</th>  
                    <th>NOMBRES</th>                    
                    <th>APELLIDOS</th>
                    <th>FECHA NACIMIENTO</th>
                    <th>EDAD</th>
                    <th>DIRECCION</th>
                    <th>CELULAR</th>
                    <th>NIT</th>
                    <th>EMPRESA</th>
                    <th>ELIMINAR</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($resultadoEstudiantes->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaEstudiantes = $resultadoEstudiantes->fetch_assoc()) {
                        ?>
                <tr class="text-center">
                    <td><?php echo $filaEstudiantes["id_estudiante"] ?></td>
                    <td><?php echo $filaEstudiantes["tipo_identificacion"] ?></td>
                    <td><?php echo $filaEstudiantes["nombre"] ?></td>
                    <td><?php echo $filaEstudiantes["apellido"] ?></td>
                    <td><?php echo $filaEstudiantes["fecha_nac"] ?></td>
                    <td><?php echo $filaEstudiantes["edad"] ?></td>
                    <td><?php echo $filaEstudiantes["direccion"] ?></td>
                    <td><?php echo $filaEstudiantes["celular"] ?></td>
                    <td><?php echo $filaEstudiantes["id_empresa"] ?></td>
                    <td><?php echo $filaEstudiantes["empresa"] ?></td>
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
                    echo "No hay Estudiantes disponibles en este momento.";
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
                <h5 class="modal-title" id="Modalcrear">Crear Estudiantes</h5>
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



