<?php
session_start();

include 'conexion/conexion.php';

$sqlExamenes = "SELECT  e.id_examen, e.nombre_examen, e.descripcion, m.nombre  nombre_modulo, m.id_modulo, e.tipo_examen, e.fecha_vigencia, e.activo
FROM examenes e 
JOIN modulos m ON e.id_modulo = m.id_modulo  order by m.nombre , e.nombre_examen ";

$resultadoExamenes = $conn->query($sqlExamenes);
?>
<!--CONTENT-->
<div class="container-fluid">
    <p class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
                class="fas fa-plus"></i> &nbsp; Crear Evaluaciones</button>
        <a class="btn btn-link btn-primary" href="archivos/evaluaciones.xlsx" download><i class="fas fa-file"></i>
            &nbsp; Formato Excel</a>
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
                        <th>EVALUACIÓN</th>
                        <th>DESCRIPCIÓN</th>
                        <th>TIPO</th>
                        <th>ESTADO</th>
                        <th>VIGENCIA</th>
                        <!-- <th>ELIMINAR</th> -->
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
                                <td><?php echo $filaExamenes["tipo_examen"] == "FINAL" ? "EXAMEN DE CAMPO" : $filaExamenes["tipo_examen"] ?>
                                </td>
                                <td><?php echo $filaExamenes["activo"] == 1 ? 'Activo' : 'Inactivo' ?></td>
                                <td><?php echo $filaExamenes["fecha_vigencia"] ?></td>
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
                        echo "No hay examenes disponibles en este momento.";
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
                <h5 class="modal-title" id="Modalcrear">Crear Evaluación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioExamen" method="post" style="width: 100%;">
                    <fieldset>
                        <legend><i class="far fa-file-archive"></i> &nbsp;Formulario Creación de Evaluación</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <?php
                                        $sqlExamen = "SELECT m.id_modulo, m.nombre nombre_modulo
                                                        FROM  modulos m
                                                        WHERE  CURRENT_DATE() <= m.fecha_vigencia
                                                        ORDER BY m.id_modulo;";
                                        $resultadoExamen = $conn->query($sqlExamen);
                                        ?>
                                        <label for="curso">Curso:</label>
                                        <select class="form-control" id="curso" name="curso">
                                            <!-- Opciones del select con los cursos disponibles -->
                                            <!-- Supongamos que tienes un array $cursosDisponibles que contiene los cursos disponibles -->
                                            <option value="">Seleccione un curso </option>
                                            <?php
                                            if ($resultadoExamen->num_rows > 0) {
                                                // Mostrar las preguntas
                                                while ($curso = $resultadoExamen->fetch_assoc()) { ?>
                                                    <option value="<?php echo $curso['id_modulo']; ?>">
                                                        <?php echo $curso['nombre_modulo']; ?>
                                                    </option>
                                                <?php }
                                            } ?>
                                        </select><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="examen">Evaluación:</label>
                                        <input type="text" id="examen" name="examen" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="descripcion">Descripcion:</label>
                                        <input type="text" id="descripcion" name="descripcion" required maxlength="255"
                                            class='form-control'><br><br>

                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="tipo">Tipo:</label>
                                        <select class="form-control" id="tipo" name="tipo">
                                            <option value="">Seleccione un tipo </option>
                                            <option value="PREVIA">PREVIA</option>
                                            <option value="INTERMEDIA">INTERMEDIA</option>
                                            <option value="FINAL">EXAMEN DE CAMPO</option>
                                        </select><br><br>
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
                        <button type="submit" onclick="crearExamen()"
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
                        <legend><i class="far fa-file-excel"></i> &nbsp;Cargar archivo de Evaluaciones</legend>
                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="archivo">Seleccionar archivo:</label>
                                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                                    accept=".xlsx,.csv">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="cargarArchivoExamen()">Cargar
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