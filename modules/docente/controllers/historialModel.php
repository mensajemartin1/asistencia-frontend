<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn      = getConnection();
$idDocente = (int)$_SESSION['user_id'];

$idGM      = (int)($_GET['idGM']      ?? 0);
$fechaIni  = $_GET['fechaIni'] ?? date('Y-m-01'); // primer día del mes
$fechaFin  = $_GET['fechaFin'] ?? date('Y-m-d');
$estado    = $_GET['estado']   ?? '';
$formato   = $_GET['formato']  ?? 'json';

$sql = "SELECT al.nombre, al.matricula, g.nombre AS grupo, m.nombre AS materia,
               a.estado, a.fecha, TIME_FORMAT(a.hora,'%H:%i') AS hora
        FROM Asistencias a
        JOIN Alumnos al ON al.id = a.idAlumno
        JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
        JOIN Grupos g   ON g.id  = gm.idGrupo
        JOIN Materias m ON m.id  = gm.idMateria
        WHERE gm.idDocente = ? AND a.fecha BETWEEN ? AND ?";

$types  = 'iss';
$params = [$idDocente, $fechaIni, $fechaFin];

if ($idGM) {
    $sql    .= " AND a.idGrupoMateria = ?";
    $types  .= 'i';
    $params[] = $idGM;
}
if ($estado) {
    $sql    .= " AND a.estado = ?";
    $types  .= 's';
    $params[] = $estado;
}
$sql .= " ORDER BY a.fecha DESC, al.nombre";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="historial_' . date('Ymd') . '.csv"');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM para Excel
    fputcsv($out, ['Nombre','Matrícula','Grupo','Materia','Estado','Fecha','Hora']);
    foreach ($rows as $r) fputcsv($out, $r);
    fclose($out);
    exit;
}

echo json_encode($rows);
