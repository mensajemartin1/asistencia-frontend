<?php
require_once __DIR__ . '/../../../config/auth_check.php';
$title     = 'Consultas — ITSZ';
$dataPage  = 'queries';
$activeNav = 'queries';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="page flex flex-col">

  <?php require_once __DIR__ . '/../../../config/partials/navbar.php'; ?>

  <main class="page-content-wide flex-1">

    <div class="mb-6">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">Consultas de Asistencia</h1>
      <p class="text-text-muted text-sm mt-1">Filtra registros por alumno, grupo, materia o fecha.</p>
    </div>

    <!-- ── Filtros ─────────────────────────────────────────────────── -->
    <div class="card mb-6">
      <h2 class="font-semibold text-text mb-4 text-sm uppercase tracking-wide text-text-muted">Filtros</h2>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <div class="form-group mb-0">
          <label class="label">Por alumno (ID)</label>
          <div class="flex gap-2">
            <input type="text" id="alumno_id" class="input" placeholder="Ej: 12" />
            <button id="btnAlumno" class="btn-primary px-4 shrink-0">Buscar</button>
          </div>
        </div>

        <div class="form-group mb-0">
          <label class="label">Por grupo</label>
          <div class="flex gap-2">
            <input type="text" id="grupo" class="input" placeholder="Ej: 3A" />
            <button id="btnGrupo" class="btn-primary px-4 shrink-0">Buscar</button>
          </div>
        </div>

        <div class="form-group mb-0">
          <label class="label">Por materia</label>
          <div class="flex gap-2">
            <select id="materia_id" class="input">
              <option value="">Seleccionar...</option>
            </select>
            <button id="btnMateria" class="btn-primary px-4 shrink-0">Ver</button>
          </div>
        </div>

        <div class="form-group mb-0">
          <label class="label">Por fecha</label>
          <div class="flex gap-2">
            <input type="date" id="fecha" class="input" />
            <button id="btnFecha" class="btn-primary px-4 shrink-0">Ver</button>
          </div>
        </div>

      </div>

      <div class="pt-4 border-t border-border">
        <button id="btnHistorial" class="btn-secondary text-sm">
          Ver historial completo
        </button>
      </div>
    </div>

    <!-- ── Resultados ─────────────────────────────────────────────── -->
    <div class="grid lg:grid-cols-3 gap-6">

      <!-- Tabla -->
      <div class="lg:col-span-2">
        <div class="card p-0 overflow-hidden">
          <div class="px-5 py-4 border-b border-border">
            <h2 class="font-semibold text-text">Resultados</h2>
          </div>
          <div class="table-wrap border-0 rounded-none">
            <table class="table">
              <thead>
                <tr>
                  <th>Foto</th>
                  <th>No. Control</th>
                  <th>Nombre</th>
                  <th>Grupo</th>
                  <th>Materia</th>
                  <th>Fecha</th>
                </tr>
              </thead>
              <tbody id="tabla">
                <tr>
                  <td colspan="6" class="text-center py-10 text-text-muted text-sm">
                    Usa un filtro para consultar registros.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Gráfica -->
      <div class="lg:col-span-1">
        <div class="card h-full flex flex-col">
          <h2 class="font-semibold text-text mb-4">Asistencias por grupo</h2>
          <p class="text-xs text-text-muted mb-4">Consulta por grupo para ver la gráfica.</p>
          <div class="flex-1 flex items-center justify-center">
            <canvas id="graficaGrupo" class="max-w-full"></canvas>
          </div>
        </div>
      </div>

    </div>

  </main>

  <?php require_once __DIR__ . '/../../../config/partials/footer.php'; ?>

</div>
