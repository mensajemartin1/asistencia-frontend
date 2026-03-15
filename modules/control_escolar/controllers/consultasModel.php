<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','control_escolar']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();

// Campus scope: control_escolar solo ve su propio campus; admin ve todo
$rol         = $_SESSION['rol']    ?? '';
$campusScope = ($rol === 'control_escolar') ? ($_SESSION['campus'] ?? '') : '';

$accion    = $_GET['accion']    ?? 'buscar';
$alumno    = trim($_GET['alumno']    ?? '');
$idGrupo   = (int)($_GET['idGrupo']  ?? 0);
$idMateria = (int)($_GET['idMateria'] ?? 0);
$idDocente = (int)($_GET['idDocente'] ?? 0);
$fechaIni  = $_GET['fechaIni']  ?? date('Y-m-01');
$fechaFin  = $_GET['fechaFin']  ?? date('Y-m-d');
$estado    = $_GET['estado']    ?? '';

// ── LISTAS AUXILIARES ────────────────────────────────────────────────────────
if ($accion === 'grupos') {
    $sql = "SELECT id,nombre FROM Grupos WHERE activo=1";
    if ($campusScope) $sql .= " AND campus=" . $conn->real_escape_string($campusScope) . "'";
    $sql .= " ORDER BY nombre";
    // Usar prepared si hay filtro
    if ($campusScope) {
        $st = $conn->prepare("SELECT id,nombre FROM Grupos WHERE activo=1 AND campus=? ORDER BY nombre");
        $st->bind_param('s', $campusScope);
        $st->execute();
        echo json_encode($st->get_result()->fetch_all(MYSQLI_ASSOC));
    } else {
        echo json_encode($conn->query("SELECT id,nombre FROM Grupos WHERE activo=1 ORDER BY nombre")->fetch_all(MYSQLI_ASSOC));
    }
    exit;
}
if ($accion === 'materias') {
    echo json_encode($conn->query("SELECT id,nombre FROM Materias WHERE activa=1 ORDER BY nombre")->fetch_all(MYSQLI_ASSOC));
    exit;
}
if ($accion === 'docentes') {
    echo json_encode($conn->query("SELECT id,nombre FROM Usuarios WHERE rol='docente' AND estado='activo' ORDER BY nombre")->fetch_all(MYSQLI_ASSOC));
    exit;
}

// ── BUSCAR ───────────────────────────────────────────────────────────────────
if ($accion === 'buscar') {
    $sql = "SELECT al.nombre AS alumno, al.matricula, g.nombre AS grupo,
                   m.nombre AS materia, u.nombre AS docente,
                   a.estado, a.fecha, TIME_FORMAT(a.hora,'%H:%i') AS hora
            FROM Asistencias a
            JOIN Alumnos al        ON al.id = a.idAlumno
            JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
            JOIN Grupos   g        ON g.id  = gm.idGrupo
            JOIN Materias m        ON m.id  = gm.idMateria
            JOIN Usuarios u        ON u.id  = gm.idDocente
            WHERE a.fecha BETWEEN ? AND ?";

    $types  = 'ss';
    $params = [$fechaIni, $fechaFin];

    if ($campusScope) { $sql .= " AND g.campus=?";      $types .= 's'; $params[] = $campusScope; }
    if ($alumno)      {
        $sql .= " AND (al.nombre LIKE ? OR al.matricula LIKE ?)";
        $q = "%{$alumno}%"; $types .= 'ss'; $params[] = $q; $params[] = $q;
    }
    if ($idGrupo)   { $sql .= " AND gm.idGrupo=?";   $types .= 'i'; $params[] = $idGrupo; }
    if ($idMateria) { $sql .= " AND gm.idMateria=?";  $types .= 'i'; $params[] = $idMateria; }
    if ($idDocente) { $sql .= " AND gm.idDocente=?";  $types .= 'i'; $params[] = $idDocente; }
    if ($estado)    { $sql .= " AND a.estado=?";       $types .= 's'; $params[] = $estado; }

    $sql .= " ORDER BY a.fecha DESC, al.nombre LIMIT 500";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// ── STATS PARA GRÁFICA ───────────────────────────────────────────────────────
if ($accion === 'stats_diarios') {
    if ($campusScope) {
        $stmt = $conn->prepare(
            "SELECT DATE_FORMAT(a.fecha,'%d %b') AS dia,
                    SUM(a.estado='presente') AS presentes,
                    SUM(a.estado='falta')    AS faltas
             FROM Asistencias a
             JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
             JOIN Grupos g ON g.id = gm.idGrupo
             WHERE a.fecha BETWEEN ? AND ? AND g.campus = ?
             GROUP BY a.fecha ORDER BY a.fecha"
        );
        $stmt->bind_param('sss', $fechaIni, $fechaFin, $campusScope);
    } else {
        $stmt = $conn->prepare(
            "SELECT DATE_FORMAT(fecha,'%d %b') AS dia,
                    SUM(estado='presente') AS presentes,
                    SUM(estado='falta')    AS faltas
             FROM Asistencias WHERE fecha BETWEEN ? AND ?
             GROUP BY fecha ORDER BY fecha"
        );
        $stmt->bind_param('ss', $fechaIni, $fechaFin);
    }
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

echo json_encode([]);
