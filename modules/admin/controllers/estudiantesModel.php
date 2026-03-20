<?php
session_start();
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
require_once __DIR__ . '/../../../config/database.php';
requireRole(['admin']);

header('Content-Type: application/json; charset=utf-8');

$conn   = getConnection();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

// ── LISTA ──────────────────────────────────────────────────────────────────────
if ($accion === 'lista') {
    $campus  = trim($_GET['campus']  ?? '');
    $idGrupo = (int)($_GET['idGrupo'] ?? 0);
    $buscar  = trim($_GET['buscar']  ?? '');

    $where = ["u.rol = 'estudiante'"];
    $types = '';
    $vals  = [];

    if ($campus)  { $where[] = 'u.campus = ?';  $types .= 's'; $vals[] = $campus; }
    if ($idGrupo) { $where[] = 'a.idGrupo = ?'; $types .= 'i'; $vals[] = $idGrupo; }
    if ($buscar)  {
        $where[] = '(u.nombre LIKE ? OR u.correo LIKE ? OR a.matricula LIKE ?)';
        $types .= 'sss';
        $like = "%$buscar%";
        $vals[] = $like; $vals[] = $like; $vals[] = $like;
    }

    $sql = "SELECT u.id AS idUsuario, u.nombre, u.correo, u.campus,
                   a.id AS idAlumno, a.matricula, a.idGrupo,
                   g.nombre AS grupo
            FROM Usuarios u
            LEFT JOIN Alumnos a ON a.idUsuario = u.id
            LEFT JOIN Grupos g  ON g.id = a.idGrupo
            WHERE " . implode(' AND ', $where) .
           " ORDER BY u.nombre";

    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$vals);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($rows);
    exit;
}

// ── GRUPOS (para select) ───────────────────────────────────────────────────────
if ($accion === 'grupos') {
    $rows = $conn->query(
        "SELECT id, nombre, campus FROM Grupos WHERE activo = 1 ORDER BY campus, nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

// ── ASIGNAR GRUPO ─────────────────────────────────────────────────────────────
if ($accion === 'asignar') {
    $idAlumno = (int)($_POST['idAlumno'] ?? 0);
    $idGrupo  = (int)($_POST['idGrupo']  ?? 0);

    if (!$idAlumno || !$idGrupo) {
        echo json_encode(['error' => 'Datos incompletos']); exit;
    }

    $stmt = $conn->prepare("UPDATE Alumnos SET idGrupo = ? WHERE id = ?");
    $stmt->bind_param('ii', $idGrupo, $idAlumno);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
    $stmt->close();
    exit;
}

echo json_encode(['error' => 'Acción no reconocida']);
