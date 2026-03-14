# asistencia-frontend

Guia basica de Git para trabajar por modulos sin conflictos.

## Objetivo

- Cada persona trabaja en su propia rama.
- Cada persona sube su modulo dentro de una carpeta con su nombre.
- El equipo integrador junta todo en una rama de integracion.
- Al final se hace merge a `main`.

## Flujo general

1. No trabajar directo en `main`.
2. Cada compañero crea su rama `feature/nombre-modulo`.
3. Cada compañero crea su carpeta `nombre_persona/` y sube ahi su modulo.
4. Los integradores revisan y unen todo en `integracion`.
5. Cuando todo este estable, se hace merge de `integracion` a `main`.

## 1) Configuracion inicial (solo una vez por persona)

```bash
git config --global user.name "Tu Nombre"
git config --global user.email "tu_correo@ejemplo.com"
```

Si aun no tienen el repo en su maquina:

```bash
git clone <URL_DEL_REPO>
cd asistencia-frontend
```

## 2) Pasos para cada compañero (desarrollador)

Primero actualizar `main` local:

```bash
git checkout main
git pull origin main
```

Crear rama de trabajo:

```bash
git checkout -b feature/nombrepersona-nombremodulo
```

Crear carpeta personal y agregar modulo:

```bash
mkdir nombrepersona
# Copia aqui tus archivos del modulo
```

Guardar cambios y subir:

```bash
git add .
git commit -m "Agrega modulo X en carpeta nombrepersona"
git push -u origin feature/nombrepersona-nombremodulo
```

Despues abrir Pull Request (PR) hacia `integracion`.

## 3) Pasos para ustedes (integradores)

Crear rama de integracion una vez:

```bash
git checkout main
git pull origin main
git checkout -b integracion
git push -u origin integracion
```

Integrar ramas en `integracion` (por PR o por terminal):

```bash
git checkout integracion
git pull origin integracion
git merge origin/feature/juan-modulo-login
git merge origin/feature/maria-modulo-reportes
git push origin integracion
```

## 4) Si hay conflictos (normal)

1. Ver archivos en conflicto:

```bash
git status
```

2. Abrir los archivos y resolver las partes marcadas con:

- `<<<<<<<`
- `=======`
- `>>>>>>>`

3. Guardar y confirmar:

```bash
git add .
git commit -m "Resuelve conflictos de integracion"
git push
```

## 5) Merge final a main

Cuando todo funcione en `integracion`:

1. Crear PR de `integracion` hacia `main`.
2. Revisar en equipo.
3. Hacer merge a `main`.

## Reglas simples del equipo

- Una rama por modulo.
- Commits pequenos y claros.
- Hacer `git pull` antes de empezar a trabajar.
- Nunca hacer push directo a `main`.
- Todo cambio entra por PR.