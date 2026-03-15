-- ═══════════════════════════════════════════════════════════════
--  Migración v5
--  - Alumnos.idGrupo pasa a nullable (el alumno existe antes de
--    que el admin le asigne grupo)
--  - Se agrega índice en idUsuario para búsquedas rápidas
-- ═══════════════════════════════════════════════════════════════
USE Zongolica;

-- idUsuario ya tiene índice implícito por ser UNIQUE
ALTER TABLE Alumnos
    MODIFY COLUMN idGrupo INT NULL;
