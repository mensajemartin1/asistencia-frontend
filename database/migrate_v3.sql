-- ─────────────────────────────────────────────────────────────────────────────
-- Migración v3 — Agrega columnas para recuperación de contraseña
-- Ejecutar solo una vez sobre la BD existente (Zongolica)
-- ─────────────────────────────────────────────────────────────────────────────
USE Zongolica;

ALTER TABLE Usuarios
    ADD COLUMN token_reset        VARCHAR(64) AFTER token_expira,
    ADD COLUMN token_reset_expira DATETIME    AFTER token_reset;
