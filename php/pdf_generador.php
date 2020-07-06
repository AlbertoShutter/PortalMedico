<?php
    require ('../fpdf/fpdf.php');

    class PDF extends FPDF {
        // Cabecera de página
        function Header() {
            // Logo
            $this->Image('../img/logougr.jpg',170,8,33);
            // Arial bold 15
            $this->SetFont('Arial','B',15);
            // Movernos a la derecha
            $this->Cell(65);
            // Título
            $this->Cell(60,10,utf8_decode('Informe Médico'),0,0,'C');
            // Salto de línea
            $this->Ln(20);
        }

        // Pie de página
        function Footer() {
            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Número de página
            $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
        }
    }

    require 'conexion.php';

    if(empty($_GET['Id'])) {
        header('Location: lista_informes.php');
    }

    $dni = $_GET['Id'];
    $sql = "SELECT Titulo, (u.Nombre) as Nom_med, (p.Nombre) as Nom_pac, p.Apellidos, Fecha, Hora, Contenido 
            FROM informe i 
            INNER JOIN usuario u ON i.Medico = u.Id 
            INNER JOIN pacientes p ON i.Medico = p.Id 
            WHERE i.Id = '$dni'";
    $resultado = mysqli_query($conection, $sql);

    while($row = $resultado->fetch_assoc()) {
        $medico = $row['Nom_med'];
        $paciente = $row['Nom_pac'].' '.$row['Apellidos'];
        $fecha = $row['Fecha'];
        $hora = $row['Hora'];
        $contenido = $row['Contenido'];
    }

    // Creación del objeto de la clase heredada
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);

    $pdf -> Text(30, 40,'Ha sido atendido por '. $medico .'');
    $pdf -> Text(30, 50,'El paciente '. $paciente .'');
    $pdf -> Text(30, 60,'Atendido el '. $fecha .' a las '. $hora .'');
    $pdf -> Text(30,70,''.$contenido);

    $pdf->Output();
?>