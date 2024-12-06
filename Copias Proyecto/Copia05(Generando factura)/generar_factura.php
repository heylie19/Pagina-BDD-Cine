<?php
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idFuncion'], $_POST['cantidad'])) {
    $idFuncion = $_POST['idFuncion'];
    $cantidad = $_POST['cantidad'];

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "cine");
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Generar manualmente el próximo ID para la factura
    $sqlObtenerMaxIdFactura = "SELECT MAX(idFactura) AS maxId FROM Factura";
    $resultadoFactura = $conexion->query($sqlObtenerMaxIdFactura);
    $filaFactura = $resultadoFactura->fetch_assoc();
    $nuevoIdFactura = $filaFactura['maxId'] + 1;

    // Inserta la factura en la base de datos
    $idEmpleado = 1; // Cambia por el ID real del empleado
    $idCliente = 2;  // Cambia por el ID real del cliente
    $sqlInsertFactura = "INSERT INTO Factura (idFactura, fecha, idEmpleado, idCliente) 
                         VALUES ($nuevoIdFactura, NOW(), $idEmpleado, $idCliente)";
    if (!$conexion->query($sqlInsertFactura)) {
        die("Error al insertar la factura: " . $conexion->error);
    }

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
                        Pelicula.nombrePelicula AS pelicula, Funciones.fecha AS funcion_fecha, Sala.numeroSala, Funciones.precio,
                        CONCAT(UsuarioCliente.p_nombre, ' ', UsuarioCliente.p_apellido) AS cliente,
                        CONCAT(UsuarioEmpleado.p_nombre, ' ', UsuarioEmpleado.p_apellido) AS empleado
                   FROM Factura
                   JOIN Boleto ON Factura.idFactura = Boleto.Factura_idFactura
                   JOIN Funciones ON Boleto.idFunciones = Funciones.idFunciones
                   JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
                   JOIN Sala ON Funciones.idSala = Sala.idSala
                   JOIN Cliente ON Factura.idCliente = Cliente.idCliente
                   JOIN Usuario AS UsuarioCliente ON Cliente.idUsuario = UsuarioCliente.idUsuario
                   JOIN Empleado ON Factura.idEmpleado = Empleado.idEmpleado
                   JOIN Usuario AS UsuarioEmpleado ON Empleado.idUsuario = UsuarioEmpleado.idUsuario
                   WHERE Factura.idFactura = $nuevoIdFactura";

    $resultado = $conexion->query($sqlFactura);
    $factura = $resultado->fetch_assoc();

    if (!$factura) {
        die("Error: No se encontraron datos para la factura.");
    }

    // Generar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Factura de Cine', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Factura #: " . $factura['idFactura'], 0, 1);
    $pdf->Cell(0, 10, "Fecha: " . $factura['fecha'], 0, 1);
    $pdf->Cell(0, 10, "Cliente: " . $factura['cliente'], 0, 1);
    $pdf->Cell(0, 10, "Empleado: " . $factura['empleado'], 0, 1);
    $pdf->Ln(10);

    $pdf->Cell(0, 10, "Pelicula: " . $factura['pelicula'], 0, 1);
    $pdf->Cell(0, 10, "Fecha de Funcion: " . $factura['funcion_fecha'], 0, 1);
    $pdf->Cell(0, 10, "Sala: " . $factura['numeroSala'], 0, 1);
    $pdf->Cell(0, 10, "Precio por boleto: " . $factura['precio'] . " USD", 0, 1);
    $pdf->Ln(10);

    $pdfFile = "factura_" . $nuevoIdFactura . ".pdf";
    $pdf->Output("F", $pdfFile);

    echo "<h1>Factura Generada</h1>";
    echo "<p>Factura #: " . $factura['idFactura'] . "</p>";
    echo "<p>Fecha: " . $factura['fecha'] . "</p>";
    echo "<p>Cliente: " . $factura['cliente'] . "</p>";
    echo "<p>Empleado: " . $factura['empleado'] . "</p>";
    echo "<p><a href='$pdfFile' target='_blank'>Descargar Factura en PDF</a></p>";

    $conexion->close();
}
?>
