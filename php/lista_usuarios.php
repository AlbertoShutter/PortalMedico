<?php include "conexion.php"; ?>
<?php include("seguridad.php"); ?>

<!DOCTYPE html>
    <html lang="en">
        <header>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="../css/lista_pacientes.css">
            <link rel="stylesheet" type="text/css" href="../css/btn_salir.css" media="screen" />
            <title>Portal empleados</title>
        </header>
        <body>
            <div class="menu">
                <?php include "menu.php"; ?>
                <?php include "btn_salir.php"; ?>
            </div>
            <section id="container">
                <h1>Lista de usuarios</h1>
                <a href="alta_usuario.php" class="btn_new">Registrar usuario</a>

                <form action="buscar_usuario.php" method="get" class="form_search">
                    <input type="text" name="busqueda" id="busqueda" placeholder="Buscar...">
                    <input type="submit" value="Buscar" class="btn_search">
                </form>

                <table>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Especialidad</th>
                        <th>Correo</th>
                        <!--<th>Fecha Alta</th>
                        <th>Fecha Baja</th>
                        <th>Estado</th>-->
                        <th>Acciones</th>
                    </tr>
                    <?php
                        //Paginador
                        $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_usuarios FROM usuario");
                        $result_register = mysqli_fetch_array($sql_register);
                        $total_usuarios = $result_register['total_usuarios'];

                        $por_pagina = 3;

                        if(empty($_GET['pagina'])) {
                            $pagina = 1;
                        } else {
                            $pagina = $_GET['pagina'];
                        }

                        $desde = ($pagina-1) * $por_pagina;
                        $total_paginas = ceil($total_usuarios / $por_pagina);

                        $query = mysqli_query($conection, "SELECT u.Id, u.Nombre, u.Usuario, r.Rol, u.Especialidad, u.Correo, u.FechaAlta, u.FechaBaja, u.Estado FROM usuario u 
                                                                  INNER JOIN rol r ON u.Rol = r.Codigo ORDER BY Nombre ASC LIMIT $desde,$por_pagina;");
                        $result = mysqli_num_rows($query);
                        if($result > 0) {
                            while($data = mysqli_fetch_array($query)) {
                                $id_usu = $data['Id'];
                    ?>
                        <tr>
                            <td><?php echo $data['Nombre']?></td>
                            <td><?php echo $data['Usuario']?></td>
                            <td><?php echo $data['Rol']?></td>
                            <td><?php echo $data['Especialidad']?></td>
                            <td><?php echo $data['Correo']?></td>
                            <!--<td><?php echo $data['FechaAlta']?></td>
                            <td><?php echo $data['FechaBaja']?></td>
                            <td><?php echo $data['Estado']?></td>-->
                            <td>
                                <a class="link_cita" href="calendario_medico.php?Id=<?php echo $id_usu;?>">Calendario</a>
                                |
                                <a class="link_gen" href="lista_festivos_usuario.php?Id=<?php echo $id_usu;?>">Festivo</a>
                                |
                                <a class="link_edit" href="editar_usuario.php?Id=<?php echo $id_usu;?>">Editar</a>
                                |
                                <a class="link_remove" href="eliminar_usuario.php?Id=<?php echo $id_usu; ?>">Eliminar</a>
                            </td>
                        </tr>
                     <?php
                            }
                        }
                    ?>
                </table>
                <div class="paginador">
                    <ul>
                        <?php
                            if($pagina != 1) {
                                ?>
                                <li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
                                <li><a href="?pagina=<?php echo $pagina - 1; ?>"><<</a></li>
                                <?php
                            }
                        ?>
                        <?php
                            for($i = 1; $i <= $total_paginas; $i++) {
                                if($i == $pagina) {
                                    echo '<li class="pageSelected"">' . $i . '</a></li>';
                                } else {
                                    echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                        ?>
                        <?php
                            if($pagina != $total_paginas) {
                                ?>
                                <li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
                                <li><a href="?pagina=<?php echo $total_paginas; ?>">>|</a></li>
                                <?php
                            }
                        ?>
                    </ul>
                </div>
            </section>
        </body>
    </html>