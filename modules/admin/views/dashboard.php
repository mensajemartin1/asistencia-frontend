<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);

$title     = 'Dashboard — ITSZ Admin';
$dataPage  = 'admin-dashboard';
$activeNav = 'dashboard';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <!-- Topbar -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Panel de Administración</h1>
        <p class="text-text-muted text-sm mt-0.5">
          <?= date('l, d \d\e F \d\e Y') ?>
        </p>
      </div>
      <span class="badge badge-primary">Admin</span>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
      <div class="stat-card border-l-primary">
        <p class="stat-label">Alumnos</p>
        <p class="stat-value text-primary" id="statAlumnos">—</p>
        <p class="stat-sub">registrados</p>
      </div>
      <div class="stat-card border-l-[#7c3aed]">
        <p class="stat-label">Docentes</p>
        <p class="stat-value text-[#7c3aed]" id="statDocentes">—</p>
        <p class="stat-sub">activos</p>
      </div>
      <div class="stat-card border-l-warning">
        <p class="stat-label">Grupos</p>
        <p class="stat-value text-warning" id="statGrupos">—</p>
        <p class="stat-sub">registrados</p>
      </div>
      <div class="stat-card border-l-[#0891b2]">
        <p class="stat-label">Materias</p>
        <p class="stat-value text-[#0891b2]" id="statMaterias">—</p>
        <p class="stat-sub">activas</p>
      </div>
      <div class="stat-card border-l-success">
        <p class="stat-label">Asistencias hoy</p>
        <p class="stat-value text-success" id="statHoy">—</p>
        <p class="stat-sub">presentes</p>
      </div>
    </div>

    <!-- Charts -->
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
      <div class="card">
        <h2 class="font-semibold text-text mb-4">Asistencia — últimos 7 días</h2>
        <div style="height:220px">
          <canvas id="chartSemanal"></canvas>
        </div>
      </div>
      <div class="card">
        <h2 class="font-semibold text-text mb-4">% Asistencia por grupo (últimos 30 días)</h2>
        <div style="height:220px">
          <canvas id="chartGrupos"></canvas>
        </div>
      </div>
    </div>

    <!-- Accesos rápidos -->
    <h2 class="font-serif text-lg font-bold text-primary-dark mb-4">Accesos rápidos</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php
      $accesos = [
        ['/modules/admin/views/usuarios.php', '#1e40af', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'Usuarios', 'Gestionar cuentas y roles'],
        ['/modules/admin/views/grupos.php',   '#0891b2', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'Grupos', 'Crear y administrar grupos'],
        ['/modules/admin/views/materias.php', '#7c3aed', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'Materias', 'Asignar materias a grupos'],
        ['/modules/control_escolar/views/reportes.php', '#16a34a', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'Reportes', 'Generar PDF y Excel'],
      ];
      foreach ($accesos as [$href, $color, $path, $titulo, $desc]): ?>
        <a href="<?= $href ?>" class="card card-hover flex gap-3 items-start group">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 transition-opacity"
               style="background:<?= $color ?>20">
            <svg class="w-5 h-5" style="color:<?= $color ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $path ?>"/>
            </svg>
          </div>
          <div>
            <p class="font-semibold text-sm text-text"><?= $titulo ?></p>
            <p class="text-xs text-text-muted mt-0.5"><?= $desc ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
</div><!-- /app-main (inner padding closed by app_footer) -->

<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
