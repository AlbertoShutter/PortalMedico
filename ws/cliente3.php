<?php
    require_once "../lib/nusoap.php";

    $client = new nusoap_client("http://localhost:63342/Proyecto/ws/servidor.php?wsdl");

    if(empty($_GET['Medico'] || $_GET['Fecha'] || $_GET['Hora']))
        header('Location: cliente2.php');

    $id_medico = $_GET['Medico'];
    $fecha = $_GET['Fecha'];
    $hora = $_GET['Hora'];

    $nombre_medico = $client->call("devolverMedico", array("id_medico"=>$id_medico));
    $nombre_medico = json_decode($nombre_medico);
    foreach($nombre_medico as $nm) {
        $nombre = $nm->Nombre;
    }

if(!empty($_POST)) {
    $alert=' ';
    if (empty($_POST['Fecha']) || empty($_POST['Hora']) || empty($_POST['Duracion']) || empty($_POST['Medico']) || empty($_POST['Paciente']) || empty($_POST['Observaciones']) ){
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $paciente = $_POST['Paciente'];
        $observaciones = $_POST['Observaciones'];

        $registrar_cita = $client->call("registrarCita", array("fecha_elegida"=>$fecha,"hora"=>$hora,"duracion"=>'20',"id_medico"=>$id_medico,"id_paciente"=>$paciente,"observaciones"=>$observaciones));
        //$confirmar_cita = json_decode($confirmar_cita);
        if($registrar_cita == 'creado') {
            $alert = '<p class="msg_save">Cita reservada correctamente</p>';
            header('Location: cliente.php?resultado=creado');
        } else {
            $alert = '<p class="msg_error">Error al reservar la cita. Por favor registrese previamente parar poder reservar cita. <a href="cliente_reg.php">Registrarse</a> </p>';
        }
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
        <br/><br/>
        <section id="container">
            <div class="form_register">
                <h1>Pedir Cita</h1>
                <hr>
                <div class="alert">
                    <?php echo isset($alert) ? $alert : ''; ?>
                </div>
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
                    <label for="Paciente">Nombre y Apellidos</label>
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