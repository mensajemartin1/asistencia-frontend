import { requestJson } from '../api.js'
import Chart from 'chart.js/auto'

const BASE = '/modules/admin/controllers/statsModel.php'

async function cargarStats() {
  try {
    const s = await requestJson(`${BASE}?tipo=global`)
    document.getElementById('statAlumnos')?.innerText !== undefined &&
      (['statAlumnos','statDocentes','statGrupos','statMaterias','statHoy']
        .forEach((id, i) => {
          const el = document.getElementById(id)
          if (el) el.textContent = [s.alumnos,s.docentes,s.grupos,s.materias,s.hoy][i] ?? '0'
        }))
  } catch(e) { console.error('stats', e) }
}

async function cargarChartSemanal() {
  try {
    const data = await requestJson(`${BASE}?tipo=asistencia_semanal`)
    if (!data.length) return
    new Chart(document.getElementById('chartSemanal'), {
      type: 'bar',
      data: {
        labels: data.map(d => d.dia),
        datasets: [
          { label:'Presentes', data: data.map(d=>d.presentes), backgroundColor:'#1e40af' },
          { label:'Faltas',    data: data.map(d=>d.faltas),    backgroundColor:'#dc2626' },
          { label:'Retardos',  data: data.map(d=>d.retardos),  backgroundColor:'#d97706' },
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}},
        scales:{ x:{stacked:false}, y:{beginAtZero:true, ticks:{stepSize:1}} } }
    })
  } catch(e) { console.error('chart semanal', e) }
}

async function cargarChartGrupos() {
  try {
    const data = await requestJson(`${BASE}?tipo=asistencia_grupos`)
    if (!data.length) return
    new Chart(document.getElementById('chartGrupos'), {
      type: 'bar',
      data: {
        labels: data.map(d => d.grupo),
        datasets: [{
          label: '% Asistencia',
          data: data.map(d => d.pct),
          backgroundColor: data.map(d => d.pct >= 80 ? '#16a34a' : d.pct >= 70 ? '#d97706' : '#dc2626'),
        }]
      },
      options: {
        indexAxis:'y', responsive:true, maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{ x:{min:0,max:100, ticks:{callback:v=>v+'%'}}, y:{} }
      }
    })
  } catch(e) { console.error('chart grupos', e) }
}

cargarStats()
cargarChartSemanal()
cargarChartGrupos()
