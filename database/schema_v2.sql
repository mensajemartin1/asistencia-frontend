-- ═══════════════════════════════════════════════════════════════
--  ITSZ — Sistema de Control de Asistencia
--  Schema v2 — instalación limpia
--  Ejecutar: mysql -u root -p < schema_v2.sql
-- ═══════════════════════════════════════════════════════════════
CREATE DATABASE IF NOT EXISTS Zongolica
    CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE Zongolica;

-- ─────────────────────────────────────────
--  USUARIOS  (autenticación + roles)
-- ─────────────────────────────────────────
CREATE TABLE Usuarios (
    id                   INT          NOT NULL AUTO_INCREMENT,
    nombre               VARCHAR(80)  NOT NULL,
    correo               VARCHAR(120) NOT NULL UNIQUE,
    password             VARCHAR(255) NOT NULL,
    rol                  ENUM('admin','docente','estudiante','control_escolar')
                                      NOT NULL DEFAULT 'estudiante',
    estado               ENUM('pendiente_confirmacion','activo','rechazado')
                                      NOT NULL DEFAULT 'pendiente_confirmacion',
    campus               VARCHAR(80),
    token_confirmacion   VARCHAR(64),
    token_expira         DATETIME,
    token_reset          VARCHAR(64),
    token_reset_expira   DATETIME,
    created_at           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_correo (correo),
    INDEX idx_rol    (rol),
    INDEX idx_estado (estado)
);

-- ─────────────────────────────────────────
--  GRUPOS  (ISC 6A, IGE 4B, etc.)
-- ─────────────────────────────────────────
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

-- ─────────────────────────────────────────
--  MATERIAS  (catálogo limpio)
-- ─────────────────────────────────────────
CREATE TABLE Materias (
    id         INT         NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(80) NOT NULL,
    clave      VARCHAR(20),
    creditos   TINYINT     UNSIGNED,
    activa     TINYINT(1)  NOT NULL DEFAULT 1,
    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- ─────────────────────────────────────────
--  GRUPOS_MATERIAS  (grupo × materia × docente × horario)
-- ─────────────────────────────────────────
CREATE TABLE GruposMaterias (
    id          INT          NOT NULL AUTO_INCREMENT,
    idGrupo     INT          NOT NULL,
    idMateria   INT          NOT NULL,
    idDocente   INT          NOT NULL,
    horaInicio  TIME,
    horaFin     TIME,
    dias        VARCHAR(20)  DEFAULT 'LMJV',  -- LMV, MJ, LMMJV …
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

-- ─────────────────────────────────────────
--  ALUMNOS  (vinculado a un grupo)
-- ─────────────────────────────────────────
CREATE TABLE Alumnos (
    id             INT          NOT NULL AUTO_INCREMENT,
    idGrupo        INT          NOT NULL,
    idUsuario      INT          UNIQUE,        -- FK a Usuarios (si tiene cuenta)
    nombre         VARCHAR(80)  NOT NULL,
    matricula      VARCHAR(20)  NOT NULL UNIQUE,
    numero_control VARCHAR(20),
    foto           VARCHAR(200),
    activo         TINYINT(1)   NOT NULL DEFAULT 1,
    created_at     TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_matricula (matricula),
    INDEX idx_grupo     (idGrupo),
    FOREIGN KEY (idGrupo)    REFERENCES Grupos(id)   ON DELETE RESTRICT,
    FOREIGN KEY (idUsuario)  REFERENCES Usuarios(id) ON DELETE SET NULL
);

-- ─────────────────────────────────────────
--  ASISTENCIAS
-- ─────────────────────────────────────────
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
    INDEX idx_fecha    (fecha),
    INDEX idx_alumno   (idAlumno),
    FOREIGN KEY (idGrupoMateria) REFERENCES GruposMaterias(id) ON DELETE CASCADE,
    FOREIGN KEY (idAlumno)       REFERENCES Alumnos(id)        ON DELETE CASCADE,
    FOREIGN KEY (registrado_por) REFERENCES Usuarios(id)       ON DELETE SET NULL
);

-- ─────────────────────────────────────────
--  CONFIGURACION  (clave → valor)
-- ─────────────────────────────────────────
CREATE TABLE Configuracion (
    clave   VARCHAR(60)  NOT NULL PRIMARY KEY,
    valor   VARCHAR(200) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO Configuracion (clave, valor) VALUES
    ('ciclo_activo',        '2026-A'),
    ('dias_totales_ciclo',  '90'),
    ('porcentaje_minimo',   '80'),
    ('nombre_sistema',      'Sistema de Control de Asistencia ITSZ'),
    ('version',             '2.0');
