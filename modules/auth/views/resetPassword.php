<?php
/**
 * Vista para restablecer contraseña vía token enviado por correo.
 * URL: /modules/auth/views/resetPassword.php?token=xxxx
 */
require_once __DIR__ . '/../../../config/database.php';

$token = trim($_GET['token'] ?? '');

// Validar token antes de mostrar el formulario
$tokenValido = false;
$tokenError  = '';

if (!$token) {
    $tokenError = 'token_invalido';
} else {
    $conn = getConnection();
    $stmt = $conn->prepare(
        "SELECT id, token_reset_expira FROM Usuarios
         WHERE token_reset = ? AND estado = 'activo' LIMIT 1"
    );
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $tokenError = 'token_invalido';
    } elseif (strtotime($row['token_reset_expira']) < time()) {
        $tokenError = 'token_expirado';
    } else {
        $tokenValido = true;
    }
}

$title    = 'Restablecer contraseña — ITSZ';
$dataPage = 'reset-password';
$bodyClass = 'min-h-screen bg-primary-dark';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="min-h-screen flex">

  <!-- Panel izquierdo (igual que login) -->
  <div class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-12
              bg-linear-to-br from-primary-dark via-[#1e3a8a] to-[#1d4ed8] overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>
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
    <div class="relative z-10">
      <p class="text-blue-500 text-xs mt-6">© <?= date('Y') ?> ITSZ · Altas Montañas, Veracruz</p>
    </div>
  </div>

  <!-- Panel derecho — Formulario -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center items-center
              bg-white min-h-screen px-6 py-12">

    <!-- Logo móvil -->
    <div class="lg:hidden text-center mb-8">
      <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-14 h-14 mx-auto mb-3 object-contain" />
      <p class="font-serif font-bold text-primary-dark text-lg leading-tight">
        Instituto Tecnológico Superior<br>de Zongolica
      </p>
    </div>

    <div class="w-full max-w-sm">

      <?php if (!$tokenValido): ?>
        <!-- Error de token -->
        <div class="text-center">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
          </div>
          <h2 class="font-serif text-2xl font-bold text-primary-dark mb-2">Enlace no válido</h2>
          <p class="text-text-muted text-sm mb-6">
            <?= $tokenError === 'token_expirado'
                ? 'Este enlace ha expirado. Solicita uno nuevo desde la pantalla de inicio de sesión.'
                : 'El enlace de recuperación no es válido o ya fue utilizado.' ?>
          </p>
          <a href="/modules/auth/views/login.php"
             class="btn-primary inline-block px-6 py-2.5 text-sm">
            Volver al inicio de sesión
          </a>
        </div>

      <?php else: ?>
        <!-- Formulario -->
        <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Nueva contraseña</h2>
        <p class="text-text-muted text-sm mb-7">Elige una contraseña segura para tu cuenta</p>

        <form id="resetForm" novalidate>
          <input type="hidden" id="resetToken" value="<?= htmlspecialchars($token) ?>" />

          <div class="form-group">
            <label class="label">Nueva contraseña</label>
            <div class="relative">
              <input type="password" id="newPassword" class="input pr-10"
                     placeholder="••••••••" required minlength="8" />
              <button type="button" id="toggleNew"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="eyeNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <input type="password" id="confirmPassword" class="input pr-10"
                     placeholder="••••••••" required />
              <button type="button" id="toggleConfirm"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-text-muted hover:text-primary transition-colors">
                <svg id="eyeConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>

          <div id="mensajeReset" class="msg mb-4"></div>

          <button type="submit" id="btnReset" class="btn-primary btn-full py-3">
            Guardar contraseña
          </button>
        </form>
      <?php endif; ?>

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
