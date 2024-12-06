<?php
include('config.php'); // Incluir el archivo de configuración para la conexión

// Obtener el ID de la película desde la URL
if (isset($_GET['idPelicula'])) {
    $idPelicula = $_GET['idPelicula'];

    // Consulta para obtener los detalles de la película seleccionada
    $sql = "SELECT p.idPelicula, p.nombrePelicula, p.duracion, p.director, p.descripcion, c.tipo, g.nombre
            FROM pelicula p
            INNER JOIN ClasificacionPelicula c ON p.idClasificacionPelicula = c.idClasificacionPelicula
            INNER JOIN GeneroPelicula g ON p.idGeneroPelicula = g.idGeneroPelicula
                WHERE p.idPelicula = :idPelicula";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idPelicula', $idPelicula, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los detalles de la película
    $pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si la película existe
    if (!$pelicula) {
        die('Película no encontrada.');
    }
} else {
    die('No se ha proporcionado un ID de película.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Película</title>
   
     <!-- ICONOS -->
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
   
   <!-- APP CSS -->
  <link rel="stylesheet" href="./css/cartelera2.css">
  <link rel="stylesheet" href="./css/normalice.css">
</head>
<body>

    <header>
        <h1>Detalles de la Película</h1>
    </header>

    <div class="detalles">
            <img src="https://via.placeholder.com/250x350?text=<?php echo urlencode($pelicula['nombre']); ?>" alt="<?php echo $pelicula['nombre']; ?>">
            <div class="movie-info">
                <h3><?php echo $pelicula['nombrePelicula']; ?></h3>
                <p><strong>Director:</strong> <?php echo $pelicula['director']; ?></p>
                <p><strong>Duración:</strong> <?php echo $pelicula['duracion']; ?> min</p>
                <p><strong>Clasificación:</strong> <?php echo $pelicula['tipo']; ?></p>
                <p><strong>Género:</strong> <?php echo $pelicula['nombre']; ?></p>
        <h3>Descripción:</h3>
        <p><?php echo nl2br($pelicula['descripcion']); ?></p>
        <a href="cartelera2.php" class="back-btn">Volver a la cartelera</a>
    </div>

</body>
</html>
