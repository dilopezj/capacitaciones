<?php
include 'conexion/conexion.php';

$id_estudiante = 0;
if ($_SESSION['id_perfil'] == 2) {
    $id_estudiante = $_SESSION['identificacion'];
}

$sqlExamenes = "SELECT DISTINCT m.id_modulo, m.nombre  nombre_modulo
                FROM examenes_asignados ea
                    JOIN estudiantes es ON ea.id_estudiante = es.id_estudiante
                    JOIN modulos m ON ea.id_modulo = m.id_modulo ";
if ($id_estudiante != 0) {
    $sqlExamenes .= " WHERE  ea.id_estudiante = $id_estudiante; ";
}


$resultadoExamenes = $conn->query($sqlExamenes);

if ($resultadoExamenes->num_rows > 0) {
    // Mostrar las preguntas
    ?> 
    <div class="full-box tile-container">
    <?php 
    while ($filaExamenes = $resultadoExamenes->fetch_assoc()) {
        $moduloID = $filaExamenes["id_modulo"] ; 
        $moduloNM = $filaExamenes["nombre_modulo"] ; 
        ?>
        <!-- Content --> 
            <button class="tile tile_menu"  onclick="menuModulos('<?php echo $moduloID ?>','<?php echo $moduloNM ?>')" >
                <div class="tile-tittle" style="height: auto !important; min-height: 54px; font-size: 12px; line-height: 24px;"><?php echo $filaExamenes["nombre_modulo"] ?></div>
                <div class="tile-icon">
                    <i class="fas fa-file fa-fw" style="font-size: 57px;"></i>
                </div>
            </button>
        <?php } ?>
    </div>
<?php } ?>