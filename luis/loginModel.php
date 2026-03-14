<?php

session_start();
include "db.php";

$accion = $_GET["accion"];

if($accion == "login"){

$usuario = $_GET["usuario"];
$password = $_GET["password"];

$sql = "SELECT * FROM usuarios 
WHERE usuario='$usuario' 
AND password='$password'";

$result = $conn->query($sql);

if($result->num_rows > 0){

$_SESSION["usuario"] = $usuario;

echo "ok";

}else{

echo "error";

}

}

if($accion == "registro"){

$usuario = $_GET["usuario"];
$password = $_GET["password"];

$sql = "INSERT INTO usuarios(usuario,password)
VALUES('$usuario','$password')";

$conn->query($sql);

echo "Usuario creado correctamente";

}

if($accion == "recuperar"){

$usuario = $_GET["usuario"];

$sql = "SELECT password FROM usuarios
WHERE usuario='$usuario'";

$result = $conn->query($sql);

if($result->num_rows > 0){

$row = $result->fetch_assoc();

echo "Tu contraseña es: ".$row["password"];

}else{

echo "Usuario no encontrado";

}

}

?>