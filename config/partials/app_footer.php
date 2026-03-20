<?php
/**
 * Footer para vistas con sidebar (sin footer institucional).
 * Cierra .app-main, carga el JS compilado y cierra body/html.
 */
$manifest = [];
$manifestPath = __DIR__ . '/../../public/assets/bundle/.vite/manifest.json';
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}
$jsFile = $manifest['src/js/main.js']['file'] ?? null;
?>
    </div><!-- /app-main inner padding -->
  </div><!-- /app-main -->
</div><!-- /app-layout -->

<?php if ($jsFile): ?>
  <script type="module" src="/public/assets/bundle/<?= $jsFile ?>"></script>
<?php endif; ?>
</body>
</html>
