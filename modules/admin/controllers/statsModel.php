<?php
require_once __DIR__ . '/../../../config/auth_check.php';
require_once __DIR__ . '/../../../config/role_check.php';
requireRole(['admin']);
require_once __DIR__ . '/../../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
$tipo = $_GET['tipo'] ?? 'global';

if ($tipo === 'global') {
    $stats = [];

    $queries = [
        'alumnos'  => "SELECT COUNT(*) FROM Alumnos WHERE activo=1",
        'docentes' => "SELECT COUNT(*) FROM Usuarios WHERE rol='docente' AND estado='activo'",
        'grupos'   => "SELECT COUNT(*) FROM Grupos WHERE activo=1",
        'materias' => "SELECT COUNT(*) FROM Materias WHERE activa=1",
        'hoy'      => "SELECT COUNT(*) FROM Asistencias WHERE fecha=CURDATE() AND estado='presente'",
    ];

    foreach ($queries as $key => $sql) {
        $r = $conn->query($sql);
        $stats[$key] = $r ? (int)$r->fetch_row()[0] : 0;
    }
    echo json_encode($stats);
    exit;
}

if ($tipo === 'asistencia_semanal') {
    $stmt = $conn->prepare(
        "SELECT DATE_FORMAT(fecha,'%d %b') AS dia,
                SUM(estado='presente') AS presentes,
                SUM(estado='falta')    AS faltas,
                SUM(estado='retardo')  AS retardos
         FROM Asistencias
         WHERE fecha >= CURDATE() - INTERVAL 6 DAY
         GROUP BY fecha
         ORDER BY fecha"
    );
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($tipo === 'asistencia_grupos') {
    $stmt = $conn->prepare(
        "SELECT g.nombre AS grupo,
                ROUND(100 * SUM(a.estado='presente') / COUNT(a.id), 1) AS pct
         FROM Asistencias a
         JOIN GruposMaterias gm ON gm.id = a.idGrupoMateria
         JOIN Grupos g ON g.id = gm.idGrupo
         WHERE a.fecha >= CURDATE() - INTERVAL 30 DAY
         GROUP BY g.id
         ORDER BY pct DESC
         LIMIT 8"
    );
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

echo json_encode([]);
