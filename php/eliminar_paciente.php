<?php
    include "conexion.php";

    if(!empty($_POST)) {
        $dni = $_POST['dni'];

        $query_delete = mysqli_query($conection, "DELETE FROM pacientes WHERE Id = '$dni'");

        if($query_delete) {
            //header("Location: lista_pacientes.php");
            echo("<script>
                    window.alert('El paciente ha sido eliminado correctamente');
                    window.location.href='lista_pacientes.php'
                </script>");
        } else {
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['Id'])) {
        header("Location: lista_pacientes.php");
    } else {
        $dni = $_REQUEST['Id'];
        $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE Id='$dni'");
        $result = mysqli_num_fields($query);

        if($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
                $nombre = $data['Nombre'];
                $apellidos = $data['Apellidos'];
                $documento = $data['Documento'];
            }
        } else {
            header("Location: lista_pacientes.php");
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
                <p>Apellidos: <span><?php echo $apellidos; ?></span></p>
                <p>Documento: <span><?php echo $documento; ?></span></p>

                <form method="post" action="">
                    <input type="hidden" name="dni" value="<?php echo $dni; ?>">
                    <a href="lista_pacientes.php" class="btn_cancel" style="text-decoration: none">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
    </body>
</html>