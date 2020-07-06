<?php

    function validar_dni($dni) {
        if($_POST['Pais'] == 'España') {
            $letra = substr($dni, -1);
            $numero = substr($dni, 0, -1);
            if (substr("TRWAGMYFPDXBNJZSQVHLCKE", $numero % 23, 1) == $letra && strlen($letra) && strlen($numero) == 8) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Nombre']) || empty($_POST['Apellidos']) || empty($_POST['FechaNac']) || empty($_POST['TipoDoc']) || empty($_POST['Documento']) || empty($_POST['Direccion']) || empty($_POST['Pais']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {
            include "conexion.php";

            $nombre = $_POST['Nombre'];
            $apellidos = $_POST['Apellidos'];
            $fechaNac = $_POST['FechaNac'];
            $tipoDoc = $_POST['TipoDoc'];
            $documento = $_POST['Documento'];
            $direccion = $_POST['Direccion'];

            if(isset($_POST['checkbox'])) {
                $id_provincia = $_POST['Provincia'];
                $id_localidad = $_POST['Localidad'];
            } else {
                $id_provincia = $_POST['provinciaList'];
                $id_localidad = $_POST['localidadList'];
            }

            $pais = $_POST['Pais'];


            if(validar_dni($documento) == true) {
                //echo "SELECT * FROM pacientes WHERE Nombre = '$nombre' AND Apellidos = '$apellidos' AND FechaNac = '$fechaNac' AND TipoDoc = '$tipoDoc' AND Documento = '$documento' AND Direccion = '$direccion' AND Provincia = '$provincia' AND Localidad = '$localidad' AND Pais = '$pais' ";
                if($pais == 'España') {
                    $query_localidad = mysqli_query($conection, "SELECT id_municipio FROM municipios WHERE nombre LIKE '%$id_localidad%'");
                    //echo "SELECT id_municipio FROM municipios WHERE nombre LIKE '%$id_localidad%'";
                    $result_localidad = mysqli_num_rows($query_localidad);
                    if ($result_localidad > 0) {
                        while ($data = mysqli_fetch_array($query_localidad)) {
                            $id_localidad = $data['id_municipio'];
                        }
                    }
                }

                $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE Nombre = '$nombre' AND Apellidos = '$apellidos' AND FechaNac = '$fechaNac' AND TipoDoc = '$tipoDoc' AND Documento = '$documento' AND Direccion = '$direccion' AND Provincia = '$id_provincia' AND Localidad = '$id_localidad' AND Pais = '$pais' ");
                $result = mysqli_fetch_array($query);

                if($result > 0) {
                    $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
                } else {
                    /*echo "INSERT INTO pacientes(Nombre, Apellidos, FechaNac, TipoDoc, Documento, Direccion, Provincia, Localidad, Pais)
                                                                    VALUES('$nombre','$apellidos','$fechaNac','$tipoDoc','$documento','$direccion','$id_provincia','$id_localidad','$pais')";*/
                    $query_insert = mysqli_query($conection, "INSERT INTO pacientes(Nombre, Apellidos, FechaNac, TipoDoc, Documento, Direccion, Provincia, Localidad, Pais)
                                                        VALUES('$nombre','$apellidos','$fechaNac','$tipoDoc','$documento','$direccion','$id_provincia','$id_localidad','$pais')");
                    if($query_insert) {
                        echo("<script>
                                window.alert('El paciente se ha registrado correctamente');
                                window.location.href='lista_pacientes.php'
                            </script>");
                    } else {
                        $alert = '<p class="msg_error">Error al registrar al paciente</p>';
                    }
                }
            } else {
                $alert = '<p class="msg_error">DNI incorrecto</p>';
            }
        }
    }
?>

<?php include("seguridad.php"); ?>
<!DOCTYPE html>
<html>
    <header>
        <title>Portal de empleados</title>
        <link rel="stylesheet" href="../css/alta_usuario.css">
        <link rel="stylesheet" type="text/css" href="../css/btn_salir.css" media="screen" />
        <script src="../js/AjaxCode.js"></script>
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
                    <input type="text" name="Nombre" id="Nombre" placeholder="Nombre completo">
                    <label for="Apellidos">Apellidos</label>
                    <input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos">
                    <label for="FechaNac">Fecha de Nacimiento</label>
                    <input type="date" name="FechaNac" id="FechaNac" placeholder="">
                    <label for="TipoDoc">Tipo de Documento</label>
                    <input type="text" name="TipoDoc" id="TipoDoc"placeholder="">
                    <label for="Documento">Documento</label>
                    <input type="text" name="Documento" id="Documento"placeholder="">
                    <label for="checkbox" id="label">No es de España</label>
                    <input type="checkbox" id="checkbox" class="checkbox" name="checkbox" value="" onclick="DoCheckUncheckDisplay(this ,'provinciaList', 'localidadList', 'Provincia', 'Localidad')">
                    <label for="Direccion">Direccion</label>
                    <input type="text" name="Direccion" id="Direccion" placeholder="Direccion">
                    <label for="Provincia">Provincia</label>
                    <input type="text" name="Provincia" id="Provincia">
                    <select name="provinciaList" id="provinciaList" onchange="return provinciaListOnChange()">
                        <option>Selecciona una provincia</option>
                        <?php
                            $xml = simplexml_load_file('../xml/provinciasypoblaciones.xml');
                            $result = $xml->xpath("/lista/provincia/nombre | /lista/provincia/@id");
                            for($i = 0; $i<count($result); $i+=2) {
                                $e = $i + 1;
                                $provincia = UTF8_DECODE($result[$e]);
                                echo("<option value='$result[$i]'>$provincia</option>");
                            }
                        ?>
                    </select>
                    <label for="Localidad">Localidad</label>
                    <input type="text" name="Localidad" id="Localidad">
                    <select name="localidadList" id="localidadList">
                        <option>Selecciona una localidad</option>
                    </select> <span id="advice"> </span>

                    <label for="Pais">Pais</label>
                    <input type="text" name="Pais" id="Pais" placeholder="">
                    <input type="submit" value="Crear usuario" class="btn_save">
                </form>
            </div>
        </section>
        <br/>
    </body>
    <script>
        function DoCheckUncheckDisplay(d,dchecked, ddchecked, dunchecked, ddunchecked)
        {
            if( d.checked == true )
            {
                document.getElementById(dchecked).style.display = "none";
                document.getElementById(ddchecked).style.display = "none";
                document.getElementById(dunchecked).style.display = "block";
                document.getElementById(ddunchecked).style.display = "block";
            }
            else
            {
                document.getElementById(dchecked).style.display = "block";
                document.getElementById(ddchecked).style.display = "block";
                document.getElementById(dunchecked).style.display = "none";
                document.getElementById(ddunchecked).style.display = "none";
            }
        }
    </script>
</html>