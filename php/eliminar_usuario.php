<?php
    include "conexion.php";

    if(!empty($_POST)) {
        $user = $_POST['usuario'];

        $query_delete = mysqli_query($conection, "DELETE FROM usuario WHERE Id = '$user'");

        if($query_delete) {
            header("Location: lista_usuarios.php");
        } else {
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['Id'])) {
        header("Location: lista_usuarios.php");
    } else {
        $user = $_REQUEST['Id'];
        $query = mysqli_query($conection, "SELECT u.Id, u.Nombre, u.Usuario, r.Rol FROM usuario u INNER JOIN rol r ON u.Rol = r.Codigo WHERE u.Id='$user'");
        $result = mysqli_num_fields($query);

        if($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
                $nombre = $data['Nombre'];
                $usuario = $data['Usuario'];
                $rol = $data['Rol'];
            }
        } else {
            header("Location: lista_usuarios.php");
        }
    }
?>

<?php include("seguridad.php"); ?>
<!DOCTYPE html>
<html>
    <header>
        <title>Portal de empleados</title>
        <link rel="stylesheet" type="text/css" href="../css/eliminar_paciente.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/btn_salir.css" media="screen" />
    </header>
    <body>
        <div class="menu">
            <?php include "menu.php";?>
        </div>
        <section id="container">
            <div class="data_delete">
                <h2>¿Está seguro de eliminar el siguiente registro?</h2>
                <p>Nombre: <span><?php echo $nombre; ?></span></p>
                <p>Usuario: <span><?php echo $usuario; ?></span></p>
                <p>Especialidad: <span><?php echo $rol; ?></span></p>

                <form method="post" action="">
                    <input type="hidden" name="usuario" value="<?php echo $user; ?>">
                    <a href="lista_usuarios.php" class="btn_cancel" style="text-decoration: none">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
    </body>
</html>