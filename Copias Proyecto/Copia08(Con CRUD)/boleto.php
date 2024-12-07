<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Boletos</title>

    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./css/boleto.css">
    <link rel="stylesheet" href="./css/normalice.css">
</head>
<body>
<header class="hero">
    <nav class="nav containerM">
        <div class="nav__logo">
            <h2 class="nav__title"><i class='bx bx-movie-play bx-tada main-color'></i>CineMaYo</h2>
        </div>
        <ul class="nav__link nav__link--menu">
            <li class="nav__items"><a class="nav__links" href="index.php">Inicio</a></li>
            <li class="nav__items"><a class="nav__links" href="formRegistro.php">Registro</a></li>
            <li class="nav__items"><a class="nav__links" href="cartelera2.php">Cartelera</a></li>
            <li class="nav__items"><a class="nav__links" href="boleto.php">Boletos</a></li>
        </ul>
    </nav>
</header>


<h2>Funciones Disponibles</h2>
<table class="tabla">
    <thead>
        <tr>
            <th class="label_tabla">Película</th>
            <th class="label_tabla">Fecha</th>
            <th class="label_tabla">Hora Función</th>
            <th class="label_tabla">Sala</th>
            <th class="label_tabla">Precio</th>
            <th class="label_tabla">Acción</th>
        </tr>
    </thead>
        <?php
        $conexion = new mysqli("localhost", "root", "", "cine");
        if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

        $sql = "SELECT Funciones.idFunciones, Pelicula.nombrePelicula AS pelicula, Funciones.fecha, Sala.numeroSala, 
                       Funciones.precio, Funciones.horaFuncion
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
                        <td>
                            <a href='comprar_boleto.php?idFuncion={$fila['idFunciones']}'>
                                <button class='action-button'>Comprar</button>
                            </a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay funciones disponibles</td></tr>";
        }
        $conexion->close();
        ?>
    </body>
</table>

<h2>Gestión de Funciones</h2>

<div class="admin-controls">
    <!-- Botones de administración -->
    <button class="btnCRUD" id="gestion" onclick="validarAcceso('gestion')">Gestionar Funcion</button>
</div>
<a href="cartelera2.php" class="enviar">Volver</a>

<script>
    function validarAcceso(accion) {
        const codEmpleado = prompt("Ingrese su código de empleado:");
        const contrasena = prompt("Ingrese su contraseña:");

        if (codEmpleado && contrasena) {
            const url = `validar_acceso.php?accion=${accion}&codEmpleado=${codEmpleado}&contrasena=${contrasena}`;
            window.location.href = url;
        } else {
            alert("Debe ingresar todos los datos para continuar.");
        }
    }
</script>
</body>
</html>
