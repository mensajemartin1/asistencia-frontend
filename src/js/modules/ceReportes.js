const BASE = '/modules/control_escolar/controllers/reportesModel.php'

document.querySelectorAll('.btnReporteGrupo').forEach(btn => {
  btn.addEventListener('click', () => {
    const idGrupo  = document.getElementById('rGrupo')?.value
    const fechaIni = document.getElementById('rGrupoFechaIni')?.value
    const fechaFin = document.getElementById('rGrupoFechaFin')?.value
    const formato  = btn.dataset.formato
    if (!idGrupo) { alert('Selecciona un grupo'); return }
    const url = `${BASE}?tipo=grupo&formato=${formato}&idGrupo=${idGrupo}&fechaIni=${fechaIni}&fechaFin=${fechaFin}`
    window.open(url, '_blank')
  })
})

document.querySelectorAll('.btnReporteAlumno').forEach(btn => {
  btn.addEventListener('click', () => {
    const idAlumno = document.getElementById('rAlumno')?.value
    const fechaIni = document.getElementById('rAlumnoFechaIni')?.value
    const fechaFin = document.getElementById('rAlumnoFechaFin')?.value
    const formato  = btn.dataset.formato
    if (!idAlumno) { alert('Selecciona un alumno'); return }
    const url = `${BASE}?tipo=alumno&formato=${formato}&idAlumno=${idAlumno}&fechaIni=${fechaIni}&fechaFin=${fechaFin}`
    window.open(url, '_blank')
  })
})
