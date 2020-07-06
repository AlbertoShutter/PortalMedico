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
            <?php include "menu.php";?>
        </div>
        <section id="container">

            <?php
                $busqueda = strtolower($_REQUEST['busqueda']);
                if(empty($busqueda)) {
                    header("Location: lista_usuarios.php");
                }
            ?>

            <h1>Lista de usuarios</h1>
            <a href="alta_usuario.php" class="btn_new">Registrar usuario</a>

            <form action="buscar_usuario.php" method="get" class="form_search">
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar..." value="<?php echo $busqueda ?>">
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
                $rol = '';
                if($busqueda == 'Administrativo') {
                    $rol = "OR Rol LIKE '%1%' ";
                } else if($busqueda == 'Medico') {
                    $rol = "OR Rol LIKE '%2%' ";
                }

                $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_usuarios FROM usuario WHERE ( 
                                                                                                        Nombre LIKE '%$busqueda%' OR
                                                                                                        Usuario LIKE '%$busqueda%' OR
                                                                                                        Rol LIKE '%$busqueda%' OR
                                                                                                        Especialidad LIKE '%$busqueda%' OR
                                                                                                        Correo LIKE '%$busqueda%' OR
                                                                                                        FechaAlta LIKE '%$busqueda%' OR
                                                                                                        FechaBaja LIKE '%$busqueda%' OR
                                                                                                        Estado LIKE '%$busqueda%' $rol)");
                $result_register = mysqli_fetch_array($sql_register);
                $total_usuario = $result_register['total_usuarios'];

                $por_pagina = 2;

                if(empty($_GET['pagina'])) {
                    $pagina = 1;
                } else {
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_usuario / $por_pagina);

                $query = mysqli_query($conection, "SELECT u.Id, u.Nombre, u.Usuario, r.Rol, u.Especialidad, u.Correo, u.FechaAlta, u.FechaBaja, u.Estado FROM usuario u 
                                                          INNER JOIN rol r ON u.Rol = r.Codigo WHERE ( 
                                                                                u.Nombre LIKE '%$busqueda%' OR
                                                                                u.Usuario LIKE '%$busqueda%' OR
                                                                                r.Rol LIKE '%$busqueda%' OR
                                                                                u.Especialidad LIKE '%$busqueda%' OR
                                                                                u.Correo LIKE '%$busqueda%' OR
                                                                                u.FechaAlta LIKE '%$busqueda%' OR
                                                                                u.FechaBaja LIKE '%$busqueda%' OR
                                                                                u.Estado LIKE '%$busqueda%')
                                                                                ORDER BY u.Nombre ASC LIMIT $desde,$por_pagina");
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
            <?php
                if($total_usuario != 0) {
                ?>
                <div class="paginador">
                    <ul>
                        <?php
                        if($pagina != 1) {
                            ?>
                            <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda?>">|<</a></li>
                            <li><a href="?pagina=<?php echo $pagina - 1; ?>&busqueda=<?php echo $busqueda?>"><<</a></li>
                            <?php
                        }
                        ?>
                        <?php
                        for($i = 1; $i <= $total_paginas; $i++) {
                            if($i == $pagina) {
                                echo '<li class="pageSelected"">' . $i . '</a></li>';
                            } else {
                                echo '<li><a href="?pagina=' . $i . '&busqueda='.$busqueda.'">' . $i . '</a></li>';
                            }
                        }
                        ?>
                        <?php
                        if($pagina != $total_paginas) {
                            ?>
                            <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda?>">>></a></li>
                            <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda?>">>|</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <?php
                }
            ?>
        </section>
    </body>
</html>