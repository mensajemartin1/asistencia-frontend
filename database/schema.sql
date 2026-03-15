CREATE DATABASE IF NOT EXISTS Zongolica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE Zongolica;

-- ─────────────────────────────────────────
--  USUARIOS  (módulo: Luis)
--  Se agregan usuario y password para autenticación.
--  password almacena el hash de bcrypt (password_hash).
-- ─────────────────────────────────────────
CREATE TABLE Usuarios (
    id                   INT          NOT NULL AUTO_INCREMENT,
    nombre               VARCHAR(80)  NOT NULL,
    correo               VARCHAR(120) NOT NULL UNIQUE,
    password             VARCHAR(255) NOT NULL,
    rol                  VARCHAR(40)  NOT NULL DEFAULT 'estudiante',
    -- pendiente_confirmacion | activo | rechazado
    estado               VARCHAR(30)  NOT NULL DEFAULT 'pendiente_confirmacion',
    campus               VARCHAR(80),
    token_confirmacion   VARCHAR(64),
    token_expira         DATETIME,
    token_reset          VARCHAR(64),
    token_reset_expira   DATETIME,
    created_at           TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

-- ─────────────────────────────────────────
--  NOTIFICACIONES
-- ─────────────────────────────────────────
CREATE TABLE Notificaciones (
    id      INT          NOT NULL AUTO_INCREMENT,
    mensaje VARCHAR(200),
    fecha   DATE,
    leido   TINYINT,
    PRIMARY KEY (id)
);

-- ─────────────────────────────────────────
--  MATERIAS  (módulo: Hector)
--  Se agregan horaInicio y horaFin para detección
--  automática de la clase activa por horario.
-- ─────────────────────────────────────────
CREATE TABLE Materias (
    id         INT         NOT NULL AUTO_INCREMENT,
    nombre     VARCHAR(80),
    docente    VARCHAR(120),
    grado      TINYINT,
    horaInicio TIME,
    horaFin    TIME,
    PRIMARY KEY (id)
);

-- ─────────────────────────────────────────
--  ALUMNOS  (módulos: Hector, Araceli, Arlyn)
--  Se agregan numero_control, grupo y foto
--  requeridos por los módulos de consultas y reportes.
-- ─────────────────────────────────────────
CREATE TABLE Alumnos (
    id             INT          NOT NULL AUTO_INCREMENT,
    idMateria      INT          NOT NULL,
    nombre         VARCHAR(80),
    matricula      VARCHAR(20),
    numero_control VARCHAR(20),
    grupo          VARCHAR(20),
    foto           VARCHAR(200),
    PRIMARY KEY (id),
    FOREIGN KEY (idMateria) REFERENCES Materias(id)
);

-- ─────────────────────────────────────────
--  ASISTENCIAS  (módulos: Hector, Araceli, Arlyn)
--  Las FK usan los nombres idAlumno e idMateria
--  conforme al esquema original.
-- ─────────────────────────────────────────
CREATE TABLE Asistencias (
    id        INT         NOT NULL AUTO_INCREMENT,
    idMateria INT         NOT NULL,
    idAlumno  INT         NOT NULL,
    estado    VARCHAR(80),
    fecha     DATE,
    hora      TIME,
    PRIMARY KEY (id),
    FOREIGN KEY (idMateria) REFERENCES Materias(id),
    FOREIGN KEY (idAlumno)  REFERENCES Alumnos(id)
);
