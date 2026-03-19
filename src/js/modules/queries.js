import { request, requestJson } from '../api.js'
import Chart from 'chart.js/auto'

const tabla = document.getElementById('tabla')
const materiaSelect = document.getElementById('materia_id')
let grafica = null

async function cargar(params) {
  tabla.innerHTML = '<tr><td colspan="6" class="table__empty">Cargando...</td></tr>'
  try {
    const html = await request('../controllers/consultasModel.php?' + new URLSearchParams(params))
    tabla.innerHTML = html
  } catch {
    tabla.innerHTML = '<tr><td colspan="6" class="table__empty">Error al cargar datos</td></tr>'
  }
}

async function cargarGrafica(grupo) {
  try {
    const datos = await requestJson('../controllers/grafica_grupo.php?grupo=' + encodeURIComponent(grupo))

    if (grafica) grafica.destroy()

    grafica = new Chart(document.getElementById('graficaGrupo'), {
      type: 'pie',
      data: {
        labels: datos.map(d => d.nombre),
        datasets: [{
          data: datos.map(d => d.total),
          backgroundColor: ['#3b82f6','#16a34a','#d97706','#dc2626','#8b5cf6','#0891b2']
        }]
      },
      options: { plugins: { legend: { position: 'bottom' } } }
    })
  } catch {
    console.error('Error al cargar gráfica')
  }
}

async function cargarMaterias() {
  if (!materiaSelect) return

  try {
    const materias = await requestJson('../controllers/consultasModel.php?accion=materias')
    const opts = ['<option value="">Seleccionar...</option>']

    materias.forEach((m) => {
      opts.push(`<option value="${m.id}">${m.nombre}</option>`)
    })

    materiaSelect.innerHTML = opts.join('')
  } catch {
    materiaSelect.innerHTML = '<option value="">Sin materias</option>'
  }
}

// Botones
document.getElementById('btnHistorial')?.addEventListener('click', () =>
  cargar({ accion: 'historial' }))

document.getElementById('btnAlumno')?.addEventListener('click', () => {
  const id = document.getElementById('alumno_id').value
  if (id) cargar({ accion: 'porAlumno', alumno_id: id })
})

document.getElementById('btnGrupo')?.addEventListener('click', () => {
  const grupo = document.getElementById('grupo').value
  if (grupo) {
    cargar({ accion: 'porGrupo', grupo })
    cargarGrafica(grupo)
  }
})

document.getElementById('btnMateria')?.addEventListener('click', () => {
  const id = document.getElementById('materia_id').value
  if (id) cargar({ accion: 'porMateria', materia_id: id })
})

document.getElementById('btnFecha')?.addEventListener('click', () => {
  const fecha = document.getElementById('fecha').value
  if (fecha) cargar({ accion: 'porFecha', fecha })
})

cargarMaterias()
