<?php
    /* A continuación, realizamos la conexión con nuestra base de datos en MySQL */
    $mysqli = new mysqli('localhost', 'practica', 'practica', 'proyecto');
    $mysqli->set_charset("utf8");

    /* El query valida si el usuario ingresado existe en la base de datos. Se utiliza la función
    htmlentities para evitar inyecciones SQL. */
    $usuario = $mysqli->query("select Usuario from usuario where Usuario = '".htmlentities($_POST["usuario"])."'");

    //Si existe el usuario, validamos también la contraseña ingresada y el estado del usuario...
    if($usuario->num_rows>0){
        $sql = "select Usuario from usuario where Estado = 1 and Usuario = '".htmlentities($_POST["usuario"])."' and Clave = '".htmlentities($_POST["clave"])."'";
        $clave = $mysqli->query($sql);

        //Si el usuario y clave ingresado son correctos (y el usuario está activo en la BD), creamos la sesión del mismo.
        if($clave->num_rows>0){
            if ($row = $clave->fetch_assoc()) {
                session_start();
                //Guardamos dos variables de sesión que nos informará si está o no "logueado" un usuario
                $_SESSION["autentica"] = "SIP";
                $_SESSION["usuarioactual"] = $_POST["usuario"];
                //echo "usuario:".$row["usuario"];
                //nombre del usuario logueado.
                //Direccionamos a nuestra página principal del sistema.
                header ("Location: index.php");
            }
        }
        else{
            echo"<script>alert('La contraseña del usuario no es correcta.');
                   window.location.href=\"login.php\"</script>";
        }
    }else{
        echo"<script>alert('El usuario no existe.');window.location.href=\"login.php\"</script>";
    }
    $usuario->free();
    //$clave->free();
    $mysqli->close();
?>