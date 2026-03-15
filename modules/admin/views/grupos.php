<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);

$title     = 'Grupos — ITSZ Admin';
$dataPage  = 'admin-grupos';
$activeNav = 'grupos';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Grupos</h1>
        <p class="text-text-muted text-sm mt-0.5">Organización académica por grupo y carrera</p>
      </div>
      <button id="btnNuevoGrupo" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo grupo
      </button>
    </div>

    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Carrera</th>
            <th>Semestre</th>
            <th>Campus</th>
            <th>Alumnos</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaGrupos">
          <tr><td colspan="7" class="text-center text-text-muted py-8">Cargando…</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Grupo -->
<div id="modalGrupo" class="modal-backdrop hidden">
  <div class="modal-box">
    <div class="modal-head">
      <h2 id="modalGrupoTitulo" class="font-serif font-bold text-primary-dark text-lg">Nuevo grupo</h2>
      <button class="modal-close" data-modal="modalGrupo">
        <svg class="w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formGrupo">
      <div class="modal-body grid grid-cols-2 gap-4">
        <input type="hidden" id="grupoId" name="id" />
        <input type="hidden" name="accion" value="crear" />

        <div class="form-group col-span-2 mb-0">
          <label class="label">Nombre del grupo</label>
          <input type="text" name="nombre" id="gNombre" class="input" placeholder="Ej. ISC 6A" required />
        </div>
        <div class="form-group mb-0">
          <label class="label">Carrera</label>
          <select name="carrera" id="gCarrera" class="input">
            <option value="">— Seleccionar —</option>
            <?php foreach(['Ing. en Sistemas Computacionales','Ing. en Gestión Empresarial','Ing. en Desarrollo Comunitario','Ing. Forestal','Ing. en Innovación Agrícola Sustentable','Ing. Civil','Posgrado'] as $c): ?>
              <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group mb-0">
          <label class="label">Semestre</label>
          <select name="semestre" id="gSemestre" class="input">
            <?php for ($i=1; $i<=9; $i++): ?>
              <option value="<?= $i ?>"><?= $i ?>°</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="form-group col-span-2 mb-0">
          <label class="label">Campus</label>
          <select name="campus" id="gCampus" class="input">
            <option value="">— Sin asignar —</option>
            <?php foreach(['Zongolica','Nogales','Tezonapa','Tehuipango','Tequila','Cuichapa','Acultzinapa'] as $c): ?>
              <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-span-2"><div id="msgGrupo" class="msg"></div></div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost modal-close" data-modal="modalGrupo">Cancelar</button>
        <button type="submit" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
