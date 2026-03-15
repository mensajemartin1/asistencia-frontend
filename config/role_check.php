<?php
/**
 * Middleware de roles.
 * Requiere que auth_check.php haya sido cargado antes.
 *
 * Uso:
 *   requireRole(['admin']);
 *   requireRole(['admin', 'control_escolar']);
 */
function requireRole(array $roles): void
{
    $rol = $_SESSION['rol'] ?? '';
    if (!in_array($rol, $roles, true)) {
        header('Location: /public/index.php');
        exit;
    }
}
