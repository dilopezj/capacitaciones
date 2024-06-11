<?php
session_start();

include 'conexion/conexion.php';

$sqlModulos = "SELECT m.nombre  nombre_modulo, m.id_modulo, m.fecha_vigencia
FROM modulos m order by m.nombre";

$resultadoModulos = $conn->query($sqlModulos);
?>
<!--CONTENT-->
<div class="container-fluid">
    <p class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
                class="fas fa-plus"></i> &nbsp; Crear Cursos</button>
        <a class="btn btn-link btn-primary" href="archivos/cursos.xlsx" download><i class="fas fa-file"></i> &nbsp;
            Formato Excel</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i
                class="fas fa-file"></i> &nbsp; Cargar Excel</button>
    </p>
    <div class="table-responsive">
        <fieldset>
            <table id="example" class="display table table-dark table-sm" style="width:100%">
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>CURSO</th>
                        <th>VIGENCIA</th>
                        <!-- <th>ELIMINAR</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultadoModulos->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($filaModulos = $resultadoModulos->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $filaModulos["id_modulo"] ?></td>
                                <td><?php echo $filaModulos["nombre_modulo"] ?></td>
                                <td><?php echo $filaModulos["fecha_vigencia"] ?></td>
                                <!-- <td>
                                <form action="">
                                    <button type="button" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td> -->
                            </tr>
                            <?php
                        }
                    } else {
                        echo "No hay Cursos disponibles en este momento.";
                    }
                    ?>

                </tbody>
            </table>
        </fieldset>
    </div>
</div>

<!-- MODAL Crear Examen -->
<div class="modal fade" id="Modalcrear" tabindex="-1" role="dialog" aria-labelledby="Modalcrear" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Crear Cursos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioCursos" method="post" style="width: 100%;">
                    <fieldset>
                        <legend><i class="far fa-file-archive"></i> &nbsp;Formulario Creación de Cursos</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="curso">Curso:</label>
                                        <input type="text" id="curso" name="curso" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="vigencia">Vigencia:</label>
                                        <input type="date" id="vigencia" name="vigencia" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" onclick="crearCursos()"
                            class="btn btn-raised btn-success btn-md">Guardar</button>
                    </p>
                </form>
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
                <form id="formArchivo" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <legend><i class="far fa-file-excel"></i> &nbsp;Cargar archivo de Cursos</legend>
                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="archivo">Seleccionar archivo:</label>
                                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                                    accept=".xlsx,.csv">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="cargarArchivoCursos()">Cargar
                                Archivo</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>