<?php
include "conexion.php";

if(!empty($_POST)) {
    $id = $_POST['Id'];

    $query_delete = mysqli_query($conection, "DELETE FROM informe WHERE Id = '$id'");

    if($query_delete) {
        header("Location: lista_informes.php");
    } else {
        echo "Error al eliminar";
    }
}

if(empty($_REQUEST['Id'])) {
    header("Location: lista_informes.php");
} else {
    $id = $_REQUEST['Id'];
    $query = mysqli_query($conection, "SELECT i.*, p.Nombre as Nom, p.Apellidos 
                                                FROM informe i 
                                                INNER JOIN pacientes p ON i.Paciente = p.Id
                                                WHERE i.Id='$id'");
    $result = mysqli_num_fields($query);

    if($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $id = $data['Id'];
            $titulo = $data['Titulo'];
            $paciente = $data['Nom'].' '.$data['Apellidos'];
            $fecha = $data['Fecha'];
        }
    } else {
        header("Location: lista_informes.php");
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
                <p>Titulo: <span><?php echo $titulo; ?></span></p>
                <p>Paciente: <span><?php echo $paciente; ?></span></p>
                <p>Fecha: <span><?php echo $fecha; ?></span></p>

                <form method="post" action="">
                    <input type="hidden" name="Id" value="<?php echo $id; ?>">
                    <a href="lista_informes.php" class="btn_cancel" style="text-decoration: none">Cancelar</a>
                    <input type="submit" value="Aceptar" class="btn_ok">
                </form>
            </div>
        </section>
    </body>
</html>