import { requestJson, showMsg } from '../api.js'

const BASE = '/modules/admin/controllers/estudiantesModel.php'
let grupos = []

// ── Cargar grupos para selects ─────────────────────────────────────────────────
async function cargarGrupos() {
  grupos = await requestJson(`${BASE}?accion=grupos`)

  const filtro = document.getElementById('filtroGrupo')
  const aGrupo = document.getElementById('aGrupo')

  grupos.forEach(g => {
    const label = `${g.nombre} (${g.campus})`
    if (filtro) {
      const o = document.createElement('option')
      o.value = g.id; o.textContent = label
      filtro.appendChild(o)
    }
    if (aGrupo) {
      const o = document.createElement('option')
      o.value = g.id; o.textContent = label
      aGrupo.appendChild(o)
    }
  })
}

// ── Cargar tabla ───────────────────────────────────────────────────────────────
async function cargar() {
  const campus  = document.getElementById('filtroCampus')?.value  || ''
  const idGrupo = document.getElementById('filtroGrupo')?.value   || ''
  const buscar  = document.getElementById('filtroBuscar')?.value  || ''
  const params  = new URLSearchParams({ accion: 'lista', campus, idGrupo, buscar })

  const tb = document.getElementById('tablaEstudiantes')
  tb.innerHTML = '<tr><td colspan="6" class="text-center text-text-muted py-6">Cargando…</td></tr>'

  const rows = await requestJson(`${BASE}?${params}`)

  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="6" class="text-center text-text-muted py-8">Sin resultados</td></tr>'
    return
  }

  tb.innerHTML = rows.map(r => {
    const grupoBadge = r.grupo
      ? `<span class="badge badge-primary">${r.grupo}</span>`
      : `<span class="badge badge-warning">Sin grupo</span>`
    const asignarBtn = r.idAlumno
      ? `<button class="btn-ghost text-xs py-1 px-2 btnAsignar"
              data-id="${r.idAlumno}" data-nombre="${r.nombre}"
              data-grupo="${r.idGrupo || ''}">
           Cambiar grupo
         </button>`
      : `<span class="text-text-muted text-xs">Sin cuenta vinculada</span>`
    return `<tr>
      <td class="font-medium">${r.nombre}</td>
      <td class="text-xs text-text-muted">${r.correo}</td>
      <td class="font-mono text-xs">${r.matricula || '—'}</td>
      <td>${grupoBadge}</td>
      <td class="text-sm">${r.campus || '—'}</td>
      <td>${asignarBtn}</td>
    </tr>`
  }).join('')

  // Listeners para botones de asignación
  tb.querySelectorAll('.btnAsignar').forEach(btn => {
    btn.addEventListener('click', () => abrirAsignar(btn.dataset))
  })
}

// ── Modal asignar grupo ────────────────────────────────────────────────────────
function abrirAsignar({ id, nombre, grupo }) {
  document.getElementById('aAlumnoId').value    = id
  document.getElementById('aAlumnoNombre').textContent = nombre
  const sel = document.getElementById('aGrupo')
  sel.value = grupo || ''
  showMsg(document.getElementById('msgAsignar'), '', '')
  document.getElementById('modalAsignar').classList.remove('hidden')
}

document.getElementById('formAsignar')?.addEventListener('submit', async e => {
  e.preventDefault()
  const data = new FormData()
  data.append('accion',   'asignar')
  data.append('idAlumno', document.getElementById('aAlumnoId').value)
  data.append('idGrupo',  document.getElementById('aGrupo').value)

  const res = await requestJson(BASE, { method: 'POST', body: data })
  if (res.ok) {
    document.getElementById('modalAsignar').classList.add('hidden')
    cargar()
  } else {
    showMsg(document.getElementById('msgAsignar'), res.error || 'Error al guardar', 'error')
  }
})

// ── Modal close ────────────────────────────────────────────────────────────────
document.querySelectorAll('.modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById(btn.dataset.modal)?.classList.add('hidden')
  })
})

// ── Buscar ─────────────────────────────────────────────────────────────────────
document.getElementById('btnBuscarEst')?.addEventListener('click', cargar)
document.getElementById('filtroBuscar')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') cargar()
})

// ── Init ───────────────────────────────────────────────────────────────────────
cargarGrupos().then(cargar)
