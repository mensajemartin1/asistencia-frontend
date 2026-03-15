<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn   = getConnection();
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if ($accion === 'lista') {
    $rows = $conn->query(
        "SELECT g.id, g.nombre, g.carrera, g.semestre, g.campus, g.activo,
                COUNT(a.id) AS total_alumnos
         FROM Grupos g
         LEFT JOIN Alumnos a ON a.idGrupo=g.id AND a.activo=1
         GROUP BY g.id
         ORDER BY g.nombre"
    )->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($accion === 'crear') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $carrera  = trim($_POST['carrera']  ?? '');
    $semestre = (int)($_POST['semestre'] ?? 0);
    $campus   = trim($_POST['campus']   ?? '');
    if (!$nombre) { echo json_encode(['ok'=>false,'msg'=>'Nombre requerido']); exit; }
    $stmt = $conn->prepare(
        "INSERT INTO Grupos (nombre,carrera,semestre,campus) VALUES (?,?,?,?)"
    );
    $stmt->bind_param('ssis', $nombre, $carrera, $semestre, $campus);
    echo json_encode(['ok'=>$stmt->execute(),'id'=>$conn->insert_id]);
    exit;
}

if ($accion === 'editar') {
    $id       = (int)($_POST['id'] ?? 0);
    $nombre   = trim($_POST['nombre']   ?? '');
    $carrera  = trim($_POST['carrera']  ?? '');
    $semestre = (int)($_POST['semestre'] ?? 0);
    $campus   = trim($_POST['campus']   ?? '');
    if (!$id || !$nombre) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare(
        "UPDATE Grupos SET nombre=?,carrera=?,semestre=?,campus=? WHERE id=?"
    );
    $stmt->bind_param('ssisi', $nombre, $carrera, $semestre, $campus, $id);
    echo json_encode(['ok'=>$stmt->execute()]);
    exit;
}

if ($accion === 'toggle') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare("UPDATE Grupos SET activo=NOT activo WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $r   = $conn->query("SELECT activo FROM Grupos WHERE id={$id}");
    echo json_encode(['ok'=>true,'activo'=>(int)$r->fetch_row()[0]]);
    exit;
}

if ($accion === 'eliminar') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) { echo json_encode(['ok'=>false]); exit; }
    $stmt = $conn->prepare("DELETE FROM Grupos WHERE id=?");
    $stmt->bind_param('i', $id);
    echo json_encode(['ok'=>$stmt->execute()]);
    exit;
}

echo json_encode(['ok'=>false,'msg'=>'Acción inválida']);
