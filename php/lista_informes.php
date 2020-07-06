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
            <h1>Lista de informes</h1>
            <?php
                if(empty($_GET['Id'])) {
                    header('Location: lista_pacientes.php');
                }
                $id_pac = $_GET['Id'];
            ?>
            <a href="informe_medico.php?Id=<?php echo $id_pac; ?>" class="btn_new">Crear informe</a>
            <table>
                <tr>
                    <th>Titulo</th>
                    <th>Medico</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
                <?php
                    /*if(empty($_GET['Id'])) {
                        header('Location: lista_pacientes.php');
                    }
                    $id_pac = $_GET['Id'];*/

                    //Paginador
                    $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_informes FROM informe WHERE Paciente = '$id_pac' ");
                    $result_register = mysqli_fetch_array($sql_register);
                    $total_informes = $result_register['total_informes'];

                    $por_pagina = 2;

                    if(empty($_GET['pagina'])) {
                        $pagina = 1;
                    } else {
                        $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina-1) * $por_pagina;
                    $total_paginas = ceil($total_informes / $por_pagina);

                    $query = mysqli_query($conection, "SELECT i.Id, Titulo, (u.Nombre) as Nom_med, p.Nombre, p.Apellidos, Fecha, Hora 
                                                                FROM informe i 
                                                                INNER JOIN usuario u ON i.Medico = u.Id 
                                                                INNER JOIN pacientes p ON i.Paciente = p.Id 
                                                                WHERE p.Id = '$id_pac'
                                                                ORDER BY Fecha ASC 
                                                                LIMIT $desde,$por_pagina;");
                    $result = mysqli_num_rows($query);

                    if($result > 0) {
                        while($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?php echo $data['Titulo']?></td>
                                <td><?php echo $data['Nom_med']?></td>
                                <td><?php echo $data['Fecha']?></td>
                                <td><?php echo $data['Hora']?></td>
                                <td>
                                    <a class="link_gen" href="pdf_generador.php?Id=<?php echo $data['Id'] ?>" target="_blank">Generar</a>
                                    |
                                    <a class="link_edit" href="editar_informe.php?Id=<?php echo $data['Id'] ?>">Editar</a>
                                    |
                                    <a class="link_remove" href="eliminar_informe.php?Id=<?php echo $data['Id'] ?>">Eliminar</a>
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