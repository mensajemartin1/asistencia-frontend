/**
 * Wrapper de fetch con manejo de errores, loading state y respuesta unificada.
 * Todos los módulos usan esto en vez de fetch() directo.
 */

/**
 * @param {string} url
 * @param {object} options  — mismas opciones que fetch()
 * @param {HTMLElement|null} btnEl — botón que disparó la acción (para loading state)
 * @returns {Promise<string>} texto de respuesta
 */
export async function request(url, options = {}, btnEl = null) {
  if (btnEl) setLoading(btnEl, true)

  try {
    const response = await fetch(url, options)

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`)
    }

    return await response.text()

  } catch (err) {
    console.error('[API error]', url, err)
    throw err

  } finally {
    if (btnEl) setLoading(btnEl, false)
  }
}

/**
 * Igual que request() pero parsea la respuesta como JSON.
 */
export async function requestJson(url, options = {}, btnEl = null) {
  if (btnEl) setLoading(btnEl, true)

  try {
    const response = await fetch(url, options)

    if (!response.ok) throw new Error(`HTTP ${response.status}`)

    return await response.json()

  } catch (err) {
    console.error('[API error]', url, err)
    throw err

  } finally {
    if (btnEl) setLoading(btnEl, false)
  }
}

/**
 * Muestra/oculta el estado de carga en un botón.
 */
export function setLoading(btnEl, loading) {
  if (!btnEl) return
  if (loading) {
    btnEl.disabled = true
    btnEl.classList.add('btn--loading')
    btnEl._originalText = btnEl.textContent
  } else {
    btnEl.disabled = false
    btnEl.classList.remove('btn--loading')
    if (btnEl._originalText) btnEl.textContent = btnEl._originalText
  }
}

/**
 * Muestra un mensaje de feedback (éxito o error) en un elemento del DOM.
 * @param {HTMLElement} el
 * @param {string} text
 * @param {'success'|'error'} type
 */
export function showMsg(el, text, type = 'success') {
  if (!el) return
  el.textContent = text
  el.className = `msg visible ${type}`
  setTimeout(() => {
    el.className = 'msg'
  }, 4000)
}
