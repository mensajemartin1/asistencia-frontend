USE Zongolica;

SET @pwd_hash := '$2y$12$nDves6yoWNPjtARCv7y1KOEOFBoDsf1A5/rpG0y9kT9cY8v/Uy1MC';

-- Usuarios base para login en entorno local
INSERT INTO Usuarios (nombre, correo, password, rol, estado, campus)
VALUES
  ('Admin ITSZ', 'admin@zongolica.tecnm.mx', @pwd_hash, 'admin', 'activo', 'Zongolica'),
  ('Docente ITSZ', 'docente@zongolica.tecnm.mx', @pwd_hash, 'docente', 'activo', 'Zongolica'),
  ('Control Escolar ITSZ', 'control@zongolica.tecnm.mx', @pwd_hash, 'control_escolar', 'activo', 'Zongolica'),
  ('Alumno Demo', 'alumno@zongolica.tecnm.mx', @pwd_hash, 'estudiante', 'activo', 'Zongolica')
ON DUPLICATE KEY UPDATE
  nombre = VALUES(nombre),
  password = VALUES(password),
  rol = VALUES(rol),
  estado = 'activo',
  campus = VALUES(campus);

-- Asegurar alumno vinculado al usuario estudiante
SET @alumno_user := (SELECT id FROM Usuarios WHERE correo='alumno@zongolica.tecnm.mx' LIMIT 1);
INSERT INTO Alumnos (idGrupo, idUsuario, nombre, matricula, numero_control, onboarding_ok, activo)
SELECT NULL, @alumno_user, 'Alumno Demo', 'alumno', 'A803001', 0, 1
WHERE @alumno_user IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM Alumnos WHERE idUsuario=@alumno_user);

-- Datos academicos minimos para probar asistencia
SET @docente_id := (SELECT id FROM Usuarios WHERE correo='docente@zongolica.tecnm.mx' LIMIT 1);

SET @idGrupo := (SELECT id FROM Grupos WHERE nombre='803' AND campus='Zongolica' LIMIT 1);
INSERT INTO Grupos (nombre, carrera, semestre, campus, activo)
SELECT '803', 'Ing. en Sistemas Computacionales', 8, 'Zongolica', 1
WHERE @idGrupo IS NULL;
SET @idGrupo := COALESCE(@idGrupo, LAST_INSERT_ID());

SET @idMateria := (SELECT id FROM Materias WHERE nombre='Programacion Web' LIMIT 1);
INSERT INTO Materias (nombre, clave, creditos, activa)
SELECT 'Programacion Web', 'ISC-803-PW', 5, 1
WHERE @idMateria IS NULL;
SET @idMateria := COALESCE(@idMateria, LAST_INSERT_ID());

INSERT INTO GruposMaterias (idGrupo, idMateria, idDocente, horaInicio, horaFin, dias, ciclo, activo)
SELECT @idGrupo, @idMateria, @docente_id, '00:00:00', '23:59:59', 'LMJV', '2026-A', 1
WHERE @docente_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1
    FROM GruposMaterias
    WHERE idGrupo=@idGrupo
      AND idMateria=@idMateria
      AND idDocente=@docente_id
      AND ciclo='2026-A'
  );

-- Vincular alumno demo al grupo 803
UPDATE Alumnos
SET idGrupo = @idGrupo,
    numero_control = 'A803001',
    onboarding_ok = 1,
    activo = 1
WHERE idUsuario = @alumno_user;
