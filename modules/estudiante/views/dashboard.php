<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['estudiante','admin']);
require_once __DIR__ . '/../../../config/database.php';

// Redirect a onboarding si no tiene grupo asignado
$conn = getConnection();
$st = $conn->prepare("SELECT idGrupo, onboarding_ok FROM Alumnos WHERE idUsuario=? LIMIT 1");
$uid = (int)$_SESSION['user_id'];
$st->bind_param('i', $uid);
$st->execute();
$alumno = $st->get_result()->fetch_assoc();
$st->close();

// Redirigir a onboarding si aún no completó el wizard (falta grupo o perfil)
if (!$alumno || !$alumno['idGrupo'] || !$alumno['onboarding_ok']) {
    header('Location: /modules/estudiante/views/onboarding.php'); exit;
}

$title             = 'Mi Asistencia — ITSZ';
$dataPage          = 'estudiante-dashboard';
$activeStudentTab  = 'dashboard';
require_once __DIR__ . '/../../../config/partials/head.php';
$_nombre = htmlspecialchars($_SESSION['nombre'] ?? '');
?>
<?php require_once __DIR__ . '/../../../config/partials/student_appbar.php'; ?>

  <!-- Alerta general -->
  <div id="alertaGeneral" class="hidden mb-4 rounded-xl border border-error/30 bg-red-50 px-4 py-3">
    <p class="text-error font-semibold text-sm">⚠ Tienes materias con asistencia por debajo del mínimo.</p>
  </div>

  <!-- Materias -->
  <h2 class="font-serif text-base font-bold text-primary-dark mb-3">Mis materias</h2>
  <div id="gridMaterias" class="flex flex-col gap-3">
    <div class="card text-center text-text-muted text-sm py-8">Cargando…</div>
  </div>

  <!-- Modal QR -->
  <div id="qrModal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center px-4 pb-6 sm:pb-0">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="qrModalBg"></div>
    <div class="relative bg-surface rounded-2xl shadow-xl w-full max-w-xs p-6 text-center z-10">
      <button id="qrModalClose" class="absolute top-3 right-3 text-text-muted hover:text-text">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
      <p id="qrModalMateria" class="text-xs font-bold text-primary uppercase tracking-wide mb-1"></p>
      <p class="text-xs text-text-muted mb-4">Muestra este código al docente</p>
      <div id="qrModalCanvas" class="flex items-center justify-center mb-3 min-h-[200px]">
        <div class="text-text-muted text-xs">Generando…</div>
      </div>
      <p id="qrModalMatricula" class="font-mono font-bold text-primary text-lg tracking-widest"></p>
      <p class="text-xs text-text-muted mt-1"><?= $_nombre ?></p>
    </div>
  </div>

<?php require_once __DIR__ . '/../../../config/partials/student_footer.php'; ?>
