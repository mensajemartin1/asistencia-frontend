<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);

$title     = 'Usuarios — ITSZ Admin';
$dataPage  = 'admin-usuarios';
$activeNav = 'usuarios';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="font-serif text-2xl font-bold text-primary-dark">Gestión de Usuarios</h1>
        <p class="text-text-muted text-sm mt-0.5">Administra cuentas, roles y accesos</p>
      </div>
      <button id="btnNuevoUsuario" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo usuario
      </button>
    </div>

    <!-- Filtros -->
    <div class="card mb-5 flex flex-wrap gap-3 items-center">
      <input id="buscarUsuario" type="text" class="input max-w-xs" placeholder="Buscar por nombre o correo…" />
      <select id="filtroRol" class="input max-w-[160px]">
        <option value="">Todos los roles</option>
        <option value="admin">Administrador</option>
        <option value="docente">Docente</option>
        <option value="estudiante">Estudiante</option>
        <option value="control_escolar">Control Escolar</option>
      </select>
      <button id="btnBuscar" class="btn-secondary px-4">Buscar</button>
    </div>

    <!-- Tabla -->
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Campus</th>
            <th>Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaUsuarios">
          <tr><td colspan="7" class="text-center text-text-muted py-8">Cargando…</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- ═══ MODAL: Crear / Editar usuario ═══ -->
<div id="modalUsuario" class="modal-backdrop hidden">
  <div class="modal-box">
    <div class="modal-head">
      <h2 id="modalTitulo" class="font-serif font-bold text-primary-dark text-lg">Nuevo usuario</h2>
      <button class="modal-close text-text-muted hover:text-text" data-modal="modalUsuario">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form id="formUsuario">
      <div class="modal-body grid grid-cols-1 sm:grid-cols-2 gap-4">
        <input type="hidden" id="userId" name="id" value="" />
        <input type="hidden" name="accion" value="crear" />

        <div class="form-group col-span-2 mb-0">
          <label class="label">Nombre completo</label>
          <input type="text" name="nombre" id="uNombre" class="input" required />
        </div>
        <div class="form-group mb-0">
          <label class="label">Correo</label>
          <input type="email" name="correo" id="uCorreo" class="input" required />
        </div>
        <div class="form-group mb-0" id="passwordGroup">
          <label class="label">Contraseña</label>
          <input type="password" name="password" id="uPassword" class="input" placeholder="Dejar vacío para no cambiar" />
        </div>
        <div class="form-group mb-0">
          <label class="label">Rol</label>
          <select name="rol" id="uRol" class="input">
            <option value="estudiante">Estudiante</option>
            <option value="docente">Docente</option>
            <option value="control_escolar">Control Escolar</option>
            <option value="admin">Administrador</option>
          </select>
        </div>
        <div class="form-group mb-0">
          <label class="label">Estado</label>
          <select name="estado" id="uEstado" class="input">
            <option value="activo">Activo</option>
            <option value="pendiente_confirmacion">Pendiente</option>
            <option value="rechazado">Desactivado</option>
          </select>
        </div>
        <div class="form-group col-span-2 mb-0">
          <label class="label">Campus</label>
          <select name="campus" id="uCampus" class="input">
            <option value="">— Sin asignar —</option>
            <?php foreach(['Zongolica','Nogales','Tezonapa','Tehuipango','Tequila','Cuichapa','Acultzinapa'] as $c): ?>
              <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-span-2">
          <div id="msgModalUsuario" class="msg"></div>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost modal-close" data-modal="modalUsuario">Cancelar</button>
        <button type="submit" id="btnGuardarUsuario" class="btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- ═══ MODAL: Confirmar eliminar ═══ -->
<div id="modalEliminar" class="modal-backdrop hidden">
  <div class="modal-box max-w-sm">
    <div class="modal-head">
      <h2 class="font-serif font-bold text-error text-lg">Eliminar usuario</h2>
      <button class="modal-close text-text-muted hover:text-text" data-modal="modalEliminar">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <p class="text-text-muted text-sm">¿Eliminar a <strong id="eliminarNombre"></strong>? Esta acción no se puede deshacer.</p>
      <input type="hidden" id="eliminarId" />
    </div>
    <div class="modal-foot">
      <button class="btn-ghost modal-close" data-modal="modalEliminar">Cancelar</button>
      <button id="btnConfirmarEliminar" class="btn-primary bg-error hover:bg-red-700">Eliminar</button>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
