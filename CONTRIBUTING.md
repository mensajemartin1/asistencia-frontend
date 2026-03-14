# CONTRIBUTING.md

## Contribuir al Sistema de Asistencia

Gracias por aportar al proyecto. Este documento explica el flujo oficial para colaborar sin conflictos cuando el sistema esta dividido en modulos.

## Objetivo del flujo

- Cada integrante trabaja en su propia rama.
- Cada integrante sube su modulo en una carpeta con su nombre.
- El equipo integrador une todo en una rama `integracion`.
- Cuando todo esta validado, se hace merge a `main`.

## Estructura esperada

Cada persona debe trabajar dentro de su carpeta personal en la raiz del proyecto:

```text
asistencia-frontend/
	nombre_persona/
		modulo-x/
```

## Configuracion de remotos (fork + repo original)

Si clonaste el repo original, la configuracion recomendada es:

- `origin` -> tu fork
- `upstream` -> repositorio original

Comandos:

```bash
git remote rename origin upstream
git remote add origin https://github.com/TU-USUARIO/asistencia-frontend.git
git remote -v
```

## Pasos para contribuir

### 1) Sincronizar rama principal

```bash
git checkout main
git pull upstream main
git push origin main
```

### 2) Crear rama de trabajo

Nombra la rama por modulo:

```bash
git checkout -b feature/nombrepersona-nombremodulo
```

Ejemplo: `feature/jesus-login-asistencia`

### 3) Desarrollar en tu carpeta

- Crea o usa tu carpeta personal.
- Sube ahi tus archivos del modulo.
- Evita editar modulos de otros companeros sin coordinacion.

### 4) Guardar cambios

```bash
git add .
git commit -m "feat: agrega modulo de asistencia de nombrepersona"
git push -u origin feature/nombrepersona-nombremodulo
```

### 5) Crear Pull Request

- Crea PR desde tu rama hacia `integracion`.
- Explica que modulo agregaste o modificaste.
- Agrega evidencia si aplica (capturas, pruebas, pasos).

## Proceso de integracion (equipo integrador)

1. Revisar y aprobar PRs hacia `integracion`.
2. Probar que los modulos no rompan el proyecto.
3. Resolver conflictos en `integracion`.
4. Crear PR de `integracion` hacia `main`.
5. Hacer merge final a `main`.

## Como resolver conflictos

1. Ejecuta `git status` para ver archivos en conflicto.
2. Abre los archivos y revisa marcas `<<<<<<<`, `=======`, `>>>>>>>`.
3. Deja el codigo correcto y guarda.
4. Ejecuta:

```bash
git add .
git commit -m "fix: resuelve conflictos de merge"
git push
```

## Buenas practicas

- No hacer push directo a `main`.
- Un modulo por rama.
- Commits pequenos y claros.
- Actualiza tu rama frecuentemente con cambios de `upstream/main`.
- Si tocas archivos compartidos, avisar al equipo antes.

## Ayuda

Si tienes dudas, abre un issue o escribe al equipo integrador para revisar tu caso.
