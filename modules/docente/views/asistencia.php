<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);

$title     = 'Registrar Asistencia — ITSZ';
$dataPage  = 'docente-asistencia';
$activeNav = 'asistencia';
require_once __DIR__ . '/../../../config/partials/head.php';
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="mb-6">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">Registrar Asistencia</h1>
      <p class="text-text-muted text-sm mt-0.5"><?= date('d/m/Y') ?></p>
    </div>

    <div class="grid lg:grid-cols-5 gap-6">

      <!-- Panel de registro -->
      <div class="lg:col-span-2 flex flex-col gap-5">

        <!-- Selector de clase -->
        <div class="card">
          <h2 class="font-semibold text-text mb-3">Clase</h2>
          <select id="selectorGM" class="input mb-3">
            <option value="">Cargando grupos…</option>
          </select>
          <div id="claseInfo" class="hidden text-xs text-text-muted border border-border rounded-lg p-3 bg-muted">
            <p><strong>Grupo:</strong> <span id="infoGrupo">—</span></p>
            <p><strong>Materia:</strong> <span id="infoMateria">—</span></p>
            <p><strong>Horario:</strong> <span id="infoHorario">—</span></p>
          </div>
        </div>

        <!-- Modo de registro -->
        <div class="card">
          <div class="flex gap-2 mb-4">
            <button id="btnModoManual" class="btn-primary flex-1 text-sm py-2">Matrícula</button>
            <button id="btnModoQR" class="btn-secondary flex-1 text-sm py-2">QR Cámara</button>
          </div>

          <!-- Manual -->
          <div id="modoManual">
            <form id="formAsistencia">
              <div class="form-group">
                <label class="label">Matrícula</label>
                <input id="inputMatricula" name="matricula" type="text" class="input"
                       placeholder="Ej. 22760001" autocomplete="off" autofocus />
              </div>
              <div class="form-group">
                <label class="label">Estado</label>
                <select name="estado" id="selectEstado" class="input">
                  <option value="presente">Presente</option>
                  <option value="retardo">Retardo</option>
                  <option value="falta">Falta</option>
                </select>
              </div>
              <div id="msgAsistencia" class="msg mb-3"></div>
              <button type="submit" id="btnRegistrar" class="btn-primary btn-full py-3">
                Registrar
              </button>
            </form>
          </div>

          <!-- QR -->
          <div id="modoQR" class="hidden">
            <p class="text-xs text-text-muted mb-3">Apunta la cámara al QR del alumno</p>
            <div class="relative rounded-lg overflow-hidden bg-black mb-3" style="aspect-ratio:1">
              <video id="qrVideo" class="w-full h-full object-cover" playsinline></video>
              <div class="absolute inset-0 border-4 border-primary/60 rounded-lg pointer-events-none"></div>
            </div>
            <div id="qrStatus" class="text-xs text-center text-text-muted">Iniciando cámara…</div>
            <div id="msgQR" class="msg mt-3"></div>
          </div>
        </div>

      </div>

      <!-- Lista del día -->
      <div class="lg:col-span-3">
        <div class="card h-full">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-text">Lista de hoy</h2>
            <span id="contadorAsistencia" class="badge badge-primary">0 registros</span>
          </div>
          <div class="table-wrap" style="max-height:60vh;overflow-y:auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Alumno</th>
                  <th>Matrícula</th>
                  <th>Estado</th>
                  <th>Hora</th>
                </tr>
              </thead>
              <tbody id="tablaLista">
                <tr><td colspan="4" class="text-center text-text-muted py-8">Selecciona una clase</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
