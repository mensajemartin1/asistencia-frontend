<?php
/**
 * Partial: footer + carga del JS compilado por Vite
 */
$manifest = [];
$manifestPath = __DIR__ . '/../../public/assets/bundle/.vite/manifest.json';
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}
$jsFile = $manifest['src/js/main.js']['file'] ?? null;
?>
<footer class="bg-primary-dark text-white py-10 px-5 mt-auto text-sm">
  <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="text-center sm:text-left">
      <p class="font-semibold">&copy; <?= date('Y') ?> Instituto Tecnológico Superior de Zongolica</p>
      <p class="text-blue-300 text-xs mt-0.5">TecNM · Sistema de Control de Asistencia</p>
    </div>
    <div class="flex items-center gap-5 text-blue-300 text-xs">
      <a href="/public/developers.php" class="hover:text-white transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Equipo de desarrollo
      </a>
      <span class="text-blue-600">·</span>
      <span>ISC 8° Semestre · 2025–2026</span>
    </div>
  </div>
</footer>

<?php if ($jsFile): ?>
  <script type="module" src="/public/assets/bundle/<?= $jsFile ?>"></script>
<?php endif; ?>
</body>
</html>
