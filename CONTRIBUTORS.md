# Colaboradores — Sistema de Control de Asistencia ITSZ

Proyecto integrador desarrollado por estudiantes de **Ingeniería en Sistemas Computacionales, 8° Semestre** del Instituto Tecnológico Superior de Zongolica — TecNM, ciclo escolar 2025–2026.

---

## Asesor del proyecto

### Ing. S.C. Martín Contreras de la Cruz
**Rol:** Docente · Asesor del proyecto integrador

**Contribuciones:**
- Responsable de la materia de **Desarrollo de Sistemas** en el marco del programa de ISC del ITSZ.
- Asesoría en el diseño y estructuración de la **base de datos** del sistema.
- Orientación técnica y académica durante el desarrollo del proyecto integrador.
- Revisión y validación de los requerimientos del sistema de control de asistencia.

---

## Equipo de desarrollo

---

### Jesus Alberto Rodriguez Puertos
**GitHub:** `Jesus-Puertos`
**Correo institucional:** `226w0496@zongolica.tecnm.mx`
**Rol:** Integrador principal · Arquitecto del sistema

**Actividades y contribuciones:**
- Diseño e implementación de la **arquitectura general del sistema**: estructura de módulos, convenciones de carpetas, router PHP.
- Configuración del **entorno de desarrollo**: Vite 6, Tailwind CSS v4, PHP built-in server con `router.php`.
- Diseño del **schema de base de datos** (schema_v3.sql) con tablas, relaciones, índices y migraciones v5/v6.
- Implementación del **sistema de autenticación dual**: Portal de Alumnos (auto-registro) y Portal Institucional (creado por admin), con recuperación de contraseña por correo.
- Desarrollo del **módulo de onboarding** del alumno: wizard de 4 pasos (carrera → semestre → grupo → perfil) con avatar e intereses en JSON para gamificación futura.
- Diseño del **layout mobile-first** para el portal del estudiante: appbar, bottom navigation, tarjetas de materia con porcentaje y progreso visual.
- Implementación de **QR por materia** en el dashboard del alumno con modal de pantalla completa que codifica `matricula:idGrupoMateria`.
- Desarrollo del **dashboard del docente** agrupado por campus, con vista de horarios, grupos y materias asignadas.
- Implementación del **scope por campus** para el módulo de Control Escolar: todas las consultas y reportes filtrados por campus del usuario.
- Configuración del **sistema de roles**: `admin`, `docente`, `estudiante`, `control_escolar` con protección de rutas mediante `auth_check.php` y `role_check.php`.
- Creación del `README.md`, `CONTRIBUTING.md`, `CONTRIBUTORS.md`, `IMPROVEMENTS.md` y `LICENSE`.
- **Integración y revisión** de todos los módulos del equipo en la rama `main`.

---

### Jose Bernadino Tlehuactle Ortega
**GitHub:** `joseorteha`
**Correo:** `joseortegahac@gmail.com`
**Rol:** Administrador del repositorio · Integrador de ramas · Desarrollo de UI

**Actividades y contribuciones:**
- **Administrador principal del repositorio** en GitHub: creó el repo, configuró ramas y revisó/aprobó todos los Pull Requests del equipo (PRs #1 al #14).
- Desarrollo y diseño de la **landing page principal** (`index.php`): sección hero con gradiente, tarjeta de estado del sistema, grid de módulos, sección de campus de la red ITSZ y oferta educativa.
- Creación y aportación del **logo SVG vectorial del ITSZ** (`jose/imagenes/logo-itsz.svg`) utilizado en todo el sistema (appbar, navbar, login, footer).
- Coordinación del flujo de trabajo colaborativo: revisión de merges y resolución de conflictos entre ramas de todos los integrantes.

---

### Arlyn Alfaro Dominguez
**GitHub:** `Arlyn-Al`
**Correo institucional:** `226w0844@zongolica.tecnm.mx`
**Rol:** Desarrolladora — Módulo de Reportes PDF

**Actividades y contribuciones:**
- Desarrollo del **módulo de generación de reportes PDF** (`arlyn/generar_reporte.php`): genera reportes de asistencia por alumno y grupo con formato institucional ITSZ.
- Integración y configuración de la **librería FPDF** (`arlyn/fpdf.php`) para generar documentos PDF desde PHP sin dependencias externas adicionales.
- Diseño del layout del reporte PDF: encabezado con nombre institucional, tabla de registros de asistencia con fecha, materia y estado, porcentaje calculado por materia.

---

### Hector Ayohua Quechulpa
**GitHub:** `Srurg`
**Correo institucional:** `226w0454@zongolica.tecnm.mx`
**Rol:** Desarrollador — Módulo de Registro de Asistencia

**Actividades y contribuciones:**
- Desarrollo de la **vista principal del registro de asistencia** (`hector/index.php`): interfaz web completa para que el docente registre la asistencia de alumnos por matrícula.
- Implementación del **controlador para guardar asistencia** (`hector/guardar_asistencia.php`): recibe matrícula, valida alumno en base de datos, detecta la clase activa por horario y registra el estado (presente, falta, retardo).
- Desarrollo de la **API para obtener registros de asistencia** (`hector/obtener_asistencia.php`): consulta y retorna en JSON la asistencia del grupo filtrada por materia y fecha.
- Configuración de la **conexión a base de datos** del módulo (`hector/database.php`).

---

### Araceli Tlehuactle Tepole
**GitHub:** `226w0451`
**Correo institucional:** `226w0451@zongolica.tecnm.mx`
**Rol:** Desarrolladora — Módulo de Consultas y Estadísticas

**Actividades y contribuciones:**
- Desarrollo de la **vista de consultas de asistencia** (`Araceli/consultasView.php`): interfaz con filtros combinables por alumno, grupo, materia y rango de fechas, con tabla de resultados.
- Implementación del **modelo PHP de consultas** (`Araceli/consultasModel.php`): queries SQL parametrizadas para filtrar y agrupar registros de asistencia con múltiples criterios simultáneos.
- Desarrollo del **controlador JavaScript de consultas** (`Araceli/consultasController.js`): maneja eventos del formulario de filtros, realiza peticiones AJAX al modelo PHP y actualiza la vista dinámicamente sin recargar la página.
- Implementación de la **gráfica de asistencia por grupo** (`Araceli/grafica_grupo.php`): visualización gráfica del porcentaje de asistencia por materia usando Chart.js con colores por umbral (verde/amarillo/rojo).
- Configuración de la conexión a base de datos del módulo (`Araceli/db.php`).

---

### Luis Almir Dominguez Puertos
**GitHub:** `luisalmir`
**Correo institucional:** `226w0417@zongolica.tecnm.mx`
**Rol:** Desarrollador — Módulo de Autenticación

**Actividades y contribuciones:**
- Desarrollo de la **vista de inicio de sesión** (`luis/login.html`): formulario de login con diseño responsivo, validación de campos de correo y contraseña.
- Implementación del **controlador JavaScript de login** (`luis/login.js`): maneja el envío del formulario con Fetch API, procesa la respuesta del servidor y redirige al módulo correspondiente según el rol del usuario.
- Desarrollo del **modelo PHP de autenticación** (`luis/loginModel.php`): valida credenciales contra la base de datos usando `password_verify()`, inicia la sesión con `$_SESSION` y retorna el rol del usuario autenticado.
- Implementación del **cierre de sesión** (`luis/logout.php`): destruye la sesión activa y redirige al login de forma segura.
- Desarrollo del módulo de **registro de asistencia** (`luis/asistencia.php`): formulario para capturar matrícula del alumno y registrar su presencia en clase.
- Configuración de la conexión a base de datos del módulo (`luis/db.php`).

---

### Reinaldo Ajactle Choncoa
**Rol:** Desarrollador — Base de datos inicial

**Actividades y contribuciones:**
- Diseño e implementación del **script SQL inicial de la base de datos** (`martin/CREATE DATABASE Zongolica.sql`): definición de la estructura de tablas `Usuarios`, `Alumnos`, `Materias` y `Asistencias` con sus campos, tipos de dato y relaciones entre tablas.
- Revisión y corrección del schema SQL: ajuste de tamaños de campos (`VARCHAR`), adición de comentarios de documentación y refinamiento de restricciones.

---

### Blanca Rosa Diaz Hernandez
**Rol:** Desarrolladora — Análisis y documentación

**Actividades y contribuciones:**
- Participación en el análisis de requerimientos del sistema de control de asistencia.
- Contribución al diseño y planificación del proyecto integrador.

---

### Luis Adrian Gutierrez Atlahua
**Rol:** Desarrollador — Análisis y desarrollo

**Actividades y contribuciones:**
- Participación en el análisis de requerimientos del sistema de control de asistencia.
- Contribución al diseño y planificación del proyecto integrador.

---

### Alejandro Hernandez Tepole
**Rol:** Desarrollador — Análisis y desarrollo

**Actividades y contribuciones:**
- Participación en el análisis de requerimientos del sistema de control de asistencia.
- Contribución al diseño y planificación del proyecto integrador.

---

### Kevin Emanuel Ixmatlahua Barojas
**Rol:** Desarrollador — Análisis y desarrollo

**Actividades y contribuciones:**
- Participación en el análisis de requerimientos del sistema de control de asistencia.
- Contribución al diseño y planificación del proyecto integrador.

---

## Resumen de módulos por integrante

| Integrante | Módulo / Área principal |
|---|---|
| Jesus Alberto Rodriguez Puertos | Arquitectura, autenticación dual, onboarding, dashboard alumno/docente, control escolar, integración general |
| Jose Bernadino Tlehuactle Ortega | Admin del repositorio, landing page, logo ITSZ SVG |
| Arlyn Alfaro Dominguez | Generación de reportes PDF con FPDF |
| Hector Ayohua Quechulpa | Registro de asistencia (vista + API controladores) |
| Araceli Tlehuactle Tepole | Consultas, filtros avanzados y gráficas de asistencia |
| Luis Almir Dominguez Puertos | Módulo de login, logout y registro de asistencia |
| Reinaldo Ajactle Choncoa | Schema SQL inicial de la base de datos |
| Blanca Rosa Diaz Hernandez | Análisis de requerimientos y planificación |
| Luis Adrian Gutierrez Atlahua | Análisis de requerimientos y planificación |
| Alejandro Hernandez Tepole | Análisis de requerimientos y planificación |
| Kevin Emanuel Ixmatlahua Barojas | Análisis de requerimientos y planificación |

---

## Institución

**Instituto Tecnológico Superior de Zongolica**
TecNM · Campus Zongolica · Altas Montañas, Veracruz, México

---

*© 2025–2026 Equipo ISC 8° — ITSZ. Proyecto académico con fines institucionales.*
