import { request, requestJson, showMsg } from '../api.js'
import jsQR from 'jsqr'

const BASE_GRUPOS = '/modules/docente/controllers/misGruposModel.php'
const BASE_ASIST  = '/modules/docente/controllers/asistenciaModel.php'

let idGMActivo = null
let qrStream   = null
let qrAnimFrame = null

// ── Cargar selector de grupos ─────────────────────────────────────────────────
async function cargarGrupos() {
  try {
    const grupos = await requestJson(`${BASE_GRUPOS}?accion=grupos`)
    const sel    = document.getElementById('selectorGM')
    if (!grupos.length) {
      sel.innerHTML = '<option value="">Sin grupos asignados</option>'
      return
    }
    sel.innerHTML = '<option value="">— Seleccionar clase —</option>' +
      grupos.map(g => `<option value="${g.idGM}">${g.grupo} · ${g.materia} (${g.horaInicio?.slice(0,5)||'—'}–${g.horaFin?.slice(0,5)||'—'})</option>`).join('')

    // Pre-seleccionar si viene ?idGM=x en la URL
    const paramIdGM = new URLSearchParams(location.search).get('idGM')
    if (paramIdGM) { sel.value = paramIdGM; seleccionarClase(paramIdGM, grupos) }
  } catch(e) { console.error(e) }
}

document.getElementById('selectorGM')?.addEventListener('change', async e => {
  const id = e.target.value
  if (!id) { idGMActivo = null; return }
  try {
    const grupos = await requestJson(`${BASE_GRUPOS}?accion=grupos`)
    seleccionarClase(id, grupos)
  } catch {}
})

function seleccionarClase(idGM, grupos) {
  idGMActivo = idGM
  const g = grupos.find(x => x.idGM == idGM)
  if (g) {
    document.getElementById('claseInfo')?.classList.remove('hidden')
    document.getElementById('infoGrupo').textContent   = g.grupo
    document.getElementById('infoMateria').textContent = g.materia
    document.getElementById('infoHorario').textContent = `${g.horaInicio?.slice(0,5)||'—'} – ${g.horaFin?.slice(0,5)||'—'} · ${g.dias||''}`
  }
  cargarLista()
}

// ── Lista del día ─────────────────────────────────────────────────────────────
async function cargarLista() {
  if (!idGMActivo) return
  try {
    const html = await request(`${BASE_ASIST}?accion=lista_hoy&idGM=${idGMActivo}`)
    document.getElementById('tablaLista').innerHTML = html
    // Contar registros
    const filas = document.getElementById('tablaLista').querySelectorAll('tr')
    document.getElementById('contadorAsistencia').textContent = `${filas.length} registros`
  } catch {}
}

// ── Registrar (manual) ────────────────────────────────────────────────────────
document.getElementById('formAsistencia')?.addEventListener('submit', async e => {
  e.preventDefault()
  if (!idGMActivo) { alert('Selecciona una clase primero'); return }
  const btn       = document.getElementById('btnRegistrar')
  const msg       = document.getElementById('msgAsistencia')
  const matricula = document.getElementById('inputMatricula').value.trim()
  const estado    = document.getElementById('selectEstado').value

  const data = new FormData()
  data.append('accion',    'registrar')
  data.append('idGM',      idGMActivo)
  data.append('matricula', matricula)
  data.append('estado',    estado)

  try {
    const res = await requestJson(BASE_ASIST, { method:'POST', body:data }, btn)
    if (res.ok) {
      showMsg(msg, `✓ ${res.nombre} — ${res.estado}`, 'success')
      document.getElementById('inputMatricula').value = ''
      document.getElementById('inputMatricula').focus()
      cargarLista()
    } else {
      showMsg(msg, res.msg||'Error', 'error')
    }
  } catch { showMsg(msg, 'Error de conexión', 'error') }
})

// Enter en matrícula → submit
document.getElementById('inputMatricula')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); document.getElementById('formAsistencia').requestSubmit() }
})

// ── Modo QR ───────────────────────────────────────────────────────────────────
document.getElementById('btnModoManual')?.addEventListener('click', () => {
  document.getElementById('modoManual').classList.remove('hidden')
  document.getElementById('modoQR').classList.add('hidden')
  stopQR()
})

document.getElementById('btnModoQR')?.addEventListener('click', () => {
  document.getElementById('modoManual').classList.add('hidden')
  document.getElementById('modoQR').classList.remove('hidden')
  startQR()
})

async function startQR() {
  const video  = document.getElementById('qrVideo')
  const status = document.getElementById('qrStatus')
  try {
    qrStream = await navigator.mediaDevices.getUserMedia({ video:{ facingMode:'environment' } })
    video.srcObject = qrStream
    video.play()
    status.textContent = 'Apunta al código QR del alumno…'
    scanLoop()
  } catch(err) {
    status.textContent = 'No se pudo acceder a la cámara: ' + err.message
  }
}

function stopQR() {
  if (qrStream) { qrStream.getTracks().forEach(t=>t.stop()); qrStream = null }
  cancelAnimationFrame(qrAnimFrame)
}

function scanLoop() {
  const video  = document.getElementById('qrVideo')
  const status = document.getElementById('qrStatus')
  if (!qrStream) return

  const canvas = document.createElement('canvas')
  const ctx    = canvas.getContext('2d')

  function tick() {
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
      canvas.width  = video.videoWidth
      canvas.height = video.videoHeight
      ctx.drawImage(video, 0, 0)
      const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height)
      const code = jsQR(imageData.data, imageData.width, imageData.height)
      if (code) {
        status.textContent = `QR detectado: ${code.data}`
        registrarQR(code.data)
        return // pausar hasta que se procese
      }
    }
    qrAnimFrame = requestAnimationFrame(tick)
  }
  qrAnimFrame = requestAnimationFrame(tick)
}

async function registrarQR(matricula) {
  if (!idGMActivo) { alert('Selecciona una clase primero'); scanLoop(); return }
  const msg = document.getElementById('msgQR')

  const data = new FormData()
  data.append('accion',    'registrar')
  data.append('idGM',      idGMActivo)
  data.append('matricula', matricula)
  data.append('estado',    'presente')

  try {
    const res = await requestJson(BASE_ASIST, { method:'POST', body:data })
    if (res.ok) {
      showMsg(msg, `✓ ${res.nombre} — presente`, 'success')
      cargarLista()
    } else {
      showMsg(msg, res.msg||'Error', 'error')
    }
  } catch {}

  // Reanudar escaneo después de 1.5s
  setTimeout(() => {
    document.getElementById('qrStatus').textContent = 'Apunta al código QR del alumno…'
    scanLoop()
  }, 1500)
}

// Init
cargarGrupos()
