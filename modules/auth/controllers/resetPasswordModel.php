<?php
/**
 * Controlador: procesa el restablecimiento de contraseña.
 * POST: token, password
 */
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: text/plain; charset=utf-8');

$token    = trim($_POST['token']    ?? '');
$password = $_POST['password'] ?? '';

if (!$token || !$password) {
    echo 'error:campos_vacios';
    exit;
}

if (strlen($password) < 8) {
    echo 'error:password_corta';
    exit;
}

$conn = getConnection();

$stmt = $conn->prepare(
    "SELECT id, token_reset_expira FROM Usuarios
     WHERE token_reset = ? AND estado = 'activo' LIMIT 1"
);
if (!$stmt) { echo 'error:db'; exit; }
$stmt->bind_param('s', $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo 'error:token_invalido';
    exit;
}

if (strtotime($row['token_reset_expira']) < time()) {
    echo 'error:token_expirado';
    exit;
}

$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "UPDATE Usuarios
     SET password = ?, token_reset = NULL, token_reset_expira = NULL
     WHERE id = ?"
);
if (!$stmt) { echo 'error:db'; exit; }
$stmt->bind_param('si', $hash, $row['id']);
$stmt->execute();
$stmt->close();

echo 'ok';
