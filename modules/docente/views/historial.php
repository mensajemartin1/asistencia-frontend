<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);

$title     = 'Historial de Asistencia — ITSZ';
$dataPage  = 'docente-historial';
$activeNav = 'historial-docente';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Historial de Asistencia</h1>
        <p class="text-text-muted text-sm mt-0.5">Consulta y exporta los registros de tus grupos</p>
      </div>
      <a id="btnExportarCSV" href="#" class="btn-secondary text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Exportar CSV
      </a>
    </div>

    <!-- Filtros -->
    <div class="card mb-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <div>
        <label class="label text-xs">Grupo / Materia</label>
        <select id="filtroGM" class="input text-sm">
          <option value="">Todos mis grupos</option>
        </select>
      </div>
      <div>
        <label class="label text-xs">Estado</label>
        <select id="filtroEstado" class="input text-sm">
          <option value="">Todos</option>
          <option value="presente">Presente</option>
          <option value="falta">Falta</option>
          <option value="retardo">Retardo</option>
        </select>
      </div>
      <div>
        <label class="label text-xs">Desde</label>
        <input type="date" id="filtroFechaIni" class="input text-sm"
               value="<?= date('Y-m-01') ?>" />
      </div>
      <div>
        <label class="label text-xs">Hasta</label>
        <input type="date" id="filtroFechaFin" class="input text-sm"
               value="<?= date('Y-m-d') ?>" />
      </div>
      <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
        <button id="btnBuscarHistorial" class="btn-primary px-6">Buscar</button>
      </div>
    </div>

    <!-- Resultados -->
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Alumno</th>
            <th>Matrícula</th>
            <th>Grupo</th>
            <th>Materia</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Hora</th>
          </tr>
        </thead>
        <tbody id="tablaHistorial">
          <tr><td colspan="7" class="text-center text-text-muted py-8">Aplica filtros y presiona Buscar</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
