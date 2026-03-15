<?php
/**
 * Carga las variables del archivo .env en la raíz del proyecto.
 * Compatible con PHP 7.4+
 */

(function () {
    $envFile = dirname(__DIR__) . '/.env';
    if (!file_exists($envFile)) return;

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;

        $parts = explode('=', $line, 2);
        if (count($parts) < 2) continue;

        $key   = trim($parts[0]);
        $value = trim($parts[1]);

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
})();

function env(string $key, $default = null)
{
    $val = $_ENV[$key] ?? getenv($key);
    return ($val !== false && $val !== null && $val !== '') ? $val : $default;
}
