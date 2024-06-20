<?php
include 'conexion.php';

// Iniciar sesión (si es necesario)
session_start();

// PHP para insertar datos en la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEmpresaE = $_POST['idEmpresaE'];
    // Recibir datos del formulario utilizando FormData
    $regional = $_POST['regionalE'];
    $ciudad = $_POST['ciudadE'];
    $nit = $_POST['nitE'];
    $nmb_empresa = $_POST['nmb_empresaE'];
    $direccion = $_POST['direccionE'];
    $telefono = $_POST['telefonoE'];
    $correo = $_POST['correoE'];
    $nmb_contacto = $_POST['nmb_contactoE'];
    $apl_contacto = $_POST['apl_contactoE'];
    
        // No se proporcionó una nueva contraseña
        $sqlEmpresa = "UPDATE empresas SET nombre= ?, regional = ?, ciudad = ?, direccion = ?, telefono = ?, nmb_contacto = ?, apl_contacto = ?, correo_contacto = ? WHERE nit = ?";
        $stmt = $conn->prepare($sqlEmpresa);
        $stmt->bind_param("ssssssssi", $nmb_empresa, $regional,  $ciudad, $direccion,$telefono,$nmb_contacto,$apl_contacto,$correo, $idEmpresaE);
        
    // Ejecuta la consulta
    if ($stmt->execute()) {        
        echo "Empresa actualizada exitosamente";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se recibieron datos del formulario.";
}
?>
