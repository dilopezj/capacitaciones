<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $pass = $_POST['pass'];
    $correo = $_POST['correo'];
    $perfil = $_POST['perfil'];
    $identificacion = $_POST['ide'];
    $activo = 1;

    // Hash de la contraseña
    $contrasena_hash = hash('sha256', $pass); // Puedes utilizar otro algoritmo de hash si prefieres

    // Verificar si el usuario ya existe por nombre de usuario o correo electrónico
    $sqlVerificarUsuario = "SELECT COUNT(*) AS count FROM usuarios WHERE nombre_usuario = ? ";
    $stmtVerificarUsuario = $conn->prepare($sqlVerificarUsuario);
    $stmtVerificarUsuario->bind_param('ss', $usuario, $correo);
    $stmtVerificarUsuario->execute();
    $resultVerificarUsuario = $stmtVerificarUsuario->get_result();
    $rowVerificarUsuario = $resultVerificarUsuario->fetch_assoc();
    $countUsuarios = $rowVerificarUsuario['count'];

    // Si countUsuarios es mayor que 0, significa que ya existe un usuario con ese nombre de usuario o correo
    if ($countUsuarios > 0) {
        echo "Error: El nombre de usuario  ya están registrados.";
    } else {
        // Preparar la consulta para insertar el nuevo usuario
        $sqlInsertUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
        $stmtInsertUsuario->bind_param("ssssss", $usuario, $correo, $contrasena_hash, $perfil, $identificacion, $activo);

        // Ejecutar la consulta de inserción
        if ($stmtInsertUsuario->execute()) {
            echo "Nuevo usuario creado exitosamente";
        } else {
            echo "Error al crear el usuario: " . $stmtInsertUsuario->error;
        }

        // Cerrar la consulta de inserción
        $stmtInsertUsuario->close();
    }

    // Cerrar la consulta de verificación y la conexión
    $stmtVerificarUsuario->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
