<?php

session_start();

if(!isset($_SESSION["usuario"])){
    header("Location: login.html");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
<title>Sistema de Asistencia</title>
</head>

<body>

<h1>Bienvenido al Sistema de Asistencia</h1>

<p>Usuario: <?php echo $_SESSION["usuario"]; ?></p>

<a href="logout.php">Cerrar sesión</a>

</body>

</html>