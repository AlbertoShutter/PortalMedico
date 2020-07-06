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
            <h1>Lista de citas reservadas</h1>
            <a href="alta_cita.php" class="btn_new">Solicitar cita</a>

            <!--<form action="buscar_paciente.php" method="get" class="form_search">
                <input type="text" name="busqueda" id="busqueda" placeholder="Buscar...">
                <input type="submit" value="Buscar" class="btn_search">
            </form>-->

            <table>
                <tr>
                    <th>Paciente</th>
                    <th>Medico</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <!--<th>Duracion</th>-->
                    <th>Acciones</th>
                </tr>
                <?php
                    //Paginador
                    $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_citas FROM cita");
                    $result_register = mysqli_fetch_array($sql_register);
                    $total_citas = $result_register['total_citas'];

                    $por_pagina = 5;

                    if(empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina-1) * $por_pagina;
                    $total_paginas = ceil($total_citas / $por_pagina);

                    $query = mysqli_query($conection, "SELECT c.Id, (u.Nombre) as Nom_med, (p.Nombre) as Nom_pac, p.Apellidos, c.Fecha, c.Hora, c.Observaciones 
                                                                FROM cita c 
                                                                INNER JOIN usuario u ON c.Medico = u.Id 
                                                                INNER JOIN pacientes p ON p.Id = c.Paciente 
                                                                ORDER BY Fecha DESC, Hora ASC
                                                                LIMIT $desde,$por_pagina;");
                    $result = mysqli_num_rows($query);
                    if($result > 0) {
                        while($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?php echo $data['Nom_pac'].' '.$data['Apellidos']?></td>
                                <td><?php echo $data['Nom_med']?></td>
                                <td><?php echo $data['Fecha']?></td>
                                <td><?php echo $data['Hora']?></td>
                                <!--<td><?php echo $data['Duracion']?></td>-->
                                <td>
                                    <a class="link_edit" href="editar_cita.php?Identificador=<?php echo $data['Id'];?>">Editar</a>
                                    |
                                    <a class="link_remove" href="eliminar_cita.php?Identificador=<?php echo $data['Id'];?>">Eliminar</a>
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