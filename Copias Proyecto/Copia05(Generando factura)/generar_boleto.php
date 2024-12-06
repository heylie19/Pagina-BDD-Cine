<?php
require('fpdf/fpdf.php');

if (isset($_GET['idFactura'])) {
    $idFactura = $_GET['idFactura'];

    $conexion = new mysqli("localhost", "root", "", "cine");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtiene los detalles de la factura y los boletos
    $sql = "SELECT Factura.idFactura, Factura.fecha, Pelicula.nombre AS pelicula, Funciones.fecha AS funcion_fecha, Sala.numeroSala, Funciones.precio 
            FROM Factura
            JOIN Boletos ON Factura.idFactura = Boletos.Factura_idFactura
            JOIN Funciones ON Boletos.idFunciones = Funciones.idFunciones
            JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
            JOIN Sala ON Funciones.idSala = Sala.idSala
            WHERE Factura.idFactura = $idFactura";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $factura = $resultado->fetch_assoc();

        // Configuración del PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Encabezado de la factura
        $pdf->Cell(0, 10, 'Factura de Cine', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Factura #: " . $factura['idFactura'], 0, 1);
        $pdf->Cell(0, 10, "Fecha: " . $factura['fecha'], 0, 1);
        $pdf->Ln(10);

        // Detalles del boleto
        $pdf->Cell(0, 10, "Pelicula: " . $factura['pelicula'], 0, 1);
        $pdf->Cell(0, 10, "Fecha de Funcion: " . $factura['funcion_fecha'], 0, 1);
        $pdf->Cell(0, 10, "Sala: " . $factura['numeroSala'], 0, 1);
        $pdf->Cell(0, 10, "Precio por boleto: " . $factura['precio'] . " USD", 0, 1);
        $pdf->Ln(10);

        // Guardar PDF
        $pdfFile = "boleto_factura_" . $idFactura . ".pdf";
        $pdf->Output("F", $pdfFile);

        echo "<h1>Boleto Generado</h1>";
        echo "<p>Factura #: " . $factura['idFactura'] . "</p>";
        echo "<p>Fecha: " . $factura['fecha'] . "</p>";
        echo "<p><a href='$pdfFile' target='_blank'>Descargar Boleto en PDF</a></p>";
    } else {
        echo "<p>Error: No se encontraron datos para la factura.</p>";
    }

    $conexion->close();
} else {
    echo "<p>Error: No se recibió un ID de factura.</p>";
}
?>
