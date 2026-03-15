<?php
/**
 * Middleware de autenticación.
 * Incluir al inicio de cualquier vista protegida.
 * Redirige al landing si no hay sesión activa.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit;
}
