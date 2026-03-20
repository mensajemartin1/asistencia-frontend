<?php

require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$matricula = trim($_POST['matricula'] ?? '');
$estadoIn  = strtolower(trim($_POST['estado'] ?? 'presente'));

$stateMap = [
    'presente' => 'presente',
    'retardo'  => 'retardo',
    'falta'    => 'falta',
    'ausente'  => 'falta',
];
$estado = $stateMap[$estadoIn] ?? 'presente';

if ($matricula === '') {
    echo 'Matrícula requerida';
    exit;
}

date_default_timezone_set("America/Mexico_City");

$fecha = date("Y-m-d");
$hora  = date("H:i:s");

// ── Buscar alumno por matrícula ────────────────────────────────────────────
$stmt = $conn->prepare("SELECT id, idGrupo FROM Alumnos WHERE matricula = ? AND activo = 1 LIMIT 1");
$stmt->bind_param("s", $matricula);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "Alumno no encontrado";
    exit;
}

$alumno   = $result->fetch_assoc();
$idAlumno = (int)$alumno['id'];
$idGrupo  = $alumno['idGrupo'] !== null ? (int)$alumno['idGrupo'] : null;
$stmt->close();

// ── Detectar grupo-materia activa por horario ──────────────────────────────
if ($idGrupo !== null) {
    $stmt = $conn->prepare(
        "SELECT id
         FROM GruposMaterias
         WHERE activo = 1
           AND idGrupo = ?
           AND ? BETWEEN horaInicio AND horaFin
         ORDER BY id
         LIMIT 1"
    );
    $stmt->bind_param("is", $idGrupo, $hora);
} else {
    $stmt = $conn->prepare(
        "SELECT id
         FROM GruposMaterias
         WHERE activo = 1
           AND ? BETWEEN horaInicio AND horaFin
         ORDER BY id
         LIMIT 1"
    );
    $stmt->bind_param("s", $hora);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "No hay clase en este horario";
    exit;
}

$idGrupoMateria = (int)$result->fetch_assoc()['id'];
$stmt->close();

// ── Guardar asistencia ────────────────────────────────────────────────────
$registradoPor = (int)($_SESSION['user_id'] ?? 0);

$stmt = $conn->prepare(
    "INSERT INTO Asistencias (idGrupoMateria, idAlumno, estado, fecha, hora, registrado_por)
     VALUES (?, ?, ?, ?, ?, ?)
     ON DUPLICATE KEY UPDATE
        estado = VALUES(estado),
        hora = VALUES(hora),
        registrado_por = VALUES(registrado_por)"
);
$stmt->bind_param("iisssi", $idGrupoMateria, $idAlumno, $estado, $fecha, $hora, $registradoPor);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al registrar";
}

$stmt->close();
