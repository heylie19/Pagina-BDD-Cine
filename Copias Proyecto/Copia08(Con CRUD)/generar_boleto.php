<?php
require('fpdf/fpdf.php');

if (isset($_GET['idFactura'])) {
    $idFactura = $_GET['idFactura'];

    $conexion = new mysqli("localhost", "root", "", "cine");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta preparada para obtener los detalles de la factura
    $sql = "SELECT Factura.idFactura, Factura.fecha, Pelicula.nombrePelicula AS pelicula, Funciones.fecha AS funcion_fecha, Sala.numeroSala,
            Funciones.precio, Funciones.horaFuncion AS funcion_hora
            FROM Factura
            JOIN Boleto ON Factura.idFactura = Boleto.Factura_idFactura
            JOIN Funciones ON Boleto.idFunciones = Funciones.idFunciones
            JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
            JOIN Sala ON Funciones.idSala = Sala.idSala
            WHERE Factura.idFactura = ?";
    
    // Consulta segura
           $stmt = $conexion->prepare($sql);
           $stmt->bind_param("i", $idFactura);
           $stmt->execute();
           $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $factura = $resultado->fetch_assoc();
    }

        // Configuración del PDF

$pdf = new FPDF();
$pdf->AddPage();

// Tamaño del boleto
$boletoAncho = 80; // Ancho del boleto (en mm)
$boletoAlto = 130; // Alto del boleto (en mm)

// Posición centrada
$posX = ($pdf->GetPageWidth() - $boletoAncho) / 2; // Centrado horizontal
$posY = ($pdf->GetPageHeight() - $boletoAlto) / 2; // Centrado vertical

// Dibujar contorno del boleto (opcional, para referencia visual)
$pdf->SetDrawColor(50, 50, 100); // Color del borde
$pdf->Rect($posX, $posY, $boletoAncho, $boletoAlto); // Rectángulo del tamaño del boleto

// Configuración de estilos
$pdf->SetFillColor(0, 102, 204); // Azul oscuro
$pdf->SetTextColor(255, 255, 255); // Color del texto
$pdf->SetFont('Arial', 'B', 12);

// Agregar fondo al boleto (asegúrate de tener una imagen adecuada en la ruta especificada)
$fondoBoleto = 'C:\wamp64\www\Cine\Imagenes\fondo.png'; // Cambia esto por la ruta de tu imagen
$pdf->Image($fondoBoleto, $posX, $posY, $boletoAncho, $boletoAlto);

// Encabezado del boleto
$pdf->SetXY($posX, $posY + 10); // Posicionar texto dentro del rectángulo

// Detalles del boleto
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(255, 255, 255); // Color del texto
$pdf->SetXY($posX + 10, $posY + 30); // Ajustar la posición inicial para el contenido
$pdf->Cell($boletoAncho - 20, 10, 'Pelicula: ' . $factura['pelicula'], 0, 1, );
$pdf->SetX($posX + 10);
$pdf->Cell($boletoAncho - 20, 10, 'Fecha: ' . $factura['funcion_fecha'], 0, 1);
$pdf->SetX($posX + 10);
$pdf->Cell($boletoAncho - 20, 10, 'Hora: ' . $factura['funcion_hora'], 0, 1);
$pdf->SetX($posX + 10);
$pdf->Cell($boletoAncho - 20, 10, 'Sala: ' . $factura['numeroSala'], 0, 1);

// Separador visual
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line($posX + 10, $posY + 70, $posX + $boletoAncho - 10, $posY + 70);

// Mensaje final
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetXY($posX + 10, $posY + 80);
$pdf->MultiCell($boletoAncho - 20, 10, "Por favor, conserve este boleto. Es necesario para el ingreso a la sala correspondiente.\nGracias por preferir CineMaYo.", 0, 'C');

// Guardar el PDF
$pdfFile = "boleto_factura_" . $idFactura . ".pdf";
$pdf->Output("F", $pdfFile);

// Mensaje de confirmación
echo "<h1>Boleto Generado</h1>";
echo "<p>Boleto #: " . $factura['idFactura'] . "</p>";
echo "<p>Pelicula: " . $factura['pelicula'] . "</p>";
echo "<p>Fecha Funcion: " . $factura['funcion_fecha'] . "</p>";
echo "<p>Hora Funcion: " . $factura['funcion_hora'] . "</p>";
echo "<p>Sala: " . $factura['numeroSala'] . "</p>";
echo "<p><a href='$pdfFile' target='_blank'>
      <button style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; cursor: pointer;'>Obtener Boleto</button>
      </a></p>";

echo "<p><a href='boleto.php'>
        <button style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; cursor: pointer;'>Volver</button>
      </a></p>";


    $conexion->close();
} else {
    echo "<p>Error: No se recibió un ID de factura.</p>";
}
?>


