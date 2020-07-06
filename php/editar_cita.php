<?php include("seguridad.php"); ?>
<?php include "conexion.php";?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Fecha']) || empty($_POST['Hora']) || empty($_POST['Duracion']) || empty($_POST['Medico']) || empty($_POST['Paciente']) || empty($_POST['Observaciones']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

            $id = $_POST['Id'];
            $fecha = $_POST['Fecha'];
            $hora = $_POST['Hora'];
            $duracion = $_POST['Duracion'];
            $medico = $_POST['Medico'];
            $paciente = $_POST['Paciente'];
            $observaciones = $_POST['Observaciones'];

            $query = mysqli_query($conection, "SELECT * FROM cita WHERE Fecha = '$fecha' AND Hora = '$hora' AND Duracion = '$duracion' 
                                                            AND Medico = '$medico' AND Paciente = '$paciente' AND Observaciones = '$observaciones' ");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">La cita ya esta registrada en la base de datos</p>';
            } else {
                $query_update = mysqli_query($conection, "UPDATE cita
                                                                    SET Fecha = '$fecha', Hora = '$hora', Duracion = $duracion, Medico = '$medico', 
                                                                    Paciente = '$paciente', Observaciones = '$observaciones' WHERE Id = '$id'");

                if($query_update) {
                    $alert = '<p class="msg_save">Cita actualizada correctamente</p>';
                    header('Location: lista_citas.php');
                } else {
                    $alert = '<p class="msg_error">Error al actualizar la cita</p>';
                }
            }
        }
    }

    //Mostrar datos
    if(empty($_GET['Identificador']))
        header('Location: lista_citas.php');

    $id = $_GET['Identificador'];
    $query = mysqli_query($conection, "SELECT c.Id, (u.Nombre) as Nom_med, (p.Nombre) as Nom_pac, p.Apellidos, c.Fecha, c.Hora, c.Duracion, c.Observaciones 
                                                FROM cita c 
                                                INNER JOIN usuario u ON c.Medico = u.Id 
                                                INNER JOIN pacientes p ON p.Id = c.Paciente 
                                                WHERE c.Id = '$id'");
    $result = mysqli_num_rows($query);
    if($result == 0) {
        header('Location: lista_citas.php');
    } else {
        while ($data = mysqli_fetch_array($query)) {
            $fecha = $data['Fecha'];
            $hora = $data['Hora'];
            $duracion = $data['Duracion'];
            $medico = $data['Nom_med'];
            $paciente = $data['Nom_pac'].' '.$data['Apellidos'];
            $observaciones = $data['Observaciones'];
        }
    }
?>

<!DOCTYPE html>
<html>
    <header>
        <title>Portal de empleados</title>
        <link rel="stylesheet" href="../css/alta_usuario.css">
        <link rel="stylesheet" type="text/css" href="../css/btn_salir.css" media="screen" />
    </header>
    <body>
        <div class="menu">
            <?php include "menu.php";?>
        </div>
            <section id="container">
                <div class="form_register">
                    <h1>Modificar Cita</h1>
                    <hr>
                    <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                    <form action="" method="post">
                        <input type="text" name="Id" id="Id" value="<?php echo $id; ?>" style="visibility: hidden">
                        <label for="Fecha">Fecha</label>
                        <input type="date" name="Fecha" id="Fecha"placeholder="" value="<?php echo $fecha; ?>">
                        <label for="Hora">Hora</label>
                        <input type="time" name="Hora" id="Hora" placeholder="" value="<?php echo $hora; ?>">
                        <label for="Duracion">Duración</label>
                        <input type="number" name="Duracion" id="Duracion" placeholder="" value="<?php echo $duracion; ?>">
                        <label for="Medico">Medico</label>
                        <input type="text" name="Medico" id="Medico"placeholder="" value="<?php echo $medico; ?>">
                        <label for="Paciente">Paciente</label>
                        <input type="text" name="Paciente" id="Paciente" placeholder="" value="<?php echo $paciente; ?>">
                        <label for="Observaciones">Observaciones</label>
                        <textarea rows="8" cols="47" name="Observaciones" id="Observaciones"><?php echo $observaciones; ?></textarea>
                        <input type="submit" value="Actualizar cita" class="btn_save">
                    </form>
                </div>
            </section>
        <br/>
    </body>
</html>