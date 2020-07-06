<?php include("seguridad.php"); ?>
<?php include "conexion.php";?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Titulo']) || empty($_POST['Medico']) || empty($_POST['Paciente']) || empty($_POST['Fecha']) || empty($_POST['Hora']) || empty($_POST['Contenido']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

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

            $query_insert = mysqli_query($conection, "INSERT INTO informe(Titulo, Medico, Paciente, Fecha, Hora, Contenido)
                                                             VALUES('$titulo','$medico','$paciente','$fecha','$hora','$contenido')");

            if($query_insert) {
                $alert = '<p class="msg_save">Usuario registrado correctamente</p>';
            } else {
                $alert = '<p class="msg_error">Error al registrar el usuario</p>';
            }
        }
    }

     // Mostrar datos
    if(empty($_GET['Id'])) {
        header('Location: lista_pacientes.php');
    }

    $dni = $_GET['Id'];
    $sql = mysqli_query($conection, "SELECT Nombre, Apellidos FROM pacientes WHERE Id = '$dni'");
    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0) {
        header('Location: lista_pacientes.php');
    } else {
        while ($data = mysqli_fetch_array($sql)) {
            $nombre = $data['Nombre'];
            $apellidos = $data['Apellidos'];
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
            <?php include "menu.php"; ?>
        </div>
        <section id="container">
            <div class="form_register">
                <h1>Registro usuario</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="post" id="inform">
                    <label for="Titulo">Titulo</label>
                    <input type="text" name="Titulo" id="Titulo"placeholder="">
                    <label for="Medico">Médico</label>
                    <input type="text" name="Medico" id="Medico" placeholder="">
                    <label for="Paciente">Paciente</label>
                    <input type="text" name="Paciente" id="Paciente" placeholder="" value="<?php echo $nombre.' '.$apellidos; ?>">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="Fecha"placeholder="">
                    <label for="Hora">Hora</label>
                    <input type="time" name="Hora" id="Hora" placeholder="">
                    <label for="Contenido">Contenido</label>
                    <textarea rows="8" cols="47" name="Contenido" id="Contenido"></textarea>
                    <input type="submit" value="Crear usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>