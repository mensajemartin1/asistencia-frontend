import { requestJson, showMsg } from '../api.js'

const BASE     = '/modules/estudiante/controllers/onboardingModel.php'
const hasGrupo = document.body.dataset.hasGrupo === 'true'

let carreraSel  = ''
let semestreSel = ''
let grupoSel    = null   // { id, nombre, campus }
let avatarSel   = 'default'
const prefsSel  = new Set()

// ── Helpers ────────────────────────────────────────────────────────────────────
const TOTAL_STEPS = 4

function setStep(n) {
  document.querySelectorAll('.wizard-step').forEach((el, i) => {
    el.classList.toggle('active', i + 1 === n)
  })
  for (let i = 1; i <= TOTAL_STEPS; i++) {
    const dot = document.getElementById(`dot${i}`)
    if (!dot) continue
    dot.classList.remove('active', 'done')
    if (i < n)  dot.classList.add('done')
    if (i === n) dot.classList.add('active')
  }
  // update subtitle
  const subtitles = {
    1: 'Cuéntanos qué estudias para cargarte tus materias.',
    2: 'Selecciona el semestre en el que te encuentras.',
    3: 'Elige el grupo y campus donde estás inscrito.',
    4: 'Personaliza tu experiencia — opcional, puedes cambiarlo después.',
  }
  const el = document.getElementById('wizardSubtitle')
  if (el) el.textContent = subtitles[n] ?? ''
}

function selectionCard(label, sublabel, onClick) {
  const div = document.createElement('div')
  div.className = 'card card-hover cursor-pointer py-4 px-4 select-none active:scale-[0.97] transition-transform'
  div.innerHTML = `
    <p class="font-semibold text-text text-sm">${label}</p>
    ${sublabel ? `<p class="text-xs text-text-muted mt-0.5">${sublabel}</p>` : ''}`
  div.addEventListener('click', onClick)
  return div
}

// ── Step 1: Carrera ────────────────────────────────────────────────────────────
async function loadCarreras() {
  const grid = document.getElementById('carreraGrid')
  grid.innerHTML = '<div class="text-center text-text-muted text-sm py-6">Cargando…</div>'
  const carreras = await requestJson(`${BASE}?accion=carreras`)
  if (!carreras.length) {
    grid.innerHTML = '<div class="text-center text-error text-sm py-4">No hay carreras disponibles. Contacta al administrador.</div>'
    return
  }
  grid.innerHTML = ''
  carreras.forEach(c => {
    grid.appendChild(selectionCard(c, '', () => {
      carreraSel = c
      document.getElementById('carreraSelLabel').textContent = c
      loadSemestres()
      setStep(2)
    }))
  })
}

// ── Step 2: Semestre ───────────────────────────────────────────────────────────
async function loadSemestres() {
  const grid = document.getElementById('semestreGrid')
  grid.innerHTML = '<div class="col-span-3 text-center text-text-muted text-sm py-6">Cargando…</div>'
  const semestres = await requestJson(`${BASE}?accion=semestres&carrera=${encodeURIComponent(carreraSel)}`)
  if (!semestres.length) {
    grid.innerHTML = '<div class="col-span-3 text-center text-error text-sm py-4">Sin semestres disponibles.</div>'
    return
  }
  grid.innerHTML = ''
  semestres.forEach(s => {
    const div = document.createElement('div')
    div.className = 'card card-hover cursor-pointer text-center py-5 select-none active:scale-[0.97] transition-transform'
    div.innerHTML = `<p class="font-bold text-xl text-primary">${s}</p><p class="text-xs text-text-muted mt-0.5">semestre</p>`
    div.addEventListener('click', () => {
      semestreSel = String(s)
      document.getElementById('grupoSelLabel').textContent = `${carreraSel} · ${s}° semestre`
      loadGrupos()
      setStep(3)
    })
    grid.appendChild(div)
  })
}

// ── Step 3: Grupo ──────────────────────────────────────────────────────────────
async function loadGrupos() {
  const grid = document.getElementById('grupoGrid')
  grid.innerHTML = '<div class="text-center text-text-muted text-sm py-6">Cargando…</div>'
  const params = new URLSearchParams({ accion: 'grupos', carrera: carreraSel, semestre: semestreSel })
  const grupos = await requestJson(`${BASE}?${params}`)
  if (!grupos.length) {
    grid.innerHTML = '<div class="text-center text-error text-sm py-4">No hay grupos disponibles. Intenta otro semestre.</div>'
    return
  }
  grid.innerHTML = ''
  grupos.forEach(g => {
    const sublabel = [
      g.campus ? `📍 ${g.campus}` : '',
      g.inscritos ? `${g.inscritos} alumno${g.inscritos != 1 ? 's' : ''}` : '',
    ].filter(Boolean).join(' · ')
    grid.appendChild(selectionCard(g.nombre, sublabel, () => {
      grupoSel = g
      setStep(4)
    }))
  })
}

// ── Step 4: Perfil — avatar picker ────────────────────────────────────────────
document.getElementById('avatarGrid')?.querySelectorAll('.avatar-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.avatar-btn').forEach(b => {
      b.classList.remove('border-primary', 'bg-blue-50')
      b.classList.add('border-border')
    })
    btn.classList.add('border-primary', 'bg-blue-50')
    btn.classList.remove('border-border')
    avatarSel = btn.dataset.avatar
    document.getElementById('inputAvatar').value = avatarSel
  })
})

// Intereses / preferencias toggle
document.getElementById('prefsGrid')?.querySelectorAll('.pref-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const p = btn.dataset.pref
    if (prefsSel.has(p)) {
      prefsSel.delete(p)
      btn.classList.remove('border-primary', 'text-primary', 'bg-blue-50')
      btn.classList.add('border-border', 'text-text-muted')
    } else {
      prefsSel.add(p)
      btn.classList.add('border-primary', 'text-primary', 'bg-blue-50')
      btn.classList.remove('border-border', 'text-text-muted')
    }
  })
})

// ── Terminar: guardar todo ─────────────────────────────────────────────────────
document.getElementById('btnTerminar')?.addEventListener('click', async () => {
  const btn  = document.getElementById('btnTerminar')
  const msg  = document.getElementById('msgStep4')
  const nickname = document.getElementById('inputNickname')?.value.trim() ?? ''

  showMsg(msg, 'Guardando…', 'success')
  btn.disabled = true

  const data = new FormData()
  data.append('nickname',     nickname)
  data.append('avatar',       avatarSel)
  data.append('preferencias', JSON.stringify([...prefsSel]))

  if (hasGrupo) {
    // Ya tiene grupo asignado por admin — solo guardar perfil
    data.append('accion', 'completar_perfil')
  } else {
    // Vino del wizard de selección de grupo
    if (!grupoSel) {
      showMsg(msg, 'Selecciona un grupo primero.', 'error')
      btn.disabled = false
      return
    }
    data.append('accion',  'inscribir')
    data.append('idGrupo', grupoSel.id)
  }

  try {
    const res = await requestJson(BASE, { method: 'POST', body: data })
    if (res.ok) {
      showMsg(msg, '¡Listo! Cargando tu dashboard…', 'success')
      setTimeout(() => { window.location = '/modules/estudiante/views/dashboard.php' }, 900)
    } else {
      showMsg(msg, res.error || 'Error al guardar. Intenta de nuevo.', 'error')
      btn.disabled = false
    }
  } catch {
    showMsg(msg, 'Error de conexión.', 'error')
    btn.disabled = false
  }
})

// ── Back buttons ───────────────────────────────────────────────────────────────
document.getElementById('btnVolverCarrera')?.addEventListener('click',  () => setStep(1))
document.getElementById('btnVolverSemestre')?.addEventListener('click', () => setStep(2))

// ── Init ───────────────────────────────────────────────────────────────────────
if (hasGrupo) {
  // Saltar directo al perfil
  document.getElementById('stepIndicator')?.querySelectorAll('.step-line').forEach(l => {
    l.style.display = 'none'
  })
  // Marcar pasos 1-3 como completados
  for (let i = 1; i <= 3; i++) {
    const dot = document.getElementById(`dot${i}`)
    if (dot) { dot.classList.remove('active'); dot.classList.add('done') }
  }
  setStep(4)
} else {
  loadCarreras()
}
