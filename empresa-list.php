<?php
session_start();

include 'conexion/conexion.php';

$sqlEmpresas = "SELECT  em.nit, em.nombre empresa, em.regional, d.departamento, em.ciudad ciudadEM, m.municipio,
em.direccion, em.telefono, 
em.nmb_contacto, em.apl_contacto, em.correo_contacto
FROM empresas em  
JOIN departamentos d ON d.id_departamento = em.regional
JOIN municipios m ON m.id_municipio = em.ciudad 
ORDER BY empresa;";

$resultadoEmpresas = $conn->query($sqlEmpresas);
?>
<!--CONTENT-->
<div class="container-fluid">
    <p class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Modalcrear"><i
                class="fas fa-plus"></i> &nbsp; Agregar Empresa</button>
        <a class="btn btn-link btn-primary" href="archivos/empresas.xlsx" download><i class="fas fa-file"></i> &nbsp;
            Formato Excel</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalArchivo"><i
                class="fas fa-file"></i> &nbsp; Cargar Excel</button>
        <button type="button" class="btn btn-danger" onclick="eliminarEmpresasSeleccionadas()"><i
                class="fas fa-trash"></i> &nbsp; Eliminar Seleccionados</button>
    </p>
    <div class="table-responsive">
        <fieldset>
            <table id="example" class="display table table-dark table-sm" style="width:100%">
                <thead>
                    <tr class="text-center roboto-medium">
                         <th><input type="checkbox" id="checkTodos"></th> <!-- Checkbox para seleccionar todos -->
                        <th>NIT</th>
                        <th>EMPRESA</th>
                        <th>DEPARTAMENTO</th>
                        <th>MUNICIPIO / CIUDAD</th>
                        <th>DIRECCIÓN</th>
                        <th>TELEFONO</th>
                        <th>CONTACTO</th>
                        <th>CORREO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultadoEmpresas->num_rows > 0) {
                        // Mostrar las preguntas
                        while ($filaEmpresas = $resultadoEmpresas->fetch_assoc()) {
                            $idEmpresa = $filaEmpresas["nit"];
                            ?>
                            <tr class="text-center">
                                <td><input type="checkbox" class="checkEliminar" value="<?php echo $idEmpresa; ?>"></td>
                                <td><?php echo $filaEmpresas["nit"] ?></td>
                                <td><?php echo $filaEmpresas["empresa"] ?></td>
                                <td><?php echo $filaEmpresas["departamento"] ?></td>
                                <td><?php echo $filaEmpresas["municipio"] ?></td>
                                <td><?php echo $filaEmpresas["direccion"] ?></td>
                                <td><?php echo $filaEmpresas["telefono"] ?></td>
                                <td><?php echo $filaEmpresas["nmb_contacto"] . " " . $filaEmpresas["apl_contacto"] ?></td>
                                <td><?php echo $filaEmpresas["correo_contacto"] ?></td>
                                <td>
                                    <button onclick="btnEditarEmpresa(<?php echo htmlspecialchars(json_encode($filaEmpresas)); ?>)" class="btn btn-raised btn-warning btn-md" data-toggle="modal" data-target="#ModalEditar">Editar</button>
                                    <button class="btn btn-raised btn-danger btn-md"  onclick="eliminarEmpresa(<?php echo $idEmpresa ?>)">Eliminar</button>
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
        </fieldset>
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
                <form id="formularioEmpresas" method="post" style="width: 100%;">
                    <fieldset>
                        <legend><i class="far fa-building"></i> &nbsp;Formulario Creación de Empresas</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="regional">Regional*:</label>
                                        <select id="regional" name="regional" required class='form-control'
                                            onchange="changeDpto()">
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
                                        <label for="ciudad">Ciudad*:</label>
                                        <select id="ciudad" name="ciudad" required class='form-control'>

                                        </select><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nit">Nit* <small>(Sin puntos y comas)</small>:</label>
                                        <input type="text" id="nit" name="nit" required class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nmb_empresa">Nombre de Empresa:</label>
                                        <input type="text" id="nmb_empresa" name="nmb_empresa" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Dirección:</label>
                                        <input type="text" id="direccion" name="direccion" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" id="telefono" name="telefono" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="text" id="correo" name="correo" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nmb_contacto">Nombre Contacto:</label>
                                        <input type="text" id="nmb_contacto" name="nmb_contacto" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="apl_contacto">Apellido Contacto:</label>
                                        <input type="text" id="apl_contacto" name="apl_contacto" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </fieldset>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" onclick="crearEmpresa()"
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
                        <legend><i class="far fa-building"></i> &nbsp;Cargar archivo de Empresas</legend>
                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="archivo">Seleccionar archivo:</label>
                                <input type="file" class="form-control form-control-file" id="archivo" name="archivo"
                                    accept=".xlsx,.csv">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="cargarArchivoEmp()">Cargar
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
<!-- MODAL Crear Examen -->
<div class="modal fade" id="ModalEditar" tabindex="-1" role="dialog" aria-labelledby="ModalEditar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcrear">Editar Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formularioEmpresasE" method="post" style="width: 100%;">
                    <input type="hidden" id="idEmpresaE" name="idEmpresaE" required >
                    <fieldset>
                        <legend><i class="far fa-building"></i> &nbsp;Formulario Modificación de Empresas</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="regionalE">Regional*:</label>
                                        <select id="regionalE" name="regionalE" required class='form-control'
                                            onchange="changeDptoE()">
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
                                        <label for="ciudadE">Ciudad*:</label>
                                        <select id="ciudadE" name="ciudadE" required class='form-control'>

                                        </select><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nitE">Nit* <small>(Sin puntos y comas)</small>:</label>
                                        <input type="text" id="nitE" name="nitE" required readonly="true" class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nmb_empresaE">Nombre de Empresa:</label>
                                        <input type="text" id="nmb_empresaE" name="nmb_empresaE" required 
                                            class='form-control'><br><br>

                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="direccionE">Dirección:</label>
                                        <input type="text" id="direccionE" name="direccionE" required
                                            class='form-control'><br><br>

                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="telefonoE">Teléfono:</label>
                                        <input type="text" id="telefonoE" name="telefonoE" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="correoE">Correo:</label>
                                        <input type="text" id="correoE" name="correoE" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="nmb_contactoE">Nombre Contacto:</label>
                                        <input type="text" id="nmb_contactoE" name="nmb_contactoE" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="apl_contactoE">Apellido Contacto:</label>
                                        <input type="text" id="apl_contactoE" name="apl_contactoE" required
                                            class='form-control'><br><br>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </fieldset>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" onclick="EditarEmpresas()"
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