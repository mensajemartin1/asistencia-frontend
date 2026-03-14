<?php

include "db.php";

$accion = $_GET["accion"];

if ($accion == "historial") {

$sql = "SELECT 
al.numero_control,
al.nombre,
al.grupo,
al.foto,
m.nombre AS materia,
a.fecha,

FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
INNER JOIN materias m ON a.materia_id = m.id
ORDER BY a.fecha DESC";

}

elseif ($accion == "porAlumno") {

$alumno_id = $_GET["alumno_id"];

$sql = "SELECT 
al.numero_control,
al.nombre,
al.grupo,
al.foto,
m.nombre AS materia,
a.fecha,

FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
INNER JOIN materias m ON a.materia_id = m.id
WHERE al.id = $alumno_id
ORDER BY a.fecha DESC";

}

elseif ($accion == "porGrupo") {

$grupo = $_GET["grupo"];

$sql = "SELECT 
al.numero_control,
al.nombre,
al.grupo,
al.foto,
m.nombre AS materia,
a.fecha,

FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
INNER JOIN materias m ON a.materia_id = m.id
WHERE al.grupo = '$grupo'
ORDER BY a.fecha DESC";

}

elseif ($accion == "porMateria") {

$materia_id = $_GET["materia_id"];

$sql = "SELECT 
al.numero_control,
al.nombre,
al.grupo,
al.foto,
m.nombre AS materia,
a.fecha

FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
INNER JOIN materias m ON a.materia_id = m.id
WHERE m.id = $materia_id
ORDER BY a.fecha DESC";

}

elseif ($accion == "porFecha") {

$fecha = $_GET["fecha"];

$sql = "SELECT 
al.numero_control,
al.nombre,
al.grupo,
al.foto,
m.nombre AS materia,
a.fecha

FROM asistencias a
INNER JOIN alumnos al ON a.alumno_id = al.id
INNER JOIN materias m ON a.materia_id = m.id
WHERE a.fecha = '$fecha'
ORDER BY a.hora ASC";

}

$result = $conn->query($sql);

if($result && $result->num_rows > 0){

foreach($result as $fila){

echo "<tr>

<td><img src='img/".$fila['foto']."' width='45'></td>
<td>".$fila['numero_control']."</td>
<td>".$fila['nombre']."</td>
<td>".$fila['grupo']."</td>
<td>".$fila['materia']."</td>
<td>".$fila['fecha']."</td>

</tr>";

}

}else{

echo "<tr><td colspan='7'>No hay registros</td></tr>";

}

?>