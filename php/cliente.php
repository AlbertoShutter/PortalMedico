<?php
    require_once "../lib/nusoap.php";

    $client = new nusoap_client("http://localhost:63342/Proyecto/php/servidor.php?wsdl");
    $usuarios =  $client->call("cargarUsuario", array());
    $usuarios = json_decode($usuarios);
?>

<!DOCTYPE>
<html>
    <header>
        <link rel="stylesheet" href="../css/date-picker.css" />
        <link rel="stylesheet" href="../css/calendario_citas.css" />
    </header>
    <body>
        <a href="cliente_reg.php" class="btn_new">¿Eres nuevo?</a>
        <form action="cliente2.php">
            <h1>Cliente externo</h1>
            <label for="Fecha">Fecha</label>
            <input type="date" name="Fecha" id="dateofbirth" required>

            <label for="Medico">Medico</label>
            <select name="Medico" id="Medico" required>
                <?php
                    foreach($usuarios as $usuario) {
                        ?>
                        <option value="<?php echo $usuario->Id ?>"><?php echo $usuario->Nombre; ?></option>
                        <?php
                    }
                ?>
            </select>
            <input type="submit">
        </form>
    </body>
</html>



