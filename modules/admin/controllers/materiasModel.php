<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn   = getConnection();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// ── LISTAR MATERIAS ──────────────────────────────────────────────────────────
if ($accion === 'lista') {
    $rows = $conn->query(
        "SELECT m.id, m.nombre, m.clave, m.creditos, m.activa,
                COUNT(gm.id) AS grupos_asignados
         FROM Materias m
         LEFT JOIN GruposMaterias gm ON gm.idMateria=m.id AND gm.activo=1
         GROUP BY m.id
         ORDER BY m.nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

// ── LISTAR ASIGNACIONES (GruposMaterias) ────────────────────────────────────
if ($accion === 'asignaciones') {
    $rows = $conn->query(
        "SELECT gm.id, g.nombre AS grupo, m.nombre AS materia,
                u.nombre AS docente, gm.horaInicio, gm.horaFin, gm.dias, gm.ciclo, gm.activo
         FROM GruposMaterias gm
         JOIN Grupos   g ON g.id = gm.idGrupo
         JOIN Materias m ON m.id = gm.idMateria
         JOIN Usuarios u ON u.id = gm.idDocente
         ORDER BY g.nombre, m.nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

// ── LISTAS AUXILIARES ────────────────────────────────────────────────────────
if ($accion === 'docentes') {
    $rows = $conn->query(
        "SELECT id, nombre FROM Usuarios WHERE rol='docente' AND estado='activo' ORDER BY nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($accion === 'grupos') {
    $rows = $conn->query(
        "SELECT id, nombre FROM Grupos WHERE activo=1 ORDER BY nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

// ── CREAR MATERIA ────────────────────────────────────────────────────────────
if ($accion === 'crear') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $clave    = trim($_POST['clave']    ?? '');
    $creditos = (int)($_POST['creditos'] ?? 0);
    if (!$nombre) { echo json_encode(['ok'=>false,'msg'=>'Nombre requerido']); exit; }
    $stmt = $conn->prepare("INSERT INTO Materias (nombre,clave,creditos) VALUES (?,?,?)");
    $stmt->bind_param('ssi', $nombre, $clave, $creditos);
    echo json_encode(['ok'=>$stmt->execute(),'id'=>$conn->insert_id]);
    exit;
}

// ── EDITAR MATERIA ───────────────────────────────────────────────────────────
if ($accion === 'editar') {
    $id       = (int)($_POST['id'] ?? 0);
    $nombre   = trim($_POST['nombre']   ?? '');
    $clave    = trim($_POST['clave']    ?? '');
    $creditos = (int)($_POST['creditos'] ?? 0);
    if (!$id || !$nombre) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare("UPDATE Materias SET nombre=?,clave=?,creditos=? WHERE id=?");
    $stmt->bind_param('ssii', $nombre, $clave, $creditos, $id);
    echo json_encode(['ok'=>$stmt->execute()]);
    exit;
}

// ── ASIGNAR MATERIA A GRUPO ───────────────────────────────────────────────────
if ($accion === 'asignar') {
    $idGrupo    = (int)($_POST['idGrupo']    ?? 0);
    $idMateria  = (int)($_POST['idMateria']  ?? 0);
    $idDocente  = (int)($_POST['idDocente']  ?? 0);
    $horaInicio = $_POST['horaInicio'] ?? null;
    $horaFin    = $_POST['horaFin']    ?? null;
    $dias       = trim($_POST['dias']  ?? 'LMJV');
    $ciclo      = trim($_POST['ciclo'] ?? '2026-A');

    if (!$idGrupo || !$idMateria || !$idDocente) {
        echo json_encode(['ok'=>false,'msg'=>'Faltan datos']); exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO GruposMaterias (idGrupo,idMateria,idDocente,horaInicio,horaFin,dias,ciclo)
         VALUES (?,?,?,?,?,?,?)"
    );
    $stmt->bind_param('iiissss', $idGrupo, $idMateria, $idDocente, $horaInicio, $horaFin, $dias, $ciclo);
    echo json_encode(['ok'=>$stmt->execute(),'msg'=>$conn->error ?: null]);
    exit;
}

// ── ELIMINAR ASIGNACIÓN ──────────────────────────────────────────────────────
if ($accion === 'quitar_asignacion') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare("DELETE FROM GruposMaterias WHERE id=?");
    $stmt->bind_param('i', $id);
    echo json_encode(['ok'=>$stmt->execute()]);
    exit;
}

echo json_encode(['ok'=>false,'msg'=>'Acción inválida']);
