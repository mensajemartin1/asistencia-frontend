import { requestJson } from '../api.js'
import Chart from 'chart.js/auto'

const BASE = '/modules/control_escolar/controllers/consultasModel.php'
let chartCE = null
let lastParams = ''

async function cargarFiltros() {
  try {
    const [grupos, materias, docentes] = await Promise.all([
      requestJson(`${BASE}?accion=grupos`),
      requestJson(`${BASE}?accion=materias`),
      requestJson(`${BASE}?accion=docentes`),
    ])
    const fillSel = (id, items) => {
      const sel = document.getElementById(id)
      if (!sel) return
      items.forEach(i => {
        const opt = document.createElement('option')
        opt.value = i.id; opt.textContent = i.nombre
        sel.appendChild(opt)
      })
    }
    fillSel('ceGrupo',   grupos)
    fillSel('ceMateria', materias)
    fillSel('ceDocente', docentes)
  } catch {}
}

async function buscar() {
  const alumno   = document.getElementById('ceAlumno')?.value    || ''
  const idGrupo  = document.getElementById('ceGrupo')?.value     || ''
  const idMat    = document.getElementById('ceMateria')?.value   || ''
  const idDoc    = document.getElementById('ceDocente')?.value   || ''
  const estado   = document.getElementById('ceEstado')?.value    || ''
  const fechaIni = document.getElementById('ceFechaIni')?.value  || ''
  const fechaFin = document.getElementById('ceFechaFin')?.value  || ''

  const params = new URLSearchParams({ accion:'buscar', alumno, idGrupo, idMateria:idMat, idDocente:idDoc, estado, fechaIni, fechaFin })
  lastParams = params.toString()

  try {
    const rows = await requestJson(`${BASE}?${params}`)
    renderTabla(rows)
  } catch {}

  // Gráfica
  try {
    const statsParams = new URLSearchParams({ accion:'stats_diarios', fechaIni, fechaFin })
    const stats = await requestJson(`${BASE}?${statsParams}`)
    renderChart(stats)
  } catch {}
}

function renderTabla(rows) {
  const tb = document.getElementById('tablaCE')
  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="7" class="text-center text-text-muted py-8">Sin resultados</td></tr>'
    return
  }
  const BADGES = { presente:'badge-success', falta:'badge-error', retardo:'badge-warning' }
  tb.innerHTML = rows.map(r => `
    <tr>
      <td class="font-medium">${r.alumno}</td>
      <td class="font-mono text-xs">${r.matricula}</td>
      <td>${r.grupo}</td>
      <td>${r.materia}</td>
      <td class="text-sm">${r.docente}</td>
      <td><span class="badge ${BADGES[r.estado]||''}">${r.estado}</span></td>
      <td>${r.fecha}</td>
    </tr>`).join('')
}

function renderChart(data) {
  const ctx = document.getElementById('chartCE')
  if (!ctx || !data.length) return
  if (chartCE) chartCE.destroy()
  chartCE = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: data.map(d => d.dia),
      datasets: [
        { label:'Presentes', data: data.map(d=>d.presentes), backgroundColor:'#1e40af' },
        { label:'Faltas',    data: data.map(d=>d.faltas),    backgroundColor:'#dc2626' },
      ]
    },
    options: {
      responsive:true, maintainAspectRatio:false,
      plugins:{ legend:{ position:'bottom' } },
      scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } }
    }
  })
}

document.getElementById('btnBuscarCE')?.addEventListener('click', buscar)

document.getElementById('btnExportarCE')?.addEventListener('click', e => {
  e.preventDefault()
  const params = new URLSearchParams(lastParams)
  params.set('formato','csv')
  window.location = `${BASE}?${params}`
})

cargarFiltros()
