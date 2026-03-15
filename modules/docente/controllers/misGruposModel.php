<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin','docente']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn      = getConnection();
$idDocente = (int)$_SESSION['user_id'];
$accion    = $_GET['accion'] ?? 'grupos';

// Grupos y materias del docente
if ($accion === 'grupos') {
    $stmt = $conn->prepare(
        "SELECT gm.id AS idGM, g.nombre AS grupo, g.campus, g.carrera, g.semestre,
                m.nombre AS materia,
                gm.horaInicio, gm.horaFin, gm.dias, gm.ciclo,
                COUNT(a.id) AS total_alumnos
         FROM GruposMaterias gm
         JOIN Grupos   g ON g.id = gm.idGrupo
         JOIN Materias m ON m.id = gm.idMateria
         LEFT JOIN Alumnos a ON a.idGrupo = gm.idGrupo AND a.activo=1
         WHERE gm.idDocente = ? AND gm.activo = 1
         GROUP BY gm.id
         ORDER BY g.campus, gm.horaInicio"
    );
    $stmt->bind_param('i', $idDocente);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

// Clase activa por hora actual
if ($accion === 'clase_activa') {
    $hora = date('H:i:s');
    $dia  = strtoupper(substr(date('D'), 0, 1)); // L,M,X,J,V,S,D
    // Mapear PHP day abbr a letras usadas en dias column
    $map = ['Mon'=>'L','Tue'=>'M','Wed'=>'X','Thu'=>'J','Fri'=>'V','Sat'=>'S','Sun'=>'D'];
    $letraHoy = $map[date('D')] ?? '';

    $stmt = $conn->prepare(
        "SELECT gm.id AS idGM, g.nombre AS grupo, g.campus, m.nombre AS materia,
                gm.horaInicio, gm.horaFin
         FROM GruposMaterias gm
         JOIN Grupos   g ON g.id = gm.idGrupo
         JOIN Materias m ON m.id = gm.idMateria
         WHERE gm.idDocente = ? AND gm.activo = 1
           AND ? BETWEEN gm.horaInicio AND gm.horaFin
         LIMIT 1"
    );
    $stmt->bind_param('is', $idDocente, $hora);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode($row ?: null);
    exit;
}

echo json_encode([]);
