import { requestJson, showMsg } from '../api.js'

const BASE = '/modules/admin/controllers/gruposModel.php'
let tablaData = []

// Modal helpers
document.querySelectorAll('.modal-close').forEach(btn =>
  btn.addEventListener('click', () => document.getElementById(btn.dataset.modal)?.classList.add('hidden'))
)
document.querySelectorAll('.modal-backdrop').forEach(el =>
  el.addEventListener('click', e => { if(e.target===el) el.classList.add('hidden') })
)

async function cargar() {
  try {
    const rows = await requestJson(`${BASE}?accion=lista`)
    tablaData = rows
    const tb = document.getElementById('tablaGrupos')
    if (!rows.length) {
      tb.innerHTML = '<tr><td colspan="7" class="text-center text-text-muted py-8">No hay grupos registrados</td></tr>'
      return
    }
    tb.innerHTML = rows.map(r => `
      <tr>
        <td class="font-medium">${r.nombre}</td>
        <td class="text-sm">${r.carrera||'—'}</td>
        <td class="text-center">${r.semestre ? r.semestre+'°' : '—'}</td>
        <td>${r.campus||'—'}</td>
        <td class="text-center">${r.total_alumnos}</td>
        <td><span class="badge ${r.activo?'badge-success':'badge-error'}">${r.activo?'Activo':'Inactivo'}</span></td>
        <td>
          <div class="flex gap-1">
            <button class="btn-ghost text-xs px-2 py-1 btnEditarGrupo" data-id="${r.id}">Editar</button>
            <button class="btn-ghost text-xs px-2 py-1 btnToggleGrupo" data-id="${r.id}">
              ${r.activo?'Desactivar':'Activar'}
            </button>
          </div>
        </td>
      </tr>`).join('')

    tb.querySelectorAll('.btnEditarGrupo').forEach(btn => {
      btn.addEventListener('click', () => {
        const r = tablaData.find(x => x.id == btn.dataset.id)
        if (!r) return
        document.getElementById('grupoId').value   = r.id
        document.getElementById('gNombre').value   = r.nombre
        document.getElementById('gCarrera').value  = r.carrera||''
        document.getElementById('gSemestre').value = r.semestre||1
        document.getElementById('gCampus').value   = r.campus||''
        document.querySelector('#formGrupo [name="accion"]').value = 'editar'
        document.getElementById('modalGrupoTitulo').textContent = 'Editar grupo'
        document.getElementById('modalGrupo').classList.remove('hidden')
      })
    })

    tb.querySelectorAll('.btnToggleGrupo').forEach(btn => {
      btn.addEventListener('click', async () => {
        const data = new FormData()
        data.append('accion','toggle'); data.append('id', btn.dataset.id)
        await requestJson(BASE, { method:'POST', body:data })
        cargar()
      })
    })
  } catch(e) { console.error(e) }
}

document.getElementById('btnNuevoGrupo')?.addEventListener('click', () => {
  document.getElementById('formGrupo').reset()
  document.getElementById('grupoId').value = ''
  document.querySelector('#formGrupo [name="accion"]').value = 'crear'
  document.getElementById('modalGrupoTitulo').textContent = 'Nuevo grupo'
  document.getElementById('modalGrupo').classList.remove('hidden')
})

document.getElementById('formGrupo')?.addEventListener('submit', async e => {
  e.preventDefault()
  const msg = document.getElementById('msgGrupo')
  try {
    const res = await requestJson(BASE, { method:'POST', body: new FormData(e.target) })
    if (res.ok) { document.getElementById('modalGrupo').classList.add('hidden'); cargar() }
    else showMsg(msg, res.msg||'Error', 'error')
  } catch { showMsg(msg, 'Error de conexión', 'error') }
})

cargar()
