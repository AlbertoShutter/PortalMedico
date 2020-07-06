<?php include("seguridad.php"); ?>
<?php include "conexion.php";?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Titulo']) || empty($_POST['Medico']) || empty($_POST['Paciente']) || empty($_POST['Fecha']) || empty($_POST['Hora']) || empty($_POST['Contenido']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

            $id_informe = $_POST['Id'];
            $titulo = $_POST['Titulo'];
            $medico = $_POST['Medico'];
            $paciente = $_POST['Paciente'];
            $fecha = $_POST['Fecha'];
            $hora = $_POST['Hora'];
            $contenido = $_POST['Contenido'];

            $nombre = $paciente;
            $partes = explode(' ', $nombre);

            $name = $partes [0];

            $apellidos = $partes [1].' '.$partes [2];

            $query_paciente = mysqli_query($conection, "SELECT Id FROM pacientes WHERE Nombre = '$name' AND Apellidos LIKE '%$apellidos%'");
            $result = mysqli_num_rows($query_paciente);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query_paciente)) {
                    $paciente = $data['Id'];
                }
            }

            $nombre = $medico;
            $partes = explode(' ', $nombre);

            $name = $partes [0];

            $apellidos = $partes [1];

            $query_paciente = mysqli_query($conection, "SELECT Id FROM usuario WHERE Nombre LIKE '%$name%' AND Rol = 2");
            $result = mysqli_num_rows($query_paciente);
            if ($result > 0) {
                while ($data = mysqli_fetch_array($query_paciente)) {
                    $medico = $data['Id'];
                }
            }

            $query = mysqli_query($conection, "SELECT * FROM informe WHERE Titulo='$titulo' AND Medico='$medico' AND Paciente='$paciente' AND Fecha='$fecha' AND Hora='$hora' AND Contenido='$contenido'");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">El informe ya esta registrado en la base de datos</p>';
            } else {
                $query_update = mysqli_query($conection, "UPDATE informe
                                                                    SET Titulo = '$titulo', Medico = '$medico', Paciente = '$paciente', Fecha = '$fecha', 
                                                                    Hora = '$hora', Contenido = '$contenido'
                                                                    WHERE Id = '$id_informe'");
                if ($query_update) {
                    $alert = '<p class="msg_save">Informe registrado correctamente</p>';
                } else {
                    $alert = '<p class="msg_error">Error al actualizar el informe</p>';
                }
            }
        }
    }

    // Mostrar datos
    if(empty($_GET['Id'])) {
        header('Location: lista_informes.php');
    }

    $id_informe = $_GET['Id'];
    $sql = mysqli_query($conection, "SELECT Titulo, (u.Nombre) as Nom_med, (p.Nombre) as Nom_pac, p.Apellidos, Fecha, Hora, Contenido 
                                            FROM informe i 
                                            INNER JOIN usuario u ON i.Medico = u.Id 
                                            INNER JOIN pacientes p ON i.Medico = p.Id 
                                            WHERE i.Id = '$id_informe'");
    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0) {
        header('Location: lista_informes.php');
    } else {
        while ($data = mysqli_fetch_array($sql)) {
            $titulo = $data['Titulo'];
            $medico = $data['Nom_med'];
            $paciente = $data['Nom_pac'].' '.$data['Apellidos'];
            $fecha = $data['Fecha'];
            $hora = $data['Hora'];
            $contenido = $data['Contenido'];
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
                <h1>Registro usuario</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="post" id="inform">
                    <input type="text" name="Id" id="Id" value="<?php echo $id_informe; ?>" style="visibility: hidden">
                    <label for="Titulo">Titulo</label>
                    <input type="text" name="Titulo" id="Titulo"placeholder="" value="<?php echo $titulo; ?>">
                    <label for="Medico">Médico</label>
                    <input type="text" name="Medico" id="Medico" placeholder="" value="<?php echo $medico; ?>">
                    <label for="Paciente">Paciente</label>
                    <input type="text" name="Paciente" id="Paciente" placeholder="" value="<?php echo $paciente; ?>">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha"placeholder="" value="<?php echo $fecha; ?>">
                    <label for="Hora">Hora</label>
                    <input type="time" name="Hora" id="Hora" placeholder="" value="<?php echo $hora; ?>">
                    <label for="Contenido">Contenido</label>
                    <textarea rows="8" cols="47" name="Contenido" id="Contenido"><?php echo $contenido; ?></textarea>
                    <input type="submit" value="Actualizar informe" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>