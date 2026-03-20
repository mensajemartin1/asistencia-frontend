import { request, showMsg } from '../api.js'

const CONTROLLER = '../controllers/resetPasswordModel.php'

const EYE_OPEN   = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
const EYE_CLOSED = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`

function makeToggle(inputId, btnId, iconId) {
  const input = document.getElementById(inputId)
  const btn   = document.getElementById(btnId)
  const icon  = document.getElementById(iconId)
  if (!input || !btn || !icon) return
  btn.addEventListener('click', () => {
    const visible = input.type === 'password'
    input.type    = visible ? 'text' : 'password'
    icon.innerHTML = visible ? EYE_CLOSED : EYE_OPEN
  })
}

makeToggle('newPassword',     'toggleNew',     'eyeNew')
makeToggle('confirmPassword', 'toggleConfirm', 'eyeConfirm')

// ── Formulario ──────────────────────────────────────────────────────────────
const msgEl = document.getElementById('mensajeReset')

document.getElementById('resetForm')?.addEventListener('submit', async (e) => {
  e.preventDefault()
  const btn     = document.getElementById('btnReset')
  const pass    = document.getElementById('newPassword').value
  const confirm = document.getElementById('confirmPassword').value
  const token   = document.getElementById('resetToken').value

  if (pass.length < 8) {
    showMsg(msgEl, 'La contraseña debe tener al menos 8 caracteres.', 'error')
    return
  }
  if (pass !== confirm) {
    showMsg(msgEl, 'Las contraseñas no coinciden.', 'error')
    return
  }

  const data = new FormData()
  data.append('token',    token)
  data.append('password', pass)

  try {
    const res = await request(CONTROLLER, { method: 'POST', body: data }, btn)
    switch (res.trim()) {
      case 'ok':
        showMsg(msgEl, 'Contraseña actualizada. Redirigiendo...', 'success')
        setTimeout(() => { window.location = '/modules/auth/views/login.php?reset=1' }, 1800)
        break
      case 'error:token_invalido':
      case 'error:token_expirado':
        showMsg(msgEl, 'El enlace ya no es válido. Solicita uno nuevo.', 'error')
        break
      case 'error:password_corta':
        showMsg(msgEl, 'La contraseña debe tener al menos 8 caracteres.', 'error')
        break
      default:
        showMsg(msgEl, 'Error al guardar. Intenta de nuevo.', 'error')
    }
  } catch {
    showMsg(msgEl, 'Error de conexión. Intenta de nuevo.', 'error')
  }
})
