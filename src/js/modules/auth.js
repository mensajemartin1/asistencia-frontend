import { request, showMsg } from '../api.js'

const CONTROLLER = '../controllers/loginModel.php'

// ── Paneles ────────────────────────────────────────────────────────────────────
const panelLogin    = document.getElementById('panelLogin')
const panelRegistro = document.getElementById('panelRegistro')
const panelRecupera = document.getElementById('panelRecupera')

function showPanel(panel) {
  [panelLogin, panelRegistro, panelRecupera].forEach(p => p?.classList.add('hidden'))
  panel?.classList.remove('hidden')
}

document.getElementById('btnIrRegistro')?.addEventListener('click',  () => showPanel(panelRegistro))
document.getElementById('btnIrRecupera')?.addEventListener('click',  () => showPanel(panelRecupera))
document.getElementById('btnVolverLogin1')?.addEventListener('click', () => showPanel(panelLogin))
document.getElementById('btnVolverLogin2')?.addEventListener('click', () => showPanel(panelLogin))

// ── Mostrar/ocultar contraseña ─────────────────────────────────────────────────
const EYE_OPEN   = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
const EYE_CLOSED = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`

function makeToggle(inputId, btnId, iconId) {
  const input  = document.getElementById(inputId)
  const btn    = document.getElementById(btnId)
  const icon   = document.getElementById(iconId)
  if (!input || !btn || !icon) return
  btn.addEventListener('click', () => {
    const visible = input.type === 'password'
    input.type    = visible ? 'text' : 'password'
    icon.innerHTML = visible ? EYE_CLOSED : EYE_OPEN
  })
}

makeToggle('password',           'togglePassword',         'eyeIcon')
makeToggle('regPassword',        'toggleRegPass',          'eyeRegPass')
makeToggle('regPasswordConfirm', 'toggleRegPassConfirm',   'eyeRegPassConfirm')

const passEl = document.getElementById('password')

// ── Mensajes por URL params (desde confirmar.php) ─────────────────────────────
const msgLogin = document.getElementById('mensajeLogin')
const params   = new URLSearchParams(window.location.search)

if (params.has('confirmado')) {
  showMsg(msgLogin, '✓ Correo confirmado. Ya puedes iniciar sesión.', 'success')
} else if (params.has('reset')) {
  showMsg(msgLogin, '✓ Contraseña actualizada. Ya puedes iniciar sesión.', 'success')
} else if (params.get('error') === 'token_invalido') {
  showMsg(msgLogin, 'El enlace de confirmación no es válido.', 'error')
} else if (params.get('error') === 'token_expirado') {
  showMsg(msgLogin, 'El enlace expiró. Regístrate de nuevo.', 'error')
} else if (params.get('info') === 'ya_confirmado') {
  showMsg(msgLogin, 'Tu correo ya fue confirmado anteriormente.', 'success')
}
// Limpiar params de la URL sin recargar
if ([...params].length) history.replaceState({}, '', window.location.pathname)

// ── LOGIN ──────────────────────────────────────────────────────────────────────
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btnLogin = document.getElementById('btnLogin')

  const data = new FormData()
  data.append('accion',   'login')
  data.append('correo',   document.getElementById('correo').value.trim())
  data.append('password', passEl.value)

  try {
    const res = await request(CONTROLLER, { method: 'POST', body: data }, btnLogin)
    const trimmed = res.trim()
    if (trimmed.startsWith('ok:')) {
      const rol = trimmed.split(':')[1]
      if (rol !== 'estudiante') {
        // Personal accediendo al portal de alumnos
        showMsg(msgLogin,
          'Este portal es exclusivo para alumnos. El personal debe usar el <a href="/modules/auth/views/staff.php" class="underline font-semibold">Portal Institucional</a>.',
          'error')
        return
      }
      window.location = '/modules/estudiante/views/dashboard.php'
    } else {
      switch (trimmed) {
        case 'error:pendiente_confirmacion':
          showMsg(msgLogin,
            'Confirma tu correo antes de iniciar sesión. Revisa tu bandeja de entrada.',
            'error')
          break
        case 'error:rechazado':
          showMsg(msgLogin, 'Tu acceso ha sido rechazado. Contacta al administrador.', 'error')
          break
        case 'error:campos_vacios':
          showMsg(msgLogin, 'Ingresa tu correo y contraseña.', 'error')
          break
        default:
          showMsg(msgLogin, 'Correo o contraseña incorrectos.', 'error')
      }
    }
  } catch {
    showMsg(msgLogin, 'Error de conexión. Intenta de nuevo.', 'error')
  }
})

// ── REGISTRO ───────────────────────────────────────────────────────────────────
const msgRegistro = document.getElementById('mensajeRegistro')

document.getElementById('registroForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btnRegistrar = document.getElementById('btnRegistrar')

  const correo  = document.getElementById('regCorreo').value.trim().toLowerCase()
  const pass    = document.getElementById('regPassword').value
  const confirm = document.getElementById('regPasswordConfirm').value

  if (!correo.endsWith('@zongolica.tecnm.mx')) {
    showMsg(msgRegistro, 'Solo se permiten correos @zongolica.tecnm.mx', 'error')
    return
  }
  if (pass.length < 8) {
    showMsg(msgRegistro, 'La contraseña debe tener al menos 8 caracteres.', 'error')
    return
  }
  if (pass !== confirm) {
    showMsg(msgRegistro, 'Las contraseñas no coinciden.', 'error')
    return
  }

  const data = new FormData()
  data.append('accion',   'registro')
  data.append('nombre',   document.getElementById('regNombre').value.trim())
  data.append('correo',   correo)
  data.append('password', pass)
  data.append('campus',   document.getElementById('regCampus').value)

  try {
    const res = await request(CONTROLLER, { method: 'POST', body: data }, btnRegistrar)
    switch (res.trim()) {
      case 'ok':
        showMsg(msgRegistro,
          'Cuenta creada. Revisa tu correo institucional para confirmarla.',
          'success')
        e.target.reset()
        break
      case 'error:correo_invalido':
        showMsg(msgRegistro, 'Solo se permiten correos @zongolica.tecnm.mx', 'error')
        break
      case 'error:ya_existe':
        showMsg(msgRegistro, 'Ya existe una cuenta con ese correo.', 'error')
        break
      case 'error:campos_vacios':
        showMsg(msgRegistro, 'Completa todos los campos.', 'error')
        break
      default:
        showMsg(msgRegistro, `Error: ${res.trim()}`, 'error')
    }
  } catch {
    showMsg(msgRegistro, 'Error de conexión. Intenta de nuevo.', 'error')
  }
})

// ── RECUPERAR ──────────────────────────────────────────────────────────────────
const msgRecupera = document.getElementById('mensajeRecupera')

document.getElementById('recuperaForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btnRecuperar = document.getElementById('btnRecuperar')

  const data = new FormData()
  data.append('accion', 'recuperar')
  data.append('correo', document.getElementById('recCorreo').value.trim())

  try {
    const res = await request(CONTROLLER, { method: 'POST', body: data }, btnRecuperar)
    if (res.trim() === 'ok:enviado') {
      showMsg(msgRecupera,
        'Si existe una cuenta activa con ese correo, recibirás un enlace en tu bandeja.',
        'success')
    } else {
      showMsg(msgRecupera, 'Error al procesar la solicitud. Intenta de nuevo.', 'error')
    }
  } catch {
    showMsg(msgRecupera, 'Error de conexión. Intenta de nuevo.', 'error')
  }
})
