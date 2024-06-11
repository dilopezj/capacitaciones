<?php include './conexion/conexion.php'; ?>
<!-- Nav lateral -->
<section class="full-box nav-lateral">
    <div class="full-box nav-lateral-bg show-nav-lateral"></div>
    <div class="full-box nav-lateral-content">
        <img src="assets/img/logo sura blanco.svg" style="width: 58%;text-align: center;margin: 0 72px;" >
        <figure class="full-box nav-lateral-avatar" style="padding: 0;">
            <i class="far fa-times-circle show-nav-lateral"></i>
            <img src="./assets/avatar/Avatar.png" class="img-fluid" alt="Avatar" style="width: 25% !important;">            
            <figcaption class="roboto-medium text-center">
                <?php echo $_SESSION['nombre_usuario'] ?> <br><small class="roboto-condensed-light"><?php echo $_SESSION['nombre_perfil'] ?></small>
            </figcaption>
        </figure>
        <div class="full-box nav-lateral-bar"></div>
        <nav class="full-box nav-lateral-menu">
            <ul>
                <?php
                // Primer query para obtener los menús de nivel 1 y padre 0
                $sql1 = "select m.id_menu, m.nombre_menu, m.url_menu, m.nivel, m.padre from menus m 
                INNER JOIN permisos AS p ON m.id_menu = p.id_menu
                INNER JOIN usuarios_permisos AS up ON p.id_permiso = up.id_permiso
                WHERE m.nivel = 1 AND m.padre = 0 AND  up.id_perfil = " . $_SESSION['id_perfil'] . " order by m.orden;";
                $resultadoMenu = $conn->query($sql1);
                if ($resultadoMenu->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaMenu = $resultadoMenu->fetch_assoc()) {
                        ?>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fab fa-dashcube fa-fw"></i> &nbsp; <?php echo $filaMenu["nombre_menu"] ?><i class="fas fa-chevron-down"></i></a>
                            <?php
                            // Segundo query para obtener los menús de nivel 2 y padre X
                            $sql2 = "select m.id_menu, m.nombre_menu, m.url_menu, m.nivel, m.padre from menus m 
                                        INNER JOIN permisos AS p ON m.id_menu = p.id_menu
                                        INNER JOIN usuarios_permisos AS up ON p.id_permiso = up.id_permiso
                                        WHERE m.nivel = 2 AND m.padre = ".$filaMenu["id_menu"]." AND  up.id_perfil = " . $_SESSION['id_perfil'] . "  order by m.orden;;";
                            $resultadoChild = $conn->query($sql2);
                            if ($resultadoChild->num_rows > 0) { ?>
                                <ul>
                                    <?php 
                                    while ($filaChild = $resultadoChild->fetch_assoc()) {
                                    ?>
                                    <li>
                                        <a href="#" class="tile_menu" data-file="<?php echo $filaChild["url_menu"] ?>"><i class="fas fa-plus fa-fw"></i> &nbsp; <?php echo $filaChild["nombre_menu"] ?></a>
                                    </li>
                                    <?php
                                    }
                                    ?>                                    
                                </ul>                                
                            <?php
                            }
                            ?>                            
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
</section>


