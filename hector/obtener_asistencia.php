<?php

include "database.php";

$sql = "SELECT 

Asistencias.id,
Alumnos.matricula,
Alumnos.nombre AS alumno,
Materias.nombre AS materia,
Asistencias.estado,
Asistencias.fecha,
Asistencias.hora

FROM Asistencias

INNER JOIN Alumnos
ON Asistencias.idAlumno = Alumnos.id

INNER JOIN Materias
ON Asistencias.idMateria = Materias.id

ORDER BY Asistencias.id DESC";

$result = $conn->query($sql) or die($conn->error);

while($row = $result->fetch_assoc()){

echo "<tr>

<td>".$row['id']."</td>
<td>".$row['matricula']."</td>
<td>".$row['alumno']."</td>
<td>".$row['materia']."</td>
<td>".$row['estado']."</td>
<td>".$row['fecha']."</td>
<td>".$row['hora']."</td>

</tr>";

}

?>
