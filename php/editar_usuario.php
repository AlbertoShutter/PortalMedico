<?php include("seguridad.php"); ?>
<?php include "conexion.php";?>

<?php
    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Nombre']) || empty($_POST['Usuario']) || empty($_POST['Rol']) || empty($_POST['Especialidad']) || empty($_POST['Correo'])/* || empty($_POST['FechaAlta']) || empty($_POST['FechaBaja']) || empty($_POST['Estado'])*/ ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

            $nombre = $_POST['Nombre'];
            $usuario = $_POST['Usuario'];
            $clave = $_POST['Clave'];
            $rol = $_POST['Rol'];
            $especialidad = $_POST['Especialidad'];
            $correo = $_POST['Correo'];
            $fechaAlta = $_POST['FechaAlta'];
            $fechaBaja = $_POST['FechaBaja'];
            $estado = $_POST['Estado'];

            //echo "SELECT * FROM usuario WHERE Nombre = '$nombre' OR Usuario = '$usuario' ";
            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE Nombre = '$nombre' AND Usuario = '$usuario' AND Rol = '$rol' AND Especialidad = '$especialidad' AND Correo = '$correo'
                                                      AND FechaAlta = '$fechaAlta' AND FechaBaja = '$fechaBaja' AND Estado = '$estado'");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
            } else {
                if(empty($_POST['Clave'])) {
                    $sql_update = mysqli_query($conection, "UPDATE usuario SET Nombre = '$nombre', Usuario = '$usuario', Rol = '$rol', Especialidad = '$especialidad', Correo = '$correo',
                                                                   FechaAlta = '$fechaAlta', FechaBaja = '$fechaBaja', Estado = '$estado' WHERE Nombre = '$nombre' OR Usuario = '$usuario'");
                } else {
                    $sql_update = mysqli_query($conection, "UPDATE usuario SET Nombre = '$nombre', Usuario = '$usuario', Clave = '$clave', Rol = '$rol', Especialidad = '$especialidad', 
                                                                   Correo = '$correo', FechaAlta = '$fechaAlta', FechaBaja = '$fechaBaja', Estado = '$estado' WHERE Nombre = '$nombre' OR Usuario = '$usuario'");
                }

                if($sql_update) {
                    $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
                    header('Location: lista_usuarios.php');
                } else {
                    $alert = '<p class="msg_error">Error al actualizar el usuario</p>';
                }
            }
        }
    }

    // Mostrar datos
    if(empty($_GET['Id'])) {
        header('Location: lista_usuarios.php');
    }

    $iduser = $_GET['Id'];
    $sql = mysqli_query($conection, "SELECT u.Id, u.Nombre, u.Clave, u.Usuario, r.Codigo, r.Rol, u.Especialidad, u.Correo, u.FechaAlta, u.FechaBaja, u.Estado 
                                            FROM usuario u INNER JOIN rol r ON u.Rol = r.Codigo WHERE u.Id='$iduser'");
    $result_sql = mysqli_num_rows($sql);
    if($result_sql == 0) {
        header('Location: lista_usuario.php');
    } else {
        $option = '';
        while($data = mysqli_fetch_array($sql)) {
            $nombre = $data['Nombre'];
            $usuario = $data['Usuario'];
            $clave = $data['Clave'];
            $codigo = $data['Codigo'];
            $rol = $data['Rol'];
            $especialidad = $data['Especialidad'];
            $correo = $data['Correo'];
            $fechaAlta = $data['FechaAlta'];
            $fechaBaja = $data['FechaBaja'];
            $estado = $data['Estado'];

            if($codigo == 1) {
                $option = '<option value="'.$codigo.'" selected>'.$rol.'</option>';
            } else if($codigo == 2) {
                $option = '<option value="'.$codigo.'" selected>'.$rol.'</option>';
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
            <?php include "menu.php";?>
        </div>
        <section id="container">
            <div class="form_register">
                <h1>Actualizar usuario</h1>
                <hr>
                <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
                <form action="" method="post">
                    <label for="Nombre">Nombre</label>
                    <input type="text" name="Nombre" id="Nombre"placeholder="Nombre completo" value="<?php echo $nombre; ?>">
                    <label for="Usuario">Usuario</label>
                    <input type="text" name="Usuario" id="Usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">
                    <label for="Clave">Contraseña</label>
                    <input type="text" name="Clave" id="Clave" placeholder="" value="<?php echo $clave; ?>">
                    <label for="Rol">Rol</label>

                    <?php
                        $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                        $result_rol = mysqli_num_rows($query_rol);
                    ?>

                    <select name="Rol" id="Rol" class="noItemOne">
                        <?php
                            echo $option;
                            if($result_rol > 0) {
                                while($rol = mysqli_fetch_array($query_rol)) {
                                    ?>
                                    <option value="<?php $rol["Codigo"]; ?>"><?php echo $rol["Rol"] ?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    <label for="Especialidad">Especialidad</label>
                    <input type="text" name="Especialidad" id="Especialidad"placeholder="" value="<?php echo $especialidad; ?>">
                    <label for="Correo">Correo</label>
                    <input type="email" name="Correo" id="Correo" placeholder="Correo" value="<?php echo $correo; ?>">
                    <label for="FechaAlta">Fecha Alta</label>
                    <input type="date" name="FechaAlta" id="FechaAlta" placeholder="" value="<?php echo $fechaAlta; ?>">
                    <label for="FechaBaja">Fecha Baja</label>
                    <input type="date" name="FechaBaja" id="FechaBaja" placeholder="" value="<?php echo $fechaBaja; ?>">
                    <label for="Estado">Estado</label>
                    <input type="text" name="Estado" id="Estado" placeholder="" value="<?php echo $estado; ?>">
                    <input type="submit" value="Actualizar usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>