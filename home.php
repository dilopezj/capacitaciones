<?php
session_start();

// Verificar si la variable de sesión id_usuario no está definida
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al usuario al formulario de login
    header("Location: index.php");
    exit(); // Terminar el script después de la redirección
}
?>
<!DOCTYPE html>
<html lang="es">
    <?php include './components/head.html'; ?>
    <body>
        <!-- Main container -->
        <main class="full-box main-container">
            <?php include './components/sidebar.php'; ?>
            <!-- Page content -->
            <section class="full-box page-content">
                <?php include './components/header.html'; ?>
                <div id="contenido-importado">
                    <?php //include './menu.php'; ?>
                </div> 
            </section>           
        </main>
        <?php include './components/footer.html'; ?>
    </body>
</html>