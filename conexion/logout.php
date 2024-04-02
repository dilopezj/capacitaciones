<?php
// Iniciar la sesión si no está iniciada
session_start();

// Destruir todas las variables de sesión
session_unset();
session_destroy();

// Redirigir al usuario al formulario de login
header("Location: ./../index.php");
exit(); // Terminar el script después de la redirección
?>
