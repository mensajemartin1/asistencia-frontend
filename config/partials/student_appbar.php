<?php
/**
 * Topbar móvil para el layout del estudiante.
 * Variables esperadas: $activeStudentTab ('dashboard' | 'historial')
 */
$_nombre = htmlspecialchars($_SESSION['nombre'] ?? '');
$_ini    = mb_strtoupper(mb_substr($_nombre, 0, 1));
$_tab    = $activeStudentTab ?? 'dashboard';

$_i_home = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>';
$_i_hist = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
$_i_user = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>';
?>
<div class="student-app">

  <!-- App bar -->
  <header class="student-appbar">
    <div class="flex items-center gap-3">
      <img src="/public/assets/img/logo-itsz.svg" alt="ITSZ" class="w-7 h-7 object-contain" />
      <div class="leading-tight">
        <p class="text-white text-xs font-bold">ITSZ Asistencias</p>
        <p class="text-blue-300 text-[10px]">TecNM</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center">
        <span class="text-white text-xs font-bold"><?= $_ini ?></span>
      </div>
    </div>
  </header>

  <!-- Page content -->
  <main class="student-content">
