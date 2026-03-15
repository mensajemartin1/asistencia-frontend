<?php
$title    = 'Equipo de Desarrollo — ITSZ';
$dataPage = 'developers';
require_once __DIR__ . '/../config/partials/head.php';
?>

<?php $activeNav = ''; require_once __DIR__ . '/../config/partials/navbar.php'; ?>

<main>

  <!-- Hero -->
  <section class="bg-linear-to-br from-primary-dark via-primary to-primary-light text-white py-16 px-5">
    <div class="max-w-3xl mx-auto text-center">
      <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
        </svg>
        Proyecto Integrador · ISC 8° Semestre
      </span>
      <h1 class="font-serif text-3xl md:text-4xl font-bold mb-4">Equipo de Desarrollo</h1>
      <p class="text-blue-100 text-base leading-relaxed max-w-xl mx-auto">
        Este sistema fue diseñado y desarrollado por estudiantes de <strong>Ingeniería en Sistemas Computacionales</strong>
        del Instituto Tecnológico Superior de Zongolica, como proyecto integrador del ciclo 2025–2026.
      </p>
    </div>
  </section>

  <!-- Asesor -->
  <section class="page-content py-12">
    <div class="text-center mb-8">
      <h2 class="font-serif text-xl font-bold text-primary-dark">Asesor del proyecto</h2>
    </div>
    <div class="max-w-md mx-auto">
      <div class="card border-primary/30 bg-blue-50 flex items-center gap-5 p-6">
        <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center text-white shrink-0">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-text text-base">Ing. S.C. Martín Contreras de la Cruz</p>
          <p class="text-primary text-xs font-semibold mt-0.5">Docente · Asesor del proyecto</p>
          <p class="text-text-muted text-xs mt-1.5 leading-relaxed">
            Ingeniería en Sistemas Computacionales — ITSZ<br>
            Responsable de la materia y apoyo en el diseño de la base de datos.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Equipo -->
  <section class="bg-muted py-12 px-5">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-10">
        <h2 class="font-serif text-xl font-bold text-primary-dark">Estudiantes desarrolladores</h2>
        <p class="text-text-muted text-sm mt-2">11 integrantes · Ingeniería en Sistemas Computacionales · 8° Semestre</p>
      </div>

      <?php
      $equipo = [
        [
          'nombre'  => 'Jesus Alberto Rodriguez Puertos',
          'emoji'   => '🧑‍💻',
          'rol'     => 'Integrador principal · Arquitecto del sistema',
          'color'   => 'blue',
          'modulos' => [
            'Arquitectura general y entorno de desarrollo (Vite + Tailwind CSS v4 + PHP)',
            'Sistema de autenticación dual: Portal Alumnos y Portal Institucional',
            'Módulo de onboarding con wizard de 4 pasos y perfil gamificable',
            'Dashboard del alumno (mobile-first, QR por materia)',
            'Dashboard del docente agrupado por campus',
            'Módulo de Control Escolar con scope por campus',
            'Sistema de roles y protección de rutas',
            'Schema de base de datos v3, migraciones v5 y v6',
            'Documentación: README, CONTRIBUTING, LICENSE',
          ],
        ],
        [
          'nombre'  => 'Jose Bernadino Tlehuactle Ortega',
          'emoji'   => '🗂️',
          'rol'     => 'Administrador del repositorio · UI',
          'color'   => 'indigo',
          'modulos' => [
            'Administración del repositorio GitHub y revisión de todos los Pull Requests',
            'Landing page principal: sección hero, módulos, campus y oferta educativa',
            'Logo SVG vectorial del ITSZ utilizado en todo el sistema',
            'Coordinación del flujo de trabajo colaborativo entre ramas',
          ],
        ],
        [
          'nombre'  => 'Arlyn Alfaro Dominguez',
          'emoji'   => '📄',
          'rol'     => 'Módulo de Reportes PDF',
          'color'   => 'red',
          'modulos' => [
            'Módulo de generación de reportes PDF con formato institucional ITSZ',
            'Integración y configuración de la librería FPDF',
            'Diseño del layout del reporte: encabezado, tabla de asistencias y porcentaje por materia',
          ],
        ],
        [
          'nombre'  => 'Hector Ayohua Quechulpa',
          'emoji'   => '✅',
          'rol'     => 'Módulo de Registro de Asistencia',
          'color'   => 'green',
          'modulos' => [
            'Vista principal del registro de asistencia para docentes',
            'Controlador PHP para guardar asistencia con detección de clase activa por horario',
            'API PHP para consultar registros de asistencia por grupo, materia y fecha',
            'Conexión y configuración de base de datos del módulo',
          ],
        ],
        [
          'nombre'  => 'Araceli Tlehuactle Tepole',
          'emoji'   => '📊',
          'rol'     => 'Módulo de Consultas y Estadísticas',
          'color'   => 'amber',
          'modulos' => [
            'Vista de consultas con filtros combinables (alumno, grupo, materia, fechas)',
            'Modelo PHP de consultas con queries SQL parametrizadas',
            'Controlador JavaScript con peticiones AJAX y actualización dinámica de resultados',
            'Gráfica de asistencia por grupo con Chart.js (verde/amarillo/rojo según umbral)',
          ],
        ],
        [
          'nombre'  => 'Luis Almir Dominguez Puertos',
          'emoji'   => '🔐',
          'rol'     => 'Módulo de Autenticación',
          'color'   => 'purple',
          'modulos' => [
            'Vista de inicio de sesión con diseño responsivo',
            'Controlador JavaScript de login con Fetch API y redirección por rol',
            'Modelo PHP de autenticación con password_verify() y manejo de sesiones',
            'Módulo de cierre de sesión seguro',
            'Formulario de registro de asistencia por matrícula',
          ],
        ],
        [
          'nombre'  => 'Reinaldo Ajactle Choncoa',
          'emoji'   => '🗄️',
          'rol'     => 'Base de datos inicial',
          'color'   => 'teal',
          'modulos' => [
            'Script SQL inicial de la base de datos con estructura de tablas Usuarios, Alumnos, Materias y Asistencias',
            'Definición de campos, tipos de dato, relaciones y restricciones del schema original',
            'Iteraciones y correcciones sobre el schema (ajuste de campos y documentación)',
          ],
        ],
        [
          'nombre'  => 'Blanca Rosa Diaz Hernandez',
          'emoji'   => '📋',
          'rol'     => 'Análisis y documentación',
          'color'   => 'pink',
          'modulos' => [
            'Análisis de requerimientos del sistema de control de asistencia',
            'Planificación y diseño del proyecto integrador',
          ],
        ],
        [
          'nombre'  => 'Luis Adrian Gutierrez Atlahua',
          'emoji'   => '🔍',
          'rol'     => 'Análisis y desarrollo',
          'color'   => 'orange',
          'modulos' => [
            'Análisis de requerimientos del sistema de control de asistencia',
            'Planificación y diseño del proyecto integrador',
          ],
        ],
        [
          'nombre'  => 'Alejandro Hernandez Tepole',
          'emoji'   => '⚙️',
          'rol'     => 'Análisis y desarrollo',
          'color'   => 'slate',
          'modulos' => [
            'Análisis de requerimientos del sistema de control de asistencia',
            'Planificación y diseño del proyecto integrador',
          ],
        ],
        [
          'nombre'  => 'Kevin Emanuel Ixmatlahua Barojas',
          'emoji'   => '🛠️',
          'rol'     => 'Análisis y desarrollo',
          'color'   => 'emerald',
          'modulos' => [
            'Análisis de requerimientos del sistema de control de asistencia',
            'Planificación y diseño del proyecto integrador',
          ],
        ],
      ];

      $bgMap = [
        'blue'    => 'bg-blue-100 text-blue-700',
        'indigo'  => 'bg-indigo-100 text-indigo-700',
        'red'     => 'bg-red-100 text-red-700',
        'green'   => 'bg-green-100 text-green-700',
        'amber'   => 'bg-amber-100 text-amber-700',
        'purple'  => 'bg-purple-100 text-purple-700',
        'teal'    => 'bg-teal-100 text-teal-700',
        'pink'    => 'bg-pink-100 text-pink-700',
        'orange'  => 'bg-orange-100 text-orange-700',
        'slate'   => 'bg-slate-100 text-slate-700',
        'emerald' => 'bg-emerald-100 text-emerald-700',
      ];
      ?>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($equipo as $i => $p):
          $badge = $bgMap[$p['color']];
        ?>
          <div class="card flex flex-col gap-3 h-full">
            <!-- Cabecera -->
            <div class="flex items-start gap-3">
              <div class="w-11 h-11 rounded-xl <?= $badge ?> flex items-center justify-center text-xl shrink-0 font-bold">
                <?= $p['emoji'] ?>
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-bold text-text text-sm leading-snug"><?= htmlspecialchars($p['nombre']) ?></p>
                <p class="text-xs text-primary font-medium mt-0.5 leading-tight"><?= htmlspecialchars($p['rol']) ?></p>
              </div>
            </div>
            <!-- Actividades -->
            <ul class="space-y-1.5 flex-1">
              <?php foreach ($p['modulos'] as $m): ?>
                <li class="flex items-start gap-2 text-xs text-text-muted leading-relaxed">
                  <span class="text-primary mt-0.5 shrink-0">›</span>
                  <?= htmlspecialchars($m) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Footer del equipo -->
  <section class="page-content py-10 text-center">
    <p class="text-text-muted text-sm">
      Proyecto desarrollado en el <strong>Instituto Tecnológico Superior de Zongolica</strong> · TecNM<br>
      Ingeniería en Sistemas Computacionales · 8° Semestre · Ciclo 2025–2026
    </p>
    <div class="flex justify-center gap-4 mt-4">
      <a href="/" class="text-xs text-primary hover:underline">← Inicio</a>
      <a href="https://github.com/joseorteha/asistencia-frontend" target="_blank" rel="noopener"
         class="text-xs text-primary hover:underline flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
        </svg>
        Repositorio GitHub
      </a>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/../config/partials/footer.php'; ?>
