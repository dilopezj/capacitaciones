<?php include './conexion/conexion.php'; ?>
<!-- Nav lateral -->
<section class="full-box nav-lateral">
    <div class="full-box nav-lateral-bg show-nav-lateral"></div>
    <div class="full-box nav-lateral-content">
        <figure class="full-box nav-lateral-avatar">
            <i class="far fa-times-circle show-nav-lateral"></i>
            <img src="./assets/avatar/Avatar.png" class="img-fluid" alt="Avatar">
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
                WHERE m.nivel = 1 AND m.padre = 0 AND  up.id_perfil = " . $_SESSION['id_perfil'] . ";";
                $resultadoMenu = $conn->query($sql1);
                if ($resultadoMenu->num_rows > 0) {
                    // Mostrar las preguntas
                    while ($filaMenu = $resultadoMenu->fetch_assoc()) {
                        ?>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fab fa-dashcube fa-fw"></i> &nbsp; <?php echo $filaMenu["nombre_menu"] ?><i class="fas fa-chevron-down"></i></a>
                            <?php
                            $sql2 = "select m.id_menu, m.nombre_menu, m.url_menu, m.nivel, m.padre from menus m 
                                        INNER JOIN permisos AS p ON m.id_menu = p.id_menu
                                        INNER JOIN usuarios_permisos AS up ON p.id_permiso = up.id_permiso
                                        WHERE m.nivel = 2 AND m.padre = ".$filaMenu["id_menu"]." AND  up.id_perfil = " . $_SESSION['id_perfil'] . ";";
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

<!--
                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Clientes <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="#" class="tile_menu" data-file="client-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Cliente</a>
                        </li>
                        <li>
                            <a href="#" class="tile:menu" data-file="client-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="client-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar cliente</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-pallet fa-fw"></i> &nbsp; Modulos <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="#" class="tile_menu" data-file="item-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Agregar Modulos</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="item-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Modulos</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="item-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar Modulos</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas fa-file-invoice-dollar fa-fw"></i> &nbsp; Examenes <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="#" class="tile_menu" data-file="reservation-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo Examen</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="reservation-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de Examenes</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="reservation-search.html"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; Buscar Examenes</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="reservation-pending.php"><i class="fas fa-hand-holding-usd fa-fw"></i> &nbsp; Examenes pendientes</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp; Usuarios <i class="fas fa-chevron-down"></i></a>
                    <ul>
                        <li>
                            <a href="#" class="tile_menu" data-file="user-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="user-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de usuarios</a>
                        </li>
                        <li>
                            <a href="#" class="tile_menu" data-file="user-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar usuario</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="tile_menu" data-file="company.html"><i class="fas fa-store-alt fa-fw"></i> &nbsp; Empresa</a>
                </li>
-->
            </ul>
        </nav>
    </div>
</section>


