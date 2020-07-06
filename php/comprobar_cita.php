<?php
    require_once 'conexion.php';

    $id_medico = $_GET['Medico'];
    $fecha_elegida = $_GET['Fecha'];

    $query_festivo = mysqli_query($conection, "SELECT * FROM festivos WHERE fecha = '$fecha_elegida' AND medico = '$id_medico'");
    $result_festivo = mysqli_num_rows($query_festivo);
    if($result_festivo > 0) {
        while ($data = mysqli_fetch_array($query_festivo)) {
            $tipo_festivo = $data['tipo'];
        }
    } else {
        $tipo_festivo = 'NOP';
    }

    if($tipo_festivo == 1) {
        header('Location: calendario.php?alert=1');
    } else {
        $horas = [];
        $i = 0;

        $query = mysqli_query($conection, "SELECT * FROM cita WHERE Medico='$id_medico' AND Fecha='$fecha_elegida' ORDER BY Hora");
        $result = mysqli_num_rows($query);
        if ($result > 0) {
            while ($data = mysqli_fetch_array($query)) {
                $horas[$i] = $data['Hora'];
                $pacientes[$i] = $data['Paciente'];
                $i++;
            }
        } else {
            $horas = null;
            $pacientes = null;
        }

        $query_medico = mysqli_query($conection, "SELECT * FROM calendario WHERE Medico='$id_medico'");
        $result_medico = mysqli_num_rows($query_medico);
        if ($result_medico > 0) {
            while ($data = mysqli_fetch_array($query_medico)) {
                if($tipo_festivo == 2) {
                    $him = $data['hora_inicio_ma'];
                    $hfm = $data['hora_fin_ma'];
                    $hit = '--';
                    $hft = '--';
                } else if ($tipo_festivo == 3) {
                    $him = '--';
                    $hfm = '--';
                    $hit = $data['hora_inicio_tard'];
                    $hft = $data['hora_fin_tard'];
                } else {
                    $him = $data['hora_inicio_ma'];
                    $hfm = $data['hora_fin_ma'];
                    $hit = $data['hora_inicio_tard'];
                    $hft = $data['hora_fin_tard'];
                    $sh = $data['sabado_h'];
                    $dh = $data['domingo_h'];
                }
            }
        }
    }
?>

<!DOCTYPE>
<html>
    <header>
        <link rel="stylesheet" type="text/css" href="../css/horario.css">
        <link rel="stylesheet" href="../css/date-picker.css" />
        <title>Portal de empleados</title>
    </header>
    <body>
        <div class="menu">
            <?php include "menu.php";?>
        </div>

        <form class="seleccion" action="comprobar_cita.php">
            <h1>Gestor de Citas</h1>
            <label for="Fecha">Fecha</label>
            <input type="date" name="Fecha" id="dateofbirth" value="<?php echo $fecha_elegida; ?>" required>

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
            <?php /*if (!empty($horas)) {
            echo '<ul>Horas ocupadas';
                    foreach ($horas as $hora) {
                        echo '<li>$hora</li>';
                    }
                    echo '</ul>';
                } */?>

            <?php
                /*$begin = new DateTime($him);
                $end = new DateTime($hfm);

                $interval = DateInterval::createFromDateString('20 min');

                $times = new DatePeriod($begin, $interval, $end);*/

                $p = 0;
                $n_paciente='';
                $a_paciente='';
                $list_pn = [];
                $list_pa = [];
                if(!empty($pacientes)) {
                    for($p = 0; $p<sizeof($pacientes); $p++) {
                        $query_paciente = mysqli_query($conection, "SELECT * FROM pacientes WHERE Id='$pacientes[$p]'");
                        $result_paciente = mysqli_num_rows($query_paciente);
                        if ($result_paciente > 0) {
                            while ($data = mysqli_fetch_array($query_paciente)) {
                                $n_paciente = $data['Nombre'];
                                $a_paciente = $data['Apellidos'];
                            }
                        }
                        $list_pn[$p] = $n_paciente;
                        $list_pa[$p] = $a_paciente;
                    }
                }

                echo '<br/>';
            ?>

            <div class="hora_morning">
                <h3>Horario de mañana</h3> <br/>

                <?php
                    $i = 0;
                    $pac = 0;

                    if(($tipo_festivo == 2 && $tipo_festivo != 3) || $result_festivo < 0 || $tipo_festivo == 'NOP') {

                        $begin = new DateTime($him);
                        $end = new DateTime($hfm);

                        $interval = DateInterval::createFromDateString('20 min');

                        $times = new DatePeriod($begin, $interval, $end);
                        foreach ($times as $time) {
                            if ($horas[$i] == $time->format('H:i')) {
                                echo $time->format('H:i') . ' Ocupado por ' . $list_pn[$pac] . ' ' . $list_pa[$pac] . '<br/>';
                                if ($i < sizeof($horas) - 1)
                                    $i++;
                                if ($pac < sizeof($list_pn) - 1) {
                                    $pac++;
                                }
                            } else {
                                echo $time->format('H:i') . ' <a href="alta_cita.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '"> Libre </a> <br/>';
                            }
                        }
                    }
                /*$begin = new DateTime($him);
                $end = new DateTime($hfm);

                $interval = DateInterval::createFromDateString('20 min');

                $times = new DatePeriod($begin, $interval, $end);

                $i = 0;

                echo 'Horario de mañana<br/>';
                foreach ($times as $time) {
                    if ($horas[0] == $time->format('H:i')) {
                        echo $time->format('H:i').' Ocupado por '. $paciente .'<br/>';
                        $i++;
                    } else {
                        echo $time->format('H:i').' <a href="alta_cita.php?Medico='.$id_medico.'&Fecha='.$fecha_elegida.'&Hora='.$time->format('H:i').'">Libre </a> <br/>';
                    }
                }*/
                ?>
            </div>

            <div class="hora_tarde">
                <h3>Horario de tarde</h3> <br/>
                <?php
                    if(($tipo_festivo == 3 && $tipo_festivo != 2) || $result_festivo < 0 || $tipo_festivo == 'NOP') {
                        $begin = new DateTime($hit);
                        $end = new DateTime($hft);

                        $interval = DateInterval::createFromDateString('20 min');

                        $times = new DatePeriod($begin, $interval, $end);

                        $j = $i;
                        $pan = $pac;

                        foreach ($times as $time) {
                            if ($horas[$j] == $time->format('H:i')) {
                                echo $time->format('H:i') . ' Ocupado por ' . $list_pn[$pan] . ' ' . $list_pa[$pan] . '<br/>';
                                if ($j < sizeof($horas) - 1) {
                                    $j++;
                                }
                                if ($pan < sizeof($list_pn) - 1) {
                                    $pan++;
                                }
                            } else {
                                echo $time->format('H:i') . ' <a href="alta_cita.php?Medico=' . $id_medico . '&Fecha=' . $fecha_elegida . '&Hora=' . $time->format('H:i') . '">Libre </a> <br/>';
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>