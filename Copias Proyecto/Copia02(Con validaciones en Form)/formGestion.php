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
   <link rel="stylesheet" href="./css/formRegistro.css">
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

<!--Formulario Gestion-->
    <form action="" method="POST" class="form-registro" id="formGestion">
        <h2 class="h2">Acceso Restringido</h2>
        <p class="parrafo">Ingresa la siguiente información para poder ingresar.</p>
     
        <label class="lbl-formR">Codigo Empleado <span class="text-danger">*</span></label>
            <input type="text" class="formR" placeholder="Ingresa primer nombre" name="id">

            <label class="lbl-formR">Contraseña <span class="text-danger">*</span></label>
            <input type="password" class="formR" placeholder="Ingresa primer apellido" name="contra">
        
            <input type="submit" class="enviar" name="enviar">

            <a href="index.php" class="enviar">Cancelar</a>

    </form>

<!--Enlace de Jquery Core para el formulario de Gestion-->

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!--Enlace de Jquery Validate para el formulario de Gestion-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>

<!--Enlace a la funcion de validacion-->
<script src="./js/funcion.js"></script>
</body>