<?php
session_start();
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
require_once __DIR__ . '/../../../config/database.php';
requireRole(['estudiante']);

header('Content-Type: application/json; charset=utf-8');

$conn   = getConnection();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

// ── CARRERAS disponibles (donde hay grupos activos) ───────────────────────────
if ($accion === 'carreras') {
    $rows = $conn->query(
        "SELECT DISTINCT carrera FROM Grupos WHERE activo=1 AND carrera IS NOT NULL AND carrera != '' ORDER BY carrera"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode(array_column($rows, 'carrera'));
    exit;
}

// ── SEMESTRES para una carrera ────────────────────────────────────────────────
if ($accion === 'semestres') {
    $carrera = trim($_GET['carrera'] ?? '');
    if (!$carrera) { echo json_encode([]); exit; }
    $st = $conn->prepare(
        "SELECT DISTINCT semestre FROM Grupos WHERE activo=1 AND carrera=? AND semestre IS NOT NULL ORDER BY semestre"
    );
    $st->bind_param('s', $carrera);
    $st->execute();
    $rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    $st->close();
    echo json_encode(array_column($rows, 'semestre'));
    exit;
}

// ── GRUPOS para carrera + semestre ────────────────────────────────────────────
if ($accion === 'grupos') {
    $carrera  = trim($_GET['carrera']  ?? '');
    $semestre = trim($_GET['semestre'] ?? '');
    if (!$carrera || !$semestre) { echo json_encode([]); exit; }
    $st = $conn->prepare(
        "SELECT id, nombre, campus,
                (SELECT COUNT(*) FROM Alumnos a WHERE a.idGrupo = g.id AND a.activo=1) AS inscritos
         FROM Grupos g
         WHERE activo=1 AND carrera=? AND semestre=?
         ORDER BY campus, nombre"
    );
    $st->bind_param('ss', $carrera, $semestre);
    $st->execute();
    echo json_encode($st->get_result()->fetch_all(MYSQLI_ASSOC));
    $st->close();
    exit;
}

// ── INSCRIBIR: asignar grupo + guardar perfil ─────────────────────────────────
if ($accion === 'inscribir') {
    $idGrupo   = (int)($_POST['idGrupo'] ?? 0);
    $idUsuario = (int)$_SESSION['user_id'];

    if (!$idGrupo) { echo json_encode(['error' => 'Selecciona un grupo']); exit; }

    // Verificar que el grupo existe y está activo
    $chk = $conn->prepare("SELECT id FROM Grupos WHERE id=? AND activo=1 LIMIT 1");
    $chk->bind_param('i', $idGrupo);
    $chk->execute();
    if (!$chk->get_result()->num_rows) {
        echo json_encode(['error' => 'Grupo no válido']); exit;
    }
    $chk->close();

    $nombre    = $_SESSION['nombre'] ?? '';
    $correo    = $_SESSION['correo'] ?? '';
    $matricula = strtolower(explode('@', $correo)[0]);
    $nickname  = trim($_POST['nickname'] ?? '');
    $avatar    = trim($_POST['avatar']   ?? 'default');
    $prefs     = json_encode(json_decode($_POST['preferencias'] ?? '[]', true));

    $st = $conn->prepare(
        "INSERT INTO Alumnos (idGrupo, idUsuario, nombre, matricula, nickname, avatar, preferencias, onboarding_ok)
         VALUES (?, ?, ?, ?, ?, ?, ?, 1)
         ON DUPLICATE KEY UPDATE
           idGrupo       = VALUES(idGrupo),
           nickname      = VALUES(nickname),
           avatar        = VALUES(avatar),
           preferencias  = VALUES(preferencias),
           onboarding_ok = 1"
    );
    $st->bind_param('iisssss', $idGrupo, $idUsuario, $nombre, $matricula, $nickname, $avatar, $prefs);
    if ($st->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
    $st->close();
    exit;
}

// ── COMPLETAR PERFIL: para alumnos que ya tienen grupo (asignado por admin) ───
if ($accion === 'completar_perfil') {
    $idUsuario = (int)$_SESSION['user_id'];
    $nickname  = trim($_POST['nickname'] ?? '');
    $avatar    = trim($_POST['avatar']   ?? 'default');
    $prefs     = json_encode(json_decode($_POST['preferencias'] ?? '[]', true));

    $st = $conn->prepare(
        "UPDATE Alumnos SET nickname=?, avatar=?, preferencias=?, onboarding_ok=1 WHERE idUsuario=?"
    );
    $st->bind_param('sssi', $nickname, $avatar, $prefs, $idUsuario);
    if ($st->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
    $st->close();
    exit;
}

echo json_encode(['error' => 'Acción no reconocida']);
