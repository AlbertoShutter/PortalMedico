<?php

include "conexion.php";

    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Nombre']) || empty($_POST['Apellidos']) || empty($_POST['FechaNac']) || empty($_POST['TipoDoc']) || empty($_POST['Documento']) || empty($_POST['Direccion']) || empty($_POST['Provincia']) || empty($_POST['Localidad']) || empty($_POST['Pais']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

            $nombre = $_POST['Nombre'];
            $apellidos = $_POST['Apellidos'];
            $fechaNac = $_POST['FechaNac'];
            $tipoDoc = $_POST['TipoDoc'];
            $documento = $_POST['Documento'];
            $direccion = $_POST['Direccion'];
            $provincia = $_POST['Provincia'];
            $localidad = $_POST['Localidad'];
            $pais = $_POST['Pais'];

            $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE Nombre = '$nombre' AND Apellidos = '$apellidos' AND FechaNac = '$fechaNac' 
                                                        AND TipoDoc = '$tipoDoc' AND Documento = '$documento' AND Direccion = '$direccion' AND Provincia = '$provincia' 
                                                        AND Localidad = '$localidad' AND Pais = '$pais' ");
            $result = mysqli_fetch_array($query);

            if($result > 0) {
                $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
            } else {
                $query_update = mysqli_query($conection, "UPDATE pacientes
                                                                SET Nombre = '$nombre', Apellidos = '$apellidos', FechaNac = $fechaNac, TipoDoc = '$tipoDoc', 
                                                                Direccion = '$direccion', Provincia = '$provincia', Localidad = '$localidad', Pais = '$pais'
                                                                WHERE Documento = '$documento'");

                if($query_update) {
                    $alert = '<p class="msg_save">Paciente actualizado correctamente</p>';
                    header('Location: lista_pacientes.php');
                } else {
                    $alert = '<p class="msg_error">Error al actualizar al paciente</p>';
                }
            }
        }
    }

    //Mostrar datos
    if(empty($_GET['Id']))
        header('Location: lista_pacientes.php');

    $id_pac = $_GET['Id'];
    $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE Id= '$id_pac'");
    $result = mysqli_num_rows($query);
    if($result == 0) {
        header('Location: lista_pacientes.php');
    } else {
        while ($data = mysqli_fetch_array($query)) {
            $nombre = $data['Nombre'];
            $apellidos = $data['Apellidos'];
            $fechaNac = $data['FechaNac'];
            $tipoDoc = $data['TipoDoc'];
            $documento = $data['Documento'];
            $direccion = $data['Direccion'];
            $provincia = $data['Provincia'];
            $localidad = $data['Localidad'];
            $pais = $data['Pais'];
        }
    }

    if($pais == 'España') {
        $query_geo = mysqli_query($conection, "SELECT p.provincia, m.Nombre FROM provincias p, municipios m WHERE p.id_provincia='$provincia' AND m.id_municipio='$localidad'");
        $result = mysqli_num_rows($query);
        while($dat = mysqli_fetch_array($query_geo)) {
            $provincia = $dat['provincia'];
            $localidad = $dat['Nombre'];
        }
    }
?>

<?php include("seguridad.php"); ?>
<!DOCTYPE html>
<html>
    <header>
        <title>Portal de empleados</title>
        <link rel="stylesheet" href="../css/editar_paciente.css">
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
                    <label for="Apellidos">Apellidos</label>
                    <input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>">
                    <label for="FechaNac">Fecha de Nacimiento</label>
                    <input type="date" name="FechaNac" id="FechaNac" placeholder="" value="<?php echo $fechaNac; ?>">
                    <label for="TipoDoc">Tipo de Documento</label>
                    <input type="text" name="TipoDoc" id="TipoDoc"placeholder="" value="<?php echo $tipoDoc; ?>">
                    <label for="Documento">Documento</label>
                    <input type="text" name="Documento" id="Documento"value="<?php echo $documento; ?>" readonly>
                    <label for="Direccion">Direccion</label>
                    <input type="text" name="Direccion" id="Direccion" placeholder="Direccion" value="<?php echo $direccion; ?>">
                    <label for="Provincia">Provincia</label>
                    <input type="text" name="Provincia" id="Provincia" placeholder="--Seleccionar--" value="<?php echo $provincia; ?>">
                    <label for="Localidad">Localidad</label>
                    <input type="text" name="Localidad" id="Localidad" placeholder="--Seleccionar--" value="<?php echo $localidad; ?>">
                    <label for="Pais">Pais</label>
                    <input type="text" name="Pais" id="Pais" placeholder="" value="<?php echo $pais; ?>">
                    <input type="submit" value="Actualizar usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
</html>