<?php
session_start();

include 'conexion/conexion.php';

$sqlInstructor = "SELECT i.descripcion AS tipo, it.identificacion, it.nombres, it.apellidos, 
g.descripcion AS genero,  it.regional, d.departamento, it.ciudad, mu.municipio
FROM instructores it
JOIN departamentos d ON d.id_departamento = it.regional
JOIN municipios mu ON mu.id_municipio = it.ciudad
JOIN tipo_identificaciones i ON i.id = it.tipo_identificacion
JOIN genero g ON g.id = it.genero
ORDER BY 1,2,4,6,5;";

$resultadoInstructor = $conn->query($sqlInstructor);
?>
<!--CONTENT-->
<div class="container-fluid">
  <p class="text-center">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
        class="fas fa-plus"></i> &nbsp; Crear Instructor</button>
    <a class="btn btn-link btn-primary" href="archivos/instructores.xlsx" download><i class="fas fa-file"></i> &nbsp;
      Formato Excel</a>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i
        class="fas fa-file"></i> &nbsp; Cargar Excel</button>
  </p>
  <div class="table-responsive">
  <fieldset>
    <table id="example" class="display table table-dark table-sm" style="width:100%">
      <thead>
        <tr class="text-center roboto-medium">
          <th>REGIONAL</th>
          <th>CIUDAD</th>
          <th>DOCUMENTO</th>
          <th>TIPO DOCUMENTO</th>
          <th>NOMBRES</th>
          <th>APELLIDOS</th>
          <th>GENERO</th>
          <!-- <th>OPCIONES</th> -->
        </tr>
      </thead>
      <tbody>
        <?php
        if ($resultadoInstructor->num_rows > 0) {
          // Mostrar las preguntas
          while ($filaInstructor = $resultadoInstructor->fetch_assoc()) {
            $nmb_est = $filaInstructor["nombres"] . " " . $filaInstructor["apellidos"];
            ?>
            <tr class="text-center">
              <td><?php echo $filaInstructor["departamento"] ?></td>
              <td><?php echo $filaInstructor["municipio"] ?></td>
              <td><?php echo $filaInstructor["identificacion"] ?></td>
              <td><?php echo $filaInstructor["tipo"] ?></td>
              <td><?php echo $filaInstructor["nombres"] ?></td>
              <td><?php echo $filaInstructor["apellidos"] ?></td>
              <td><?php echo $filaInstructor["genero"] ?></td>
               <!--<td>
                <form action="">
                  <button type="button" class="btn btn-sm btn-warning" style="float:left;">
                    <i class="far fa-user-cog"></i> Editar
                  </button>
                </form> 
              </td>-->
            </tr>
            <?php
          }
        } else {
          echo "No hay Instructor disponibles en este momento.";
        }
        ?>

      </tbody>
    </table>
      </fieldset>
  </div>
</div>

<!-- MODAL Crear Examen -->
<div class="modal fade" id="Modalcrear" tabindex="-1" role="dialog" aria-labelledby="Modalcrear" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modalcrear">Crear Instructor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formularioInstructor" method="post" style="width: 100%;">
          <fieldset>
            <legend><i class="far fa-user"></i> &nbsp;Formulario Creación de Instructor</legend>
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="regional">Regional:</label>
                    <select id="regional" name="regional" required class='form-control' onchange="changeDpto()">
                      <option value="">Seleccione un departamento</option>
                      <?php
                      // Conexión a la base de datos
                      include 'conexion.php';
                      // Consulta SQL para obtener los departamentos
                      $sqlDpto = "SELECT id_departamento, departamento FROM departamentos ORDER BY departamento";
                      $resultadoDpto = $conn->query($sqlDpto);
                      if ($resultadoDpto->num_rows > 0) {
                        while ($dpto = $resultadoDpto->fetch_assoc()) {
                          echo "<option value='" . $dpto["id_departamento"] . "'>" . $dpto["departamento"] . "</option>";
                        }
                      }
                      ?>
                    </select><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="ciudad">Ciudad:</label>
                    <select id="ciudad" name="ciudad" required class='form-control'>
                     
                    </select><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <?php
                    //**Query tipo_identificaciones */
                    $sqlTdoc = "SELECT ti.id, ti.sigla,ti.descripcion from tipo_identificaciones ti ORDER BY 2 ;";
                    ?>
                    <label for="tipo_documento">Tipo Documento:</label>
                    <select id="tipo_documento" name="tipo_documento" required class='form-control'>
                      <option value="">Seleccione un tipo documento</option>
                      <?php $resultadoTdoc = $conn->query($sqlTdoc);
                      if ($resultadoTdoc->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($tDoc = $resultadoTdoc->fetch_assoc()) {
                          ?>
                          <option value="<?php echo $tDoc["id"] ?>"><?php echo $tDoc["descripcion"] ?>
                          </option>
                          <?php
                        }
                      }
                      ?>
                    </select><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="documento">Documento:</label>
                    <input type="text" id="documento" name="documento" required class='form-control'><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="nombres">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" required class='form-control'><br><br>

                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required class='form-control'><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <?php
                    //**Query genero */
                    $sqlGenero = "SELECT g.id, g.sigla,g.descripcion from genero g ORDER BY 2 ;";
                    ?>
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" required class='form-control'>
                      <option value="">Seleccione un genero</option>
                      <?php $resultadoGenero = $conn->query($sqlGenero);
                      if ($resultadoGenero->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($genero = $resultadoGenero->fetch_assoc()) {
                          ?>
                          <option value="<?php echo $genero["id"] ?>"><?php echo $genero["descripcion"] ?>
                          </option>
                          <?php
                        }
                      }
                      ?>
                    </select><br><br>
                  </div>
                </div>            

              </div>
            </div>
            <!-- Agrega los demás campos del formulario aquí -->
          </fieldset>
          <p class="text-center" style="margin-top: 40px;">
            <button type="submit" onclick="crearInstructor()" class="btn btn-raised btn-success btn-md">Guardar</button>
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
            <legend><i class="far fa-user"></i> &nbsp;Cargar archivo de Instructor</legend>
            <div class="container-fluid">
              <div class="form-group">
                <label for="archivo">Seleccionar archivo:</label>
                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                  accept=".xlsx,.csv">
              </div>
              <button type="button" class="btn btn-primary" onclick="cargarArchivoIns()">Cargar
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

<!-- Modal para mostrar los cursos asignados -->
<div class="modal fade " id="cursosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cursos Asignados</h5>
        <input type="hidden" id="id_nmbEstud">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="modal-title navbar-text" id="nmb_estudiante"></h5><br>
        <!-- Formulario para asignar más cursos -->
        <?php
        $sqlExamen = "SELECT m.id_modulo, m.nombre nombre_modulo
                                FROM  modulos m
                                WHERE  CURRENT_DATE() <= m.fecha_vigencia
                                ORDER BY m.id_modulo;";
        $resultadoExamen = $conn->query($sqlExamen);
        ?>
        <form id="formAsignarCursos">
          <div class="form-group">
            <label for="selectCursos">Seleccionar curso a asignar:</label>
            <select class="form-control" id="selectCursosModal" name="selectCursosModal">
              <!-- Opciones del select con los cursos disponibles -->
              <!-- Supongamos que tienes un array $cursosDisponibles que contiene los cursos disponibles -->
              <option value="">Seleccione un curso </option>
              <?php
              if ($resultadoExamen->num_rows > 0) {
                // Mostrar las preguntas
                while ($curso = $resultadoExamen->fetch_assoc()) { ?>
                  <option value="<?php echo $curso['id_modulo']; ?>"><?php echo $curso['nombre_modulo']; ?></option>
                <?php }
              } ?>
            </select>
          </div>
          <button type="button" class="btn btn-primary" onclick="formAsignarCursos()">Asignar Curso</button>
        </form>
        <!-- Tabla para mostrar los cursos asignados al estudiante -->
        <table id="example" class="display table table-dark table-sm table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>Curso</th>
              <th>Descripción</th>
              <th>Tipo</th>
              <th>Instructor</th>
              <th>Fecha Asignación</th>
              <th>Salon</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="listaCursosEstudiante">
            <!-- Aquí se cargarán los cursos asignados al estudiante -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
