<?php

require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$accion = $_GET["accion"] ?? '';

if ($accion === 'materias') {
    header('Content-Type: application/json; charset=utf-8');
    $result = $conn->query("SELECT id, nombre FROM Materias WHERE activa = 1 ORDER BY nombre");
    echo json_encode($result ? $result->fetch_all(MYSQLI_ASSOC) : []);
    exit;
}

$sql    = "";
$params = [];
$types  = "";

$base = "SELECT
    al.numero_control,
    al.matricula,
    al.nombre,
    COALESCE(g.nombre, '-') AS grupo,
    al.foto,
    m.nombre AS materia,
    a.fecha
FROM Asistencias a
INNER JOIN Alumnos al        ON a.idAlumno       = al.id
INNER JOIN GruposMaterias gm ON a.idGrupoMateria = gm.id
INNER JOIN Materias m        ON gm.idMateria     = m.id
LEFT JOIN Grupos g           ON al.idGrupo       = g.id";

if ($accion == "historial") {

    $sql = $base . " ORDER BY a.fecha DESC";

} elseif ($accion == "porAlumno") {

    $alumno_id = (int) ($_GET["alumno_id"] ?? 0);
    $sql       = $base . " WHERE al.id = ? ORDER BY a.fecha DESC";
    $types     = "i";
    $params[]  = $alumno_id;

} elseif ($accion == "porGrupo") {

    $grupo    = $_GET["grupo"] ?? '';
    $sql      = $base . " WHERE g.nombre = ? ORDER BY a.fecha DESC";
    $types    = "s";
    $params[] = $grupo;

} elseif ($accion == "porMateria") {

    $materia_id = (int) ($_GET["materia_id"] ?? 0);
    $sql        = $base . " WHERE gm.idMateria = ? ORDER BY a.fecha DESC";
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

if (!$stmt) {
    echo "<tr><td colspan='6'>Error al preparar consulta</td></tr>";
    exit;
}

if ($types && count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {

    while ($fila = $result->fetch_assoc()) {
        $foto = !empty($fila['foto'])
            ? "<img src='/public/assets/img/" . htmlspecialchars($fila['foto']) . "' width='45' alt='foto'>"
            : "-";

        $numeroControl = $fila['numero_control'] ?: ($fila['matricula'] ?? '-');
        $grupo = $fila['grupo'] ?: '-';

        echo "<tr>
            <td>{$foto}</td>
            <td>" . htmlspecialchars((string)$numeroControl) . "</td>
            <td>" . htmlspecialchars($fila['nombre'])         . "</td>
            <td>" . htmlspecialchars((string)$grupo)          . "</td>
            <td>" . htmlspecialchars($fila['materia'])        . "</td>
            <td>" . htmlspecialchars($fila['fecha'])          . "</td>
        </tr>";
    }

} else {

    echo "<tr><td colspan='6'>No hay registros</td></tr>";

}

$stmt->close();
