<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','control_escolar']);

$title     = 'Dashboard — Control Escolar ITSZ';
$dataPage  = 'ce-dashboard';
$activeNav = 'dashboard';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="mb-8">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">Control Escolar</h1>
      <p class="text-text-muted text-sm mt-0.5"><?= date('l, d \d\e F \d\e Y') ?></p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <?php
      require_once __DIR__ . '/../../../config/database.php';
      $conn = getConnection();
      $stats = [
        ['Alumnos',          'text-primary',   "SELECT COUNT(*) FROM Alumnos WHERE activo=1"],
        ['Grupos activos',   'text-[#0891b2]', "SELECT COUNT(*) FROM Grupos WHERE activo=1"],
        ['Materias',         'text-[#7c3aed]', "SELECT COUNT(*) FROM Materias WHERE activa=1"],
        ['Asistencias hoy',  'text-success',   "SELECT COUNT(*) FROM Asistencias WHERE fecha=CURDATE()"],
      ];
      foreach ($stats as [$label, $color, $sql]):
          $val = (int)$conn->query($sql)->fetch_row()[0];
      ?>
        <div class="stat-card border-l-4 <?= str_replace('text-', 'border-l-', $color) ?>">
          <p class="stat-label"><?= $label ?></p>
          <p class="stat-value <?= $color ?>"><?= $val ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Accesos -->
    <div class="grid sm:grid-cols-2 gap-4">
      <a href="/modules/control_escolar/views/consultas.php"
         class="card card-hover flex gap-4 items-start group">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-text">Consultas avanzadas</p>
          <p class="text-sm text-text-muted mt-0.5">Filtra por alumno, grupo, materia, docente y fechas</p>
        </div>
      </a>
      <a href="/modules/control_escolar/views/reportes.php"
         class="card card-hover flex gap-4 items-start group">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-text">Reportes PDF y Excel</p>
          <p class="text-sm text-text-muted mt-0.5">Genera reportes institucionales por grupo o alumno</p>
        </div>
      </a>
    </div>

  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
