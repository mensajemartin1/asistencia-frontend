-- ═══════════════════════════════════════════════════════════════
--  Migración v4 — Reestructura completa del schema
--  ⚠ ADVERTENCIA: elimina y recrea las tablas académicas.
--  Los datos de Alumnos, Materias, Asistencias se perderán.
--  Usuarios se conserva y adapta.
-- ═══════════════════════════════════════════════════════════════
USE Zongolica;

-- 1. Eliminar tablas en orden inverso de FK
DROP TABLE IF EXISTS Asistencias;
DROP TABLE IF EXISTS Alumnos;
DROP TABLE IF EXISTS GruposMaterias;
DROP TABLE IF EXISTS Grupos;
DROP TABLE IF EXISTS Materias;
DROP TABLE IF EXISTS Notificaciones;
DROP TABLE IF EXISTS Configuracion;

-- 2. Adaptar Usuarios: cambiar VARCHAR a ENUM
ALTER TABLE Usuarios
    MODIFY COLUMN rol    ENUM('admin','docente','estudiante','control_escolar')
                         NOT NULL DEFAULT 'estudiante',
    MODIFY COLUMN estado ENUM('pendiente_confirmacion','activo','rechazado')
                         NOT NULL DEFAULT 'pendiente_confirmacion';

-- 3. Crear nuevas tablas
CREATE TABLE Grupos (
    id         INT          NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(40)  NOT NULL,
    carrera    VARCHAR(80),
    semestre   TINYINT      UNSIGNED,
    campus     VARCHAR(80),
    activo     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_activo (activo)
);

CREATE TABLE Materias (
    id         INT         NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(80) NOT NULL,
    clave      VARCHAR(20),
    creditos   TINYINT     UNSIGNED,
    activa     TINYINT(1)  NOT NULL DEFAULT 1,
    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE GruposMaterias (
    id          INT          NOT NULL AUTO_INCREMENT,
    idGrupo     INT          NOT NULL,
    idMateria   INT          NOT NULL,
    idDocente   INT          NOT NULL,
    horaInicio  TIME,
    horaFin     TIME,
    dias        VARCHAR(20)  DEFAULT 'LMJV',
    ciclo       VARCHAR(20)  DEFAULT '2026-A',
    activo      TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uq_gm_ciclo (idGrupo, idMateria, idDocente, ciclo),
    INDEX idx_docente (idDocente),
    INDEX idx_grupo   (idGrupo),
    FOREIGN KEY (idGrupo)   REFERENCES Grupos(id)   ON DELETE CASCADE,
    FOREIGN KEY (idMateria) REFERENCES Materias(id) ON DELETE CASCADE,
    FOREIGN KEY (idDocente) REFERENCES Usuarios(id) ON DELETE RESTRICT
);

CREATE TABLE Alumnos (
    id             INT          NOT NULL AUTO_INCREMENT,
    idGrupo        INT          NOT NULL,
    idUsuario      INT          UNIQUE,
    nombre         VARCHAR(80)  NOT NULL,
    matricula      VARCHAR(20)  NOT NULL UNIQUE,
    numero_control VARCHAR(20),
    foto           VARCHAR(200),
    activo         TINYINT(1)   NOT NULL DEFAULT 1,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_matricula (matricula),
    INDEX idx_grupo     (idGrupo),
    FOREIGN KEY (idGrupo)   REFERENCES Grupos(id)   ON DELETE RESTRICT,
    FOREIGN KEY (idUsuario) REFERENCES Usuarios(id) ON DELETE SET NULL
);

CREATE TABLE Asistencias (
    id              INT          NOT NULL AUTO_INCREMENT,
    idGrupoMateria  INT          NOT NULL,
    idAlumno        INT          NOT NULL,
    estado          ENUM('presente','falta','retardo') NOT NULL DEFAULT 'presente',
    fecha           DATE         NOT NULL,
    hora            TIME,
    registrado_por  INT,
    created_at      TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_asistencia (idGrupoMateria, idAlumno, fecha),
    INDEX idx_fecha  (fecha),
    INDEX idx_alumno (idAlumno),
    FOREIGN KEY (idGrupoMateria) REFERENCES GruposMaterias(id) ON DELETE CASCADE,
    FOREIGN KEY (idAlumno)       REFERENCES Alumnos(id)        ON DELETE CASCADE,
    FOREIGN KEY (registrado_por) REFERENCES Usuarios(id)       ON DELETE SET NULL
);

CREATE TABLE Configuracion (
    clave      VARCHAR(60)  NOT NULL PRIMARY KEY,
    valor      VARCHAR(200) NOT NULL,
    updated_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO Configuracion (clave, valor) VALUES
    ('ciclo_activo',       '2026-A'),
    ('dias_totales_ciclo', '90'),
    ('porcentaje_minimo',  '80'),
    ('nombre_sistema',     'Sistema de Control de Asistencia ITSZ'),
    ('version',            '2.0');
