<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Check if the department ID is provided
if(isset($_POST['departamento_id'])) {
    // Retrieve department ID from the POST data
    $departamento_id = $_POST['departamento_id'];
    
    // Query to fetch municipalities based on department ID
    $sql = "SELECT id_municipio, municipio FROM municipios WHERE departamento_id = $departamento_id";
    
    // Prepare the SQL statement
    $result = $conn->query($sql);
    
     // Verificar si se encontraron cursos asignados
     if ($result->num_rows > 0) {
        echo '<option value="">Seleccione una ciudad</option>';
        // Mostrar los cursos asignados en formato de lista HTML
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["id_municipio"] . '">' . $row["municipio"] . '</option>';
        }
    }
} else {
    // Department ID not provided in POST data
    echo 'No se proporcionó el ID del departamento.';
}
?>
