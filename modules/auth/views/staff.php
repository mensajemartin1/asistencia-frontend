<?php
$title     = 'Portal Institucional — ITSZ';
$dataPage  = 'staff-login';
$bodyClass = 'min-h-screen bg-primary-dark';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="min-h-screen flex">

  <!-- ══ PANEL IZQUIERDO — Identidad ══ -->
  <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-12
              bg-linear-to-br from-[#0f172a] via-[#1e293b] to-[#1e3a8a] overflow-hidden">

    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>

    <div class="relative z-10">
      <div class="flex items-center gap-4 mb-12">
        <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-14 h-14 object-contain drop-shadow-lg" />
        <div>
          <p class="text-white font-bold text-sm leading-tight">Instituto Tecnológico Superior</p>
          <p class="text-blue-400 text-xs">de Zongolica · TecNM</p>
        </div>
      </div>

      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <span class="text-blue-300 text-sm font-semibold">Portal Institucional</span>
      </div>

      <h1 class="font-serif text-4xl font-bold text-white leading-tight mb-4">
        Acceso para<br>Personal<br>Docente y<br>Administrativo
      </h1>
      <p class="text-blue-300 text-sm leading-relaxed max-w-xs">
        Gestión de asistencia, reportes y administración académica para el personal del ITSZ.
      </p>
    </div>

    <div class="relative z-10">
      <p class="text-blue-500 text-xs font-semibold uppercase tracking-wider mb-3">Acceso por rol</p>
      <div class="space-y-2">
        <?php foreach([
          ['🔧', 'Administrador', 'Gestión completa del sistema'],
          ['📚', 'Docente',       'Registro de asistencia y reportes'],
          ['📋', 'Control Escolar','Consultas y reportes por campus'],
        ] as [$ico, $rol, $desc]): ?>
          <div class="flex items-center gap-3 bg-white/5 rounded-lg px-3 py-2">
            <span class="text-lg"><?= $ico ?></span>
            <div>
              <p class="text-white text-xs font-semibold leading-none"><?= $rol ?></p>
              <p class="text-blue-400 text-[10px]"><?= $desc ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="text-blue-600 text-xs mt-6">© <?= date('Y') ?> ITSZ · Altas Montañas, Veracruz</p>
    </div>
  </div>

  <!-- ══ PANEL DERECHO — Formulario ══ -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center
              bg-white min-h-screen px-6 py-12">

    <!-- Logo móvil -->
    <div class="lg:hidden text-center mb-8">
      <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-12 h-12 mx-auto mb-3 object-contain" />
      <p class="font-serif font-bold text-primary-dark text-base leading-tight">
        Instituto Tecnológico Superior<br>de Zongolica
      </p>
      <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-xs font-semibold px-3 py-1 rounded-full mt-2">
        🔐 Portal Institucional
      </span>
    </div>

    <div class="w-full max-w-sm">

      <!-- ─── LOGIN ─────────────────────────────── -->
      <div id="panelStaffLogin">
        <div class="flex items-center gap-2 mb-5 lg:flex hidden">
          <span class="w-7 h-7 bg-slate-100 rounded-lg flex items-center justify-center text-base">🔐</span>
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Portal Institucional</span>
        </div>
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Iniciar sesión</h2>
        <p class="text-text-muted text-sm mb-7">Personal docente y administrativo</p>

        <form id="staffLoginForm" novalidate>
          <div class="form-group">
            <label for="sCorreo" class="label">Correo institucional</label>
            <input type="email" id="sCorreo" class="input"
                   placeholder="nombre@zongolica.tecnm.mx" autocomplete="email" required />
          </div>

          <div class="form-group">
            <label for="sPassword" class="label">Contraseña</label>
            <div class="relative">
              <input type="password" id="sPassword" class="input pr-10"
                     placeholder="••••••••" autocomplete="current-password" required />
              <button type="button" id="sTogglePass"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="sEyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>

          <div id="mensajeStaff" class="msg mb-4"></div>

          <button type="submit" id="btnStaffLogin" class="btn-primary btn-full py-3 text-base mt-1">
            Ingresar
          </button>
        </form>

        <div class="mt-6 pt-5 border-t border-border flex justify-between items-center">
          <button id="btnStaffRecupera" class="text-sm text-text-muted hover:text-primary transition-colors">
            Recuperar contraseña
          </button>
          <a href="/modules/auth/views/login.php" class="text-xs text-text-muted hover:text-primary transition-colors">
            Portal de Alumnos →
          </a>
        </div>
      </div>

      <!-- ─── RECUPERAR ─────────────────────────── -->
      <div id="panelStaffRecupera" class="hidden">
        <button id="btnVolverStaffLogin" class="flex items-center gap-1 text-text-muted hover:text-primary text-sm mb-6 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Volver
        </button>
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Recuperar acceso</h2>
        <p class="text-text-muted text-sm mb-6">Te enviaremos un enlace para restablecer tu contraseña</p>

        <form id="staffRecuperaForm" novalidate>
          <div class="form-group">
            <label class="label">Correo institucional</label>
            <input type="email" id="sRecCorreo" class="input"
                   placeholder="nombre@zongolica.tecnm.mx" required />
          </div>
          <div id="mensajeStaffRecupera" class="msg mb-4"></div>
          <button type="submit" id="btnStaffEnviar" class="btn-primary btn-full py-3">
            Enviar enlace
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<?php
$manifest     = [];
$manifestPath = __DIR__ . '/../../../public/assets/bundle/.vite/manifest.json';
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
}
$jsFile = $manifest['src/js/main.js']['file'] ?? null;
?>
<?php if ($jsFile): ?>
  <script type="module" src="/public/assets/bundle/<?= $jsFile ?>"></script>
<?php endif; ?>
</body>
</html>
