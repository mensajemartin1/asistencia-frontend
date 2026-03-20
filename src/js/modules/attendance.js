import { request, showMsg } from '../api.js'

const form   = document.getElementById('formAsistencia')
const tabla  = document.getElementById('tablaAsistencia')
const msgEl  = document.getElementById('mensajeRegistro')
const btnEl  = document.getElementById('btnRegistrar')

async function cargarAsistencia() {
  tabla.classList.add('table--loading')
  try {
    const html = await request('../controllers/obtener_asistencia.php')
    tabla.innerHTML = html
  } catch {
    tabla.innerHTML = '<tr><td colspan="6" class="table__empty">Error al cargar registros</td></tr>'
  } finally {
    tabla.classList.remove('table--loading')
  }
}

form?.addEventListener('submit', async (e) => {
  e.preventDefault()

  const data = new FormData(form)

  try {
    const res = await request('../controllers/guardar_asistencia.php', { method: 'POST', body: data }, btnEl)
    if (res.trim() === 'ok') {
      form.reset()
      showMsg(msgEl, 'Asistencia registrada correctamente', 'success')
      await cargarAsistencia()
    } else {
      showMsg(msgEl, res, 'error')
    }
  } catch {
    showMsg(msgEl, 'Error al registrar. Intenta de nuevo.', 'error')
  }
})

// Enter en campo matrícula dispara el formulario
document.getElementById('matricula')?.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    e.preventDefault()
    form.requestSubmit()
  }
})

// Carga inicial
cargarAsistencia()
