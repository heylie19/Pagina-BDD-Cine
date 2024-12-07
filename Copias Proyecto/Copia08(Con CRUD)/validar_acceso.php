<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "cine");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Función para verificar si el empleado es administrador
function esAdministrador($codEmpleado) {
    return strpos($codEmpleado, 'ad') === 0; // Los administradores tienen un codEmpleado que comienza con "ad"
}

// ** Manejo de autenticación temporal (codEmpleado y contraseña) **
$codEmpleado = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codEmpleado'], $_POST['password'])) {
    $codEmpleado = $_POST['codEmpleado'];
    $password = $_POST['password'];

    // Verificar que el codEmpleado y la contraseña coincidan en la base de datos
    $sqlAuth = "SELECT idEmpleado FROM Empleado WHERE codEmpleado = '$codEmpleado' AND contrasena = '$password'";
    $resultadoAuth = $conexion->query($sqlAuth);

    if ($resultadoAuth->num_rows === 0) {
        die("Error: Credenciales incorrectas. Acceso denegado.");
    }

    if (!esAdministrador($codEmpleado)) {
        die("Acceso denegado. Solo los administradores pueden gestionar funciones.");
    }
}

// ** Manejo de solicitudes (Crear, Editar, Eliminar) **
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion === "crear") {
        // Crear nueva función
        $fecha = $_POST['fecha'];
        $hora=$_POST['hora'];
        $idPelicula = $_POST['idPelicula'];
        $idSala = $_POST['idSala'];
        $precio = $_POST['precio'];

        
    // Generar manualmente el próximo ID para la funcion
    $sqlObtenerMaxIdFunciones = "SELECT MAX(idFunciones) AS maxId FROM Funciones";
    $resultadoFunciones = $conexion->query($sqlObtenerMaxIdFunciones);
    $filaFunciones = $resultadoFunciones->fetch_assoc();
    $nuevoIdFunciones = $filaFunciones['maxId'] + 1;

        $sqlInsert = "INSERT INTO Funciones (idFunciones, fecha, horaFuncion, idPelicula, idSala, precio) 
                     VALUES ('$nuevoIdFunciones', '$fecha', '$hora', $idPelicula, $idSala, $precio)";
        if ($conexion->query($sqlInsert)) {
            echo "Función creada exitosamente.";
        } else {
            echo "Error al crear la función: " . $conexion->error;
        }
    } elseif ($accion === "editar") {
        // Editar función existente
        $idFunciones = $_POST['idFunciones'];
        $fecha = $_POST['fecha'];
        $idPelicula = $_POST['idPelicula'];
        $idSala = $_POST['idSala'];
        $precio = $_POST['precio'];

        $sqlUpdate = "UPDATE Funciones SET fecha = '$fecha', idPelicula = $idPelicula, idSala = $idSala, precio = $precio WHERE idFunciones = $idFunciones";
        if ($conexion->query($sqlUpdate)) {
            echo "Función actualizada exitosamente.";
        } else {
            echo "Error al actualizar la función: " . $conexion->error;
        }
    } elseif ($accion === "eliminar") {
        // Eliminar función
        $idFunciones = $_POST['idFunciones'];

        $sqlDelete = "DELETE FROM Funciones WHERE idFunciones = $idFunciones";
        if ($conexion->query($sqlDelete)) {
            echo "Función eliminada exitosamente.";
        } else {
            echo "Error al eliminar la función: " . $conexion->error;
        }
    }
}

// Consultar funciones para mostrar en la tabla
$sqlFunciones = "SELECT idFunciones, fecha, idPelicula, idSala, precio FROM Funciones";
$resultadoFunciones = $conexion->query($sqlFunciones);

// Consultar lista de películas
$sqlPeliculas = "SELECT idPelicula, nombrePelicula FROM Pelicula";
$resultadoPeliculas = $conexion->query($sqlPeliculas);
$peliculas = [];
while ($fila = $resultadoPeliculas->fetch_assoc()) {
    $peliculas[$fila['idPelicula']] = $fila['nombrePelicula'];
}

// Consultar lista de salas
$sqlSalas = "SELECT idSala, numeroSala FROM Sala";
$resultadoSalas = $conexion->query($sqlSalas);
$salas = [];
while ($fila = $resultadoSalas->fetch_assoc()) {
    $salas[$fila['idSala']] = $fila['numeroSala'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Funciones</title>

    <link rel="stylesheet" href="./css/boleto.css">
    <link rel="stylesheet" href="./css/normalice.css">
</head>
<body>
    <h1 class="h1FormV">Acceso a Gestión de Funciones</h1>

    <!-- Formulario de autenticación -->
    <?php if (!$codEmpleado) : ?>
        <div class="container">
        <form method="POST" action="" class="formValidacion">
            <label class="formValidacion" for="codEmpleado">Código de Empleado:</label><br>
            <input class="formValidacionInput" type="text" name="codEmpleado" required><br>
            <label class="formValidacion" for="password">Contraseña:</label><br>
            <input class="formValidacionInput" type="password" name="password" required><br>
            <button class="btnFormV" type="submit">Ingresar</button>
        </form>
        </div>
        <?php exit; ?>
    <?php endif; ?>

    <!-- Formulario para crear una nueva función -->
    <br><br><h2>Crear Función</h2>
    <form method="POST" action="">
        <input type="hidden" name="accion" value="crear">

        <label for="fecha">Fecha:</label>
        <input type="datetime-local" name="fecha" required>

        <label for="hora">Hora:</label>
        <input type="time" name="hora"required>

        <label for="idPelicula">Película:</label>
        <select name="idPelicula" required>
            <option value="">Seleccione una película</option>
            <?php foreach ($peliculas as $id => $nombre) : ?>
                <option value="<?= $id ?>"><?= $nombre ?></option>
            <?php endforeach; ?>
        </select>
        <label for="idSala">Sala:</label>
        <select name="idSala" required>
            <option value="">Seleccione una sala</option>
            <?php foreach ($salas as $id => $numero) : ?>
                <option value="<?= $id ?>"><?= $numero ?></option>
            <?php endforeach; ?>
        </select>
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" required>
        <button type="submit">Crear <br><img width="26" height="26" src="https://img.icons8.com/metro/26/add-list.png" alt="add-list"/></button>
    </form>

    <!-- Mostrar las funciones disponibles -->
    <br><h2>Funciones Disponibles</h2><br>
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Película</th>
                <th>Sala</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultadoFunciones->fetch_assoc()) : ?>
                <tr>
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="idFunciones" value="<?= $fila['idFunciones'] ?>">
                        <td><?= $fila['idFunciones'] ?></td>
                        <td><input type="date-local" name="fecha" value="<?= $fila['fecha'] ?>"></td>
                        <td>
                            <select name="idPelicula">
                                <?php foreach ($peliculas as $id => $nombre) : ?>
                                    <option value="<?= $id ?>" <?= $id == $fila['idPelicula'] ? 'selected' : '' ?>><?= $nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="idSala">
                                <?php foreach ($salas as $id => $numero) : ?>
                                    <option value="<?= $id ?>" <?= $id == $fila['idSala'] ? 'selected' : '' ?>><?= $numero ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" step="0.01" name="precio" value="<?= $fila['precio'] ?>"></td>
                        <td>
                            <button class="btnEditar" type="submit">Editar<img src="./Imagenes/editar.svg" alt="" class="equis"></button>
                        </td>
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="idFunciones" value="<?= $fila['idFunciones'] ?>">
                        <td colspan="2">
                            <button class="btnEliminar" type="submit">Eliminar
                                <img width="24" height="24" src="https://img.icons8.com/material-outlined/24/FFFFFF/waste.png" alt="waste" class="eliminar"></button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
