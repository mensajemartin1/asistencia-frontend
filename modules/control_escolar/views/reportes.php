<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','control_escolar']);
require_once __DIR__ . '/../../../config/database.php';

$conn = getConnection();

$title     = 'Reportes — ITSZ';
$dataPage  = 'ce-reportes';
$activeNav = 'reportes';
require_once __DIR__ . '/../../../config/partials/head.php';

// Cargar grupos y alumnos filtrando por campus si es control_escolar
$campusScope = ($_SESSION['rol'] === 'control_escolar') ? ($_SESSION['campus'] ?? '') : '';

if ($campusScope) {
    $st = $conn->prepare("SELECT id,nombre FROM Grupos WHERE activo=1 AND campus=? ORDER BY nombre");
    $st->bind_param('s', $campusScope);
    $st->execute();
    $grupos = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    $st->close();

    $st = $conn->prepare(
        "SELECT a.id, a.nombre, a.matricula FROM Alumnos a
         JOIN Grupos g ON g.id = a.idGrupo
         WHERE a.activo=1 AND g.campus=? ORDER BY a.nombre"
    );
    $st->bind_param('s', $campusScope);
    $st->execute();
    $alumnos = $st->get_result()->fetch_all(MYSQLI_ASSOC);
    $st->close();
} else {
    $grupos  = $conn->query("SELECT id,nombre FROM Grupos WHERE activo=1 ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
    $alumnos = $conn->query("SELECT id,nombre,matricula FROM Alumnos WHERE activo=1 ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
}
?>
<div class="app-layout">
<?php require_once __DIR__ . '/../../../config/partials/sidebar.php'; ?>

<div class="app-main">
  <div class="p-6 lg:p-8">

    <div class="mb-6">
      <h1 class="font-serif text-2xl font-bold text-primary-dark">Reportes Institucionales</h1>
      <p class="text-text-muted text-sm mt-0.5">Genera reportes en PDF o Excel</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">

      <!-- Reporte por grupo -->
      <div class="card">
        <h2 class="font-semibold text-text mb-4 flex items-center gap-2">
          <span class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </span>
          Reporte por grupo
        </h2>
        <div class="form-group">
          <label class="label text-xs">Grupo</label>
          <select id="rGrupo" class="input text-sm">
            <option value="">— Seleccionar grupo —</option>
            <?php foreach ($grupos as $g): ?>
              <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2 form-group">
          <div>
            <label class="label text-xs">Desde</label>
            <input type="date" id="rGrupoFechaIni" class="input text-sm" value="<?= date('Y-m-01') ?>" />
          </div>
          <div>
            <label class="label text-xs">Hasta</label>
            <input type="date" id="rGrupoFechaFin" class="input text-sm" value="<?= date('Y-m-d') ?>" />
          </div>
        </div>
        <div class="flex gap-2 mt-2">
          <button class="btn-primary flex-1 text-sm btnReporteGrupo" data-formato="pdf">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            PDF
          </button>
          <button class="btn-secondary flex-1 text-sm btnReporteGrupo" data-formato="csv">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Excel/CSV
          </button>
        </div>
      </div>

      <!-- Reporte por alumno -->
      <div class="card">
        <h2 class="font-semibold text-text mb-4 flex items-center gap-2">
          <span class="w-7 h-7 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-[#7c3aed]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </span>
          Reporte por alumno
        </h2>
        <div class="form-group">
          <label class="label text-xs">Alumno</label>
          <select id="rAlumno" class="input text-sm">
            <option value="">— Seleccionar alumno —</option>
            <?php foreach ($alumnos as $a): ?>
              <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?> — <?= $a['matricula'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-2 form-group">
          <div>
            <label class="label text-xs">Desde</label>
            <input type="date" id="rAlumnoFechaIni" class="input text-sm" value="<?= date('Y-m-01') ?>" />
          </div>
          <div>
            <label class="label text-xs">Hasta</label>
            <input type="date" id="rAlumnoFechaFin" class="input text-sm" value="<?= date('Y-m-d') ?>" />
          </div>
        </div>
        <div class="flex gap-2 mt-2">
          <button class="btn-primary flex-1 text-sm btnReporteAlumno" data-formato="pdf">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            PDF
          </button>
          <button class="btn-secondary flex-1 text-sm btnReporteAlumno" data-formato="csv">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Excel/CSV
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../../config/partials/app_footer.php'; ?>
