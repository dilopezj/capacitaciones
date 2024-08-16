<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// Recibir datos del formulario utilizando FormData
$empresa = $_POST['empresa'];
$tipo_documento = $_POST['tipo_documento'];
$documento = $_POST['documento'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$cargo = $_POST['cargo'];
$celular = $_POST['celular'];
$correo = $_POST['correo'];

if($correo != "" && $correo != null){

// Validar si ya existe el correo electrónico en la tabla usuarios
$sqlVerificarCorreo = "SELECT COUNT(*) as existe FROM usuarios WHERE correo_usuario = ?";
$stmtVerificarCorreo = $conn->prepare($sqlVerificarCorreo);
$stmtVerificarCorreo->bind_param("s", $correo);
$stmtVerificarCorreo->execute();
$stmtVerificarCorreo->bind_result($existe);
$stmtVerificarCorreo->fetch();
$stmtVerificarCorreo->close(); // Cerrar la consulta preparada

if ($existe > 0) {
    echo "El correo electrónico '$correo' ya está registrado.";
} else {
    // Consulta SQL para insertar datos en la tabla de estudiantes
    $sqlInsertarEstudiante = "INSERT INTO estudiantes (id_empresa, tipo_identificacion, id_estudiante, nombre, apellido, genero, cargo, celular, correo)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmtInsertarEstudiante = $conn->prepare($sqlInsertarEstudiante);
    if ($stmtInsertarEstudiante === false) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    // Vincular parámetros
    $stmtInsertarEstudiante->bind_param("isssssiss", $empresa, $tipo_documento, $documento, $nombres, $apellidos, $genero, $cargo, $celular, $correo);

    // Ejecutar la consulta de inserción de estudiantes
    if ($stmtInsertarEstudiante->execute()) {
        // Generar hash de contraseña (opcional)
        $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres

        // Consulta SQL para insertar usuario
        $sqlInsertarUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                               VALUES (?, ?, ?, 2, ?, 1)";

        // Preparar la declaración
        $stmtInsertarUsuario = $conn->prepare($sqlInsertarUsuario);
        if ($stmtInsertarUsuario === false) {
            die('Error al preparar la consulta: ' . $conn->error);
        }

        // Vincular parámetros
        $stmtInsertarUsuario->bind_param("ssss", $documento, $correo, $contrasena_hash, $documento);

        // Ejecutar la consulta de inserción de usuario
        if ($stmtInsertarUsuario->execute()) {
            echo "Nuevo estudiante creado exitosamente";
        } else {
            echo "Error al crear el usuario: " . $stmtInsertarUsuario->error;
        }

        // Cerrar la declaración de inserción de usuario
        $stmtInsertarUsuario->close();
    } else {
        echo "Error al insertar estudiante: " . $stmtInsertarEstudiante->error;
    }

    // Cerrar la declaración de inserción de estudiante y la conexión
    $stmtInsertarEstudiante->close();
}
}else{
      // Consulta SQL para insertar datos en la tabla de estudiantes
    $sqlInsertarEstudiante = "INSERT INTO estudiantes (id_empresa, tipo_identificacion, id_estudiante, nombre, apellido, genero, cargo, celular)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmtInsertarEstudiante = $conn->prepare($sqlInsertarEstudiante);
    if ($stmtInsertarEstudiante === false) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    // Vincular parámetros
    $stmtInsertarEstudiante->bind_param("isssssis", $empresa, $tipo_documento, $documento, $nombres, $apellidos, $genero, $cargo, $celular);

    // Ejecutar la consulta de inserción de estudiantes
    if ($stmtInsertarEstudiante->execute()) {
        // Generar hash de contraseña (opcional)
        $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres

        // Consulta SQL para insertar usuario
        $sqlInsertarUsuario = "INSERT INTO usuarios (nombre_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                               VALUES (?, ?, 2, ?, 1)";

        // Preparar la declaración
        $stmtInsertarUsuario = $conn->prepare($sqlInsertarUsuario);
        if ($stmtInsertarUsuario === false) {
            die('Error al preparar la consulta: ' . $conn->error);
        }

        // Vincular parámetros
        $stmtInsertarUsuario->bind_param("sss", $documento, $contrasena_hash, $documento);

        // Ejecutar la consulta de inserción de usuario
        if ($stmtInsertarUsuario->execute()) {
            echo "Nuevo estudiante creado exitosamente";
        } else {
            echo "Error al crear el usuario: " . $stmtInsertarUsuario->error;
        }

        // Cerrar la declaración de inserción de usuario
        $stmtInsertarUsuario->close();
    } else {
        echo "Error al insertar estudiante: " . $stmtInsertarEstudiante->error;
    }

    // Cerrar la declaración de inserción de estudiante y la conexión
    $stmtInsertarEstudiante->close();
}

// Cerrar la conexión
$conn->close();
?>
