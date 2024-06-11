<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuarioE = $_POST['idUsuarioE'];
    $usuario = $_POST['usuarioE'];
    $correo = $_POST['correoE'];
   // $perfil = $_POST['perfilE'];
    $identificacion = $_POST['ideE'];
    
    // Verifica si se proporciona una contraseña
    if(isset($_POST['passE']) && !empty($_POST['passE'])) {
        $pass = $_POST['passE'];
        $contrasena_hash = hash('sha256', $pass); // Hashea la contraseña
        $sqlUsuario = "UPDATE usuarios SET nombre_usuario = ?, correo_usuario = ?, contrasena_usuario = ?, identificacion = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sqlUsuario);
        $stmt->bind_param("ssssi", $usuario, $correo, $contrasena_hash,  $identificacion, $idUsuarioE);
    } else {
        // No se proporcionó una nueva contraseña
        $sqlUsuario = "UPDATE usuarios SET nombre_usuario = ?, correo_usuario = ?,  identificacion = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sqlUsuario);
        $stmt->bind_param("sssi", $usuario, $correo,  $identificacion, $idUsuarioE);
    }
    
    // Ejecuta la consulta
    if ($stmt->execute()) {        
        echo "Usuario actualizado exitosamente";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
