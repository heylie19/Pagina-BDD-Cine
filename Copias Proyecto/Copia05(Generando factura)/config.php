<?php
$host = 'localhost'; // Dirección del servidor
$dbname = 'cine'; // Nombre de la base de datos
$username = 'root'; // Nombre de usuario
$password = ''; // Contraseña del usuario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>

<?php
