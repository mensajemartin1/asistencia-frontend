<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "asistencia";

$conn = new mysqli($server, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión");
}

?>