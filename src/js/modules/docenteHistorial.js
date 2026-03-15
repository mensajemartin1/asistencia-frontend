import { requestJson } from '../api.js'

const BASE = '/modules/docente/controllers/historialModel.php'
const BASE_GRUPOS = '/modules/docente/controllers/misGruposModel.php'

let lastParams = ''

async function cargarSelector() {
  try {
    const grupos = await requestJson(`${BASE_GRUPOS}?accion=grupos`)
    const sel    = document.getElementById('filtroGM')
    if (!sel) return
    grupos.forEach(g => {
      const opt = document.createElement('option')
      opt.value = g.idGM
      opt.textContent = `${g.grupo} · ${g.materia}`
      sel.appendChild(opt)
    })
  } catch {}
}

async function buscar() {
  const idGM      = document.getElementById('filtroGM')?.value       || ''
  const estado    = document.getElementById('filtroEstado')?.value   || ''
  const fechaIni  = document.getElementById('filtroFechaIni')?.value || ''
  const fechaFin  = document.getElementById('filtroFechaFin')?.value || ''

  const params = new URLSearchParams({ idGM, estado, fechaIni, fechaFin })
  lastParams = params.toString()

  try {
    const rows = await requestJson(`${BASE}?${params}`)
    const tb   = document.getElementById('tablaHistorial')
    if (!rows.length) {
      tb.innerHTML = '<tr><td colspan="7" class="text-center text-text-muted py-8">Sin resultados para los filtros aplicados</td></tr>'
      return
    }
    const BADGES = { presente:'badge-success', falta:'badge-error', retardo:'badge-warning' }
    tb.innerHTML = rows.map(r => `
      <tr>
        <td>${r.nombre}</td>
        <td class="font-mono text-xs">${r.matricula}</td>
        <td>${r.grupo}</td>
        <td>${r.materia}</td>
        <td><span class="badge ${BADGES[r.estado]||'badge-primary'}">${r.estado}</span></td>
        <td>${r.fecha}</td>
        <td class="text-xs text-text-muted">${r.hora||'—'}</td>
      </tr>`).join('')
  } catch(e) { console.error(e) }
}

document.getElementById('btnBuscarHistorial')?.addEventListener('click', buscar)

document.getElementById('btnExportarCSV')?.addEventListener('click', e => {
  e.preventDefault()
  const url = `${BASE}?${lastParams}&formato=csv`
  window.location = url
})

cargarSelector()
