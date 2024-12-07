<?php
require('./fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        //$this->Image('ruta_completa/logo.png', 185, 5, 20); 
        $this->SetFont('Arial', 'B', 19); 
        $this->Cell(45);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, iconv('UTF-8', 'ISO-8859-1', 'NOMBRE EMPRESA'), 1, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        /* Datos adicionales de la cabecera */
        $this->Cell(110);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, iconv('UTF-8', 'ISO-8859-1', 'Ubicación: Dirección ejemplo'), 0, 0, '', 0);
        $this->Ln(5);

        $this->Cell(110);
        $this->Cell(59, 10, iconv('UTF-8', 'ISO-8859-1', 'Teléfono: +123 456 789'), 0, 0, '', 0);
        $this->Ln(5);

        $this->Cell(110);
        $this->Cell(85, 10, iconv('UTF-8', 'ISO-8859-1', 'Correo: ejemplo@correo.com'), 0, 0, '', 0);
        $this->Ln(10);

        $this->SetTextColor(228, 100, 0);
        $this->Cell(50);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, iconv('UTF-8', 'ISO-8859-1', 'REPORTE DE HABITACIONES'), 0, 1, 'C', 0);
        $this->Ln(7);

        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(18, 10, iconv('UTF-8', 'ISO-8859-1', 'N°'), 1, 0, 'C', 1);
        $this->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', 'NÚMERO'), 1, 0, 'C', 1);
        $this->Cell(30, 10, iconv('UTF-8', 'ISO-8859-1', 'TIPO'), 1, 0, 'C', 1);
        $this->Cell(25, 10, iconv('UTF-8', 'ISO-8859-1', 'PRECIO'), 1, 0, 'C', 1);
        $this->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', 'CARACTERÍSTICAS'), 1, 0, 'C', 1);
        $this->Cell(25, 10, iconv('UTF-8', 'ISO-8859-1', 'ESTADO'), 1, 1, 'C', 1);
    }

    function Footer()
    {
        $this->SetY(-15); 
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetX(-30);
        $hoy = date('d/m/Y');
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', $hoy), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

/* Datos dinámicos de ejemplo */
$datos = [
    ['numero' => 1, 'nombre' => 'Habitación 101', 'tipo' => 'Individual', 'precio' => '50.00', 'caracteristicas' => 'Cama sencilla, Wi-Fi', 'estado' => 'Disponible'],
    ['numero' => 2, 'nombre' => 'Habitación 102', 'tipo' => 'Doble', 'precio' => '80.00', 'caracteristicas' => 'Cama doble, Vista al mar', 'estado' => 'Ocupada'],
];

foreach ($datos as $fila) {
    $pdf->Cell(18, 10, $fila['numero'], 1, 0, 'C', 0);
    $pdf->Cell(20, 10, iconv('UTF-8', 'ISO-8859-1', $fila['numero']), 1, 0, 'C', 0);
    $pdf->Cell(30, 10, iconv('UTF-8', 'ISO-8859-1', $fila['tipo']), 1, 0, 'C', 0);
    $pdf->Cell(25, 10, $fila['precio'], 1, 0, 'C', 0);
    $pdf->Cell(70, 10, iconv('UTF-8', 'ISO-8859-1', $fila['caracteristicas']), 1, 0, 'C', 0);
    $pdf->Cell(25, 10, iconv('UTF-8', 'ISO-8859-1', $fila['estado']), 1, 1, 'C', 0);
}

$pdf->Output('Prueba.pdf', 'I');
