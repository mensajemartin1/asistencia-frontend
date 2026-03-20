import { request, showMsg } from '../api.js'

const CONTROLLER = '/modules/auth/controllers/loginModel.php'

// ── Paneles ────────────────────────────────────────────────────────────────────
const panelLogin   = document.getElementById('panelStaffLogin')
const panelRecupera = document.getElementById('panelStaffRecupera')

function showPanel(panel) {
  [panelLogin, panelRecupera].forEach(p => p?.classList.add('hidden'))
  panel?.classList.remove('hidden')
}

document.getElementById('btnStaffRecupera')?.addEventListener('click',   () => showPanel(panelRecupera))
document.getElementById('btnVolverStaffLogin')?.addEventListener('click', () => showPanel(panelLogin))

// ── Mostrar/ocultar contraseña ─────────────────────────────────────────────────
const EYE_OPEN   = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
const EYE_CLOSED = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`

const passInput = document.getElementById('sPassword')
const toggleBtn = document.getElementById('sTogglePass')
const eyeIcon   = document.getElementById('sEyeIcon')

toggleBtn?.addEventListener('click', () => {
  const visible = passInput.type === 'password'
  passInput.type = visible ? 'text' : 'password'
  eyeIcon.innerHTML = visible ? EYE_CLOSED : EYE_OPEN
})

// ── Params de URL ──────────────────────────────────────────────────────────────
const msgLogin = document.getElementById('mensajeStaff')
const params   = new URLSearchParams(window.location.search)

if (params.has('reset')) {
  showMsg(msgLogin, '✓ Contraseña actualizada. Ya puedes iniciar sesión.', 'success')
}
if ([...params].length) history.replaceState({}, '', window.location.pathname)

// ── LOGIN ──────────────────────────────────────────────────────────────────────
const DASHBOARDS = {
  admin:           '/modules/admin/views/dashboard.php',
  docente:         '/modules/docente/views/dashboard.php',
  control_escolar: '/modules/control_escolar/views/dashboard.php',
}

document.getElementById('staffLoginForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btn = document.getElementById('btnStaffLogin')

  const data = new FormData()
  data.append('accion',   'login')
  data.append('correo',   document.getElementById('sCorreo').value.trim())
  data.append('password', passInput.value)

  try {
    const res     = await request(CONTROLLER, { method: 'POST', body: data }, btn)
    const trimmed = res.trim()

    if (trimmed.startsWith('ok:')) {
      const rol = trimmed.split(':')[1]
      if (rol === 'estudiante') {
        showMsg(msgLogin,
          'Este portal es para personal docente y administrativo. Los alumnos deben usar el <a href="/modules/auth/views/login.php" class="underline font-semibold">Portal de Alumnos</a>.',
          'error')
        return
      }
      window.location = DASHBOARDS[rol] ?? '/modules/admin/views/dashboard.php'
      return
    }

    switch (trimmed) {
      case 'error:pendiente_confirmacion':
        showMsg(msgLogin, 'Tu cuenta está pendiente de activación por el administrador.', 'error')
        break
      case 'error:rechazado':
        showMsg(msgLogin, 'Tu acceso ha sido desactivado. Contacta al administrador.', 'error')
        break
      case 'error:campos_vacios':
        showMsg(msgLogin, 'Ingresa tu correo y contraseña.', 'error')
        break
      default:
        showMsg(msgLogin, 'Correo o contraseña incorrectos.', 'error')
    }
  } catch {
    showMsg(msgLogin, 'Error de conexión. Intenta de nuevo.', 'error')
  }
})

// ── RECUPERAR ──────────────────────────────────────────────────────────────────
const msgRecupera = document.getElementById('mensajeStaffRecupera')

document.getElementById('staffRecuperaForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btn = document.getElementById('btnStaffEnviar')

  const data = new FormData()
  data.append('accion', 'recuperar')
  data.append('correo', document.getElementById('sRecCorreo').value.trim())

  try {
    const res = await request(CONTROLLER, { method: 'POST', body: data }, btn)
    if (res.trim() === 'ok:enviado') {
      showMsg(msgRecupera,
        'Si existe una cuenta con ese correo, recibirás un enlace en tu bandeja.',
        'success')
    } else {
      showMsg(msgRecupera, 'Error al procesar la solicitud.', 'error')
    }
  } catch {
    showMsg(msgRecupera, 'Error de conexión.', 'error')
  }
})
