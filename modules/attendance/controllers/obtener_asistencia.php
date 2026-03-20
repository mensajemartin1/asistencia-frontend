<?php

require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$sql = "SELECT
    a.id,
    al.matricula,
    al.nombre AS alumno,
    m.nombre  AS materia,
    a.estado,
    a.fecha,
    a.hora
FROM Asistencias a
INNER JOIN Alumnos al        ON a.idAlumno       = al.id
INNER JOIN GruposMaterias gm ON a.idGrupoMateria = gm.id
INNER JOIN Materias m        ON gm.idMateria     = m.id
ORDER BY a.id DESC
LIMIT 200";

$result = $conn->query($sql) or die($conn->error);

while ($row = $result->fetch_assoc()) {
    $estado = ucfirst((string)$row['estado']);
    echo "<tr>
        <td>" . (int)$row['id'] . "</td>
        <td>" . htmlspecialchars($row['matricula']) . "</td>
        <td>" . htmlspecialchars($row['alumno'])    . "</td>
        <td>" . htmlspecialchars($row['materia'])   . "</td>
        <td>" . htmlspecialchars($estado) . "</td>
        <td>" . htmlspecialchars((string)$row['fecha']) . "</td>
        <td>" . htmlspecialchars((string)$row['hora'])  . "</td>
    </tr>";
}
