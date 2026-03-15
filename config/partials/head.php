<?php
/**
 * Partial: <head> compartido
 * Variables esperadas:
 *   $title      — título de la página (requerido)
 *   $bodyClass  — clases extra para <body> (opcional)
 *   $dataPage   — valor de data-page para el JS (opcional)
 */
$title     ??= 'Sistema de Asistencia — ITSZ';
$bodyClass ??= '';
$dataPage  ??= '';

$manifest = [];
$manifestPath = __DIR__ . '/../../public/assets/bundle/.vite/manifest.json';
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}
$cssFile = $manifest['src/js/main.js']['css'][0] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet" />

  <?php if ($cssFile): ?>
    <link rel="stylesheet" href="/public/assets/bundle/<?= htmlspecialchars($cssFile) ?>">
  <?php endif; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>" <?= $dataPage ? 'data-page="' . htmlspecialchars($dataPage) . '"' : '' ?>>
