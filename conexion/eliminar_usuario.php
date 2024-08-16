<?php

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Verificar si se ha enviado un ID de usuario a eliminar
    if (isset($_POST['id_usuario'])) {
        // Obtener el ID del usuario a eliminar
        $id_usuario = $_POST['id_usuario'];

        // Consulta SQL para eliminar el usuario
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";

        // Preparar la consulta
        $stmt = $conn->prepare($sql);

        // Vincular el parámetro del ID del usuario
        $stmt->bind_param("i", $id_usuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la eliminación fue exitosa, mostrar un mensaje de éxito
            echo "Usuario eliminado exitosamente";
        } else {
            // Si ocurrió un error al eliminar el usuario, mostrar el mensaje de error
            echo "Error al intentar eliminar el usuario: " . $stmt->error;
        }

        // Cerrar la conexión y la sentencia preparada
        $stmt->close();
        $conn->close();
    } else {
        // Si no se recibió el ID del usuario a eliminar, mostrar un mensaje de error
        echo "Error: No se recibió el ID del usuario a eliminar.";
    }
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
