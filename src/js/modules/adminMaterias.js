import { requestJson, showMsg } from '../api.js'

const BASE = '/modules/admin/controllers/materiasModel.php'
let tablaData = []
let activeTab  = 'catalogo'

// Modal helpers
document.querySelectorAll('.modal-close').forEach(btn =>
  btn.addEventListener('click', () => document.getElementById(btn.dataset.modal)?.classList.add('hidden'))
)
document.querySelectorAll('.modal-backdrop').forEach(el =>
  el.addEventListener('click', e => { if(e.target===el) el.classList.add('hidden') })
)

// Tabs
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.remove('border-primary','text-primary')
      b.classList.add('border-transparent','text-text-muted')
    })
    btn.classList.add('border-primary','text-primary')
    btn.classList.remove('border-transparent','text-text-muted')
    activeTab = btn.dataset.tab
    document.getElementById('tabCatalogo').classList.toggle('hidden', activeTab !== 'catalogo')
    document.getElementById('tabAsignaciones').classList.toggle('hidden', activeTab !== 'asignaciones')
    if (activeTab === 'asignaciones') cargarAsignaciones()
  })
})

// ── Catálogo ──────────────────────────────────────────────────────────────────
async function cargar() {
  const rows = await requestJson(`${BASE}?accion=lista`)
  tablaData  = rows
  const tb   = document.getElementById('tablaMaterias')
  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="6" class="text-center text-text-muted py-8">Sin materias</td></tr>'
    return
  }
  tb.innerHTML = rows.map(r => `
    <tr>
      <td class="font-medium">${r.nombre}</td>
      <td class="font-mono text-xs">${r.clave||'—'}</td>
      <td class="text-center">${r.creditos||'—'}</td>
      <td class="text-center">${r.grupos_asignados}</td>
      <td><span class="badge ${r.activa?'badge-success':'badge-error'}">${r.activa?'Activa':'Inactiva'}</span></td>
      <td>
        <button class="btn-ghost text-xs px-2 py-1 btnEditarMat" data-id="${r.id}">Editar</button>
      </td>
    </tr>`).join('')

  tb.querySelectorAll('.btnEditarMat').forEach(btn => {
    btn.addEventListener('click', () => {
      const r = tablaData.find(x => x.id == btn.dataset.id)
      if (!r) return
      document.getElementById('materiaId').value  = r.id
      document.getElementById('mNombre').value    = r.nombre
      document.getElementById('mClave').value     = r.clave||''
      document.getElementById('mCreditos').value  = r.creditos||5
      document.querySelector('#formMateria [name="accion"]').value = 'editar'
      document.getElementById('modalMateriaTitulo').textContent = 'Editar materia'
      document.getElementById('modalMateria').classList.remove('hidden')
    })
  })
}

// ── Asignaciones ──────────────────────────────────────────────────────────────
async function cargarAsignaciones() {
  const rows = await requestJson(`${BASE}?accion=asignaciones`)
  const tb   = document.getElementById('tablaAsignaciones')
  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="7" class="text-center text-text-muted py-8">Sin asignaciones</td></tr>'
    return
  }
  tb.innerHTML = rows.map(r => `
    <tr>
      <td>${r.grupo}</td>
      <td>${r.materia}</td>
      <td>${r.docente}</td>
      <td class="font-mono text-xs">${r.horaInicio||'—'} – ${r.horaFin||'—'}</td>
      <td>${r.dias||'—'}</td>
      <td>${r.ciclo||'—'}</td>
      <td>
        <button class="btn-ghost text-xs px-2 py-1 text-error btnQuitarAsig" data-id="${r.id}">Quitar</button>
      </td>
    </tr>`).join('')

  tb.querySelectorAll('.btnQuitarAsig').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!confirm('¿Eliminar esta asignación?')) return
      const data = new FormData()
      data.append('accion','quitar_asignacion')
      data.append('id', btn.dataset.id)
      await requestJson(BASE, { method:'POST', body:data })
      cargarAsignaciones()
    })
  })
}

// ── Abrir modal nueva materia ─────────────────────────────────────────────────
document.getElementById('btnNuevaMateria')?.addEventListener('click', () => {
  document.getElementById('formMateria').reset()
  document.getElementById('materiaId').value = ''
  document.querySelector('#formMateria [name="accion"]').value = 'crear'
  document.getElementById('modalMateriaTitulo').textContent = 'Nueva materia'
  document.getElementById('modalMateria').classList.remove('hidden')
})

document.getElementById('formMateria')?.addEventListener('submit', async e => {
  e.preventDefault()
  const res = await requestJson(BASE, { method:'POST', body: new FormData(e.target) })
  if (res.ok) { document.getElementById('modalMateria').classList.add('hidden'); cargar() }
  else showMsg(document.getElementById('msgMateria'), res.msg||'Error', 'error')
})

// ── Abrir modal asignación ────────────────────────────────────────────────────
document.getElementById('btnNuevaAsignacion')?.addEventListener('click', async () => {
  // Cargar listas
  const [docentes, grupos, materias] = await Promise.all([
    requestJson(`${BASE}?accion=docentes`),
    requestJson(`${BASE}?accion=grupos`),
    requestJson(`${BASE}?accion=lista`),
  ])
  const fillSelect = (id, items, labelKey) => {
    const sel = document.getElementById(id)
    sel.innerHTML = `<option value="">— Seleccionar —</option>` +
      items.map(i => `<option value="${i.id}">${i[labelKey]}</option>`).join('')
  }
  fillSelect('asDocente', docentes, 'nombre')
  fillSelect('asGrupo',   grupos,   'nombre')
  fillSelect('asMateria', materias, 'nombre')
  document.getElementById('modalAsignacion').classList.remove('hidden')
})

document.getElementById('formAsignacion')?.addEventListener('submit', async e => {
  e.preventDefault()
  const res = await requestJson(BASE, { method:'POST', body: new FormData(e.target) })
  if (res.ok) { document.getElementById('modalAsignacion').classList.add('hidden'); cargarAsignaciones() }
  else showMsg(document.getElementById('msgAsignacion'), res.msg||'Error', 'error')
})

cargar()
