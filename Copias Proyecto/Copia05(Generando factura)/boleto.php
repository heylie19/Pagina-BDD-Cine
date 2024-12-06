<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Boletos</title>
    <link rel="stylesheet" href="./css/boleto.css">
</head>

<body>
    <h1>Venta de Boletos</h1>
    <h2>Funciones Disponibles</h2>
    <table>
        <thead>
            <tr>
                <th>Película</th>
                <th>Fecha</th>
                <th>Hora Funcion</th>
                <th>Sala</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
</body>
            <?php
            // Conexión a la base de datos
            $conexion = new mysqli("localhost", "root", "", "cine");
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }
            
            // Consulta las funciones
            $sql = "SELECT Funciones.idFunciones, Pelicula.nombrePelicula AS pelicula, Funciones.fecha, Sala.numeroSala, Funciones.precio, Funciones.horaFuncion
                    FROM Funciones
                    JOIN Pelicula ON Funciones.idPelicula = Pelicula.idPelicula
                    JOIN Sala ON Funciones.idSala = Sala.idSala";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>{$fila['pelicula']}</td>
                            <td>{$fila['fecha']}</td>
                            <td>{$fila['horaFuncion']}</td>
                            <td>{$fila['numeroSala']}</td>
                            <td>Lps {$fila['precio']}</td>
                            <td><a  class='btncompra' href='comprar_boleto.php?idFuncion={$fila['idFunciones']}'>Comprar</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay funciones disponibles</td></tr>";
            }

            $conexion->close();
            ?>
        </body>
    </table>
</body>
</html>
