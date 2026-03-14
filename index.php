<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Instituto Tecnológico Superior de Zongolica | Sistema de Asistencia</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    :root {
      --primary: #1e40af;
      --primary-dark: #1e3a8a;
      --primary-light: #3b82f6;
      --accent: #dc2626;
      --bg: #f8f9fa;
      --card-bg: #ffffff;
      --text: #1f2937;
      --text-muted: #6b7280;
      --border: #e5e7eb;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      color: var(--text);
      font-family: "Open Sans", sans-serif;
      background: var(--bg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: var(--card-bg);
      border-bottom: 2px solid var(--primary);
      padding: 20px clamp(20px, 4vw, 40px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: var(--shadow);
    }

    .brand {
      display: flex;
      gap: 16px;
      align-items: center;
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--primary-dark);
    }

    .brand-logo {
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .brand-logo img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .brand-text {
      line-height: 1.2;
      font-size: 0.95rem;
    }

    .nav {
      display: flex;
      gap: 24px;
      align-items: center;
    }

    .nav a {
      text-decoration: none;
      color: var(--text);
      font-weight: 600;
      font-size: 0.95rem;
      transition: color 0.3s ease;
    }

    .nav a:hover {
      color: var(--primary);
    }

    main {
      flex: 1;
      padding: 40px clamp(20px, 4vw, 40px);
      max-width: 1200px;
      margin: 0 auto;
      width: 100%;
    }

    .hero {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      margin-bottom: 60px;
    }

    .hero-content h1 {
      font-family: "Merriweather", serif;
      font-size: clamp(2rem, 5vw, 2.8rem);
      line-height: 1.2;
      margin: 0 0 16px;
      color: var(--primary-dark);
    }

    .hero-content p {
      margin: 0 0 28px;
      color: var(--text-muted);
      font-size: 1.05rem;
      line-height: 1.6;
    }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .btn {
      padding: 12px 24px;
      border-radius: 6px;
      border: none;
      text-decoration: none;
      font-weight: 600;
      display: inline-flex;
      gap: 8px;
      align-items: center;
      transition: all 0.3s ease;
      cursor: pointer;
      font-size: 0.95rem;
    }

    .btn-primary {
      background: var(--primary);
      color: #fff;
      box-shadow: var(--shadow);
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
      background: var(--card-bg);
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
      background: var(--primary-light);
      color: #fff;
    }

    .info-card {
      background: var(--card-bg);
      border-left: 4px solid var(--accent);
      border-radius: 6px;
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .info-card h3 {
      margin: 0 0 12px;
      color: var(--primary-dark);
      font-family: "Merriweather", serif;
    }

    .info-card p {
      margin: 0;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .modules {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
    }

    .card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 28px;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: var(--shadow-lg);
      transform: translateY(-4px);
    }

    .card h3 {
      margin: 0 0 12px;
      font-family: "Merriweather", serif;
      font-size: 1.3rem;
      color: var(--primary-dark);
    }

    .card p {
      margin: 0 0 18px;
      color: var(--text-muted);
      line-height: 1.6;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      background: var(--primary-light);
      color: #fff;
      font-size: 0.8rem;
      font-weight: 600;
      margin-bottom: 12px;
    }

    footer {
      background: var(--primary-dark);
      color: #fff;
      text-align: center;
      padding: 32px 20px;
      font-size: 0.95rem;
      margin-top: auto;
    }

    footer a {
      color: var(--primary-light);
      text-decoration: none;
      font-weight: 600;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .hero {
        grid-template-columns: 1fr;
      }

      .nav {
        gap: 12px;
      }

      .nav a {
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="brand">
      <div class="brand-logo">
        <img src="jose/imagenes/logo-itsz.svg" alt="ITSZ Logo" />
      </div>
      <div class="brand-text">
        <div>Instituto Tecnológico Superior</div>
        <div style="font-size: 0.9rem; font-weight: 400; color: var(--accent);">Zongolica</div>
      </div>
    </div>
    <nav class="nav">
      <a href="luis/login.html">Iniciar sesión</a>
      <a href="Araceli/consultasView.php">Consultas</a>
      <a href="README.md">Documentación</a>
    </nav>
  </header>

  <main>
    <section class="hero">
      <div class="hero-content">
        <h1>Sistema de Control de Asistencia</h1>
        <p>Plataforma integral para gestión de asistencias del Instituto Tecnológico Superior de Zongolica. Consulta registros, genera reportes y visualiza gráficas de asistencia por grupo.</p>
        <div class="actions">
          <a class="btn btn-primary" href="luis/login.html">→ Acceder al sistema</a>
          <a class="btn btn-secondary" href="Araceli/consultasView.php">Ver reportes</a>
        </div>
      </div>
      <div class="info-card">
        <h3>Estado del sistema</h3>
        <p><strong>Rama de desarrollo:</strong> developer</p>
        <p><strong>Módulos activos:</strong> Login, Asistencia, Consultas</p>
        <p><strong>Base de datos:</strong> Integración en progreso</p>
        <p style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); color: var(--text);">
          <strong>Nota:</strong> Sistema en fase de integración. Todos los módulos están siendo consolidados en esta rama.
        </p>
      </div>
    </section>

    <section>
      <h2 style="font-family: 'Merriweather', serif; font-size: 1.8rem; color: var(--primary-dark); margin-bottom: 32px;">Accesos principales</h2>
      <div class="modules">
        <article class="card">
          <span class="badge">Autenticación</span>
          <h3>Iniciar sesión</h3>
          <p>Acceso seguro al sistema con validación de credenciales de usuario.</p>
          <a class="btn btn-primary" href="luis/login.html">Ingresar</a>
        </article>

        <article class="card">
          <span class="badge">Panel</span>
          <h3>Panel de control</h3>
          <p>Visualiza el estado de tu sesión y acceso al sistema protegido.</p>
          <a class="btn btn-primary" href="luis/asistencia.php">Abrir panel</a>
        </article>

        <article class="card">
          <span class="badge">Reportes</span>
          <h3>Consultas y gráficas</h3>
          <p>Filtra asistencias por alumno, grupo, materia o fecha con visualización gráfica.</p>
          <a class="btn btn-primary" href="Araceli/consultasView.php">Ver reportes</a>
        </article>
      </div>
    </section>
  </main>

  <footer>
    &copy; 2026 Instituto Tecnológico Superior de Zongolica | Sistema de Asistencia v1.0
    <br>
    <small>Rama: developer | Última actualización: 14/03/2026</small>
  </footer>
</body>
</html>
