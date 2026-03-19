<?php

require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$grupo = $_GET["grupo"] ?? '';

$stmt = $conn->prepare(
    "SELECT al.nombre, COUNT(a.id) AS total
     FROM Asistencias a
     INNER JOIN Alumnos al ON a.idAlumno = al.id
    LEFT JOIN Grupos g ON al.idGrupo = g.id
    WHERE g.nombre = ?
     GROUP BY al.nombre"
);
$stmt->bind_param("s", $grupo);
$stmt->execute();
$result = $stmt->get_result();

$datos = [];
while ($fila = $result->fetch_assoc()) {
    $datos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($datos);

$stmt->close();
