import { requestJson } from '../api.js'

const BASE_GRUPOS = '/modules/docente/controllers/misGruposModel.php'

const DIAS_MAP = { L:'Lun', M:'Mar', X:'Mié', J:'Jue', V:'Vie', S:'Sáb', D:'Dom' }

function formatDias(dias) {
  return (dias || '').split('').map(d => DIAS_MAP[d] || d).join(' · ')
}

async function init() {
  // ── Clase activa ahora ────────────────────────────────────────────────────────
  try {
    const activa  = await requestJson(`${BASE_GRUPOS}?accion=clase_activa`)
    const card    = document.getElementById('claseActivaCard')
    const sinCard = document.getElementById('sinClaseCard')
    if (activa) {
      document.getElementById('claseActivaNombre').textContent = activa.materia
      document.getElementById('claseActivaGrupo').textContent  =
        `${activa.grupo}${activa.campus ? ' · ' + activa.campus : ''} · ${activa.horaInicio?.slice(0,5)} – ${activa.horaFin?.slice(0,5)}`
      const btn = document.getElementById('btnIrAsistencia')
      if (btn) btn.href = `/modules/docente/views/asistencia.php?idGM=${activa.idGM}`
      card?.classList.remove('hidden')
      sinCard?.classList.add('hidden')
    }
  } catch { /* sin clase activa */ }

  // ── Grupos agrupados por campus ───────────────────────────────────────────────
  try {
    const grupos = await requestJson(`${BASE_GRUPOS}?accion=grupos`)
    const wrap   = document.getElementById('gridGrupos')
    if (!wrap) return

    if (!grupos.length) {
      wrap.innerHTML = '<div class="card text-text-muted text-sm py-8 text-center col-span-full">No tienes grupos asignados este ciclo.</div>'
      return
    }

    // Agrupar por campus
    const byCampus = {}
    grupos.forEach(g => {
      const key = g.campus || 'Sin campus'
      if (!byCampus[key]) byCampus[key] = []
      byCampus[key].push(g)
    })

    const multipleCampuses = Object.keys(byCampus).length > 1
    let html = ''

    Object.entries(byCampus).forEach(([campus, items]) => {
      if (multipleCampuses) {
        html += `
          <div class="col-span-full flex items-center gap-3 mt-4 first:mt-0">
            <div class="flex items-center gap-2">
              <div class="w-2.5 h-2.5 rounded-full bg-primary shrink-0"></div>
              <h3 class="font-bold text-primary-dark text-sm uppercase tracking-wide">${campus}</h3>
            </div>
            <div class="flex-1 h-px bg-border"></div>
            <span class="text-xs text-text-muted">${items.length} grupo${items.length !== 1 ? 's' : ''}</span>
          </div>`
      }
      items.forEach(g => {
        html += `
          <a href="/modules/docente/views/asistencia.php?idGM=${g.idGM}" class="card card-hover block group">
            <div class="flex items-start justify-between gap-2 mb-2">
              <div class="flex-1 min-w-0">
                <p class="font-bold text-text text-sm leading-tight">${g.materia}</p>
                <p class="text-xs text-text-muted mt-0.5">${g.grupo}${g.semestre ? ' · ' + g.semestre + '° sem' : ''}</p>
              </div>
              <span class="badge badge-primary text-[10px] shrink-0">${g.ciclo}</span>
            </div>
            <div class="flex items-center justify-between text-xs text-text-muted mt-3">
              <span>${g.horaInicio?.slice(0,5) || '—'} – ${g.horaFin?.slice(0,5) || '—'}</span>
              <span>${formatDias(g.dias)}</span>
            </div>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-text-muted">${g.total_alumnos} alumnos</span>
              <span class="text-xs font-semibold text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                Pasar lista →
              </span>
            </div>
          </a>`
      })
    })

    wrap.innerHTML = html
  } catch(e) { console.error(e) }
}

init()
