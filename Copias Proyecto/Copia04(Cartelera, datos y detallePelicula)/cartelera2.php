<?php
include('config.php'); // Incluir el archivo de configuración para la conexión

// Consulta para obtener las películas, clasificaciones y géneros
$sql = "SELECT p.idPelicula, p.nombrePelicula, p.duracion, p.director, c.tipo, g.nombre
        FROM pelicula p
        INNER JOIN ClasificacionPelicula c ON p.idClasificacionPelicula = c.idClasificacionPelicula
        INNER JOIN GeneroPelicula g ON p.idGeneroPelicula = g.idGeneroPelicula";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartelera de Cine</title>
    
    <!-- ICONOS -->
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
   
   <!-- APP CSS -->
  <link rel="stylesheet" href="./css/cartelera2.css">
  <link rel="stylesheet" href="./css/normalice.css">

</head>
<body>
<!--Menu Navegacion-->
<header class="hero">
        <nav class="nav container">
            <div class="nav__logo">
                <h2 class="nav__title"><i class='bx bx-movie-play bx-tada main-color'></i>CineMaYo</h2>
            </div>
            
            <!--Lista Menu-->
            <ul class="nav__link nav__link--menu">
                <li class="nav__items">
                    <a href="index.php" class="nav__links">Inicio</a>
                </li>
                <li class="nav__items">
                    <a href="formRegistro.php" class="nav__links">Registro</a>
                </li>
                <li class="nav__items">
                    <a href="#" class="nav__links">Cartelera</a>
                </li>
                <li class="nav__items">
                    <a href="#" class="nav__links">Boletos</a>
                </li>
            </ul>
        </nav>
    </header>
<!--Fin Menu Navegacion-->
    <header>
        <h1>Cartelera de Cine</h1>
    </header>

    <div class="container">
        <?php foreach ($peliculas as $pelicula): ?>
        <div class="movie">
            <img src="https://via.placeholder.com/250x350?text=<?php echo urlencode($pelicula['nombre']); ?>" alt="<?php echo $pelicula['nombre']; ?>">
            <div class="movie-info">
                <h3><?php echo $pelicula['nombrePelicula']; ?></h3>
                <p><strong>Director:</strong> <?php echo $pelicula['director']; ?></p>
                <p><strong>Duración:</strong> <?php echo $pelicula['duracion']; ?> min</p>
                <p><strong>Clasificación:</strong> <?php echo $pelicula['tipo']; ?></p>
                <p><strong>Género:</strong> <?php echo $pelicula['nombre']; ?></p>
                <a href="detallePelicula.php?idPelicula=<?php echo $pelicula['idPelicula']; ?>" class="btn-ver-mas">Ver más</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
