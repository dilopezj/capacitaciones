<?php
session_start();

include 'conexion/conexion.php';

$sqlEstudiantes = "SELECT em.nit, em.nombre AS empresa, 
i.descripcion AS tipo, es.id_estudiante, es.nombre, es.apellido, 
es.cargo, g.descripcion AS genero, es.fecha_nac, es.edad, es.celular, 
es.direccion, es.correo, em.regional, d.departamento, em.ciudad, mu.municipio, 
IFNULL(sub.cont_asignados, 0) AS modulos_asignados,
IFNULL(sub.cont_terminados, 0) AS modulos_terminados
FROM estudiantes es
JOIN empresas em ON em.nit = es.id_empresa
JOIN departamentos d ON d.id_departamento = em.regional
JOIN municipios mu ON mu.id_municipio = em.ciudad
JOIN tipo_identificaciones i ON i.id = es.tipo_identificacion
JOIN genero g ON g.id = es.genero
LEFT JOIN 
(SELECT ea.id_estudiante, COUNT(DISTINCT ea.id_modulo) AS cont_asignados, COUNT(DISTINCT CASE WHEN ea.asistencia = 1 THEN ea.id_modulo END) AS cont_terminados
FROM examenes_asignados ea
GROUP BY ea.id_estudiante) AS sub
ON  sub.id_estudiante = es.id_estudiante

ORDER BY 1,2,4,6,5;";

$resultadoEstudiantes = $conn->query($sqlEstudiantes);
?>
<!--CONTENT-->
<div class="container-fluid">
  <p class="text-center">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
        class="fas fa-plus"></i> &nbsp; Crear Estudiantes</button>
    <a class="btn btn-link btn-primary" href="archivos/estudiantes.xlsx" download><i class="fas fa-file"></i> &nbsp;
      Formato Excel</a>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i
        class="fas fa-file"></i> &nbsp; Cargar Excel</button>
  </p>
  <div class="table-responsive">
    <table id="example" class="display table table-dark table-sm" style="width:100%">
      <thead>
        <tr class="text-center roboto-medium">
          <th>REGIONAL</th>
          <th>CIUDAD</th>
          <th>NIT</th>
          <th>EMPRESA</th>
          <th>DOCUMENTO</th>
          <th>TIPO DOCUMENTO</th>
          <th>NOMBRES</th>
          <th>APELLIDOS</th>
          <th>GENERO</th>
          <th>FECHA NACIMIENTO</th>
          <th>CARGO</th>
          <th>DIRECCION</th>
          <th>CELULAR</th>
          <th>CORREO</th>
          <th>CURSOS ASIGNADOS</th>
          <th>CURSOS TERMINADOS</th>
          <th>ACCIONES</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($resultadoEstudiantes->num_rows > 0) {
          // Mostrar las preguntas
          while ($filaEstudiantes = $resultadoEstudiantes->fetch_assoc()) {
            $nmb_est = $filaEstudiantes["nombre"] . " " . $filaEstudiantes["apellido"];
            ?>
            <tr class="text-center">
              <td><?php echo $filaEstudiantes["departamento"] ?></td>
              <td><?php echo $filaEstudiantes["municipio"] ?></td>
              <td><?php echo $filaEstudiantes["nit"] ?></td>
              <td><?php echo $filaEstudiantes["empresa"] ?></td>
              <td><?php echo $filaEstudiantes["id_estudiante"] ?></td>
              <td><?php echo $filaEstudiantes["tipo"] ?></td>
              <td><?php echo $filaEstudiantes["nombre"] ?></td>
              <td><?php echo $filaEstudiantes["apellido"] ?></td>
              <td><?php echo $filaEstudiantes["genero"] ?></td>
             <!-- <td><?php //echo $filaEstudiantes["fecha_nac"] ?></td> -->
              <td><?php echo $filaEstudiantes["cargo"] ?></td>
             <!-- <td><?php //echo $filaEstudiantes["direccion"] ?></td> -->
              <td><?php echo $filaEstudiantes["celular"] ?></td>
              <td><?php echo $filaEstudiantes["correo"] ?></td>
              <td><?php echo $filaEstudiantes["modulos_asignados"] ?></td>
              <td><?php echo $filaEstudiantes["modulos_terminados"] ?></td>
              <td>
                <form action="">
                  <button type="button" class="btn btn-sm btn-success" style="float:left;"
                    onclick="mostrarCursos(<?php echo $filaEstudiantes['id_estudiante']; ?>,'<?php echo $nmb_est ?>')"
                    data-toggle="modal" data-target="#cursosModal">
                    <i class="far fa-file-archive"></i> cursos
                  </button>
                  <button onclick="btnEditarUsuario(<?php echo htmlspecialchars(json_encode($filaUsuarios)); ?>)" class="btn btn-raised btn-warning btn-sm" data-toggle="modal" data-target="#ModalEditar">Editar</button>
                  <button class="btn btn-raised btn-danger btn-sm"  onclick="eliminarUsuario(<?php echo $idUsuario ?>)">Eliminar</button>
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
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Modalcrear">Crear Estudiantes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formularioEstudiante" method="post" style="width: 100%;">
          <fieldset>
            <legend><i class="far fa-user"></i> &nbsp;Formulario Creación de Estudiantes</legend>
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 col-md-12">
                  <div class="form-group">
                    <label for="empresa">Empresa:</label>
                    <?php
                    //**Query Departemanto */
                    $sqlEmpresa = "SELECT e.nit,e.nombre from empresas e ORDER BY 2 ;";
                    ?>
                    <select id="empresa" name="empresa" required class='form-control'>
                      <option value="">Seleccione una empresa</option>
                      <?php $resultadoEmpresa = $conn->query($sqlEmpresa);
                      if ($resultadoEmpresa->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($empresa = $resultadoEmpresa->fetch_assoc()) {
                          ?>
                          <option value="<?php echo $empresa["nit"] ?>"><?php echo $empresa["nombre"] ?>
                          </option>
                          <?php
                        }
                      }
                      ?>
                    </select> <br><br>
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

            <!--    <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                      class='form-control'><br><br>
                  </div>
                </div>-->

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="cargo">Cargo:</label>
                    <input type="text" id="cargo" name="cargo" required class='form-control'><br><br>
                  </div>
                </div>

              <!--  <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required class='form-control'><br><br>
                  </div>
                </div> -->

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" class='form-control'><br><br>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" class='form-control'><br><br>

                  </div>
                </div>

              </div>
            </div>
            <!-- Agrega los demás campos del formulario aquí -->
          </fieldset>
          <p class="text-center" style="margin-top: 40px;">
            <button type="submit" onclick="crearEstudiante()" class="btn btn-raised btn-success btn-md">Guardar</button>
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
            <legend><i class="far fa-user"></i> &nbsp;Cargar archivo de Usuarios</legend>
            <div class="container-fluid">
              <div class="form-group">
                <label for="archivo">Seleccionar archivo:</label>
                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                  accept=".xlsx,.csv">
              </div>
              <button type="button" class="btn btn-primary" onclick="cargarArchivoEst()">Cargar
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="menuReload('estudiantes-list.php', 'Listar Estudiantes');">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="modal-title navbar-text">ESTUDIANTE: <span id="nmb_estudiante"></span></h5><br>
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="menuReload('estudiantes-list.php', 'Listar Estudiantes');">Cerrar</button>
      </div>
    </div>
  </div>
</div>