<?php
include 'conexion.php';
// Iniciar sesión
session_start();

// Obtener datos del formulario de login
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Consulta SQL para verificar las credenciales del usuario
$sql = "SELECT id_usuario, correo_usuario, nombre_usuario, u.id_perfil, p.nombre_perfil, u.estudiante "
        . " FROM usuarios u, perfiles p"
        . " WHERE nombre_usuario = '$usuario' AND contrasena_usuario = '$contrasena'"
        . "       AND p.id_perfil = u.id_perfil";
$resultado = $conn->query($sql);

if ($resultado->num_rows == 1) {
    // Usuario autenticado correctamente
    $fila = $resultado->fetch_assoc();

    // Guardar datos del usuario en la sesión
    $_SESSION['id_usuario'] = $fila['id_usuario'];
    $_SESSION['nombre_usuario'] = $fila['nombre_usuario'];
    $_SESSION['correo_usuario'] = $fila['correo_usuario'];
    $_SESSION['id_perfil'] = $fila['id_perfil'];
    $_SESSION['nombre_perfil'] = $fila['nombre_perfil'];
    $_SESSION['estudiante'] = $fila['estudiante'];

    echo 1; // Mensaje de éxito
    
} else {
    // Usuario no encontrado o credenciales incorrectas
    echo "Correo electrónico o contraseña incorrectos";    
    $_SESSION['error_login'] = "Correo electrónico o contraseña incorrectos";
    
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
