# Guía de Contribución

Gracias por contribuir al **Sistema de Control de Asistencia ITSZ**. Esta guía explica el flujo de trabajo del equipo para colaborar sin conflictos.

---

## Flujo general de trabajo

```
main  ←──── integracion  ←──── feature/nombre-modulo
                                (cada integrante)
```

1. Cada integrante trabaja en su propia rama `feature/`.
2. Los cambios van a `integracion` mediante Pull Request.
3. Cuando todo está validado, `integracion` se fusiona con `main`.
4. **Nunca se hace push directo a `main`.**

---

## Configuración inicial (solo una vez)

```bash
git config --global user.name "Tu Nombre Completo"
git config --global user.email "matricula@zongolica.tecnm.mx"

git clone https://github.com/joseorteha/asistencia-frontend.git
cd asistencia-frontend

npm install
npm run build
php -S localhost:3000 router.php
```

---

## Convenciones de ramas

| Tipo | Formato | Ejemplo |
|---|---|---|
| Nueva funcionalidad | `feature/nombre-descripcion` | `feature/arlyn-modulo-reportes` |
| Corrección de bug | `fix/descripcion` | `fix/jesus-login-redirect` |
| Mejora de estilos | `style/descripcion` | `style/kevin-dashboard-mobile` |

---

## Flujo de trabajo diario

### 1. Sincronizar antes de empezar

```bash
git checkout main
git pull origin main
```

### 2. Crear o retomar tu rama

```bash
# Nueva rama
git checkout -b feature/tunombre-modulo

# Retomar rama existente
git checkout feature/tunombre-modulo
git pull origin feature/tunombre-modulo
```

### 3. Hacer cambios y confirmarlos

Usa el formato **Conventional Commits** para los mensajes:

```bash
git add archivo1.php src/js/modules/miModulo.js
git commit -m "feat: agrega vista de historial para el alumno"
```

| Prefijo | Cuándo usarlo |
|---|---|
| `feat:` | Nueva funcionalidad |
| `fix:` | Corrección de bug |
| `style:` | Cambios de CSS o UI sin lógica |
| `refactor:` | Reorganización de código |
| `docs:` | Solo documentación |
| `chore:` | Tareas de mantenimiento (build, deps) |

### 4. Subir la rama

```bash
git push -u origin feature/tunombre-modulo
```

### 5. Abrir Pull Request

- Desde GitHub, crea un PR de tu rama hacia `integracion`.
- Título claro: `feat: módulo de asistencia docente`
- Describe qué hiciste, qué archivos tocaste y cómo probarlo.
- Agrega capturas de pantalla si es una vista nueva.

---

## Estructura del proyecto

Al agregar archivos nuevos, respeta la estructura existente:

```
modules/
  tu_modulo/
    views/        ← archivos .php (HTML + PHP)
    controllers/  ← archivos Model.php (JSON API)

src/js/modules/
  tuModulo.js     ← lógica JS del módulo

config/partials/  ← componentes reutilizables (head, footer, navbar)
database/         ← migraciones SQL
```

Si tu módulo necesita un nuevo `data-page`, agrégalo en `src/js/main.js`:

```js
if (page === 'mi-pagina') import('./modules/miModulo.js')
```

Y después ejecuta:

```bash
npm run build
```

---

## Proceso de integración (Jesus / integrador)

1. Revisar y aprobar PRs hacia `integracion`.
2. Verificar que los módulos no rompan rutas ni base de datos.
3. Resolver conflictos en `integracion`.
4. Ejecutar `npm run build` y probar en local.
5. Crear PR de `integracion` → `main` con resumen de cambios.

---

## Resolver conflictos de merge

```bash
# Ver archivos en conflicto
git status

# Abrir el archivo, buscar y resolver:
# <<<<<<< HEAD
#   tu código
# =======
#   código del otro
# >>>>>>> rama-de-origen

# Confirmar resolución
git add .
git commit -m "fix: resuelve conflictos de merge en modulo X"
git push
```

---

## Buenas prácticas

- **Un PR por funcionalidad** — no mezcles módulos en un solo PR.
- **Commits pequeños y frecuentes** — facilita la revisión.
- **No modifiques archivos de otros** sin coordinación previa.
- **Comenta el código** cuando la lógica no sea evidente.
- **Prueba en local antes de hacer push** — ejecuta el servidor y verifica tu módulo.
- Si tocas `config/database.php`, `router.php` o `src/css/main.css`, avisa al integrador.

---

## Archivos que NO debes subir

Asegúrate de que `.gitignore` excluya:

```
node_modules/
public/assets/bundle/
*.env
config/database.php   ← cada quien tiene sus credenciales locales
```

---

## Dudas

Abre un **Issue** en GitHub o comunícate directamente con el integrador del equipo.
