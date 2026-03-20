<?php

/**
 * Lee el manifest.json de Vite y devuelve la URL del asset con hash.
 *
 * Uso en cualquier vista PHP:
 *   <?php require_once ROOT . '/config/assets.php'; ?>
 *   <link rel="stylesheet" href="<?= asset('assets/main.css') ?>">
 *   <script type="module" src="<?= asset('js/main.js') ?>"></script>
 */

define('ROOT', dirname(__DIR__));
define('BUNDLE_PATH', ROOT . '/public/assets/bundle');
define('BUNDLE_URL', '/assets/bundle');

function asset(string $file): string {
    static $manifest = null;

    if ($manifest === null) {
        $manifestPath = BUNDLE_PATH . '/.vite/manifest.json';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } else {
            $manifest = [];
        }
    }

    // Busca la clave con y sin prefijo src/
    $keys = ["src/{$file}", $file];
    foreach ($keys as $key) {
        if (isset($manifest[$key]['file'])) {
            return BUNDLE_URL . '/assets/' . basename($manifest[$key]['file']);
        }
    }

    // Fallback en desarrollo (Vite dev server)
    return "http://localhost:5173/src/{$file}";
}
