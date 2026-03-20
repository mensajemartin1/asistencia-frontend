<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);

$title     = 'Mis clases — ITSZ';
$dataPage  = 'docente-dashboard';
$activeNav = 'dashboard';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="mb-8">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">
        Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>
      </h1>
      <p class="text-text-muted text-sm mt-0.5"><?= date('l, d \d\e F \d\e Y') ?></p>
    </div>

    <!-- Clase activa ahora -->
    <div id="claseActivaCard" class="card border-l-4 border-l-success mb-6 hidden">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-text-muted uppercase tracking-wide mb-1">Clase activa ahora</p>
          <p id="claseActivaNombre" class="font-bold text-xl text-text"></p>
          <p id="claseActivaGrupo" class="text-text-muted text-sm"></p>
        </div>
        <a id="btnIrAsistencia" href="/modules/docente/views/asistencia.php" class="btn-primary">
          Pasar lista →
        </a>
      </div>
    </div>
    <div id="sinClaseCard" class="card border-l-4 border-l-border mb-6">
      <p class="text-text-muted text-sm">No hay clase activa en este horario.</p>
    </div>

    <!-- Todas mis materias -->
    <h2 class="font-serif text-lg font-bold text-primary-dark mb-4">Mis grupos y materias</h2>
    <div id="gridGrupos" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div class="card text-center text-text-muted text-sm py-8">Cargando…</div>
    </div>

  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
