<?php include("seguridad.php"); ?>
<?php include "conexion.php";?>

<?php
if(!empty($_POST)) {
    $alert=' ';
    if (empty($_POST['him']) || empty($_POST['hfm']) || empty($_POST['hit']) || empty($_POST['hft'])){
        $alert='<p class="msg_error">Todos los campos son obligatorios</p>';
    } else {
        $iduser = $_POST['iduser'];
        $him = $_POST['him'];
        $hfm = $_POST['hfm'];
        $hit = $_POST['hit'];
        $hft = $_POST['hft'];
        $sh = $_POST['sh'];
        $dh = $_POST['dh'];

        $query = mysqli_query($conection, "SELECT * FROM calendario WHERE hora_inicio_ma = '$him' AND hora_fin_ma = '$hfm' AND hora_inicio_tard = '$hit' AND hora_fin_tard = '$hft' AND sabado_h = '$sh'
                                                  AND domingo_h = '$dh'");
        $result = mysqli_fetch_array($query);

        if($result > 0) {
            $alert = '<p class="msg_error">El paciente ya esta registrado en la base de datos</p>';
        } else {
            $sql_update = mysqli_query($conection, "UPDATE calendario SET hora_inicio_ma = '$him', hora_fin_ma = '$hfm', hora_inicio_tard = '$hit', hora_fin_tard = '$hft', sabado_h = '$sh',
                                                           domingo_h = '$dh'  WHERE medico = '$iduser'");
            /*echo "UPDATE calendario SET hora_inicio_ma = '$him', hora_fin_ma = '$hfm', hora_inicio_tard = '$hit', hora_fin_tard = '$hft', sabado_h = '$sh',
                                                           domingo_h = '$dh' WHERE medico = '$iduser'";*/
            if($sql_update) {
                $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
            } else {
                $sql_insert = mysqli_query($conection, "INSERT INTO calendario (medico, hora_inicio_ma, hora_fin_ma, hora_inicio_tard, hora_fin_tard, sabado_h, domingo_h) 
                                                                VALUES ('$iduser','$him','$hfm','$hit','$hft','$sh','$dh')");
               if($sql_insert) {
                   $alert = '<p class="msg_save">Usuario actualizado correctamente</p>';
               } else {
                   $alert = '<p class="msg_error">Error al actualizar el usuario</p>';
               }
            }
        }
    }
}

// Mostrar datos
if(empty($_GET['Id'])) {
    header('Location: lista_usuario.php');
}

$iduser = $_GET['Id'];
$sql = mysqli_query($conection, "SELECT * FROM calendario WHERE medico='$iduser'");
$result_sql = mysqli_num_rows($sql);
if($result_sql < 0) {
    header('Location: lista_usuarios.php');
} else {
    $option = '';
    while($data = mysqli_fetch_array($sql)) {
        $him = $data['hora_inicio_ma'];
        $hfm = $data['hora_fin_ma'];
        $hit = $data['hora_inicio_tard'];
        $hft = $data['hora_fin_tard'];
        $sh = $data['sabado_h'];
        $dh = $data['domingo_h'];
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
        <h1>Actualizar calendario</h1>
        <hr>
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
        <form action="" method="post">
            <input type="text" name="iduser" id="iduser" style="display: none" value="<?php echo $iduser; ?>">
            <label for="him">Hora inicio mañana</label>
            <input type="time" name="him" id="him" placeholder="Nombre completo" value="<?php echo $him; ?>">
            <label for="hfm">Hora fin mañana</label>
            <input type="time" name="hfm" id="hfm" placeholder="Usuario" value="<?php echo $hfm; ?>">
            <label for="hit">Hora inicio tarde</label>
            <input type="time" name="hit" id="hit" placeholder="" value="<?php echo $hit; ?>">
            <label for="hft">Hora fin tarde</label>
            <input type="time" name="hft" id="hft" placeholder="" value="<?php echo $hft; ?>">
            <label for="sh">Sabado</label>
            <input type="date" name="sh" id="sh" placeholder="Correo" value="<?php echo $sh; ?>">
            <label for="dh">Domingo</label>
            <input type="date" name="dh" id="dh" placeholder="" value="<?php echo $dh; ?>">
            <input type="submit" value="Actualizar usuario" class="btn_save">
        </form>
    </div>
</section>
<br/>
</body>
</html>