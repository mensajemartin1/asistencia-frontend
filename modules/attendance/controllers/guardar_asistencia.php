<?php

require_once __DIR__ . '/../../../config/database.php';
$conn = getConnection();

$matricula = $_POST['matricula'] ?? '';
$estado    = $_POST['estado']    ?? '';

date_default_timezone_set("America/Mexico_City");

$fecha = date("Y-m-d");
$hora  = date("H:i:s");

// ── Buscar alumno por matrícula ────────────────────────────────────────────
$stmt = $conn->prepare("SELECT id FROM Alumnos WHERE matricula = ?");
$stmt->bind_param("s", $matricula);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "Alumno no encontrado";
    exit;
}

$idAlumno = $result->fetch_assoc()['id'];
$stmt->close();

// ── Detectar materia activa por horario ───────────────────────────────────
$stmt = $conn->prepare("SELECT id FROM Materias WHERE ? BETWEEN horaInicio AND horaFin");
$stmt->bind_param("s", $hora);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "No hay clase en este horario";
    exit;
}

$idMateria = $result->fetch_assoc()['id'];
$stmt->close();

// ── Guardar asistencia ────────────────────────────────────────────────────
$stmt = $conn->prepare(
    "INSERT INTO Asistencias (idAlumno, idMateria, estado, fecha, hora)
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->bind_param("iisss", $idAlumno, $idMateria, $estado, $fecha, $hora);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al registrar";
}

$stmt->close();
