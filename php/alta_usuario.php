<?php include("seguridad.php"); ?>
<?php include "conexion.php"; ?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Nombre']) || empty($_POST['Usuario']) || empty($_POST['Clave']) || empty($_POST['Rol']) || empty($_POST['Especialidad']) || empty($_POST['Correo'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

            $nombre = $_POST['Nombre'];
            $usuario = $_POST['Usuario'];
            $clave = $_POST['Clave'];
            $rol = $_POST['Rol'];
            $especialidad = $_POST['Especialidad'];
            $correo = $_POST['Correo'];
            /*$fechaAlta = $_POST['FechaAlta'];
            $fechaBaja = $_POST['FechaBaja'];
            $estado = $_POST['Estado'];*/

            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE Nombre = '$nombre' OR Usuario = '$usuario' ");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
            } else {
                $query_insert = mysqli_query($conection, "INSERT INTO usuario(Nombre, Usuario, Clave, Rol, Especialidad, Correo)
                                                    VALUES('$nombre','$usuario','$clave','$rol','$especialidad','$correo')");
                if($query_insert) {
                    /*$alert = '<p class="msg_save">Usuario registrado correctamente</p>';
                    header('Location: lista_usuarios.php');*/
                    echo("<script>
                                window.alert('El usuario se ha registrado correctamente');
                                window.location.href='lista_usuarios.php';
                            </script>");
                } else {
                    $alert = '<p class="msg_error">Error al registrar el usuario</p>';
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
                    <label for="Nombre">Nombre</label>
                    <input type="text" name="Nombre" id="Nombre"placeholder="Nombre completo">
                    <label for="Usuario">Usuario</label>
                    <input type="text" name="Usuario" id="Usuario" placeholder="Usuario">
                    <label for="Clave">Contraseña</label>
                    <input type="text" name="Clave" id="Clave" placeholder="">
                    <label for="Rol">Rol</label>

                    <?php
                        $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                        $result_rol = mysqli_num_rows($query_rol);
                    ?>

                    <select name="Rol" id="Rol">
                        <?php
                            if($result_rol > 0) {
                                while($rol = mysqli_fetch_array($query_rol)) {
                                    ?>
                                    <option value="<?php echo $rol['Codigo']; ?>"><?php echo $rol['Rol']; ?></option>
                                <?php
                                }
                            }
                        ?>
                    </select>
                    <label for="Especialidad">Especialidad</label>
                    <input type="text" name="Especialidad" id="Especialidad"placeholder="">
                    <label for="Correo">Correo</label>
                    <input type="email" name="Correo" id="Correo" placeholder="Correo">
                    <!--<label for="FechaAlta">Fecha Alta</label>
                    <input type="date" name="FechaAlta" id="FechaAlta" placeholder="">
                    <label for="FechaBaja">Fecha Baja</label>
                    <input type="date" name="FechaBaja" id="FechaBaja" placeholder="">
                    <label for="Estado">Estado</label>
                    <input type="text" name="Estado" id="Estado" placeholder="">-->
                    <input type="submit" value="Crear usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>