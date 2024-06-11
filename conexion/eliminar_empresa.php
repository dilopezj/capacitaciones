<?php

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ha enviado un ID de empresa a eliminar
    if (isset($_POST['idEmpresa'])) {
        // Obtener el ID de la empresa a eliminar
        $idEmpresa = $_POST['idEmpresa'];

        // Verificar si la empresa tiene estudiantes asociados
        $sqlCheck = "SELECT COUNT(*) AS total_estudiantes FROM estudiantes WHERE id_empresa = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $idEmpresa);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $rowCheck = $resultCheck->fetch_assoc();
        $totalEstudiantes = $rowCheck['total_estudiantes'];

        if ($totalEstudiantes > 0) {
            // Si la empresa tiene estudiantes asociados, mostrar un mensaje de error
            echo "Error: No se puede eliminar la empresa porque tiene estudiantes asociados.";
        } else {
            // Si no tiene estudiantes asociados, proceder con la eliminación de la empresa
            // Consulta SQL para eliminar la empresa
            $sql = "DELETE FROM empresas WHERE nit = ?";

            // Preparar la consulta
            $stmt = $conn->prepare($sql);

            // Vincular el parámetro del ID de la empresa
            $stmt->bind_param("i", $idEmpresa);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si la eliminación fue exitosa, mostrar un mensaje de éxito
                echo "Empresa eliminada exitosamente";
            } else {
                // Si ocurrió un error al eliminar la empresa, mostrar el mensaje de error
                echo "Error al intentar eliminar la empresa: " . $stmt->error;
            }

            // Cerrar la conexión y la sentencia preparada
            $stmt->close();
        }
    } else {
        // Si no se recibió el ID de la empresa a eliminar, mostrar un mensaje de error
        echo "Error: No se recibió el ID de la empresa a eliminar.";
    }
} else {
    echo "Error: No se recibieron datos del formulario.";
}

?>
