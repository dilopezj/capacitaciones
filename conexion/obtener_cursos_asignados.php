<?php
include 'conexion.php';

session_start();
// Supongamos que tienes una conexión a tu base de datos en este archivo o incluyes un archivo que la contenga

// Verificar si se recibió el ID del estudiante
if (isset($_POST['idEstudiante'])) {
    $idEstudiante = $_POST['idEstudiante'];
    $cont = 1;

    // Aquí deberías realizar una consulta a tu base de datos para obtener los cursos asignados al estudiante con el ID proporcionado
    // Supongamos que tienes una tabla llamada cursos_asignados donde se almacenan los cursos asignados a cada estudiante
    // También supongamos que tienes una tabla llamada cursos donde se almacenan los detalles de los cursos

    // Realizar la consulta SQL
    $sql = "SELECT m.id_modulo, m.nombre AS nombre_modulo, e.nombre_examen, e.tipo_examen, ea.fecha_programado, ea.salon, ea.id_instructor,
                (SELECT concat(i.nombres,' ', i.apellidos) FROM instructores i WHERE i.identificacion = ea.id_instructor) instructor
            FROM examenes_asignados ea
            JOIN estudiantes es ON ea.id_estudiante = es.id_estudiante
            JOIN modulos m ON ea.id_modulo = m.id_modulo 
            JOIN examenes  e ON ea.id_examen = e.id_examen AND  ea.id_modulo = e.id_modulo  
            WHERE ea.id_estudiante = $idEstudiante
            ORDER BY  m.id_modulo ,  e.nombre_examen ;";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    //**Query instructor */
    $sqlInstructor = "SELECT DISTINCT i.identificacion , concat(i.nombres,' ', i.apellidos) nombre_instructor,
                        (select m.municipio from municipios m where m.departamento_id = i.regional and m.id_municipio = i.ciudad)ciudad
                        FROM instructores i , 
                            empresas em JOIN estudiantes e ON em.nit = e.id_empresa
                            WHERE  e.id_estudiante = $idEstudiante
                            ORDER BY 2 ;"; /*i.regional = em.regional AND i.ciudad = em.ciudad and */

    //**Query Salones */
    $sqlSalones = "SELECT DISTINCT s.id , s.descripcion, s.capacidad
                    FROM salones s , 
                        empresas em JOIN estudiantes e ON em.nit = e.id_empresa
                        WHERE s.regional = em.regional AND s.ciudad = em.ciudad
                         and e.id_estudiante = $idEstudiante
                    ORDER BY 2 ;";

    // Verificar si se encontraron cursos asignados
    if ($result->num_rows > 0) {
        // Mostrar los cursos asignados en formato de lista HTML
        while ($row = $result->fetch_assoc()) {
            $tipoExam = $row["tipo_examen"]  == "FINAL" ? "EXAMEN DE CAMPO" : $row["tipo_examen"] ;
            echo "<tr>";
            echo "<td>" . $row["nombre_modulo"] . "</td>";
            echo "<td>" . $row["nombre_examen"] . "</td>";
            echo "<td>" . $tipoExam . "</td>";
            // Agregar select para el instructor
            echo "<td><select id='instructor".$cont."' name='instructor[]'  class='form-control' " ;
            if ($row["tipo_examen"] == 'INTERMEDIA') {
                echo ' disabled'; // Añade el atributo disabled
            }
            echo " >";
            echo "<option value=''>Seleccionar Instructor</option>";
            // Aquí cargarías dinámicamente los instructores desde la base de datos
            // Por ejemplo, podrías tener un array de instructores obtenido previamente desde la base de datos
            $resultadoInstructor = $conn->query($sqlInstructor);
            if ($resultadoInstructor->num_rows > 0) {
                // Mostrar las preguntas
                while ($instructor = $resultadoInstructor->fetch_assoc()) {
                    $selected = $row["id_instructor"] == $instructor["identificacion"] ? 'selected' : '';
                    echo "<option value='" . $instructor["identificacion"] . "' $selected  >" . $instructor["nombre_instructor"] . " - " . $instructor["ciudad"] . "</option>";
                }
            }
            echo "</select></td>";
            // Agregar input para la fecha programada
           echo "<td><input id='fecha".$cont."' class='form-control' type='date' name='fecha_programada[]' value='" . $row["fecha_programado"] . "' min='" . date("Y-m-d") . "'  " ;
            if ($row["tipo_examen"] == 'INTERMEDIA') {
                echo ' readonly="true" ';
            }
            if ($row["tipo_examen"] == 'PREVIA') {
                $id_moludop = $row["id_modulo"];
                echo ' onchange="validateDate(\'fecha' . $cont . '\', ' . $idEstudiante . ', ' . $id_moludop . ')" ';
            }
            echo "></td>";

            if ($row["tipo_examen"] != 'FINAL') {
                $cadn = "salon$cont";
            echo "<td><select id='salon".$cont."' name='salon[]' class='form-control' onchange='validarCapacidadSalon(\"$cadn\")' " ;
            if ($row["tipo_examen"] == 'INTERMEDIA') {
               echo ' disabled'; // Añade el atributo disabled
            }
            echo " > ";
            echo "<option value=''>Seleccionar Salón</option>";
            // Aquí cargarías dinámicamente los salones desde la base de datos
            // Por ejemplo, podrías tener un array de salones obtenido previamente desde la base de datos
            $resultadoSalones = $conn->query($sqlSalones);
            if ($resultadoSalones->num_rows > 0) {
                while ($salon = $resultadoSalones->fetch_assoc()) {
                    $selectedS = $row["salon"] == $salon["id"] ? 'selected' : ''; // Cambio aquí
                    echo "<option value='" . $salon["id"] . "' $selectedS >" . $salon["descripcion"] . "</option>"; // Cambio aquí
                }
            }
            echo "</select></td>";
            }else {
                echo "<td>N/A</td>"; // Cambio aquí
            }
            if ($row["tipo_examen"] == 'FINAL') {
                echo "<td><button class='btn btn-info' type='button' onclick='selectInstructorCampo(".$cont.",".$idEstudiante .",".$row["id_modulo"].")' title='Guardar'><i class='fas fa-save'></i></button></td>"; // Cambio aquí
            }else {
                if ($row["tipo_examen"] == 'PREVIA') {
                echo "<td style='width: 15%;'><button class='btn btn-success btn-sm' type='button' onclick='selectInstructor(".$cont.",".$idEstudiante .",".$row["id_modulo"].")' title='Guardar'><i class='fas fa-save'></i></button>"; 
                echo "<button class='btn btn-danger btn-sm' type='button' onclick='deleteInstructor(".$cont.",".$idEstudiante .",".$row["id_modulo"].")' title='Eliminar'><i class='fas fa-trash'></i></button></td>"; 
           
                }else{
                     echo "<td></td>"; // 
                }
            }
           
            echo "</tr>";
            $cont++;
        }
    } else {
        // Si no hay cursos asignados, mostrar un mensaje indicando esto
        echo "<tr><td colspan='2'>No tiene cursos asignados.</td></tr>";
    }

    // Liberar el resultado de la consulta
    $result->free();
} else {
    // Si no se recibió el ID del estudiante, mostrar un mensaje de error
    echo "Error: No se proporcionó el ID del estudiante.";
}

// Cerrar la conexión a la base de datos si es necesario
$conn->close();
?>