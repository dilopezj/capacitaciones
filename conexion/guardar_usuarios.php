<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];
    $correo = $_POST['correo'];
    $perfil = $_POST['perfil'];
    $identificacion = $_POST['ide'];
     // Reemplaza 0 y 1 con las variables reales que corresponden a id_perfil e identificacion, respectivamente
    // $identificacion = 0;
     $activo = 1;

     $contrasena_hash = hash('sha256', $pass); // Puedes utilizar otro algoritmo de hash si prefieres
    
    $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlUsuario);
    
    $stmt->bind_param("ssssss", $usuario, $correo, $contrasena_hash, $perfil, $identificacion, $activo);   
    
    // Ejecuta la consulta
    if ($stmt->execute()) {        
        echo "Nuevo usuario creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
