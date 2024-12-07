<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFuncion'], $_POST['cantidad'], $_POST['codEmpleado'])) {
    $idFuncion = $_POST['idFuncion'];
    $cantidad = $_POST['cantidad'];
    $codEmpleado = $_POST['codEmpleado']; // Recibe el codEmpleado seleccionado

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cine");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener el idEmpleado asociado al codEmpleado
    $sqlEmpleado = "SELECT idEmpleado FROM Empleado WHERE codEmpleado = '$codEmpleado'";
    $resultadoEmpleado = $conexion->query($sqlEmpleado);
    if ($resultadoEmpleado->num_rows === 0) {
        die("Error: No se encontró un empleado con el código proporcionado.");
    }
    $filaEmpleado = $resultadoEmpleado->fetch_assoc();
    $idEmpleado = $filaEmpleado['idEmpleado'];

    // Generar manualmente el próximo ID para la factura
    $sqlObtenerMaxIdFactura = "SELECT MAX(idFactura) AS maxId FROM Factura";
    $resultadoFactura = $conexion->query($sqlObtenerMaxIdFactura);
    $filaFactura = $resultadoFactura->fetch_assoc();
    $nuevoIdFactura = $filaFactura['maxId'] + 1;

    // Inserta la factura en la base de datos
    $idCliente = 2;  // Cambia por el ID real del cliente
    $sqlInsertFactura = "INSERT INTO Factura (idFactura, fecha, idEmpleado, idCliente) 
                         VALUES ($nuevoIdFactura, NOW(), $idEmpleado, $idCliente)";
    if (!$conexion->query($sqlInsertFactura)) {
        die("Error al insertar la factura: " . $conexion->error);
    }

    // Obtener el precio por boleto de la función
    $sqlPrecio = "SELECT precio FROM Funciones WHERE idFunciones = $idFuncion";
    $resultadoPrecio = $conexion->query($sqlPrecio);
    $filaPrecio = $resultadoPrecio->fetch_assoc();
    $precioPorBoleto = $filaPrecio['precio'];

    // Calcular el precio total
    $precioTotal = $precioPorBoleto * $cantidad;

    // Generar el próximo ID para idTipoBoleto
    $sqlObtenerMaxIdTipoBoleto = "SELECT MAX(idTipoBoleto) AS maxId FROM TipoBoleto";
    $resultadoTipoBoleto = $conexion->query($sqlObtenerMaxIdTipoBoleto);
    $filaTipoBoleto = $resultadoTipoBoleto->fetch_assoc();
    $nuevoIdTipoBoleto = $filaTipoBoleto['maxId'] + 1;

    // Inserta un tipo de boleto asociado (si es necesario)
    $sqlInsertTipoBoleto = "INSERT INTO TipoBoleto (idTipoBoleto, tipo)
                            VALUES ($nuevoIdTipoBoleto, 'Entrada General')";
    if (!$conexion->query($sqlInsertTipoBoleto)) {
        die("Error al insertar tipo de boleto: " . $conexion->error);
    }

    // Generar boletos asociados a la factura
    $sqlObtenerMaxIdBoleto = "SELECT MAX(idBoleto) AS maxId FROM Boleto";
    $resultadoBoleto = $conexion->query($sqlObtenerMaxIdBoleto);
    $filaBoleto = $resultadoBoleto->fetch_assoc();
    $nuevoIdBoleto = $filaBoleto['maxId'] + 1;

    for ($i = 0; $i < $cantidad; $i++) {
        $sqlInsertBoleto = "INSERT INTO Boleto (idBoleto, num_asiento, idTipoBoleto, idFunciones, Factura_idFactura)
                            VALUES ($nuevoIdBoleto, NULL, $nuevoIdTipoBoleto, $idFuncion, $nuevoIdFactura)";
        if (!$conexion->query($sqlInsertBoleto)) {
            die("Error al generar boleto: " . $conexion->error);
        }
        $nuevoIdBoleto++;
    }

    // Consulta para obtener los datos de la factura
    $sqlFactura = "SELECT 
                        Factura.idFactura, Factura.fecha,
                        Pelicula.nombrePelicula AS pelicula, Funciones.fecha AS funcion_fecha, Sala.numeroSala,
                        CONCAT(UsuarioCliente.p_nombre, ' ', UsuarioCliente.p_apellido) AS cliente
                   FROM Factura
                   JOIN Boleto ON Factura.idFactura = Boleto.Factura_idFactura
                   JOIN Funciones ON Boleto.idFunciones = Funciones.idFunciones
                   JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
                   JOIN Sala ON Funciones.idSala = Sala.idSala
                   JOIN Cliente ON Factura.idCliente = Cliente.idCliente
                   JOIN Usuario AS UsuarioCliente ON Cliente.idUsuario = UsuarioCliente.idUsuario
                   WHERE Factura.idFactura = $nuevoIdFactura";

    $resultado = $conexion->query($sqlFactura);
    $factura = $resultado->fetch_assoc();

    if (!$factura) {
        die("Error: No se encontraron datos para la factura.");
    }
    //$this->Cell(96, 10, iconv('UTF-8', 'ISO-8859-1', 'Ubicación: Dirección ejemplo'), 0, 0, '', 0);
    
    // Generar PDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado de factura
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetFillColor(0, 102, 204); // Azul oscuro
$pdf->SetTextColor(255, 255, 255); // Texto blanco
$pdf->Cell(0, 15, 'Factura de CineMaYo', 0, 1, 'C', true);
$pdf->Ln(5);

// Información de la factura
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Texto negro
$pdf->Cell(50, 10, 'Factura #:', 1, 0, 'L');
$pdf->Cell(140, 10, $factura['idFactura'], 1, 1, 'L');
$pdf->Cell(50, 10, 'Fecha:', 1, 0, 'L');
$pdf->Cell(140, 10, $factura['fecha'], 1, 1, 'L');
$pdf->Cell(50, 10, 'Cliente:', 1, 0, 'L');
$pdf->Cell(140, 10, $factura['cliente'], 1, 1, 'L');
$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', 'Empleado (Código):'), 1, 0, 'L');
$pdf->Cell(140, 10, $codEmpleado, 1, 1, 'L');
$pdf->Ln(10);

// Encabezado de factura
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetFillColor(0, 102, 204); // Azul oscuro
$pdf->SetTextColor(255, 255, 255); // Texto blanco
$pdf->Cell(0, 15, 'Detalle', 0, 1, 'C', true);
$pdf->Ln(5);

// Tabla con detalles de la compra
$pdf->SetFillColor(220, 230, 241); // Azul claro para encabezados
$pdf->SetTextColor(0, 0, 0); // Texto negro
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Pelicula', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Cantidad', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Precio Unitario', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

// Detalles de los boletos
$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor(255, 255, 255); // Fondo blanco para filas
$pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $factura['pelicula']), 1, 0, 'C');
$pdf->Cell(50, 10, $cantidad, 1, 0, 'C');
$pdf->Cell(50, 10, 'Lps.' . number_format($precioPorBoleto, 2), 1, 0, 'C');
$pdf->Cell(30, 10, 'Lps.' . number_format($precioPorBoleto * $cantidad, 2), 1, 1, 'C');

// Total a pagar
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(0, 102, 204); // Azul oscuro para el total
$pdf->SetTextColor(255, 255, 255); // Texto blanco
$pdf->Cell(160, 10, 'Total a Pagar:', 1, 0, 'R', true);
$pdf->Cell(30, 10, 'Lps. ' . number_format($precioTotal, 2), 1, 1, 'C', true);

// Guardar el archivo y mostrar enlace
$pdfFile = "factura_" . $nuevoIdFactura . ".pdf";
$pdf->Output("F", $pdfFile);

echo "<h1>Factura Generada</h1>";
echo "<p>Factura #: " . $factura['idFactura'] . "</p>";
echo "<p>Fecha: " . $factura['fecha'] . "</p>";
echo "<p>Cliente: " . $factura['cliente'] . "</p>";
echo "<p>Empleado (Código): " . $codEmpleado . "</p>";
echo "<p>Pelicula: " . $factura['pelicula'] . "</p>";
echo "<p>Precio por boleto: Lps. " . $precioPorBoleto . "</p>";
echo "<p>Cantidad de boletos: " . $cantidad . "</p>";
echo "<p>Total a pagar: Lps. " . $precioTotal . "</p>";
echo "<p><a href='$pdfFile' target='_blank'>
 <button style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; cursor: pointer;'>Generar Factura</button></a></p>";

// Botón para ir a la página de generar boletos
echo "<p><a href='generar_boleto.php?idFactura=$nuevoIdFactura'>
        <button style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; cursor: pointer;'>Generar Boletos</button>
      </a></p>";

echo "<p><a href='boleto.php'>
        <button style='padding: 10px 20px; background-color: #007BFF; color: white; border: none; cursor: pointer;'>Volver</button>
      </a></p>";

$conexion->close();


}
?>


