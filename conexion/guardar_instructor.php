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
    $mensaje = "";

    // Verificar si el instructor ya está registrado por su número de documento
    $sqlVerificarInstructor = "SELECT COUNT(*) AS count FROM instructores WHERE identificacion = ?";
    $stmtVerificarInstructor = $conn->prepare($sqlVerificarInstructor);
    $stmtVerificarInstructor->bind_param('s', $documento);
    $stmtVerificarInstructor->execute();
    $resultVerificarInstructor = $stmtVerificarInstructor->get_result();
    $rowVerificarInstructor = $resultVerificarInstructor->fetch_assoc();
    $countInstructores = $rowVerificarInstructor['count'];

    // Si countInstructores es mayor que 0, significa que ya existe un instructor con ese número de documento
    if ($countInstructores > 0) {
        echo "Error: El instructor con número de documento $documento ya está registrado.";
    } else {
        // Preparar la consulta para insertar el nuevo instructor
        $sqlInsertInstructor = "INSERT INTO instructores (regional, ciudad, tipo_identificacion, identificacion, nombres, apellidos, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsertInstructor = $conn->prepare($sqlInsertInstructor);
        $stmtInsertInstructor->bind_param("sssssss", $regional, $ciudad, $tipo_documento, $documento, $nombres, $apellidos, $genero);

        // Ejecutar la consulta de inserción del instructor
        if ($stmtInsertInstructor->execute()) {
            // Insertar usuario relacionado con el instructor
            $activo = 1;
            $perfil = 3;
            $correo = null;
            $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres

            $sqlInsertUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
            $stmtInsertUsuario->bind_param("ssssss", $documento, $correo, $contrasena_hash, $perfil, $documento, $activo);

            // Ejecutar la consulta de inserción del usuario
            if ($stmtInsertUsuario->execute()) {
                $mensaje .= "Usuario e ";
            } else {
                $mensaje .= "Error al crear el usuario: " . $stmtInsertUsuario->error;
            }

            $stmtInsertUsuario->close();

            $mensaje .= "Instructor creado exitosamente.";
        } else {
            $mensaje .= "Error al crear el instructor: " . $stmtInsertInstructor->error;
        }

        // Cerrar la consulta de inserción del instructor
        $stmtInsertInstructor->close();
    }

    // Cerrar la consulta de verificación y la conexión
    $stmtVerificarInstructor->close();
    $conn->close();

    echo $mensaje;
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
