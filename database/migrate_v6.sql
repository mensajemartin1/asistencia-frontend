-- ═══════════════════════════════════════════════════════════════
--  Migración v6
--  - Alumnos: campos de perfil para gamificación futura
--    · nickname     → alias visible del alumno (opcional)
--    · avatar       → identificador del avatar elegido ('default','gato','zorro',…)
--    · preferencias → intereses en JSON (['Programación','Diseño',…])
--    · onboarding_ok → 1 cuando el alumno completó el wizard de bienvenida
-- ═══════════════════════════════════════════════════════════════
USE Zongolica;

ALTER TABLE Alumnos
    ADD COLUMN nickname      VARCHAR(40)  NULL         AFTER matricula,
    ADD COLUMN avatar        VARCHAR(20)  NOT NULL DEFAULT 'default' AFTER nickname,
    ADD COLUMN preferencias  JSON         NULL         AFTER avatar,
    ADD COLUMN onboarding_ok TINYINT(1)  NOT NULL DEFAULT 0 AFTER preferencias;
