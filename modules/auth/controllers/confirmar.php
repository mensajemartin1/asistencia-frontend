<?php
/**
 * Confirma el correo de un usuario a través del token enviado por email.
 * URL: /modules/auth/controllers/confirmar.php?token=xxxx
 */
require_once __DIR__ . '/../../../config/database.php';

$token = trim($_GET['token'] ?? '');

if (!$token) {
    redirect('error=token_invalido');
}

$conn = getConnection();

$stmt = $conn->prepare(
    "SELECT id, nombre, correo, estado, token_expira
     FROM Usuarios
     WHERE token_confirmacion = ? LIMIT 1"
);
$stmt->bind_param('s', $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    redirect('error=token_invalido');
}

if ($row['estado'] === 'activo') {
    redirect('info=ya_confirmado');
}

if (strtotime($row['token_expira']) < time()) {
    redirect('error=token_expirado');
}

// Activar cuenta
$stmt = $conn->prepare(
    "UPDATE Usuarios
     SET estado = 'activo', token_confirmacion = NULL, token_expira = NULL
     WHERE id = ?"
);
$stmt->bind_param('i', $row['id']);
$stmt->execute();
$stmt->close();

redirect('confirmado=1');

// ── Helper ────────────────────────────────────────────────────────────────────
function redirect(string $param): void
{
    header("Location: /modules/auth/views/login.php?{$param}");
    exit;
}
