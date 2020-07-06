<?php
    $host = '127.0.0.1';
    $user = 'practica';
    $password = 'practica';
    $dbname = 'proyecto';

    $conection = new mysqli($host, $user, $password, $dbname);

    if(!$conection) {
        echo "Error en la conexión";
    }
?>
