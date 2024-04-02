<?php include 'conexion/conexion.php'; ?>

<div class="container-fluid">
    <?php
// Verificar si se ha enviado el parámetro 'id' a través de la URL
    if (isset($_GET['id'])) {
        // Obtener el valor del parámetro 'id'
        $examenId = $_GET['id'];
    } else {
        // En caso de que no se haya enviado el parámetro 'id', puedes mostrar un mensaje de error o realizar otra acción
        echo "Error: No se proporcionó el ID del examen.";
    }

// Verificar si el estudiante está logueado y obtener su ID
// Aquí se asume que $id_estudiante contiene el ID del estudiante logueado
// Consulta para obtener todas las preguntas asociadas a exámenes activos
    $sqlPreguntas = "SELECT id_pregunta, texto_pregunta, imagen_url AS pregunta_imagen
                 FROM preguntas
                 WHERE id_examen = $examenId";

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
                <fieldset id="step-<?php echo $cont_p ?>" <?php if ($cont_p > 1) { echo 'style="display: none"'; } else { echo ''; } ?> >
                    <legend><strong>Pregunta:</strong><?php echo $cont_p ?></legend>
                    <?php if ($filaPregunta["pregunta_imagen"] != "") { ?>
                        <div style="padding:18px;">
                            <img src="./assets/imagen_preguntas/<?php echo $filaPregunta["pregunta_imagen"] ?>" alt="..." width="200" height="200" class="img-thumbnail">
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
                                <li style="float: left;padding: 38px; border: 1px solid #cccccc;">
                                    <input type="radio" name='<?php echo $filaPregunta["id_pregunta"] ?>' value='<?php echo $filaRespuesta["id_respuesta"] ?>'> <strong>Respuesta<?php echo $cont_r ?>:</strong> <?php echo $filaRespuesta["texto_respuesta"] ?><br>
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
                    <div class="text-rigth" style="text-align: right;">
                        <!-- Agrega más opciones si es necesario -->
                        <?php if ($cont_p == $totalP) { ?>
                            <button class="btn btn-raised btn-sm btn-sura" type="button" onclick="submitForm(<?php echo $examenId ; ?>)">Enviar</button>
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
$conn->close();
?>