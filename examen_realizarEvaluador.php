<?php
include 'conexion/conexion.php';
?>

<div class="container-fluid">
    <?php
    if (isset($_GET['id'])) {
        $estudianteId = $_GET['id'];
    } else {
        echo "Error: No se proporcionó el ID del estudiante.";
        exit;
    }

    if (isset($_GET['modulo'])) {
        $moduloId = $_GET['modulo'];
    } else {
        echo "Error: No se proporcionó el módulo del examen.";
        exit;
    }

    $sqlEstudiantes = "SELECT e.id_estudiante, e.nombre, e.apellido, e.id_empresa, em.nombre empresa, em.regional, em.ciudad, e.tipo_identificacion, e.celular,
    e.fecha_nac, e.edad, e.direccion 
    FROM estudiantes e 
    JOIN empresas em ON em.nit = e.id_empresa 
    WHERE e.id_estudiante = $estudianteId
    ORDER BY e.apellido;";

    $resultadoEstudiantes = $conn->query($sqlEstudiantes);
    if ($resultadoEstudiantes->num_rows > 0) {
        while ($filaEstudiantes = $resultadoEstudiantes->fetch_assoc()) {
            echo '<div class="title"><legend><strong>ESTUDIANTE :</strong>  ' . $filaEstudiantes["nombre"] . ' ' . $filaEstudiantes["apellido"] . '</legend></div>';
        }
    }

    $sqlModulos = "SELECT m.nombre nombre_modulo, m.id_modulo, m.fecha_vigencia
                   FROM modulos m 
                   WHERE m.id_modulo = $moduloId 
                   ORDER BY m.nombre";

    $resultadoModulos = $conn->query($sqlModulos);
    if ($resultadoModulos->num_rows > 0) {
        while ($filaModulos = $resultadoModulos->fetch_assoc()) {
            echo '<div class="title"><legend><strong>MODULO : </strong>' . $filaModulos["nombre_modulo"] . '</legend></div>';

            $sqlPreguntas = "SELECT p.id_pregunta, p.texto_pregunta, p.imagen_url AS pregunta_imagen, c.orden AS orden_criterio, c.descripcion AS criterio, c.texto AS texto
                             FROM preguntas p
                             JOIN examenes ex ON ex.id_examen = p.id_examen
                             JOIN modulos m ON m.id_modulo = ex.id_modulo
                             JOIN criterios c ON c.id_criterios = p.id_criterio
                             WHERE m.id_modulo = $moduloId AND ex.tipo_examen = 'final'
                             ORDER BY p.id_pregunta;";

            $resultadoPreguntas = $conn->query($sqlPreguntas);
            $cont_p = 1;
            $totalP = $resultadoPreguntas->num_rows;
            ?>
            <form id="form-wizard">
                <?php
                if ($resultadoPreguntas->num_rows > 0) {
                    while ($filaPregunta = $resultadoPreguntas->fetch_assoc()) {
                        ?>
                        <fieldset id="step-<?php echo $cont_p ?>" <?php if ($cont_p > 1) { echo 'style="display: none"'; } ?>>
                            <legend><strong>Pregunta:</strong> <?php echo $cont_p . " <br> " . $filaPregunta["criterio"] ."<br>" ?></legend>
                            <p><?php echo $filaPregunta["texto"] ?></p>
                            <?php if ($filaPregunta["pregunta_imagen"] != "") { ?>
                                <div style="padding:18px;">
                                    <img src="./assets/imagen_preguntas/<?php echo $filaPregunta["pregunta_imagen"] ?>" alt="..." width="200" height="200" class="img-thumbnail">
                                </div>
                            <?php } ?>
                            <h4><?php echo $filaPregunta["texto_pregunta"] ?></h4>
                            <input type="hidden" name="criterio_<?php echo $filaPregunta["id_pregunta"] ?>" value="<?php echo $filaPregunta["orden_criterio"] ?>">
                            <?php
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
                                        <li style="float: left; padding: 38px; border: 1px solid #cccccc; width: 418px; min-height: 140px;">
                                            <input type="radio" name='<?php echo $idPreguntaActual ?>' value='<?php echo $filaRespuesta["id_respuesta"] ?>'>
                                            <strong>Respuesta <?php echo $cont_r ?>:</strong> <?php echo $filaRespuesta["texto_respuesta"] ?><br>
                                            <?php if ($filaRespuesta["respuesta_imagen"] != "") { ?>
                                                <img src="./assets/imagen_respuestas/<?php echo $filaRespuesta["respuesta_imagen"] ?>" alt="..." width="200" height="200" class="img-thumbnail">
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
                            <div class="text-right" style="text-align: right;">
                                <?php if ($cont_p == $totalP) { ?>
                                    <button class="btn btn-raised btn-sm btn-sura" type="button" onclick="submitForm02(<?php echo $estudianteId; ?>, <?php echo $moduloId; ?>)">Enviar</button>
                                <?php } else { ?>
                                    <button class="btn btn-raised btn-sm btn-vivo" type="button" onclick="nextStep(<?php echo ($cont_p + 1) ?>)">Siguiente</button>
                                <?php } ?>
                                <?php if ($cont_p > 1) { ?>
                                    <button class="btn btn-raised btn-danger btn-sm" type="button" onclick="prevStep(<?php echo ($cont_p - 1) ?>)">Anterior</button>
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
</div>
