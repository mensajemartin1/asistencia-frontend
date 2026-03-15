<?php
/**
 * Footer + bottom nav para el layout del estudiante.
 * Variables esperadas: $activeStudentTab ('dashboard' | 'historial')
 */
$_tab = $activeStudentTab ?? 'dashboard';

$manifest = [];
$manifestPath = __DIR__ . '/../../public/assets/bundle/.vite/manifest.json';
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}
$jsFile = $manifest['src/js/main.js']['file'] ?? null;

$_i_home = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>';
$_i_hist = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
$_i_out  = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>';

function _stab(string $href, string $icon, string $label, string $key, string $active): string {
    $cls = $key === $active ? 'bottom-nav-item active' : 'bottom-nav-item';
    return "<a href=\"{$href}\" class=\"{$cls}\">{$icon}<span>{$label}</span></a>";
}
?>
  </main><!-- /student-content -->

  <!-- Bottom navigation -->
  <nav class="bottom-nav">
    <?= _stab('/modules/estudiante/views/dashboard.php', $_i_home, 'Inicio',    'dashboard',  $_tab) ?>
    <?= _stab('/modules/estudiante/views/historial.php', $_i_hist, 'Historial', 'historial',  $_tab) ?>
    <a href="/modules/auth/controllers/logout.php" class="bottom-nav-item">
      <?= $_i_out ?><span>Salir</span>
    </a>
  </nav>

</div><!-- /student-app -->

<?php if ($jsFile): ?>
  <script type="module" src="/public/assets/bundle/<?= htmlspecialchars($jsFile) ?>"></script>
<?php endif; ?>
</body>
</html>
