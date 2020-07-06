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

    require_once "../lib/nusoap.php";
    $client = new nusoap_client("http://localhost:63342/Proyecto/ws/servidor.php?wsdl");

    if(!empty($_POST)) {
        $alert=' ';
        if (empty($_POST['Nombre']) || empty($_POST['Apellidos']) || empty($_POST['FechaNac']) || empty($_POST['TipoDoc']) || empty($_POST['Documento']) || empty($_POST['Direccion']) || empty($_POST['Pais']) ){
            $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
        } else {

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
                if($pais == 'España') {
                    $municipio = $client->call("devolverLocalidad", array('localidad'=>$id_localidad));
                    $municipio = json_decode($municipio);
                    foreach ($municipio as $mp) {
                         $id_localidad = $mp->id_municipio;
                    }
                }

                $status = $client->call("registrarUsuario", array('nombre'=>$nombre,'apellidos'=>$apellidos,'fechaNac'=>$fechaNac,'tipoDoc'=>$tipoDoc,'documento'=>$documento,
                                                                    'direccion'=>$direccion,'id_provincia'=>$id_provincia,'id_localidad'=>$id_localidad,'pais'=>$pais));
                if($status == 'existe') {
                    $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
                } else if($status == 'error') {
                    $alert = '<p class="msg_error">Error al registrar al paciente</p>';
                } else if($status == 'creado') {
                    $alert = '<p class="msg_save">Registrado correctamente</p>';
                    header('Location: cliente.php?resultado=registrado');
                }
            } else {
                $alert = '<p class="msg_error">DNI incorrecto</p>';
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
        <script src="../js/AjaxCode.js"></script>
    </header>
    <body>
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
                    <input type="submit" value="Registrar" class="btn_save">
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