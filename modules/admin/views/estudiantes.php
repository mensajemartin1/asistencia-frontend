<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);

$title     = 'Estudiantes — ITSZ Admin';
$dataPage  = 'admin-estudiantes';
$activeNav = 'estudiantes';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Estudiantes</h1>
        <p class="text-text-muted text-sm mt-0.5">Asigna cada alumno a su grupo académico</p>
      </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-5 flex flex-wrap items-end gap-3">
      <div class="flex-1 min-w-[180px]">
        <label class="label">Campus</label>
        <select id="filtroCampus" class="input">
          <option value="">Todos los campus</option>
          <?php foreach(['Zongolica','Nogales','Tezonapa','Tehuipango','Tequila','Cuichapa','Acultzinapa'] as $c): ?>
            <option value="<?= $c ?>"><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex-1 min-w-[160px]">
        <label class="label">Grupo</label>
        <select id="filtroGrupo" class="input">
          <option value="">Todos los grupos</option>
        </select>
      </div>
      <div class="flex-1 min-w-[200px]">
        <label class="label">Buscar</label>
        <input type="text" id="filtroBuscar" class="input" placeholder="Nombre o matrícula…" />
      </div>
      <button id="btnBuscarEst" class="btn-primary shrink-0">Buscar</button>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo institucional</th>
            <th>Matrícula</th>
            <th>Grupo asignado</th>
            <th>Campus</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaEstudiantes">
          <tr><td colspan="6" class="text-center text-text-muted py-8">Cargando…</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal asignar grupo -->
<div id="modalAsignar" class="modal-backdrop hidden">
  <div class="modal-box">
    <div class="modal-head">
      <h2 class="font-serif font-bold text-primary-dark text-lg">Asignar grupo</h2>
      <button class="modal-close" data-modal="modalAsignar">
        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formAsignar">
      <div class="modal-body">
        <input type="hidden" id="aAlumnoId" name="idAlumno" />
        <p class="text-sm text-text-muted mb-4">
          Alumno: <strong id="aAlumnoNombre" class="text-text"></strong>
        </p>
        <div class="form-group mb-0">
          <label class="label">Grupo</label>
          <select id="aGrupo" name="idGrupo" class="input" required>
            <option value="">— Seleccionar grupo —</option>
          </select>
        </div>
        <div id="msgAsignar" class="msg mt-3"></div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost modal-close" data-modal="modalAsignar">Cancelar</button>
        <button type="submit" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
