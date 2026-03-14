<?php

include "db.php";

$grupo = $_GET["grupo"];

$sql = "SELECT al.nombre, COUNT(a.id) as total
FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
WHERE al.grupo='$grupo'
GROUP BY al.nombre";

$result = $conn->query($sql);

$datos = [];

while($fila = $result->fetch_assoc()){
$datos[] = $fila;
}

echo json_encode($datos);

?>