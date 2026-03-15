<?php
$title    = 'Sistema de Control de Asistencia — ITSZ';
$dataPage = 'home';
require_once __DIR__ . '/../config/partials/head.php';
?>

<?php $activeNav = ''; require_once __DIR__ . '/../config/partials/navbar.php'; ?>

<main>

  <!-- ═══════════════════════════════════════════════════════════════
       HERO
  ═══════════════════════════════════════════════════════════════ -->
  <section class="relative overflow-hidden bg-linear-to-br from-primary-dark via-primary to-primary-light text-white">
    <!-- decoración de fondo -->
    <div class="absolute inset-0 opacity-10 pointer-events-none"
         style="background-image: radial-gradient(circle at 70% 40%, #fff 0%, transparent 60%)"></div>

    <div class="max-w-5xl mx-auto px-5 py-20 grid md:grid-cols-2 gap-12 items-center relative z-10">

      <div>
        <span class="inline-flex items-center gap-2 bg-white/15 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
          <span class="w-2 h-2 rounded-full bg-green-400 inline-block animate-pulse"></span>
          TecNM · Campus Zongolica
        </span>
        <h1 class="font-serif text-4xl md:text-5xl font-bold leading-tight mb-4">
          Control de<br>Asistencia
        </h1>
        <p class="text-blue-100 text-base leading-relaxed mb-8 max-w-sm">
          Plataforma institucional para el registro, consulta y generación de reportes de asistencia del Instituto Tecnológico Superior de Zongolica.
        </p>
        <div class="flex flex-wrap gap-3">
          <a href="/modules/auth/views/login.php"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm bg-white text-primary shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 no-underline">
            🎓 Portal de Alumnos
          </a>
          <a href="/modules/auth/views/staff.php"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm bg-white/10 text-white border border-white/30 hover:bg-white/20 transition-all duration-200 no-underline">
            🔐 Portal Institucional
          </a>
        </div>
      </div>

      <!-- Tarjeta de características -->
      <div class="card bg-white/10 border-white/20 text-white backdrop-blur-sm">
        <div class="flex items-center gap-3 mb-4">
          <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-10 h-10 object-contain" />
          <div>
            <p class="font-bold text-sm leading-none">ITSZ</p>
            <p class="text-blue-200 text-xs">Altas Montañas, Veracruz</p>
          </div>
        </div>
        <hr class="border-white/20 mb-4">
        <ul class="space-y-3 text-sm">
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
            <span class="text-blue-100">Registro de asistencia por QR</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
            <span class="text-blue-100">Reportes PDF institucionales</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
            <span class="text-blue-100">7 campus de la región Altas Montañas</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
            <span class="text-blue-100">Portales independientes por rol</span>
          </li>
          <li class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span>
            <span class="text-blue-100">Historial y estadísticas en tiempo real</span>
          </li>
        </ul>
        <hr class="border-white/20 my-4">
        <a href="/public/developers.php"
           class="flex items-center justify-center gap-2 text-xs text-blue-200 hover:text-white transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          Conoce al equipo de desarrollo
        </a>
      </div>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════════════════════════
       MÓDULOS
  ═══════════════════════════════════════════════════════════════ -->
  <section class="page-content-wide py-16">
    <div class="text-center mb-10">
      <h2 class="font-serif text-2xl font-bold text-primary-dark">Módulos del sistema</h2>
      <p class="text-text-muted text-sm mt-2">Gestión integral de asistencia para todos los campus del ITSZ</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

      <a href="/modules/auth/views/login.php" class="card card-hover group">
        <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-all">
          <svg class="w-5 h-5 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <span class="badge-primary mb-2">Autenticación</span>
        <h3 class="card-title text-base mt-1">Acceso al sistema</h3>
        <p class="text-xs text-text-muted mt-1 leading-relaxed">Inicio de sesión seguro con validación de credenciales institucionales.</p>
      </a>

      <a href="/modules/attendance/views/index.php" class="card card-hover group">
        <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center mb-4 group-hover:bg-success group-hover:text-white transition-all">
          <svg class="w-5 h-5 text-success group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <span class="badge-success mb-2">Registro</span>
        <h3 class="card-title text-base mt-1">Registro de asistencia</h3>
        <p class="text-xs text-text-muted mt-1 leading-relaxed">Registra presencia por matrícula con detección automática de clase activa.</p>
      </a>

      <a href="/modules/queries/views/consultasView.php" class="card card-hover group">
        <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center mb-4 group-hover:bg-warning group-hover:text-white transition-all">
          <svg class="w-5 h-5 text-warning group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <span class="badge-warning mb-2">Consultas</span>
        <h3 class="card-title text-base mt-1">Reportes y gráficas</h3>
        <p class="text-xs text-text-muted mt-1 leading-relaxed">Filtra asistencias por alumno, grupo, materia o fecha. Incluye gráficas.</p>
      </a>

      <a href="/modules/reports/generar_reporte.php" class="card card-hover group">
        <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center mb-4 group-hover:bg-accent group-hover:text-white transition-all">
          <svg class="w-5 h-5 text-accent group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <span class="badge-error mb-2">PDF</span>
        <h3 class="card-title text-base mt-1">Reporte PDF</h3>
        <p class="text-xs text-text-muted mt-1 leading-relaxed">Genera reportes de rendimiento académico en PDF con porcentaje de asistencia.</p>
      </a>

    </div>
  </section>

  <!-- ═══════════════════════════════════════════════════════════════
       CAMPUS
  ═══════════════════════════════════════════════════════════════ -->
  <section class="bg-primary-dark text-white py-14 px-5">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-10">
        <h2 class="font-serif text-2xl font-bold">Red de campus ITSZ</h2>
        <p class="text-blue-300 text-sm mt-2">7 unidades académicas en la región de las Altas Montañas de Veracruz</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        <?php
        $campuses = [
          ['name' => 'Zongolica',   'tipo' => 'Campus principal', 'year' => '2002'],
          ['name' => 'Nogales',     'tipo' => 'Unidad académica',  'year' => '—'],
          ['name' => 'Tezonapa',    'tipo' => 'Unidad académica',  'year' => '—'],
          ['name' => 'Tehuipango',  'tipo' => 'Unidad académica',  'year' => '—'],
          ['name' => 'Tequila',     'tipo' => 'Unidad académica',  'year' => '—'],
          ['name' => 'Cuichapa',    'tipo' => 'Unidad académica',  'year' => '—'],
          ['name' => 'Acultzinapa', 'tipo' => 'Unidad académica',  'year' => '2016'],
        ];
        foreach ($campuses as $c): ?>
          <div class="bg-white/10 rounded-xl p-4 border border-white/10 hover:bg-white/15 transition-colors">
            <div class="w-8 h-8 rounded-lg bg-primary-light/30 flex items-center justify-center mb-2">
              <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            <p class="font-semibold text-sm text-white"><?= $c['name'] ?></p>
            <p class="text-blue-300 text-xs"><?= $c['tipo'] ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ═══════════════════════════════════════════════════════════════
       OFERTA EDUCATIVA
  ═══════════════════════════════════════════════════════════════ -->
  <section class="page-content py-14">
    <div class="text-center mb-10">
      <h2 class="font-serif text-2xl font-bold text-primary-dark">Oferta educativa</h2>
      <p class="text-text-muted text-sm mt-2">Ingenierías y posgrado del ITSZ</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php
      $carreras = [
        ['nombre' => 'Ing. en Sistemas Computacionales',      'año' => '2007', 'color' => 'blue'],
        ['nombre' => 'Ing. en Gestión Empresarial',           'año' => '2009', 'color' => 'green'],
        ['nombre' => 'Ing. en Desarrollo Comunitario',        'año' => '2002', 'color' => 'amber'],
        ['nombre' => 'Ing. Forestal',                         'año' => '2003', 'color' => 'emerald'],
        ['nombre' => 'Ing. en Innovación Agrícola Sustentable','año' => '—',   'color' => 'lime'],
        ['nombre' => 'Ingeniería Civil',                      'año' => '—',    'color' => 'orange'],
        ['nombre' => 'Maestría en Ciencias en Desarrollo Regional y Tecnológico', 'año' => 'Posgrado', 'color' => 'purple'],
      ];
      $bgMap = [
        'blue' => 'bg-blue-50 border-blue-200', 'green' => 'bg-green-50 border-green-200',
        'amber' => 'bg-amber-50 border-amber-200', 'emerald' => 'bg-emerald-50 border-emerald-200',
        'lime' => 'bg-lime-50 border-lime-200', 'orange' => 'bg-orange-50 border-orange-200',
        'purple' => 'bg-purple-50 border-purple-200',
      ];
      $textMap = [
        'blue' => 'text-blue-700', 'green' => 'text-green-700', 'amber' => 'text-amber-700',
        'emerald' => 'text-emerald-700', 'lime' => 'text-lime-700', 'orange' => 'text-orange-700',
        'purple' => 'text-purple-700',
      ];
      foreach ($carreras as $c):
        $bg   = $bgMap[$c['color']];
        $text = $textMap[$c['color']];
      ?>
        <div class="flex items-center gap-4 p-4 rounded-xl border <?= $bg ?> hover:shadow-sm transition-shadow">
          <div class="flex-1">
            <p class="font-semibold text-sm text-text leading-tight"><?= $c['nombre'] ?></p>
          </div>
          <span class="text-xs font-medium <?= $text ?> shrink-0"><?= $c['año'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/../config/partials/footer.php'; ?>
