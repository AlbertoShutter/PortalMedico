<?php
include "conexion.php";

if(!empty($_POST)) {
    $id = $_POST['Id'];

    $query_delete = mysqli_query($conection, "DELETE FROM festivos WHERE codigo = '$id'");

    if($query_delete) {
        header("Location: lista_festivos.php");
    } else {
        echo "Error al eliminar";
    }
}

if(empty($_REQUEST['Id'])) {
    header("Location: lista_festivos.php");
} else {
    $id = $_REQUEST['Id'];
    $query = mysqli_query($conection, "SELECT * FROM festivos WHERE codigo = '$id'");
    $result = mysqli_num_fields($query);

    if($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $id = $data['codigo'];
            $fecha = $data['fecha'];
            $tipo = $data['tipo'];

            if($tipo == 1) {
                $option = '<option value="'.$tipo.'" selected>Completo</option>';
            } else if($tipo == 2) {
                $option = '<option value="'.$tipo.'" selected>Mañana</option>';
            } else {
                $option = '<option value="'.$tipo.'" selected>Tarde</option>';
            }

            $medico = $data['medico'];
        }

        $query_user = mysqli_query($conection, "SELECT Usuario FROM usuario WHERE Id = '$medico'");
        $result_user = mysqli_num_rows($query_user);
        if($result_user > 0) {
            while($datauser = mysqli_fetch_array($query_user)) {
                $medico = $datauser['Usuario'];
            }
        }

    } else {
        header("Location: lista_festivos.php");
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
                <p>Fecha: <span><?php echo $fecha; ?></span></p>
                <p>Tipo: <span><?php echo $tipo; ?></span></p>
                <p>Medico: <span><?php echo $medico; ?></span></p>

                <form method="post" action="">
                    <input type="hidden" name="Id" value="<?php echo $id; ?>">
                    <a href="lista_festivos.php" class="btn_cancel" style="text-decoration: none">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
    </body>
</html>