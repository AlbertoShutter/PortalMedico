<?php include("seguridad.php"); ?>
<!DOCTYPE html>
<html>
    <header>
        <title>Portal de empleados</title>
        <link rel="stylesheet" type="text/css" href="../css/index.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/btn_salir.css" media="screen" />
    </header>
    <body>
        <div class="menu">
            <?php include "menu.php"; ?>
            <?php include "btn_salir.php"; ?>
            <div class="info">
                <h1>Bienvenido al sistema <?php echo $_SESSION["usuarioactual"]; ?>!</h1><br>
                <p>Entró correctamente al sistema.</p><br><br>
            </div>
        </div>
    </body>
</html>