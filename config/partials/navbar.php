<?php
/**
 * Partial: navbar institucional
 * Variables esperadas:
 *   $activeNav — 'login' | 'attendance' | 'queries' | 'reports' (opcional)
 */
$activeNav = $activeNav ?? '';

// Ruta relativa al logo desde cualquier vista
// Se resuelve con la variable $logoPath si se define antes del include
$logoSrc = $logoPath ?? '/public/assets/img/logo-itsz.svg';
?>
<nav class="navbar">
  <a href="/" class="navbar-brand">
    <img src="<?= $logoSrc ?>" alt="ITSZ Logo" class="w-10 h-10 object-contain" />
    <div class="leading-tight">
      <strong class="block text-sm font-bold text-primary-dark">Instituto Tecnológico Superior</strong>
      <span class="text-xs font-normal text-accent">Zongolica</span>
    </div>
  </a>

  <ul class="navbar-links hidden sm:flex">
    <li>
      <a href="/modules/auth/views/login.php"
         class="<?= $activeNav === 'login' ? 'text-primary' : '' ?>">
        Iniciar sesión
      </a>
    </li>
    <li>
      <a href="/modules/attendance/views/index.php"
         class="<?= $activeNav === 'attendance' ? 'text-primary' : '' ?>">
        Asistencia
      </a>
    </li>
    <li>
      <a href="/modules/queries/views/consultasView.php"
         class="<?= $activeNav === 'queries' ? 'text-primary' : '' ?>">
        Consultas
      </a>
    </li>
    <li>
      <a href="/modules/reports/generar_reporte.php"
         class="<?= $activeNav === 'reports' ? 'text-primary' : '' ?>">
        Reportes
      </a>
    </li>
  </ul>

  <!-- Mobile: solo logo visible, sin menú hamburguesa por ahora -->
</nav>
