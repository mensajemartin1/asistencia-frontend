<?php
/**
 * Helper de correo usando la API de Resend (cURL, sin Composer).
 * Requiere config/env.php cargado previamente (via database.php).
 */

function sendMail(string $to, string $subject, string $htmlBody): bool
{
    $apiKey = env('RESEND_API_KEY', '');
    $from   = env('RESEND_FROM', 'Sistema ITSZ <noreply@zongolica.tecnm.mx>');

    if (empty($apiKey) || strncmp($apiKey, 're_xxx', 6) === 0) {
        error_log("[MAIL] Para: $to | Asunto: $subject");
        return true;
    }

    $payload = json_encode([
        'from'    => $from,
        'to'      => [$to],
        'subject' => $subject,
        'html'    => $htmlBody,
    ]);

    $ch = curl_init('https://api.resend.com/emails');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$apiKey}",
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT        => 15,
        // Windows: sin bundle CA configurado, desactivar verificación en dev
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
    ]);

    $body   = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log("[MAIL] cURL error: {$err}");
        return false;
    }

    if ($status !== 200 && $status !== 201) {
        error_log("[MAIL] Resend error {$status}: {$body}");
        return false;
    }

    return true;
}

// ── Templates ─────────────────────────────────────────────────────────────────

function mailConfirmacionCuenta(string $to, string $nombre, string $token): bool
{
    $appUrl = env('APP_URL', 'http://localhost:3000');
    $link   = "{$appUrl}/modules/auth/controllers/confirmar.php?token={$token}";

    $html = "<!DOCTYPE html><html lang='es'><body style='font-family:Arial,sans-serif;background:#f4f4f4;padding:32px;margin:0'>
  <div style='max-width:560px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)'>
    <div style='background:#1e3a8a;padding:24px 32px'>
      <h1 style='color:#fff;margin:0;font-size:20px'>Instituto Tecnológico Superior de Zongolica</h1>
      <p style='color:#93c5fd;margin:4px 0 0;font-size:13px'>TecNM · Sistema de Control de Asistencia</p>
    </div>
    <div style='padding:32px'>
      <h2 style='color:#1e3a8a;margin-top:0'>Confirma tu correo electrónico</h2>
      <p>Hola <strong>{$nombre}</strong>,</p>
      <p>Para activar tu cuenta en el Sistema de Asistencia del ITSZ, confirma tu dirección de correo haciendo clic en el botón:</p>
      <div style='text-align:center;margin:28px 0'>
        <a href='{$link}' style='display:inline-block;background:#1e40af;color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-weight:bold;font-size:15px'>
          Confirmar correo →
        </a>
      </div>
      <p style='color:#6b7280;font-size:13px'>Este enlace es válido por <strong>24 horas</strong>. Si no creaste esta cuenta, ignora este correo.</p>
      <p style='color:#9ca3af;font-size:12px;word-break:break-all'>O copia este enlace: {$link}</p>
    </div>
    <div style='background:#f9fafb;padding:16px 32px;border-top:1px solid #e5e7eb'>
      <p style='color:#9ca3af;font-size:12px;margin:0'>© " . date('Y') . " ITSZ — TecNM Campus Zongolica · Veracruz, México</p>
    </div>
  </div>
</body></html>";

    return sendMail($to, 'Confirma tu correo — ITSZ Sistema de Asistencia', $html);
}

function mailRecuperarAcceso(string $to, string $nombre, string $token): bool
{
    $appUrl = env('APP_URL', 'http://localhost:3000');
    $link   = "{$appUrl}/modules/auth/views/resetPassword.php?token={$token}";

    $html = "<!DOCTYPE html><html lang='es'><body style='font-family:Arial,sans-serif;background:#f4f4f4;padding:32px;margin:0'>
  <div style='max-width:560px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)'>
    <div style='background:#1e3a8a;padding:24px 32px'>
      <h1 style='color:#fff;margin:0;font-size:20px'>Instituto Tecnológico Superior de Zongolica</h1>
      <p style='color:#93c5fd;margin:4px 0 0;font-size:13px'>TecNM · Sistema de Control de Asistencia</p>
    </div>
    <div style='padding:32px'>
      <h2 style='color:#1e3a8a;margin-top:0'>Restablecer contraseña</h2>
      <p>Hola <strong>{$nombre}</strong>,</p>
      <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón para crear una nueva:</p>
      <div style='text-align:center;margin:28px 0'>
        <a href='{$link}' style='display:inline-block;background:#1e40af;color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-weight:bold;font-size:15px'>
          Restablecer contraseña →
        </a>
      </div>
      <p style='color:#6b7280;font-size:13px'>Este enlace es válido por <strong>1 hora</strong>. Si no solicitaste esto, ignora este correo.</p>
      <p style='color:#9ca3af;font-size:12px;word-break:break-all'>O copia este enlace: {$link}</p>
    </div>
    <div style='background:#f9fafb;padding:16px 32px;border-top:1px solid #e5e7eb'>
      <p style='color:#9ca3af;font-size:12px;margin:0'>© " . date('Y') . " ITSZ — TecNM Campus Zongolica · Veracruz, México</p>
    </div>
  </div>
</body></html>";

    return sendMail($to, 'Restablecer contraseña — ITSZ Sistema de Asistencia', $html);
}

function mailCuentaAprobada(string $to, string $nombre): bool
{
    $appUrl = env('APP_URL', 'http://localhost:3000');
    $link   = "{$appUrl}/modules/auth/views/login.php";

    $html = "<!DOCTYPE html><html lang='es'><body style='font-family:Arial,sans-serif;background:#f4f4f4;padding:32px;margin:0'>
  <div style='max-width:560px;margin:auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)'>
    <div style='background:#1e3a8a;padding:24px 32px'>
      <h1 style='color:#fff;margin:0;font-size:20px'>Instituto Tecnológico Superior de Zongolica</h1>
      <p style='color:#93c5fd;margin:4px 0 0;font-size:13px'>TecNM · Sistema de Control de Asistencia</p>
    </div>
    <div style='padding:32px'>
      <h2 style='color:#16a34a;margin-top:0'>¡Cuenta activada!</h2>
      <p>Hola <strong>{$nombre}</strong>,</p>
      <p>Tu acceso al Sistema de Control de Asistencia ha sido actualizado por un administrador.</p>
      <div style='text-align:center;margin:28px 0'>
        <a href='{$link}' style='display:inline-block;background:#1e40af;color:#fff;text-decoration:none;padding:14px 32px;border-radius:8px;font-weight:bold;font-size:15px'>
          Iniciar sesión →
        </a>
      </div>
    </div>
    <div style='background:#f9fafb;padding:16px 32px;border-top:1px solid #e5e7eb'>
      <p style='color:#9ca3af;font-size:12px;margin:0'>© " . date('Y') . " ITSZ — TecNM Campus Zongolica · Veracruz, México</p>
    </div>
  </div>
</body></html>";

    return sendMail($to, 'Tu acceso ha sido actualizado — ITSZ', $html);
}
