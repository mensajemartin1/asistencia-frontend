<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);

$title     = 'Materias — ITSZ Admin';
$dataPage  = 'admin-materias';
$activeNav = 'materias';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Materias</h1>
        <p class="text-text-muted text-sm mt-0.5">Catálogo y asignación a grupos</p>
      </div>
      <div class="flex gap-2">
        <button id="btnNuevaMateria" class="btn-primary">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Nueva materia
        </button>
        <button id="btnNuevaAsignacion" class="btn-secondary">
          Asignar a grupo
        </button>
      </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-border mb-6">
      <button class="tab-btn active px-5 py-2.5 text-sm font-semibold border-b-2 border-primary text-primary" data-tab="catalogo">
        Catálogo de materias
      </button>
      <button class="tab-btn px-5 py-2.5 text-sm font-semibold border-b-2 border-transparent text-text-muted" data-tab="asignaciones">
        Asignaciones grupo-materia
      </button>
    </div>

    <!-- Tab: Catálogo -->
    <div id="tabCatalogo">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Clave</th>
              <th>Créditos</th>
              <th>Grupos asignados</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaMaterias">
            <tr><td colspan="6" class="text-center text-text-muted py-8">Cargando…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tab: Asignaciones -->
    <div id="tabAsignaciones" class="hidden">
      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>Grupo</th>
              <th>Materia</th>
              <th>Docente</th>
              <th>Horario</th>
              <th>Días</th>
              <th>Ciclo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaAsignaciones">
            <tr><td colspan="7" class="text-center text-text-muted py-8">Cargando…</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Modal: Materia -->
<div id="modalMateria" class="modal-backdrop hidden">
  <div class="modal-box">
    <div class="modal-head">
      <h2 id="modalMateriaTitulo" class="font-serif font-bold text-primary-dark text-lg">Nueva materia</h2>
      <button class="modal-close" data-modal="modalMateria">
        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formMateria">
      <div class="modal-body grid grid-cols-2 gap-4">
        <input type="hidden" id="materiaId" name="id" />
        <input type="hidden" name="accion" value="crear" />
        <div class="form-group col-span-2 mb-0">
          <label class="label">Nombre</label>
          <input type="text" name="nombre" id="mNombre" class="input" required placeholder="Ej. Programación Web" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Clave</label>
          <input type="text" name="clave" id="mClave" class="input" placeholder="ISC-501" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Créditos</label>
          <input type="number" name="creditos" id="mCreditos" class="input" min="1" max="12" value="5" />
        </div>
        <div class="col-span-2"><div id="msgMateria" class="msg"></div></div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost modal-close" data-modal="modalMateria">Cancelar</button>
        <button type="submit" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Asignación -->
<div id="modalAsignacion" class="modal-backdrop hidden">
  <div class="modal-box">
    <div class="modal-head">
      <h2 class="font-serif font-bold text-primary-dark text-lg">Asignar materia a grupo</h2>
      <button class="modal-close" data-modal="modalAsignacion">
        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formAsignacion">
      <div class="modal-body grid grid-cols-2 gap-4">
        <input type="hidden" name="accion" value="asignar" />
        <div class="form-group col-span-2 mb-0">
          <label class="label">Grupo</label>
          <select name="idGrupo" id="asGrupo" class="input" required>
            <option value="">Cargando…</option>
          </select>
        </div>
        <div class="form-group col-span-2 mb-0">
          <label class="label">Materia</label>
          <select name="idMateria" id="asMateria" class="input" required>
            <option value="">Cargando…</option>
          </select>
        </div>
        <div class="form-group col-span-2 mb-0">
          <label class="label">Docente</label>
          <select name="idDocente" id="asDocente" class="input" required>
            <option value="">Cargando…</option>
          </select>
        </div>
        <div class="form-group mb-0">
          <label class="label">Hora inicio</label>
          <input type="time" name="horaInicio" id="asHoraInicio" class="input" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Hora fin</label>
          <input type="time" name="horaFin" id="asHoraFin" class="input" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Días</label>
          <input type="text" name="dias" class="input" value="LMJV" placeholder="LMV, MJ…" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Ciclo</label>
          <input type="text" name="ciclo" class="input" value="2026-A" />
        </div>
        <div class="col-span-2"><div id="msgAsignacion" class="msg"></div></div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost modal-close" data-modal="modalAsignacion">Cancelar</button>
        <button type="submit" class="btn-primary">Asignar</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
