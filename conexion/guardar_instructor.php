<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $regional = $_POST['regional'];
    $ciudad = $_POST['ciudad'];
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $genero = 1;
    
    $sql = "INSERT INTO instructores (regional, ciudad, tipo_identificacion, identificacion, nombres, apellidos, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $regional, $ciudad, $tipo_documento, $documento, $nombres, $apellidos, $genero);
    
    if ($stmt->execute()) {
         // Insertar en la tabla de usuarios
        $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres
        $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                        VALUES ('$documento','','$contrasena_hash',3,'$documento',1)";
        $conn->query($sqlUsuario);
        
        echo "Nuevo instructor creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
