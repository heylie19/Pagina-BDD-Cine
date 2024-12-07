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

    // Consulta para obtener los empleados
    $sqlEmpleados = "SELECT codEmpleado, CONCAT(p_nombre, ' ', p_apellido) AS nombre 
                     FROM Empleado 
                     JOIN Usuario ON Empleado.idUsuario = Usuario.idUsuario";
    $resultadoEmpleados = $conexion->query($sqlEmpleados);

    if (!$resultadoEmpleados || !$funcion) {
        die("Error al cargar datos: " . $conexion->error);
    }

    $empleados = $resultadoEmpleados->fetch_all(MYSQLI_ASSOC);
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
    <p>Película: <?= htmlspecialchars($funcion['pelicula']) ?></p>
    <p>Fecha: <?= htmlspecialchars($funcion['fecha']) ?></p>
    <p>Sala: <?= htmlspecialchars($funcion['numeroSala']) ?></p>
    <p>Tipo Sala: <?= htmlspecialchars($funcion['tipo']) ?></p>
    <p>Precio: <?= htmlspecialchars($funcion['precio']) ?> USD</p>

    <form action="generar_factura.php" method="POST">
        <input type="hidden" name="idFuncion" value="<?= htmlspecialchars($funcion['idFunciones']) ?>">

        <label for="codEmpleado">Código del empleado:</label>
        <select name="codEmpleado" id="codEmpleado" required>
            <option value="" disabled selected>Seleccione un empleado</option>
            <?php foreach ($empleados as $empleado): ?>
                <option value="<?= htmlspecialchars($empleado['codEmpleado']) ?>">
                    <?= htmlspecialchars($empleado['codEmpleado'] . " - " . $empleado['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>
        <label for="cantidad">Cantidad de Boletos:</label>
        <input class="caja" type="number" name="cantidad" id="cantidad" min="1" required>
        <br><br>

        <button class="btn" type="submit">Confirmar Compra</button>
        <a href="boleto.php" class="btn">Volver a Cartelera</a>
    </form>
</div>
</body>
</html>
