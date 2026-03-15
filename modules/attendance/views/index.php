<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

date_default_timezone_set("America/Mexico_City");
$hora_actual = date("H:i:s");

$stmt = $conn->prepare("SELECT nombre FROM Materias WHERE ? BETWEEN horaInicio AND horaFin");
$stmt->bind_param("s", $hora_actual);
$stmt->execute();
$result = $stmt->get_result();

$materia_actual    = null;
$hay_clase         = false;

if ($result && $result->num_rows > 0) {
    $materia_actual = $result->fetch_assoc()['nombre'];
    $hay_clase      = true;
}
$stmt->close();

$title     = 'Registro de Asistencia — ITSZ';
$dataPage  = 'attendance';
$activeNav = 'attendance';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="page flex flex-col">

  <?php require_once __DIR__ . '/../../../config/partials/navbar.php'; ?>

  <main class="page-content flex-1">

    <div class="mb-6">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">Registro de Asistencia</h1>
      <p class="text-text-muted text-sm mt-1">Ingresa la matrícula o escanea el código QR del alumno.</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

      <!-- ── Formulario ──────────────────────────────────────────── -->
      <div class="lg:col-span-1">
        <div class="card">

          <!-- Materia activa -->
          <div class="mb-5">
            <p class="label">Materia activa ahora</p>
            <?php if ($hay_clase): ?>
              <span class="badge-success px-4 py-2 text-sm font-semibold">
                📚 <?= htmlspecialchars($materia_actual) ?>
              </span>
            <?php else: ?>
              <span class="badge bg-gray-100 text-text-muted px-4 py-2 text-sm">
                Sin clase en este horario
              </span>
            <?php endif; ?>
          </div>

          <form id="formAsistencia" novalidate>

            <div class="form-group">
              <label for="matricula" class="label">Matrícula / QR</label>
              <input
                type="text"
                id="matricula"
                name="matricula"
                class="input font-mono text-base tracking-wider"
                placeholder="Escanea o escribe..."
                autocomplete="off"
                autofocus
                required
              />
              <p class="text-xs text-text-muted mt-1">Presiona Enter para registrar automáticamente.</p>
            </div>

            <div class="form-group">
              <label for="estado" class="label">Estado</label>
              <select id="estado" name="estado" class="input">
                <option value="Presente">✅ Presente</option>
                <option value="Ausente">❌ Ausente</option>
              </select>
            </div>

            <div id="mensajeRegistro" class="msg mb-4"></div>

            <button type="submit" id="btnRegistrar" class="btn-primary btn-full py-3">
              Registrar asistencia
            </button>

          </form>

        </div>
      </div>

      <!-- ── Tabla ───────────────────────────────────────────────── -->
      <div class="lg:col-span-2">
        <div class="card p-0 overflow-hidden">

          <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            <h2 class="font-semibold text-text">Lista de asistencia del día</h2>
            <span class="text-xs text-text-muted"><?= date('d/m/Y') ?></span>
          </div>

          <div class="table-wrap border-0 rounded-none">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Matrícula</th>
                  <th>Nombre</th>
                  <th>Materia</th>
                  <th>Estado</th>
                  <th>Hora</th>
                </tr>
              </thead>
              <tbody id="tablaAsistencia">
                <tr>
                  <td colspan="6" class="table__empty text-center py-8 text-text-muted text-sm">
                    Cargando registros...
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </div>

  </main>

  <?php require_once __DIR__ . '/../../../config/partials/footer.php'; ?>

</div>
