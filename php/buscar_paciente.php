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
                    header("Location: lista_pacientes.php");
                }
            ?>

            <h1>Lista de pacientes</h1>
            <a href="alta.php" class="btn_new">Registrar paciente</a>

            <form action="buscar_paciente.php" method="get" class="form_search">
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar..." value="<?php echo $busqueda ?>">
                <input type="submit" value="Buscar" class="btn_search">
            </form>

            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Tipo Documento</th>
                    <th>Documento</th>
                    <th>Dirección</th>
                    <th>Provincia</th>
                    <th>Localidad</th>
                    <th>Pais</th>
                    <th>Acciones</th>
                </tr>
                <?php
                //Paginador
                $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_pacientes FROM pacientes WHERE ( 
                                                                                                        Nombre LIKE '%$busqueda%' OR
                                                                                                        Apellidos LIKE '%$busqueda%' OR
                                                                                                        FechaNac LIKE '%$busqueda%' OR
                                                                                                        TipoDoc LIKE '%$busqueda%' OR
                                                                                                        Documento LIKE '%$busqueda%' OR
                                                                                                        Direccion LIKE '%$busqueda%' OR
                                                                                                        Provincia LIKE '%$busqueda%' OR
                                                                                                        Localidad LIKE '%$busqueda%' OR
                                                                                                        Pais LIKE '%$busqueda%')");
                $result_register = mysqli_fetch_array($sql_register);
                $total_pacientes = $result_register['total_pacientes'];

                $por_pagina = 5;

                if(empty($_GET['pagina'])) {
                    $pagina = 1;
                } else {
                    $pagina = $_GET['pagina'];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_pacientes / $por_pagina);

                $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE ( 
                                                                                Id LIKE '%$busqueda%' OR
                                                                                Nombre LIKE '%$busqueda%' OR
                                                                                Apellidos LIKE '%$busqueda%' OR
                                                                                FechaNac LIKE '%$busqueda%' OR
                                                                                TipoDoc LIKE '%$busqueda%' OR
                                                                                Documento LIKE '%$busqueda%' OR
                                                                                Direccion LIKE '%$busqueda%' OR
                                                                                Provincia LIKE '%$busqueda%' OR
                                                                                Localidad LIKE '%$busqueda%' OR
                                                                                Pais LIKE '%$busqueda%')
                                                                                ORDER BY Nombre ASC LIMIT $desde,$por_pagina;");
                $result = mysqli_num_rows($query);
                if($result > 0) {
                    while($data = mysqli_fetch_array($query)) {
                        $id = $data['Id'];
                        ?>
                        <tr>
                            <td><?php echo $data['Nombre']?></td>
                            <td><?php echo $data['Apellidos']?></td>
                            <td><?php echo $data['FechaNac']?></td>
                            <td><?php echo $data['TipoDoc']?></td>
                            <td><?php echo $data['Documento']?></td>
                            <td><?php echo $data['Direccion']?></td>
                            <td><?php echo $data['Provincia']?></td>
                            <td><?php echo $data['Localidad']?></td>
                            <td><?php echo $data['Pais']?></td>
                            <td>
                                <?php
                                    $current_user = $_SESSION["usuarioactual"];
                                    $query_control = mysqli_query($conection, "SELECT Rol FROM usuario WHERE usuario = '$current_user'");
                                    $result_control = mysqli_num_rows($query_control);
                                    if($result_control > 0) {
                                        while($data = mysqli_fetch_array($query_control)) {
                                            $user_status = $data['Rol'];
                                        }
                                    }

                                    if($user_status != 1) {
                                        ?>
                                        <!--<a class="link_crear"
                                           href="informe_medico.php?Id=<?php echo $id; ?>">Crear
                                            Informe</a>
                                        |-->
                                        <a class="link_list_inform"
                                           href="lista_informes.php?Id=<?php echo $id; ?>">Informes</a>
                                        |
                                    <?php
                                    }
                                ?>
                                <a class="link_cita" href="lista_citas_paciente.php?Id=<?php echo $id;?>">Citas</a>
                                |
                                <a class="link_edit" href="editar_paciente.php?Id=<?php echo $id;?>">Editar</a>
                                |
                                <a class="link_remove" href="eliminar_paciente.php?Id=<?php echo $id;?>">Eliminar</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <?php
                if($total_pacientes != 0) {
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