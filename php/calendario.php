<?php include "conexion.php";?>
<?php include "seguridad.php";?>

<?php
    $alert=' ';
    if(!empty($_GET['alert'])) {
        $alert = '<p class="msg_error">Este médico no esta disponible para esta fecha</p>';
    }

    if(!empty($_GET['resultado']))
        if($_GET['resultado'] == 'creado')
            $alert = '<p class="msg_save">Cita reservada correctamente</p>';
?>

<!DOCTYPE>
<html>
    <header>
        <link rel="stylesheet" href="../css/date-picker.css" />
        <link rel="stylesheet" href="../css/calendario_citas.css" />
        <title>Portal de empleados</title>
    </header>
    <body>
        <div class="menu">
            <?php include "menu.php";?>
        </div>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        <form action="comprobar_cita.php">
            <h1>Gestor de Citas</h1>
            <label for="Fecha">Fecha</label>
            <input type="date" name="Fecha" class="datepicker" id="datepicker" required>

            <?php
                $query_medico = mysqli_query($conection, "SELECT * FROM usuario WHERE Rol=2 ");
                $result_medico = mysqli_num_rows($query_medico);
            ?>

            <label for="Medico">Medico</label>
            <select name="Medico" id="Medico" required>
                <?php
                    if($result_medico > 0) {
                        while($medico = mysqli_fetch_array($query_medico)) {
                            ?>
                            <option value="<?php echo $medico['Id']; ?>"><?php echo $medico['Nombre']; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
            <input type="submit">
        </form>
        <div class="alert">
            <?php echo isset($alert) ? $alert : ''; ?>
        </div>
    </body>
</html>