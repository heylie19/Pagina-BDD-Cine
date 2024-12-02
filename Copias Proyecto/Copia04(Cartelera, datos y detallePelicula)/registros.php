<?php

include('conexion.php');


if(isset($_POST['pnombre']))
$primerNombre= $_POST ['pnombre'];   //pnombre es el nombre dado en el input del formulario
$segundoNombre= $_POST ['snombre'];
$primerApellido= $_POST ['papellido'];
$segundoApellido= $_POST ['sapellido'];
$correo= $_POST['correo'];
$tUsuario= $_POST['tipoUsuario'];


$insertar= "INSERT INTO usuario (idUsuario, p_nombre, s_nombre, p_apellido, s_apellido, correo, idTipoUsuario) VALUES ('', '$primerNombre', '$segundoNombre', '$primerApellido', '$segundoApellido', '$correo', '$tUsuario')";

$ejecutarInsertar = mysqli_query($conexion, $insertar);
mysqli_close($conexion);

if($ejecutarInsertar)
{
    echo '
    <script>
    alert("Registro Exitoso");
    location.href = "registros.php";
    </script>
    ';
}

mysqli_close($conexion);
?>


