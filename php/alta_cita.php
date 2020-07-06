<?php include("seguridad.php"); ?>
<?php include "conexion.php"; ?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Fecha']) || empty($_POST['Hora']) || empty($_POST['Duracion']) || empty($_POST['Medico']) || empty($_POST['Paciente']) || empty($_POST['Observaciones']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {
            $fecha = $_POST['Fecha'];
            $hora = $_POST['Hora'];
            $duracion = $_POST['Duracion'];
            $id_medico = $_POST['Medico'];
            $paciente = $_POST['Paciente'];
            $observaciones = $_POST['Observaciones'];

            $nombre = $paciente;
            $partes = explode(' ', $nombre);

            $name = $partes [0];

            $apellidos = $partes [1].' '.$partes [2];

            $query_paciente = mysqli_query($conection, "SELECT Id FROM pacientes WHERE Nombre = '$name' AND Apellidos LIKE '%$apellidos%'");
            $result = mysqli_num_rows($query_paciente);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query_paciente)) {
                    $id_paciente = $data['Id'];
                }
            }

            $query = mysqli_query($conection, "SELECT * FROM cita WHERE Fecha = '$fecha' AND Hora = '$hora' AND Medico = '$id_medico' AND Paciente = '$id_paciente'");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">La cita ya esta reservada para otro paciente para este mismo medico, seleccione otro medico u otra hora</p>';
            } else {
                $query_insert = mysqli_query($conection, "INSERT INTO cita (Fecha, Hora, Duracion, Medico, Paciente, Observaciones)
                                                        VALUES('$fecha', '$hora', '$duracion', '$id_medico', '$id_paciente', '$observaciones')");

                if($query_insert) {
                    $alert = '<p class="msg_save">Cita registrada correctamente</p>';
                    header('Location: cliente.php?resultado=creado');
                } else {
                    $alert = '<p class="msg_error">Error al registrar la cita</p>';
                }
            }
        }
    }

    //Mostrar datos
    if(empty($_GET['Medico'] && $_GET['Fecha'] && $_GET['Hora']))
        header('Location: calendario.php');

    $id_medico = $_GET['Medico'];
    $fecha = $_GET['Fecha'];
    $hora = $_GET['Hora'];

    $query = mysqli_query($conection, "SELECT * FROM usuario WHERE Id = '$id_medico'");
    $result = mysqli_num_rows($query);

    if ($result > 0) {
        while ($data = mysqli_fetch_array($query)) {
            $nombre = $data['Nombre'];
        }
    }

    /*$query = mysqli_query($conection, "SELECT * FROM cita WHERE Id= '$id'");
    $result = mysqli_num_rows($query);
    if($result == 0) {
        header('Location: lista_citas.php');
    } else {
        while ($data = mysqli_fetch_array($query)) {
            $fecha = $data['Fecha'];
            $hora = $data['Hora'];
            $duracion = $data['Duracion'];
            $medico = $data['Medico'];
            $paciente = $data['Paciente'];
            $observaciones = $data['Observaciones'];
        }
    }*/
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
                <h1>Pedir Cita</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="post">
                    <input name="Medico" id="Medico" value="<?php echo $id_medico; ?>" style="visibility: hidden">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha"placeholder="" value="<?php echo $fecha; ?>">
                    <label for="Hora">Hora</label>
                    <input type="time" name="Hora" id="Hora" placeholder="" value="<?php echo $hora; ?>">
                    <label for="Duracion">Duración</label>
                    <input type="number" name="Duracion" id="Duracion" value="20" readonly>
                    <label for="Medico">Medico</label>
                    <input type="text" name="N_Medico" id="Medico"placeholder="" value="<?php echo $nombre; ?>">
                    <label for="Paciente">Paciente</label>
                    <input type="text" name="Paciente" id="Paciente" placeholder="">
                    <label for="Observaciones">Observaciones</label>
                    <textarea rows="8" cols="47" name="Observaciones" id="Observaciones"></textarea>
                    <input type="submit" value="Crear usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>