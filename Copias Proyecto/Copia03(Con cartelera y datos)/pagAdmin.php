<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        CINEMAYO
    </title>
<!-- ICONOS -->
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
   
    <!-- APP CSS -->
   <link rel="stylesheet" href="./css/pagAdmin.css">
   <link rel="stylesheet" href="./css/normalice.css">
</head>

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

<body>
    <div class="cartelera">

    <h1 class="bienvenido">Ingreso con el codigo: <?php echo $_SESSION['admin']; ?> </h1>
    </div>

</body>