import { request, requestJson, showMsg } from '../api.js'

const BASE = '/modules/admin/controllers/usuariosModel.php'

const ROLES = { admin:'Administrador', docente:'Docente', estudiante:'Estudiante', control_escolar:'Control Escolar' }
const ESTADOS = { activo:'badge-success', pendiente_confirmacion:'badge-warning', rechazado:'badge-error' }

let tablaData = []

// ── Modal helpers ────────────────────────────────────────────────────────────
document.querySelectorAll('.modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById(btn.dataset.modal)?.classList.add('hidden')
  })
})
document.querySelectorAll('.modal-backdrop').forEach(el => {
  el.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden') })
})

// ── Cargar tabla ─────────────────────────────────────────────────────────────
async function cargar() {
  const q   = document.getElementById('buscarUsuario').value.trim()
  const rol = document.getElementById('filtroRol').value
  try {
    const rows = await requestJson(`${BASE}?accion=lista&q=${encodeURIComponent(q)}&rol=${rol}`)
    tablaData = rows
    renderTabla(rows)
  } catch { }
}

function renderTabla(rows) {
  const tb = document.getElementById('tablaUsuarios')
  if (!rows.length) {
    tb.innerHTML = '<tr><td colspan="7" class="text-center text-text-muted py-8">Sin resultados</td></tr>'
    return
  }
  tb.innerHTML = rows.map(r => `
    <tr data-id="${r.id}">
      <td class="font-medium">${r.nombre}</td>
      <td class="text-xs">${r.correo}</td>
      <td><span class="badge badge-primary">${ROLES[r.rol]||r.rol}</span></td>
      <td><span class="badge ${ESTADOS[r.estado]||'badge-primary'}">${r.estado.replace('_',' ')}</span></td>
      <td class="text-xs">${r.campus||'—'}</td>
      <td class="text-xs">${r.created_at?.split(' ')[0]||'—'}</td>
      <td>
        <div class="flex gap-1 flex-wrap">
          <button class="btn-ghost text-xs px-2 py-1 btnEditar" data-id="${r.id}">Editar</button>
          <button class="btn-ghost text-xs px-2 py-1 btnToggle" data-id="${r.id}">
            ${r.estado==='activo'?'Desactivar':'Activar'}
          </button>
          <button class="btn-ghost text-xs px-2 py-1 text-error btnEliminar" data-id="${r.id}" data-nombre="${r.nombre}">Eliminar</button>
        </div>
      </td>
    </tr>`).join('')

  // Botones editar
  tb.querySelectorAll('.btnEditar').forEach(btn => {
    btn.addEventListener('click', () => {
      const r = tablaData.find(x => x.id == btn.dataset.id)
      if (!r) return
      document.getElementById('userId').value  = r.id
      document.getElementById('uNombre').value = r.nombre
      document.getElementById('uCorreo').value = r.correo
      document.getElementById('uRol').value    = r.rol
      document.getElementById('uEstado').value = r.estado
      document.getElementById('uCampus').value = r.campus||''
      document.querySelector('#formUsuario [name="accion"]').value = 'editar'
      document.getElementById('modalTitulo').textContent = 'Editar usuario'
      document.getElementById('passwordGroup').classList.add('hidden')
      document.getElementById('modalUsuario').classList.remove('hidden')
    })
  })

  // Toggle estado
  tb.querySelectorAll('.btnToggle').forEach(btn => {
    btn.addEventListener('click', async () => {
      const data = new FormData()
      data.append('accion', 'toggle_estado')
      data.append('id', btn.dataset.id)
      try {
        const res = await requestJson(BASE, { method:'POST', body:data })
        if (res.ok) cargar()
        else alert(res.msg||'Error')
      } catch { }
    })
  })

  // Eliminar
  tb.querySelectorAll('.btnEliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('eliminarId').value = btn.dataset.id
      document.getElementById('eliminarNombre').textContent = btn.dataset.nombre
      document.getElementById('modalEliminar').classList.remove('hidden')
    })
  })
}

// ── Nuevo usuario ─────────────────────────────────────────────────────────────
document.getElementById('btnNuevoUsuario')?.addEventListener('click', () => {
  document.getElementById('formUsuario').reset()
  document.getElementById('userId').value = ''
  document.querySelector('#formUsuario [name="accion"]').value = 'crear'
  document.getElementById('modalTitulo').textContent = 'Nuevo usuario'
  document.getElementById('passwordGroup').classList.remove('hidden')
  document.getElementById('modalUsuario').classList.remove('hidden')
})

// ── Guardar usuario ───────────────────────────────────────────────────────────
document.getElementById('formUsuario')?.addEventListener('submit', async e => {
  e.preventDefault()
  const btn = document.getElementById('btnGuardarUsuario')
  const msg = document.getElementById('msgModalUsuario')
  try {
    const res = await requestJson(BASE, { method:'POST', body: new FormData(e.target) }, btn)
    if (res.ok) {
      document.getElementById('modalUsuario').classList.add('hidden')
      cargar()
    } else {
      showMsg(msg, res.msg||'Error al guardar', 'error')
    }
  } catch { showMsg(msg, 'Error de conexión', 'error') }
})

// ── Confirmar eliminar ────────────────────────────────────────────────────────
document.getElementById('btnConfirmarEliminar')?.addEventListener('click', async () => {
  const id = document.getElementById('eliminarId').value
  const data = new FormData()
  data.append('accion','eliminar')
  data.append('id', id)
  try {
    const res = await requestJson(BASE, { method:'POST', body:data })
    if (res.ok) {
      document.getElementById('modalEliminar').classList.add('hidden')
      cargar()
    } else { alert(res.msg||'Error') }
  } catch { }
})

// ── Buscar ────────────────────────────────────────────────────────────────────
document.getElementById('btnBuscar')?.addEventListener('click', cargar)
document.getElementById('buscarUsuario')?.addEventListener('keydown', e => { if(e.key==='Enter') cargar() })

cargar()
