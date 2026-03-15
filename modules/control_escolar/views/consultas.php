<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','control_escolar']);

$title     = 'Consultas — ITSZ';
$dataPage  = 'ce-consultas';
$activeNav = 'consultas';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Consultas de Asistencia</h1>
        <p class="text-text-muted text-sm mt-0.5">Búsqueda avanzada con filtros múltiples</p>
      </div>
      <a id="btnExportarCE" href="#" class="btn-secondary text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Exportar CSV
      </a>
    </div>

    <!-- Filtros -->
    <div class="card mb-5 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
      <div>
        <label class="label text-xs">Alumno (nombre o matrícula)</label>
        <input type="text" id="ceAlumno" class="input text-sm" placeholder="Buscar alumno…" />
      </div>
      <div>
        <label class="label text-xs">Grupo</label>
        <select id="ceGrupo" class="input text-sm">
          <option value="">Todos</option>
        </select>
      </div>
      <div>
        <label class="label text-xs">Materia</label>
        <select id="ceMateria" class="input text-sm">
          <option value="">Todas</option>
        </select>
      </div>
      <div>
        <label class="label text-xs">Docente</label>
        <select id="ceDocente" class="input text-sm">
          <option value="">Todos</option>
        </select>
      </div>
      <div>
        <label class="label text-xs">Estado</label>
        <select id="ceEstado" class="input text-sm">
          <option value="">Todos</option>
          <option value="presente">Presente</option>
          <option value="falta">Falta</option>
          <option value="retardo">Retardo</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="label text-xs">Desde</label>
          <input type="date" id="ceFechaIni" class="input text-sm" value="<?= date('Y-m-01') ?>" />
        </div>
        <div>
          <label class="label text-xs">Hasta</label>
          <input type="date" id="ceFechaFin" class="input text-sm" value="<?= date('Y-m-d') ?>" />
        </div>
      </div>
      <div class="sm:col-span-2 lg:col-span-3 flex justify-end">
        <button id="btnBuscarCE" class="btn-primary px-8">Buscar</button>
      </div>
    </div>

    <!-- Gráfica + Tabla -->
    <div class="grid lg:grid-cols-3 gap-6">

      <div class="lg:col-span-2 table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Alumno</th>
              <th>Matrícula</th>
              <th>Grupo</th>
              <th>Materia</th>
              <th>Docente</th>
              <th>Estado</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody id="tablaCE">
            <tr><td colspan="7" class="text-center text-text-muted py-10">Aplica filtros para buscar</td></tr>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h2 class="font-semibold text-text mb-4 text-sm">Asistencia por día</h2>
        <div style="height:280px">
          <canvas id="chartCE"></canvas>
        </div>
      </div>

    </div>

  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
