<?php
session_start();

include 'conexion/conexion.php';

$sqlUsuarios = "SELECT u.id_usuario, u.nombre_usuario, u.correo_usuario, u.id_perfil, p.nombre_perfil, u.identificacion,
    CASE 
        WHEN u.id_perfil = 3 THEN (
            SELECT CONCAT(i.nombres, ' ', i.apellidos) 
            FROM instructores i 
            WHERE i.identificacion = u.identificacion
        )
        ELSE COALESCE((
            SELECT CONCAT(e.nombre, ' ', e.apellido) 
            FROM estudiantes e 
            WHERE e.id_estudiante = u.identificacion
        ), '') 
    END AS nmb_estudiante
FROM 
    usuarios u
JOIN 
    perfiles p ON p.id_perfil = u.id_perfil ";
if ($_SESSION['id_perfil'] != "1" && $_SESSION['id_perfil'] != "4") {
    $sqlUsuarios .= " WHERE p.id_perfil in (2) ";
}
$sqlUsuarios .= " ORDER BY u.id_usuario ;";

$resultadoUsuarios = $conn->query($sqlUsuarios);
?>
<!--CONTENT-->
<div class="container-fluid">
    <p class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
                class="fas fa-plus"></i> &nbsp; Crear Usuarios</button>
        <a class="btn btn-link btn-primary" href="archivos/usuarios.xlsx" download><i class="fas fa-file"></i> &nbsp;
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
                    <th>USUARIO</th>
                    <th>CORREO</th>
                    <th>PERFIL</th>
                    <th>IDENTIFICACIÓN</th>
                    <th>ESTUDIANTE / INSTRUCTOR</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultadoUsuarios->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaUsuarios = $resultadoUsuarios->fetch_assoc()) {
                        $idUsuario = $filaUsuarios["id_usuario"];
                        ?>
                        <tr class="text-center">
                            <td><?php echo $filaUsuarios["id_usuario"] ?></td>
                            <td><?php echo $filaUsuarios["nombre_usuario"] ?></td>
                            <td><?php echo $filaUsuarios["correo_usuario"] ?></td>
                            <td><?php echo $filaUsuarios["nombre_perfil"] ?></td>
                            <td><?php echo $filaUsuarios["identificacion"] ?></td>
                            <td><?php echo $filaUsuarios["nmb_estudiante"] ?></td>
                            <td>
                                <button onclick="btnEditarUsuario(<?php echo htmlspecialchars(json_encode($filaUsuarios)); ?>)" class="btn btn-raised btn-warning btn-md" data-toggle="modal" data-target="#ModalEditar">Editar</button>
                                <button class="btn btn-raised btn-danger btn-md"  onclick="eliminarUsuario(<?php echo $idUsuario ?>)">Eliminar</button>
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
            </fieldset>
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
                <form id="formularioUsuarios" method="post" style="width: 100%;">
                    <fieldset>
                        <legend><i class="far fa-user"></i> &nbsp;Formulario Creación de Usuarios</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="usuario">Identificación: (Sin puntos ni comas)</label>
                                        <input type="text" id="ide" name="ide" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="usuario">Usuario:</label>
                                        <input type="text" id="usuario" name="usuario" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="pass">Contraseña:</label>
                                        <input type="text" id="pass" name="pass" required class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="email" id="correo" name="correo" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <?php
                                        //**Query genero */
                                        if ($_SESSION['id_perfil'] == 1) {
                                            $sqlPerfil = "SELECT p.id_perfil, p.nombre_perfil from perfiles p
                                        WHERE p.id_perfil IN (1,4,3) ORDER BY 2 ;";
                                        } else {
                                            $sqlPerfil = "SELECT p.id_perfil, p.nombre_perfil from perfiles p
                                            WHERE p.id_perfil IN (4) ORDER BY 2 ;";
                                        }

                                        ?>
                                        <label for="perfil">Perfil:</label>
                                        <select id="perfil" name="perfil" required class='form-control'>
                                            <option value="">Seleccione un perfil</option>
                                            <?php $resultadoPerfil = $conn->query($sqlPerfil);
                                            if ($resultadoPerfil->num_rows > 0) {
                                                // Mostrar las preguntas
                                                while ($perfil = $resultadoPerfil->fetch_assoc()) {
                                                    ?>
                                                    <option value="<?php echo $perfil["id_perfil"] ?>">
                                                        <?php echo $perfil["nombre_perfil"] ?>
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
                    </fieldset>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" onclick="crearUsuarios()"
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
                        <legend><i class="far fa-user"></i> &nbsp;Cargar archivo de Usuarios</legend>
                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="archivo">Seleccionar archivo:</label>
                                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                                    accept=".xlsx,.csv">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="cargarArchivo()">Cargar
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

<!-- Agrega un modal para la edición de usuarios -->
<!-- MODAL ModalEditar -->
<div class="modal fade" id="ModalEditar" tabindex="-1" role="dialog" aria-labelledby="ModalEditar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Editar Usuarios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioUsuariosE" method="post" style="width: 100%;">
                    <input type="hidden" id="idUsuarioE" name="idUsuarioE" required >
                    <fieldset>
                        <legend><i class="far fa-user"></i> &nbsp;Formulario Editar Usuarios</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="usuario">Identificación: (Sin puntos ni comas)</label>
                                        <input type="text" id="ideE" name="ideE" required 
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="usuario">Usuario:</label>
                                        <input type="text" id="usuarioE" name="usuarioE" required  readonly="true"
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="pass">Contraseña:</label>
                                        <input type="text" id="passE" name="passE" class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="email" id="correoE" name="correoE" required 
                                            class='form-control'><br><br>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <?php
                                        //**Query genero */
                                        if ($_SESSION['id_perfil'] == 1) {
                                            $sqlPerfil = "SELECT p.id_perfil, p.nombre_perfil from perfiles p
                                        WHERE p.id_perfil IN (1,4,2,3) ORDER BY 2 ;";
                                        } else {
                                            $sqlPerfil = "SELECT p.id_perfil, p.nombre_perfil from perfiles p
                                            WHERE p.id_perfil IN (4,2,3) ORDER BY 2 ;";
                                        }

                                        ?>
                                        <label for="perfilE">Perfil:</label>
                                        <select id="perfilE" name="perfilE" class='form-control' disabled="true" >
                                            <option value="">Seleccione un perfil</option>
                                            <?php $resultadoPerfil = $conn->query($sqlPerfil);
                                            if ($resultadoPerfil->num_rows > 0) {
                                                // Mostrar las preguntas
                                                while ($perfil = $resultadoPerfil->fetch_assoc()) {                                                    
                                                    ?>
                                                    <option value="<?php echo $perfil["id_perfil"] ?>">
                                                        <?php echo $perfil["nombre_perfil"] ?>
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
                    </fieldset>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" onclick="EditarUsuarios()"
                            class="btn btn-raised btn-success btn-md">Guardar Cambios</button>
                    </p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>