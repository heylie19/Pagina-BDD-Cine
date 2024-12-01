

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

<!--Formulario Registro-->
    <form action="" method="POST" class="form-registro">
        <h2 class="h2">Registrar Nuevo Usuario</h2>
        <p class="parrafo">Ingresa la siguiente información para registrar.</p>
     
        <label class="lbl-formR">Primer Nombre <span class="text-danger">*</span></label>
            <input type="text" class="formR" placeholder="Ingresa primer nombre" name="pnombre">

            <label class="lbl-formR">Segundo Nombre </label>
            <input type="text" class="formR" placeholder="Ingresa segundo nombre" name="snombre">

            <label class="lbl-formR">Primer Apellido <span class="text-danger">*</span></label>
            <input type="text" class="formR" placeholder="Ingresa primer apellido" name="papellido">

            <label class="lbl-formR">Segundo Apellido<span class="text-danger">*</span></label>
            <input type="text" class="formR" placeholder="Ingresa segundo apellido" name="sapellido">

            <label class="lbl-formR">Correo electrónico <span class="text-danger">*</span></label>
            <input type="email" class="formR" placeholder="Ingresa correo electrónico" name="correo">

            <label class="lbl-formR">Tipo Usuario <span class="text-danger">*</span></label>
            <input type="number" min="1" max="3" step="1"  class="formR" placeholder="1:Admin 2:Empleado 3:Cliente" tUsuario name="tipoUsuario"> 
        
            <input type="submit" class="enviar" name="enviar">

            <a href="index.php" class="enviar">Cancelar</a>


<!--CONEXION A LA BASE DE DATOS-->
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $conexion = new mysqli('localhost', 'root', '', 'cine');
                    $primerNombre = $_POST['pnombre'];
                    $segundoNombre = $_POST['snombre'];
                    $primerApellido = $_POST['papellido'];
                    $segundoApellido = $_POST['sapellido'];
                    $correo = $_POST['correo'];
                    $tUsuario = $_POST['tipoUsuario'];

                    $insertar = "INSERT INTO usuario (p_nombre, s_nombre, p_apellido, s_apellido, correo, idTipoUsuario)
                                VALUES ('$primerNombre', '$segundoNombre', '$primerApellido', '$segundoApellido', '$correo', '$tUsuario' )";
                    
                    $ejecutarInsertar = mysqli_query($conexion, $insertar);
                    
                    if ($ejecutarInsertar) {
                        echo '
                        <script>
                        alert("Registro Exitoso");
                        location.href = "index.php";
                        </script>
                        ';
                    } else {
                        echo '
                        <script>
                        alert("Error en el registro");
                        </script>
                        ';
                    }
                    mysqli_close($conexion);
                }
            ?>     
    </form> 
</body>
</html>


