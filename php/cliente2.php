<?php
    require_once "../lib/nusoap.php";

    $client = new nusoap_client("http://localhost/Proyecto/php/servidor.php?wsdl");
    $usuarios =  $client->call("cargarUsuario", array());
    $usuarios = json_decode($usuarios);

    if(empty($_GET['Medico'] || $_GET['Fecha']))
        header('Location: cliente.php');

    $id_medico = $_GET['Medico'];
    $fecha_elegida = $_GET['Fecha'];

    $horario = $client->call("devolverCalendario", array('id_medico'=>$id_medico));
    $horario = json_decode($horario);
    foreach ($horario as $hr) {
        $him = $hr->hora_inicio_ma;
        $hfm = $hr->hora_fin_ma;
        $hit = $hr->hora_inicio_tard;
        $hft = $hr->hora_fin_tard;
        $sh = $hr->sabado_h;
        $dh = $hr->domingo_h;
    }

    $horas_ocupadas = $client->call("devolverCitas", array('id_medico'=>$id_medico, 'fecha_elegida'=>$fecha_elegida));
    $horas_ocupadas = json_decode($horas_ocupadas);
    //$i = 0;
    $horas_nd = [];
    foreach($horas_ocupadas as $ha) {
        $horas_nd [] = $ha->Hora;
    }
?>

<!DOCTYPE>
<html>
    <header>
        <link rel="stylesheet" type="text/css" href="../css/horario.css">
        <link rel="stylesheet" href="../css/date-picker.css" />
    </header>
    <body>
        <form class="seleccion" action="cliente2.php">
            <h1>Gestor de Citas</h1>
            <label for="Fecha">Fecha</label>
            <input type="date" name="Fecha" id="dateofbirth" value="<?php echo $fecha_elegida; ?>" required>

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

        <form class="hora_laboral">
            <label for="him">Hora inicio mañana</label>
            <input name="him" id="him" value="<?php echo $him; ?>" readonly>
            <label for="hfm">Hora fin mañana</label>
            <input name="hfm" id="hfm" value="<?php echo $hfm; ?>" readonly>
            <label for="hit">Hora inicio tarde</label>
            <input name="hit" id="hit" value="<?php echo $hit; ?>" readonly>
            <label for="hft">Hora fin tarde</label>
            <input name="hft" id="hft" value="<?php echo $hft; ?>" readonly>
        </form>

        <div class="timetable">
            <div class="hora_morning">
                <h3>Horario de mañana</h3> <br/>

                <?php
                    $begin = new DateTime($him);
                    $end = new DateTime($hfm);

                    $interval = DateInterval::createFromDateString('20 min');

                    $times = new DatePeriod($begin, $interval, $end);
                    $i = 0;
                    //$pac = 0;

                    foreach ($times as $time) {
                        if($horas_nd != null) {
                            if ($horas_nd[$i] == $time->format('H:i')) {
                                echo $time->format('H:i') . ' Ocupado <br/>';
                                if ($i < sizeof($horas_nd) - 1)
                                    $i++;
                            } else {
                                echo $time->format('H:i') . ' <a href="cliente3.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '"> Libre </a> <br/>';
                            }
                        } else {
                            echo $time->format('H:i') . ' <a href="cliente3.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '"> Libre </a> <br/>';
                        }
                    }
                ?>
            </div>

            <div class="hora_tarde">
                <h3>Horario de tarde</h3> <br/>
                <?php
                    $begin = new DateTime($hit);
                    $end = new DateTime($hft);

                    $interval = DateInterval::createFromDateString('20 min');

                    $times = new DatePeriod($begin, $interval, $end);

                    $j = $i;

                    foreach ($times as $time) {
                        if($horas_nd != null) {
                            if ($horas_nd[$j] == $time->format('H:i')) {
                                echo $time->format('H:i') . ' Ocupado <br/>';
                                if ($j < sizeof($horas_nd) - 1) {
                                    $j++;
                                }
                            } else {
                                echo $time->format('H:i') . ' <a href="cliente3.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '">Libre </a> <br/>';
                            }
                        } else {
                            echo $time->format('H:i') . ' <a href="cliente3.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '">Libre </a> <br/>';
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
