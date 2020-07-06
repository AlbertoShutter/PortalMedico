<?php
    require_once "../lib/nusoap.php";

    $server = new soap_server();
    $server->configureWSDL("mi primer ws","urn:portalmedico");

    if(!isset($HTTP_RAW_POST_DATA)) {
        $HTTP_RAW_POST_DATA = file_get_contents("php://input");
    }

    function cargarUsuario() {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $usuarios = $conection->query("SELECT Id, Nombre FROM usuario WHERE Rol=2");
        $arrUsuarios = [];
        while($usuario = mysqli_fetch_array($usuarios, MYSQLI_ASSOC)) {
            $arrUsuarios[] = $usuario;
        }
        return json_encode($arrUsuarios);
    }

    $server->register('cargarUsuario', array(),
                                                array("return"=>"xsd:string"),
                                                "urn:portalmedico",
                                                "urn:portalmedico#cargarUsuario",
                                                "rpc",
                                                "encoded",
                                                "Cargar todos los usuarios");

    function devolverCalendario($id_medico) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $calendario = $conection->query("SELECT * FROM calendario WHERE Medico='$id_medico'");
        $arrCalen = [];
        while($cita = mysqli_fetch_array($calendario, MYSQLI_ASSOC)) {
            $arrCalen[] = $cita;
        }
        return json_encode($arrCalen);
    }

    $server->register('devolverCalendario', array("id_medico"=>"xsd:string"),
                                                    array("return"=>"xsd:string"),
                                                    "urn:portalmedico",
                                                    "urn:portalmedico#devolverCalendario",
                                                    "rpc",
                                                    "encoded",
                                                    "Horario del medico");

    function devolverCitas($id_medico, $fecha_elegida) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $citas = $conection->query("SELECT Hora FROM cita WHERE Medico='$id_medico' AND Fecha='$fecha_elegida' ORDER BY Hora");
        $arrCitas = [];
        while($cita = mysqli_fetch_array($citas, MYSQLI_ASSOC)) {
            $arrCitas[] = $cita;
        }
        return json_encode($arrCitas);
    }

    $server->register('devolverCitas', array("id_medico"=>"xsd:string","fecha_elegida"=>"xsd:string"),
                                                array("return"=>"xsd:string"),
                                                "urn:portalmedico",
                                                "urn:portalmedico#devolverCitas",
                                                "rpc",
                                                "encoded",
                                                "Citas concertadas");

    function devolverMedico($id_medico) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $nombre = $conection->query("SELECT Nombre FROM usuario WHERE Id='$id_medico'");
        $nom_usu = [];
        while($name = mysqli_fetch_array($nombre,MYSQLI_ASSOC)) {
            $nom_usu[] = $name;
        }
        return json_encode($nom_usu);
    }

    $server->register('devolverMedico', array("id_medico"=>"xsd:string"),
                                                array("return"=>"xsd:string"),
                                                "urn:portalmedico",
                                                "urn:portalmedico#devolverMedico",
                                                "rpc",
                                                "encoded",
                                                "Nombre del medico");

    function devolverPaciente($name_paciente) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');

        $nombre = $name_paciente;
        $partes = explode(' ', $nombre);

        $name = $partes [0];
        $apellidos = $partes [1] . ' ' . $partes [2];

        //$id = $conection->query("SELECT Id FROM pacientes WHERE Nombre = '$name' AND Apellidos LIKE '%$apellidos%'");
        $id = mysqli_query($conection, "SELECT Id FROM pacientes WHERE Nombre = '$name' AND Apellidos LIKE '%$apellidos%'");
        $result_id = mysqli_num_rows($id);
        if($result_id > 0) {
            while ($idp = mysqli_fetch_array($id)) {
                $id_paciente = $idp['Id'];
            }
            return $id_paciente;
        } else {
            /*$crear_paciente = mysqli_query($conection, "INSERT INTO pacientes (Nombre, Apellidos, FechaNac, TipoDoc, Documento, Direccion, Provincia, Localidad, Pais)
                                                                        VALUES ('$name_paciente', '$apellidos', '', '', '', '', '', '', '')");
            $id = mysqli_query($conection, "SELECT Id FROM pacientes WHERE Nombre LIKE '%$name%' AND Apellidos LIKE '%$apellidos%'");
            while ($idp = mysqli_fetch_array($id)) {
                $id_paciente = $idp['Id'];
            }*/
            $id_paciente = 'error';
            return $id_paciente;
        }
    }

    function registrarCita($fecha_elegida, $hora, $duracion, $id_medico, $paciente, $observaciones) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');

        $id_paciente = devolverPaciente($paciente);

        if($id_paciente == 'error') {
            $devolver = 'error';
        } else {
            $crear_cita = $conection->query("INSERT INTO cita (Fecha, Hora, Duracion, Medico, Paciente, Observaciones)
                                                        VALUES('$fecha_elegida', '$hora', '$duracion', '$id_medico', '$id_paciente', '$observaciones')");
            if ($crear_cita) {
                $devolver = "creado";
            } else {
                $devolver = "error";
            }
        }
        return $devolver;
    }

    $server->register('registrarCita', array("fecha_elegida"=>"xsd:string","hora"=>"xsd:string","duracion"=>"xsd:string","id_medico"=>"xsd:string","id_paciente"=>"xsd:string","observaciones"=>"xsd:string"),
                                                array("return"=>"xsd:string"),
                                                "urn:portalmedico",
                                                "urn:portalmedico#registrarCita",
                                                "rpc",
                                                "encoded",
                                                "Registrar cita");

    function devolverLocalidad($localidad) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $query_localidad = mysqli_query($conection, "SELECT id_municipio FROM municipios WHERE nombre LIKE '%$localidad%'");
        $localidad = [];
        while($qlocalidad = mysqli_fetch_array($query_localidad,MYSQLI_ASSOC)) {
            $localidad[] = $qlocalidad;
        }
        return json_encode($localidad);
    }

    $server->register('devolverLocalidad', array("localidad"=>"xsd:string"),
                                                    array("return"=>"xsd:string"),
                                                    "urn:portalmedico",
                                                    "urn:portalmedico#devolverLocalidad",
                                                    "rpc",
                                                    "Devolver localidad");

    function registrarUsuario($nombre, $apellidos, $fechaNac, $tipoDoc, $documento, $direccion, $id_provincia, $id_localidad, $pais) {
        $conection = mysqli_connect('127.0.0.1', 'root', 'root', 'proyecto');
        $query = mysqli_query($conection, "SELECT * FROM pacientes WHERE Nombre = '$nombre' AND 
                                                                            Apellidos = '$apellidos' AND 
                                                                            FechaNac = '$fechaNac' AND 
                                                                            TipoDoc = '$tipoDoc' AND 
                                                                            Documento = '$documento' AND 
                                                                            Direccion = '$direccion' AND 
                                                                            Provincia = '$id_provincia' AND 
                                                                            Localidad = '$id_localidad' AND 
                                                                            Pais = '$pais' ");
        $result = mysqli_fetch_array($query);

        if($result > 0) {
            $resultado = 'existe';
        } else {
            $query_insert = mysqli_query($conection, "INSERT INTO pacientes(Nombre, Apellidos, FechaNac, TipoDoc, Documento, Direccion, Provincia, Localidad, Pais)
                                                            VALUES('$nombre','$apellidos','$fechaNac','$tipoDoc','$documento','$direccion','$id_provincia','$id_localidad','$pais')");
            if($query_insert) {
                $resultado = 'creado';
            } else {
                $resultado = 'error';
            }
        }
        return $resultado;
    }

    $server->register('registrarUsuario', array("nombre"=>"xsd:string","apellidos"=>"xsd:string","fechaNac"=>"xsd:string","tipoDoc"=>"xsd:string","documento"=>"xsd:string","direccion"=>"xsd:string","id_provincia"=>"xsd:string","id_localidad"=>"xsd:string","pais"=>"xsd:string"),
                                                    array("return"=>"xsd:string"),
                                                    "urn:portalmedico",
                                                    "urn:portalmedico#registrarUsuario",
                                                    "rpc",
                                                    "Devuelve paciente");

    $server->service($HTTP_RAW_POST_DATA);
?>