<?php
    include "conexion.php";

    if(!empty($_POST)) {
        $id = $_POST['Id'];

        $query_delete = mysqli_query($conection, "DELETE FROM cita WHERE Id = '$id'");

        if($query_delete) {
            header("Location: lista_citas.php");
        } else {
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['Identificador'])) {
        header("Location: lista_citas.php");
    } else {
        $id = $_REQUEST['Identificador'];
        $query = mysqli_query($conection, "SELECT * FROM cita WHERE Id='$id'");
        $result = mysqli_num_fields($query);

        if($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
                $paciente = $data['Paciente'];
                $medico = $data['Medico'];
                $fecha = $data['Fecha'];
                $hora = $data['Hora'];
            }
        } else {
            header("Location: lista_citas.php");
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
                <p>Paciente: <span><?php echo $paciente; ?></span></p>
                <p>Medico: <span><?php echo $medico; ?></span></p>
                <p>Fecha: <span><?php echo $fecha; ?></span> a las <span><?php echo $hora; ?></span></p>

                <form method="post" action="">
                    <input type="hidden" name="Id" value="<?php echo $id; ?>">
                    <a href="lista_citas.php" class="btn_cancel" style="text-decoration: none">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
    </body>
</html>