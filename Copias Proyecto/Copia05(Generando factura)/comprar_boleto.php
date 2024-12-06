<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['idFuncion'])) {
    $idFuncion = $_GET['idFuncion'];
    $conexion = new mysqli("localhost", "root", "", "cine");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtiene los detalles de la función
    $sql = "SELECT Funciones.idFunciones, Pelicula.nombrePelicula AS pelicula, Funciones.fecha, Sala.numeroSala, Funciones.precio, TipoSala.tipo
            FROM Funciones
            JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
            JOIN Sala ON Funciones.idSala = Sala.idSala
            JOIN TipoSala ON Sala.idTipoSala = TipoSala.idTipoSala
            WHERE Funciones.idFunciones = $idFuncion";
    $resultado = $conexion->query($sql);
    $funcion = $resultado->fetch_assoc();
    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/boleto.css">
    <title>Comprar Boleto</title>
</head>
<body>
<div class="caja">
    <h1>Comprar Boleto</h1>
    <h2>Detalles de la Función</h2>
    <p>Película: <?= $funcion['pelicula'] ?></p>
    <p>Fecha: <?= $funcion['fecha'] ?></p>
    <p>Sala: <?= $funcion['numeroSala'] ?></p>
    <p>Tipo Sala: <?= $funcion['tipo'] ?></p>
    <p>Precio: <?= $funcion['precio'] ?> USD</p>

    <form action="generar_factura.php" method="POST">
        <input type="hidden" name="idFuncion" value="<?= $funcion['idFunciones'] ?>">
        <label for="cantidad">Cantidad de Boletos:</label>
        <input class="caja" type="number" name="cantidad" id="cantidad" min="1" required>
        <br><br>
    </div>
        <button class="btn" "submit">Generar Factura</button>
        <a href="cartelera2.php" class="btn">Volver a Cartelera</a>
    </form>
</body>
</html>
