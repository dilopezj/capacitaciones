<?php
include 'conexion/conexion.php';
?>

<div class="container-fluid">
    <?php
    // Verificar si se ha enviado el parámetro 'id' a través de la URL
    if (isset($_GET['id'])) {
        // Obtener el valor del parámetro 'id'
        $estudianteId = $_GET['id'];
    } else {
        // En caso de que no se haya enviado el parámetro 'id', puedes mostrar un mensaje de error o realizar otra acción
        echo "Error: No se proporcionó el ID del examen.";
    }

    if (isset($_GET['modulo'])) {
        // Obtener el valor del parámetro 'id'
        $moduloId = $_GET['modulo'];
    } else {
        // En caso de que no se haya enviado el parámetro 'id', puedes mostrar un mensaje de error o realizar otra acción
        echo "Error: No se proporcionó el modulo del examen.";
    }

    $sqlEstudiantes = "SELECT e.id_estudiante, e.nombre, e.apellido, e.id_empresa, em.nombre empresa, em.regional, em.ciudad, e.tipo_identificacion, e.celular,
    e.fecha_nac, e.edad, e.direccion 
    FROM estudiantes e JOIN empresas em ON em.nit = e.id_empresa and e.id_estudiante = $estudianteId
    ORDER BY e.apellido;";

    $resultadoEstudiantes = $conn->query($sqlEstudiantes);
    if ($resultadoEstudiantes->num_rows > 0) {
        // Mostrar las preguntas
        while ($filaEstudiantes = $resultadoEstudiantes->fetch_assoc()) {
            echo '<div class ="title"><legend><strong>ESTUDIANTE :</strong>  ' . $filaEstudiantes["nombre"] . ' ' . $filaEstudiantes["apellido"] . '</legend> </div>';
        }
    }

    $sqlModulos = "SELECT m.nombre  nombre_modulo, m.id_modulo, m.fecha_vigencia
                   FROM modulos m  WHERE  m.id_modulo= $moduloId  order by m.nombre";

    $resultadoModulos = $conn->query($sqlModulos);
    if ($resultadoModulos->num_rows > 0) {
        // Mostrar las preguntas
        while ($filaModulos = $resultadoModulos->fetch_assoc()) {
            echo '<div class ="title"> <legend><strong>MODULO : </strong>' . $filaModulos["nombre_modulo"] . ' </legend></div>';

            // Verificar si el estudiante está logueado y obtener su ID
// Aquí se asume que $id_estudiante contiene el ID del estudiante logueado
// Consulta para obtener todas las preguntas asociadas a exámenes activos
            $sqlPreguntas = "SELECT id_pregunta, texto_pregunta, imagen_url AS pregunta_imagen
                        FROM preguntas p
                        JOIN examenes ex ON ex.id_examen = p.id_examen
                        JOIN modulos m ON m.id_modulo = ex.id_modulo
                        WHERE m.id_modulo = $moduloId AND ex.tipo_examen = 'final'
                        ORDER BY p.id_pregunta;";

            $resultadoPreguntas = $conn->query($sqlPreguntas);
            $cont_p = 1;
            $totalP = $resultadoPreguntas->num_rows;
            ?>
            <form id="form-wizard">
                <?php
                if ($resultadoPreguntas->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaPregunta = $resultadoPreguntas->fetch_assoc()) {
                        ?>
                        <fieldset id="step-<?php echo $cont_p ?>" <?php if ($cont_p > 1) {
                               echo 'style="display: none"';
                           } else {
                               echo '';
                           } ?>>
                            <legend><strong>Pregunta:</strong><?php echo $cont_p ?></legend>
                            <?php if ($filaPregunta["pregunta_imagen"] != "") { ?>
                                <div style="padding:18px;">
                                    <img src="./assets/imagen_preguntas/<?php echo $filaPregunta["pregunta_imagen"] ?>" alt="..."
                                        width="200" height="200" class="img-thumbnail">
                                </div>
                            <?php } ?>
                            <p><?php echo $filaPregunta["texto_pregunta"] ?></p>

                            <?php
                            // Consulta para obtener las respuestas asociadas a la pregunta actual
                            $idPreguntaActual = $filaPregunta["id_pregunta"];
                            $sqlRespuestas = "SELECT id_respuesta, texto_respuesta, imagen_url AS respuesta_imagen
                          FROM respuestas
                          WHERE id_pregunta = $idPreguntaActual";

                            $resultadoRespuestas = $conn->query($sqlRespuestas);
                            ?>
                            <ul class="mi-lista">
                                <?php
                                if ($resultadoRespuestas->num_rows > 0) {
                                    $cont_r = 1;
                                    while ($filaRespuesta = $resultadoRespuestas->fetch_assoc()) {
                                        ?>
                                        <li style="float: left;padding: 38px; border: 1px solid #cccccc; width: 418px; height: 100px;">
                                            <input type="radio" name='<?php echo $filaPregunta["id_pregunta"] ?>'
                                                value='<?php echo $filaRespuesta["id_respuesta"] ?>'>
                                            <strong>Respuesta<?php echo $cont_r ?>:</strong> <?php echo $filaRespuesta["texto_respuesta"] ?><br>
                                            <?php if ($filaRespuesta["respuesta_imagen"] != "") { ?>
                                                <img src="./assets/imagen_respuestas/<?php echo $filaRespuesta["respuesta_imagen"] ?>" alt="..."
                                                    width="200" height="200" class="img-thumbnail">
                                            <?php } ?>
                                        </li>
                                        <?php
                                        $cont_r++;
                                    }
                                } else {
                                    echo "No hay respuestas disponibles para esta pregunta.";
                                }
                                ?>
                            </ul>
                            <div style="clear: both;"></div>
                            <div class="text-rigth" style="text-align: right;">
                                <!-- Agrega más opciones si es necesario -->
                                <?php if ($cont_p == $totalP) { ?>
                                    <button class="btn btn-raised btn-sm btn-sura" type="button"
                                        onclick="submitForm02(<?php echo $estudianteId; ?>,<?php echo $moduloId; ?>)">Enviar</button>
                                <?php } else { ?>
                                    <button class="btn btn-raised btn-sm btn-vivo" type="button"
                                        onclick="nextStep(<?php echo ($cont_p + 1) ?>)">Siguiente</button>
                                <?php } ?>
                                <?php if ($cont_p > 1) { ?>
                                    <button class="btn btn-raised btn-danger btn-sm" type="button"
                                        onclick="prevStep(<?php echo ($cont_p - 1) ?>)">Anterior</button>
                                <?php } ?>
                            </div>
                        </fieldset>
                        <?php
                        $cont_p++;
                    }
                } else {
                    echo "No hay preguntas disponibles en este momento.";
                }
                ?>
            </form>

            <div id="resultadoContainer"></div>

        </div>
        <?php
        }
    }
    $conn->close();
    ?>