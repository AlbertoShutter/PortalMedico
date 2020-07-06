<?php include("seguridad.php"); ?>
<?php include "conexion.php"; ?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if(empty($_POST['Fecha']) || empty($_POST['Tipo']) || empty($_POST['Medico'])) {
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {
            $fecha = $_POST['Fecha'];
            $tipo = $_POST['Tipo'];
            $medico = $_POST['Medico'];

            $query = mysqli_query($conection, "SELECT * FROM festivos WHERE fecha = '$fecha' AND tipo = '$tipo' AND medico = '$medico'");
            $result = mysqli_num_rows($query);
            if($result > 0) {
                $alert = '<p class="msg_error">El festivo ya esta registrado en la base de datos</p>';
            } else {
                $query_medico = mysqli_query($conection, "SELECT Id FROM usuario WHERE Usuario = '$medico'");
                $result_medico = mysqli_num_rows($query_medico);
                if($result_medico > 0) {
                    while($data = mysqli_fetch_array($query_medico)) {
                        $medico = $data['Id'];
                    }
                } else {
                    $medico = 8;
                }

                $query_insert = mysqli_query($conection, "INSERT INTO festivos(fecha, tipo, medico) VALUES('$fecha', '$tipo', '$medico')");
                if($query_insert) {
                    $alert = '<p class="msg_save">Festivo registrado correctamente</p>';
                } else {
                    $alert = '<p class="msg_error">Error al registrar el festivo</p>';
                }
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
        <div class="menu">
            <?php include "menu.php"; ?>
        </div>
        <section id="container">
            <div class="form_register">
                <h1>Registro usuario</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="post">
                    <label for="Fecha">Fecha</label>
                    <input type="date" name="Fecha" id="dateofbirth" required>
                    <label for="Tipo">Tipo</label>
                    <select name="Tipo" id="Rol">
                        <option value="1">Completo</option>
                        <option value="2">Mañana</option>
                        <option value="3">Tarde</option>
                    </select>
                    <label for="Medico">Usuario medico</label>
                    <input name="Medico" id="Medico" placeholder="Escribe todos si afecta a toda la plantilla">
                    <input type="submit" value="Crear usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>