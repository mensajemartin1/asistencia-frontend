<?php
/**
 * Router para PHP built-in server.
 * Uso: php -S localhost:3000 router.php
 *      (ejecutar desde la raíz del proyecto: asistencia-frontend/)
 */
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $uri;

// Serve static files directly (css, js, images, fonts, etc.)
if (is_file($file)) {
    return false;
}

// If it's a PHP file, execute it
if (is_file($file . '.php')) {
    require $file . '.php';
    return true;
}

// Default: let PHP handle it (serves PHP files normally)
return false;
