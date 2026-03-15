<?php

require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$accion = $_GET["accion"] ?? '';

$sql    = "";
$params = [];
$types  = "";

$base = "SELECT
    al.numero_control,
    al.nombre,
    al.grupo,
    al.foto,
    m.nombre AS materia,
    a.fecha
FROM Asistencias a
INNER JOIN Alumnos al  ON a.idAlumno  = al.id
INNER JOIN Materias m  ON a.idMateria = m.id";

if ($accion == "historial") {

    $sql = $base . " ORDER BY a.fecha DESC";

} elseif ($accion == "porAlumno") {

    $alumno_id = (int) ($_GET["alumno_id"] ?? 0);
    $sql       = $base . " WHERE al.id = ? ORDER BY a.fecha DESC";
    $types     = "i";
    $params[]  = $alumno_id;

} elseif ($accion == "porGrupo") {

    $grupo    = $_GET["grupo"] ?? '';
    $sql      = $base . " WHERE al.grupo = ? ORDER BY a.fecha DESC";
    $types    = "s";
    $params[] = $grupo;

} elseif ($accion == "porMateria") {

    $materia_id = (int) ($_GET["materia_id"] ?? 0);
    $sql        = $base . " WHERE m.id = ? ORDER BY a.fecha DESC";
    $types      = "i";
    $params[]   = $materia_id;

} elseif ($accion == "porFecha") {

    $fecha    = $_GET["fecha"] ?? '';
    $sql      = $base . " WHERE a.fecha = ? ORDER BY a.hora ASC";
    $types    = "s";
    $params[] = $fecha;

} else {

    echo "<tr><td colspan='6'>Acción no válida</td></tr>";
    exit;

}

$stmt = $conn->prepare($sql);

if ($types && count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {

    while ($fila = $result->fetch_assoc()) {
        $foto = $fila['foto'] ? "<img src='../assets/img/" . htmlspecialchars($fila['foto']) . "' width='45'>" : "-";
        echo "<tr>
            <td>{$foto}</td>
            <td>" . htmlspecialchars($fila['numero_control']) . "</td>
            <td>" . htmlspecialchars($fila['nombre'])         . "</td>
            <td>" . htmlspecialchars($fila['grupo'])          . "</td>
            <td>" . htmlspecialchars($fila['materia'])        . "</td>
            <td>" . htmlspecialchars($fila['fecha'])          . "</td>
        </tr>";
    }

} else {

    echo "<tr><td colspan='6'>No hay registros</td></tr>";

}

$stmt->close();
