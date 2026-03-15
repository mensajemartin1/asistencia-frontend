<?php
session_start();
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['estudiante','admin']);

$title            = 'Mi Historial — ITSZ';
$dataPage         = 'estudiante-historial';
$activeStudentTab = 'historial';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<?php require_once __DIR__ . '/../../../config/partials/student_appbar.php'; ?>

  <h1 class="font-serif text-xl font-bold text-primary-dark mb-4">Mi Historial</h1>

  <!-- Filtros -->
  <div class="card mb-4 flex flex-col gap-3">
    <div>
      <label class="label text-xs">Materia</label>
      <select id="filtroMateriaEst" class="input text-sm">
        <option value="">Todas las materias</option>
      </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="label text-xs">Desde</label>
        <input type="date" id="filtroFechaIniEst" class="input text-sm" value="<?= date('Y-m-01') ?>" />
      </div>
      <div>
        <label class="label text-xs">Hasta</label>
        <input type="date" id="filtroFechaFinEst" class="input text-sm" value="<?= date('Y-m-d') ?>" />
      </div>
    </div>
    <button id="btnBuscarEst" class="btn-primary w-full">Buscar</button>
  </div>

  <!-- Resultados como cards (mejor en móvil que tabla) -->
  <div id="listaHistorial" class="flex flex-col gap-2">
    <p class="text-center text-text-muted text-sm py-8">Aplica filtros y presiona Buscar</p>
  </div>

<?php require_once __DIR__ . '/../../../config/partials/student_footer.php'; ?>
