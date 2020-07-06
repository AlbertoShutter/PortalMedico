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
    <h1>Lista de festivos</h1>
    <a href="festivos.php" class="btn_new">Registrar festivo</a>

    <!--<form action="buscar_festivos.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar...">
        <input type="submit" value="Buscar" class="btn_search">
    </form>-->

    <table>
        <tr>
            <th>Medico</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?php
        if(empty($_REQUEST['Id'])) {
            header("Location: lista_usuarios.php");
        }
        $id = $_REQUEST['Id'];

        //Paginador
        $sql_register = mysqli_query($conection, "SELECT COUNT(*) as total_festivos FROM festivos WHERE medico = '$id' OR medico = '8'");
        $result_register = mysqli_fetch_array($sql_register);
        $total_pacientes = $result_register['total_festivos'];

        $por_pagina = 5;

        if(empty($_GET['pagina'])) {
            $pagina = 1;
        } else {
            $pagina = $_GET['pagina'];
        }

        $desde = ($pagina-1) * $por_pagina;
        $total_paginas = ceil($total_pacientes / $por_pagina);

        $query = mysqli_query($conection, "SELECT codigo, u.nombre, tipo, fecha 
                                                    FROM festivos f 
                                                    INNER JOIN usuario u ON f.medico = u.Id 
                                                    WHERE medico = '$id' OR medico = '8'
                                                    ORDER BY fecha ASC 
                                                    LIMIT $desde,$por_pagina;");
        $result = mysqli_num_rows($query);
        if($result > 0) {
            while($data = mysqli_fetch_array($query)) {
                $id_fes = $data['codigo'];
                ?>
                <tr>
                    <td><?php echo $data['nombre']?></td>
                    <td>
                        <?php
                        if($data['tipo'] == 1) {
                            echo "Completo";
                        } else if( $data['tipo'] == 2) {
                            echo "Mañana";
                        } else {
                            echo "Tarde";
                        }
                        ?>
                    </td>
                    <td><?php echo $data['fecha']?></td>
                    <td>
                        <a class="link_edit" href="editar_festivos.php?Id=<?php echo $id_fes;?>">Editar</a>
                        |
                        <a class="link_remove" href="eliminar_festivo.php?Id=<?php echo $id_fes;?>">Eliminar</a>
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