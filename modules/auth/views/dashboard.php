<?php
require_once __DIR__ . '/../../../config/auth_check.php';

$usuario  = htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['correo'] ?? '');
$title    = 'Panel de control — ITSZ';
$dataPage = 'dashboard';
$activeNav = '';
require_once __DIR__ . '/../../../config/partials/head.php';
?>

<div class="page flex flex-col">

  <?php require_once __DIR__ . '/../../../config/partials/navbar.php'; ?>

  <main class="page-content-wide flex-1">

    <!-- Bienvenida -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="font-serif text-3xl font-bold text-primary-dark">
          Bienvenido, <?= $usuario ?>
        </h1>
        <p class="text-text-muted mt-1 text-sm">
          <?= date('l, d \d\e F \d\e Y') ?> — Sistema de Control de Asistencia ITSZ
        </p>
      </div>
      <a href="../controllers/logout.php"
         class="btn-ghost text-sm border border-border px-4 py-2 rounded-lg">
        Cerrar sesión →
      </a>
    </div>

    <!-- Stats (placeholder, se llenarán con el panel admin) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

      <div class="card border-l-4 border-l-primary">
        <p class="text-xs text-text-muted mb-1 uppercase tracking-wide">Alumnos</p>
        <p class="text-3xl font-bold text-primary">—</p>
        <p class="text-xs text-text-muted mt-1">registrados</p>
      </div>

      <div class="card border-l-4 border-l-success">
        <p class="text-xs text-text-muted mb-1 uppercase tracking-wide">Asistencias hoy</p>
        <p class="text-3xl font-bold text-success">—</p>
        <p class="text-xs text-text-muted mt-1">registradas</p>
      </div>

      <div class="card border-l-4 border-l-warning">
        <p class="text-xs text-text-muted mb-1 uppercase tracking-wide">Materias</p>
        <p class="text-3xl font-bold text-warning">—</p>
        <p class="text-xs text-text-muted mt-1">activas</p>
      </div>

      <div class="card border-l-4 border-l-accent">
        <p class="text-xs text-text-muted mb-1 uppercase tracking-wide">Grupos</p>
        <p class="text-3xl font-bold text-accent">—</p>
        <p class="text-xs text-text-muted mt-1">registrados</p>
      </div>

    </div>

    <!-- Accesos rápidos -->
    <h2 class="font-serif text-xl font-bold text-primary-dark mb-5">Accesos rápidos</h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

      <a href="/modules/attendance/views/index.php" class="card card-hover group flex gap-4 items-start">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center shrink-0 group-hover:bg-green-200 transition-colors">
          <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <h3 class="font-semibold text-text mb-0.5">Registrar asistencia</h3>
          <p class="text-sm text-text-muted">Escanea QR o ingresa matrícula para marcar presencia.</p>
        </div>
      </a>

      <a href="/modules/queries/views/consultasView.php" class="card card-hover group flex gap-4 items-start">
        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center shrink-0 group-hover:bg-amber-200 transition-colors">
          <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-semibold text-text mb-0.5">Consultar registros</h3>
          <p class="text-sm text-text-muted">Filtra por alumno, grupo, materia o fecha con gráficas.</p>
        </div>
      </a>

      <a href="/modules/reports/generar_reporte.php" class="card card-hover group flex gap-4 items-start">
        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0 group-hover:bg-red-200 transition-colors">
          <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-semibold text-text mb-0.5">Generar reporte PDF</h3>
          <p class="text-sm text-text-muted">Descarga el reporte de rendimiento con porcentajes de asistencia.</p>
        </div>
      </a>

    </div>

  </main>

  <?php require_once __DIR__ . '/../../../config/partials/footer.php'; ?>

</div>
