<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn   = getConnection();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// ── LISTAR ──────────────────────────────────────────────────────────────────
if ($accion === 'lista') {
    $q   = '%' . ($_GET['q'] ?? '') . '%';
    $rol = $_GET['rol'] ?? '';

    $sql  = "SELECT id, nombre, correo, rol, estado, campus, created_at
             FROM Usuarios WHERE nombre LIKE ? OR correo LIKE ?";
    $types = 'ss';
    $params = [$q, $q];

    if ($rol) {
        $sql .= " AND rol = ?";
        $types .= 's';
        $params[] = $rol;
    }

    $sql .= " ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// ── CREAR ────────────────────────────────────────────────────────────────────
if ($accion === 'crear') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $correo   = strtolower(trim($_POST['correo']   ?? ''));
    $password = $_POST['password'] ?? '';
    $rol      = $_POST['rol']      ?? 'estudiante';
    $campus   = trim($_POST['campus']   ?? '');

    if (!$nombre || !$correo || !$password) {
        echo json_encode(['ok'=>false,'msg'=>'Campos requeridos vacíos']); exit;
    }

    $stmt = $conn->prepare("SELECT id FROM Usuarios WHERE correo=? LIMIT 1");
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['ok'=>false,'msg'=>'El correo ya está registrado']); exit;
    }
    $stmt->close();

    $hash  = password_hash($password, PASSWORD_BCRYPT);
    $estado = 'activo';
    $stmt = $conn->prepare(
        "INSERT INTO Usuarios (nombre,correo,password,rol,estado,campus) VALUES (?,?,?,?,?,?)"
    );
    $stmt->bind_param('ssssss', $nombre, $correo, $hash, $rol, $estado, $campus);
    if ($stmt->execute()) {
        echo json_encode(['ok'=>true,'id'=>$conn->insert_id]);
    } else {
        echo json_encode(['ok'=>false,'msg'=>'Error al crear usuario']);
    }
    exit;
}

// ── EDITAR ───────────────────────────────────────────────────────────────────
if ($accion === 'editar') {
    $id     = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $campus = trim($_POST['campus'] ?? '');
    $rol    = $_POST['rol'] ?? '';
    $estado = $_POST['estado'] ?? '';

    if (!$id || !$nombre) {
        echo json_encode(['ok'=>false,'msg'=>'Datos inválidos']); exit;
    }

    $stmt = $conn->prepare(
        "UPDATE Usuarios SET nombre=?, campus=?, rol=?, estado=? WHERE id=?"
    );
    $stmt->bind_param('ssssi', $nombre, $campus, $rol, $estado, $id);
    echo json_encode(['ok' => $stmt->execute()]);
    exit;
}

// ── CAMBIAR ROL ──────────────────────────────────────────────────────────────
if ($accion === 'cambiar_rol') {
    $id  = (int)($_POST['id']  ?? 0);
    $rol = $_POST['rol'] ?? '';
    if (!$id || !$rol) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare("UPDATE Usuarios SET rol=? WHERE id=?");
    $stmt->bind_param('si', $rol, $id);
    echo json_encode(['ok' => $stmt->execute()]);
    exit;
}

// ── TOGGLE ESTADO ────────────────────────────────────────────────────────────
if ($accion === 'toggle_estado') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['ok'=>false]); exit; }
    // No desactivar al propio admin
    if ($id === (int)$_SESSION['user_id']) {
        echo json_encode(['ok'=>false,'msg'=>'No puedes desactivar tu propia cuenta']); exit;
    }
    $stmt = $conn->prepare(
        "UPDATE Usuarios SET estado = IF(estado='activo','rechazado','activo') WHERE id=?"
    );
    $stmt->bind_param('i', $id);
    $stmt->execute();
    // Devolver nuevo estado
    $r = $conn->query("SELECT estado FROM Usuarios WHERE id={$id}");
    $row = $r->fetch_assoc();
    echo json_encode(['ok'=>true,'estado'=>$row['estado']]);
    exit;
}

// ── ELIMINAR ─────────────────────────────────────────────────────────────────
if ($accion === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id || $id === (int)$_SESSION['user_id']) {
        echo json_encode(['ok'=>false,'msg'=>'No puedes eliminarte a ti mismo']); exit;
    }
    $stmt = $conn->prepare("DELETE FROM Usuarios WHERE id=?");
    $stmt->bind_param('i', $id);
    echo json_encode(['ok' => $stmt->execute()]);
    exit;
}

echo json_encode(['ok'=>false,'msg'=>'Acción inválida']);
