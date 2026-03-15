# Mejoras pendientes y trabajo futuro

Este documento registra funcionalidades que aún pueden desarrollarse para mejorar el sistema. Está organizado por área y prioridad para servir como hoja de ruta del proyecto.

---

## Alta prioridad

### Seguridad y robustez
- [ ] **Rate limiting en endpoints de login** — Limitar intentos fallidos por IP para prevenir ataques de fuerza bruta.
- [ ] **CSRF tokens** en todos los formularios POST.
- [ ] **Sanitización centralizada** de entradas de usuario en un helper compartido.
- [ ] **HTTPS obligatorio** — Configuración de certificado SSL para el servidor de producción.
- [ ] **Expiración de sesión** automática por inactividad con alerta al usuario.

### Funcionalidad docente
- [ ] **Escáner QR en tiempo real** desde cámara del dispositivo (actualmente el QR es generado pero el escaneo es manual). Usar `jsQR` o la API `BarcodeDetector`.
- [ ] **Registro masivo de asistencia** — Marcar a todos como presentes/falta con un clic y luego editar excepciones.
- [ ] **Editar asistencia registrada** — El docente puede corregir un registro del mismo día.
- [ ] **Lista de alumnos sin QR** — Vista para ver qué alumnos no se han registrado aún en una clase.

### Módulo alumno
- [ ] **Foto de perfil** — Subida de imagen para el avatar del alumno (almacenada en servidor o como URL).
- [ ] **Cambio de semestre/grupo** — El alumno puede solicitar cambio de grupo (requiere aprobación del admin).
- [ ] **Notificaciones push** (PWA) cuando la asistencia baja del mínimo requerido.

---

## Prioridad media

### Gamificación y niveles
- [ ] **Sistema de puntos** — Los alumnos acumulan puntos por asistencia perfecta, racha semanal, etc.
- [ ] **Niveles de alumno** — Basados en puntos acumulados (ej. Bronce / Plata / Oro / Platino).
- [ ] **Insignias/logros** — "10 clases seguidas sin falta", "Asistencia perfecta del mes", etc.
- [ ] **Tabla de líderes** (leaderboard) por grupo — Ranking de alumnos con mejor asistencia.
- [ ] **Rachas de asistencia** — Contador de días/clases consecutivas sin falta.
- [ ] **Tienda de recompensas** — Los puntos pueden canjearse por beneficios simbólicos dentro del sistema.

### Reportes y estadísticas
- [ ] **Exportación a Excel** (CSV/XLSX) de registros de asistencia.
- [ ] **Reporte PDF mejorado** — Incluir gráfica de barras y porcentaje por materia dentro del PDF.
- [ ] **Estadísticas por campus** para admin general — No solo por campus individual.
- [ ] **Alertas automáticas por correo** cuando un alumno baja del porcentaje mínimo.
- [ ] **Vista de calendario** — Visualizar asistencias en formato de calendario mensual.
- [ ] **Comparativa entre ciclos** — Comparar el rendimiento de un grupo en diferentes semestres.

### Administración
- [ ] **Importación masiva de alumnos** desde CSV/Excel.
- [ ] **Importación masiva de grupos y materias** al inicio de cada ciclo.
- [ ] **Clonar grupos de un ciclo al siguiente** — Reutilizar la estructura sin perder datos históricos.
- [ ] **Gestión de ciclos escolares** — Archivar un ciclo y abrir el siguiente sin borrar datos.
- [ ] **Log de actividad** — Registro de acciones importantes (quién creó qué, cuándo).
- [ ] **Panel de control general** — Métricas globales: total alumnos activos, promedio de asistencia por campus, materias con más faltas.

### Control escolar
- [ ] **Reporte de alumnos en riesgo** — Lista automática de alumnos con asistencia por debajo del mínimo, exportable.
- [ ] **Historial de reportes generados** — El sistema guarda un log de los PDFs generados.
- [ ] **Filtro por docente** en las consultas de asistencia.

---

## Prioridad baja / mejoras de UX

### Interfaz y accesibilidad
- [ ] **Modo oscuro** — Toggle dark/light mode guardado en preferencias del usuario.
- [ ] **Soporte para lectores de pantalla** — Mejorar etiquetas ARIA en formularios y tablas.
- [ ] **Internacionalización (i18n)** — Preparar el sistema para soportar inglés además de español.
- [ ] **Responsive mejorado en vistas admin** — Algunas tablas no son cómodas en pantallas pequeñas.
- [ ] **Animaciones de transición** entre pasos del onboarding y entre vistas.
- [ ] **Vista previa de QR en pantalla completa** — Para mostrar el QR más grande al docente desde el móvil.

### Progressive Web App (PWA)
- [ ] **Manifest de PWA** — Permitir que los alumnos instalen el portal en su pantalla de inicio.
- [ ] **Service Worker** para cache básico y funcionamiento offline parcial (ver historial sin conexión).
- [ ] **Sincronización en segundo plano** — Si no hay conexión al momento del registro, guardar localmente y sincronizar al reconectarse.

### Comunicación
- [ ] **Sistema de avisos/anuncios** — El docente o admin puede publicar avisos visibles en el dashboard del alumno.
- [ ] **Mensajería interna** básica entre docente y alumno.
- [ ] **Integración con correo institucional** para notificaciones automáticas (Nodemailer / SMTP de la institución).

---

## Deuda técnica

- [ ] **Tests automatizados** — Pruebas unitarias para los controllers PHP (PHPUnit) y pruebas E2E básicas.
- [ ] **Documentación de la API** — Documentar todos los endpoints del tipo `*Model.php` con sus parámetros y respuestas.
- [ ] **Migración a PDO** — Reemplazar `mysqli` por PDO para uniformidad y mejor manejo de errores.
- [ ] **Sistema de plantillas** — Considerar un motor de plantillas ligero (Twig) en lugar de PHP embebido en HTML.
- [ ] **Separación de lógica de negocio** — Mover lógica de controladores a clases de servicio.
- [ ] **Versionado de la API** — Prefijos `/api/v1/` para los endpoints JSON.
- [ ] **CI/CD básico** — GitHub Actions para ejecutar linting y build automáticamente en cada PR.

---

## Ideas a largo plazo

- [ ] **Portal para tutores/padres de familia** — Consulta de asistencia con acceso limitado.
- [ ] **Integración con sistema escolar SIIA** — Sincronización de datos con el sistema oficial TecNM.
- [ ] **App móvil nativa** (Flutter o React Native) para docentes con escaneo de QR integrado.
- [ ] **Panel multi-institución** — Adaptar el sistema para ser desplegado en otros campus TecNM.
- [ ] **Reconocimiento facial** como método alternativo de registro de asistencia.

---

*Última actualización: Marzo 2026 · Equipo ISC 8° — ITSZ*
