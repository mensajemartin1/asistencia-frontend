-- ─────────────────────────────────────────────────────────────────────────────
-- Migración v2 — Ejecutar en MySQL sobre la BD existente (Zongolica)
-- NOTA: Ejecuta cada ADD COLUMN por separado si ya existe alguna columna.
-- ─────────────────────────────────────────────────────────────────────────────
USE Zongolica;

-- 1. Modificaciones seguras (columnas que ya deben existir)
ALTER TABLE Usuarios
    MODIFY COLUMN correo VARCHAR(120) NOT NULL,
    MODIFY COLUMN rol    VARCHAR(40)  NOT NULL DEFAULT 'estudiante';

-- 2. Agrega índice único en correo (omitir si ya existe)
ALTER TABLE Usuarios
    ADD UNIQUE INDEX idx_correo (correo);

-- 3. Nuevas columnas (ejecutar solo si NO existen aún)
ALTER TABLE Usuarios
    ADD COLUMN password             VARCHAR(255) NOT NULL DEFAULT '' AFTER correo,
    ADD COLUMN estado               VARCHAR(30)  NOT NULL DEFAULT 'pendiente_confirmacion' AFTER rol,
    ADD COLUMN campus               VARCHAR(80)  AFTER estado,
    ADD COLUMN token_confirmacion   VARCHAR(64)  AFTER campus,
    ADD COLUMN token_expira         DATETIME     AFTER token_confirmacion,
    ADD COLUMN token_reset          VARCHAR(64)  AFTER token_expira,
    ADD COLUMN token_reset_expira   DATETIME     AFTER token_reset,
    ADD COLUMN created_at           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP AFTER token_reset_expira;

-- 4. Si tienes usuarios de prueba sin estado, ponlos como activos
--    (comenta si prefieres dejarlos como pendiente_confirmacion)
-- UPDATE Usuarios SET estado = 'activo' WHERE estado = '' OR estado IS NULL;

-- 5. Elimina la columna usuario si aún existe (ya no se usa)
-- ALTER TABLE Usuarios DROP COLUMN usuario;
