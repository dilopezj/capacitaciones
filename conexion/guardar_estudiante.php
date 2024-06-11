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
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    //$edad = $_POST['edad'];
    $cargo = $_POST['cargo'];
    $direccion = $_POST['direccion'];
    $celular = $_POST['celular'];
    $correo = $_POST['correo'];

    // Consulta SQL para insertar datos en la tabla de estudiantes
    $sql = "INSERT INTO estudiantes (id_empresa, tipo_identificacion, id_estudiante, nombre, apellido, genero, fecha_nac, edad, cargo, direccion, celular, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("isssssissss", $empresa, $tipo_documento, $documento, $nombres, $apellidos, $genero, $fecha_nacimiento,  $cargo, $direccion, $celular, $correo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $contrasena_hash = hash('sha256', $documento); // Puedes utilizar otro algoritmo de hash si prefieres
        $sqlUsuario = "INSERT INTO usuarios (nombre_usuario, correo_usuario, contrasena_usuario, id_perfil, identificacion, activo)
                        VALUES ('$documento','$correo','$contrasena_hash',2,'$documento',1)";
        $conn->query($sqlUsuario);

        echo "Nuevo estudiante creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
?>
