<?php
$title    = 'Acceso — ITSZ Sistema de Asistencia';
$dataPage = 'login';
$bodyClass = 'min-h-screen bg-primary-dark';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="min-h-screen flex">

  <!-- ══════════════════════════════════════════════
       PANEL IZQUIERDO — Identidad institucional
  ══════════════════════════════════════════════ -->
  <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-12
              bg-linear-to-br from-primary-dark via-[#1e3a8a] to-[#1d4ed8] overflow-hidden">

    <!-- decoración -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>

    <!-- Logo + institución -->
    <div class="relative z-10">
      <div class="flex items-center gap-4 mb-12">
        <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-14 h-14 object-contain drop-shadow-lg" />
        <div>
          <p class="text-white font-bold text-sm leading-tight">Instituto Tecnológico Superior</p>
          <p class="text-blue-300 text-xs">de Zongolica · TecNM</p>
        </div>
      </div>

      <h1 class="font-serif text-4xl font-bold text-white leading-tight mb-4">
        Sistema de<br>Control de<br>Asistencia
      </h1>
      <p class="text-blue-200 text-sm leading-relaxed max-w-xs">
        Plataforma institucional para el registro y seguimiento de asistencia en los 7 campus del ITSZ.
      </p>
    </div>

    <!-- Campus list -->
    <div class="relative z-10">
      <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider mb-3">Red de campus</p>
      <div class="grid grid-cols-2 gap-2">
        <?php foreach(['Zongolica','Nogales','Tezonapa','Tehuipango','Tequila','Cuichapa','Acultzinapa'] as $c): ?>
          <div class="flex items-center gap-2 text-blue-200 text-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>
            <?= $c ?>
          </div>
        <?php endforeach; ?>
      </div>
      <p class="text-blue-500 text-xs mt-6">© <?= date('Y') ?> ITSZ · Altas Montañas, Veracruz</p>
    </div>
  </div>

  <!-- ══════════════════════════════════════════════
       PANEL DERECHO — Formulario
  ══════════════════════════════════════════════ -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center
              bg-white min-h-screen px-6 py-12">

    <!-- Logo móvil -->
    <div class="lg:hidden text-center mb-8">
      <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-14 h-14 mx-auto mb-3 object-contain" />
      <p class="font-serif font-bold text-primary-dark text-lg leading-tight">
        Instituto Tecnológico Superior<br>de Zongolica
      </p>
      <p class="text-text-muted text-xs mt-1">TecNM · Sistema de Control de Asistencia</p>
    </div>

    <div class="w-full max-w-sm">

      <!-- ─── LOGIN ─────────────────────────────── -->
      <div id="panelLogin">
        <div class="flex items-center gap-2 mb-5">
          <span class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center text-base">🎓</span>
          <span class="text-xs font-semibold text-primary uppercase tracking-wide">Portal de Alumnos</span>
        </div>
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Acceso alumnos</h2>
        <p class="text-text-muted text-sm mb-7">Ingresa con tu correo institucional</p>

        <form id="loginForm" novalidate>
          <div class="form-group">
            <label for="correo" class="label">Correo institucional</label>
            <input type="email" id="correo" name="correo" class="input"
                   placeholder="nombre@zongolica.tecnm.mx" autocomplete="email" required />
          </div>

          <div class="form-group">
            <label for="password" class="label">Contraseña</label>
            <div class="relative">
              <input type="password" id="password" name="password" class="input pr-10"
                     placeholder="••••••••" autocomplete="current-password" required />
              <button type="button" id="togglePassword"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>

          <div id="mensajeLogin" class="msg mb-4"></div>

          <button type="submit" id="btnLogin" class="btn-primary btn-full py-3 text-base mt-1">
            Iniciar sesión
          </button>
        </form>

        <div class="flex justify-between mt-6 pt-5 border-t border-border text-sm">
          <button id="btnIrRegistro" class="text-primary hover:text-primary-dark font-semibold transition-colors">
            Crear cuenta
          </button>
          <button id="btnIrRecupera" class="text-text-muted hover:text-primary transition-colors">
            Recuperar acceso
          </button>
        </div>

        <div class="mt-5 pt-4 border-t border-border text-center">
          <p class="text-xs text-text-muted">
            ¿Eres docente o personal administrativo?
            <a href="/modules/auth/views/staff.php" class="text-primary font-semibold hover:text-primary-dark transition-colors">
              Portal Institucional →
            </a>
          </p>
        </div>
      </div>

      <!-- ─── REGISTRO ──────────────────────────── -->
      <div id="panelRegistro" class="hidden">
        <button id="btnVolverLogin1" class="flex items-center gap-1 text-text-muted hover:text-primary text-sm mb-6 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Volver
        </button>
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Solicitar acceso</h2>
        <p class="text-text-muted text-sm mb-6">
          Solo correos <span class="font-medium text-primary">@zongolica.tecnm.mx</span>
        </p>

        <form id="registroForm" novalidate>
          <div class="form-group">
            <label class="label">Nombre completo</label>
            <input type="text" id="regNombre" class="input" placeholder="Ej. Juan Pérez López" required />
          </div>
          <div class="form-group">
            <label class="label">Correo institucional</label>
            <input type="email" id="regCorreo" class="input"
                   placeholder="nombre@zongolica.tecnm.mx" autocomplete="email" required />
          </div>
          <div class="form-group">
            <label class="label">Contraseña</label>
            <div class="relative">
              <input type="password" id="regPassword" class="input pr-10" placeholder="••••••••" required />
              <button type="button" id="toggleRegPass"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="eyeRegPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label class="label">Confirmar contraseña</label>
            <div class="relative">
              <input type="password" id="regPasswordConfirm" class="input pr-10" placeholder="••••••••" required />
              <button type="button" id="toggleRegPassConfirm"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="eyeRegPassConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label class="label">Campus</label>
            <select id="regCampus" class="input">
              <option value="">— Selecciona campus —</option>
              <option value="Zongolica">Campus Zongolica</option>
              <option value="Nogales">UA Nogales</option>
              <option value="Tezonapa">UA Tezonapa</option>
              <option value="Tehuipango">UA Tehuipango</option>
              <option value="Tequila">UA Tequila</option>
              <option value="Cuichapa">UA Cuichapa</option>
              <option value="Acultzinapa">UA Acultzinapa</option>
            </select>
          </div>

          <div id="mensajeRegistro" class="msg mb-4"></div>

          <button type="submit" id="btnRegistrar" class="btn-primary btn-full py-3">
            Enviar solicitud
          </button>
        </form>
      </div>

      <!-- ─── RECUPERAR ─────────────────────────── -->
      <div id="panelRecupera" class="hidden">
        <button id="btnVolverLogin2" class="flex items-center gap-1 text-text-muted hover:text-primary text-sm mb-6 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Volver
        </button>
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Recuperar acceso</h2>
        <p class="text-text-muted text-sm mb-6">Te enviaremos un enlace para restablecer tu contraseña</p>

        <form id="recuperaForm" novalidate>
          <div class="form-group">
            <label class="label">Correo institucional</label>
            <input type="email" id="recCorreo" class="input"
                   placeholder="nombre@zongolica.tecnm.mx" required />
          </div>
          <div id="mensajeRecupera" class="msg mb-4"></div>
          <button type="submit" id="btnRecuperar" class="btn-primary btn-full py-3">
            Enviar enlace
          </button>
        </form>
      </div>

    </div><!-- /max-w-sm -->
  </div><!-- /panel derecho -->

</div><!-- /flex -->

<?php
// Carga solo el JS, sin el footer institucional (no aplica en login)
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
