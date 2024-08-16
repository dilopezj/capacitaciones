<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

    // Recibir datos del formulario utilizando FormData
    $regional = $_POST['regional'];
    $ciudad = $_POST['ciudad'];
    $nit = $_POST['nit'];
    $nmb_empresa = $_POST['nmb_empresa'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $nmb_contacto = $_POST['nmb_contacto'];
    $apl_contacto = $_POST['apl_contacto'];

    // Consulta SQL para insertar datos en la tabla de estudiantes
    $sql = "INSERT INTO empresas (nit, nombre, regional, ciudad, direccion, telefono, nmb_contacto, apl_contacto, correo_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? )";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("issssssss", $nit, $nmb_empresa, $regional, $ciudad, $direccion, $telefono, $nmb_contacto, $apl_contacto, $correo);


    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Nueva empresa creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
?>
