# Sistema de Control de Asistencia — ITSZ

> Plataforma institucional para el registro, consulta y generación de reportes de asistencia del **Instituto Tecnológico Superior de Zongolica** (TecNM · Campus Zongolica y unidades académicas de la región Altas Montañas, Veracruz).

---

## Descripción

Sistema web desarrollado como proyecto integrador por estudiantes de Ingeniería en Sistemas Computacionales del ITSZ. Permite a docentes registrar asistencia mediante códigos QR, a alumnos consultar su historial en tiempo real y a personal administrativo generar reportes por grupo, materia, campus y fecha.

---

## Características principales

| Módulo | Descripción |
|---|---|
| **Portal de Alumnos** | Registro propio, onboarding con selección de carrera/semestre/grupo, perfil con avatar e intereses, código QR por materia |
| **Portal Docente** | Dashboard agrupado por campus, lista de grupos y materias por horario, registro de asistencia con QR |
| **Portal Admin** | Gestión de usuarios, grupos, materias, asignación de docentes, vista de alumnos por grupo |
| **Control Escolar** | Consultas y reportes filtrados por campus propio, exportación a PDF |
| **Onboarding** | Wizard guiado de 4 pasos (carrera → semestre → grupo → perfil) que se muestra siempre hasta completarse |
| **Autenticación** | Dos portales separados: alumnos (auto-registro) y personal institucional (creado por admin), recuperación de contraseña por correo |

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Frontend — estilos | Tailwind CSS v4 |
| Frontend — scripts | Vanilla JS (ES Modules), Vite 6 como bundler |
| Backend | PHP 8.4 |
| Base de datos | MySQL 8 |
| Servidor de desarrollo | PHP built-in server (`php -S localhost:3000 router.php`) |
| QR | `qrcode` npm package |
| Gráficas | Chart.js |
| Fuentes | Inter + Merriweather (Google Fonts) |

---

## Arquitectura de directorios

```
asistencia-frontend/
├── config/
│   ├── database.php          # Conexión MySQL (getConnection)
│   ├── auth_check.php        # Verificación de sesión
│   ├── role_check.php        # requireRole()
│   └── partials/             # head.php, footer.php, navbar.php,
│                             # student_appbar.php, student_footer.php, sidebar.php
├── database/
│   ├── schema_v3.sql         # Schema completo para instalación limpia ← usar este
│   ├── migrate_v5.sql        # Alumnos.idGrupo → nullable
│   └── migrate_v6.sql        # Campos de perfil + onboarding_ok
├── modules/
│   ├── auth/                 # Login, registro, recuperación de contraseña
│   ├── admin/                # Usuarios, grupos, materias, estudiantes
│   ├── docente/              # Dashboard, registro de asistencia, historial
│   ├── estudiante/           # Dashboard, historial, onboarding
│   └── control_escolar/      # Consultas y reportes (scope por campus)
├── public/
│   ├── index.php             # Landing page
│   └── assets/
│       ├── img/              # Logo ITSZ SVG y otros
│       └── bundle/           # Archivos generados por Vite (no editar)
├── src/
│   ├── css/main.css          # Estilos globales + componentes Tailwind
│   └── js/
│       ├── main.js           # Entry point Vite — enruta módulos por data-page
│       ├── api.js            # request(), requestJson(), showMsg()
│       └── modules/          # Un archivo JS por vista
├── router.php                # Router para PHP built-in server
└── vite.config.js
```

---

## Instalación y configuración

### Requisitos previos

- PHP 8.1 o superior con extensiones: `mysqli`, `mbstring`, `json`
- MySQL 8.0 o superior
- Node.js 18 o superior
- Composer (opcional, no requerido actualmente)

### 1. Clonar el repositorio

```bash
git clone https://github.com/joseorteha/asistencia-frontend.git
cd asistencia-frontend
```

### 2. Configurar la base de datos

Edita `config/database.php` con tus credenciales MySQL y ejecuta el schema limpio:

```bash
mysql -u root -p < database/schema_v3.sql
```

> Si ya tienes una base de datos con datos, ejecuta solo las migraciones pendientes (`migrate_v5.sql`, `migrate_v6.sql`) y luego:
> ```sql
> UPDATE Alumnos SET onboarding_ok = 1 WHERE idGrupo IS NOT NULL;
> ```

### 3. Instalar dependencias y compilar assets

```bash
npm install
npm run build
```

### 4. Levantar el servidor de desarrollo

```bash
php -S localhost:3000 router.php
```

Abre [http://localhost:3000](http://localhost:3000) en tu navegador.

> **Nota:** Cada vez que modifiques archivos en `src/`, ejecuta `npm run build` para regenerar el bundle.

---

## Portales de acceso

| Portal | URL | Roles |
|---|---|---|
| Portal de Alumnos | `/modules/auth/views/login.php` | `estudiante` |
| Portal Institucional | `/modules/auth/views/staff.php` | `admin`, `docente`, `control_escolar` |

Los alumnos se auto-registran con su correo institucional (`matricula@zongolica.tecnm.mx`).
Las cuentas de docentes y control escolar las crea el administrador.

---

## Base de datos — tablas principales

| Tabla | Descripción |
|---|---|
| `Usuarios` | Todos los usuarios del sistema (todos los roles) |
| `Grupos` | Grupos académicos (campus, carrera, semestre) |
| `Materias` | Catálogo de materias |
| `GruposMaterias` | Relación grupo × materia × docente × horario |
| `Alumnos` | Perfil del alumno: grupo, matrícula, avatar, preferencias, onboarding |
| `Asistencias` | Registro de asistencia por alumno, materia y fecha |
| `Configuracion` | Parámetros del sistema (ciclo activo, % mínimo, etc.) |

---

## Scripts npm disponibles

```bash
npm run build    # Compila y minifica para producción
npm run dev      # Servidor Vite en modo desarrollo (puerto 5173)
```

---

## Equipo de desarrollo

Proyecto desarrollado por estudiantes de **Ingeniería en Sistemas Computacionales — 9° Semestre**, ITSZ (2025–2026).

| Nombre | Rol principal |
|---|---|
| Reinaldo Ajactle Choncoa | Desarrollo |
| Arlyn Alfaro Dominguez | Desarrollo |
| Hector Ayohua Quechulpa | Desarrollo |
| Blanca Rosa Diaz Hernandez | Desarrollo |
| Luis Almir Dominguez Puertos | Desarrollo |
| Luis Adrian Gutierrez Atlahua | Desarrollo |
| Alejandro Hernandez Tepole | Desarrollo |
| Kevin Emanuel Ixmatlahua Barojas | Desarrollo |
| Jose Bernadino Tlehuactle Ortega | Desarrollo |
| Araceli Tlehuactle Tepole | Desarrollo |
| **Jesus Alberto Rodriguez Puertos** | **Integrador / Arquitectura** |

Ver [CONTRIBUTORS.md](CONTRIBUTORS.md) para el detalle completo de contribuciones.

---

## Licencia

Este proyecto está protegido bajo una licencia de uso académico. Consulta el archivo [LICENSE](LICENSE) para más información.

© 2025–2026 Equipo de Desarrollo ISC — ITSZ. Todos los derechos reservados.
